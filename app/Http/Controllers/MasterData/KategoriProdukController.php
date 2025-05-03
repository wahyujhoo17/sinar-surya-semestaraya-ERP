<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Illuminate\Support\Facades\DB; // Import DB facade
use App\Traits\HasNotifications;
use App\Notifications\SystemNotification;

class KategoriProdukController extends Controller
{
    use HasNotifications;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Eager load product count and default sort
        $query = KategoriProduk::withCount('produk')->latest();

        // Search Logic
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                    ->orWhere('kode', 'like', '%' . $searchTerm . '%');
            });
        }

        // Sorting Logic
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        // Validate sort field to prevent arbitrary column sorting
        $allowedSortFields = ['nama', 'kode', 'created_at', 'produk_count']; // Add 'produk_count'
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc'); // Default sort
        }

        $perPage = $request->input('per_page', 10);
        $kategoris = $query->paginate($perPage)->withQueryString();

        // Handle AJAX requests for table updates
        if ($request->ajax()) {
            $tableHtml = view('master-data.kategori-produk._table_body', compact('kategoris'))->render();
            $paginationHtml = $kategoris->links('vendor.pagination.tailwind-custom')->render();

            return response()->json([
                'success' => true,
                'table_html' => $tableHtml,
                'pagination_html' => $paginationHtml,
                'total_results' => $kategoris->total(),
                'first_item' => $kategoris->firstItem(),
                'last_item' => $kategoris->lastItem(),
            ]);
        }

        $breadcrumbs = [];
        $currentPage = 'Kategori Produk';

        // Pass sort variables to the view for sort icons
        return view('master-data.kategori-produk.index', compact('kategoris', 'breadcrumbs', 'currentPage', 'sortField', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Redirect to index to open modal
        return redirect()->route('master.kategori-produk.index', ['open_modal' => 'create']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode' => 'required|unique:kategori_produk,kode|max:50',
            'nama' => 'required|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $kategori = KategoriProduk::create($validatedData);


        return response()->json([
            'success' => true, 
            'message' => 'Kategori Produk berhasil ditambahkan.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriProduk $kategoriProduk) // Renamed variable for clarity
    {
        // Load products associated with this category for the detail view
        $kategoriProduk->load(['produk' => function ($query) {
            $query->orderBy('nama')->paginate(10); // Example: Paginate products in detail view
        }]);

        $breadcrumbs = [
            'Kategori Produk' => route('master.kategori-produk.index')
        ];
        $currentPage = 'Detail Kategori Produk';
        // You might need to create a 'show.blade.php' view for categories
        // return view('master-data.kategori-produk.show', compact('kategoriProduk', 'breadcrumbs', 'currentPage'));
        // For now, redirect back or to index if show view doesn't exist
        return redirect()->route('master.kategori-produk.index')->with('info', 'Halaman detail kategori belum tersedia.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriProduk $kategoriProduk)
    {
        // This route might not be needed if editing is done via modal
        // If needed, return a view or redirect to index with modal trigger
        return redirect()->route('master.kategori-produk.index', ['open_modal' => 'edit', 'id' => $kategoriProduk->id]);
    }

    /**
     * Fetch category data for modal editing.
     */
    public function getKategori(KategoriProduk $kategoriProduk) // Renamed variable
    {
        return response()->json(['success' => true, 'kategori' => $kategoriProduk]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriProduk $kategoriProduk) // Renamed variable
    {
        try {
            $validatedData = $request->validate([
                'kode' => 'required|unique:kategori_produk,kode,' . $kategoriProduk->id . '|max:50',
                'nama' => 'required|max:255',
                'deskripsi' => 'nullable|string',
                'is_active' => 'required|boolean', // Use boolean validation
            ]);

            // No need to manually check 'has'
            $updateData = $validatedData;
            $updateData['is_active'] = $request->has('is_active');

            $kategoriProduk->update($validatedData);

            // Always return JSON for AJAX requests
            return response()->json(['success' => true, 'message' => 'Kategori Produk berhasil diperbarui.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Let Laravel handle the 422 JSON response for AJAX validation errors
            throw $e;
        } catch (\Exception $e) {
            // Catch other potential errors during update
            // Return a generic error JSON response
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui kategori: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriProduk $kategoriProduk) // Renamed variable
    {
        try {
            // Check if the category is used by any products
            if ($kategoriProduk->produk()->exists()) {
                $msg = "Gagal menghapus. Kategori '{$kategoriProduk->nama}' masih digunakan oleh produk.";
                if (request()->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 409); // 409 Conflict
                }
                return back()->with('error', $msg);
            }

            $nama = $kategoriProduk->nama;
            $kategoriProduk->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Kategori '{$nama}' berhasil dihapus."
                ]);
            }

            return redirect()->route('master.kategori-produk.index')
                ->with('success', "Kategori '{$nama}' berhasil dihapus.");
        } catch (\Exception $e) {
            $msg = 'Gagal menghapus kategori. Terjadi kesalahan: ' . $e->getMessage();
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 500);
            }
            return back()->with('error', $msg);
        }
    }

    /**
     * Remove multiple specified resources from storage.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:kategori_produk,id',
        ]);

        $ids = $request->ids;
        $deletedCount = 0;
        $errorMessages = [];
        $skippedCount = 0;

        // Find categories that are NOT used by products
        $categoriesToDelete = KategoriProduk::whereIn('id', $ids)
            ->whereDoesntHave('produk')
            ->get();

        // Find categories that ARE used by products (to report errors)
        $categoriesSkipped = KategoriProduk::whereIn('id', $ids)
            ->whereHas('produk')
            ->pluck('nama'); // Get names of skipped categories

        if ($categoriesToDelete->isNotEmpty()) {
            try {
                DB::beginTransaction();
                $deletedCount = KategoriProduk::whereIn('id', $categoriesToDelete->pluck('id'))->delete();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('master.kategori-produk.index')
                    ->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
            }
        }

        $skippedCount = $categoriesSkipped->count();

        // Build response message
        $message = '';
        if ($deletedCount > 0) {
            $message .= "<strong>{$deletedCount}</strong> kategori berhasil dihapus.";
        }
        if ($skippedCount > 0) {
            $message .= ($deletedCount > 0 ? ' ' : '') . "<strong>{$skippedCount}</strong> kategori ('" . $categoriesSkipped->implode("', '") . "') tidak dihapus karena masih digunakan oleh produk.";
            return redirect()->route('master.kategori-produk.index')->with('warning', $message); // Use warning if some were skipped
        }
        if ($deletedCount == 0 && $skippedCount == 0) {
            return redirect()->route('master.kategori-produk.index')->with('info', 'Tidak ada kategori yang dihapus.');
        }

        return redirect()->route('master.kategori-produk.index')->with('success', $message); // Use success if all selected were deleted
    }
}