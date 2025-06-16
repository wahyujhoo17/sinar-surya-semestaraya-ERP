<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            $statusPembayaran = $request->input('status_pembayaran');
            $search = $request->input('search');
            $perPage = $request->input('per_page', 25);
            $page = $request->input('page', 1);

            // Debug filter values
            \Log::info('Laporan Penjualan Filter', [
                'tanggal_awal' => $tanggalAwal,
                'tanggal_akhir' => $tanggalAkhir,
                'customer_id' => $customerId,
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
                    'sales_order.catatan as keterangan',
                    'sales_order.created_at',
                    'sales_order.updated_at',
                    'customer.nama as customer_nama',
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

            // Filter berdasarkan status pembayaran
            if ($statusPembayaran) {
                $query->where('sales_order.status_pembayaran', $statusPembayaran);
            }

            // Filter pencarian
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('sales_order.nomor', 'like', "%{$search}%")
                        ->orWhere('customer.nama', 'like', "%{$search}%");
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
            \Log::info('Sales data results', [
                'count' => $dataPenjualan->count(),
                'first_item' => $dataPenjualan->first(),
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            // Hitung total penjualan, total dibayar, dan sisa pembayaran
            $totalPenjualan = $dataPenjualan->sum('total');
            $totalDibayar = $dataPenjualan->sum('total_bayar');
            $sisaPembayaran = $totalPenjualan - $totalDibayar;

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
                    'sisa_pembayaran' => $sisaPembayaran
                ],
                'filter' => [
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d')
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in Laporan Penjualan getData: ' . $e->getMessage(), [
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
        $tanggalAwal = Carbon::parse($request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
        $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay();
        $customerId = $request->input('customer_id');
        $statusPembayaran = $request->input('status_pembayaran');
        $search = $request->input('search');

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
                'sales_order.catatan as keterangan',
                'sales_order.created_at',
                'sales_order.updated_at',
                'customer.nama as customer_nama',
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

        // Filter berdasarkan status pembayaran
        if ($statusPembayaran) {
            $query->where('sales_order.status_pembayaran', $statusPembayaran);
        }

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('sales_order.nomor', 'like', "%{$search}%")
                    ->orWhere('customer.nama', 'like', "%{$search}%");
            });
        }

        $dataPenjualan = $query->orderBy('sales_order.tanggal', 'desc')->get();

        // Hitung total penjualan, total dibayar, dan sisa pembayaran
        $totalPenjualan = $dataPenjualan->sum('total');
        $totalDibayar = $dataPenjualan->sum('total_bayar');
        $sisaPembayaran = $totalPenjualan - $totalDibayar;

        $filters = [
            'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
            'tanggal_akhir' => $tanggalAkhir->format('Y-m-d'),
            'customer_id' => $customerId,
            'status_pembayaran' => $statusPembayaran,
            'search' => $search
        ];

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.laporan_penjualan.pdf', [
            'dataPenjualan' => $dataPenjualan,
            'filters' => $filters,
            'totalPenjualan' => $totalPenjualan,
            'totalDibayar' => $totalDibayar,
            'sisaPembayaran' => $sisaPembayaran
        ]);

        // Format tanggal untuk nama file
        $fileDate = now()->format('Ymd_His');
        $fileName = "laporan_penjualan_{$fileDate}.pdf";

        return $pdf->download($fileName);
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

        return $pdf->download($fileName);
    }
}
