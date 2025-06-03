<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\PembayaranPiutang;
use App\Models\Customer;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    private function generateNewInvoiceNumber()
    {
        $prefix = 'INV-';
        $date = now()->format('Ymd');

        // PostgreSQL compatible query - convert substring to integer instead of UNSIGNED
        $lastInvoice = DB::table('invoice')
            ->where('nomor', 'like', $prefix . $date . '-%')
            ->selectRaw('MAX(CAST(SUBSTRING(nomor FROM ' . (strlen($prefix . $date . '-') + 1) . ') AS INTEGER)) as last_num')
            ->first();

        $newNumberSuffix = '001';
        if ($lastInvoice && !is_null($lastInvoice->last_num)) {
            $newNumberSuffix = str_pad($lastInvoice->last_num + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . $date . '-' . $newNumberSuffix;
    }



    public function index(Request $request)
    {
        $query = Invoice::with('customer');

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
                \Illuminate\Support\Facades\Log::info('Filtering invoices for nota kredit', [
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
                    'pagination_html' => view('penjualan.invoice._pagination', ['paginator' => $invoices])->render(),
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
        $salesOrders = SalesOrder::where('status_pembayaran', '!=', 'lunas')
            ->with('customer')
            ->orderBy('tanggal', 'desc')
            ->get();

        $nomor = $this->generateNewInvoiceNumber();

        return view('penjualan.invoice.create', compact('salesOrders', 'nomor'));
    }

    public function getSalesOrderData($id)
    {
        try {
            $salesOrder = SalesOrder::with(['customer'])->findOrFail($id);
            $details = SalesOrderDetail::with(['produk', 'satuan'])
                ->where('sales_order_id', $id)
                ->get()
                ->map(function ($detail) {
                    return [
                        'produk_id' => $detail->produk_id,
                        'nama_produk' => $detail->produk ? $detail->produk->nama : $detail->deskripsi,
                        'satuan' => $detail->satuan ? $detail->satuan->nama : '',
                        'qty' => $detail->quantity,
                        'harga' => $detail->harga,
                        'diskon' => $detail->diskon_persen,
                        'subtotal' => $detail->subtotal,
                    ];
                });

            return response()->json([
                'success' => true,
                'sales_order' => $salesOrder,
                'details' => $details
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
        ]);

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

            // Update sales order status if needed
            $this->updateSalesOrderStatus($request->sales_order_id);

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Membuat invoice baru: ' . $request->nomor,
                'modul' => 'invoice',
                'data_id' => $invoice->id,
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
                ->with('error', 'Terjadi kesalahan saat membuat invoice. ' . ($request->app()->environment('local') ? $e->getMessage() : ''));
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'salesOrder', 'details', 'pembayaranPiutang']); // Changed 'pembayaran' to 'pembayaranPiutang'
        return view('penjualan.invoice.show', compact('invoice'));
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
                'details.barang',
                'details.barang.satuan',
                'pembayaranPiutang' // Changed 'pembayaran' to 'pembayaranPiutang'
            ]);

            // Set paper size and orientation
            $pdf = PDF::loadView('penjualan.invoice.print', compact('invoice'))
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
            // First, delete associated details
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
        // Check if sales order is already fully invoiced
        if ($salesOrder->status_pembayaran === 'lunas') {
            return redirect()
                ->back()
                ->with('error', 'Sales Order ini sudah memiliki invoice penuh.');
        }

        // Generate invoice number
        $nomor = $this->generateNewInvoiceNumber();

        return view('penjualan.invoice.create', [
            'nomor' => $nomor,
            'salesOrders' => [$salesOrder],
            'preselectedSalesOrder' => $salesOrder->id
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

        // Check if sales order has been invoiced fully or partially
        if ($totalInvoiced >= $totalSalesOrder) {
            // Sales order is fully invoiced
            $salesOrder->status_invoice = 'fully_invoiced';
        } elseif ($totalInvoiced > 0) {
            // Partially invoiced
            $salesOrder->status_invoice = 'partially_invoiced';
        } else {
            // No invoice created yet
            $salesOrder->status_invoice = 'not_invoiced';
        }

        // Status pembayaran is separate from invoice status
        // Status should remain "belum_bayar" until payment is recorded in pembayaran_piutang
        // We're not changing status to "lunas" here, that will be done in PembayaranPiutangController
        if ($salesOrder->status_pembayaran != 'lunas' && $salesOrder->status_pembayaran != 'sebagian') {
            $salesOrder->status_pembayaran = 'belum_bayar';
        }

        $salesOrder->save();

        return $salesOrder;
    }
}
