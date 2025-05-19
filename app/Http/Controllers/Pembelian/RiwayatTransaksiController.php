<?php

namespace App\Http\Controllers\Pembelian;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PenerimaanBarang;
use App\Models\Supplier;
use App\Models\PembayaranHutang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatTransaksiController extends Controller
{
    public function index(Request $request)
    {
        // Filter parameters
        $search = $request->input('search');
        $dateFilter = $request->input('date_filter', 'all');
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');
        $supplierId = $request->input('supplier_id');
        $status = $request->input('status', 'all');

        // Sorting parameters
        $sortColumn = $request->input('sort', 'tanggal');
        $sortDirection = $request->input('direction', 'desc');

        // Validate sort column to prevent SQL injection
        $allowedSortColumns = ['nomor', 'tanggal', 'total', 'status', 'supplier_id', 'status_pembayaran'];
        if (!in_array($sortColumn, $allowedSortColumns)) {
            $sortColumn = 'tanggal';
        }

        // Base query for purchase orders
        $query = PurchaseOrder::query();

        // We'll eager load these relationships after applying all filters and sorting
        $with = ['supplier', 'user', 'penerimaan', 'details'];

        // Apply sorting
        if ($sortColumn === 'supplier_id') {
            // Use leftJoin instead of join to ensure all purchase orders are included
            // even if supplier is missing
            $query->leftJoin('supplier', 'purchase_order.supplier_id', '=', 'supplier.id')
                ->select('purchase_order.*') // Keep only purchase_order fields
                ->orderBy('supplier.nama', $sortDirection);
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }

        // Apply filters
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by date
        if ($dateFilter !== 'all') {
            if ($dateFilter === 'today') {
                $query->whereDate('tanggal', now()->toDateString());
            } else if ($dateFilter === 'this_week') {
                $query->whereBetween('tanggal', [
                    now()->startOfWeek()->toDateString(),
                    now()->endOfWeek()->toDateString()
                ]);
            } else if ($dateFilter === 'this_month') {
                $query->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year);
            } else if ($dateFilter === 'last_month') {
                $query->whereMonth('tanggal', now()->subMonth()->month)
                    ->whereYear('tanggal', now()->subMonth()->year);
            } else if ($dateFilter === 'range' && $dateStart && $dateEnd) {
                $query->whereBetween('tanggal', [$dateStart, $dateEnd]);
            }
        }

        // Filter by supplier
        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Get PO data with pagination and eager load relationships after all filters are applied
        $transactions = $query->with($with)->paginate(10)->withQueryString();

        // Get suppliers for filter dropdown
        $suppliers = Supplier::orderBy('nama')->get();

        // Get total counts for status overview
        $statusCounts = [
            'all' => PurchaseOrder::count(),
            'draft' => PurchaseOrder::where('status', 'draft')->count(),
            'diproses' => PurchaseOrder::where('status', 'diproses')->count(),
            'dikirim' => PurchaseOrder::where('status', 'dikirim')->count(),
            'selesai' => PurchaseOrder::where('status', 'selesai')->count(),
            'dibatalkan' => PurchaseOrder::where('status', 'dibatalkan')->count(),
        ];

        // Get total purchase value - exclude draft and canceled POs
        $totalPurchaseValue = PurchaseOrder::whereNotIn('status', ['draft', 'dibatalkan'])
            ->sum('total');

        // Get POs that have outstanding payments - excluding draft/canceled POs and including all with partial payments
        $outstandingPOs = PurchaseOrder::whereNotIn('status', ['draft', 'dibatalkan'])
            ->get();

        // Calculate total outstanding by subtracting payments made from each PO's total
        $totalOutstanding = 0;
        foreach ($outstandingPOs as $po) {
            // Get total payments made for this PO
            $totalPaid = PembayaranHutang::where('purchase_order_id', $po->id)->sum('jumlah');

            // If there's a remaining balance, add it to outstanding
            $remainingBalance = $po->total - $totalPaid;
            if ($remainingBalance > 0) {
                $totalOutstanding += $remainingBalance;
            }
        }

        // Get total completed payments - sum of all payments made (including partial payments)
        $totalCompleted = PembayaranHutang::whereHas('purchaseOrder', function ($query) {
            $query->whereNotIn('status', ['draft', 'dibatalkan']);
        })->sum('jumlah');

        // For AJAX requests (table updates)
        if ($request->ajax()) {
            $tableHtml = view('pembelian.riwayat_transaksi._table', [
                'transactions' => $transactions,
                'sortColumn' => $sortColumn,
                'sortDirection' => $sortDirection
            ])->render();

            // Create pagination with AJAX links
            $paginationLinks = $transactions->appends([
                'status' => $status,
                'search' => $search,
                'date_filter' => $dateFilter,
                'date_start' => $dateStart,
                'date_end' => $dateEnd,
                'supplier_id' => $supplierId,
                'sort' => $sortColumn,
                'direction' => $sortDirection,
            ])->links('vendor.pagination.tailwind-custom')->render();

            // Modify pagination links to use AJAX
            $paginationHtml = $paginationLinks;

            return response()->json([
                'table_html' => $tableHtml,
                'pagination_html' => $paginationHtml,
            ]);
        }

        return view('pembelian.riwayat_transaksi.index', [
            'transactions' => $transactions,
            'suppliers' => $suppliers,
            'statusCounts' => $statusCounts,
            'search' => $search,
            'dateFilter' => $dateFilter,
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
            'selectedSupplier' => $supplierId,
            'selectedStatus' => $status,
            'totalPurchaseValue' => $totalPurchaseValue,
            'totalOutstanding' => $totalOutstanding,
            'totalCompleted' => $totalCompleted,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection
        ]);
    }

    public function show($id)
    {
        $transaction = PurchaseOrder::with([
            'supplier',
            'user',
            'details.produk',
            'details.satuan',
            'penerimaan.details'
        ])->findOrFail($id);

        // Get payment history
        $payments = PembayaranHutang::where('purchase_order_id', $id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('pembelian.riwayat_transaksi.show', [
            'transaction' => $transaction,
            'payments' => $payments
        ]);
    }
}
