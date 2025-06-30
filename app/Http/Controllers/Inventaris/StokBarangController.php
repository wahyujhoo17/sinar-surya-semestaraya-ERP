<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Gudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StokBarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:stok_barang.view')->only(['index', 'show']);
        $this->middleware('permission:stok_barang.create')->only(['create', 'store']);
        $this->middleware('permission:stok_barang.edit')->only(['edit', 'update']);
        $this->middleware('permission:stok_barang.delete')->only(['destroy', 'bulkDestroy']);
    }
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['name' => 'Inventaris', 'url' => '#'],
            ['name' => 'Stok Barang per Gudang', 'url' => route('inventaris.stok.index')]
        ];
        $currentPage = 'Stok Barang per Gudang';

        $gudangs = Gudang::with(['stok.produk.satuan'])
            ->orderBy('nama')
            ->get();

        // Log the data to help diagnose issues
        $dataCheck = [];
        foreach ($gudangs as $gudang) {
            $dataCheck[$gudang->nama] = [
                'id' => $gudang->id,
                'stok_count' => $gudang->stok->count(),
                'has_stok' => $gudang->stok->isNotEmpty(),
                'first_item' => $gudang->stok->first() ? [
                    'id' => $gudang->stok->first()->id,
                    'jumlah' => $gudang->stok->first()->jumlah,
                    'has_produk' => $gudang->stok->first()->produk ? true : false,
                ] : null
            ];
        }
        Log::info('Stok data check', $dataCheck);

        if ($request->ajax()) {
            return response()->json([
                'tableHtml' => view('inventaris.stok._gudang_list', compact('gudangs'))->render(),
            ]);
        }

        return view('inventaris.stok_barang.index', compact('gudangs', 'breadcrumbs', 'currentPage'));
    }
}
