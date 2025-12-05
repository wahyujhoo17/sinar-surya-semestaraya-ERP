<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\SalesOrder;
use App\Models\Karyawan;
use App\Models\SalesOrderDetail;
use App\Models\PembayaranPiutang;
use App\Models\Customer;
use App\Models\LogAktivitas;
use App\Models\UangMukaPenjualan;
use App\Models\UangMukaAplikasi;
use App\Models\JurnalUmum;
use App\Models\AccountingConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\DirekturUtamaService;
use App\Services\PDFInvoiceSinarSuryaTemplate;


class InvoiceController extends Controller
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

    private function getDirekturUtama()
    {
        return DirekturUtamaService::getDirekturUtama();
    }

    private function generateNewInvoiceNumber()
    {
        $prefix = get_document_prefix('invoice') . '-';
        $date = now()->format('Ymd');
        $pattern = $prefix . $date . '-';

        // MySQL compatible query - use RIGHT function to extract last 3 digits
        $lastInvoice = DB::table('invoice')
            ->where('nomor', 'like', $pattern . '%')
            ->selectRaw('MAX(CAST(RIGHT(nomor, 3) AS UNSIGNED)) as last_num')
            ->first();

        $newNumberSuffix = '001';
        if ($lastInvoice && !is_null($lastInvoice->last_num)) {
            $newNumberSuffix = str_pad($lastInvoice->last_num + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . $date . '-' . $newNumberSuffix;
    }



    public function index(Request $request)
    {
        $query = null;

        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager_penjualan') || Auth::user()->hasRole('admin_penjualan')) {
            $query = Invoice::with('customer.sales');
        } else {
            $query = Invoice::with('customer.sales')->where('user_id', Auth::id());
        }

        // Check if this is for credit application
        $notaKreditId = $request->filled('nota_kredit_id') ? $request->nota_kredit_id : null;
        $notaKredit = null;

        if ($notaKreditId) {
            $notaKredit = \App\Models\NotaKredit::find($notaKreditId);
            // Filter by customer if nota kredit is found
            if ($notaKredit && $notaKredit->customer_id) {
                $query->where('customer_id', $notaKredit->customer_id);

                // Only show invoices that are not fully paid
                $query->where('status', '!=', 'lunas');

                // Only show invoices that haven't received the full credit from this nota kredit
                $query->whereRaw('(invoice.total - COALESCE(invoice.kredit_terapkan, 0)) > 0');

                // Debug log to verify filter is applied
                Log::info('Filtering invoices for nota kredit', [
                    'nota_kredit_id' => $notaKreditId,
                    'customer_id' => $notaKredit->customer_id,
                    'query' => $query->toSql(),
                    'query_bindings' => $query->getBindings()
                ]);
            }
        }

        $sort = $request->get('sort', 'tanggal');
        $direction = $request->get('direction', 'desc');

        // Map frontend sort field names to database column names
        $sortMapping = [
            'no' => 'nomor',
            'no_invoice' => 'nomor',
            'tanggal' => 'tanggal',
            'customer' => 'customer_id',
            'kontak' => 'customer_id',
            'status' => 'status',
            'jatuh_tempo' => 'jatuh_tempo',
            'total' => 'total'
        ];

        // Get the actual database column name to sort by
        $dbSortField = $sortMapping[$sort] ?? $sort;

        // Apply sorting
        if (in_array($dbSortField, ['nomor', 'tanggal', 'status', 'jatuh_tempo', 'total'])) {
            $query->orderBy($dbSortField, $direction);
        } elseif ($sort === 'customer' || $sort === 'kontak') {
            // Join with customer table to sort by customer name
            $query->leftJoin('customer', 'invoice.customer_id', '=', 'customer.id')
                ->orderBy('customer.nama', $direction)
                ->select('invoice.*');
        } else {
            $query->orderBy('tanggal', 'desc');
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q_customer) use ($search) {
                        $q_customer->where('nama', 'like', "%{$search}%")
                            ->orWhere('company', 'like', "%{$search}%");
                    });

                if (is_numeric($search)) {
                    $q->orWhere('total', '=', $search);
                }
            });
        }

        // Apply status filter
        if ($request->filled('status') && $request->status !== 'all' && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Apply date filtering
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
        // If periode is set but no explicit dates, calculate the date range
        elseif ($request->filled('periode') && $request->periode !== 'custom' && !$request->filled('tanggal_awal')) {
            $today = now();
            $startDate = null;
            $endDate = $today;

            switch ($request->periode) {
                case 'today':
                    $startDate = $today->copy();
                    break;
                case 'yesterday':
                    $startDate = $today->copy()->subDay();
                    $endDate = $startDate->copy();
                    break;
                case 'this_week':
                    $startDate = $today->copy()->startOfWeek();
                    break;
                case 'last_week':
                    $startDate = $today->copy()->subWeek()->startOfWeek();
                    $endDate = $today->copy()->subWeek()->endOfWeek();
                    break;
                case 'this_month':
                    $startDate = $today->copy()->startOfMonth();
                    break;
                case 'last_month':
                    $startDate = $today->copy()->subMonth()->startOfMonth();
                    $endDate = $today->copy()->subMonth()->endOfMonth();
                    break;
                case 'this_year':
                    $startDate = $today->copy()->startOfYear();
                    break;
            }

            if ($startDate) {
                $query->whereDate('tanggal', '>=', $startDate->format('Y-m-d'))
                    ->whereDate('tanggal', '<=', $endDate->format('Y-m-d'));
            }
        }

        try {
            $invoices = $query->paginate(10)->withQueryString();

            if ($request->ajax()) {
                return response()->json([
                    'table_html' => view('penjualan.invoice._table', compact('invoices', 'sort', 'direction', 'notaKreditId'))->render(),
                    'pagination_html' => view('vendor.pagination.tailwind-custom', ['paginator' => $invoices])->render(),
                    'sort_field' => $sort,
                    'sort_direction' => $direction,
                ]);
            }

            return view('penjualan.invoice.index', compact('invoices', 'sort', 'direction', 'notaKreditId', 'notaKredit'));
        } catch (\Exception $e) {
            Log::error('Error in invoice index: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            $userFriendlyMessage = 'Terjadi kesalahan saat memuat data. Silakan coba lagi.';

            if (app()->environment('local', 'development', 'staging')) {
                $userFriendlyMessage .= ' Detail: ' . $e->getMessage();
            }

            if ($request->ajax()) {
                return response()->json([
                    'table_html' => '<tr><td colspan="7" class="px-6 py-4 text-center text-red-500">Terjadi kesalahan saat memuat data. Silakan muat ulang halaman ini.</td></tr>',
                    'pagination_html' => '',
                    'error' => $userFriendlyMessage
                ], 500);
            }

            return view('penjualan.invoice.index', [
                'invoices' => collect([]),
                'sort' => $sort,
                'direction' => $direction,
                'error' => $userFriendlyMessage
            ]);
        }
    }

    public function create()
    {
        // Get sales orders that are not fully invoiced
        $salesOrders = SalesOrder::with('customer')
            ->whereRaw('(
                SELECT COALESCE(SUM(total), 0) 
                FROM invoice 
                WHERE invoice.sales_order_id = sales_order.id
            ) < sales_order.total')
            ->orderBy('tanggal', 'desc')
            ->get();

        $nomor = $this->generateNewInvoiceNumber();

        // Get customers for uang muka lookup
        $customers = Customer::orderBy('nama')->get();

        // Get bank accounts for invoice using helper functions
        $bankAccounts = get_bank_accounts_for_invoice();
        $primaryBank = get_primary_bank_account();

        return view('penjualan.invoice.create', compact('salesOrders', 'nomor', 'customers', 'bankAccounts', 'primaryBank'));
    }

    public function getSalesOrderData($id)
    {
        try {
            $salesOrder = SalesOrder::with(['customer'])->findOrFail($id);

            // Hitung total invoice yang sudah ada untuk sales order ini
            $totalInvoiced = Invoice::where('sales_order_id', $id)->sum('total');

            // Hitung sisa yang belum di-invoice
            $sisaInvoice = $salesOrder->total - $totalInvoiced;

            // Jika sisa invoice <= 0, berarti sudah fully invoiced
            if ($sisaInvoice <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sales Order ini sudah fully invoiced',
                    'total_sales_order' => $salesOrder->total,
                    'total_invoiced' => $totalInvoiced,
                    'sisa_invoice' => $sisaInvoice
                ], 400);
            }

            // Hitung proporsi untuk setiap detail item
            $proporsiSisa = $sisaInvoice / $salesOrder->total;

            $details = SalesOrderDetail::with(['produk', 'satuan'])
                ->where('sales_order_id', $id)
                ->get()
                ->map(function ($detail) use ($proporsiSisa, $id) {
                    // Hitung qty yang sudah di-invoice untuk produk ini
                    $qtyAlreadyInvoiced = InvoiceDetail::join('invoice', 'invoice.id', '=', 'invoice_detail.invoice_id')
                        ->where('invoice.sales_order_id', $id)
                        ->where('invoice_detail.produk_id', $detail->produk_id)
                        ->sum('invoice_detail.quantity');

                    // Hitung qty yang tersedia untuk di-invoice
                    $qtyAvailable = $detail->quantity - $qtyAlreadyInvoiced;

                    // Hitung subtotal berdasarkan proporsi sisa
                    $subtotalProporsional = $detail->subtotal * $proporsiSisa;

                    return [
                        'produk_id' => $detail->produk_id,
                        'nama_produk' => $detail->produk ? $detail->produk->nama : $detail->deskripsi,
                        'satuan' => $detail->satuan ? $detail->satuan->nama : '',
                        'qty' => $qtyAvailable, // Qty yang tersedia untuk di-invoice
                        'original_qty' => $detail->quantity, // Qty asli dari SO
                        'max_available_qty' => $qtyAvailable, // Qty maksimum yang bisa di-invoice
                        'harga' => $detail->harga,
                        'diskon' => $detail->diskon_persen,
                        'subtotal' => $subtotalProporsional,
                        'subtotal_original' => $detail->subtotal, // Simpan subtotal asli untuk referensi
                    ];
                })
                ->filter(function ($item) {
                    // Filter hanya item yang masih memiliki qty tersedia
                    return $item['qty'] > 0;
                })
                ->values(); // Re-index array

            // Hitung tanggal jatuh tempo berdasarkan terms pembayaran dari sales order
            $jatuhTempo = now()->format('Y-m-d');
            if ($salesOrder->terms_pembayaran_hari) {
                $jatuhTempo = now()->addDays($salesOrder->terms_pembayaran_hari)->format('Y-m-d');
            }

            return response()->json([
                'success' => true,
                'sales_order' => $salesOrder,
                'details' => $details,
                'jatuh_tempo' => $jatuhTempo,
                'terms_pembayaran' => $salesOrder->terms_pembayaran,
                'invoice_info' => [
                    'total_sales_order' => $salesOrder->total,
                    'total_invoiced' => $totalInvoiced,
                    'sisa_invoice' => $sisaInvoice,
                    'proporsi_sisa' => $proporsiSisa
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data sales order tidak ditemukan',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor' => 'required|unique:invoice,nomor',
            'tanggal' => 'required|date',
            'sales_order_id' => 'required|exists:sales_order,id',
            'customer_id' => 'required|exists:customer,id',
            'jatuh_tempo' => 'required|date',
            'status' => 'required|in:belum_bayar,sebagian,lunas',
            'subtotal' => 'required|numeric',
            'diskon_persen' => 'nullable|numeric|min:0|max:100',
            'diskon_nominal' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0',
            'ongkos_kirim' => 'nullable|numeric|min:0',
            'total' => 'required|numeric',
            'items' => 'required|array',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.nama_produk' => 'required|string',
            'items.*.satuan' => 'required|string|exists:satuan,nama',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.diskon' => 'nullable|numeric|min:0|max:100',
            'items.*.subtotal' => 'required|numeric|min:0',
            'gunakan_uang_muka' => 'nullable|boolean',
            'uang_muka_terpilih' => 'nullable|array',
            'uang_muka_terpilih.*' => 'nullable|exists:uang_muka_penjualan,id',
        ]);

        // Custom validation for qty based on available stock
        $validationResponse = $this->validateInvoiceQuantities($request);
        if ($validationResponse) {
            return $validationResponse;
        }

        DB::beginTransaction();

        try {
            // Create invoice
            $invoice = Invoice::create([
                'nomor' => $request->nomor,
                'tanggal' => $request->tanggal,
                'sales_order_id' => $request->sales_order_id,
                'customer_id' => $request->customer_id,
                'user_id' => Auth::id(),
                'subtotal' => $request->subtotal,
                'diskon_persen' => $request->diskon_persen ?? 0,
                'diskon_nominal' => $request->diskon_nominal ?? 0,
                'ppn' => $request->ppn ?? 0,
                'ongkos_kirim' => $request->ongkos_kirim ?? 0,
                'total' => $request->total,
                'uang_muka_terapkan' => 0, // Initialize
                'sisa_tagihan' => $request->total, // Initialize with total
                'jatuh_tempo' => $request->jatuh_tempo,
                'status' => $request->status,
                'catatan' => $request->catatan,
                'syarat_ketentuan' => $request->syarat_ketentuan,
            ]);

            // Create invoice details
            foreach ($request->items as $item) {
                InvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    'produk_id' => $item['produk_id'],
                    'deskripsi' => $item['nama_produk'], // Store the product name in the deskripsi field
                    'quantity' => $item['qty'], // Map qty to quantity
                    'satuan_id' => \App\Models\Satuan::where('nama', $item['satuan'])->first()->id, // Get the satuan_id from the name
                    'harga' => $item['harga'],
                    'diskon_persen' => $item['diskon'] ?? 0,
                    'diskon_nominal' => ($item['harga'] * $item['qty'] * ($item['diskon'] ?? 0)) / 100,
                    'subtotal' => $item['subtotal'],
                ]);
            }

            // Aplikasikan uang muka jika user memilih untuk menggunakan uang muka
            if ($request->filled('gunakan_uang_muka') && $request->gunakan_uang_muka) {
                if ($request->filled('uang_muka_terpilih') && !empty($request->uang_muka_terpilih)) {
                    $this->applySelectedAdvancePayments($invoice, $request->uang_muka_terpilih);
                } else {
                    $this->autoApplyAdvancePayment($invoice);
                }
            }

            // Update sales order status if needed
            $this->updateSalesOrderStatus($request->sales_order_id);

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Membuat invoice baru: ' . $request->nomor,
                'modul' => 'invoice',
                'data_id' => $invoice->id,
            ]);

            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Membuat invoice baru: ' . $request->nomor,
                'modul' => 'sales_order',
                'data_id' => $request->sales_order_id,
            ]);

            DB::commit();

            return redirect()
                ->route('penjualan.invoice.show', $invoice->id)
                ->with('success', 'Invoice berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating invoice: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat invoice. ' . (app()->environment('local') ? $e->getMessage() : ''));
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'salesOrder', 'details', 'pembayaranPiutang']);

        // Load applied advance payments
        $appliedAdvances = UangMukaAplikasi::where('invoice_id', $invoice->id)
            ->with('uangMukaPenjualan')
            ->get();

        return view('penjualan.invoice.show', compact('invoice', 'appliedAdvances'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load(['customer', 'salesOrder', 'details']);
        return view('penjualan.invoice.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jatuh_tempo' => 'required|date',
            'status' => 'required|in:belum_bayar,sebagian',
            'subtotal' => 'required|numeric',
            'diskon_persen' => 'nullable|numeric|min:0|max:100',
            'diskon_nominal' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0',
            'ongkos_kirim' => 'nullable|numeric|min:0',
            'total' => 'required|numeric',
            'gunakan_uang_muka' => 'nullable|boolean',
            'cara_aplikasi_uang_muka' => 'nullable|in:otomatis,manual',
            'uang_muka_terpilih' => 'nullable|array',
            'uang_muka_terpilih.*' => 'exists:uang_muka_penjualan,id',
        ]);

        DB::beginTransaction();

        try {
            // Update invoice
            $invoice->update([
                'tanggal' => $request->tanggal,
                'diskon_persen' => $request->diskon_persen ?? 0,
                'diskon_nominal' => $request->diskon_nominal ?? 0,
                'ppn' => $request->ppn ?? 0,
                'ongkos_kirim' => $request->ongkos_kirim ?? 0,
                'total' => $request->total,
                'jatuh_tempo' => $request->jatuh_tempo,
                'status' => $request->status,
                'catatan' => $request->catatan,
                'syarat_ketentuan' => $request->syarat_ketentuan,
            ]);

            // Handle aplikasi uang muka jika ada perubahan
            // Cek apakah user ingin menggunakan uang muka
            if ($request->filled('gunakan_uang_muka') && $request->gunakan_uang_muka) {
                // Jika sebelumnya sudah ada aplikasi uang muka, hapus dulu
                $existingApplications = UangMukaAplikasi::where('invoice_id', $invoice->id)->get();

                foreach ($existingApplications as $aplikasi) {
                    // Hapus jurnal terkait
                    \App\Models\JurnalUmum::where('ref_type', 'App\\Models\\UangMukaAplikasi')
                        ->where('ref_id', $aplikasi->id)
                        ->where('sumber', 'uang_muka_aplikasi')
                        ->delete();

                    // Hapus aplikasi
                    $aplikasi->delete();

                    // Update status uang muka
                    $uangMuka = $aplikasi->uangMukaPenjualan;
                    if ($uangMuka) {
                        $uangMuka->refresh();
                        $uangMuka->load('aplikasi');
                        $uangMuka->updateStatus();
                    }
                }

                // Reset uang_muka_terapkan
                $invoice->uang_muka_terapkan = 0;
                $invoice->save();

                // Aplikasikan uang muka baru
                if ($request->filled('uang_muka_terpilih') && !empty($request->uang_muka_terpilih)) {
                    $this->applySelectedAdvancePayments($invoice, $request->uang_muka_terpilih);
                } else {
                    $this->autoApplyAdvancePayment($invoice);
                }
            }

            // Update sales order status if needed
            $this->updateSalesOrderStatus($invoice->sales_order_id);

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Memperbarui invoice: ' . $invoice->nomor,
                'modul' => 'invoice',
                'data_id' => $invoice->id,
            ]);

            DB::commit();

            return redirect()
                ->route('penjualan.invoice.show', $invoice->id)
                ->with('success', 'Invoice berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating invoice: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui invoice. ' .
                    (app()->environment('local', 'development') ? $e->getMessage() : ''));
        }
    }

    public function print(Invoice $invoice)
    {
        try {
            // Load all necessary relationships for the invoice
            $invoice->load([
                'customer',
                'salesOrder',
                'details.produk',
                'details.produk.satuan',
                'details.satuan',
                'pembayaranPiutang' // Changed 'pembayaran' to 'pembayaranPiutang'
            ]);

            // Get direktur utama using service
            $namaDirektur = DirekturUtamaService::getDirekturUtama();

            // Set paper size and orientation
            $pdf = PDF::loadView('penjualan.invoice.print', compact('invoice', 'namaDirektur'))
                ->setPaper('a4', 'portrait');

            // Stream the PDF with a sensible filename
            return $pdf->stream('Invoice-' . $invoice->nomor . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error generating invoice PDF: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mencetak invoice. Silakan coba lagi.');
        }
    }

    public function destroy(Invoice $invoice)
    {
        DB::beginTransaction();

        try {
            // First, handle uang muka applications if any
            $uangMukaAplikasi = UangMukaAplikasi::where('invoice_id', $invoice->id)->get();

            if ($uangMukaAplikasi->isNotEmpty()) {
                Log::info('Restoring advance payments for deleted invoice', [
                    'invoice_id' => $invoice->id,
                    'invoice_nomor' => $invoice->nomor,
                    'applications_count' => $uangMukaAplikasi->count()
                ]);

                foreach ($uangMukaAplikasi as $aplikasi) {
                    // Simpan data uang muka sebelum aplikasi dihapus
                    $uangMuka = $aplikasi->uangMukaPenjualan;
                    $restoredAmount = $aplikasi->jumlah_aplikasi;

                    if ($uangMuka) {
                        $oldStatus = $uangMuka->status;
                        $oldJumlahTersedia = $uangMuka->jumlah_tersedia;

                        // Hapus jurnal entry untuk aplikasi uang muka
                        JurnalUmum::where('ref_type', 'App\\Models\\UangMukaAplikasi')
                            ->where('ref_id', $aplikasi->id)
                            ->where('sumber', 'uang_muka_aplikasi')
                            ->delete();

                        // Hapus record aplikasi DULU
                        $aplikasi->delete();

                        // SETELAH aplikasi dihapus, baru refresh dan update status
                        $uangMuka->refresh(); // Refresh model dari database
                        $uangMuka->load('aplikasi'); // Load relasi aplikasi yang terbaru
                        $uangMuka->updateStatus(); // Recalculate status dan jumlah_tersedia

                        Log::info('Advance payment status updated after invoice deletion', [
                            'uang_muka_id' => $uangMuka->id,
                            'uang_muka_nomor' => $uangMuka->nomor,
                            'old_status' => $oldStatus,
                            'new_status' => $uangMuka->status,
                            'old_jumlah_tersedia' => $oldJumlahTersedia,
                            'new_jumlah_tersedia' => $uangMuka->jumlah_tersedia,
                            'restored_amount' => $restoredAmount
                        ]);
                    } else {
                        // Jika uang muka tidak ditemukan, tetap hapus jurnal dan aplikasi
                        JurnalUmum::where('ref_type', 'App\\Models\\UangMukaAplikasi')
                            ->where('ref_id', $aplikasi->id)
                            ->where('sumber', 'uang_muka_aplikasi')
                            ->delete();

                        $aplikasi->delete();
                    }
                }

                // Reset uang_muka_terapkan field pada invoice yang akan dihapus
                $invoice->uang_muka_terapkan = 0;
                $invoice->save();
            }

            // Delete associated details
            InvoiceDetail::where('invoice_id', $invoice->id)->delete();

            // Log the deletion
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Menghapus invoice: ' . $invoice->nomor,
                'modul' => 'invoice',
                'data_id' => $invoice->id,
            ]);

            // Delete the invoice
            $invoice->delete();

            // Update sales order status
            $this->updateSalesOrderStatus($invoice->sales_order_id);

            DB::commit();

            return redirect()
                ->route('penjualan.invoice.index')
                ->with('success', 'Invoice berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting invoice: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus invoice.');
        }
    }

    /**
     * Generate invoice from sales order
     */
    public function generateFromSalesOrder(SalesOrder $salesOrder)
    {
        // Hitung total invoice yang sudah ada untuk sales order ini
        $totalInvoiced = Invoice::where('sales_order_id', $salesOrder->id)->sum('total');

        // Hitung sisa yang belum di-invoice
        $sisaInvoice = $salesOrder->total - $totalInvoiced;

        // Check if sales order is already fully invoiced
        if ($sisaInvoice <= 0) {
            return redirect()
                ->back()
                ->with('error', 'Sales Order ini sudah fully invoiced. Total SO: ' . number_format($salesOrder->total, 0, ',', '.') . ', Total Invoice: ' . number_format($totalInvoiced, 0, ',', '.'));
        }

        // Generate invoice number
        $nomor = $this->generateNewInvoiceNumber();

        // Hitung tanggal jatuh tempo berdasarkan terms pembayaran dari sales order
        $jatuhTempo = now()->format('Y-m-d');
        if ($salesOrder->terms_pembayaran_hari) {
            $jatuhTempo = now()->addDays($salesOrder->terms_pembayaran_hari)->format('Y-m-d');
        }

        // Get bank accounts for invoice
        $bankAccounts = get_bank_accounts_for_invoice();
        $primaryBank = get_primary_bank_account();

        return view('penjualan.invoice.create', [
            'nomor' => $nomor,
            'salesOrders' => [$salesOrder],
            'preselectedSalesOrder' => $salesOrder->id,
            'jatuhTempo' => $jatuhTempo,
            'termsPembayaran' => $salesOrder->terms_pembayaran,
            'bankAccounts' => $bankAccounts,
            'primaryBank' => $primaryBank,
            'invoiceInfo' => [
                'total_sales_order' => $salesOrder->total,
                'total_invoiced' => $totalInvoiced,
                'sisa_invoice' => $sisaInvoice
            ]
        ]);
    }

    /**
     * Update the status of a sales order based on its invoices
     */
    private function updateSalesOrderStatus($salesOrderId)
    {
        $salesOrder = SalesOrder::findOrFail($salesOrderId);

        // Get the total amount invoiced
        $totalInvoiced = Invoice::where('sales_order_id', $salesOrderId)->sum('total');

        // Get the total amount from sales order
        $totalSalesOrder = $salesOrder->total;

        // Use proper rounding to handle floating point precision issues
        $totalSalesOrderRounded = round($totalSalesOrder, 2);
        $totalInvoicedRounded = round($totalInvoiced, 2);

        // Check if sales order has been invoiced fully or partially
        if ($totalInvoicedRounded >= $totalSalesOrderRounded) {
            // Sales order is fully invoiced
            $salesOrder->status_invoice = 'fully_invoiced';
        } elseif ($totalInvoicedRounded > 0) {
            // Partially invoiced
            $salesOrder->status_invoice = 'partially_invoiced';
        } else {
            // No invoice created yet
            $salesOrder->status_invoice = 'not_invoiced';
        }

        // Update status pembayaran berdasarkan total pembayaran dari semua invoice
        $totalPembayaran = \App\Models\PembayaranPiutang::whereHas('invoice', function ($query) use ($salesOrderId) {
            $query->where('sales_order_id', $salesOrderId);
        })->sum('jumlah');

        // Hitung total uang muka yang sudah diaplikasikan
        $totalUangMukaTerapkan = \App\Models\UangMukaAplikasi::whereHas('invoice', function ($query) use ($salesOrderId) {
            $query->where('sales_order_id', $salesOrderId);
        })->sum('jumlah_aplikasi');

        // Hitung total kredit yang sudah diaplikasikan
        $totalKreditTerapkan = Invoice::where('sales_order_id', $salesOrderId)->sum('kredit_terapkan') ?? 0;

        // Total yang sudah dibayar (pembayaran + uang muka + kredit)
        $totalTerbayar = $totalPembayaran + $totalUangMukaTerapkan + $totalKreditTerapkan;
        $totalTerbayarRounded = round($totalTerbayar, 2);

        // Update status pembayaran
        // Sales order can only be "lunas" if it's fully invoiced AND all invoices are paid
        // Use tolerance for floating point comparison
        $isFullyInvoiced = ($totalInvoicedRounded >= $totalSalesOrderRounded - 0.001);
        $isFullyPaid = ($totalTerbayarRounded >= $totalInvoicedRounded - 0.001);

        if ($isFullyInvoiced && $isFullyPaid) {
            $salesOrder->status_pembayaran = 'lunas';
        } elseif ($totalTerbayarRounded > 0) {
            $salesOrder->status_pembayaran = 'sebagian';
        } else {
            $salesOrder->status_pembayaran = 'belum_bayar';
        }

        // Log status before save
        Log::info('About to save sales order status', [
            'sales_order_id' => $salesOrderId,
            'new_status_pembayaran' => $salesOrder->status_pembayaran,
            'new_status_invoice' => $salesOrder->status_invoice,
            'is_dirty' => $salesOrder->isDirty(),
            'dirty_attributes' => $salesOrder->getDirty()
        ]);

        $salesOrder->save();

        // Log untuk debugging
        Log::info('Sales Order Status Updated', [
            'sales_order_id' => $salesOrderId,
            'total_sales_order' => $totalSalesOrder,
            'total_invoiced' => $totalInvoiced,
            'total_pembayaran' => $totalPembayaran,
            'total_uang_muka' => $totalUangMukaTerapkan,
            'total_kredit' => $totalKreditTerapkan,
            'total_terbayar' => $totalTerbayar,
            'total_sales_order_rounded' => $totalSalesOrderRounded,
            'total_invoiced_rounded' => $totalInvoicedRounded,
            'total_terbayar_rounded' => $totalTerbayarRounded,
            'is_fully_invoiced' => $isFullyInvoiced,
            'is_fully_paid' => $isFullyPaid,
            'calculation_result' => [
                'should_be_lunas' => $isFullyInvoiced && $isFullyPaid,
                'should_be_sebagian' => $totalTerbayarRounded > 0 && !($isFullyInvoiced && $isFullyPaid),
                'should_be_belum_bayar' => $totalTerbayarRounded <= 0
            ],
            'status_invoice' => $salesOrder->status_invoice,
            'status_pembayaran' => $salesOrder->status_pembayaran,
            'status_before_update' => [
                'status_invoice' => $salesOrder->getOriginal('status_invoice'),
                'status_pembayaran' => $salesOrder->getOriginal('status_pembayaran')
            ]
        ]);

        return $salesOrder;
    }
    public function printTemplate($id)
    {
        try {
            // Load invoice with its relationships
            $invoice = Invoice::with([
                'salesOrder',
                'customer',
                'user',
                'details.produk.satuan',
                'details.satuan',
            ])->findOrFail($id);

            // Get direktur utama using service
            $namaDirektur = DirekturUtamaService::getDirekturUtama();

            // Get DP amount from request (optional, for temporary DP display only)
            $dpAmount = request('dp_amount', 0);

            // Use PDF template service - Menggunakan template asli
            $pdfService = new \App\Services\PDFInvoiceTamplate();
            $pdf = $pdfService->fillInvoiceTemplate($invoice, $namaDirektur, $dpAmount);



            // Output PDF
            $filename = 'Invoice-' . $invoice->nomor . '.pdf';

            // Log aktivitas
            $this->logUserAktivitas(
                'print invoice template',
                'invoice',
                $invoice->id,
                'Print invoice menggunakan Sinar Surya FPDI template' . ($dpAmount > 0 ? ' dengan DP Rp ' . number_format($dpAmount, 0, ',', '.') : '')
            );

            return $pdf->Output($filename, 'I'); // 'I' for inline display, 'D' for download

        } catch (\Exception $e) {
            Log::error('Error printing invoice template: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mencetak invoice: ' . $e->getMessage());
        }
    }

    public function printTemplateNonPpn($id)
    {
        try {
            // Load invoice with its relationships
            $invoice = Invoice::with([
                'salesOrder',
                'customer',
                'user',
                'details.produk.satuan',
                'details.satuan',
            ])->findOrFail($id);

            // Get direktur utama using service
            $namaDirektur = DirekturUtamaService::getDirekturUtama();

            // Get bank accounts for payment information
            $bankAccounts = get_bank_accounts_for_invoice();
            $primaryBank = get_primary_bank_account();

            // Get DP amount from request (optional, for temporary DP display only)
            $dpAmount = request('dp_amount', 0);

            // Use PDF template service untuk Non PPN
            $pdfService = new \App\Services\PDFInvoiceNonPpnTemplate();
            $pdf = $pdfService->fillInvoiceTemplate($invoice, $namaDirektur, $dpAmount, $bankAccounts, $primaryBank);

            // Output PDF
            $filename = 'Invoice-Non-PPN-' . $invoice->nomor . '.pdf';

            // Log aktivitas
            $this->logUserAktivitas(
                'print invoice template non ppn',
                'invoice',
                $invoice->id,
                'Print invoice menggunakan template Non PPN' . ($dpAmount > 0 ? ' dengan DP Rp ' . number_format($dpAmount, 0, ',', '.') : '')
            );

            return $pdf->Output($filename, 'I'); // 'I' for inline display, 'D' for download

        } catch (\Exception $e) {
            Log::error('Error printing invoice template non ppn: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mencetak invoice non PPN: ' . $e->getMessage());
        }
    }

    /**
     * Export invoice to PDF with template selection
     */
    public function exportPdf($id, Request $request)
    {
        try {
            // Load invoice dengan relasi yang diperlukan
            $invoice = Invoice::with([
                'salesOrder',
                'customer',
                'user',
                'details.produk.satuan',
                'details.satuan',
                'pembayaranPiutang'
            ])->findOrFail($id);

            $direkturUtama = $this->getDirekturUtama();

            // Template configuration
            $templates = [
                'sinar-surya' => [
                    'name' => 'PT Sinar Surya Semestaraya',
                    'use_fpdi_template' => true, // Flag untuk menggunakan FPDI template
                    'service' => \App\Services\PDFInvoiceSinarSuryaTemplate::class,
                    'view' => 'penjualan.invoice.pdf.sinar-surya',
                    'logo' => public_path('img/logo-sinar-surya.png'),
                    'direktur_nama' => $direkturUtama
                ],
                'atsaka' => [
                    'name' => 'PT Indo Atsaka Industri',
                    'view' => 'penjualan.invoice.pdf.atsaka',
                    'logo' => public_path('img/PTIndoatsakaindustri-2.jpeg'),
                    'direktur_nama' => $direkturUtama
                ],
                'hidayah' => [
                    'name' => 'PT Hidayah Cahaya Berkah',
                    'view' => 'penjualan.invoice.pdf.hidayah',
                    'logo' => public_path('img/LogoHCB-0.jpeg'),
                    'direktur_nama' => $direkturUtama
                ]
            ];

            // Get template from request, default to sinar-surya
            $template = $request->get('template', 'sinar-surya');

            // Validate template
            if (!array_key_exists($template, $templates)) {
                $template = 'sinar-surya';
            }

            $templateConfig = $templates[$template];

            // Get bank accounts for template
            $bankAccounts = get_bank_accounts_for_invoice();
            $primaryBank = get_primary_bank_account();

            // Get DP amount from request (optional, for temporary DP display only)
            $dpAmount = request('dp_amount', 0);

            // Check if this template uses FPDI service (new template system)
            if (isset($templateConfig['use_fpdi_template']) && $templateConfig['use_fpdi_template']) {
                // Use FPDI template service for Sinar Surya
                $serviceClass = $templateConfig['service'];
                $pdfService = new $serviceClass();

                // For Sinar Surya template, pass invoice, director name, and DP amount
                $pdf = $pdfService->fillInvoiceTemplate($invoice, $templateConfig['direktur_nama'], $dpAmount);

                $filename = 'Invoice-' . $invoice->nomor . '-' . $templateConfig['name'] . '.pdf';

                // Log aktivitas
                $this->logUserAktivitas(
                    'export invoice pdf',
                    'invoice',
                    $invoice->id,
                    'Export invoice ke PDF menggunakan FPDI template: ' . $templateConfig['name'] . ($dpAmount > 0 ? ' dengan DP Rp ' . number_format($dpAmount, 0, ',', '.') : '')
                );

                return $pdf->Output($filename, 'I'); // 'I' for inline display
            }

            // Legacy template system (for other templates)
            // Convert logo to base64 untuk menghindari masalah path
            $logoBase64 = '';
            if (file_exists($templateConfig['logo'])) {
                $logoData = file_get_contents($templateConfig['logo']);
                $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
            }

            // Prepare data untuk view
            $data = [
                'invoice' => $invoice,
                'template' => $templateConfig,
                'logoBase64' => $logoBase64,
                'bankAccounts' => $bankAccounts,
                'primaryBank' => $primaryBank,
                'dpAmount' => $dpAmount, // Pass DP amount to view
                'currentDate' => now()->format('d F Y'),
                'currentTime' => now()->format('H:i:s')
            ];

            // Generate PDF dengan pengaturan optimal
            $pdf = Pdf::loadView($templateConfig['view'], $data);

            // Set custom paper size (165x212mm)
            $pdf->setPaper([0, 0, 467.72, 600.945], 'portrait'); // 165x212mm dalam points

            // Optimasi PDF untuk performa
            $pdf->setOptions([
                'dpi' => 96,
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => false,
                'isJavascriptEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'fontSubsetting' => true,
                'tempDir' => sys_get_temp_dir(),
                'chroot' => public_path(),
                'logOutputFile' => storage_path('logs/dompdf.log'),
                'defaultMediaType' => 'print',
                'isFontSubsettingEnabled' => true,
            ]);

            // Set memory limit dan execution time untuk PDF besar
            ini_set('memory_limit', '512M');
            ini_set('max_execution_time', 300);

            $filename = 'Invoice-' . $invoice->nomor . '-' . $templateConfig['name'] . '.pdf';

            // Log aktivitas
            $this->logUserAktivitas(
                'export invoice pdf',
                'invoice',
                $invoice->id,
                'Export invoice ke PDF menggunakan template: ' . $templateConfig['name']
            );

            return $pdf->stream($filename);
        } catch (\Exception $e) {
            Log::error('Error exporting invoice PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengexport invoice ke PDF: ' . $e->getMessage());
        }
    }

    /**
     * Otomatis aplikasikan uang muka yang tersedia untuk customer
     */
    private function autoApplyAdvancePayment($invoice)
    {
        // Cari uang muka yang tersedia untuk customer ini
        $availableAdvances = UangMukaPenjualan::byCustomer($invoice->customer_id)
            ->available()
            ->orderBy('tanggal', 'asc')
            ->get();

        if ($availableAdvances->isEmpty()) {
            return; // Tidak ada uang muka tersedia
        }

        $remainingInvoiceAmount = $invoice->total;
        $totalAdvanceApplied = 0;

        foreach ($availableAdvances as $advance) {
            if ($remainingInvoiceAmount <= 0) {
                break; // Invoice sudah fully covered
            }

            $amountToApply = min($advance->jumlah_tersedia, $remainingInvoiceAmount);

            if ($amountToApply > 0) {
                $aplikasi = UangMukaAplikasi::create([
                    'uang_muka_penjualan_id' => $advance->id,
                    'invoice_id' => $invoice->id,
                    'jumlah_aplikasi' => $amountToApply,
                    'tanggal_aplikasi' => today(),
                    'keterangan' => 'Auto aplikasi uang muka ke invoice ' . $invoice->nomor
                ]);

                // Update uang muka
                $advance->updateStatus();

                $totalAdvanceApplied += $amountToApply;
                $remainingInvoiceAmount -= $amountToApply;

                // Buat jurnal entry untuk aplikasi
                $this->createApplicationJournalEntry($aplikasi);
            }
        }

        // Update invoice
        if ($totalAdvanceApplied > 0) {
            $invoice->uang_muka_terapkan = $totalAdvanceApplied;

            // Formula: Subtotal + PPN - Uang Muka = Total - Uang Muka
            $invoice->sisa_tagihan = $invoice->total - $totalAdvanceApplied;

            // Update status invoice jika fully covered
            if ($invoice->sisa_tagihan <= 0) {
                $invoice->status = 'lunas';
            } elseif ($totalAdvanceApplied > 0) {
                $invoice->status = 'sebagian';
            }

            $invoice->save();
        }
    }

    /**
     * Aplikasikan uang muka yang dipilih user
     */
    private function applySelectedAdvancePayments($invoice, $selectedAdvanceIds)
    {
        // Validate dan ambil uang muka yang dipilih
        $selectedAdvances = UangMukaPenjualan::whereIn('id', $selectedAdvanceIds)
            ->where('customer_id', $invoice->customer_id)
            ->available()
            ->orderBy('tanggal', 'asc')
            ->get();

        if ($selectedAdvances->isEmpty()) {
            return; // Tidak ada uang muka yang valid
        }

        $remainingInvoiceAmount = $invoice->total;
        $totalAdvanceApplied = 0;

        foreach ($selectedAdvances as $advance) {
            if ($remainingInvoiceAmount <= 0) {
                break; // Invoice sudah fully covered
            }

            $amountToApply = min($advance->jumlah_tersedia, $remainingInvoiceAmount);

            if ($amountToApply > 0) {
                $aplikasi = UangMukaAplikasi::create([
                    'uang_muka_penjualan_id' => $advance->id,
                    'invoice_id' => $invoice->id,
                    'jumlah_aplikasi' => $amountToApply,
                    'tanggal_aplikasi' => today(),
                    'keterangan' => 'Aplikasi uang muka ke invoice ' . $invoice->nomor . ' (dipilih user)'
                ]);

                // Update uang muka
                $advance->updateStatus();

                $totalAdvanceApplied += $amountToApply;
                $remainingInvoiceAmount -= $amountToApply;

                // Buat jurnal entry untuk aplikasi
                $this->createApplicationJournalEntry($aplikasi);
            }
        }

        // Update invoice
        if ($totalAdvanceApplied > 0) {
            $invoice->uang_muka_terapkan = $totalAdvanceApplied;

            // Formula: Subtotal + PPN - Uang Muka = Total - Uang Muka
            $invoice->sisa_tagihan = $invoice->total - $totalAdvanceApplied;

            // Update status invoice jika fully covered
            if ($invoice->sisa_tagihan <= 0) {
                $invoice->status = 'lunas';
            } elseif ($totalAdvanceApplied > 0) {
                $invoice->status = 'sebagian';
            }

            $invoice->save();
        }
    }

    /**
     * Buat jurnal entry untuk aplikasi uang muka ke invoice
     */
    private function createApplicationJournalEntry($aplikasi)
    {
        // Cek apakah jurnal sudah ada untuk aplikasi ini
        $existingJournal = \App\Models\JurnalUmum::where('ref_type', 'App\\Models\\UangMukaAplikasi')
            ->where('ref_id', $aplikasi->id)
            ->where('sumber', 'uang_muka_aplikasi')
            ->exists();

        if ($existingJournal) {
            // Jurnal sudah ada, skip untuk menghindari duplikasi
            return;
        }

        $akunUangMukaPenjualanId = AccountingConfiguration::getAccountId('uang_muka_penjualan', 'hutang_uang_muka_penjualan');
        $akunUangMukaPenjualan = $akunUangMukaPenjualanId ? \App\Models\AkunAkuntansi::find($akunUangMukaPenjualanId) : null;

        $akunPiutangId = AccountingConfiguration::getAccountId('penjualan', 'piutang_usaha');
        $akunPiutang = $akunPiutangId ? \App\Models\AkunAkuntansi::find($akunPiutangId) : null;

        if (!$akunUangMukaPenjualan) {
            throw new \Exception('Akun Hutang Uang Muka Penjualan tidak ditemukan. Silakan konfigurasi akun di menu Konfigurasi Akuntansi atau hubungi administrator.');
        }

        if (!$akunPiutang) {
            throw new \Exception('Akun Piutang Usaha tidak ditemukan. Silakan konfigurasi akun di menu Konfigurasi Akuntansi atau hubungi administrator.');
        }

        // Buat jurnal debit hutang uang muka
        \App\Models\JurnalUmum::create([
            'tanggal' => $aplikasi->tanggal_aplikasi,
            'no_referensi' => 'APP-' . $aplikasi->uangMukaPenjualan->nomor . '-' . $aplikasi->invoice->nomor,
            'akun_id' => $akunUangMukaPenjualan->id,
            'debit' => $aplikasi->jumlah_aplikasi,
            'kredit' => 0,
            'keterangan' => 'Aplikasi uang muka ke invoice: ' . $aplikasi->invoice->nomor,
            'jenis_jurnal' => 'umum',
            'sumber' => 'uang_muka_aplikasi',
            'ref_type' => 'App\Models\UangMukaAplikasi',
            'ref_id' => $aplikasi->id,
            'user_id' => Auth::id(),
            'is_posted' => true, // Aplikasi uang muka tidak mempengaruhi saldo kas/bank
            'posted_at' => now(),
            'posted_by' => Auth::id()
        ]);

        // Buat jurnal kredit piutang usaha
        \App\Models\JurnalUmum::create([
            'tanggal' => $aplikasi->tanggal_aplikasi,
            'no_referensi' => 'APP-' . $aplikasi->uangMukaPenjualan->nomor . '-' . $aplikasi->invoice->nomor,
            'akun_id' => $akunPiutang->id,
            'debit' => 0,
            'kredit' => $aplikasi->jumlah_aplikasi,
            'keterangan' => 'Pengurangan piutang karena aplikasi uang muka',
            'jenis_jurnal' => 'umum',
            'sumber' => 'uang_muka_aplikasi',
            'ref_type' => 'App\Models\UangMukaAplikasi',
            'ref_id' => $aplikasi->id,
            'user_id' => Auth::id(),
            'is_posted' => true, // Jurnal otomatis langsung diposting
            'posted_at' => now(),
            'posted_by' => Auth::id()
        ]);
    }

    /**
     * Get available advance payments for a customer
     */
    public function getCustomerAdvancePayments($customerId)
    {
        try {
            $uangMuka = UangMukaPenjualan::byCustomer($customerId)
                ->available()
                ->with('customer')
                ->orderBy('tanggal', 'asc')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nomor' => $item->nomor,
                        'tanggal' => isset($item->tanggal) && $item->tanggal ? $item->tanggal->format('d/m/Y') : '',
                        'jumlah' => (float) $item->jumlah,
                        'jumlah_tersedia' => (float) $item->jumlah_tersedia,
                        'jumlah_formatted' => 'Rp ' . number_format((float) $item->jumlah, 0, ',', '.'),
                        'jumlah_tersedia_formatted' => 'Rp ' . number_format((float) $item->jumlah_tersedia, 0, ',', '.'),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $uangMuka
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data uang muka customer',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    private function validateInvoiceQuantities(Request $request)
    {
        $salesOrderId = $request->sales_order_id;
        $errors = [];

        foreach ($request->items as $index => $item) {
            $produkId = $item['produk_id'];
            $qtyRequested = $item['qty'];

            // Get original qty from sales order
            $soDetail = SalesOrderDetail::where('sales_order_id', $salesOrderId)
                ->where('produk_id', $produkId)
                ->first();

            if (!$soDetail) {
                $errors[] = "Produk {$item['nama_produk']} tidak ditemukan dalam Sales Order";
                continue;
            }

            // Get qty already invoiced for this product
            $qtyAlreadyInvoiced = InvoiceDetail::join('invoice', 'invoice.id', '=', 'invoice_detail.invoice_id')
                ->where('invoice.sales_order_id', $salesOrderId)
                ->where('invoice_detail.produk_id', $produkId)
                ->sum('invoice_detail.quantity');

            // Calculate available qty
            $qtyAvailable = $soDetail->quantity - $qtyAlreadyInvoiced;

            // Check if requested qty exceeds available
            if ($qtyRequested > $qtyAvailable) {
                $qtyInvoiced = $qtyAlreadyInvoiced > 0 ? " (sudah di-invoice: {$qtyAlreadyInvoiced})" : "";
                $errors[] = "Produk {$item['nama_produk']}: Qty {$qtyRequested} melebihi qty yang tersedia {$qtyAvailable} dari total SO {$soDetail->quantity}{$qtyInvoiced}";
            }

            // Check if requested qty is less than minimum
            if ($qtyRequested <= 0) {
                $errors[] = "Produk {$item['nama_produk']}: Qty harus lebih dari 0";
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $errors
            ], 422);
        }
    }

    /**
     * Public method to update sales order status that can be called from other controllers
     */
    public static function updateSalesOrderStatusFromPayment($salesOrderId)
    {
        $instance = new self();
        return $instance->updateSalesOrderStatus($salesOrderId);
    }
}
