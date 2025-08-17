<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\PembayaranHutang;
use App\Models\PurchaseOrder;
use App\Models\ReturPembelian;
use App\Models\Supplier;
use App\Models\Kas;
use App\Models\RekeningBank;
use App\Models\TransaksiKas;
use App\Models\TransaksiBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranHutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = PembayaranHutang::with(['supplier', 'purchaseOrder'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('keuangan.hutang_usaha.create', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $po = null;
        $sisaHutang = 0;
        $suppliers = Supplier::orderBy('nama')->get();
        $kasAccounts = Kas::all();
        $bankAccounts = RekeningBank::all();

        if ($request->has('po_id')) {
            $po = PurchaseOrder::with(['supplier', 'details', 'details.produk'])
                ->findOrFail($request->po_id);

            // Calculate payments made
            $totalPayments = PembayaranHutang::where('purchase_order_id', $po->id)->sum('jumlah');

            // Calculate returns
            $returPembelian = ReturPembelian::where('purchase_order_id', $po->id)
                ->where('status', 'selesai')
                ->with(['details', 'purchaseOrder.details'])
                ->get();

            $totalReturValue = 0;
            foreach ($returPembelian as $retur) {
                $poDetails = $retur->purchaseOrder->details;

                foreach ($retur->details as $returDetail) {
                    // Find matching PO detail for this product
                    $matchingPoDetail = $poDetails->where('produk_id', $returDetail->produk_id)->first();

                    if ($matchingPoDetail) {
                        $totalReturValue += $matchingPoDetail->harga * $returDetail->quantity;
                    }
                }
            }

            $sisaHutang = $po->total - $totalPayments - $totalReturValue;
        }

        // Generate a unique payment number
        $today = date('Ymd');
        $prefix = 'BYR-' . $today . '-';

        // Find the last payment number for today
        $lastPayment = PembayaranHutang::where('nomor', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 0;
        if ($lastPayment) {
            // Extract number from format BYR-YYYYMMDD-XXXX
            $parts = explode('-', $lastPayment->nomor);
            if (isset($parts[2])) {
                $lastNumber = (int)$parts[2];
            }
        }

        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $paymentNumber = $prefix . $newNumber;

        return view('keuangan.hutang_usaha.create', [
            'po' => $po,
            'sisaHutang' => $sisaHutang,
            'suppliers' => $suppliers,
            'paymentNumber' => $paymentNumber,
            'kasAccounts' => $kasAccounts,
            'bankAccounts' => $bankAccounts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor' => 'nullable|unique:pembayaran_hutang,nomor',
            'tanggal' => 'required|date',
            'purchase_order_id' => 'required|exists:purchase_order,id',
            'supplier_id' => 'required|exists:supplier,id',
            'jumlah' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required',
            'no_referensi' => 'nullable|string',
            'catatan' => 'nullable|string',
            'kas_id' => 'required_if:metode_pembayaran,kas',
            'rekening_id' => 'required_if:metode_pembayaran,bank'
        ]);

        DB::beginTransaction();

        try {
            // Generate a fresh payment number based on the payment date
            $paymentDate = date('Ymd', strtotime($request->tanggal));
            $prefix = 'BYR-' . $paymentDate . '-';

            // Find the last payment number for this date
            $lastPayment = PembayaranHutang::where('nomor', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();

            $lastNumber = 0;
            if ($lastPayment) {
                // Extract number from format BYR-YYYYMMDD-XXXX
                $parts = explode('-', $lastPayment->nomor);
                if (isset($parts[2])) {
                    $lastNumber = (int)$parts[2];
                }
            }

            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $validated['nomor'] = $prefix . $newNumber;

            // Add current user_id to data
            $validated['user_id'] = Auth::id();

            // Create payment
            $payment = PembayaranHutang::create($validated);

            // NOTE: Jurnal otomatis dibuat melalui model observer (AutomaticJournalEntry trait)
            // Tidak perlu manual membuat jurnal atau update saldo kas/bank

            // Update PO payment status
            $po = PurchaseOrder::find($request->purchase_order_id);

            // Calculate total payments
            $totalPayments = PembayaranHutang::where('purchase_order_id', $po->id)->sum('jumlah');

            // Calculate returns
            $returPembelian = ReturPembelian::where('purchase_order_id', $po->id)
                ->where('status', 'selesai')
                ->with('details')
                ->get();

            $totalReturValue = 0;
            foreach ($returPembelian as $retur) {
                $poDetails = $retur->purchaseOrder->details;  // Ambil detail PO

                foreach ($retur->details as $returDetail) {
                    // Cari detail PO yang sesuai dengan produk di retur
                    $matchingPoDetail = $poDetails->where('produk_id', $returDetail->produk_id)->first();

                    if ($matchingPoDetail) {
                        $totalReturValue += $matchingPoDetail->harga * $returDetail->quantity;
                    }
                }
            }

            $sisaHutang = $po->total - $totalPayments - $totalReturValue;

            // Update PO status
            if ($sisaHutang < 0) {
                // Kelebihan bayar situation
                $po->status_pembayaran = 'kelebihan_bayar';
                $po->kelebihan_bayar = abs($sisaHutang);
                $po->save();
            } else if ($sisaHutang == 0) {
                $po->status_pembayaran = 'lunas';
                $po->kelebihan_bayar = 0;
                
                // Cek apakah juga sudah diterima, jika ya maka PO selesai
                if ($po->status_penerimaan == 'diterima') {
                    $po->status = 'selesai';
                    $po->save();
                    
                    // Update harga beli rata-rata ketika PO selesai melalui pembayaran
                    \App\Http\Controllers\Pembelian\PurchasingOrderController::updateHargaBeliRataRataFromExternalController($po->id);
                } else {
                    $po->save();
                }
            } else {
                $po->status_pembayaran = 'sebagian';
                $po->kelebihan_bayar = 0;
                $po->save();
            }

            DB::commit();

            return redirect()
                ->route('keuangan.hutang-usaha.history', $po->id)
                ->with('success', 'Pembayaran hutang berhasil dicatat.');
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
        $payment = PembayaranHutang::with(['supplier', 'purchaseOrder', 'user'])
            ->findOrFail($id);

        return view('keuangan.pembayaran_hutang.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payment = PembayaranHutang::with(['supplier', 'purchaseOrder'])
            ->findOrFail($id);

        $suppliers = Supplier::orderBy('nama')->get();

        $po = $payment->purchaseOrder;

        // Calculate payments made, excluding this one
        $totalPayments = PembayaranHutang::where('purchase_order_id', $po->id)
            ->where('id', '!=', $payment->id)
            ->sum('jumlah');

        // Calculate returns
        $returPembelian = ReturPembelian::where('purchase_order_id', $po->id)
            ->where('status', 'selesai')
            ->with(['details', 'purchaseOrder.details'])
            ->get();

        $totalReturValue = 0;
        foreach ($returPembelian as $retur) {
            $poDetails = $retur->purchaseOrder->details;

            foreach ($retur->details as $returDetail) {
                // Find matching PO detail for this product
                $matchingPoDetail = $poDetails->where('produk_id', $returDetail->produk_id)->first();

                if ($matchingPoDetail) {
                    $totalReturValue += $matchingPoDetail->harga * $returDetail->quantity;
                }
            }
        }

        $sisaHutang = $po->total - $totalPayments - $totalReturValue;

        return view('keuangan.pembayaran_hutang.edit', [
            'payment' => $payment,
            'suppliers' => $suppliers,
            'po' => $po,
            'sisaHutang' => $sisaHutang
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = PembayaranHutang::findOrFail($id);

        $validated = $request->validate([
            'nomor' => 'required|unique:pembayaran_hutang,nomor,' . $id,
            'tanggal' => 'required|date',
            'purchase_order_id' => 'required|exists:purchase_order,id',
            'supplier_id' => 'required|exists:supplier,id',
            'jumlah' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required',
            'no_referensi' => 'nullable|string',
            'catatan' => 'nullable|string',
            'kas_id' => 'required_if:metode_pembayaran,kas',
            'rekening_id' => 'required_if:metode_pembayaran,bank'
        ]);

        DB::beginTransaction();

        try {
            // Update payment - automatic journal akan di-handle oleh model observer
            $payment->update($validated);

            // Update PO payment status
            $po = PurchaseOrder::find($request->purchase_order_id);

            // Calculate total payments
            $totalPayments = PembayaranHutang::where('purchase_order_id', $po->id)->sum('jumlah');

            // Calculate returns
            $returPembelian = ReturPembelian::where('purchase_order_id', $po->id)
                ->where('status', 'selesai')
                ->with(['details', 'purchaseOrder.details'])
                ->get();

            $totalReturValue = 0;
            foreach ($returPembelian as $retur) {
                $poDetails = $retur->purchaseOrder->details;

                foreach ($retur->details as $returDetail) {
                    // Find matching PO detail for this product
                    $matchingPoDetail = $poDetails->where('produk_id', $returDetail->produk_id)->first();

                    if ($matchingPoDetail) {
                        $totalReturValue += $matchingPoDetail->harga * $returDetail->quantity;
                    }
                }
            }

            $sisaHutang = $po->total - $totalPayments - $totalReturValue;

            // Update PO status
            if ($sisaHutang < 0) {
                // Kelebihan bayar situation
                $po->status_pembayaran = 'kelebihan_bayar';
                $po->kelebihan_bayar = abs($sisaHutang);
                $po->save();
            } else if ($sisaHutang == 0) {
                $po->status_pembayaran = 'lunas';
                $po->kelebihan_bayar = 0;
                
                // Cek apakah juga sudah diterima, jika ya maka PO selesai
                if ($po->status_penerimaan == 'diterima') {
                    $po->status = 'selesai';
                    $po->save();
                    
                    // Update harga beli rata-rata ketika PO selesai melalui pembayaran
                    \App\Http\Controllers\Pembelian\PurchasingOrderController::updateHargaBeliRataRataFromExternalController($po->id);
                } else {
                    $po->save();
                }
            } else {
                $po->status_pembayaran = 'sebagian';
                $po->kelebihan_bayar = 0;
                $po->save();
            }

            DB::commit();

            return redirect()
                ->route('keuangan.pembayaran-hutang.show', $payment->id)
                ->with('success', 'Pembayaran hutang berhasil diperbarui.');
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
        $payment = PembayaranHutang::findOrFail($id);
        $po_id = $payment->purchase_order_id;

        DB::beginTransaction();

        try {
            // Delete payment - automatic journal deletion akan di-handle oleh model observer
            $payment->delete();

            // Update PO payment status
            $po = PurchaseOrder::find($po_id);

            // Calculate total payments
            $totalPayments = PembayaranHutang::where('purchase_order_id', $po->id)->sum('jumlah');

            // Calculate returns
            $returPembelian = ReturPembelian::where('purchase_order_id', $po->id)
                ->where('status', 'selesai')
                ->with(['details', 'purchaseOrder.details'])
                ->get();

            $totalReturValue = 0;
            foreach ($returPembelian as $retur) {
                $poDetails = $retur->purchaseOrder->details;

                foreach ($retur->details as $returDetail) {
                    // Find matching PO detail for this product
                    $matchingPoDetail = $poDetails->where('produk_id', $returDetail->produk_id)->first();

                    if ($matchingPoDetail) {
                        $totalReturValue += $matchingPoDetail->harga * $returDetail->quantity;
                    }
                }
            }

            $sisaHutang = $po->total - $totalPayments - $totalReturValue;

            // Update PO status
            if ($sisaHutang < 0) {
                // Kelebihan bayar situation
                $po->status_pembayaran = 'kelebihan_bayar';
                $po->kelebihan_bayar = abs($sisaHutang);
            } else if ($sisaHutang == 0) {
                $po->status_pembayaran = 'lunas';
                $po->kelebihan_bayar = 0;
            } else if ($totalPayments > 0) {
                $po->status_pembayaran = 'sebagian';
                $po->kelebihan_bayar = 0;
            } else {
                $po->status_pembayaran = 'belum_bayar';
                $po->kelebihan_bayar = 0;
            }

            $po->save();

            DB::commit();

            return redirect()
                ->route('keuangan.pembayaran-hutang.index')
                ->with('success', 'Pembayaran hutang berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Print the payment receipt
     */
    public function print($id)
    {
        $payment = PembayaranHutang::with(['supplier', 'purchaseOrder', 'user'])
            ->findOrFail($id);

        $pdf = PDF::loadView('keuangan.pembayaran_hutang.print', compact('payment'));

        return $pdf->stream('pembayaran_hutang_' . $payment->nomor . '.pdf');
    }
}