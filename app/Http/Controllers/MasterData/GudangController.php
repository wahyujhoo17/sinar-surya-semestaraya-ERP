<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Gudang;
use App\Models\User; // Untuk dropdown penanggung jawab
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Gudang::withCount('stok')->latest();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                    ->orWhere('kode', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('sort')) {
            $sortField = $request->input('sort');
            $sortDirection = $request->input('direction', 'asc');
            $query->orderBy($sortField, $sortDirection);
        }

        $gudangs = $query->paginate($request->input('per_page', 10))->withQueryString();
        $users = User::where('is_active', true)->orderBy('name')->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'table_html' => view('master-data.gudang._table_body', compact('gudangs'))->render(),
                'pagination_html' => $gudangs->links('vendor.pagination.tailwind-custom')->render(),
                'total_results' => $gudangs->total(),
                'first_item' => $gudangs->firstItem(),
                'last_item' => $gudangs->lastItem(),
            ]);
        }

        return view('master-data.gudang.index', [
            'gudangs' => $gudangs,
            'users' => $users,
            'breadcrumbs' => [],
            'currentPage' => 'Gudang',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = [
            'Gudang' => route('master.gudang.index')
        ];
        $currentPage = 'Tambah Gudang';
        $users = User::where('is_active', true)->orderBy('name')->get(); // Ambil user aktif untuk P.Jawab

        return view('master-data.gudang.create', compact('breadcrumbs', 'currentPage', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode' => 'required|unique:gudang,kode|max:50',
            'nama' => 'required|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'penanggung_jawab' => 'nullable|exists:users,id', // Validasi user ID
            'jenis' => 'required|in:utama,cabang,produksi', // Validasi jenis gudang
            'is_active' => 'sometimes|boolean',
        ]);

        $validatedData['is_active'] = $request->has('is_active');
        if (empty($validatedData['penanggung_jawab'])) $validatedData['penanggung_jawab'] = null;

        $gudang = Gudang::create($validatedData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Gudang berhasil ditambahkan.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Gudang berhasil ditambahkan.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gudang $gudang)
    {
        $gudang->load('penanggungjawab');
        $breadcrumbs = [
            'Gudang' => route('master.gudang.index')
        ];
        $currentPage = 'Detail Gudang';
        return view('master-data.gudang.show', compact('gudang', 'breadcrumbs', 'currentPage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gudang $gudang)
    {
        $breadcrumbs = [
            'Gudang' => route('master.gudang.index')
        ];
        $currentPage = 'Edit Gudang';
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('master-data.gudang.edit', compact('gudang', 'breadcrumbs', 'currentPage', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gudang $gudang)
    {
        $validatedData = $request->validate([
            'kode' => 'required|unique:gudang,kode,' . $gudang->id . '|max:50',
            'nama' => 'required|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'penanggung_jawab' => 'nullable|exists:users,id',
            'jenis' => 'required|in:utama,cabang,produksi',
            'is_active' => 'sometimes|boolean',
        ]);

        $validatedData['is_active'] = $request->has('is_active');
        if (empty($validatedData['penanggung_jawab'])) $validatedData['penanggung_jawab'] = null;

        $gudang->update($validatedData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Gudang berhasil diperbarui.'
            ]);
        }

        // return redirect()->route('master.gudang.index')
        //     ->with('success', 'Gudang berhasil diperbarui.');

        return response()->json([
            'success' => true,
            'message' => 'Gudang berhasil diperbarui.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gudang $gudang)
    {
        try {
            // Cek relasi ke StokProduk sebelum menghapus
            if ($gudang->stok()->exists()) {
                return redirect()->route('master.gudang.index')
                    ->with('error', 'Gagal menghapus. Gudang ini masih memiliki data stok produk.');
            }
            // Tambahkan cek relasi lain jika ada (misal: WorkOrder, DeliveryOrder, PenerimaanBarang, RiwayatStok)

            $gudang->delete();
            return redirect()->route('master.gudang.index')
                ->with('success', 'Gudang berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            // Handle other potential foreign key issues
            return redirect()->route('master.gudang.index')
                ->with('error', 'Gagal menghapus gudang. Terjadi kesalahan database: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('master.gudang.index')
                ->with('error', 'Gagal menghapus gudang. Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get a specific Gudang resource.
     */
    public function getGudang(Gudang $gudang)
    {
        return response()->json(['success' => true, 'gudang' => $gudang]);
    }

    /**
     * Bulk delete Gudang resources.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:gudang,id',
        ]);

        try {
            DB::beginTransaction();

            $idsToDelete = Gudang::whereIn('id', $request->ids)
                ->whereDoesntHave('stok')
                ->pluck('id');

            $deletedCount = Gudang::whereIn('id', $idsToDelete)->delete();

            DB::commit();

            $message = $deletedCount > 0
                ? "$deletedCount gudang berhasil dihapus."
                : "Tidak ada gudang yang dapat dihapus karena masih memiliki stok.";

            return redirect()->route('master.gudang.index')
                ->with($deletedCount > 0 ? 'success' : 'warning', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('master.gudang.index')
                ->with('error', 'Gagal menghapus gudang: ' . $e->getMessage());
        }
    }
}
