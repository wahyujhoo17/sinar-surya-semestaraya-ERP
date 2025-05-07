<?php

namespace App\Http\Controllers\Pembelian;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PenerimaanBarang;
use App\Models\PenerimaanBarangDetail;
use App\Models\Gudang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanBarangController extends Controller
{
    /**
     * Tampilkan daftar penerimaan barang
     */
    public function index(Request $request)
    {
        $query = PenerimaanBarang::with(['purchaseOrder', 'supplier', 'gudang', 'user'])
            ->orderBy('tanggal', 'desc');

        // --- Bagian Filter --- 
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status', 'draft'), // Default status
            'date_filter' => $request->input('date_filter'),
            'date_start' => $request->input('date_start'),
            'date_end' => $request->input('date_end'),
            'supplier_id' => $request->input('supplier_id'),
            'purchase_order_id' => $request->input('purchase_order_id'),
        ];

        if ($filters['search']) {
            $query->where(function ($q) use ($filters) {
                $q->where('nomor', 'like', '%' . $filters['search'] . '%')
                    ->orWhereHas('purchaseOrder', function ($poQuery) use ($filters) {
                        $poQuery->where('nomor', 'like', '%' . $filters['search'] . '%');
                    })
                    ->orWhereHas('supplier', function ($sQuery) use ($filters) {
                        $sQuery->where('nama', 'like', '%' . $filters['search'] . '%');
                    });
            });
        }
        if ($filters['status']) {
            $query->where('status', $filters['status']);
        }
        if ($filters['supplier_id']) {
            $query->where('supplier_id', $filters['supplier_id']);
        }
        if ($filters['purchase_order_id']) {
            $query->whereHas('purchaseOrder', function ($poQuery) use ($filters) {
                $poQuery->where('nomor', 'like', '%' . $filters['purchase_order_id'] . '%');
            });
        }

        // Implement date filtering logic
        if ($filters['date_filter']) {
            switch ($filters['date_filter']) {
                case 'today':
                    $query->whereDate('tanggal', today());
                    break;
                case 'this_week':
                    $startOfWeek = now()->startOfWeek();
                    $endOfWeek = now()->endOfWeek();
                    $query->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);
                    break;
                case 'this_month':
                    $startOfMonth = now()->startOfMonth();
                    $endOfMonth = now()->endOfMonth();
                    $query->whereBetween('tanggal', [$startOfMonth, $endOfMonth]);
                    break;
                case 'range':
                    if ($filters['date_start'] && $filters['date_end']) {
                        $query->whereBetween('tanggal', [$filters['date_start'], $filters['date_end']]);
                    } elseif ($filters['date_start']) {
                        $query->whereDate('tanggal', '>=', $filters['date_start']);
                    } elseif ($filters['date_end']) {
                        $query->whereDate('tanggal', '<=', $filters['date_end']);
                    }
                    break;
            }
        }

        $penerimaanBarangs = $query->paginate(15)->appends($filters); // appends agar filter terbawa di pagination

        if ($request->ajax()) {
            $tableHtml = view('pembelian.Penerimaan_barang._table', ['penerimaanBarangs' => $penerimaanBarangs])->render();
            $paginationHtml = view('pembelian.Penerimaan_barang._pagination', ['penerimaanBarangs' => $penerimaanBarangs])->render();
            return response()->json([
                'table_html' => $tableHtml,
                'pagination_html' => $paginationHtml,
            ]);
        }

        // Data untuk view utama (non-AJAX)
        $statusCounts = PenerimaanBarang::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $suppliers = Supplier::orderBy('nama')->get();

        return view('pembelian.Penerimaan_barang.index', array_merge([
            'penerimaanBarangs' => $penerimaanBarangs, // Untuk initial load jika ada (meski akan dioverwrite AJAX)
            'statusCounts' => $statusCounts,
            'suppliers' => $suppliers,
        ], $filters)); // Kirim semua filter ke view untuk inisialisasi Alpine.js
    }

    /**
     * Tampilkan form penerimaan barang (pilih PO yang belum selesai)
     */
    public function create(Request $request)
    {
        $poQuery = PurchaseOrder::with(['supplier', 'details'])
            ->where('status', '!=', 'selesai');
        if ($request->filled('q')) {
            $poQuery->where('nomor', 'like', '%' . $request->q . '%');
        }
        $purchaseOrders = $poQuery->orderBy('created_at', 'desc')->limit(10)->get();
        $gudangs = Gudang::all();
        return view('pembelian.Penerimaan_barang.create', compact('purchaseOrders', 'gudangs'));
    }

    /**
     * Simpan penerimaan barang
     */
    public function store(Request $request)
    {
        $request->validate([
            'po_id' => 'required|exists:purchase_orders,id',
            'gudang_id' => 'required|exists:gudang,id',
            'tanggal' => 'required|date',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:purchase_order_details,id',
            'items.*.qty_diterima' => 'required|numeric|min:0',
        ]);
        DB::beginTransaction();
        try {
            $po = PurchaseOrder::with('details')->findOrFail($request->po_id);
            $nomor = 'GR-' . date('Ymd') . '-' . str_pad((PenerimaanBarang::max('id') + 1), 4, '0', STR_PAD_LEFT);
            $penerimaan = PenerimaanBarang::create([
                'nomor' => $nomor,
                'tanggal' => $request->tanggal,
                'po_id' => $po->id,
                'supplier_id' => $po->supplier_id,
                'user_id' => auth()->id(),
                'gudang_id' => $request->gudang_id,
                'nomor_surat_jalan' => $request->nomor_surat_jalan,
                'tanggal_surat_jalan' => $request->tanggal_surat_jalan,
                'catatan' => $request->catatan,
                'status' => 'draft',
            ]);
            foreach ($request->items as $item) {
                $poDetail = $po->details->where('id', $item['id'])->first();
                $qtySisa = $poDetail->qty - $poDetail->qty_diterima;
                if ($item['qty_diterima'] > $qtySisa) {
                    throw new \Exception('Qty diterima tidak boleh melebihi sisa PO.');
                }
                PenerimaanBarangDetail::create([
                    'penerimaan_id' => $penerimaan->id,
                    'po_detail_id' => $poDetail->id,
                    'produk_id' => $poDetail->produk_id,
                    'qty_dipesan' => $poDetail->qty,
                    'qty_diterima' => $item['qty_diterima'],
                    'catatan' => $item['catatan'] ?? null,
                ]);
                // Update qty diterima di PO detail
                $poDetail->qty_diterima += $item['qty_diterima'];
                $poDetail->save();
                // Update stok gudang & inventory
                // ...panggil helper update stok sesuai modul inventory Anda...
            }
            // Update status PO jika semua item sudah diterima
            $allReceived = $po->details->every(fn($d) => $d->qty_diterima >= $d->qty);
            $po->status = $allReceived ? 'selesai' : 'parsial';
            $po->save();
            DB::commit();
            return redirect()->route('pembelian.penerimaan-barang.index')->with('success', 'Penerimaan barang berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Tampilkan detail penerimaan barang
     */
    public function show($id)
    {
        $penerimaan = PenerimaanBarang::with(['purchaseOrder', 'supplier', 'gudang', 'user', 'details.produk'])->findOrFail($id);
        return view('pembelian.Penerimaan_barang.show', compact('penerimaan'));
    }

    /**
     * Tampilkan form edit penerimaan barang
     */
    public function edit($id)
    {
        $penerimaan = PenerimaanBarang::with(['details', 'purchaseOrder', 'gudang'])->findOrFail($id);
        $gudangs = Gudang::all();
        return view('pembelian.Penerimaan_barang.edit', compact('penerimaan', 'gudangs'));
    }

    /**
     * Update penerimaan barang
     */
    public function update(Request $request, $id)
    {
        $penerimaan = PenerimaanBarang::findOrFail($id);
        $request->validate([
            'gudang_id' => 'required|exists:gudang,id',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
        ]);
        $penerimaan->update([
            'gudang_id' => $request->gudang_id,
            'tanggal' => $request->tanggal,
            'catatan' => $request->catatan,
        ]);
        return redirect()->route('pembelian.penerimaan-barang.index')->with('success', 'Data berhasil diupdate.');
    }

    /**
     * Hapus penerimaan barang
     */
    public function destroy($id)
    {
        $penerimaan = PenerimaanBarang::findOrFail($id);
        $penerimaan->delete();
        return redirect()->route('pembelian.penerimaan-barang.index')->with('success', 'Data berhasil dihapus.');
    }
}
