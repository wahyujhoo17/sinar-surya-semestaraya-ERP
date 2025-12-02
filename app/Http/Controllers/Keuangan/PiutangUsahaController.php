<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\SalesOrder; // Still needed for context via Invoice
use App\Models\PembayaranPiutang; // For relationships and potentially direct queries
use App\Models\ReturPenjualan; // For context in show view
use App\Models\Customer;
use App\Exports\PiutangUsahaExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Keep for now, might be unused
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\View\View; // Added for type hinting
use Illuminate\Http\Response; // Added for type hinting

class PiutangUsahaController extends Controller
{
    /**
     * Display a listing of piutang usaha (invoice-centric).
     *
     * @param  \\Illuminate\\Http\\Request  $request
     * @return \\Illuminate\\Contracts\\View\\View
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['customer', 'salesOrder', 'pembayaranPiutang', 'uangMukaAplikasi'])
            ->where('total', '>', 0); // Consider only invoices with a value

        // Get sort column and direction
        $sortColumn = $request->input('sort', 'tanggal');
        $sortDirection = $request->input('direction', 'desc');

        // Validate sort column to prevent SQL injection
        $allowedColumns = [
            'nomor',
            'tanggal',
            'jatuh_tempo',
            'total',
            'status_pembayaran'
        ];

        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'tanggal';
        }

        // Apply sorting
        $query->orderBy($sortColumn, $sortDirection);

        // Filter by customer if provided
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by sales user if provided
        if ($request->filled('sales_id')) {
            $query->whereHas('salesOrder', function ($q) use ($request) {
                $q->where('user_id', $request->sales_id);
            });
        }

        // Filter by date range (invoice date)
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nomor', 'like', "%{$searchTerm}%")
                    ->orWhereHas('customer', function ($q2) use ($searchTerm) {
                        $q2->where('nama', 'like', "%{$searchTerm}%")
                            ->orWhere('company', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('salesOrder', function ($q2) use ($searchTerm) {
                        $q2->where('nomor', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Filter by payment status
        if ($request->filled('status_pembayaran_filter')) {
            $statusFilterUI = $request->status_pembayaran_filter;
            if ($statusFilterUI !== 'all') {
                $statusMap = [
                    'lunas' => 'lunas',
                    'belum_bayar' => 'belum_bayar',
                    'sebagian' => 'sebagian',
                    'jatuh_tempo' => 'belum_bayar' // Special case for jatuh_tempo
                ];
                if (array_key_exists($statusFilterUI, $statusMap)) {
                    $query->where('status', $statusMap[$statusFilterUI]);
                }
            }
        } else {
            // By default, show only unpaid and partially paid invoices
            $query->whereIn('status', ['belum_bayar', 'sebagian']);
        }

        // Calculate summary statistics from the filtered query (before pagination)
        $filteredQuery = clone $query;
        $filteredInvoices = $filteredQuery->with(['pembayaranPiutang', 'uangMukaAplikasi'])->get();

        // If filtering for jatuh_tempo, we need to do post-DB filtering
        // since this combines DB status with date logic
        if ($request->filled('status_pembayaran_filter') && $request->status_pembayaran_filter === 'jatuh_tempo') {
            // Get all IDs of invoices that are overdue
            $overdueIds = $filteredInvoices->filter(function ($invoice) {
                $isOverdue = $invoice->jatuh_tempo && Carbon::parse($invoice->jatuh_tempo)->startOfDay()->lt(Carbon::today()->startOfDay());
                return $isOverdue && ($invoice->sisa_piutang > 0);
            })->pluck('id')->toArray();

            // Requery with these IDs to maintain pagination
            if (!empty($overdueIds)) {
                $query->whereIn('id', $overdueIds);
            } else {
                $query->where('id', 0); // No results if no overdue invoices
            }
            $invoices = $query->paginate(15)->withQueryString();

            // Recalculate statistics for overdue invoices only
            $overdueInvoices = $filteredInvoices->filter(function ($invoice) {
                $isOverdue = $invoice->jatuh_tempo && Carbon::parse($invoice->jatuh_tempo)->startOfDay()->lt(Carbon::today()->startOfDay());
                return $isOverdue && ($invoice->sisa_piutang > 0);
            });

            $totalPiutang = 0;
            $jumlahInvoiceBelumLunas = 0;
            $jatuhTempoMingguIni = 0;

            foreach ($overdueInvoices as $invoice) {
                $totalPiutang += $invoice->sisa_piutang;
                $jumlahInvoiceBelumLunas++;
                // For overdue invoices, they are already past due, so jatuhTempoMingguIni should be 0
            }
        } else {
            // Paginate the results
            $invoices = $query->paginate(15)->withQueryString();

            // Calculate summary statistics from filtered results
            $totalPiutang = 0;
            $jumlahInvoiceBelumLunas = 0;
            $jatuhTempoMingguIni = 0;
            $now = Carbon::now();
            $nextWeek = $now->copy()->addDays(7);

            foreach ($filteredInvoices as $invoice) {
                $totalPayments = $invoice->pembayaranPiutang->sum('jumlah');
                $sisaPiutang = $invoice->sisa_piutang; // Use accessor that includes nota kredit

                if ($sisaPiutang > 0) {
                    $totalPiutang += $sisaPiutang;
                    $jumlahInvoiceBelumLunas++;

                    if ($invoice->jatuh_tempo) {
                        $dueDate = Carbon::parse($invoice->jatuh_tempo);
                        // Check if due date is between now and next week
                        if ($dueDate->gte($now) && $dueDate->lt($nextWeek)) {
                            $jatuhTempoMingguIni++;
                        }
                    }
                }
            }
        }

        $customers = Customer::orderBy('nama')->get();

        // Get sales users (users who have created sales orders)
        $salesUsers = \App\Models\User::whereHas('salesOrders')
            ->with('karyawan')
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->display_name,
                    'email' => $user->display_email,
                ];
            });

        // Calculate total sisa_piutang for the current page
        $totalSisaPiutangCurrent = 0;
        foreach ($invoices as $invoice) {
            $totalPayments = $invoice->pembayaranPiutang->sum('jumlah');
            $sisaPiutang = $invoice->sisa_piutang; // Use accessor that includes nota kredit
            if ($sisaPiutang > 0) {
                $totalSisaPiutangCurrent += $sisaPiutang;
            }
        }

        if ($request->ajax()) {
            // For AJAX requests, return the full index view 
            // since there's no separate partial for the table
            return view('keuangan.piutang_usaha.index', [
                'invoices' => $invoices,
                'customers' => $customers,
                'salesUsers' => $salesUsers,
                'request' => $request,
                'totalPiutang' => $totalPiutang,
                'jumlahInvoiceBelumLunas' => $jumlahInvoiceBelumLunas,
                'jatuhTempoMingguIni' => $jatuhTempoMingguIni,
                'totalSisaPiutangCurrent' => $totalSisaPiutangCurrent,
                'sortColumn' => $sortColumn,
                'sortDirection' => $sortDirection,
            ]);
        }

        return view('keuangan.piutang_usaha.index', [
            'invoices' => $invoices,
            'customers' => $customers,
            'salesUsers' => $salesUsers,
            'request' => $request,
            'totalPiutang' => $totalPiutang,
            'jumlahInvoiceBelumLunas' => $jumlahInvoiceBelumLunas,
            'jatuhTempoMingguIni' => $jatuhTempoMingguIni,
            'totalSisaPiutangCurrent' => $totalSisaPiutangCurrent,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
        ]);
    }

    /**
     * Display details of a specific invoice's debt.
     *
     * @param  int  $id  (Invoice ID)
     * @return \\Illuminate\\Contracts\\View\\View
     */
    public function show($id): View
    {
        $invoice = Invoice::with([
            'customer',
            'user', // User who created/managed the invoice
            'details',
            'details.produk', // Invoice items
            'pembayaranPiutang',
            'pembayaranPiutang.user', // Payments for this invoice
            'uangMukaAplikasi', // Uang muka applications for this invoice
            'uangMukaAplikasi.uangMukaPenjualan', // Related uang muka penjualan records
            'salesOrder', // Parent Sales Order for context
            'salesOrder.user', // User who created SO
            'salesOrder.details',
            'salesOrder.details.produk', // Original SO items
            'salesOrder.returPenjualan' => function ($query) { // Returns related to parent SO
                $query->with(['details', 'details.produk', 'user'])->orderBy('tanggal', 'desc');
            }
        ])->findOrFail($id);

        // Total payments for this specific invoice (can also use $invoice->pembayaranPiutang->sum('jumlah'))
        $totalPaymentsForInvoice = $invoice->pembayaranPiutang->sum('jumlah');

        // Total uang muka applied to this invoice
        $totalUangMukaApplied = $invoice->uangMukaAplikasi->sum('jumlah_aplikasi');

        // Calculate total value of returns associated with the parent Sales Order (for informational display)
        $totalReturValueSO = 0;
        $returnsRelatedToSO = collect();

        if ($invoice->salesOrder) {
            $returnsRelatedToSO = $invoice->salesOrder->returPenjualan; // Already loaded with details
            foreach ($returnsRelatedToSO as $return) {
                // This calculation assumes ReturPenjualanDetail has quantity and we get price from original SO detail.
                // It would be better if ReturPenjualan model had a 'total_nilai_retur' attribute or an accessor.
                if ($return->salesOrder && $return->salesOrder->details) { // Ensure parent SO of return and its details are available
                    $soDetailsOfReturn = $return->salesOrder->details; // SO details related to the SO of the return document
                    foreach ($return->details as $returDetail) {
                        $matchingSoDetail = $soDetailsOfReturn->where('produk_id', $returDetail->produk_id)->first();
                        if ($matchingSoDetail) {
                            $totalReturValueSO += ($matchingSoDetail->harga ?? 0) * $returDetail->quantity;
                        }
                    }
                } elseif (isset($return->total_nilai_retur)) { // Ideal: if ReturPenjualan model has this
                    $totalReturValueSO += $return->total_nilai_retur;
                }
            }
        }

        // $invoice->sisa_piutang and $invoice->status_display are available from accessors on the Invoice model.

        return view('keuangan.piutang_usaha.show', [
            'invoice' => $invoice,
            'payments' => $invoice->pembayaranPiutang, // Payments specific to this invoice
            'totalPaymentsForInvoice' => $totalPaymentsForInvoice,
            'totalUangMukaApplied' => $totalUangMukaApplied, // Total uang muka applied
            'returnsRelatedToSO' => $returnsRelatedToSO, // For display
            'totalReturValueSO' => $totalReturValueSO,   // For display
        ]);
    }

