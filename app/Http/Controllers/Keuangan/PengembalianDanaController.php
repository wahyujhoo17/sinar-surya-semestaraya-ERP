<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\PengembalianDana;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Kas;
use App\Models\RekeningBank;
use App\Models\TransaksiKas;
use App\Models\TransaksiBank;
use App\Models\ReturPembelian;
use App\Models\PembayaranHutang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PengembalianDanaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengembalian = PengembalianDana::with(['supplier', 'purchaseOrder'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        $suppliers = Supplier::orderBy('nama')->get();

        return view('keuangan.pengembalian_dana.index', compact('pengembalian', 'suppliers'));
    }

    /**
     * Get data for AJAX request with filters
     */
    public function data(Request $request)
    {
        // Start with a base query
        $query = PengembalianDana::with(['supplier', 'purchaseOrder', 'kas', 'rekeningBank'])
            ->orderBy('tanggal', 'desc');

        // Apply date range filter
        if ($request->has('date_range') && !empty($request->date_range)) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) {
                $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            }
        }

        // Apply supplier filter
        if ($request->has('supplier_id') && !empty($request->supplier_id)) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Apply search query filter
        if ($request->has('search_query') && !empty($request->search_query)) {
            $search = $request->search_query;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($subq) use ($search) {
                        $subq->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('purchaseOrder', function ($subq) use ($search) {
                        $subq->where('nomor', 'like', "%{$search}%");
                    });
            });
        }

        // Paginate the results
        $pengembalian = $query->paginate(10);

        // Transform data for the response
        $data = $pengembalian->map(function ($refund) {
            $akun = '-';
            if ($refund->metode_penerimaan == 'kas') {
                $akun = $refund->kas->nama ?? '-';
            } elseif ($refund->metode_penerimaan == 'bank') {
                $akun = ($refund->rekeningBank->nama_bank ?? '-') . ' - ' . ($refund->rekeningBank->nomor ?? '-');
            }

            return [
                'id' => $refund->id,
                'nomor' => $refund->nomor,
                'tanggal' => $refund->tanggal,
                'tanggal_formatted' => \Carbon\Carbon::parse($refund->tanggal)->format('d/m/Y'),
                'supplier_id' => $refund->supplier_id,
                'supplier_nama' => $refund->supplier->nama ?? 'Unknown',
                'purchase_order_id' => $refund->purchase_order_id,
                'purchase_order_nomor' => $refund->purchaseOrder->nomor ?? 'Unknown',
                'jumlah' => $refund->jumlah,
                'jumlah_formatted' => 'Rp ' . number_format($refund->jumlah, 0, ',', '.'),
                'metode_penerimaan' => $refund->metode_penerimaan,
                'akun' => $akun,
                'detail_url' => route('keuangan.pengembalian-dana.show', $refund->id),
                'print_url' => route('keuangan.pengembalian-dana.print', $refund->id),
            ];
        });

        // Prepare the response
        return response()->json([
            'data' => $data,
            'pagination' => $pengembalian->links()->toHtml(),
            'from' => $pengembalian->firstItem() ?? 0,
            'to' => $pengembalian->lastItem() ?? 0,
            'total' => $pengembalian->total(),
            'current_page' => $pengembalian->currentPage(),
            'last_page' => $pengembalian->lastPage(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(Request $request)
    {
        $po = null;
        $kelebihanBayar = 0;
        $suppliers = Supplier::orderBy('nama')->get();
        $kasAccounts = Kas::all();
        $bankAccounts = RekeningBank::all();

        if ($request->has('po_id')) {
            $po = PurchaseOrder::with(['supplier', 'details', 'details.produk'])
                ->findOrFail($request->po_id);

            // Pastikan PO ini memiliki status kelebihan_bayar
            if ($po->status_pembayaran !== 'kelebihan_bayar') {
                return redirect()->back()->with('error', 'Purchase Order ini tidak memiliki kelebihan pembayaran.');
            }

            $kelebihanBayar = $po->kelebihan_bayar;
        }

        // Generate a unique payment number
        $today = date('Ymd');
        $prefix = 'RTR-' . $today . '-';

        // Find the last refund number for today
        $lastRefund = PengembalianDana::where('nomor', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 0;
        if ($lastRefund) {
            // Extract number from format RTR-YYYYMMDD-XXXX
            $parts = explode('-', $lastRefund->nomor);
            if (isset($parts[2])) {
                $lastNumber = (int)$parts[2];
            }
        }

        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $refundNumber = $prefix . $newNumber;

        return view('keuangan.pengembalian_dana.create', [
            'po' => $po,
            'kelebihanBayar' => $kelebihanBayar,
            'suppliers' => $suppliers,
            'refundNumber' => $refundNumber,
            'kasAccounts' => $kasAccounts,
            'bankAccounts' => $bankAccounts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate([
            'nomor' => 'nullable|unique:pengembalian_dana,nomor',
            'tanggal' => 'required|date',
            'purchase_order_id' => 'required|exists:purchase_order,id',
            'supplier_id' => 'required|exists:supplier,id',
            'jumlah' => 'required|numeric|min:1',
            'metode_penerimaan' => 'required|in:kas,bank',
            'no_referensi' => 'nullable|string',
            'catatan' => 'nullable|string',
            'kas_id' => 'nullable|exists:kas,id',
            'rekening_id' => 'nullable|exists:rekening_bank,id'
        ]);

        // dd($validated , $request->all());

        // Additional validation: ensure jumlah is not greater than kelebihan_bayar
        $po = PurchaseOrder::findOrFail($request->purchase_order_id);


        if ($request->jumlah > $po->kelebihan_bayar) {
            return redirect()->back()->withInput()->with('error', 'Jumlah pengembalian tidak boleh melebihi kelebihan pembayaran (Rp ' . number_format($po->kelebihan_bayar, 0, ',', '.') . ')');
        }

        DB::beginTransaction();

        try {
            // Generate a fresh refund number based on the refund date
            $refundDate = date('Ymd', strtotime($request->tanggal));
            $prefix = 'RTR-' . $refundDate . '-';

            // Find the last refund number for this date
            $lastRefund = PengembalianDana::where('nomor', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();

            $lastNumber = 0;
            if ($lastRefund) {
                // Extract number from format RTR-YYYYMMDD-XXXX
                $parts = explode('-', $lastRefund->nomor);
                if (isset($parts[2])) {
                    $lastNumber = (int)$parts[2];
                }
            }

            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $validated['nomor'] = $prefix . $newNumber;

            // Add current user_id to data
            $validated['user_id'] = Auth::id();

            // Create refund record
            $refund = PengembalianDana::create($validated);

            // Record transaction based on refund method
            if ($request->metode_penerimaan === 'kas') {
                // Update the kas balance
                $kas = Kas::findOrFail($request->kas_id);
                $kas->saldo += $request->jumlah;  // Tambah saldo kas
                $kas->save();

                // Get PO number
                $po = PurchaseOrder::findOrFail($request->purchase_order_id);
                $supplierName = Supplier::find($request->supplier_id)->nama;

                // Create transaction record
                TransaksiKas::create([
                    'tanggal' => $request->tanggal,
                    'kas_id' => $request->kas_id,
                    'jenis' => 'masuk',
                    'jumlah' => $request->jumlah,
                    'keterangan' => 'Pengembalian dana PO #' . $po->nomor . ' dari ' . $supplierName,
                    'no_bukti' => $validated['nomor'],
                    'related_id' => $refund->id,
                    'related_type' => PengembalianDana::class,
                    'user_id' => Auth::id()
                ]);
            } elseif ($request->metode_penerimaan === 'bank') {
                // Update the bank account balance
                $rekening = RekeningBank::findOrFail($request->rekening_id);
                $rekening->saldo += $request->jumlah;  // Tambah saldo rekening
                $rekening->save();

                // Get PO number
                $po = PurchaseOrder::findOrFail($request->purchase_order_id);
                $supplierName = Supplier::find($request->supplier_id)->nama;

                // Create transaction record
                TransaksiBank::create([
                    'tanggal' => $request->tanggal,
                    'rekening_id' => $request->rekening_id,
                    'jenis' => 'masuk',
                    'jumlah' => $request->jumlah,
                    'keterangan' => 'Pengembalian dana PO #' . $po->nomor . ' dari ' . $supplierName,
                    'no_referensi' => $request->no_referensi,
                    'related_id' => $refund->id,
                    'related_type' => PengembalianDana::class,
                    'user_id' => Auth::id()
                ]);
            }

            // Update PO
            $po = PurchaseOrder::find($request->purchase_order_id);
            $po->kelebihan_bayar -= $request->jumlah;

            // If kelebihan_bayar is now zero or less, update status
            if ($po->kelebihan_bayar <= 0) {
                $po->status_pembayaran = 'lunas';
                $po->kelebihan_bayar = 0; // Set to zero to prevent negative values
            }

            $po->save();

            DB::commit();

            return redirect()
                ->route('keuangan.pengembalian-dana.show', $refund->id)
                ->with('success', 'Pengembalian dana berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $refund = PengembalianDana::with(['supplier', 'purchaseOrder', 'user', 'kas', 'rekeningBank'])
            ->findOrFail($id);

        return view('keuangan.pengembalian_dana.show', compact('refund'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $refund = PengembalianDana::with(['supplier', 'purchaseOrder'])
            ->findOrFail($id);

        $suppliers = Supplier::orderBy('nama')->get();
        $kasAccounts = Kas::all();
        $bankAccounts = RekeningBank::all();

        return view('keuangan.pengembalian_dana.edit', [
            'refund' => $refund,
            'suppliers' => $suppliers,
            'kasAccounts' => $kasAccounts,
            'bankAccounts' => $bankAccounts
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $refund = PengembalianDana::findOrFail($id);

        $validated = $request->validate([
            'nomor' => 'required|unique:pengembalian_dana,nomor,' . $id,
            'tanggal' => 'required|date',
            'purchase_order_id' => 'required|exists:purchase_order,id',
            'supplier_id' => 'required|exists:supplier,id',
            'jumlah' => 'required|numeric|min:1',
            'metode_penerimaan' => 'required|in:kas,bank',
            'no_referensi' => 'nullable|string',
            'catatan' => 'nullable|string',
            'kas_id' => 'required_if:metode_penerimaan,kas|exists:kas,id',
            'rekening_id' => 'required_if:metode_penerimaan,bank|exists:rekening_bank,id'
        ]);

        // Additional validation: check if new amount is valid
        $po = PurchaseOrder::findOrFail($request->purchase_order_id);
        $currentPO = PurchaseOrder::findOrFail($refund->purchase_order_id);

        // If the PO has been changed or the amount has increased
        if (($refund->purchase_order_id != $request->purchase_order_id && $request->jumlah > $po->kelebihan_bayar) ||
            ($refund->purchase_order_id == $request->purchase_order_id && $request->jumlah > ($currentPO->kelebihan_bayar + $refund->jumlah))
        ) {
            $availableAmount = ($refund->purchase_order_id == $request->purchase_order_id) ?
                $currentPO->kelebihan_bayar + $refund->jumlah : $po->kelebihan_bayar;

            return redirect()->back()->withInput()->with(
                'error',
                'Jumlah pengembalian tidak boleh melebihi kelebihan pembayaran (Rp ' . number_format($availableAmount, 0, ',', '.') . ')'
            );
        }

        DB::beginTransaction();

        try {
            // Restore the original PO kelebihan_bayar before applying changes
            $po = PurchaseOrder::find($refund->purchase_order_id);
            $po->kelebihan_bayar += $refund->jumlah; // Add back the original refund amount

            // Find related transactions and restore the original balance first
            if ($refund->metode_penerimaan === 'kas') {
                $transaksi = TransaksiKas::where('related_id', $refund->id)
                    ->where('related_type', PengembalianDana::class)
                    ->first();

                if ($transaksi) {
                    $kas = Kas::findOrFail($transaksi->kas_id);
                    $kas->saldo -= $refund->jumlah; // Remove the money from the cash balance
                    $kas->save();

                    // Delete the old transaction
                    $transaksi->delete();
                }
            } elseif ($refund->metode_penerimaan === 'bank') {
                $transaksi = TransaksiBank::where('related_id', $refund->id)
                    ->where('related_type', PengembalianDana::class)
                    ->first();

                if ($transaksi) {
                    $rekening = RekeningBank::findOrFail($transaksi->rekening_id);
                    $rekening->saldo -= $refund->jumlah; // Remove the money from the bank account
                    $rekening->save();

                    // Delete the old transaction
                    $transaksi->delete();
                }
            }

            // Update refund
            $refund->update($validated);

            // Create new transaction based on updated refund method
            if ($request->metode_penerimaan === 'kas') {
                // Update the kas balance
                $kas = Kas::findOrFail($request->kas_id);
                $kas->saldo += $request->jumlah;
                $kas->save();

                // Get PO number
                $po = PurchaseOrder::findOrFail($request->purchase_order_id);
                $supplierName = Supplier::find($request->supplier_id)->nama;

                TransaksiKas::create([
                    'tanggal' => $request->tanggal,
                    'kas_id' => $request->kas_id,
                    'jenis' => 'masuk',
                    'jumlah' => $request->jumlah,
                    'keterangan' => 'Pengembalian dana PO #' . $po->nomor . ' dari ' . $supplierName,
                    'no_bukti' => $request->nomor,
                    'related_id' => $refund->id,
                    'related_type' => PengembalianDana::class,
                    'user_id' => Auth::id()
                ]);
            } elseif ($request->metode_penerimaan === 'bank') {
                // Update the bank account balance
                $rekening = RekeningBank::findOrFail($request->rekening_id);
                $rekening->saldo += $request->jumlah;
                $rekening->save();

                // Get PO number
                $po = PurchaseOrder::findOrFail($request->purchase_order_id);
                $supplierName = Supplier::find($request->supplier_id)->nama;

                TransaksiBank::create([
                    'tanggal' => $request->tanggal,
                    'rekening_id' => $request->rekening_id,
                    'jenis' => 'masuk',
                    'jumlah' => $request->jumlah,
                    'keterangan' => 'Pengembalian dana PO #' . $po->nomor . ' dari ' . $supplierName,
                    'no_referensi' => $request->no_referensi,
                    'related_id' => $refund->id,
                    'related_type' => PengembalianDana::class,
                    'user_id' => Auth::id()
                ]);
            }

            // Update the PO with the new refund amount
            $po->kelebihan_bayar -= $request->jumlah;

            // If kelebihan_bayar is now zero or less, update status
            if ($po->kelebihan_bayar <= 0) {
                $po->status_pembayaran = 'lunas';
                $po->kelebihan_bayar = 0; // Set to zero to prevent negative values
            } else {
                $po->status_pembayaran = 'kelebihan_bayar';
            }

            $po->save();

            DB::commit();

            return redirect()
                ->route('keuangan.pengembalian-dana.show', $refund->id)
                ->with('success', 'Pengembalian dana berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $refund = PengembalianDana::findOrFail($id);
        $po_id = $refund->purchase_order_id;

        DB::beginTransaction();

        try {
            // Restore the PO kelebihan_bayar before deleting
            $po = PurchaseOrder::findOrFail($po_id);
            $po->kelebihan_bayar += $refund->jumlah;
            $po->status_pembayaran = 'kelebihan_bayar'; // Restore to kelebihan_bayar status
            $po->save();

            // Restore the cash or bank balance before deleting the refund
            if ($refund->metode_penerimaan === 'kas') {
                $transaksi = TransaksiKas::where('related_id', $refund->id)
                    ->where('related_type', PengembalianDana::class)
                    ->first();

                if ($transaksi) {
                    $kas = Kas::findOrFail($transaksi->kas_id);
                    $kas->saldo -= $refund->jumlah; // Remove the money from the cash balance
                    $kas->save();

                    // Delete the transaction
                    $transaksi->delete();
                }
            } elseif ($refund->metode_penerimaan === 'bank') {
                $transaksi = TransaksiBank::where('related_id', $refund->id)
                    ->where('related_type', PengembalianDana::class)
                    ->first();

                if ($transaksi) {
                    $rekening = RekeningBank::findOrFail($transaksi->rekening_id);
                    $rekening->saldo -= $refund->jumlah; // Remove the money from the bank account
                    $rekening->save();

                    // Delete the transaction
                    $transaksi->delete();
                }
            }

            // Delete refund
            $refund->delete();

            DB::commit();

            return redirect()
                ->route('keuangan.pengembalian-dana.index')
                ->with('success', 'Pengembalian dana berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Print the refund receipt
     */
    public function print($id)
    {
        $refund = PengembalianDana::with(['supplier', 'purchaseOrder', 'user', 'kas', 'rekeningBank'])
            ->findOrFail($id);

        $pdf = PDF::loadView('keuangan.pengembalian_dana.print', compact('refund'));

        return $pdf->stream('pengembalian_dana_' . $refund->nomor . '.pdf');
    }

    /**
     * Get Purchase Order data for AJAX request
     */
    public function getPurchaseOrderData(Request $request)
    {
        $poId = $request->input('po_id');
        if (!$poId) {
            return response()->json([
                'success' => false,
                'message' => 'ID Purchase Order tidak ditemukan'
            ]);
        }

        $po = PurchaseOrder::with('supplier')->find($poId);
        if (!$po) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase Order tidak ditemukan'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'kelebihan_bayar' => $po->kelebihan_bayar,
                'supplier_id' => $po->supplier_id,
                'nomor_po' => $po->nomor,
                'tanggal_po' => $po->tanggal,
                'supplier_nama' => $po->supplier->nama ?? ''
            ]
        ]);
    }
}
