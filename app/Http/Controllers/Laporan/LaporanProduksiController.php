<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanProduksiController extends Controller
{
    /**
     * Menampilkan halaman laporan produksi
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tanggalAwal = now()->startOfMonth();
        $tanggalAkhir = now();

        // Breadcrumbs
        $breadcrumbs = [
            ['name' => 'Dashboard', 'link' => route('dashboard')],
            ['name' => 'Laporan', 'link' => '#'],
            ['name' => 'Laporan Produksi', 'link' => '#'],
        ];

        $currentPage = 'laporan-produksi';

        return view('laporan.laporan_produksi.index', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Ambil data laporan produksi untuk tampilan tabel
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        try {
            $tanggalAwal = Carbon::parse($request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
            $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay();
            $search = $request->input('search');
            $perPage = $request->input('per_page', 25);
            $page = $request->input('page', 1);

            // Debug filter values
            \Log::info('Laporan Produksi Filter', [
                'tanggal_awal' => $tanggalAwal,
                'tanggal_akhir' => $tanggalAkhir,
                'search' => $search
            ]);

            // Query using work_order table instead of produksi
            $query = DB::table('work_order')
                ->select(
                    'work_order.id',
                    'work_order.nomor',
                    'work_order.tanggal',
                    'work_order.quantity as jumlah',
                    'work_order.status',
                    'work_order.catatan',
                    'produk.nama as produk_nama',
                    'produk.kode as produk_kode',
                    'users.name as nama_petugas'
                )
                ->join('produk', 'work_order.produk_id', '=', 'produk.id')
                ->leftJoin('users', 'work_order.user_id', '=', 'users.id')
                ->whereBetween('work_order.tanggal', [$tanggalAwal, $tanggalAkhir]);

            // Filter pencarian
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('work_order.nomor', 'like', "%{$search}%")
                        ->orWhere('produk.nama', 'like', "%{$search}%")
                        ->orWhere('produk.kode', 'like', "%{$search}%");
                });
            }

            // Get total count for pagination
            $totalItems = $query->count();

            // Skip dan ambil data untuk pagination
            $skip = ($page - 1) * $perPage;
            $dataProduksi = $query->orderBy('work_order.tanggal', 'desc')
                ->skip($skip)
                ->take($perPage)
                ->get();

            // Hitung total produksi
            $totalProduksi = $query->sum('work_order.quantity');

            // Calculate last page
            $lastPage = ceil($totalItems / $perPage);

            return response()->json([
                'data' => $dataProduksi,
                'total' => $totalItems,
                'current_page' => (int) $page,
                'per_page' => (int) $perPage,
                'last_page' => $lastPage,
                'totals' => [
                    'total_produksi' => $totalProduksi
                ],
                'filter' => [
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d')
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in Laporan Produksi getData: ' . $e->getMessage(), [
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
                    'total_produksi' => 0
                ]
            ], 500);
        }
    }

    /**
     * Export laporan produksi ke Excel
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
        $fileName = "laporan_produksi_{$fileDate}.xlsx";

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\LaporanProduksiExport($request->all()),
            $fileName
        );
    }

    /**
     * Export laporan produksi ke PDF
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $tanggalAwal = Carbon::parse($request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
        $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay();
        $search = $request->input('search');

        // Example query for production data using work_order
        $query = DB::table('work_order')
            ->select(
                'work_order.id',
                'work_order.nomor',
                'work_order.tanggal',
                'work_order.quantity as jumlah',
                'work_order.status',
                'work_order.catatan',
                'produk.nama as produk_nama',
                'produk.kode as produk_kode',
                'users.name as nama_petugas'
            )
            ->join('produk', 'work_order.produk_id', '=', 'produk.id')
            ->leftJoin('users', 'work_order.user_id', '=', 'users.id')
            ->whereBetween('work_order.tanggal', [$tanggalAwal, $tanggalAkhir]);

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('work_order.nomor', 'like', "%{$search}%")
                    ->orWhere('produk.nama', 'like', "%{$search}%")
                    ->orWhere('produk.kode', 'like', "%{$search}%");
            });
        }

        $dataProduksi = $query->orderBy('work_order.tanggal', 'desc')->get();

        // Hitung total produksi
        $totalProduksi = $dataProduksi->sum('jumlah');

        $filters = [
            'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
            'tanggal_akhir' => $tanggalAkhir->format('Y-m-d'),
            'search' => $search
        ];

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.laporan_produksi.pdf', [
            'dataProduksi' => $dataProduksi,
            'filters' => $filters,
            'totalProduksi' => $totalProduksi
        ]);

        // Format tanggal untuk nama file
        $fileDate = now()->format('Ymd_His');
        $fileName = "laporan_produksi_{$fileDate}.pdf";

        return $pdf->download($fileName);
    }

    /**
     * Menampilkan detail produksi
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function detail($id)
    {
        $produksi = DB::table('work_order')
            ->select(
                'work_order.*',
                'work_order.quantity as jumlah',
                'produk.nama as produk_nama',
                'produk.kode as produk_kode',
                'users.name as nama_petugas'
            )
            ->join('produk', 'work_order.produk_id', '=', 'produk.id')
            ->leftJoin('users', 'work_order.user_id', '=', 'users.id')
            ->where('work_order.id', $id)
            ->first();

        if (!$produksi) {
            abort(404, 'Data produksi tidak ditemukan');
        }

        // Breadcrumbs
        $breadcrumbs = [
            ['name' => 'Dashboard', 'link' => route('dashboard')],
            ['name' => 'Laporan', 'link' => '#'],
            ['name' => 'Laporan Produksi', 'link' => route('laporan.produksi.index')],
            ['name' => 'Detail Produksi #' . $produksi->nomor, 'link' => '#'],
        ];

        $currentPage = 'laporan-produksi-detail';

        return view('laporan.laporan_produksi.detail', compact(
            'produksi',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Generate PDF for a specific production
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailPdf($id)
    {
        $produksi = DB::table('work_order')
            ->select(
                'work_order.*',
                'work_order.quantity as jumlah',
                'produk.nama as produk_nama',
                'produk.kode as produk_kode',
                'users.name as nama_petugas'
            )
            ->join('produk', 'work_order.produk_id', '=', 'produk.id')
            ->leftJoin('users', 'work_order.user_id', '=', 'users.id')
            ->where('work_order.id', $id)
            ->first();

        if (!$produksi) {
            abort(404, 'Data produksi tidak ditemukan');
        }

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.laporan_produksi.detail_pdf', compact('produksi'));

        // Format for filename
        $fileName = "detail_produksi_{$produksi->nomor}_{$id}.pdf";

        return $pdf->download($fileName);
    }
}