    /**
     * Export piutang usaha data to Excel (invoice-centric).
     *
     * @param  \\Illuminate\\Http\\Request  $request
     * @return \\Symfony\\Component\\HttpFoundation\\BinaryFileResponse
     */
    public function export(Request $request)
    {
        $exportData = $request->all();

        // Add current sort and direction parameters
        $exportData['sort'] = $request->input('sort', 'tanggal');
        $exportData['direction'] = $request->input('direction', 'desc');

        // Create a filename with timestamp
        $fileName = 'laporan_piutang_usaha_' . date('YmdHis') . '.xlsx';

        return Excel::download(new PiutangUsahaExport($exportData), $fileName);
    }

    /**
     * Generate PDF for piutang usaha data (invoice-centric).
     *
     * @param  \\Illuminate\\Http\\Request  $request
     * @return \\Illuminate\\Http\\Response
     */
    public function generatePdf(Request $request)
    {
        $query = Invoice::with(['customer', 'salesOrder', 'pembayaranPiutang'])
            ->where('total', '>', 0);

        // Get sort column and direction
        $sortColumn = $request->input('sort', 'tanggal');
        $sortDirection = $request->input('direction', 'desc');

        // Validate sort column to prevent SQL injection
        $allowedColumns = [
            'nomor',
            'tanggal',
            'jatuh_tempo',
            'total',
            'status_pembayaran'
        ];

        if (in_array($sortColumn, $allowedColumns)) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy('tanggal', 'desc');
            $sortColumn = 'tanggal'; // Reset to default if invalid
        }

