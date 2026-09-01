<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PembayaranPiutang;
use App\Models\PembayaranPiutangDetail;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Http\Controllers\Penjualan\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Kas;
use App\Models\RekeningBank;
use App\Models\TransaksiKas;
use App\Models\TransaksiBank;
use App\Services\NotificationService;
use App\Models\LogAktivitas;

class PembayaranPiutangController extends Controller
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
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembayaranPiutangs = PembayaranPiutang::with([
            'details.invoice',
            'invoices',
            'customer',
            'user',
            'kas',
            'rekeningBank'
        ])->latest()->paginate(10);

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
        $availableInvoices = collect();

        if ($invoiceId) {
            $invoice = Invoice::with(['customer', 'pembayaranDetails', 'uangMukaAplikasi'])->find($invoiceId);
            if ($invoice) {
                $sisaPiutang = (float)$invoice->sisa_piutang;
                $customer = $invoice->customer;

                // Load all unpaid invoices for this customer
                $availableInvoices = Invoice::where('customer_id', $customer->id)
                    ->whereIn('status', ['Belum Lunas', 'Lunas Sebagian', 'belum_bayar', 'sebagian'])
                    ->orderBy('tanggal', 'asc')
                    ->get()
                    ->filter(function ($inv) {
                        return (float)$inv->sisa_piutang > 0;
                    })
                    ->values();
            }
        }

        $today = date('Ymd');
        $prefix = 'BPP-' . $today . '-';

        $lastPayment = PembayaranPiutang::where('nomor', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 0;
        if ($lastPayment && $lastPayment->nomor) {
            $parts = explode('-', $lastPayment->nomor);
            if (count($parts) === 3 && strlen($parts[2]) === 4) {
                $lastNumber = (int)end($parts);
            } else if (count($parts) > 3) {
                $lastNumber = (int)array_pop($parts);
            }
        }

        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $nomorPembayaran = $prefix . $newNumber;

        return view('keuangan.pembayaran_piutang.create', compact(
            'invoice',
            'sisaPiutang',
            'customer',
            'customers',
            'nomorPembayaran',
            'kasAccounts',
            'bankAccounts',
            'availableInvoices'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Normalize request fields if needed
        if ($request->has('tanggal') && !$request->has('tanggal_pembayaran')) {
            $request->merge(['tanggal_pembayaran' => $request->input('tanggal')]);
        }
        if ($request->has('jumlah') && !$request->has('jumlah_pembayaran')) {
            $request->merge(['jumlah_pembayaran' => $request->input('jumlah')]);
        }

        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customer,id',
            'invoice_id' => 'nullable|exists:invoice,id',
            'tanggal_pembayaran' => 'required|date',
            'jumlah_pembayaran' => 'required|numeric|min:0.01',
            'metode_pembayaran' => 'required|string|in:Kas,Bank Transfer,Giro,Cek,kas,bank,giro,cek,tunai,transfer',
            'kas_id' => 'nullable|required_if:metode_pembayaran,Kas,kas,tunai|exists:kas,id',
            'rekening_bank_id' => 'nullable|required_if:metode_pembayaran,Bank Transfer,bank,Giro,giro,Cek,cek,transfer|exists:rekening_bank,id',
            'catatan' => 'nullable|string|max:500',
            'no_referensi' => 'nullable|string|max:100',
            'allocations' => 'nullable|array',
            'allocations.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $customer = Customer::findOrFail($validatedData['customer_id']);
            $totalBayar = (float)$validatedData['jumlah_pembayaran'];

            // Process allocations
            $allocations = [];
            if ($request->has('allocations') && is_array($request->allocations)) {
                foreach ($request->allocations as $invId => $amount) {
                    $amountFloat = (float)$amount;
                    if ($amountFloat > 0) {
                        $allocations[$invId] = $amountFloat;
                    }
                }
            }

            // Fallback for single invoice input if no allocations array provided
            if (empty($allocations) && !empty($validatedData['invoice_id'])) {
                $allocations[$validatedData['invoice_id']] = $totalBayar;
            }

            // If allocations are provided, validate amounts against remaining receivables
            $totalAllocated = array_sum($allocations);
            if (!empty($allocations) && abs($totalAllocated - $totalBayar) > 0.05) {
                DB::rollBack();
                return back()->withInput()->withErrors([
                    'jumlah_pembayaran' => 'Total alokasi invoice (Rp ' . number_format($totalAllocated, 0, ',', '.') .
                        ') harus sama dengan Jumlah Pembayaran (Rp ' . number_format($totalBayar, 0, ',', '.') . ').'
                ]);
            }

            // Verify each invoice belonging to this customer and validate remaining balance
            $validatedInvoices = [];
            foreach ($allocations as $invId => $amount) {
                $inv = Invoice::where('id', $invId)
                    ->where('customer_id', $customer->id)
                    ->first();

                if (!$inv) {
                    DB::rollBack();
                    return back()->withInput()->withErrors([
                        'allocations' => "Invoice ID #{$invId} tidak ditemukan atau bukan milik customer ini."
                    ]);
                }

                $sisaPiutang = (float)$inv->sisa_piutang;
                if (round($amount, 2) > round($sisaPiutang, 2) + 0.01) {
                    DB::rollBack();
                    return back()->withInput()->withErrors([
                        'allocations' => "Jumlah alokasi untuk Invoice {$inv->nomor} (Rp " . number_format($amount, 0, ',', '.') .
                            ") melebihi sisa piutang (Rp " . number_format($sisaPiutang, 0, ',', '.') . ")."
                    ]);
                }

                $validatedInvoices[$invId] = [
                    'model' => $inv,
                    'amount' => $amount,
                ];
            }

            // Generate Payment Number
            $paymentDate = date('Ymd', strtotime($request->tanggal_pembayaran));
            $prefix = 'BPP-' . $paymentDate . '-';
            $lastPaymentOnDate = PembayaranPiutang::where('nomor', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();
            $lastNum = 0;
            if ($lastPaymentOnDate && $lastPaymentOnDate->nomor) {
                $parts = explode('-', $lastPaymentOnDate->nomor);
                if (count($parts) === 3 && strlen($parts[2]) === 4) {
                    $lastNum = (int)end($parts);
                } else if (count($parts) > 3) {
                    $lastNum = (int)array_pop($parts);
                }
            }
            $newNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);

            // Create Payment Header
            $pembayaran = new PembayaranPiutang();
            $pembayaran->nomor = $prefix . $newNum;
            $pembayaran->tanggal = $validatedData['tanggal_pembayaran'];
            $pembayaran->jumlah = $totalBayar;
            $pembayaran->metode_pembayaran = $validatedData['metode_pembayaran'];
            $pembayaran->customer_id = $customer->id;
            $pembayaran->user_id = Auth::id();
            $pembayaran->no_referensi = $validatedData['no_referensi'] ?? null;

            // If exactly 1 invoice is allocated, store its ID on header for backward compatibility
            if (count($validatedInvoices) === 1) {
                $pembayaran->invoice_id = array_key_first($validatedInvoices);
            } else {
                $pembayaran->invoice_id = null;
            }

            // Setup kas / bank relations & notes
            $originalCatatan = $validatedData['catatan'] ?? '';
            $isKas = in_array(strtolower($validatedData['metode_pembayaran']), ['kas', 'tunai']);

            if ($isKas && !empty($validatedData['kas_id'])) {
                $kas = Kas::find($validatedData['kas_id']);
                $kasCatatan = "Pembayaran melalui Kas: " . ($kas ? $kas->nama : 'N/A');
                $pembayaran->catatan = $originalCatatan ? ($originalCatatan . '. ' . $kasCatatan) : $kasCatatan;
                $pembayaran->kas_id = $validatedData['kas_id'];
            } elseif (!empty($validatedData['rekening_bank_id'])) {
                $rekening = RekeningBank::find($validatedData['rekening_bank_id']);
                $rekeningCatatan = "Pembayaran melalui Bank: " .
                    ($rekening ? $rekening->nama_bank . " - " . $rekening->nomor_rekening . " a.n " . $rekening->atas_nama : 'N/A');
                $pembayaran->catatan = $originalCatatan ? ($originalCatatan . '. ' . $rekeningCatatan) : $rekeningCatatan;
                $pembayaran->rekening_bank_id = $validatedData['rekening_bank_id'];
            } else {
                $pembayaran->catatan = $originalCatatan;
            }

            $pembayaran->save();

            // Create Detail records and update invoices status
            $notificationService = new NotificationService();
            $processedInvoiceNumbers = [];

            foreach ($validatedInvoices as $invId => $item) {
                $inv = $item['model'];
                $amount = $item['amount'];

                $itemNote = $request->input("catatan_allocations.{$inv->id}") ?: $originalCatatan;

                PembayaranPiutangDetail::create([
                    'pembayaran_piutang_id' => $pembayaran->id,
                    'invoice_id' => $inv->id,
                    'jumlah' => $amount,
                    'catatan' => $itemNote,
                ]);

                // Calculate remaining balance after this allocation
                $sisaAfter = (float)$inv->sisa_piutang; // Recalculated including new detail
                if ($sisaAfter <= 0.01) {
                    $inv->status = 'lunas';
                } else {
                    $inv->status = 'sebagian';
                }
                $inv->save();

                // Update Sales Order status if associated
                if ($inv->sales_order_id) {
                    InvoiceController::updateSalesOrderStatusFromPayment($inv->sales_order_id);
                }

                $processedInvoiceNumbers[] = $inv->nomor;

                // Send notification for each paid invoice
                try {
                    $notificationService->notifyPaymentReceived($pembayaran, $inv);
                } catch (\Exception $e) {
                    Log::warning('Failed sending payment notification: ' . $e->getMessage());
                }
            }

            DB::commit();

            // Log activity
            $this->logUserAktivitas(
                'tambah',
                'piutang_usaha',
                $pembayaran->id,
                [
                    'nomor' => $pembayaran->nomor,
                    'jumlah' => $pembayaran->jumlah,
                    'customer' => $customer->nama,
                    'invoices' => implode(', ', $processedInvoiceNumbers),
                ]
            );

            return redirect()
                ->route('keuangan.pembayaran-piutang.show', $pembayaran->id)
                ->with('success', 'Pembayaran piutang berhasil dicatat. Nomor: ' . $pembayaran->nomor .
                    (!empty($processedInvoiceNumbers) ? ' untuk Invoice: ' . implode(', ', $processedInvoiceNumbers) : ''));

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving PembayaranPiutang: ' . $e->getMessage() . ' Stack: ' . $e->getTraceAsString());
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan pembayaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pembayaran = PembayaranPiutang::with([
            'details.invoice',
            'invoices',
            'customer',
            'user',
            'kas',
            'rekeningBank'
        ])->findOrFail($id);

        $logs = LogAktivitas::where('modul', 'piutang_usaha')
            ->where('data_id', $id)
            ->with('user')
            ->latest()
            ->get();

        return view('keuangan.pembayaran_piutang.show', compact('pembayaran', 'logs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama') && !$user->hasRole('administrator') && !$user->hasRole('admin')) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengedit pembayaran piutang.');
        }

        $pembayaran = PembayaranPiutang::with(['details.invoice', 'customer'])->findOrFail($id);
        $customers = Customer::orderBy('nama')->get();
        $kasAccounts = Kas::where('is_aktif', true)->get();
        $bankAccounts = RekeningBank::where('is_aktif', true)->get();

        $invoice = null;
        $sisaPiutangSaatIni = 0;
        $sisaPiutangUntukEdit = 0;

        if ($pembayaran->invoice_id) {
            $invoice = Invoice::with('customer')->find($pembayaran->invoice_id);
            if ($invoice) {
                $sisaPiutangSaatIni = (float)$invoice->sisa_piutang;
                $sisaPiutangUntukEdit = (float)$invoice->sisa_piutang + (float)$pembayaran->jumlah;
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
        $user = Auth::user();
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama') && !$user->hasRole('administrator') && !$user->hasRole('admin')) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengupdate pembayaran piutang.');
        }

        $pembayaran = PembayaranPiutang::with('details.invoice')->findOrFail($id);
        $oldData = $pembayaran->getOriginal();

        $validatedData = $request->validate([
            'tanggal_pembayaran' => 'required|date',
            'jumlah_pembayaran' => 'required|numeric|min:0.01',
            'metode_pembayaran' => 'required|string|in:Kas,Bank Transfer',
            'kas_id' => 'nullable|required_if:metode_pembayaran,Kas|exists:kas,id',
            'rekening_bank_id' => 'nullable|required_if:metode_pembayaran,Bank Transfer|exists:rekening_bank,id',
            'catatan' => 'nullable|string|max:255',
            'no_referensi' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            $pembayaran->tanggal = $validatedData['tanggal_pembayaran'];
            $pembayaran->metode_pembayaran = $validatedData['metode_pembayaran'];
            $pembayaran->no_referensi = $validatedData['no_referensi'] ?? null;

            $originalCatatan = $validatedData['catatan'] ?? '';
            $pembayaran->kas_id = null;
            $pembayaran->rekening_bank_id = null;

            if ($validatedData['metode_pembayaran'] === 'Kas' && isset($validatedData['kas_id'])) {
                $kas = Kas::find($validatedData['kas_id']);
                $kasCatatan = "Pembayaran melalui Kas: " . ($kas ? $kas->nama : 'N/A');
                $pembayaran->catatan = $originalCatatan ? ($originalCatatan . '. ' . $kasCatatan) : $kasCatatan;
                $pembayaran->kas_id = $validatedData['kas_id'];
            } elseif ($validatedData['metode_pembayaran'] === 'Bank Transfer' && isset($validatedData['rekening_bank_id'])) {
                $rekening = RekeningBank::find($validatedData['rekening_bank_id']);
                $rekeningCatatan = "Pembayaran melalui Bank: " .
                    ($rekening ? $rekening->nama_bank . " - " . $rekening->nomor_rekening . " a.n " . $rekening->atas_nama : 'N/A');
                $pembayaran->catatan = $originalCatatan ? ($originalCatatan . '. ' . $rekeningCatatan) : $rekeningCatatan;
                $pembayaran->rekening_bank_id = $validatedData['rekening_bank_id'];
            } else {
                $pembayaran->catatan = $originalCatatan;
            }

            // If single invoice legacy update
            if ($pembayaran->details->count() === 1) {
                $detail = $pembayaran->details->first();
                $inv = $detail->invoice;
                if ($inv) {
                    $newAmount = (float)$validatedData['jumlah_pembayaran'];
                    $currentSisa = (float)$inv->sisa_piutang + (float)$detail->jumlah;
                    if ($newAmount > $currentSisa + 0.01) {
                        DB::rollBack();
                        return back()->withInput()->withErrors([
                            'jumlah_pembayaran' => 'Jumlah pembayaran (Rp ' . number_format($newAmount, 0, ',', '.') .
                                ') melebihi sisa piutang (Rp ' . number_format($currentSisa, 0, ',', '.') . ') untuk invoice ini.'
                        ]);
                    }

                    $detail->jumlah = $newAmount;
                    $detail->save();

                    $pembayaran->jumlah = $newAmount;

                    $sisaAfter = (float)$inv->sisa_piutang;
                    $inv->status = ($sisaAfter <= 0.01) ? 'lunas' : 'sebagian';
                    $inv->save();

                    if ($inv->sales_order_id) {
                        InvoiceController::updateSalesOrderStatusFromPayment($inv->sales_order_id);
                    }
                }
            }

            $pembayaran->save();
            DB::commit();

            return redirect()->route('keuangan.pembayaran-piutang.show', $pembayaran->id)
                ->with('success', 'Pembayaran piutang berhasil diperbarui. Nomor: ' . $pembayaran->nomor);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui pembayaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        if (!$user->hasRole('superadmin') && !$user->hasRole('direktur_utama') && !$user->hasRole('administrator') && !$user->hasRole('admin')) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk menghapus pembayaran piutang.');
        }

        $pembayaran = PembayaranPiutang::with('details.invoice')->findOrFail($id);

        $paymentLogDetail = [
            'nomor' => $pembayaran->nomor,
            'jumlah' => $pembayaran->jumlah,
            'metode_pembayaran' => $pembayaran->metode_pembayaran,
            'tanggal' => $pembayaran->tanggal,
            'customer' => $pembayaran->customer->nama ?? '-',
        ];

        DB::beginTransaction();
        try {
            $invoicesToUpdate = [];
            foreach ($pembayaran->details as $detail) {
                if ($detail->invoice) {
                    $invoicesToUpdate[] = [
                        'invoice' => $detail->invoice,
                        'detail_id' => $detail->id,
                        'amount' => (float)$detail->jumlah,
                    ];
                }
            }

            if ($pembayaran->metode_pembayaran === 'Kas' && $pembayaran->kas_id) {
                TransaksiKas::where('related_id', $pembayaran->id)->where('related_type', PembayaranPiutang::class)->delete();
            } elseif ($pembayaran->metode_pembayaran === 'Bank Transfer' && $pembayaran->rekening_bank_id) {
                TransaksiBank::where('related_id', $pembayaran->id)->where('related_type', PembayaranPiutang::class)->delete();
            }

            $pembayaran->delete();

            // Recalculate status for all affected invoices
            foreach ($invoicesToUpdate as $item) {
                $inv = $item['invoice'];
                $sisa = (float)$inv->sisa_piutang;
                if ($sisa >= (float)$inv->total - 0.01) {
                    $inv->status = 'belum_bayar';
                } else {
                    $inv->status = 'sebagian';
                }
                $inv->save();

                if ($inv->sales_order_id) {
                    InvoiceController::updateSalesOrderStatusFromPayment($inv->sales_order_id);
                }
            }

            DB::commit();

            $this->logUserAktivitas('hapus', 'piutang_usaha', $id, $paymentLogDetail);

            return redirect()->route('keuangan.pembayaran-piutang.index')->with('success', 'Pembayaran piutang berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus pembayaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Print the specified resource.
     */
    public function print(string $id)
    {
        $pembayaran = PembayaranPiutang::with([
            'details.invoice',
            'invoices',
            'customer',
            'user',
            'kas',
            'rekeningBank'
        ])->findOrFail($id);

        return view('keuangan.pembayaran_piutang.print', compact('pembayaran'));
    }

    /**
     * Get unpaid / partially paid invoices for a specific customer.
     */
    public function getCustomerInvoices(Request $request, Customer $customer)
    {
        $invoices = Invoice::where('customer_id', $customer->id)
            ->whereIn('status', ['Belum Lunas', 'Lunas Sebagian', 'belum_bayar', 'sebagian'])
            ->orderBy('tanggal', 'asc')
            ->get()
            ->filter(function ($invoice) {
                return (float)$invoice->sisa_piutang > 0;
            })
            ->values()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'nomor_invoice' => $invoice->nomor,
                    'total_invoice' => (float)$invoice->total,
                    'sisa_piutang' => (float)$invoice->sisa_piutang,
                    'tanggal_invoice' => $invoice->tanggal,
                    'jatuh_tempo' => $invoice->jatuh_tempo ? date('d/m/Y', strtotime($invoice->jatuh_tempo)) : '-',
                ];
            });

        return response()->json($invoices);
    }
}
