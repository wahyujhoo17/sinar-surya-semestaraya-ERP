<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        // Decode visible columns from request, default to all true if not provided or invalid
        $visibleColumnsInput = $request->input('visible_columns', '{}');
        $visibleColumns = json_decode($visibleColumnsInput, true);
        if (!is_array($visibleColumns)) {
            $visibleColumns = [
                'kode' => true,
                'nama' => true,
                'deskripsi' => true,
                'produk_count' => true,
            ];
        } else {
            // Ensure all keys exist, default to true if missing
            $defaults = ['kode' => true, 'nama' => true, 'deskripsi' => true, 'produk_count' => true];
            $visibleColumns = array_merge($defaults, $visibleColumns);
        }

        $allowedSortFields = ['kode', 'nama', 'created_at']; // Add 'produk_count' if you make it sortable
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        $query = Satuan::query();

        // Conditionally add count if the column is visible and sortable
        if ($visibleColumns['produk_count'] ?? true) {
            $query->withCount('produk'); // Assuming 'produk' is the relationship name
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $satuans = $query->paginate($perPage)->withQueryString();

        if ($request->ajax()) {
            $pagination_html = $satuans->links('vendor.pagination.tailwind-custom')->render();
            return response()->json([
                'success' => true,
                // Pass visibleColumns to the partial view
                'table_html' => view('master-data.satuan._table_body', compact('satuans', 'visibleColumns'))->render(),
                'pagination_html' => $pagination_html,
                'total_results' => $satuans->total(),
                'first_item' => $satuans->firstItem(),
                'last_item' => $satuans->lastItem(),
            ]);
        }

        $breadcrumbs = [
            ['name' => 'Master Data', 'url' => '#'],
            ['name' => 'Satuan', 'url' => route('master.satuan.index')],
        ];
        $currentPage = 'Daftar Satuan';

        // Pass sort and visible columns to the main view for initial Alpine state
        return view('master-data.satuan.index', compact(
            'satuans',
            'breadcrumbs',
            'currentPage',
            'sortField',
            'sortDirection',
            'visibleColumns' // Pass for initial state if needed, though JS loads from localStorage
        ));
    }

    public function create()
    {
        return redirect()->route('master.satuan.index', ['open_modal' => 'create']);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode' => 'required|unique:satuan,kode|max:50',
            'nama' => 'required|max:255',
            'deskripsi' => 'nullable|string',
        ]);
        Satuan::create($validatedData);

        return response()->json(['success' => true, 'message' => 'Satuan berhasil ditambahkan.']);
    }

    public function getSatuan(Satuan $satuan)
    {
        return response()->json(['success' => true, 'satuan' => $satuan]);
    }

    public function update(Request $request, Satuan $satuan)
    {
        $validatedData = $request->validate([
            'kode' => 'required|unique:satuan,kode,' . $satuan->id . '|max:50',
            'nama' => 'required|max:255',
            'deskripsi' => 'nullable|string',
        ]);
        $satuan->update($validatedData);

        return response()->json(['success' => true, 'message' => 'Satuan berhasil diperbarui.']);
    }

    public function destroy(Satuan $satuan)
    {
        if ($satuan->produk()->exists()) {
            $msg = "Gagal menghapus. Satuan '{$satuan->nama}' masih digunakan oleh produk.";
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 409);
            }
            return back()->with('error', $msg);
        }
        $nama = $satuan->nama;
        $satuan->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => "Satuan '{$nama}' berhasil dihapus."]);
        }
        return redirect()->route('master.satuan.index')->with('success', "Satuan '{$nama}' berhasil dihapus.");
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:satuan,id',
        ]);

        $ids = $request->ids;
        $deletedCount = 0;

        $satuansToDelete = Satuan::whereIn('id', $ids)->whereDoesntHave('produk')->get();
        $satuansSkipped = Satuan::whereIn('id', $ids)->whereHas('produk')->pluck('nama');

        if ($satuansToDelete->isNotEmpty()) {
            try {
                DB::beginTransaction();
                $deletedCount = Satuan::whereIn('id', $satuansToDelete->pluck('id'))->delete();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('master.satuan.index')->with('error', 'Gagal menghapus satuan: ' . $e->getMessage());
            }
        }

        $skippedCount = $satuansSkipped->count();
        $message = '';
        if ($deletedCount > 0) {
            $message .= "<strong>{$deletedCount}</strong> satuan berhasil dihapus.";
        }
        if ($skippedCount > 0) {
            $message .= ($deletedCount > 0 ? ' ' : '') . "<strong>{$skippedCount}</strong> satuan ('" . $satuansSkipped->implode("', '") . "') tidak dihapus karena masih digunakan oleh produk.";
            return redirect()->route('master.satuan.index')->with('warning', $message);
        }
        if ($deletedCount == 0 && $skippedCount == 0) {
            return redirect()->route('master.satuan.index')->with('info', 'Tidak ada satuan yang dihapus.');
        }
        return redirect()->route('master.satuan.index')->with('success', $message);
    }
}
