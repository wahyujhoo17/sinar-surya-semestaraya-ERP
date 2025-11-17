<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LaporanPenjualanController extends Controller
{
    /**
     * Menampilkan halaman laporan penjualan
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tanggalAwal = now()->startOfMonth();
        $tanggalAkhir = now();
        $customers = Customer::orderBy('nama')->get();

        // Get all users (sales)
        $users = \App\Models\User::orderBy('name')->get();

        // Breadcrumbs
        $breadcrumbs = [
            ['name' => 'Dashboard', 'link' => route('dashboard')],
            ['name' => 'Laporan', 'link' => '#'],
            ['name' => 'Laporan Penjualan', 'link' => '#'],
        ];

        $currentPage = 'laporan-penjualan';

        return view('laporan.laporan_penjualan.index', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'customers',
            'users',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Ambil data laporan penjualan untuk tampilan tabel
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        try {
            $tanggalAwal = Carbon::parse($request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
            $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay();
            $customerId = $request->input('customer_id');
            $userId = $request->input('user_id');
            $statusPembayaran = $request->input('status_pembayaran');
            $search = $request->input('search');
            $perPage = $request->input('per_page', 25);
            $page = $request->input('page', 1);

            // Debug filter values
            Log::info('Laporan Penjualan Filter', [
                'tanggal_awal' => $tanggalAwal,
                'tanggal_akhir' => $tanggalAkhir,
                'customer_id' => $customerId,
                'user_id' => $userId,
                'status_pembayaran' => $statusPembayaran,
                'search' => $search
            ]);

            // Query sales_order dengan join tabel terkait
            $query = SalesOrder::query()
                ->select(
                    'sales_order.id',
                    'sales_order.nomor as nomor_faktur',
                    'sales_order.tanggal',
                    'sales_order.customer_id',
                    'sales_order.status_pembayaran as status',
                    'sales_order.total',
                    DB::raw('COALESCE(
                    (SELECT SUM(pp.jumlah) FROM pembayaran_piutang pp 
                     JOIN invoice i ON pp.invoice_id = i.id 
                     WHERE i.sales_order_id = sales_order.id), 
                    0
                ) as total_bayar'),
                    DB::raw('COALESCE(
                    (SELECT SUM(rp.total) FROM retur_penjualan rp 
                     WHERE rp.sales_order_id = sales_order.id), 
                    0
                ) as total_retur'),
                    DB::raw('COALESCE(
                    (SELECT SUM(i.uang_muka_terapkan) FROM invoice i 
                     WHERE i.sales_order_id = sales_order.id), 
                    0
                ) as total_uang_muka'),
                    'sales_order.catatan as keterangan',
                    'sales_order.created_at',
                    'sales_order.updated_at',
                    DB::raw('COALESCE(NULLIF(TRIM(customer.company), ""), NULLIF(TRIM(customer.nama), ""), customer.kode, CONCAT("Customer #", customer.id)) as customer_nama'),
                    'customer.kode as customer_kode',
                    'users.name as nama_petugas'
                )
                ->join('customer', 'sales_order.customer_id', '=', 'customer.id')
                ->leftJoin('users', 'sales_order.user_id', '=', 'users.id')
                ->whereBetween('sales_order.tanggal', [$tanggalAwal, $tanggalAkhir]);

            // Filter berdasarkan customer
            if ($customerId) {
                $query->where('sales_order.customer_id', $customerId);
            }

            // Filter berdasarkan user/sales
            if ($userId) {
                $query->where('sales_order.user_id', $userId);
            }

            // Filter berdasarkan status pembayaran
            if ($statusPembayaran) {
                $query->where('sales_order.status_pembayaran', $statusPembayaran);
            }

            // Filter pencarian
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('sales_order.nomor', 'like', "%{$search}%")
                        ->orWhere('customer.nama', 'like', "%{$search}%")
                        ->orWhere('customer.company', 'like', "%{$search}%")
                        ->orWhere('customer.kode', 'like', "%{$search}%");
                });
            }

            // Get total count for pagination
            $totalItems = $query->count();

            // Skip dan ambil data untuk pagination
            $skip = ($page - 1) * $perPage;
            $dataPenjualan = $query->orderBy('sales_order.tanggal', 'desc')
                ->skip($skip)
                ->take($perPage)
                ->get();

            // Debug results
            Log::info('Sales data results', [
                'count' => $dataPenjualan->count(),
                'first_item' => $dataPenjualan->first(),
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            // Hitung total penjualan, total dibayar, total uang muka, dan sisa pembayaran
            $totalPenjualan = $dataPenjualan->sum('total');
            $totalDibayar = $dataPenjualan->sum('total_bayar');
            $totalUangMuka = $dataPenjualan->sum('total_uang_muka');
            $sisaPembayaran = $totalPenjualan - $totalDibayar - $totalUangMuka;

            // Calculate last page
            $lastPage = ceil($totalItems / $perPage);

            return response()->json([
                'data' => $dataPenjualan,
                'total' => $totalItems,
                'current_page' => (int) $page,
                'per_page' => (int) $perPage,
                'last_page' => $lastPage,
                'totals' => [
                    'total_penjualan' => $totalPenjualan,
                    'total_dibayar' => $totalDibayar,
                    'total_uang_muka' => $totalUangMuka,
                    'sisa_pembayaran' => $sisaPembayaran
                ],
                'filter' => [
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in Laporan Penjualan getData: ' . $e->getMessage(), [
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
                    'total_penjualan' => 0,
                    'total_dibayar' => 0,
                    'total_uang_muka' => 0,
                    'sisa_pembayaran' => 0
                ]
            ], 500);
        }
    }

    /**
     * Export laporan penjualan ke Excel
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
        $fileName = "laporan_penjualan_{$fileDate}.xlsx";

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\LaporanPenjualanExport($request->all()),
            $fileName
        );
    }

    /**
     * Export laporan penjualan ke PDF
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        // Increase execution time for large reports
        set_time_limit(120);
        ini_set('memory_limit', '512M');

        $tanggalAwal = Carbon::parse($request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
        $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay();
        $customerId = $request->input('customer_id');
        $userId = $request->input('user_id');
        $statusPembayaran = $request->input('status_pembayaran');
        $search = $request->input('search');

        // OPTIMIZED: Single query dengan eager loading langsung
        $query = SalesOrder::query()
            ->with(['details.produk.satuan', 'customer', 'user'])
            ->select(
                'sales_order.*',
                DB::raw('COALESCE(
                    (SELECT SUM(pp.jumlah) FROM pembayaran_piutang pp 
                     JOIN invoice i ON pp.invoice_id = i.id 
                     WHERE i.sales_order_id = sales_order.id), 
                    0
                ) as total_bayar'),
                DB::raw('COALESCE(
                    (SELECT SUM(rp.total) FROM retur_penjualan rp 
                     WHERE rp.sales_order_id = sales_order.id), 
                    0
                ) as total_retur'),
                DB::raw('COALESCE(
                    (SELECT SUM(i.uang_muka_terapkan) FROM invoice i 
                     WHERE i.sales_order_id = sales_order.id), 
                    0
                ) as total_uang_muka')
            )
            ->whereBetween('sales_order.tanggal', [$tanggalAwal, $tanggalAkhir]);

        // Filter berdasarkan customer
        if ($customerId) {
            $query->where('sales_order.customer_id', $customerId);
        }

        // Filter berdasarkan user/sales
        if ($userId) {
            $query->where('sales_order.user_id', $userId);
        }

        // Filter berdasarkan status pembayaran
        if ($statusPembayaran) {
            $query->where('sales_order.status_pembayaran', $statusPembayaran);
        }

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('sales_order.nomor', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('company', 'like', "%{$search}%")
                            ->orWhere('kode', 'like', "%{$search}%");
                    });
            });
        }

        $dataPenjualan = $query->orderBy('sales_order.tanggal', 'desc')->get();

        // Hitung total penjualan, total dibayar, total uang muka, dan sisa pembayaran
        $totalPenjualan = $dataPenjualan->sum('total');
        $totalDibayar = $dataPenjualan->sum('total_bayar');
        $totalUangMuka = $dataPenjualan->sum('total_uang_muka');
        $sisaPembayaran = $totalPenjualan - $totalDibayar - $totalUangMuka;

        $filters = [
            'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
            'tanggal_akhir' => $tanggalAkhir->format('Y-m-d'),
            'customer_id' => $customerId,
            'user_id' => $userId,
            'status_pembayaran' => $statusPembayaran,
            'search' => $search
        ];

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.laporan_penjualan.pdf', [
            'dataPenjualan' => $dataPenjualan,
            'filters' => $filters,
            'totalPenjualan' => $totalPenjualan,
            'totalDibayar' => $totalDibayar,
            'totalUangMuka' => $totalUangMuka,
            'sisaPembayaran' => $sisaPembayaran
        ]);

        // Set paper size to A4 portrait
        $pdf->setPaper('a4', 'portrait');

        // Set options optimized for speed
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false, // Faster: disable remote resources
            'defaultFont' => 'Arial',
            'isFontSubsettingEnabled' => false, // Faster: disable font subsetting
            'dpi' => 96, // Lower DPI for faster rendering (was 150)
            'debugPng' => false,
            'debugKeepTemp' => false,
            'debugCss' => false,
            'enable_php' => true,
            'chroot' => public_path(), // Security: limit file access
        ]);

        // Format tanggal untuk nama file
        $fileDate = now()->format('Ymd_His');
        $fileName = "laporan_penjualan_detail_{$fileDate}.pdf";

        return $pdf->stream($fileName);
    }

    /**
     * Menampilkan detail penjualan
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function detail($id)
    {
        $penjualan = SalesOrder::with(['customer', 'details.produk.satuan', 'user', 'invoices.pembayaranPiutang', 'returPenjualan.details.produk.satuan', 'returPenjualan.user'])
            ->findOrFail($id);

        // Breadcrumbs
        $breadcrumbs = [
            ['name' => 'Dashboard', 'link' => route('dashboard')],
            ['name' => 'Laporan', 'link' => '#'],
            ['name' => 'Laporan Penjualan', 'link' => route('laporan.penjualan.index')],
            ['name' => 'Detail Penjualan #' . $penjualan->nomor, 'link' => '#'],
        ];

        $currentPage = 'laporan-penjualan-detail';

        return view('laporan.laporan_penjualan.detail', compact(
            'penjualan',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Generate PDF for a specific sales
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailPdf($id)
    {
        $penjualan = SalesOrder::with(['customer', 'details.produk.satuan', 'user', 'invoices.pembayaranPiutang', 'returPenjualan.details.produk.satuan', 'returPenjualan.user'])
            ->findOrFail($id);

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.laporan_penjualan.detail_pdf', compact('penjualan'));

        // Format for filename
        $fileName = "detail_penjualan_{$penjualan->nomor}_{$id}.pdf";

        return $pdf->stream($fileName);
    }

    /**
     * Get chart data for sales analytics
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChartData(Request $request)
    {
        try {
            $tanggalAwal = Carbon::parse($request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
            $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay();
            $customerId = $request->input('customer_id');
            $userId = $request->input('user_id');
            $statusPembayaran = $request->input('status_pembayaran');
            $chartType = $request->input('chart_type', 'monthly'); // monthly, daily, customer, status, product

            // Base query
            $query = SalesOrder::query()
                ->join('customer', 'sales_order.customer_id', '=', 'customer.id')
                ->leftJoin('users', 'sales_order.user_id', '=', 'users.id')
                ->whereBetween('sales_order.tanggal', [$tanggalAwal, $tanggalAkhir]);

            // Apply filters
            if ($customerId) {
                $query->where('sales_order.customer_id', $customerId);
            }

            if ($userId) {
                $query->where('sales_order.user_id', $userId);
            }

            if ($statusPembayaran) {
                $query->where('sales_order.status_pembayaran', $statusPembayaran);
            }

            $chartData = [];

            switch ($chartType) {
                case 'daily':
                    $chartData = $this->getDailyChart($query, $tanggalAwal, $tanggalAkhir);
                    break;

                case 'monthly':
                    $chartData = $this->getMonthlyChart($query, $tanggalAwal, $tanggalAkhir);
                    break;

                case 'customer':
                    $chartData = $this->getCustomerChart($query);
                    break;

                case 'status':
                    $chartData = $this->getStatusChart($query);
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
     * Get daily sales chart data
     */
    private function getDailyChart($query, $tanggalAwal, $tanggalAkhir)
    {
        $dailyData = $query->select(
            DB::raw('DATE(sales_order.tanggal) as date'),
            DB::raw('SUM(sales_order.total) as total_sales'),
            DB::raw('COUNT(*) as count_orders'),
            DB::raw('AVG(sales_order.total) as avg_order_value')
        )
            ->groupBy(DB::raw('DATE(sales_order.tanggal)'))
            ->orderBy('date')
            ->get();

        $labels = [];
        $salesData = [];
        $orderCounts = [];
        $avgOrderValues = [];

        foreach ($dailyData as $item) {
            $labels[] = Carbon::parse($item->date)->format('d M');
            $salesData[] = (float) $item->total_sales;
            $orderCounts[] = (int) $item->count_orders;
            $avgOrderValues[] = (float) $item->avg_order_value;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $salesData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                    'yAxisID' => 'y'
                ],
                [
                    'label' => 'Jumlah Order',
                    'data' => $orderCounts,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                    'borderColor' => 'rgba(16, 185, 129, 1)',
                    'borderWidth' => 2,
                    'yAxisID' => 'y1'
                ]
            ],
            'options' => [
                'responsive' => true,
                'interaction' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
                'scales' => [
                    'y' => [
                        'type' => 'linear',
                        'display' => true,
                        'position' => 'left',
                        'title' => [
                            'display' => true,
                            'text' => 'Total Penjualan (Rp)'
                        ]
                    ],
                    'y1' => [
                        'type' => 'linear',
                        'display' => true,
                        'position' => 'right',
                        'title' => [
                            'display' => true,
                            'text' => 'Jumlah Order'
                        ],
                        'grid' => [
                            'drawOnChartArea' => false,
                        ],
                    ]
                ]
            ]
        ];
    }

    /**
     * Get monthly sales chart data
     */
    private function getMonthlyChart($query, $tanggalAwal, $tanggalAkhir)
    {
        // Get the year from the date range
        $startYear = Carbon::parse($tanggalAwal)->year;
        $endYear = Carbon::parse($tanggalAkhir)->year;

        // If multiple years, use the most recent year or the end year
        $targetYear = $endYear;

        // Get monthly data for the target year
        $monthlyData = $query->select(
            DB::raw('MONTH(sales_order.tanggal) as month'),
            DB::raw('SUM(sales_order.total) as total_sales'),
            DB::raw('COUNT(*) as count_orders'),
            DB::raw('AVG(sales_order.total) as avg_order_value')
        )
            ->whereYear('sales_order.tanggal', $targetYear)
            ->groupBy(DB::raw('MONTH(sales_order.tanggal)'))
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Create data for all 12 months
        $labels = [];
        $salesData = [];
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

            if (isset($monthlyData[$month])) {
                $salesData[] = (float) $monthlyData[$month]->total_sales;
                $orderCounts[] = (int) $monthlyData[$month]->count_orders;
                $avgOrderValues[] = (float) $monthlyData[$month]->avg_order_value;
            } else {
                $salesData[] = 0;
                $orderCounts[] = 0;
                $avgOrderValues[] = 0;
            }
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => "Penjualan {$targetYear} (Rp)",
                    'data' => $salesData,
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
                    'label' => "Jumlah Transaksi {$targetYear}",
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
                'year' => $targetYear,
                'total_annual_sales' => array_sum($salesData),
                'total_annual_orders' => array_sum($orderCounts),
                'avg_monthly_sales' => array_sum($salesData) / 12,
                'best_month' => $labels[array_search(max($salesData), $salesData)] ?? 'N/A',
                'best_month_sales' => max($salesData)
            ]
        ];
    }

    /**
     * Get customer-wise sales chart data
     */
    private function getCustomerChart($query)
    {
        Log::info('Getting customer chart data');

        // Clone the query to avoid affecting other operations
        $customerQuery = clone $query;

        $customerData = $customerQuery->select(
            'customer.nama as customer_name',
            'customer.company as customer_company',
            'customer.kode as customer_code',
            DB::raw('SUM(sales_order.total) as total_sales'),
            DB::raw('COUNT(sales_order.id) as count_orders'),
            DB::raw('AVG(sales_order.total) as avg_order_value')
        )
            ->groupBy('customer.id', 'customer.nama', 'customer.company', 'customer.kode')
            ->orderBy('total_sales', 'desc')
            ->limit(5) // Limit to top 5 customers
            ->get();

        Log::info('Customer data retrieved', [
            'count' => $customerData->count(),
            'data' => $customerData->toArray()
        ]);

        // If no data found, return empty chart structure
        if ($customerData->isEmpty()) {
            Log::warning('No customer data found for chart');
            return [
                'labels' => ['Tidak ada data'],
                'datasets' => [
                    [
                        'label' => 'Total Penjualan',
                        'data' => [0],
                        'backgroundColor' => ['rgba(156, 163, 175, 0.8)'],
                        'borderColor' => ['rgba(156, 163, 175, 1)'],
                        'borderWidth' => 2
                    ]
                ]
            ];
        }

        $labels = [];
        $salesData = [];
        $orderCounts = [];
        $colors = [
            'rgba(59, 130, 246, 0.8)',   // Blue
            'rgba(16, 185, 129, 0.8)',   // Green
            'rgba(245, 158, 11, 0.8)',   // Yellow
            'rgba(239, 68, 68, 0.8)',    // Red
            'rgba(139, 92, 246, 0.8)',   // Purple
            'rgba(236, 72, 153, 0.8)',   // Pink
            'rgba(20, 184, 166, 0.8)',   // Teal
            'rgba(249, 115, 22, 0.8)',   // Orange
            'rgba(99, 102, 241, 0.8)',   // Indigo
            'rgba(34, 197, 94, 0.8)'     // Emerald
        ];

        $borderColors = [
            'rgba(59, 130, 246, 1)',
            'rgba(16, 185, 129, 1)',
            'rgba(245, 158, 11, 1)',
            'rgba(239, 68, 68, 1)',
            'rgba(139, 92, 246, 1)',
            'rgba(236, 72, 153, 1)',
            'rgba(20, 184, 166, 1)',
            'rgba(249, 115, 22, 1)',
            'rgba(99, 102, 241, 1)',
            'rgba(34, 197, 94, 1)'
        ];

        foreach ($customerData as $index => $item) {
            // Use customer name if available, otherwise use company name
            $displayName = !empty(trim($item->customer_name)) ? $item->customer_name : $item->customer_company;

            // If both name and company are empty, use customer code as fallback
            if (empty(trim($displayName))) {
                $displayName = $item->customer_code ?: 'Customer #' . ($index + 1);
            }

            // Truncate long names for better display
            if (strlen($displayName) > 25) {
                $displayName = substr($displayName, 0, 25) . '...';
            }

            $labels[] = $displayName;
            $salesData[] = (float) $item->total_sales;
            $orderCounts[] = (int) $item->count_orders;
        }

        $result = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $salesData,
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',   // Blue
                        'rgba(16, 185, 129, 0.8)',   // Green  
                        'rgba(245, 158, 11, 0.8)',   // Yellow
                        'rgba(239, 68, 68, 0.8)',    // Red
                        'rgba(139, 92, 246, 0.8)',   // Purple
                    ],
                    'borderColor' => [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(139, 92, 246, 1)',
                    ],
                    'borderWidth' => 2,
                    'hoverBackgroundColor' => [
                        'rgba(59, 130, 246, 0.9)',
                        'rgba(16, 185, 129, 0.9)',
                        'rgba(245, 158, 11, 0.9)',
                        'rgba(239, 68, 68, 0.9)',
                        'rgba(139, 92, 246, 0.9)',
                    ],
                    'hoverBorderWidth' => 3
                ]
            ],
            'meta' => [
                'total_customers' => count($customerData),
                'total_sales' => array_sum($salesData),
                'total_orders' => array_sum($orderCounts),
                'average_per_customer' => count($salesData) > 0 ? array_sum($salesData) / count($salesData) : 0,
                'top_customer' => $customerData->first() ?
                    (!empty(trim($customerData->first()->customer_name)) ?
                        $customerData->first()->customer_name :
                        $customerData->first()->customer_company) : 'N/A',
                'top_customer_sales' => $salesData[0] ?? 0
            ]
        ];

        Log::info('Customer chart result', $result);

        return $result;
    }

    /**
     * Get status-wise sales chart data
     */
    private function getStatusChart($query)
    {
        $statusData = $query->select(
            'sales_order.status_pembayaran',
            DB::raw('SUM(sales_order.total) as total_sales'),
            DB::raw('COUNT(*) as count_orders')
        )
            ->groupBy('sales_order.status_pembayaran')
            ->get();

        $labels = [];
        $salesData = [];
        $colors = [
            'lunas' => 'rgba(34, 197, 94, 0.8)',
            'sebagian' => 'rgba(245, 158, 11, 0.8)',
            'belum_bayar' => 'rgba(239, 68, 68, 0.8)',
            'kelebihan_bayar' => 'rgba(59, 130, 246, 0.8)'
        ];

        foreach ($statusData as $item) {
            $statusLabel = match ($item->status_pembayaran) {
                'lunas' => 'Lunas',
                'sebagian' => 'Dibayar Sebagian',
                'belum_bayar' => 'Belum Dibayar',
                'kelebihan_bayar' => 'Kelebihan Bayar',
                default => ucfirst($item->status_pembayaran)
            };

            $labels[] = $statusLabel;
            $salesData[] = (float) $item->total_sales;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $salesData,
                    'backgroundColor' => array_values($colors),
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff'
                ]
            ]
        ];
    }

    /**
     * Get product-wise sales chart data
     */
    private function getProductChart($query)
    {
        $productData = DB::table('sales_order')
            ->join('sales_order_detail', 'sales_order.id', '=', 'sales_order_detail.sales_order_id')
            ->join('produk', 'sales_order_detail.produk_id', '=', 'produk.id')
            ->join('customer', 'sales_order.customer_id', '=', 'customer.id')
            ->whereBetween('sales_order.tanggal', [
                Carbon::parse(request()->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')))->startOfDay(),
                Carbon::parse(request()->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay()
            ])
            ->select(
                'produk.nama as product_name',
                DB::raw('SUM(sales_order_detail.quantity) as total_quantity'),
                DB::raw('SUM(sales_order_detail.subtotal) as total_sales')
            )
            ->groupBy('produk.id', 'produk.nama')
            ->orderBy('total_sales', 'desc')
            ->limit(10)
            ->get();

        $labels = [];
        $salesData = [];
        $quantityData = [];

        foreach ($productData as $item) {
            $labels[] = strlen($item->product_name) > 15 ? substr($item->product_name, 0, 15) . '...' : $item->product_name;
            $salesData[] = (float) $item->total_sales;
            $quantityData[] = (float) $item->total_quantity;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $salesData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 1
                ]
            ]
        ];
    }

    /**
     * Get comparison chart data (current vs previous period)
     */
    private function getComparisonChart($query, $tanggalAwal, $tanggalAkhir)
    {
        $daysDiff = $tanggalAwal->diffInDays($tanggalAkhir);
        $previousStart = $tanggalAwal->copy()->subDays($daysDiff + 1);
        $previousEnd = $tanggalAwal->copy()->subDay();

        // Current period data
        $currentData = $query->select(
            DB::raw('SUM(sales_order.total) as total_sales'),
            DB::raw('COUNT(*) as count_orders'),
            DB::raw('AVG(sales_order.total) as avg_order_value')
        )->first();

        // Previous period data
        $previousQuery = SalesOrder::query()
            ->join('customer', 'sales_order.customer_id', '=', 'customer.id')
            ->whereBetween('sales_order.tanggal', [$previousStart, $previousEnd]);

        $previousData = $previousQuery->select(
            DB::raw('SUM(sales_order.total) as total_sales'),
            DB::raw('COUNT(*) as count_orders'),
            DB::raw('AVG(sales_order.total) as avg_order_value')
        )->first();

        return [
            'labels' => ['Total Penjualan', 'Jumlah Order', 'Rata-rata Order'],
            'datasets' => [
                [
                    'label' => 'Periode Saat Ini',
                    'data' => [
                        (float) ($currentData->total_sales ?? 0),
                        (int) ($currentData->count_orders ?? 0),
                        (float) ($currentData->avg_order_value ?? 0)
                    ],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2
                ],
                [
                    'label' => 'Periode Sebelumnya',
                    'data' => [
                        (float) ($previousData->total_sales ?? 0),
                        (int) ($previousData->count_orders ?? 0),
                        (float) ($previousData->avg_order_value ?? 0)
                    ],
                    'backgroundColor' => 'rgba(156, 163, 175, 0.8)',
                    'borderColor' => 'rgba(156, 163, 175, 1)',
                    'borderWidth' => 2
                ]
            ]
        ];
    }
}
