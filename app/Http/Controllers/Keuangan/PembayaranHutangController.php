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

            // Record transaction based on payment method
            if ($request->metode_pembayaran === 'kas') {
                // Update the kas balance
                $kas = Kas::findOrFail($request->kas_id);
                if ($kas->saldo < $request->jumlah) {
                    throw new \Exception('Saldo kas tidak mencukupi untuk melakukan pembayaran ini.');
                }
                $kas->saldo -= $request->jumlah;
                $kas->save();

                // Get PO number
                $po = PurchaseOrder::findOrFail($request->purchase_order_id);
                $supplierName = Supplier::find($request->supplier_id)->nama;

                TransaksiKas::create([
                    'tanggal' => $request->tanggal,
                    'kas_id' => $request->kas_id,
                    'jenis' => 'keluar',
                    'jumlah' => $request->jumlah,
                    'keterangan' => 'Pembayaran PO #' . $po->nomor . ' ke ' . $supplierName,
                    'no_bukti' => $validated['nomor'],
                    'related_id' => $payment->id,
                    'related_type' => PembayaranHutang::class,
                    'user_id' => Auth::id()
                ]);
            } elseif ($request->metode_pembayaran === 'bank') {
                // Update the bank account balance
                $rekening = RekeningBank::findOrFail($request->rekening_id);
                if ($rekening->saldo < $request->jumlah) {
                    throw new \Exception('Saldo rekening bank tidak mencukupi untuk melakukan pembayaran ini.');
                }
                $rekening->saldo -= $request->jumlah;
                $rekening->save();

                // Get PO number
                $po = PurchaseOrder::findOrFail($request->purchase_order_id);
                $supplierName = Supplier::find($request->supplier_id)->nama;

                TransaksiBank::create([
                    'tanggal' => $request->tanggal,
                    'rekening_id' => $request->rekening_id,
                    'jenis' => 'keluar',
                    'jumlah' => $request->jumlah,
                    'keterangan' => 'Pembayaran PO #' . $po->nomor . ' ke ' . $supplierName,
                    'no_referensi' => $request->no_referensi,
                    'related_id' => $payment->id,
                    'related_type' => PembayaranHutang::class,
                    'user_id' => Auth::id()
                ]);
            }

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
            } else if ($sisaHutang == 0) {
                $po->status_pembayaran = 'lunas';
                $po->kelebihan_bayar = 0;
            } else {
                $po->status_pembayaran = 'sebagian';
                $po->kelebihan_bayar = 0;
            }

            $po->save();

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
            // Find related transactions and restore the original balance first
            if ($payment->metode_pembayaran === 'kas') {
                $transaksi = TransaksiKas::where('related_id', $payment->id)
                    ->where('related_type', PembayaranHutang::class)
                    ->first();

                if ($transaksi) {
                    $kas = Kas::findOrFail($transaksi->kas_id);
                    $kas->saldo += $payment->jumlah; // Restore the original balance
                    $kas->save();

                    // Delete the old transaction
                    $transaksi->delete();
                }
            } elseif ($payment->metode_pembayaran === 'bank') {
                $transaksi = TransaksiBank::where('related_id', $payment->id)
                    ->where('related_type', PembayaranHutang::class)
                    ->first();

                if ($transaksi) {
                    $rekening = RekeningBank::findOrFail($transaksi->rekening_id);
                    $rekening->saldo += $payment->jumlah; // Restore the original balance
                    $rekening->save();

                    // Delete the old transaction
                    $transaksi->delete();
                }
            }

            // Update payment
            $payment->update($validated);

            // Create new transaction based on updated payment method
            if ($request->metode_pembayaran === 'kas') {
                // Update the kas balance
                $kas = Kas::findOrFail($request->kas_id);
                if ($kas->saldo < $request->jumlah) {
                    throw new \Exception('Saldo kas tidak mencukupi untuk melakukan pembayaran ini.');
                }
                $kas->saldo -= $request->jumlah;
                $kas->save();

                // Get PO number
                $po = PurchaseOrder::findOrFail($request->purchase_order_id);
                $supplierName = Supplier::find($request->supplier_id)->nama;

                TransaksiKas::create([
                    'tanggal' => $request->tanggal,
                    'kas_id' => $request->kas_id,
                    'jenis' => 'keluar',
                    'jumlah' => $request->jumlah,
                    'keterangan' => 'Pembayaran PO #' . $po->nomor . ' ke ' . $supplierName,
                    'no_bukti' => $request->nomor,
                    'related_id' => $payment->id,
                    'related_type' => PembayaranHutang::class,
                    'user_id' => Auth::id()
                ]);
            } elseif ($request->metode_pembayaran === 'bank') {
                // Update the bank account balance
                $rekening = RekeningBank::findOrFail($request->rekening_id);
                if ($rekening->saldo < $request->jumlah) {
                    throw new \Exception('Saldo rekening bank tidak mencukupi untuk melakukan pembayaran ini.');
                }
                $rekening->saldo -= $request->jumlah;
                $rekening->save();

                // Get PO number
                $po = PurchaseOrder::findOrFail($request->purchase_order_id);
                $supplierName = Supplier::find($request->supplier_id)->nama;

                TransaksiBank::create([
                    'tanggal' => $request->tanggal,
                    'rekening_id' => $request->rekening_id,
                    'jenis' => 'keluar',
                    'jumlah' => $request->jumlah,
                    'keterangan' => 'Pembayaran PO #' . $po->nomor . ' ke ' . $supplierName,
                    'no_referensi' => $request->no_referensi,
                    'related_id' => $payment->id,
                    'related_type' => PembayaranHutang::class,
                    'user_id' => Auth::id()
                ]);
            }

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
            } else if ($sisaHutang == 0) {
                $po->status_pembayaran = 'lunas';
                $po->kelebihan_bayar = 0;
            } else {
                $po->status_pembayaran = 'sebagian';
                $po->kelebihan_bayar = 0;
            }

            $po->save();

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
            // Restore the cash or bank balance before deleting the payment
            if ($payment->metode_pembayaran === 'kas') {
                $transaksi = TransaksiKas::where('related_id', $payment->id)
                    ->where('related_type', PembayaranHutang::class)
                    ->first();

                if ($transaksi) {
                    $kas = Kas::findOrFail($transaksi->kas_id);
                    $kas->saldo += $payment->jumlah; // Add the money back to the cash balance
                    $kas->save();

                    // Delete the transaction
                    $transaksi->delete();
                }
            } elseif ($payment->metode_pembayaran === 'bank') {
                $transaksi = TransaksiBank::where('related_id', $payment->id)
                    ->where('related_type', PembayaranHutang::class)
                    ->first();

                if ($transaksi) {
                    $rekening = RekeningBank::findOrFail($transaksi->rekening_id);
                    $rekening->saldo += $payment->jumlah; // Add the money back to the bank account
                    $rekening->save();

                    // Delete the transaction
                    $transaksi->delete();
                }
            }

            // Delete payment
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