        // Apply filters
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('sales_id')) {
            $query->whereHas('salesOrder', function ($q) use ($request) {
                $q->where('user_id', $request->sales_id);
            });
        }
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->filled('status_pembayaran_filter')) {
            $statusFilterUI = $request->status_pembayaran_filter;
            if ($statusFilterUI !== 'all') {
                $statusMap = [
                    'lunas' => 'lunas',
                    'belum_bayar' => 'belum_bayar',
                    'sebagian' => 'sebagian',
                    'jatuh_tempo' => 'belum_bayar' // Special case for jatuh_tempo
                ];
                if (array_key_exists($statusFilterUI, $statusMap)) {
                    $query->where('status', $statusMap[$statusFilterUI]);
                }
            }
        } else {
            $query->whereIn('status', ['belum_bayar', 'sebagian']);
        }

        $invoices = $query->get();

        // Handle jatuh tempo filter if applied
        if ($request->filled('status_pembayaran_filter') && $request->status_pembayaran_filter === 'jatuh_tempo') {
            $invoices = $invoices->filter(function ($invoice) {
                $isOverdue = $invoice->jatuh_tempo && Carbon::parse($invoice->jatuh_tempo)->startOfDay()->lt(Carbon::today()->startOfDay());
                return $isOverdue && ($invoice->sisa_piutang > 0);
            });
        }

        // Calculate total piutang for the filtered set for PDF summary
        $totalPiutangPdf = 0;
        foreach ($invoices as $invoice) {
            $totalPayments = $invoice->pembayaranPiutang->sum('jumlah');
            $sisaPiutang = $invoice->sisa_piutang; // Use accessor that includes nota kredit
            if ($sisaPiutang > 0) {
                $totalPiutangPdf += $sisaPiutang;
            }
        }

        $data = [
            'invoices' => $invoices,
            'request' => $request, // Pass request for displaying filter criteria in PDF
            'tanggalCetak' => Carbon::now()->translatedFormat('d F Y'),
            'totalPiutangPdf' => $totalPiutangPdf,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection
        ];

        // Create PDF with updated view
        $pdf = Pdf::loadView('keuangan.piutang_usaha.pdf.piutang_usaha_pdf', $data);

        // Set paper size to A4 landscape
        $pdf->setPaper('a4', 'landscape');

        // Set options for better rendering
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial',
            'isFontSubsettingEnabled' => true,
            'dpi' => 150,
            'debugPng' => false,
            'debugKeepTemp' => false,
            'debugCss' => false,
            'enable_php' => true,
        ]);

        return $pdf->stream('laporan_piutang_usaha_' . date('YmdHis') . '.pdf');
    }

    /**
     * Display payment history for a specific invoice.
     * (This method might be redundant if show() already lists payments,
     * or it could be a dedicated view for just payment history if extensive)
     * For now, assuming it's similar to show() or part of it.
     * If a separate history view is needed, it would focus on $invoice->pembayaranPiutang.
     *
     * @param int $id (Invoice ID)
     * @return \\Illuminate\\Contracts\\View\\View
     */
    public function history($id): View
    {
        $invoice = Invoice::with([
            'customer',
            'pembayaranPiutang',
            'pembayaranPiutang.user', // User who recorded payment
            'pembayaranPiutang.transaksiKas', // If linked
            'pembayaranPiutang.transaksiBank', // If linked
            'salesOrder' // For context like SO number
        ])->findOrFail($id);

        // The view 'keuangan.piutang_usaha.history' will receive $invoice
        // and can iterate over $invoice->pembayaranPiutang

        return view('keuangan.piutang_usaha.history', [
            'invoice' => $invoice,
            'payments' => $invoice->pembayaranPiutang()->orderBy('tanggal', 'desc')->get(),
        ]);
    }
}
