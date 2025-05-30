<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PembayaranPiutang;
use App\Models\Customer;
use App\Models\SalesOrder; // Added SalesOrder
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Kas;
use App\Models\RekeningBank;
use App\Models\TransaksiKas;
use App\Models\TransaksiBank;
// use App\\Models\\AkunAkuntansi; // User commented out

class PembayaranPiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembayaranPiutangs = PembayaranPiutang::with(['invoice.customer', 'customer', 'user', 'kas', 'rekeningBank'])->latest()->paginate(10);
        return view('keuangan.pembayaran_piutang.index', compact('pembayaranPiutangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $invoiceId = $request->query('invoice_id');
        $invoice = null;
        $sisaPiutang = 0;
        $customer = null;
        $customers = Customer::orderBy('nama')->get();
        $kasAccounts = Kas::where('is_aktif', true)->get();
        $bankAccounts = RekeningBank::where('is_aktif', true)->get();

        if ($invoiceId) {
            $invoice = Invoice::with('customer')->find($invoiceId);
            if ($invoice) {
                $sisaPiutang = $invoice->sisa_piutang;
                $customer = $invoice->customer;
            } else {
            }
        }

        $today = date('Ymd');
        $prefix = 'BPP-' . $today . '-';

        $lastPayment = PembayaranPiutang::where('nomor', 'like', $prefix . '%') // Changed nomor_pembayaran to nomor
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 0;
        if ($lastPayment && $lastPayment->nomor) { // Changed nomor_pembayaran to nomor
            $parts = explode('-', $lastPayment->nomor); // Changed nomor_pembayaran to nomor
            if (count($parts) === 3 && strlen($parts[2]) === 4) { // BPP-YYYYMMDD-XXXX
                $lastNumber = (int)end($parts);
            } else if (count($parts) > 3) { // Handle if nomor might have more hyphens
                $lastNumber = (int)array_pop($parts);
            }
        }

        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $nomorPembayaran = $prefix . $newNumber; // Variable name kept for view compatibility

        return view('keuangan.pembayaran_piutang.create', compact(
            'invoice',
            'sisaPiutang',
            'customer',
            'customers',
            'nomorPembayaran',
            'kasAccounts',
            'bankAccounts'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Enable query logging for debugging in development environments
        if (config('app.debug')) {
            DB::enableQueryLog();
        }

        $validatedData = $request->validate([
            'invoice_id' => 'nullable|exists:invoice,id', // Changed invoices to invoice
            'customer_id' => 'required_without:invoice_id|exists:customer,id',
            'tanggal_pembayaran' => 'required|date',
            'jumlah_pembayaran' => 'required|numeric|min:0.01',
            'metode_pembayaran' => 'required|string|in:Kas,Bank Transfer',
            'kas_id' => 'nullable|required_if:metode_pembayaran,Kas|exists:kas,id',
            'rekening_bank_id' => 'nullable|required_if:metode_pembayaran,Bank Transfer|exists:rekening_bank,id',
            'catatan' => 'nullable|string|max:255',
            'no_referensi' => 'nullable|string|max:100',
        ]);


        DB::beginTransaction();
        // dd($validatedData);
        try {
            $pembayaran = new PembayaranPiutang();
            // Manually map fields that differ from validatedData keys or need specific handling
            $pembayaran->tanggal = $validatedData['tanggal_pembayaran'];
            $pembayaran->jumlah = $validatedData['jumlah_pembayaran'];
            $pembayaran->metode_pembayaran = $validatedData['metode_pembayaran'];

            // Store kas and rekening information in catatan field instead of separate columns
            $originalCatatan = $validatedData['catatan'] ?? '';

            if ($validatedData['metode_pembayaran'] === 'Kas' && isset($validatedData['kas_id'])) {
                $kas = Kas::find($validatedData['kas_id']);
                $kasCatatan = "Pembayaran melalui Kas: " . ($kas ? $kas->nama : 'N/A');
                $pembayaran->catatan = $originalCatatan ? ($originalCatatan . '. ' . $kasCatatan) : $kasCatatan;
                $pembayaran->kas_id = $validatedData['kas_id']; // Tetap simpan untuk relasi
            } elseif ($validatedData['metode_pembayaran'] === 'Bank Transfer' && isset($validatedData['rekening_bank_id'])) {
                $rekening = RekeningBank::find($validatedData['rekening_bank_id']);
                $rekeningCatatan = "Pembayaran melalui Bank: " .
                    ($rekening ? $rekening->nama_bank . " - " . $rekening->nomor_rekening . " a.n " . $rekening->atas_nama : 'N/A');
                $pembayaran->catatan = $originalCatatan ? ($originalCatatan . '. ' . $rekeningCatatan) : $rekeningCatatan;
                $pembayaran->rekening_bank_id = $validatedData['rekening_bank_id']; // Tetap simpan untuk relasi
            } else {
                $pembayaran->catatan = $originalCatatan;
            }

            $pembayaran->no_referensi = $validatedData['no_referensi'] ?? null;
            $pembayaran->invoice_id = $validatedData['invoice_id'] ?? null;

            $paymentDate = date('Ymd', strtotime($request->tanggal_pembayaran));
            $prefix = 'BPP-' . $paymentDate . '-';
            $lastPaymentOnDate = PembayaranPiutang::where('nomor', 'like', $prefix . '%') // Changed nomor_pembayaran to nomor
                ->orderBy('id', 'desc')
                ->first();
            $lastNum = 0;
            if ($lastPaymentOnDate && $lastPaymentOnDate->nomor) { // Changed nomor_pembayaran to nomor
                $parts = explode('-', $lastPaymentOnDate->nomor); // Changed nomor_pembayaran to nomor
                if (count($parts) === 3 && strlen($parts[2]) === 4) {
                    $lastNum = (int)end($parts);
                } else if (count($parts) > 3) {
                    $lastNum = (int)array_pop($parts);
                }
            }
            $newNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
            $pembayaran->nomor = $prefix . $newNum; // Changed nomor_pembayaran to nomor
            $pembayaran->user_id = Auth::id();

            $invoice = null;
            if ($request->invoice_id) {
                $invoice = Invoice::findOrFail($request->invoice_id);
                // Check if sales_order_id exists before accessing it
                $salesOrder = null;
                if ($invoice->sales_order_id) {
                    $salesOrder = SalesOrder::find($invoice->sales_order_id);
                }

                $pembayaran->customer_id = $invoice->customer_id;

                // Calculate sisa_piutang by subtracting total payments from invoice total
                $totalPaymentsBefore = $invoice->pembayaranPiutang()->sum('jumlah');
                $sisaPiutang = (float)$invoice->total - (float)$totalPaymentsBefore;

                if (round((float)$pembayaran->jumlah, 2) > round((float)$sisaPiutang, 2) + 0.001) { // Use $pembayaran->jumlah
                    DB::rollBack();
                    return back()->withInput()->withErrors(['jumlah_pembayaran' => 'Jumlah pembayaran (Rp ' . number_format($pembayaran->jumlah, 2, ',', '.') . ') melebihi sisa piutang (Rp ' . number_format($sisaPiutang, 2, ',', '.') . ') untuk invoice ini.']);
                }

                // Calculate remaining amount after this payment
                $sisaPiutangAfterPayment = (float)$sisaPiutang - (float)$pembayaran->jumlah;

                if ($sisaPiutangAfterPayment <= 0.009) { // Tolerance for zero
                    $invoice->status = 'lunas'; // Changed from status_pembayaran to status
                    if ($salesOrder) {
                        $salesOrder->status_pembayaran = 'lunas';
                        $salesOrder->total_pembayaran = $totalPaymentsBefore + $pembayaran->jumlah;
                    }
                } else {
                    $invoice->status = 'sebagian'; // Changed from status_pembayaran to status
                    if ($salesOrder) {
                        $salesOrder->status_pembayaran = 'sebagian';
                        $salesOrder->total_pembayaran = $totalPaymentsBefore + $pembayaran->jumlah;
                    }
                }

                $invoice->save();
                if ($salesOrder) {
                    $salesOrder->save();
                }
            } else {
                $pembayaran->customer_id = $validatedData['customer_id'];
            }

            $pembayaran->save();

            $customerName = Customer::find($pembayaran->customer_id);
            $customerName = $customerName ? ($customerName->company ?? $customerName->nama) : 'Unknown';
            $invoiceNumber = $invoice ? $invoice->nomor : 'Tanpa Invoice';

            if ($pembayaran->metode_pembayaran === 'Kas' && $pembayaran->kas_id) {
                $kas = Kas::findOrFail($pembayaran->kas_id);
                $kas->saldo += $pembayaran->jumlah; // Use $pembayaran->jumlah
                $kas->save();
                TransaksiKas::create([
                    'tanggal' => $pembayaran->tanggal, // Use $pembayaran->tanggal
                    'kas_id' => $pembayaran->kas_id,
                    'jenis' => 'masuk',
                    'jumlah' => $pembayaran->jumlah, // Use $pembayaran->jumlah
                    'keterangan' => 'Penerimaan piutang dari ' . $customerName . ' (Inv: ' . $invoiceNumber . ')',
                    'no_bukti' => $pembayaran->nomor, // Changed nomor_pembayaran to nomor
                    'related_id' => $pembayaran->id,
                    'related_type' => PembayaranPiutang::class,
                    'user_id' => Auth::id()
                ]);
            } elseif ($pembayaran->metode_pembayaran === 'Bank Transfer' && $pembayaran->rekening_bank_id) {
                $rekening = RekeningBank::findOrFail($pembayaran->rekening_bank_id);
                $rekening->saldo += $pembayaran->jumlah; // Use $pembayaran->jumlah
                $rekening->save();
                TransaksiBank::create([
                    'tanggal' => $pembayaran->tanggal, // Use $pembayaran->tanggal
                    'rekening_id' => $pembayaran->rekening_bank_id,
                    'jenis' => 'masuk',
                    'jumlah' => $pembayaran->jumlah, // Use $pembayaran->jumlah
                    'keterangan' => 'Penerimaan piutang dari ' . $customerName . ' (Inv: ' . $invoiceNumber . ')',
                    'no_referensi' => $pembayaran->no_referensi,
                    'no_bukti' => $pembayaran->nomor, // Changed nomor_pembayaran to nomor
                    'related_id' => $pembayaran->id,
                    'related_type' => PembayaranPiutang::class,
                    'user_id' => Auth::id()
                ]);
            }

            DB::commit();
            $redirectRoute = $request->invoice_id ? route('keuangan.piutang-usaha.show', $request->invoice_id) : route('keuangan.pembayaran-piutang.show', $pembayaran->id);
            return redirect($redirectRoute)->with('success', 'Pembayaran piutang berhasil dicatat. Nomor: ' . $pembayaran->nomor); // Changed nomor_pembayaran to nomor

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            if (config('app.debug')) {
                Log::debug('Validation error in PembayaranPiutang store: ' . json_encode($e->errors()));
            }
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            if (config('app.debug')) {
                Log::error('Error saving payment: ' . $e->getMessage() . ' Stack: ' . $e->getTraceAsString());
                if (method_exists(DB::class, 'getQueryLog')) {
                    Log::debug('Query log: ' . json_encode(DB::getQueryLog()));
                }
            }
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan pembayaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pembayaran = PembayaranPiutang::with(['invoice.customer', 'customer', 'user', 'kas', 'rekeningBank'])->findOrFail($id);
        return view('keuangan.pembayaran_piutang.show', compact('pembayaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pembayaran = PembayaranPiutang::with('customer')->findOrFail($id);
        $invoice = null;
        $sisaPiutangSaatIni = 0;
        $sisaPiutangUntukEdit = 0;

        $customers = Customer::orderBy('nama')->get();
        $kasAccounts = Kas::where('is_aktif', true)->get();
        $bankAccounts = RekeningBank::where('is_aktif', true)->get();

        if ($pembayaran->invoice_id) {
            $invoice = Invoice::with('customer')->find($pembayaran->invoice_id);
            if ($invoice) {
                $sisaPiutangSaatIni = $invoice->sisa_piutang;
                // Sisa piutang for form: current sisa + this payment amount (as if this payment is reverted)
                $sisaPiutangUntukEdit = $invoice->sisa_piutang + $pembayaran->jumlah; // Use $pembayaran->jumlah
            }
        }

        return view('keuangan.pembayaran_piutang.edit', compact(
            'pembayaran',
            'invoice',
            'sisaPiutangSaatIni',
            'sisaPiutangUntukEdit',
            'customers',
            'kasAccounts',
            'bankAccounts'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pembayaran = PembayaranPiutang::findOrFail($id);

        $validatedData = $request->validate([
            'invoice_id' => 'nullable|exists:invoice,id', // Changed invoices to invoice
            'customer_id' => 'required_without:invoice_id|exists:customer,id',
            'tanggal_pembayaran' => 'required|date',
            'jumlah_pembayaran' => 'required|numeric|min:0.01',
            'metode_pembayaran' => 'required|string|in:Kas,Bank Transfer',
            'kas_id' => 'nullable|required_if:metode_pembayaran,Kas|exists:kas,id',
            'rekening_bank_id' => 'nullable|required_if:metode_pembayaran,Bank Transfer|exists:rekening_bank,id',
            'catatan' => 'nullable|string|max:255',
            'no_referensi' => 'nullable|string|max:100',
            // If it needs to be updatable, add 'nomor_pembayaran' => 'required|string|unique:pembayaran_piutang,nomor,'.$pembayaran->id
        ]);

        DB::beginTransaction();
        try {
            $originalJumlahPembayaran = $pembayaran->jumlah;
            $originalMetodePembayaran = $pembayaran->metode_pembayaran;
            $originalKasId = $pembayaran->kas_id;
            $originalRekeningBankId = $pembayaran->rekening_bank_id;
            $originalInvoiceId = $pembayaran->invoice_id;
            $originalCustomerId = $pembayaran->customer_id; // For payments without invoice

            // --- Revert old financial records and invoice status ---
            // Revert Kas/Bank transaction
            if ($originalMetodePembayaran === 'Kas' && $originalKasId) {
                $kas = Kas::find($originalKasId);
                if ($kas) {
                    $kas->saldo -= $originalJumlahPembayaran;
                    $kas->save();
                }
                TransaksiKas::where('related_id', $pembayaran->id)->where('related_type', PembayaranPiutang::class)->delete();
            } elseif ($originalMetodePembayaran === 'Bank Transfer' && $originalRekeningBankId) {
                $rekening = RekeningBank::find($originalRekeningBankId);
                if ($rekening) {
                    $rekening->saldo -= $originalJumlahPembayaran;
                    $rekening->save();
                }
                TransaksiBank::where('related_id', $pembayaran->id)->where('related_type', PembayaranPiutang::class)->delete();
            }

            $originalSalesOrderToUpdate = null;
            if ($originalInvoiceId) {
                $originalInvoice = Invoice::find($originalInvoiceId);
                if ($originalInvoice) {
                    $originalInvoice->sisa_piutang += $originalJumlahPembayaran;
                    if (round($originalInvoice->sisa_piutang, 2) >= round($originalInvoice->total, 2) - 0.001) {
                        $originalInvoice->status = 'belum_bayar';
                    } else {
                        $originalInvoice->status = 'sebagian';
                    }
                    $originalInvoice->save();

                    if ($originalInvoice->sales_order_id) {
                        $originalSalesOrderToUpdate = SalesOrder::find($originalInvoice->sales_order_id);
                    }
                }
            }

            // --- Apply new payment details ---
            $pembayaran->tanggal = $validatedData['tanggal_pembayaran'];
            $pembayaran->jumlah = $validatedData['jumlah_pembayaran'];
            $pembayaran->metode_pembayaran = $validatedData['metode_pembayaran'];

            // Store kas and rekening information in catatan field instead of separate columns
            $originalCatatan = $validatedData['catatan'] ?? '';

            if ($validatedData['metode_pembayaran'] === 'Kas' && isset($validatedData['kas_id'])) {
                $kas = Kas::find($validatedData['kas_id']);
                $kasCatatan = "Pembayaran melalui Kas: " . ($kas ? $kas->nama : 'N/A');
                $pembayaran->catatan = $originalCatatan ? ($originalCatatan . '. ' . $kasCatatan) : $kasCatatan;
                $pembayaran->kas_id = $validatedData['kas_id']; // Tetap simpan untuk relasi
            } elseif ($validatedData['metode_pembayaran'] === 'Bank Transfer' && isset($validatedData['rekening_bank_id'])) {
                $rekening = RekeningBank::find($validatedData['rekening_bank_id']);
                $rekeningCatatan = "Pembayaran melalui Bank: " .
                    ($rekening ? $rekening->nama_bank . " - " . $rekening->nomor_rekening . " a.n " . $rekening->atas_nama : 'N/A');
                $pembayaran->catatan = $originalCatatan ? ($originalCatatan . '. ' . $rekeningCatatan) : $rekeningCatatan;
                $pembayaran->rekening_bank_id = $validatedData['rekening_bank_id']; // Tetap simpan untuk relasi
            } else {
                $pembayaran->catatan = $originalCatatan;
            }

            $pembayaran->no_referensi = $validatedData['no_referensi'] ?? null;
            $pembayaran->user_id = Auth::id();

            $newInvoice = null;
            $newSalesOrderToUpdate = null;

            if ($request->invoice_id) {
                $newInvoice = Invoice::findOrFail($request->invoice_id);
                $pembayaran->customer_id = $newInvoice->customer_id;
                $pembayaran->invoice_id = $request->invoice_id;

                $currentSisaPiutangNewInvoice = $newInvoice->sisa_piutang;
                // If the invoice being paid is the same as the original, its sisa_piutang was already increased.
                // If it's a different invoice, currentSisaPiutangNewInvoice is its actual current balance.

                if (round($pembayaran->jumlah, 2) > round($currentSisaPiutangNewInvoice, 2) + 0.001) {
                    DB::rollBack();
                    return back()->withInput()->withErrors(['jumlah_pembayaran' => 'Jumlah pembayaran (Rp ' . number_format($pembayaran->jumlah, 2, ',', '.') . ') melebihi sisa piutang (Rp ' . number_format($currentSisaPiutangNewInvoice, 2, ',', '.') . ') untuk invoice ini.']);
                }

                $newInvoice->sisa_piutang -= $pembayaran->jumlah;
                if ($newInvoice->sisa_piutang <= 0.009) {
                    $newInvoice->sisa_piutang = 0;
                    $newInvoice->status = 'lunas';
                } else {
                    $newInvoice->status = 'sebagian';
                }
                $newInvoice->save();
                if ($newInvoice->sales_order_id) {
                    $newSalesOrderToUpdate = SalesOrder::find($newInvoice->sales_order_id);
                }
            } else {
                $pembayaran->customer_id = $validatedData['customer_id'];
                $pembayaran->invoice_id = null;
            }
            $pembayaran->save();

            // Update Sales Order statuses 
            if ($originalSalesOrderToUpdate && (!$newSalesOrderToUpdate || $originalSalesOrderToUpdate->id != $newSalesOrderToUpdate->id)) {
                // Update status of the SO from the original invoice if it's different from the new SO or if there's no new SO from invoice
                $totalSisaPiutangSO = $originalSalesOrderToUpdate->invoices()->sum('sisa_piutang');
                if ($totalSisaPiutangSO <= 0.009) {
                    $originalSalesOrderToUpdate->status_pembayaran = 'Lunas';
                } else {
                    $anyPaymentMade = $originalSalesOrderToUpdate->invoices()->where('status', '!=', 'Belum Lunas')->exists();
                    if ($anyPaymentMade) {
                        $originalSalesOrderToUpdate->status_pembayaran = 'sebagian';
                    } else {
                        $originalSalesOrderToUpdate->status_pembayaran = 'lunas';
                    }
                }
                $originalSalesOrderToUpdate->save();
            }

            if ($newSalesOrderToUpdate) {
                // Always update the status of the SO related to the new/updated invoice
                $totalSisaPiutangSO = $newSalesOrderToUpdate->invoices()->sum('sisa_piutang');
                if ($totalSisaPiutangSO <= 0.009) {
                    $newSalesOrderToUpdate->status_pembayaran = 'lunas';
                } else {
                    $anyPaymentMade = $newSalesOrderToUpdate->invoices()->where('status', '!=', 'belum_dibayar')->exists();
                    if ($anyPaymentMade) {
                        $newSalesOrderToUpdate->status_pembayaran = 'sebagian';
                    } else {
                        $newSalesOrderToUpdate->status_pembayaran = 'belum_dibayar';
                    }
                }
                $newSalesOrderToUpdate->save();
            }

            $customerName = Customer::find($pembayaran->customer_id)->nama ?? 'N/A';
            $invoiceNumber = $newInvoice ? $newInvoice->nomor_invoice : 'Tanpa Invoice';

            if ($pembayaran->metode_pembayaran === 'Kas' && $pembayaran->kas_id) {
                $kas = Kas::findOrFail($pembayaran->kas_id);
                $kas->saldo += $pembayaran->jumlah; // Use $pembayaran->jumlah
                $kas->save();
                TransaksiKas::create([
                    'tanggal' => $pembayaran->tanggal, // Use $pembayaran->tanggal
                    'kas_id' => $pembayaran->kas_id,
                    'jenis' => 'masuk',
                    'jumlah' => $pembayaran->jumlah, // Use $pembayaran->jumlah
                    'keterangan' => 'Update Penerimaan piutang dari ' . $customerName . ' (Inv: ' . $invoiceNumber . ')',
                    'no_bukti' => $pembayaran->nomor, // Use $pembayaran->nomor
                    'related_id' => $pembayaran->id,
                    'related_type' => PembayaranPiutang::class,
                    'user_id' => Auth::id()
                ]);
            } elseif ($pembayaran->metode_pembayaran === 'Bank Transfer' && $pembayaran->rekening_bank_id) {
                $rekening = RekeningBank::findOrFail($pembayaran->rekening_bank_id);
                $rekening->saldo += $pembayaran->jumlah; // Use $pembayaran->jumlah
                $rekening->save();
                TransaksiBank::create([
                    'tanggal' => $pembayaran->tanggal, // Use $pembayaran->tanggal
                    'rekening_id' => $pembayaran->rekening_bank_id,
                    'jenis' => 'masuk',
                    'jumlah' => $pembayaran->jumlah, // Use $pembayaran->jumlah
                    'keterangan' => 'Update Penerimaan piutang dari ' . $customerName . ' (Inv: ' . $invoiceNumber . ')',
                    'no_referensi' => $pembayaran->no_referensi,
                    'no_bukti' => $pembayaran->nomor, // Use $pembayaran->nomor
                    'related_id' => $pembayaran->id,
                    'related_type' => PembayaranPiutang::class,
                    'user_id' => Auth::id()
                ]);
            }

            DB::commit();
            return redirect()->route('keuangan.pembayaran-piutang.show', $pembayaran->id)
                ->with('success', 'Pembayaran piutang berhasil diperbarui. Nomor: ' . $pembayaran->nomor); // Use $pembayaran->nomor

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Error updating payment: ' . $e->getMessage() . ' Stack: ' . $e->getTraceAsString());
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui pembayaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pembayaran = PembayaranPiutang::findOrFail($id);

        DB::beginTransaction();
        try {
            $originalJumlahPembayaran = $pembayaran->jumlah; // Store before deleting payment related records
            $originalInvoiceId = $pembayaran->invoice_id;

            if ($pembayaran->metode_pembayaran === 'Kas' && $pembayaran->kas_id) {
                $kas = Kas::find($pembayaran->kas_id);
                if ($kas) {
                    $kas->saldo -= $pembayaran->jumlah; // Use $pembayaran->jumlah
                    $kas->save();
                }
                TransaksiKas::where('related_id', $pembayaran->id)->where('related_type', PembayaranPiutang::class)->delete();
            } elseif ($pembayaran->metode_pembayaran === 'Bank Transfer' && $pembayaran->rekening_bank_id) {
                $rekening = RekeningBank::find($pembayaran->rekening_bank_id);
                if ($rekening) {
                    $rekening->saldo -= $pembayaran->jumlah; // Use $pembayaran->jumlah
                    $rekening->save();
                }
                TransaksiBank::where('related_id', $pembayaran->id)->where('related_type', PembayaranPiutang::class)->delete();
            }

            $invoiceIdToRedirect = $pembayaran->invoice_id; // Store before deleting payment

            if ($pembayaran->invoice_id) {
                $invoice = Invoice::find($pembayaran->invoice_id);
                if ($invoice) {
                    $invoice->sisa_piutang += $pembayaran->jumlah; // Use $pembayaran->jumlah
                    if (round($invoice->sisa_piutang, 2) >= round($invoice->total_invoice, 2) - 0.001) { // Check against total_invoice
                        $invoice->status = 'lunas';
                    } else {
                        $invoice->status = 'sebagian';
                    }
                    $invoice->save();

                    // Update SalesOrder status
                    if ($invoice->sales_order_id) {
                        $salesOrder = SalesOrder::find($invoice->sales_order_id);
                        if ($salesOrder) {
                            $totalSisaPiutangSO = $salesOrder->invoices()->sum('sisa_piutang');
                            if ($totalSisaPiutangSO <= 0.009) {
                                $salesOrder->status_pembayaran = 'lunas';
                            } else {
                                // Check if any payment still exists for any invoice of this SO
                                $anyPaymentMade = $salesOrder->invoices()->where('status', '!=', 'belum_lunas')->exists();
                                if ($anyPaymentMade) {
                                    $salesOrder->status_pembayaran = 'sebagian';
                                } else {
                                    $salesOrder->status_pembayaran = 'belum_lunas';
                                }
                            }
                            $salesOrder->save();
                        }
                    }
                }
            }

            $pembayaran->delete();

            DB::commit();

            if ($invoiceIdToRedirect) {
                return redirect()->route('keuangan.piutang-usaha.show', $invoiceIdToRedirect)->with('success', 'Pembayaran piutang berhasil dihapus.');
            }
            return redirect()->route('keuangan.pembayaran-piutang.index')->with('success', 'Pembayaran piutang berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Error deleting payment: ' . $e->getMessage() . ' Stack: ' . $e->getTraceAsString());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus pembayaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Print the specified resource.
     */
    public function print(string $id)
    {
        $pembayaran = PembayaranPiutang::with(['invoice.customer', 'customer', 'user', 'kas', 'rekeningBank', 'invoice.details.produk'])->findOrFail($id);
        // For DOMPDF or other PDF libraries, ensure they are installed and configured.
        // Example:
        // if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
        //     $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('keuangan.pembayaran_piutang.print', compact('pembayaran'));
        //     return $pdf->stream('pembayaran-'.$pembayaran->nomor.'.pdf'); // Changed nomor_pembayaran to nomor
        // }
        return view('keuangan.pembayaran_piutang.print', compact('pembayaran'));
    }

    /**
     * Get invoices for a specific customer.
     */
    public function getCustomerInvoices(Request $request, Customer $customer)
    {
        if ($request->ajax()) {
            $invoices = Invoice::where('customer_id', $customer->id)
                ->whereIn('status', ['Belum Lunas', 'Lunas Sebagian']) // Use consistent status values with correct column name
                ->orderBy('tanggal', 'desc')
                ->select('id', 'nomor', 'total', 'tanggal')
                ->get()
                ->map(function ($invoice) {
                    return [
                        'id' => $invoice->id,
                        'nomor_invoice' => $invoice->nomor,
                        'total_invoice' => $invoice->total,
                        'sisa_piutang' => $invoice->sisa_piutang,
                        'tanggal_invoice' => $invoice->tanggal
                    ];
                });

            return response()->json($invoices);
        }
        return response()->json([], 400); // Bad request if not AJAX
    }
}
