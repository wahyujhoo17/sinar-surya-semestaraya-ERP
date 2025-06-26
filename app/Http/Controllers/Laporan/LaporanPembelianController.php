<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LaporanPembelianController extends Controller
{
    /**
     * Menampilkan halaman laporan pembelian
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tanggalAwal = now()->startOfMonth();
        $tanggalAkhir = now();
        $suppliers = Supplier::orderBy('nama')->get();

        // Breadcrumbs
        $breadcrumbs = [
            ['name' => 'Dashboard', 'link' => route('dashboard')],
            ['name' => 'Laporan', 'link' => '#'],
            ['name' => 'Laporan Pembelian', 'link' => '#'],
        ];

        $currentPage = 'laporan-pembelian';

        return view('laporan.laporan_pembelian.index', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'suppliers',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Ambil data laporan pembelian untuk tampilan tabel
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        try {
            $tanggalAwal = Carbon::parse($request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
            $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay();
            $supplierId = $request->input('supplier_id');
            $statusPembayaran = $request->input('status_pembayaran');
            $search = $request->input('search');
            $perPage = $request->input('per_page', 25);
            $page = $request->input('page', 1);

            // Debug filter values
            \Log::info('Laporan Pembelian Filter', [
                'tanggal_awal' => $tanggalAwal,
                'tanggal_akhir' => $tanggalAkhir,
                'supplier_id' => $supplierId,
                'status_pembayaran' => $statusPembayaran,
                'search' => $search
            ]);

            // Query purchase_order dengan join tabel terkait
            $query = PurchaseOrder::query()
                ->select(
                    'purchase_order.id',
                    'purchase_order.nomor as nomor_faktur',
                    'purchase_order.tanggal',
                    'purchase_order.supplier_id',
                    'purchase_order.status_pembayaran as status',
                    'purchase_order.total',
                    DB::raw('COALESCE(
                    (SELECT SUM(jumlah) FROM pembayaran_hutang WHERE purchase_order_id = purchase_order.id), 
                    0
                ) as total_bayar'),
                    'purchase_order.catatan as keterangan',
                    'purchase_order.created_at',
                    'purchase_order.updated_at',
                    'supplier.nama as supplier_nama',
                    'supplier.kode as supplier_kode',
                    'users.name as nama_petugas'
                )
                ->join('supplier', 'purchase_order.supplier_id', '=', 'supplier.id')
                ->leftJoin('users', 'purchase_order.user_id', '=', 'users.id')
                ->whereBetween('purchase_order.tanggal', [$tanggalAwal, $tanggalAkhir])
                ->where('purchase_order.status', '!=', 'draft'); // Exclude draft POs

            // Filter berdasarkan supplier
            if ($supplierId) {
                $query->where('purchase_order.supplier_id', $supplierId);
            }

            // Filter berdasarkan status pembayaran
            if ($statusPembayaran) {
                $query->where('purchase_order.status_pembayaran', $statusPembayaran);
            }

            // Filter pencarian
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('purchase_order.nomor', 'like', "%{$search}%")
                        ->orWhere('supplier.nama', 'like', "%{$search}%");
                });
            }

            // Get total count for pagination
            $totalItems = $query->count();

            // Calculate totals from all filtered data (not just paginated data)
            $totalsQuery = clone $query;
            $allFilteredData = $totalsQuery->get();
            $totalPembelian = $allFilteredData->sum('total');
            $totalDibayar = $allFilteredData->sum('total_bayar');
            $sisaPembayaran = $totalPembelian - $totalDibayar;

            // Skip dan ambil data untuk pagination
            $skip = ($page - 1) * $perPage;
            $dataPembelian = $query->orderBy('purchase_order.tanggal', 'desc')
                ->skip($skip)
                ->take($perPage)
                ->get();

            // Debug results
            \Log::info('Purchase data results', [
                'count' => $dataPembelian->count(),
                'total_items' => $totalItems,
                'total_pembelian' => $totalPembelian,
                'first_item' => $dataPembelian->first(),
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings(),
                'filters_applied' => [
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d'),
                    'supplier_id' => $supplierId,
                    'status_pembayaran' => $statusPembayaran,
                    'search' => $search
                ]
            ]);            // Calculate last page
            $lastPage = ceil($totalItems / $perPage);

            return response()->json([
                'data' => $dataPembelian,
                'total' => $totalItems,
                'current_page' => (int) $page,
                'per_page' => (int) $perPage,
                'last_page' => $lastPage,
                'totals' => [
                    'total_pembelian' => $totalPembelian,
                    'total_dibayar' => $totalDibayar,
                    'sisa_pembayaran' => $sisaPembayaran
                ],
                'filter' => [
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d')
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in Laporan Pembelian getData: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Terjadi kesalahan saat memuat data',
                'message' => $e->getMessage(),
                'data' => [],
                'total' => 0,
                'current_page' => 1,
                'per_page' => $perPage,
                'last_page' => 1,
                'totals' => [
                    'total_pembelian' => 0,
                    'total_dibayar' => 0,
                    'sisa_pembayaran' => 0
                ]
            ], 500);
        }
    }

    /**
     * Export laporan pembelian ke Excel
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel(Request $request)
    {
        $tanggalAwal = $request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->input('tanggal_akhir', now()->format('Y-m-d'));

        // Format tanggal untuk nama file
        $fileDate = now()->format('Ymd_His');
        $fileName = "laporan_pembelian_{$fileDate}.xlsx";

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\LaporanPembelianExport($request->all()),
            $fileName
        );
    }

    /**
     * Export laporan pembelian ke PDF
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $tanggalAwal = Carbon::parse($request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
        $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay();
        $supplierId = $request->input('supplier_id');
        $statusPembayaran = $request->input('status_pembayaran');
        $search = $request->input('search');

        // Query purchase_order dengan join tabel terkait
        $query = PurchaseOrder::query()
            ->select(
                'purchase_order.id',
                'purchase_order.nomor as nomor_faktur',
                'purchase_order.tanggal',
                'purchase_order.supplier_id',
                'purchase_order.status_pembayaran as status',
                'purchase_order.total',
                DB::raw('COALESCE(
                    (SELECT SUM(jumlah) FROM pembayaran_hutang WHERE purchase_order_id = purchase_order.id), 
                    0
                ) as total_bayar'),
                'purchase_order.catatan as keterangan',
                'purchase_order.created_at',
                'purchase_order.updated_at',
                'supplier.nama as supplier_nama',
                'supplier.kode as supplier_kode',
                'users.name as nama_petugas'
            )
            ->join('supplier', 'purchase_order.supplier_id', '=', 'supplier.id')
            ->leftJoin('users', 'purchase_order.user_id', '=', 'users.id')
            ->whereBetween('purchase_order.tanggal', [$tanggalAwal, $tanggalAkhir])
            ->where('purchase_order.status', '!=', 'draft'); // Exclude draft POs

        // Filter berdasarkan supplier
        if ($supplierId) {
            $query->where('purchase_order.supplier_id', $supplierId);
        }

        // Filter berdasarkan status pembayaran
        if ($statusPembayaran) {
            $query->where('purchase_order.status_pembayaran', $statusPembayaran);
        }

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('purchase_order.nomor', 'like', "%{$search}%")
                    ->orWhere('supplier.nama', 'like', "%{$search}%");
            });
        }

        $dataPembelian = $query->orderBy('purchase_order.tanggal', 'desc')->get();

        // Hitung total pembelian, total dibayar, dan sisa pembayaran
        $totalPembelian = $dataPembelian->sum('total');
        $totalDibayar = $dataPembelian->sum('total_bayar');
        $sisaPembayaran = $totalPembelian - $totalDibayar;

        $filters = [
            'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
            'tanggal_akhir' => $tanggalAkhir->format('Y-m-d'),
            'supplier_id' => $supplierId,
            'status_pembayaran' => $statusPembayaran,
            'search' => $search
        ];

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.laporan_pembelian.pdf', [
            'dataPembelian' => $dataPembelian,
            'filters' => $filters,
            'totalPembelian' => $totalPembelian,
            'totalDibayar' => $totalDibayar,
            'sisaPembayaran' => $sisaPembayaran
        ]);

        // Format tanggal untuk nama file
        $fileDate = now()->format('Ymd_His');
        $fileName = "laporan_pembelian_{$fileDate}.pdf";

        return $pdf->download($fileName);
    }

    /**
     * Menampilkan detail pembelian
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function detail($id)
    {
        $pembelian = PurchaseOrder::with(['supplier', 'details.produk.satuan', 'user'])
            ->findOrFail($id);

        // Breadcrumbs
        $breadcrumbs = [
            ['name' => 'Dashboard', 'link' => route('dashboard')],
            ['name' => 'Laporan', 'link' => '#'],
            ['name' => 'Laporan Pembelian', 'link' => route('laporan.pembelian.index')],
            ['name' => 'Detail Pembelian #' . $pembelian->nomor, 'link' => '#'],
        ];

        $currentPage = 'laporan-pembelian-detail';

        return view('laporan.laporan_pembelian.detail', compact(
            'pembelian',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Generate PDF for a specific purchase
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailPdf($id)
    {
        $pembelian = PurchaseOrder::with(['supplier', 'details.produk.satuan', 'user'])
            ->findOrFail($id);

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.laporan_pembelian.detail_pdf', compact('pembelian'));

        // Format for filename
        $fileName = "detail_pembelian_{$pembelian->nomor}_{$id}.pdf";

        return $pdf->download($fileName);
    }

    /**
     * Get chart data for purchase analytics
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChartData(Request $request)
    {
        try {
            $tanggalAwal = Carbon::parse($request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
            $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay();
            $supplierId = $request->input('supplier_id');
            $statusPembayaran = $request->input('status_pembayaran');
            $chartType = $request->input('chart_type', 'monthly'); // monthly, daily, supplier, status, product

            // Base query
            $query = PurchaseOrder::query()
                ->join('supplier', 'purchase_order.supplier_id', '=', 'supplier.id')
                ->leftJoin('users', 'purchase_order.user_id', '=', 'users.id')
                ->whereBetween('purchase_order.tanggal', [$tanggalAwal, $tanggalAkhir])
                ->where('purchase_order.status', '!=', 'draft'); // Exclude draft POs

            // Apply filters
            if ($supplierId) {
                $query->where('purchase_order.supplier_id', $supplierId);
            }

            if ($statusPembayaran) {
                $query->where('purchase_order.status_pembayaran', $statusPembayaran);
            }

            $chartData = [];

            switch ($chartType) {
                case 'daily':
                    $chartData = $this->getDailyChart($query, $tanggalAwal, $tanggalAkhir);
                    break;

                case 'monthly':
                    $chartData = $this->getMonthlyChart($query, $tanggalAwal, $tanggalAkhir);
                    break;

                case 'supplier':
                    $chartData = $this->getSupplierChart($query);
                    break;

                case 'status':
                    // For status chart, clone query and remove status_pembayaran filter
                    $statusQuery = PurchaseOrder::query()
                        ->join('supplier', 'purchase_order.supplier_id', '=', 'supplier.id')
                        ->leftJoin('users', 'purchase_order.user_id', '=', 'users.id')
                        ->whereBetween('purchase_order.tanggal', [$tanggalAwal, $tanggalAkhir])
                        ->where('purchase_order.status', '!=', 'draft'); // Exclude draft POs

                    // Apply only supplier and search filters, not status_pembayaran
                    if ($supplierId) {
                        $statusQuery->where('purchase_order.supplier_id', $supplierId);
                    }

                    // Apply search filter if present
                    $search = $request->input('search');
                    if ($search) {
                        $statusQuery->where(function ($q) use ($search) {
                            $q->where('purchase_order.nomor', 'like', "%{$search}%")
                                ->orWhere('supplier.nama', 'like', "%{$search}%");
                        });
                    }

                    $chartData = $this->getStatusChart($statusQuery);
                    break;

                case 'product':
                    $chartData = $this->getProductChart($query);
                    break;

                case 'comparison':
                    $chartData = $this->getComparisonChart($query, $tanggalAwal, $tanggalAkhir);
                    break;

                default:
                    $chartData = $this->getMonthlyChart($query, $tanggalAwal, $tanggalAkhir);
            }

            return response()->json([
                'success' => true,
                'data' => $chartData,
                'chart_type' => $chartType
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getChartData: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data chart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get daily purchase chart data
     */
    private function getDailyChart($query, $tanggalAwal, $tanggalAkhir)
    {
        $dailyData = $query->select(
            DB::raw('DATE(purchase_order.tanggal) as date'),
            DB::raw('SUM(purchase_order.total) as total_purchases'),
            DB::raw('COUNT(*) as count_orders'),
            DB::raw('AVG(purchase_order.total) as avg_order_value')
        )
            ->groupBy(DB::raw('DATE(purchase_order.tanggal)'))
            ->orderBy('date')
            ->get();

        $labels = [];
        $purchasesData = [];
        $orderCounts = [];

        foreach ($dailyData as $item) {
            $labels[] = Carbon::parse($item->date)->format('d M');
            $purchasesData[] = (float) $item->total_purchases;
            $orderCounts[] = (int) $item->count_orders;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nilai Pembelian (Rp)',
                    'data' => $purchasesData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => 'rgba(59, 130, 246, 1)',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 6,
                    'pointHoverRadius' => 8,
                    'yAxisID' => 'y'
                ],
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $orderCounts,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'borderColor' => 'rgba(16, 185, 129, 1)',
                    'borderWidth' => 2,
                    'fill' => false,
                    'tension' => 0.4,
                    'pointBackgroundColor' => 'rgba(16, 185, 129, 1)',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'yAxisID' => 'y1'
                ]
            ]
        ];
    }

    /**
     * Get monthly purchase chart data
     */
    private function getMonthlyChart($query, $tanggalAwal, $tanggalAkhir)
    {
        // Parse dates
        $startDate = Carbon::parse($tanggalAwal);
        $endDate = Carbon::parse($tanggalAkhir);

        // Get the year from the date range
        $startYear = $startDate->year;
        $endYear = $endDate->year;

        // Determine target year for display
        // If single year, use that year
        // If multiple years, use the year with most data or the end year
        $targetYear = $endYear;

        // If date range spans multiple years, create a combined label
        $yearLabel = $startYear === $endYear ? $endYear : "{$startYear}-{$endYear}";

        // Debug log for year calculation
        \Log::info('Monthly Chart Year Calculation', [
            'start_date' => $tanggalAwal,
            'end_date' => $tanggalAkhir,
            'start_year' => $startYear,
            'end_year' => $endYear,
            'target_year' => $targetYear,
            'year_label' => $yearLabel
        ]);

        // Get monthly data - remove the additional whereYear filter 
        // since the base query already has the date range filter
        $monthlyData = $query->select(
            DB::raw('MONTH(purchase_order.tanggal) as month'),
            DB::raw('YEAR(purchase_order.tanggal) as year'),
            DB::raw('SUM(purchase_order.total) as total_purchases'),
            DB::raw('COUNT(*) as count_orders'),
            DB::raw('AVG(purchase_order.total) as avg_order_value')
        )
            ->groupBy(DB::raw('YEAR(purchase_order.tanggal)'), DB::raw('MONTH(purchase_order.tanggal)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Debug log
        \Log::info('Monthly Chart Data Query', [
            'start_date' => $tanggalAwal,
            'end_date' => $tanggalAkhir,
            'target_year' => $targetYear,
            'monthly_data_count' => $monthlyData->count(),
            'monthly_data' => $monthlyData->toArray(),
            'sql' => $query->toSql()
        ]);

        // Group data by month (and year if needed)
        $monthlyDataGrouped = [];
        foreach ($monthlyData as $item) {
            $key = $item->year . '-' . $item->month; // Use year-month as key for multiple years
            if (!isset($monthlyDataGrouped[$item->month])) {
                $monthlyDataGrouped[$item->month] = [
                    'total_purchases' => 0,
                    'count_orders' => 0,
                    'avg_order_value' => 0
                ];
            }
            // Aggregate data for each month across years
            $monthlyDataGrouped[$item->month]['total_purchases'] += $item->total_purchases;
            $monthlyDataGrouped[$item->month]['count_orders'] += $item->count_orders;
            // Recalculate average
            $monthlyDataGrouped[$item->month]['avg_order_value'] =
                $monthlyDataGrouped[$item->month]['count_orders'] > 0 ?
                $monthlyDataGrouped[$item->month]['total_purchases'] / $monthlyDataGrouped[$item->month]['count_orders'] : 0;
        }

        // Create data for all 12 months
        $labels = [];
        $purchasesData = [];
        $orderCounts = [];
        $avgOrderValues = [];

        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        for ($month = 1; $month <= 12; $month++) {
            $labels[] = $monthNames[$month];

            if (isset($monthlyDataGrouped[$month])) {
                $purchasesData[] = (float) $monthlyDataGrouped[$month]['total_purchases'];
                $orderCounts[] = (int) $monthlyDataGrouped[$month]['count_orders'];
                $avgOrderValues[] = (float) $monthlyDataGrouped[$month]['avg_order_value'];
            } else {
                $purchasesData[] = 0;
                $orderCounts[] = 0;
                $avgOrderValues[] = 0;
            }
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => "Pembelian {$yearLabel} (Rp)",
                    'data' => $purchasesData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => 'rgba(59, 130, 246, 1)',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 6,
                    'pointHoverRadius' => 8
                ],
                [
                    'label' => "Jumlah Transaksi {$yearLabel}",
                    'data' => $orderCounts,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'borderColor' => 'rgba(16, 185, 129, 1)',
                    'borderWidth' => 2,
                    'fill' => false,
                    'tension' => 0.4,
                    'pointBackgroundColor' => 'rgba(16, 185, 129, 1)',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'yAxisID' => 'y1'
                ]
            ],
            'meta' => [
                'year' => $yearLabel,
                'date_range' => $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d'),
                'target_year' => $targetYear,
                'year_label' => $yearLabel,
                'total_annual_purchases' => array_sum($purchasesData),
                'total_annual_orders' => array_sum($orderCounts),
                'avg_monthly_purchases' => count($purchasesData) > 0 ? array_sum($purchasesData) / 12 : 0,
                'best_month' => count($purchasesData) > 0 ? ($labels[array_search(max($purchasesData), $purchasesData)] ?? 'N/A') : 'N/A',
                'best_month_purchases' => count($purchasesData) > 0 ? max($purchasesData) : 0,
                'months_with_data' => count(array_filter($purchasesData, function ($value) {
                    return $value > 0;
                }))
            ]
        ];
    }

    /**
     * Get supplier-wise purchase chart data
     */
    private function getSupplierChart($query)
    {
        Log::info('Getting supplier chart data');

        // Clone the query to avoid affecting other operations
        $supplierQuery = clone $query;

        $supplierData = $supplierQuery->select(
            'supplier.nama as supplier_name',
            'supplier.alamat as supplier_alamat',
            'supplier.kode as supplier_code',
            DB::raw('SUM(purchase_order.total) as total_purchases'),
            DB::raw('COUNT(purchase_order.id) as count_orders'),
            DB::raw('AVG(purchase_order.total) as avg_order_value')
        )
            ->whereNotNull('purchase_order.total')
            ->where('purchase_order.total', '>', 0)
            ->groupBy('supplier.id', 'supplier.nama', 'supplier.alamat', 'supplier.kode')
            ->orderBy('total_purchases', 'desc')
            ->limit(5) // Limit to top 5 suppliers
            ->get();

        Log::info('Supplier data retrieved', [
            'count' => $supplierData->count(),
            'data' => $supplierData->toArray()
        ]);

        // If no data found, return empty chart structure
        if ($supplierData->isEmpty()) {
            Log::warning('No supplier data found for chart');
            return [
                'labels' => ['Tidak ada data'],
                'datasets' => [
                    [
                        'label' => 'Total Pembelian',
                        'data' => [0],
                        'backgroundColor' => ['rgba(107, 114, 128, 0.5)'],
                        'borderColor' => ['rgba(107, 114, 128, 1)'],
                        'borderWidth' => 1
                    ]
                ]
            ];
        }

        $labels = [];
        $purchasesData = [];
        $colors = [
            'rgba(59, 130, 246, 0.8)',   // Blue
            'rgba(16, 185, 129, 0.8)',   // Green
            'rgba(245, 158, 11, 0.8)',   // Yellow
            'rgba(239, 68, 68, 0.8)',    // Red
            'rgba(139, 92, 246, 0.8)',   // Purple
        ];

        $borderColors = [
            'rgba(59, 130, 246, 1)',
            'rgba(16, 185, 129, 1)',
            'rgba(245, 158, 11, 1)',
            'rgba(239, 68, 68, 1)',
            'rgba(139, 92, 246, 1)',
        ];

        foreach ($supplierData as $index => $item) {
            // Use supplier name if available, otherwise use alamat, fallback to code
            $displayName = !empty(trim($item->supplier_name)) ? $item->supplier_name : (!empty(trim($item->supplier_alamat)) ? $item->supplier_alamat : $item->supplier_code);

            // Truncate long names for better display
            if (strlen($displayName) > 25) {
                $displayName = substr($displayName, 0, 22) . '...';
            }

            $labels[] = $displayName;
            $purchasesData[] = (float) $item->total_purchases;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Pembelian (Rp)',
                    'data' => $purchasesData,
                    'backgroundColor' => array_slice($colors, 0, count($labels)),
                    'borderColor' => array_slice($borderColors, 0, count($labels)),
                    'borderWidth' => 2
                ]
            ]
        ];
    }

    /**
     * Get status-wise purchase chart data
     */
    private function getStatusChart($query)
    {
        $statusData = $query->select(
            'purchase_order.status_pembayaran',
            DB::raw('COUNT(*) as count_orders'),
            DB::raw('SUM(purchase_order.total) as total_purchases')
        )
            ->groupBy('purchase_order.status_pembayaran')
            ->get();

        // Debug log to check status data
        $totalStatusCount = $statusData->sum('count_orders');
        \Log::info('Status Chart Data', [
            'data' => $statusData->toArray(),
            'total_count' => $totalStatusCount,
            'query_sql' => $query->toSql(),
            'query_bindings' => $query->getBindings()
        ]);

        $labels = [];
        $data = [];
        $colors = [
            'lunas' => 'rgba(34, 197, 94, 0.8)',        // Green
            'sebagian' => 'rgba(245, 158, 11, 0.8)',    // Yellow
            'belum_bayar' => 'rgba(239, 68, 68, 0.8)',  // Red
            'kelebihan_bayar' => 'rgba(59, 130, 246, 0.8)' // Blue
        ];

        $statusLabels = [
            'lunas' => 'Lunas',
            'sebagian' => 'Dibayar Sebagian',
            'belum_bayar' => 'Belum Dibayar',
            'kelebihan_bayar' => 'Kelebihan Bayar'
        ];

        $backgroundColors = [];
        $borderColors = [];

        foreach ($statusData as $item) {
            $status = $item->status_pembayaran;
            $labels[] = $statusLabels[$status] ?? ucfirst($status);
            $data[] = (float) $item->total_purchases;
            $backgroundColors[] = $colors[$status] ?? 'rgba(107, 114, 128, 0.8)';
            $borderColors[] = str_replace('0.8', '1', $colors[$status] ?? 'rgba(107, 114, 128, 1)');
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Pembelian',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 2
                ]
            ],
            'meta' => [
                'total_count' => $totalStatusCount,
                'breakdown' => $statusData->mapWithKeys(function ($item) {
                    return [$item->status_pembayaran => $item->count_orders];
                })->toArray()
            ]
        ];
    }

    /**
     * Get product-wise purchase chart data
     */
    private function getProductChart($query)
    {
        Log::info('Getting product chart data');

        // Clone the query to avoid affecting other operations  
        $productQuery = clone $query;

        $productData = $productQuery->join('purchase_order_detail', 'purchase_order.id', '=', 'purchase_order_detail.po_id')
            ->join('produk', 'purchase_order_detail.produk_id', '=', 'produk.id')
            ->leftJoin('satuan', 'produk.satuan_id', '=', 'satuan.id')
            ->select(
                'produk.nama as product_name',
                'produk.kode as product_code',
                'satuan.nama as unit_name',
                DB::raw('SUM(purchase_order_detail.quantity) as total_qty'),
                DB::raw('SUM(purchase_order_detail.subtotal) as total_purchases'),
                DB::raw('COUNT(DISTINCT purchase_order.id) as count_orders')
            )
            ->whereNotNull('purchase_order_detail.quantity')
            ->where('purchase_order_detail.quantity', '>', 0)
            ->whereNotNull('purchase_order_detail.subtotal')
            ->where('purchase_order_detail.subtotal', '>', 0)
            ->groupBy('produk.id', 'produk.nama', 'produk.kode', 'satuan.nama')
            ->orderBy('total_purchases', 'desc')
            ->limit(10)
            ->get();

        Log::info('Product data retrieved', [
            'count' => $productData->count(),
            'data' => $productData->toArray()
        ]);

        // If no data found, return empty chart structure
        if ($productData->isEmpty()) {
            Log::warning('No product data found for chart');
            return [
                'labels' => ['Tidak ada data'],
                'datasets' => [
                    [
                        'label' => 'Total Pembelian',
                        'data' => [0],
                        'backgroundColor' => ['rgba(107, 114, 128, 0.5)'],
                        'borderColor' => ['rgba(107, 114, 128, 1)'],
                        'borderWidth' => 1
                    ]
                ]
            ];
        }

        $labels = [];
        $data = [];
        $quantities = [];
        $metadata = [];
        $colors = [
            'rgba(59, 130, 246, 0.8)',
            'rgba(16, 185, 129, 0.8)',
            'rgba(245, 158, 11, 0.8)',
            'rgba(239, 68, 68, 0.8)',
            'rgba(139, 92, 246, 0.8)',
            'rgba(236, 72, 153, 0.8)',
            'rgba(20, 184, 166, 0.8)',
            'rgba(249, 115, 22, 0.8)',
            'rgba(99, 102, 241, 0.8)',
            'rgba(34, 197, 94, 0.8)'
        ];

        foreach ($productData as $index => $item) {
            $productName = !empty(trim($item->product_name)) ? $item->product_name : $item->product_code;
            if (strlen($productName) > 30) {
                $productName = substr($productName, 0, 27) . '...';
            }

            $labels[] = $productName;
            $data[] = (float) $item->total_purchases;
            $quantities[] = (float) $item->total_qty;

            // Store metadata for each product
            $metadata[] = [
                'quantity' => (float) $item->total_qty,
                'unit' => $item->unit_name ?? 'unit',
                'orders' => (int) $item->count_orders,
                'full_name' => $item->product_name,
                'code' => $item->product_code
            ];
        }

        return [
            'labels' => $labels,
            'quantities' => $quantities,
            'metadata' => $metadata,
            'datasets' => [
                [
                    'label' => 'Total Pembelian (Rp)',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($labels)),
                    'borderColor' => array_map(function ($color) {
                        return str_replace('0.8', '1', $color);
                    }, array_slice($colors, 0, count($labels))),
                    'borderWidth' => 2
                ]
            ]
        ];
    }

    /**
     * Get comparison chart data
     */
    private function getComparisonChart($query, $tanggalAwal, $tanggalAkhir)
    {
        // Current period data
        $currentData = $query->select(
            DB::raw('SUM(purchase_order.total) as total_purchases'),
            DB::raw('COUNT(*) as count_orders'),
            DB::raw('AVG(purchase_order.total) as avg_order_value')
        )
            ->first();

        // Previous period data (same length of time before current period)
        $periodLength = Carbon::parse($tanggalAwal)->diffInDays(Carbon::parse($tanggalAkhir));
        $prevStartDate = Carbon::parse($tanggalAwal)->subDays($periodLength + 1);
        $prevEndDate = Carbon::parse($tanggalAwal)->subDay();

        $prevData = PurchaseOrder::whereBetween('tanggal', [$prevStartDate, $prevEndDate])
            ->where('status', '!=', 'draft') // Exclude draft POs
            ->select(
                DB::raw('SUM(total) as total_purchases'),
                DB::raw('COUNT(*) as count_orders'),
                DB::raw('AVG(total) as avg_order_value')
            )
            ->first();

        return [
            'labels' => ['Periode Sebelumnya', 'Periode Saat Ini'],
            'datasets' => [
                [
                    'label' => 'Total Pembelian (Rp)',
                    'data' => [
                        (float) ($prevData->total_purchases ?? 0),
                        (float) ($currentData->total_purchases ?? 0)
                    ],
                    'backgroundColor' => ['rgba(107, 114, 128, 0.8)', 'rgba(59, 130, 246, 0.8)'],
                    'borderColor' => ['rgba(107, 114, 128, 1)', 'rgba(59, 130, 246, 1)'],
                    'borderWidth' => 2
                ],
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => [
                        (int) ($prevData->count_orders ?? 0),
                        (int) ($currentData->count_orders ?? 0)
                    ],
                    'backgroundColor' => ['rgba(156, 163, 175, 0.8)', 'rgba(16, 185, 129, 0.8)'],
                    'borderColor' => ['rgba(156, 163, 175, 1)', 'rgba(16, 185, 129, 1)'],
                    'borderWidth' => 2,
                    'yAxisID' => 'y1'
                ]
            ]
        ];
    }
}
