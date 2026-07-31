<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\StokProduk;
use App\Models\RiwayatStok;
use App\Models\Gudang;
use App\Models\TransferBarang;
use App\Models\TransferBarangDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanStokController extends Controller
{
    /**
     * Menampilkan halaman laporan stok
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tanggalAwal = now()->startOfMonth();
        $tanggalAkhir = now();
        $kategoriProduk = \App\Models\KategoriProduk::orderBy('nama')->get();
        $gudangs = Gudang::orderBy('nama')->get();

        // Breadcrumbs
        $breadcrumbs = [
            ['name' => 'Dashboard', 'link' => route('dashboard')],
            ['name' => 'Laporan', 'link' => '#'],
            ['name' => 'Laporan Stok', 'link' => '#'],
        ];

        $currentPage = 'laporan-stok';

        return view('laporan.laporan_stok.index', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'kategoriProduk',
            'gudangs',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Menampilkan detail riwayat stok untuk produk tertentu
     * 
     * @param int $produkId
     * @param int|null $gudangId
     * @return \Illuminate\View\View
     */
    public function detail(Request $request, $produkId, $gudangId = null)
    {
        // Cek produk tersedia
        $produk = Produk::with(['kategori', 'satuan'])->findOrFail($produkId);

        // Parameter filter
        $tanggalMulai = Carbon::parse($request->input('tanggal_mulai', now()->subMonths(1)))->startOfDay();
        $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()))->endOfDay();

        // Filter gudang dari request atau dari parameter
        $gudangId = $request->input('gudang_id', $gudangId);

        // Ambil daftar gudang untuk filter
        $gudangList = Gudang::orderBy('nama')->get();

        // Query dasar untuk riwayat stok
        $query = RiwayatStok::select(
            'riwayat_stok.*',
            'gudang.nama as nama_gudang',
            DB::raw('CASE 
                WHEN riwayat_stok.referensi_tipe = \'work_order\' THEN wo.nomor
                WHEN riwayat_stok.referensi_tipe = \'transfer_barang\' THEN tb.nomor
                WHEN riwayat_stok.referensi_tipe = \'penerimaan_barang\' THEN pb.nomor
                WHEN riwayat_stok.referensi_tipe = \'delivery_order\' THEN delivery_o.nomor
                WHEN riwayat_stok.referensi_tipe = \'penyesuaian_stok\' THEN ps.nomor
                ELSE NULL
                END as nomor_referensi')
        )
            ->where('riwayat_stok.produk_id', $produkId)
            ->join('gudang', 'riwayat_stok.gudang_id', '=', 'gudang.id')
            ->leftJoin('work_order as wo', function ($join) {
                $join->on('riwayat_stok.referensi_id', '=', 'wo.id')
                    ->whereColumn('riwayat_stok.referensi_tipe', DB::raw("'work_order'"));
            })
            ->leftJoin('transfer_barang as tb', function ($join) {
                $join->on('riwayat_stok.referensi_id', '=', 'tb.id')
                    ->whereColumn('riwayat_stok.referensi_tipe', DB::raw("'transfer_barang'"));
            })
            ->leftJoin('penerimaan_barang as pb', function ($join) {
                $join->on('riwayat_stok.referensi_id', '=', 'pb.id')
                    ->whereColumn('riwayat_stok.referensi_tipe', DB::raw("'penerimaan_barang'"));
            })
            ->leftJoin('delivery_order as delivery_o', function ($join) {
                $join->on('riwayat_stok.referensi_id', '=', 'delivery_o.id')
                    ->whereColumn('riwayat_stok.referensi_tipe', DB::raw("'delivery_order'"));
            })
            ->leftJoin('penyesuaian_stok as ps', function ($join) {
                $join->on('riwayat_stok.referensi_id', '=', 'ps.id')
                    ->whereColumn('riwayat_stok.referensi_tipe', DB::raw("'penyesuaian_stok'"));
            })
            ->whereBetween('riwayat_stok.created_at', [$tanggalMulai, $tanggalAkhir])
            ->orderBy('riwayat_stok.created_at', 'desc');

        // Filter berdasarkan gudang jika ada
        if ($gudangId) {
            $query->where('riwayat_stok.gudang_id', $gudangId);
        }

        // Ambil riwayat stok dengan pagination
        $riwayat = $query->paginate(20);

        // Ambil data stok saat ini
        $stokSaatIni = StokProduk::with('gudang')
            ->when($gudangId, function ($q) use ($gudangId) {
                return $q->where('gudang_id', $gudangId);
            })
            ->where('produk_id', $produkId)
            ->get();

        // Breadcrumbs
        $breadcrumbs = [
            ['name' => 'Dashboard', 'link' => route('dashboard')],
            ['name' => 'Laporan Stok', 'link' => route('laporan.stok.index')],
            ['name' => 'Detail Riwayat: ' . $produk->nama, 'link' => '#'],
        ];

        $currentPage = 'laporan-stok';

        return view('laporan.laporan_stok.detail', compact(
            'produk',
            'riwayat',
            'stokSaatIni',
            'gudangList',
            'gudangId',
            'tanggalMulai',
            'tanggalAkhir',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Ambil data laporan stok untuk tampilan tabel
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        $tanggalAwal = Carbon::parse($request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
        $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay();
        $kategoriId = $request->input('kategori_id');
        $gudangId = $request->input('gudang_id');
        $search = $request->input('search');
        $perPage = (int) $request->input('per_page', 25);
        $page = (int) $request->input('page', 1);

        // Core Query: StokProduk join Produk, Kategori, Satuan, Gudang
        $query = StokProduk::query()
            ->select(
                'stok_produk.id',
                'stok_produk.produk_id',
                'stok_produk.gudang_id',
                'stok_produk.jumlah as stok_akhir',
                'produk.nama as nama_barang',
                'produk.kode as kode_barang',
                'produk.stok_minimum',
                'produk.harga_jual',
                'kategori_produk.nama as kategori',
                'satuan.nama as satuan',
                'gudang.nama as gudang',
                'stok_produk.updated_at as tanggal_update'
            )
            ->join('produk', 'stok_produk.produk_id', '=', 'produk.id')
            ->leftJoin('kategori_produk', 'produk.kategori_id', '=', 'kategori_produk.id')
            ->leftJoin('satuan', 'produk.satuan_id', '=', 'satuan.id')
            ->join('gudang', 'stok_produk.gudang_id', '=', 'gudang.id');

        // Only include products with movements in the date range on that specific warehouse
        // This is identical to checking that there exists a riwayat_stok entry
        $query->whereExists(function ($q) use ($tanggalAwal, $tanggalAkhir) {
            $q->select(DB::raw(1))
                ->from('riwayat_stok')
                ->whereColumn('riwayat_stok.produk_id', 'stok_produk.produk_id')
                ->whereColumn('riwayat_stok.gudang_id', 'stok_produk.gudang_id')
                ->whereBetween('riwayat_stok.created_at', [$tanggalAwal, $tanggalAkhir])
                ->whereIn('riwayat_stok.jenis', ['masuk', 'keluar', 'transfer'])
                ->where(function ($sub) {
                    $sub->where('riwayat_stok.jumlah_perubahan', '>', 0)
                        ->orWhere('riwayat_stok.jumlah_perubahan', '<', 0);
                });
        });

        // Filter berdasarkan kategori produk
        if ($kategoriId) {
            $query->where('produk.kategori_id', $kategoriId);
        }

        // Filter berdasarkan gudang
        if ($gudangId) {
            $query->where('stok_produk.gudang_id', $gudangId);
        }

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('produk.nama', 'like', "%{$search}%")
                    ->orWhere('produk.kode', 'like', "%{$search}%");
            });
        }

        // PAGINATE ON THE DATABASE SIDE FIRST
        $totalItems = $query->count();
        $lastPage = (int) ceil($totalItems / $perPage);
        if ($lastPage < 1) $lastPage = 1;

        // Adjust page if it exceeds last page
        if ($page > $lastPage) {
            $page = $lastPage;
        }

        $offset = ($page - 1) * $perPage;
        $dataStok = $query->skip($offset)->take($perPage)->get();

        // Loop only the items for current page
        $paginatedResult = $dataStok->map(function ($item) use ($tanggalAwal, $tanggalAkhir) {
            // Get stok awal (last stock before start date)
            $stokAwalQuery = RiwayatStok::where('produk_id', $item->produk_id)
                ->where('gudang_id', $item->gudang_id)
                ->where('created_at', '<', $tanggalAwal)
                ->orderBy('created_at', 'desc')
                ->first();

            $stokAwal = $stokAwalQuery ? $stokAwalQuery->stok_akhir : 0;

            // Get total stock movements within the date range
            $movements = RiwayatStok::where('produk_id', $item->produk_id)
                ->where('gudang_id', $item->gudang_id)
                ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                ->orderBy('created_at', 'asc')
                ->get();

            $riwayatMasuk = 0;
            $riwayatKeluar = 0;
            $stokAkhir = $stokAwal;

            foreach ($movements as $movement) {
                if ($movement->jenis === 'masuk' || ($movement->jenis === 'transfer' && $movement->jumlah_perubahan > 0)) {
                    $riwayatMasuk += abs($movement->jumlah_perubahan);
                } elseif ($movement->jenis === 'keluar' || ($movement->jenis === 'transfer' && $movement->jumlah_perubahan < 0)) {
                    $riwayatKeluar += abs($movement->jumlah_perubahan);
                }

                // Update stok akhir based on the movement
                if ($movement->jenis === 'masuk' || $movement->jenis === 'transfer') {
                    $stokAkhir += $movement->jumlah_perubahan;
                } elseif ($movement->jenis === 'keluar') {
                    $stokAkhir -= abs($movement->jumlah_perubahan);
                }
            }

            // Calculate nilai barang using the actual ending stock
            $nilaiBarang = $stokAkhir * $item->harga_jual;

            return [
                'id' => $item->id,
                'produk_id' => $item->produk_id,
                'gudang_id' => $item->gudang_id,
                'kode_barang' => $item->kode_barang,
                'nama_barang' => $item->nama_barang,
                'kategori' => $item->kategori,
                'satuan' => $item->satuan,
                'gudang' => $item->gudang,
                'stok_awal' => $stokAwal,
                'barang_masuk' => $riwayatMasuk,
                'barang_keluar' => $riwayatKeluar,
                'stok_akhir' => $stokAkhir,
                'nilai_barang' => $nilaiBarang,
                'tanggal_update' => $item->tanggal_update,
                'is_below_minimum' => $stokAkhir < $item->stok_minimum
            ];
        });

        return response()->json([
            'data' => $paginatedResult,
            'total' => $totalItems,
            'current_page' => $page,
            'per_page' => $perPage,
            'last_page' => $lastPage,
            'filter' => [
                'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                'tanggal_akhir' => $tanggalAkhir->format('Y-m-d')
            ]
        ]);
    }

    /**
     * Export laporan stok ke Excel
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
        $fileName = "laporan_stok_{$fileDate}.xlsx";

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\LaporanStokExport($request->all()),
            $fileName
        );
    }

    /**
     * Export laporan stok ke PDF
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
}
