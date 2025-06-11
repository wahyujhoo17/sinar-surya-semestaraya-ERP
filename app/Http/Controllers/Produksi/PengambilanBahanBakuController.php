<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\PengambilanBahanBaku;
use App\Models\PengambilanBahanBakuDetail;
use App\Models\WorkOrder;
use App\Models\StokProduk;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PengambilanBahanBakuController extends Controller
{
    /**
     * Menampilkan daftar pengambilan bahan baku
     */
    public function index(Request $request)
    {
        $query = PengambilanBahanBaku::with(['workOrder', 'workOrder.produk', 'gudang', 'creator']);

        $sort = $request->get('sort', 'tanggal');
        $direction = $request->get('direction', 'desc');

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhere('catatan', 'like', "%{$search}%")
                    ->orWhereHas('workOrder', function ($sq) use ($search) {
                        $sq->where('nomor', 'like', "%{$search}%");
                    })
                    ->orWhereHas('workOrder.produk', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%")
                            ->orWhere('kode', 'like', "%{$search}%");
                    })
                    ->orWhereHas('gudang', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        // Filter berdasarkan periode
        if ($request->filled('periode') && $request->periode !== 'all') {
            $periode = explode(' - ', $request->periode);
            if (count($periode) == 2) {
                $start = date('Y-m-d', strtotime($periode[0]));
                $end = date('Y-m-d', strtotime($periode[1]));
                $query->whereBetween('tanggal', [$start, $end]);
            }
        }

        // Sorting
        if (in_array($sort, ['nomor', 'tanggal', 'status'])) {
            $query->orderBy($sort, $direction);
        } else if ($sort === 'work_order') {
            $query->join('work_order', 'pengambilan_bahan_baku.work_order_id', '=', 'work_order.id')
                ->orderBy('work_order.nomor', $direction)
                ->select('pengambilan_bahan_baku.*');
        } else if ($sort === 'gudang') {
            $query->join('gudang', 'pengambilan_bahan_baku.gudang_id', '=', 'gudang.id')
                ->orderBy('gudang.nama', $direction)
                ->select('pengambilan_bahan_baku.*');
        } else {
            $query->orderBy('tanggal', 'desc');
        }

        $pengambilanList = $query->paginate(10);

        return view('produksi.pengambilan-bahan-baku.index', compact('pengambilanList'));
    }

    /**
     * Menampilkan detail pengambilan bahan baku
     */
    public function show($id)
    {
        $pengambilan = PengambilanBahanBaku::with([
            'workOrder',
            'workOrder.produk',
            'gudang',
            'detail',
            'detail.produk',
            'detail.satuan',
            'creator'
        ])->findOrFail($id);

        return view('produksi.pengambilan-bahan-baku.show', compact('pengambilan'));
    }

    /**
     * Export pengambilan bahan baku sebagai PDF
     */
    public function exportPdf($id)
    {
        $pengambilan = PengambilanBahanBaku::with([
            'workOrder',
            'workOrder.produk',
            'workOrder.satuan',
            'gudang',
            'detail',
            'detail.produk',
            'detail.satuan',
            'creator'
        ])->findOrFail($id);

        $pdf = \PDF::loadView('produksi.pengambilan-bahan-baku.pdf', compact('pengambilan'));

        return $pdf->download('Pengambilan_Bahan_Baku_' . $pengambilan->nomor . '.pdf');
    }

    /**
     * Check ketersediaan stok bahan baku
     */
    public function checkStok(Request $request)
    {
        $produkId = $request->produk_id;
        $gudangId = $request->gudang_id;
        $jumlah = $request->jumlah;

        $stokTersedia = StokProduk::where('produk_id', $produkId)
            ->where('gudang_id', $gudangId)
            ->sum('jumlah');

        $cukup = $stokTersedia >= $jumlah;

        return response()->json([
            'stok_tersedia' => $stokTersedia,
            'cukup' => $cukup,
            'kekurangan' => $cukup ? 0 : $jumlah - $stokTersedia
        ]);
    }
}
