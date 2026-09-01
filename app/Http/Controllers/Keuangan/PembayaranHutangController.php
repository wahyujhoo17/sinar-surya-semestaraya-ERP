<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\PembayaranHutang;
use App\Models\PembayaranHutangDetail;
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
use Illuminate\Support\Facades\Log;
use App\Models\LogAktivitas;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranHutangController extends Controller
{
    /**
     * Helper untuk mencatat log aktivitas user
     */
    private function logUserAktivitas($aktivitas, $modul, $data_id = null, $detail = null)
    {
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => $aktivitas,
            'modul' => $modul,
            'data_id' => $data_id,
            'ip_address' => request()->ip(),
            'detail' => $detail ? (is_array($detail) ? json_encode($detail) : $detail) : null,
        ]);
    }

    /**
     * Update status pembayaran Purchase Order berdasarkan total pembayaran & retur terkini
     */
    private function updatePurchaseOrderStatus(PurchaseOrder $po)
    {
        $totalPayments = $po->pembayaranDetails()->sum('jumlah');

        $returPembelian = ReturPembelian::where('purchase_order_id', $po->id)
            ->where('status', 'selesai')
            ->with(['details', 'purchaseOrder.details'])
            ->get();

        $totalReturValue = 0;
        foreach ($returPembelian as $retur) {
            $poDetails = $retur->purchaseOrder->details;
            foreach ($retur->details as $returDetail) {
                $matchingPoDetail = $poDetails->where('produk_id', $returDetail->produk_id)->first();
                if ($matchingPoDetail) {
                    $totalReturValue += $matchingPoDetail->harga * $returDetail->quantity;
                }
            }
        }

        $sisaHutang = (float)$po->total - (float)$totalPayments - (float)$totalReturValue;
        $sisaHutangRounded = round($sisaHutang, 2);

        if ($sisaHutangRounded < -0.01) {
            $po->status_pembayaran = 'kelebihan_bayar';
            $po->kelebihan_bayar = abs($sisaHutangRounded);
            $po->save();
        } else if ($sisaHutangRounded <= 0.01) {
            $po->status_pembayaran = 'lunas';
            $po->kelebihan_bayar = 0;

            if ($po->status_penerimaan == 'diterima') {
                $po->status = 'selesai';
                $po->save();
                \App\Http\Controllers\Pembelian\PurchasingOrderController::updateHargaBeliTerbaruFromExternalController($po->id);
            } else {
                $po->save();
            }
        } else {
            $po->status_pembayaran = ($totalPayments > 0) ? 'sebagian' : 'belum_bayar';
            $po->kelebihan_bayar = 0;
            $po->save();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = PembayaranHutang::with(['supplier', 'details.purchaseOrder', 'purchaseOrders', 'kas', 'rekeningBank'])
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('keuangan.hutang_usaha.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $po = null;
        $sisaHutang = 0;
        $suppliers = Supplier::orderBy('nama')->get();
        $kasAccounts = Kas::where('is_aktif', true)->get();
        $bankAccounts = RekeningBank::where('is_aktif', true)->get();
        $availablePurchaseOrders = collect();
        $selectedSupplier = null;

        if ($request->has('po_id')) {
            $po = PurchaseOrder::with(['supplier', 'details', 'details.produk', 'pembayaranDetails'])
                ->findOrFail($request->po_id);

            $selectedSupplier = $po->supplier;
            $totalPayments = $po->pembayaranDetails()->sum('jumlah');

            $returPembelian = ReturPembelian::where('purchase_order_id', $po->id)
                ->where('status', 'selesai')
                ->with(['details', 'purchaseOrder.details'])
                ->get();

            $totalReturValue = 0;
            foreach ($returPembelian as $retur) {
                $poDetails = $retur->purchaseOrder->details;
                foreach ($retur->details as $returDetail) {
                    $matchingPoDetail = $poDetails->where('produk_id', $returDetail->produk_id)->first();
                    if ($matchingPoDetail) {
                        $totalReturValue += $matchingPoDetail->harga * $returDetail->quantity;
                    }
                }
            }

            $sisaHutang = $po->total - $totalPayments - $totalReturValue;

            // Load all unpaid POs for this supplier
            $availablePurchaseOrders = PurchaseOrder::where('supplier_id', $po->supplier_id)
                ->whereIn('status_pembayaran', ['belum_bayar', 'sebagian'])
                ->orderBy('tanggal', 'asc')
                ->get();
        } elseif ($request->has('supplier_id')) {
            $selectedSupplier = Supplier::find($request->supplier_id);
            if ($selectedSupplier) {
                $availablePurchaseOrders = PurchaseOrder::where('supplier_id', $selectedSupplier->id)
                    ->whereIn('status_pembayaran', ['belum_bayar', 'sebagian'])
                    ->orderBy('tanggal', 'asc')
                    ->get();
            }
        }

        // Generate a unique payment number
        $today = date('Ymd');
        $prefix = 'BYR-' . $today . '-';

        $lastPayment = PembayaranHutang::where('nomor', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 0;
        if ($lastPayment) {
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
            'selectedSupplier' => $selectedSupplier,
            'paymentNumber' => $paymentNumber,
            'kasAccounts' => $kasAccounts,
            'bankAccounts' => $bankAccounts,
            'availablePurchaseOrders' => $availablePurchaseOrders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Normalize field names if needed
        if ($request->has('tanggal_pembayaran') && !$request->has('tanggal')) {
            $request->merge(['tanggal' => $request->input('tanggal_pembayaran')]);
        }
        if ($request->has('jumlah_pembayaran') && !$request->has('jumlah')) {
            $request->merge(['jumlah' => $request->input('jumlah_pembayaran')]);
        }
        if ($request->has('rekening_bank_id') && !$request->has('rekening_id')) {
            $request->merge(['rekening_id' => $request->input('rekening_bank_id')]);
        }

        $validated = $request->validate([
            'nomor' => 'nullable|unique:pembayaran_hutang,nomor',
            'tanggal' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
            'purchase_order_id' => 'nullable|exists:purchase_order,id',
            'jumlah' => 'required|numeric|min:0.01',
            'metode_pembayaran' => 'required|string|in:kas,bank,tunai,transfer,giro,cek,Kas,Bank,Bank Transfer,Giro,Cek',
            'no_referensi' => 'nullable|string|max:100',
            'catatan' => 'nullable|string|max:500',
            'kas_id' => 'nullable|required_if:metode_pembayaran,kas,tunai,Kas|exists:kas,id',
            'rekening_id' => 'nullable|required_if:metode_pembayaran,bank,transfer,giro,cek,Bank,Bank Transfer,Giro,Cek|exists:rekening_bank,id',
            'allocations' => 'nullable|array',
            'allocations.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $supplier = Supplier::findOrFail($validated['supplier_id']);
            $totalBayar = (float)$validated['jumlah'];

            // Process allocations
            $allocations = [];
            if ($request->has('allocations') && is_array($request->allocations)) {
                foreach ($request->allocations as $poId => $amount) {
                    $amountFloat = (float)$amount;
                    if ($amountFloat > 0) {
                        $allocations[$poId] = $amountFloat;
                    }
                }
            }

            // Fallback for single PO input if no allocations array provided
            if (empty($allocations) && !empty($validated['purchase_order_id'])) {
                $allocations[$validated['purchase_order_id']] = $totalBayar;
            }

            // Validate total allocation equals total payment amount
            $totalAllocated = array_sum($allocations);
            if (!empty($allocations) && abs($totalAllocated - $totalBayar) > 0.05) {
                DB::rollBack();
                return back()->withInput()->withErrors([
                    'jumlah' => 'Total alokasi Purchase Order (Rp ' . number_format($totalAllocated, 0, ',', '.') .
                        ') harus sama dengan Jumlah Pembayaran (Rp ' . number_format($totalBayar, 0, ',', '.') . ').'
                ]);
            }

            // Verify each PO belongs to this supplier
            $validatedPOs = [];
            foreach ($allocations as $poId => $amount) {
                $poItem = PurchaseOrder::where('id', $poId)
                    ->where('supplier_id', $supplier->id)
                    ->first();

                if (!$poItem) {
                    DB::rollBack();
                    return back()->withInput()->withErrors([
                        'allocations' => "Purchase Order ID #{$poId} tidak ditemukan atau bukan milik supplier ini."
                    ]);
                }

                $validatedPOs[$poId] = [
                    'model' => $poItem,
                    'amount' => $amount,
                ];
            }

            // Generate a fresh payment number based on the payment date
            $paymentDate = date('Ymd', strtotime($request->tanggal));
            $prefix = 'BYR-' . $paymentDate . '-';

            $lastPayment = PembayaranHutang::where('nomor', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();

            $lastNumber = 0;
            if ($lastPayment) {
                $parts = explode('-', $lastPayment->nomor);
                if (isset($parts[2])) {
                    $lastNumber = (int)$parts[2];
                }
            }

            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $validated['nomor'] = $prefix . $newNumber;
            $validated['user_id'] = Auth::id();

            // Set single PO for backward compatibility if exactly 1 PO allocated
            if (count($validatedPOs) === 1) {
                $validated['purchase_order_id'] = array_key_first($validatedPOs);
            } else {
                $validated['purchase_order_id'] = null;
            }

            // Create Payment Header
            $payment = PembayaranHutang::create($validated);

            // Create Detail records and update PO status
            $processedPoNumbers = [];
            foreach ($validatedPOs as $poId => $item) {
                $poItem = $item['model'];
                $amount = $item['amount'];

                $itemNote = $request->input("catatan_allocations.{$poItem->id}") ?: ($validated['catatan'] ?? null);

                PembayaranHutangDetail::create([
                    'pembayaran_hutang_id' => $payment->id,
                    'purchase_order_id' => $poItem->id,
                    'jumlah' => $amount,
                    'catatan' => $itemNote,
                ]);

                // Update PO payment status
                $this->updatePurchaseOrderStatus($poItem);
                $processedPoNumbers[] = $poItem->nomor;
            }

            DB::commit();

            // Log activity
            $this->logUserAktivitas(
                'tambah',
                'hutang_usaha',
                $payment->id,
                [
                    'nomor' => $payment->nomor,
                    'jumlah' => $payment->jumlah,
                    'supplier' => $supplier->nama,
                    'purchase_orders' => implode(', ', $processedPoNumbers),
                ]
            );

            return redirect()
                ->route('keuangan.pembayaran-hutang.show', $payment->id)
                ->with('success', 'Pembayaran hutang berhasil dicatat. Nomor: ' . $payment->nomor .
                    (!empty($processedPoNumbers) ? ' untuk PO: ' . implode(', ', $processedPoNumbers) : ''));

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving PembayaranHutang: ' . $e->getMessage() . ' Stack: ' . $e->getTraceAsString());
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
        $payment = PembayaranHutang::with([
            'supplier',
            'details.purchaseOrder',
            'purchaseOrders',
            'purchaseOrder',
            'user',
            'kas',
            'rekeningBank'
        ])->findOrFail($id);

        $logs = LogAktivitas::where('modul', 'hutang_usaha')
            ->where('data_id', $id)
            ->with('user')
            ->latest()
            ->get();

        return view('keuangan.pembayaran_hutang.show', compact('payment', 'logs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama') && !$user->hasRole('administrator') && !$user->hasRole('admin')) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengedit pembayaran hutang.');
        }

        $payment = PembayaranHutang::with(['supplier', 'details.purchaseOrder', 'purchaseOrder'])->findOrFail($id);

        $suppliers = Supplier::orderBy('nama')->get();
        $kasAccounts = Kas::where('is_aktif', true)->get();
        $bankAccounts = RekeningBank::where('is_aktif', true)->get();

        $po = $payment->purchaseOrder;
        $sisaHutang = 0;

        if ($po) {
            $totalPayments = $po->pembayaranDetails()->where('pembayaran_hutang_id', '!=', $payment->id)->sum('jumlah');

            $returPembelian = ReturPembelian::where('purchase_order_id', $po->id)
                ->where('status', 'selesai')
                ->with(['details', 'purchaseOrder.details'])
                ->get();

            $totalReturValue = 0;
            foreach ($returPembelian as $retur) {
                $poDetails = $retur->purchaseOrder->details;
                foreach ($retur->details as $returDetail) {
                    $matchingPoDetail = $poDetails->where('produk_id', $returDetail->produk_id)->first();
                    if ($matchingPoDetail) {
                        $totalReturValue += $matchingPoDetail->harga * $returDetail->quantity;
                    }
                }
            }

            $sisaHutang = $po->total - $totalPayments - $totalReturValue;
        }

        return view('keuangan.hutang_usaha.edit_pembayaran', [
            'payment' => $payment,
            'suppliers' => $suppliers,
            'kasAccounts' => $kasAccounts,
            'bankAccounts' => $bankAccounts,
            'po' => $po,
            'sisaHutang' => $sisaHutang
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama') && !$user->hasRole('administrator') && !$user->hasRole('admin')) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengupdate pembayaran hutang.');
        }

        $payment = PembayaranHutang::with('details.purchaseOrder')->findOrFail($id);

        $validated = $request->validate([
            'nomor' => 'required|unique:pembayaran_hutang,nomor,' . $id,
            'tanggal' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
            'jumlah' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|string|in:kas,bank,tunai,transfer',
            'no_referensi' => 'nullable|string|max:100',
            'catatan' => 'nullable|string|max:500',
            'kas_id' => 'required_if:metode_pembayaran,kas,tunai',
            'rekening_id' => 'required_if:metode_pembayaran,bank,transfer'
        ]);

        DB::beginTransaction();

        try {
            $payment->update($validated);

            // If single PO legacy, update detail and PO status
            if ($payment->details->count() === 1) {
                $detail = $payment->details->first();
                $detail->jumlah = (float)$validated['jumlah'];
                $detail->save();

                if ($detail->purchaseOrder) {
                    $this->updatePurchaseOrderStatus($detail->purchaseOrder);
                }
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
        $user = Auth::user();
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama') && !$user->hasRole('administrator') && !$user->hasRole('admin')) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk menghapus pembayaran hutang.');
        }

        $payment = PembayaranHutang::with('details.purchaseOrder')->findOrFail($id);

        $paymentLogDetail = [
            'nomor' => $payment->nomor,
            'jumlah' => $payment->jumlah,
            'metode_pembayaran' => $payment->metode_pembayaran,
            'tanggal' => $payment->tanggal,
            'supplier' => $payment->supplier->nama ?? '-',
        ];

        DB::beginTransaction();

        try {
            $posToUpdate = [];
            foreach ($payment->details as $detail) {
                if ($detail->purchaseOrder) {
                    $posToUpdate[] = $detail->purchaseOrder;
                }
            }

            if ($payment->metode_pembayaran === 'kas' || $payment->metode_pembayaran === 'tunai') {
                TransaksiKas::where('related_id', $payment->id)->where('related_type', PembayaranHutang::class)->delete();
            } else {
                TransaksiBank::where('related_id', $payment->id)->where('related_type', PembayaranHutang::class)->delete();
            }

            $payment->delete();

            // Recalculate status for all affected POs
            foreach ($posToUpdate as $po) {
                $this->updatePurchaseOrderStatus($po);
            }

            DB::commit();

            $this->logUserAktivitas('hapus', 'hutang_usaha', $id, $paymentLogDetail);

            return redirect()
                ->route('keuangan.hutang-usaha.index')
                ->with('success', 'Pembayaran hutang berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Print the specified resource.
     */
    public function print(string $id)
    {
        $payment = PembayaranHutang::with([
            'supplier',
            'details.purchaseOrder',
            'purchaseOrders',
            'purchaseOrder',
            'user',
            'kas',
            'rekeningBank'
        ])->findOrFail($id);

        return view('keuangan.hutang_usaha.print', compact('payment'));
    }
}