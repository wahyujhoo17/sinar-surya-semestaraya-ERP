<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
                ->whereBetween('purchase_order.tanggal', [$tanggalAwal, $tanggalAkhir]);

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

            // Skip dan ambil data untuk pagination
            $skip = ($page - 1) * $perPage;
            $dataPembelian = $query->orderBy('purchase_order.tanggal', 'desc')
                ->skip($skip)
                ->take($perPage)
                ->get();

            // Debug results
            \Log::info('Purchase data results', [
                'count' => $dataPembelian->count(),
                'first_item' => $dataPembelian->first(),
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            // Hitung total pembelian, total dibayar, dan sisa pembayaran
            $totalPembelian = $dataPembelian->sum('total');
            $totalDibayar = $dataPembelian->sum('total_bayar');
            $sisaPembayaran = $totalPembelian - $totalDibayar;            // Calculate last page
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
            ->whereBetween('purchase_order.tanggal', [$tanggalAwal, $tanggalAkhir]);

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
}
