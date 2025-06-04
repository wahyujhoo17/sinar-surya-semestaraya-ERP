<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\NotaKredit;
use App\Models\NotaKreditDetail;
use App\Models\ReturPenjualan;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\Invoice;
use App\Models\LogAktivitas;
use App\Helpers\ReturPenjualanDebugger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class NotaKreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = NotaKredit::with(['customer', 'returPenjualan', 'user']);

        // Apply sorting
        $sortField = $request->input('sort', 'tanggal');
        $sortDirection = $request->input('direction', 'desc');

        // Validate sort field to prevent SQL injection
        $allowedSortFields = ['nomor', 'tanggal', 'customer_id', 'retur_penjualan_id', 'total', 'status', 'created_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Status filter
        $status = $request->input('status', 'semua');
        $validStatuses = ['draft', 'diproses', 'selesai'];

        if ($status !== 'semua' && in_array($status, $validStatuses)) {
            $query->where('status', $status);
        }

        // Search functionality
        $search = $request->input('search', '');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%")
                            ->orWhere('company', 'like', "%{$search}%");
                    });
            });
        }

        // Date filter
        $dateStart = $request->input('date_start', '');
        $dateEnd = $request->input('date_end', '');
        if ($dateStart && $dateEnd) {
            $query->whereBetween('tanggal', [$dateStart, $dateEnd]);
        } elseif ($dateStart) {
            $query->whereDate('tanggal', '>=', $dateStart);
        } elseif ($dateEnd) {
            $query->whereDate('tanggal', '<=', $dateEnd);
        }

        // Customer filter
        $customerId = $request->input('customer_id', '');
        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        $notaKredits = $query->paginate(15)->withQueryString();
        $customers = Customer::where('is_active', true)->orderBy('nama')->get();

        // Status counts for summary cards
        $statusCounts = [
            'draft' => NotaKredit::where('status', 'draft')->count(),
            'diproses' => NotaKredit::where('status', 'diproses')->count(),
            'selesai' => NotaKredit::where('status', 'selesai')->count(),
            'semua' => NotaKredit::count()
        ];

        // Check if this is an AJAX request
        if ($request->ajax()) {
            return response()->json([
                'table' => view('penjualan.nota_kredit.partials.table', compact('notaKredits'))->render(),
                'pagination' => view('penjualan.nota_kredit.partials.pagination', compact('notaKredits'))->render(),
            ]);
        }

        return view('penjualan.nota_kredit.index', compact(
            'notaKredits',
            'statusCounts',
            'validStatuses',
            'customers',
            'status',
            'search',
            'dateStart',
            'dateEnd',
            'customerId',
            'sortField',
            'sortDirection'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {


        $returId = $request->input('retur_id');
        $returPenjualan = null;

        if ($returId) {
            $returPenjualan = ReturPenjualan::with(['customer', 'salesOrder', 'details.produk', 'details.satuan'])
                ->findOrFail($returId);

            // Validate return status - only completed returns can have credit notes
            if ($returPenjualan->status !== 'selesai') {
                return redirect()->route('penjualan.retur.show', $returId)
                    ->with('error', 'Hanya retur penjualan dengan status selesai yang dapat dibuatkan nota kredit.');
            }

            // Check if credit note already exists
            if ($returPenjualan->nota_kredit_id) {
                return redirect()->route('penjualan.nota-kredit.show', $returPenjualan->nota_kredit_id)
                    ->with('error', 'Nota kredit sudah ada untuk retur penjualan ini.');
            }
        }

        // Generate nomor nota kredit
        $today = date('Ymd');
        $lastNota = NotaKredit::where('nomor', 'like', "NK-{$today}%")
            ->orderBy('nomor', 'desc')
            ->first();

        $sequence = '001';
        if ($lastNota) {
            $lastSequence = (int) substr($lastNota->nomor, -3);
            $sequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);
        }

        $nomorNotaKredit = "NK-{$today}-{$sequence}";
        $customers = Customer::where('is_active', true)->get();

        return view('penjualan.nota_kredit.create', compact(
            'returPenjualan',
            'nomorNotaKredit',
            'customers'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'nomor' => 'required|string|unique:nota_kredit,nomor',
            'tanggal' => 'required|date',
            'retur_penjualan_id' => 'required|exists:retur_penjualan,id',
            'customer_id' => 'required|exists:customer,id',
            'sales_order_id' => 'required|exists:sales_order,id',
            'catatan' => 'nullable|string',
            'total' => 'required|numeric|min:0',
            'details' => 'required|array|min:1',
            'details.*.produk_id' => 'required|exists:produk,id',
            'details.*.quantity' => 'required|numeric|min:0.01',
            'details.*.satuan_id' => 'required|exists:satuan,id',
            'details.*.harga' => 'required|numeric|min:0',
            'details.*.subtotal' => 'required|numeric|min:0',
        ]);



        // Check if retur is valid
        $returPenjualan = ReturPenjualan::with(['customer', 'salesOrder', 'details.produk'])
            ->findOrFail($validated['retur_penjualan_id']);

        if ($returPenjualan->status !== 'selesai') {
            return back()->withInput()->with('error', 'Hanya retur penjualan dengan status selesai yang dapat dibuatkan nota kredit.');
        }

        if ($returPenjualan->nota_kredit_id) {
            return back()->withInput()->with('error', 'Nota kredit sudah ada untuk retur penjualan ini.');
        }

        try {
            DB::beginTransaction();

            // Create nota kredit header
            $notaKredit = NotaKredit::create([
                'nomor' => $validated['nomor'],
                'tanggal' => $validated['tanggal'],
                'retur_penjualan_id' => $validated['retur_penjualan_id'],
                'customer_id' => $validated['customer_id'],
                'sales_order_id' => $validated['sales_order_id'],
                'user_id' => Auth::id(),
                'total' => $validated['total'],
                'status' => 'draft',
                'catatan' => $validated['catatan'] ?? null,
            ]);

            // Create nota kredit details
            foreach ($validated['details'] as $detail) {
                NotaKreditDetail::create([
                    'nota_kredit_id' => $notaKredit->id,
                    'produk_id' => $detail['produk_id'],
                    'quantity' => $detail['quantity'],
                    'satuan_id' => $detail['satuan_id'],
                    'harga' => $detail['harga'],
                    'subtotal' => $detail['subtotal']
                ]);
            }

            // Update retur penjualan with nota_kredit_id
            $returPenjualan->nota_kredit_id = $notaKredit->id;
            $returPenjualan->save();

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'tambah',
                'modul' => 'nota_kredit',
                'data_id' => $notaKredit->id,
                'ip_address' => request()->ip(),
                'detail' => "Membuat nota kredit {$notaKredit->nomor} untuk retur penjualan {$returPenjualan->nomor} dengan total Rp " . number_format($validated['total'], 0, ',', '.')
            ]);

            DB::commit();

            return redirect()->route('penjualan.nota-kredit.show', $notaKredit->id)
                ->with('success', 'Nota Kredit berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating nota kredit: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $notaKredit = NotaKredit::with([
            'returPenjualan',
            'customer',
            'salesOrder',
            'user',
            'details.produk',
            'details.satuan',
            'invoices'  // Add relationship to get invoices with pivot data
        ])->findOrFail($id);

        // Get log aktivitas
        $logAktivitas = LogAktivitas::with('user')
            ->where('modul', 'nota_kredit')
            ->where('data_id', $notaKredit->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Use the relationship to get applied invoices
        $appliedToInvoices = $notaKredit->invoices;

        // Log activity (viewing detail)
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'detail',
            'modul' => 'nota_kredit',
            'data_id' => $notaKredit->id,
            'ip_address' => request()->ip(),
            'detail' => "Melihat detail nota kredit {$notaKredit->nomor}"
        ]);

        return view('penjualan.nota_kredit.show', compact(
            'notaKredit',
            'logAktivitas',
            'appliedToInvoices'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $notaKredit = NotaKredit::with([
            'customer',
            'returPenjualan',
            'salesOrder',
            'user',
            'details.produk',
            'details.satuan'
        ])->findOrFail($id);

        if ($notaKredit->status !== 'draft') {
            return redirect()->route('penjualan.nota-kredit.show', $notaKredit->id)
                ->with('error', 'Hanya nota kredit dengan status draft yang dapat diedit.');
        }

        $customers = Customer::where('is_active', true)->orderBy('nama')->get();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'buka_form_edit',
            'modul' => 'nota_kredit',
            'data_id' => $notaKredit->id,
            'ip_address' => request()->ip(),
            'detail' => "Membuka form edit untuk nota kredit {$notaKredit->nomor}"
        ]);

        return view('penjualan.nota_kredit.edit', compact('notaKredit', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $notaKredit = NotaKredit::with(['details'])->findOrFail($id);

        // Validate that the nota kredit is still in draft status
        if ($notaKredit->status !== 'draft') {
            return redirect()->route('penjualan.nota-kredit.show', $notaKredit->id)
                ->with('error', 'Hanya nota kredit dengan status draft yang dapat diedit.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customer,id',
            'sales_order_id' => 'required|exists:sales_order,id',
            'catatan' => 'nullable|string',
            'total' => 'required|numeric|min:0',
            'details' => 'required|array|min:1',
            'details.*.produk_id' => 'required|exists:produk,id',
            'details.*.quantity' => 'required|numeric|min:0.01',
            'details.*.satuan_id' => 'required|exists:satuan,id',
            'details.*.harga' => 'required|numeric|min:0',
            'details.*.subtotal' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Update nota kredit header
            $notaKredit->update([
                'tanggal' => $validated['tanggal'],
                'customer_id' => $validated['customer_id'],
                'sales_order_id' => $validated['sales_order_id'],
                'total' => $validated['total'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            // Delete existing details
            NotaKreditDetail::where('nota_kredit_id', $notaKredit->id)->delete();

            // Create new nota kredit details
            foreach ($validated['details'] as $detail) {
                NotaKreditDetail::create([
                    'nota_kredit_id' => $notaKredit->id,
                    'produk_id' => $detail['produk_id'],
                    'quantity' => $detail['quantity'],
                    'satuan_id' => $detail['satuan_id'],
                    'harga' => $detail['harga'],
                    'subtotal' => $detail['subtotal']
                ]);
            }

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'edit',
                'modul' => 'nota_kredit',
                'data_id' => $notaKredit->id,
                'ip_address' => request()->ip(),
                'detail' => "Mengubah nota kredit {$notaKredit->nomor} dengan total Rp " . number_format($validated['total'], 0, ',', '.')
            ]);

            DB::commit();

            return redirect()->route('penjualan.nota-kredit.show', $notaKredit->id)
                ->with('success', 'Nota Kredit berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating nota kredit: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Process the credit note
     */
    public function processNotaKredit($id)
    {
        // Get a single NotaKredit model instance, not a collection
        $notaKredit = NotaKredit::with([
            'returPenjualan',
            'customer',
            'salesOrder',
            'details.produk'
        ])->where('id', $id)->first();

        if (!$notaKredit) {
            return redirect()->route('penjualan.nota-kredit.index')
                ->with('error', 'Nota kredit tidak ditemukan.');
        }

        // Validate status
        if ($notaKredit->status !== 'draft') {
            return redirect()->route('penjualan.nota-kredit.show', $notaKredit->id)
                ->with('error', 'Hanya nota kredit dengan status draft yang dapat diproses.');
        }

        try {
            DB::beginTransaction();

            // Update credit note status
            $notaKredit->update(['status' => 'diproses']);

            // Apply credit to sales order
            $salesOrder = $notaKredit->salesOrder;

            $remainingCredit = $this->applyCreditToInvoices($notaKredit);

            if ($salesOrder) {
                // If status is lunas, create overpayment
                if ($salesOrder->status_pembayaran === 'lunas') {
                    $salesOrder->kelebihan_bayar = ($salesOrder->kelebihan_bayar ?? 0) + $remainingCredit;
                    $salesOrder->status_pembayaran = 'kelebihan_bayar';
                }
                // If status is sebagian, reduce outstanding amount
                else if ($salesOrder->status_pembayaran === 'sebagian') {
                    $outstandingAmount = $salesOrder->total - ($salesOrder->total_pembayaran ?? 0);

                    if ($remainingCredit >= $outstandingAmount) {
                        // Credit note covers the entire outstanding amount
                        $salesOrder->kelebihan_bayar = $remainingCredit - $outstandingAmount;
                        $salesOrder->status_pembayaran = $salesOrder->kelebihan_bayar > 0 ? 'kelebihan_bayar' : 'lunas';
                    } else {
                        // Partial credit applied
                        $salesOrder->total_pembayaran = ($salesOrder->total_pembayaran ?? 0) + $remainingCredit;
                    }
                }

                $salesOrder->save();
            }

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'proses',
                'modul' => 'nota_kredit',
                'data_id' => $notaKredit->id,
                'ip_address' => request()->ip(),
                'detail' => "Memproses nota kredit {$notaKredit->nomor} dengan total Rp " . number_format($notaKredit->total, 0, ',', '.')
            ]);

            DB::commit();

            return redirect()->route('penjualan.nota-kredit.show', $notaKredit->id)
                ->with('success', 'Nota Kredit berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Complete the credit note
     */
    public function completeNotaKredit($id)
    {
        $notaKredit = NotaKredit::findOrFail($id);

        // Validate status
        if ($notaKredit->status !== 'diproses') {
            return redirect()->route('penjualan.nota-kredit.show', $notaKredit->id)
                ->with('error', 'Hanya nota kredit dengan status diproses yang dapat diselesaikan.');
        }

        try {
            DB::beginTransaction();

            // Update credit note status
            $notaKredit->update(['status' => 'selesai']);

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'selesai',
                'modul' => 'nota_kredit',
                'data_id' => $notaKredit->id,
                'ip_address' => request()->ip(),
                'detail' => "Menyelesaikan nota kredit {$notaKredit->nomor}"
            ]);

            DB::commit();

            return redirect()->route('penjualan.nota-kredit.show', $notaKredit->id)
                ->with('success', 'Nota Kredit berhasil diselesaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export PDF
     */
    public function exportPdf(string $id)
    {
        // Increase the execution time limit for PDF generation
        ini_set('max_execution_time', 300); // Set to 5 minutes (300 seconds)
        // Increase memory limit for larger PDF generation
        ini_set('memory_limit', '512M');

        $notaKredit = NotaKredit::with([
            'returPenjualan',
            'customer',
            'salesOrder',
            'user',
            'details.produk',
            'details.satuan'
        ])->findOrFail($id);


        $pdf = Pdf::loadView('penjualan.nota_kredit.pdf', compact('notaKredit'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Nota-Kredit-' . $notaKredit->nomor . '.pdf');
    }

    /**
     * Finalize the credit note, changing status from draft to diproses.
     * This is equivalent to processing the credit note.
     */
    public function finalize($id)
    {
        // Get a single NotaKredit model instance, not a collection
        $notaKredit = NotaKredit::with([
            'returPenjualan',
            'customer',
            'salesOrder',
            'details.produk'
        ])->where('id', $id)->first();

        if (!$notaKredit) {
            return redirect()->route('penjualan.nota-kredit.index')
                ->with('error', 'Nota kredit tidak ditemukan.');
        }

        // Validate status
        if ($notaKredit->status !== 'draft') {
            return redirect()->route('penjualan.nota-kredit.show', $notaKredit->id)
                ->with('error', 'Hanya nota kredit dengan status draft yang dapat difinalisasi.');
        }

        try {
            DB::beginTransaction();

            // Update credit note status
            $notaKredit->update(['status' => 'diproses']);

            // Apply credit to sales order
            $salesOrder = $notaKredit->salesOrder;

            $remainingCredit = $this->applyCreditToInvoices($notaKredit);

            if ($salesOrder) {
                // If status is lunas, create overpayment
                if ($salesOrder->status_pembayaran === 'lunas') {
                    $salesOrder->kelebihan_bayar = ($salesOrder->kelebihan_bayar ?? 0) + $remainingCredit;
                    $salesOrder->status_pembayaran = 'kelebihan_bayar';
                }
                // If status is sebagian, reduce outstanding amount
                else if ($salesOrder->status_pembayaran === 'sebagian') {
                    $outstandingAmount = $salesOrder->total - ($salesOrder->total_pembayaran ?? 0);

                    if ($remainingCredit >= $outstandingAmount) {
                        // Credit note covers the entire outstanding amount
                        $salesOrder->kelebihan_bayar = $remainingCredit - $outstandingAmount;
                        $salesOrder->status_pembayaran = $salesOrder->kelebihan_bayar > 0 ? 'kelebihan_bayar' : 'lunas';
                    } else {
                        // Partial credit applied
                        $salesOrder->total_pembayaran = ($salesOrder->total_pembayaran ?? 0) + $remainingCredit;
                    }
                }

                $salesOrder->save();
            }

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'finalisasi',
                'modul' => 'nota_kredit',
                'data_id' => $notaKredit->id,
                'ip_address' => request()->ip(),
                'detail' => "Memfinalisasi nota kredit {$notaKredit->nomor} dengan total Rp " . number_format($notaKredit->total, 0, ',', '.')
            ]);

            DB::commit();

            return redirect()->route('penjualan.nota-kredit.show', $notaKredit->id)
                ->with('success', 'Nota Kredit berhasil difinalisasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Helper method to apply credit to invoices
     * 
     * @param NotaKredit $notaKredit
     * @return float Remaining credit after applying to invoices
     */
    private function applyCreditToInvoices(NotaKredit $notaKredit)
    {
        // Find related invoices and apply credit to them first
        $relatedInvoices = Invoice::where('sales_order_id', $notaKredit->sales_order_id)
            ->where('status', '!=', 'lunas')
            ->orderBy('tanggal', 'asc')
            ->get();

        $remainingCredit = $notaKredit->total;

        foreach ($relatedInvoices as $invoice) {
            if ($remainingCredit <= 0) break;

            $sisaPiutang = $invoice->sisa_piutang;

            if ($sisaPiutang > 0) {
                $creditToApply = min($remainingCredit, $sisaPiutang);

                // Apply credit to invoice using the applyCredit method
                $invoice->applyCredit($creditToApply);

                // Create or update the relationship in the pivot table
                $notaKredit->invoices()->syncWithoutDetaching([
                    $invoice->id => ['applied_amount' => $creditToApply]
                ]);

                // Reduce remaining credit
                $remainingCredit -= $creditToApply;

                // Log the credit application
                LogAktivitas::create([
                    'user_id' => Auth::id(),
                    'aktivitas' => 'terapkan_kredit',
                    'modul' => 'nota_kredit',
                    'data_id' => $notaKredit->id,
                    'ip_address' => request()->ip(),
                    'detail' => "Menerapkan kredit sebesar Rp " . number_format($creditToApply, 0, ',', '.') . " ke invoice " . $invoice->nomor
                ]);
            }
        }

        return $remainingCredit;
    }

    /**
     * Apply a credit note to an invoice
     * 
     * @param int $notaKreditId
     * @param int $invoiceId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function applyToInvoice(Request $request, $notaKreditId, $invoiceId)
    {
        // Validate the request
        $request->validate([
            'amount' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Find the nota kredit
            $notaKredit = NotaKredit::where('id', $notaKreditId)->first();

            if (!$notaKredit) {
                return redirect()->route('penjualan.nota-kredit.index')
                    ->with('error', 'Nota kredit tidak ditemukan.');
            }

            // Check if the nota kredit has available credit
            $appliedTotal = $notaKredit->invoices()->sum('nota_kredit_invoice.applied_amount');
            $availableCredit = $notaKredit->total - $appliedTotal;

            if ($availableCredit <= 0) {
                return redirect()->route('penjualan.nota-kredit.show', $notaKredit->id)
                    ->with('error', 'Nota kredit ini tidak memiliki sisa kredit untuk diaplikasikan.');
            }

            // Find the invoice
            $invoice = Invoice::where('id', $invoiceId)->first();



            if (!$invoice) {
                return redirect()->route('penjualan.nota-kredit.show', $notaKredit->id)
                    ->with('error', 'Invoice tidak ditemukan.');
            }

            // Make sure the amount doesn't exceed the available credit or invoice balance
            $creditAmount = min($request->amount, $availableCredit, $invoice->sisa_piutang);

            if ($creditAmount <= 0) {
                return redirect()->route('penjualan.nota-kredit.show', $notaKredit->id)
                    ->with('error', 'Jumlah kredit tidak valid.');
            }

            // Apply the credit to the invoice
            $invoice->applyCredit($creditAmount);

            // Create or update the relationship in the pivot table
            $existingCredit = $notaKredit->invoices()->where('invoice_id', $invoice->id)->first();

            if ($existingCredit) {
                // Update existing credit application
                $newAmount = $existingCredit->pivot->applied_amount + $creditAmount;
                $notaKredit->invoices()->updateExistingPivot($invoice->id, ['applied_amount' => $newAmount]);
            } else {
                // Create new credit application
                $notaKredit->invoices()->attach($invoice->id, ['applied_amount' => $creditAmount]);
            }

            // Check if related sales order exists and update its status if needed
            if ($notaKredit->salesOrder->id) {
                $salesOrder = SalesOrder::find($notaKredit->salesOrder->id);
                if ($salesOrder && $salesOrder->status_pembayaran === 'kelebihan_bayar') {
                    $salesOrder->status_pembayaran = 'lunas';
                    $salesOrder->kelebihan_bayar = 0;
                    $salesOrder->save();

                    // Log sales order status change
                    LogAktivitas::create([
                        'user_id' => Auth::id(),
                        'aktivitas' => 'ubah_status',
                        'modul' => 'sales_order',
                        'data_id' => $salesOrder->id,
                        'ip_address' => request()->ip(),
                        'detail' => "Mengubah status pembayaran sales order {$salesOrder->nomor} dari kelebihan_bayar menjadi lunas"
                    ]);
                }
            }

            // Log the credit application
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'terapkan_kredit',
                'modul' => 'nota_kredit',
                'data_id' => $notaKredit->id,
                'ip_address' => request()->ip(),
                'detail' => "Menerapkan kredit sebesar Rp " . number_format($creditAmount, 0, ',', '.') . " ke invoice " . $invoice->nomor
            ]);

            // Update nota kredit status to selesai after applying credit
            $notaKredit->status = 'selesai';
            $notaKredit->save();

            // Log the status change
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'ubah_status',
                'modul' => 'nota_kredit',
                'data_id' => $notaKredit->id,
                'ip_address' => request()->ip(),
                'detail' => "Mengubah status nota kredit {$notaKredit->nomor} menjadi selesai setelah diaplikasikan ke invoice"
            ]);

            DB::commit();

            return redirect()->route('penjualan.nota-kredit.show', $notaKredit->id)
                ->with('success', 'Kredit berhasil diaplikasikan ke invoice ' . $invoice->nomor);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
