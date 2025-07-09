<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\WorkOrderMaterial;
use App\Models\RiwayatStok;
use App\Models\StokProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PengembalianMaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:work_order.create')->only(['create', 'store']);
    }

    /**
     * Show the form for creating a new pengembalian material.
     *
     * @param  int  $workOrderId
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function create($workOrderId)
    {
        $workOrder = WorkOrder::with(['materials.produk', 'materials.satuan', 'gudangProduksi'])
            ->findOrFail($workOrderId);

        // Ensure the work order is in the QC Passed status
        if ($workOrder->status !== 'qc_passed') {
            return redirect()->route('produksi.work-order.show', $workOrder->id)
                ->with('error', 'Pengembalian material hanya dapat dilakukan setelah produk lulus QC.');
        }

        // Get materials that still have remaining quantity (not fully consumed)
        $materials = $workOrder->materials()
            ->whereRaw('quantity > quantity_terpakai')
            ->with(['produk', 'satuan'])
            ->get();

        return view('produksi.pengembalian-material.create', compact('workOrder', 'materials'));
    }

    /**
     * Store a newly created pengembalian material in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $workOrderId
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $workOrderId)
    {
        $workOrder = WorkOrder::findOrFail($workOrderId);

        // Validate the request
        $request->validate([
            'material_id' => 'required|array',
            'material_id.*' => 'exists:work_order_materials,id',
            'quantity_return' => 'required|array',
            'quantity_return.*' => 'numeric|min:0',
            'catatan' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $totalMaterialsReturned = 0;

            foreach ($request->material_id as $index => $materialId) {
                $material = WorkOrderMaterial::findOrFail($materialId);
                $quantityReturn = $request->quantity_return[$index];

                // Skip if no quantity to return
                if ($quantityReturn <= 0) {
                    continue;
                }

                // Ensure the return quantity doesn't exceed what's available
                $availableForReturn = $material->quantity - $material->quantity_terpakai;
                if ($quantityReturn > $availableForReturn) {
                    throw new \Exception("Jumlah pengembalian untuk {$material->produk->nama} melebihi jumlah yang tersedia.");
                }

                // Find or create stock record
                $stokProduk = StokProduk::firstOrCreate(
                    [
                        'produk_id' => $material->produk_id,
                        'gudang_id' => $workOrder->gudang_produksi_id
                    ],
                    [
                        'jumlah' => 0
                    ]
                );

                // Record current stock amount
                $jumlahSebelum = $stokProduk->jumlah;

                // Update stock amount
                $stokProduk->jumlah += $quantityReturn;
                $stokProduk->save();

                // Create stock history entry for the returned material
                RiwayatStok::create([
                    'stok_id' => $stokProduk->id,
                    'produk_id' => $material->produk_id,
                    'gudang_id' => $workOrder->gudang_produksi_id,
                    'user_id' => Auth::id(),
                    'jumlah_sebelum' => $jumlahSebelum,
                    'jumlah_perubahan' => $quantityReturn,
                    'jumlah_setelah' => $stokProduk->jumlah,
                    'jenis' => 'masuk',
                    'referensi_tipe' => 'produksi',
                    'referensi_id' => $workOrder->nomor,
                    'keterangan' => "Pengembalian material dari Work Order #{$workOrder->nomor}"
                ]);

                // Update the material usage - we're returning it so we don't count it as used
                $material->quantity_terpakai -= $quantityReturn;
                $material->save();

                $totalMaterialsReturned++;
            }

            // Update work order notes if provided
            if ($request->filled('catatan')) {
                $notes = $workOrder->catatan ?? '';
                $workOrder->catatan = $notes . "\n\nPengembalian Material (" . now()->format('d/m/Y H:i') . "):\n" . $request->catatan;
                $workOrder->save();
            }

            DB::commit();

            $message = $totalMaterialsReturned > 0
                ? 'Pengembalian material berhasil disimpan.'
                : 'Tidak ada material yang dikembalikan.';

            return redirect()->route('produksi.work-order.show', $workOrder->id)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}
