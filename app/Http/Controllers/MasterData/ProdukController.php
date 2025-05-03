<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Models\Satuan;
use App\Models\JenisProduk; // Add JenisProduk model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import Storage facade for file handling
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Import Log facade
use App\Exports\ProdukExport;
use App\Exports\TemplateExport;
use App\Imports\ProdukImport;
use Maatwebsite\Excel\Facades\Excel;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kategoris = KategoriProduk::orderBy('nama')->get();
        $satuans = Satuan::orderBy('nama')->get();
        $jenisProduks = JenisProduk::orderBy('nama')->get();

        // Decode visible columns from request, default to all true if not provided or invalid
        $visibleColumnsInput = $request->input('visible_columns', '{}');
        $visibleColumns = json_decode($visibleColumnsInput, true);
        // Define defaults matching the JS defaults
        $defaults = [
            'gambar' => true, 'produk' => true, 'kategori' => true, 'jenis' => true,
            'stok' => true, 'satuan' => true, 'harga_jual' => true, 'harga_beli' => false,
            'merek' => false, 'sku' => false, 'ukuran' => false, 'material' => false,
            'kualitas' => false, 'sub_kategori' => false, 'deskripsi' => false,
            'bahan' => false, 'status' => true,
        ];
        if (!is_array($visibleColumns)) {
            $visibleColumns = $defaults;
        } else {
            // Ensure all keys exist, merge stored with defaults
            $finalColumns = $defaults; // Start with defaults
            foreach ($defaults as $key => $defaultValue) {
                if (array_key_exists($key, $visibleColumns)) {
                    $finalColumns[$key] = $visibleColumns[$key]; // Use value from request if exists
                }
            }
            $visibleColumns = $finalColumns; // Assign the merged and validated array
        }

        $query = Produk::with(['kategori', 'satuan', 'jenis', 'stok'])->latest(); // Eager load relations

        // --- Apply Filters (Search, Kategori, Jenis, Harga) ---
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                    ->orWhere('kode', 'like', '%' . $searchTerm . '%')
                    ->orWhere('product_sku', 'like', '%' . $searchTerm . '%')
                    ->orWhere('merek', 'like', '%' . $searchTerm . '%')
                    ->orWhere('ukuran', 'like', '%' . $searchTerm . '%')
                    ->orWhere('type_material', 'like', '%' . $searchTerm . '%')
                    ->orWhere('kualitas', 'like', '%' . $searchTerm . '%');
            });
        }
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }
        if ($request->filled('jenis_id')) {
            $query->where('jenis_id', $request->jenis_id);
        }
        if ($request->filled('min_price')) {
            $query->where('harga_jual', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('harga_jual', '<=', $request->max_price);
        }
        // --- End Apply Filters ---

        // --- Apply Sorting ---
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        // Basic validation for sort field to prevent arbitrary column sorting
        $allowedSortFields = ['nama', 'kategori_id', 'harga_jual', 'created_at']; // Hapus total_stok dari allowed fields biasa

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else if ($sortField === 'total_stok') {
            // Metode 1: Menggunakan subquery untuk menghitung total stok
            $stokSubquery = DB::table('stok_produk')
                ->select('produk_id', DB::raw('COALESCE(SUM(jumlah), 0) as total_stok'))
                ->groupBy('produk_id');

            $query->leftJoinSub($stokSubquery, 'stok_totals', function ($join) {
                $join->on('produk.id', '=', 'stok_totals.produk_id');
            })
                ->select('produk.*', DB::raw('COALESCE(stok_totals.total_stok, 0) as total_stok_value'))
                ->orderBy('total_stok_value', $sortDirection);

            // Penting: Menambahkan kembali eager loading yang hilang karena kita mengubah select
            $query->with(['kategori', 'satuan', 'jenis', 'stok']);
        } else {
            // If invalid sort field, fallback to default
            $query->orderBy('created_at', 'desc');
        }
        // --- End Apply Sorting ---

        // --- Pagination ---
        $perPage = $request->input('per_page', 10); // Default 10 items per page
        $produks = $query->paginate($perPage)->withQueryString();
        // --- End Pagination ---

        // Handle AJAX request
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            try {
                // Pass visibleColumns to the partial view
                $tableHtml = view('master-data.produk._table_body', compact('produks', 'visibleColumns'))->render();
                $paginationHtml = view('vendor.pagination.tailwind-custom', ['paginator' => $produks])->render();

                return response()->json([
                    'table_html' => $tableHtml,
                    'pagination_html' => $paginationHtml,
                    'total_results' => $produks->total(),
                    'first_item' => $produks->firstItem(),
                    'last_item' => $produks->lastItem(),
                    'success' => true,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error rendering table: ' . $e->getMessage(),
                ], 500);
            }
        }

        // Normal HTTP request
        $breadcrumbs = [];
        $currentPage = 'Produk';
        // Pass sort, direction, and visible columns to the main view for initial Alpine state
        return view('master-data.produk.index', compact(
            'produks', 'breadcrumbs', 'currentPage', 'kategoris', 'satuans', 'jenisProduks',
            'sortField', 'sortDirection', 'visibleColumns' // Pass for initial state
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Sekarang kita redirect ke halaman index dengan parameter untuk membuka modal
        return redirect()->route('master.produk.index', ['open_modal' => 'create']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate($this->validationRules());

        // Set default value for harga_beli if not provided
        $validatedData['harga_beli'] = $request->input('harga_beli', 0);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('produk_gambar', 'public');
            $validatedData['gambar'] = $path;
        }

        // Pastikan status boolean (default: aktif)
        $validatedData['is_active'] = $request->boolean('is_active', true);

        $produk = Produk::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Produk <strong>' . $produk->nama . '</strong> berhasil ditambahkan.',
            'product' => $produk
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        // Load relasi jika belum
        $produk->load(['kategori', 'satuan', 'jenis']);
        $jenisProduks = JenisProduk::orderBy('nama')->get(); // Add this line

        $breadcrumbs = [
            'Produk' => route('master.produk.index')
        ];
        $currentPage = 'Detail Produk';
        return view('master-data.produk.show', compact('produk', 'breadcrumbs', 'currentPage', 'jenisProduks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        $breadcrumbs = [
            'Produk' => route('master.produk.index')
        ];
        $currentPage = 'Edit Produk';

        // Add jenis_produk to dropdown data
        $kategoris = KategoriProduk::where('is_active', true)->orderBy('nama')->get();
        $satuans = Satuan::where('is_active', true)->orderBy('nama')->get();
        $jenisProduks = JenisProduk::orderBy('nama')->get(); // Add this line

        return view('master-data.produk.edit', compact('produk', 'breadcrumbs', 'currentPage', 'kategoris', 'satuans', 'jenisProduks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $validatedData = $request->validate($this->validationRules($produk->id));

        // Set default value for harga_beli if not provided
        $validatedData['harga_beli'] = $request->input('harga_beli', 0);

        if ($request->hasFile('gambar')) {
            if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $path = $request->file('gambar')->store('produk_gambar', 'public');
            $validatedData['gambar'] = $path;
        }

        // Pastikan status boolean (default: aktif)
        $validatedData['is_active'] = $request->boolean('is_active', true);

        $produk->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Produk <strong>' . $produk->nama . '</strong> berhasil diperbarui.',
            'product' => $produk
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        $nama = $produk->nama;

        try {
            // Hapus gambar jika ada
            if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                Storage::disk('public')->delete($produk->gambar);
            }

            $produk->delete();

            return redirect()->route('master.produk.index')
                ->with('success', 'Produk <strong>' . $nama . '</strong> berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('master.produk.index')
                ->with('error', 'Gagal menghapus produk. Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove multiple resources from storage.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        // Validasi array
        if (!is_array($ids)) {
            $ids = [];
        }

        // Filter nilai kosong atau non-numeric
        $ids = array_filter($ids, function ($id) {
            return is_numeric($id);
        });

        if (empty($ids)) {
            return redirect()->route('master.produk.index')
                ->with('warning', 'Tidak ada produk yang dipilih untuk dihapus.');
        }

        try {
            // Ambil semua produk yang akan dihapus
            $produks = Produk::whereIn('id', $ids)->get();
            $count = $produks->count();

            if ($count === 0) {
                return redirect()->route('master.produk.index')
                    ->with('warning', 'Produk yang dipilih tidak ditemukan.');
            }

            DB::beginTransaction();

            // Hapus gambar terkait untuk semua produk
            foreach ($produks as $produk) {
                if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                    Storage::disk('public')->delete($produk->gambar);
                }
            }

            // Hapus produk dari database
            Produk::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()->route('master.produk.index')
                ->with('success', '<strong>' . $count . '</strong> produk berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('master.produk.index')
                ->with('error', 'Gagal menghapus produk. Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function ajaxStore(Request $request)
    {
        $validatedData = $request->validate([
            'kode' => 'required|unique:produk,kode|max:50',
            'nama' => 'required|max:255',
            'product_sku' => 'nullable|max:100',
            'kategori_id' => 'required|exists:kategori_produk,id',
            'jenis_id' => 'nullable|exists:jenis_produk,id',
            'ukuran' => 'nullable|max:50',
            'satuan_id' => 'required|exists:satuan,id',
            'merek' => 'nullable|max:100',
            'sub_kategori' => 'nullable|max:100',
            'type_material' => 'nullable|max:100',
            'kualitas' => 'nullable|max:100',
            'deskripsi' => 'nullable|string',
            'harga_jual' => 'required|numeric|min:0',
            'stok_minimum' => 'nullable|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'required|boolean',
        ]);

        // Handle file upload - perbaiki proses upload
        if ($request->hasFile('gambar')) {
            try {
                $file = $request->file('gambar');


                $path = $file->store('produk_gambar', 'public');
                $validatedData['gambar'] = $path;
            } catch (\Exception $e) {

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupload gambar: ' . $e->getMessage(),
                    'errors' => ['gambar' => 'Gagal mengupload gambar']
                ], 422);
            }
        } else {
        }

        // Buat produk baru
        $produk = Produk::create($validatedData);

        // Return respons JSON dengan informasi lengkap
        return response()->json([
            'success' => true,
            'product' => $produk,
            'message' => 'Produk <strong>' . $produk->nama . '</strong> berhasil ditambahkan.',
            'image_path' => $produk->gambar ?? null,
            'image_url' => $produk->gambar ? Storage::url($produk->gambar) : null
        ]);
    }

    public function ajaxUpdate(Request $request, Produk $produk)
    {
        $validatedData = $request->validate([
            'kode' => 'required|unique:produk,kode,' . $produk->id . '|max:50',
            'nama' => 'required|max:255',
            'product_sku' => 'nullable|max:100',
            'kategori_id' => 'required|exists:kategori_produk,id',
            'jenis_id' => 'nullable|exists:jenis_produk,id',
            'ukuran' => 'nullable|max:50',
            'satuan_id' => 'required|exists:satuan,id',
            'merek' => 'nullable|max:100',
            'sub_kategori' => 'nullable|max:100',
            'type_material' => 'nullable|max:100',
            'kualitas' => 'nullable|max:100',
            'deskripsi' => 'nullable|string',
            'harga_jual' => 'required|numeric|min:0',
            'stok_minimum' => 'nullable|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'required|boolean',
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                Storage::disk('public')->delete($produk->gambar);
            }
            // Simpan gambar baru
            $path = $request->file('gambar')->store('produk_gambar', 'public');
            $validatedData['gambar'] = $path;
        }

        // Update produk
        $produk->update($validatedData);

        // Return respons JSON
        return response()->json([
            'success' => true,
            'product' => $produk,
            'message' => 'Produk <strong>' . $produk->nama . '</strong> berhasil diperbarui.'
        ]);
    }

    public function getById(Produk $produk)
    {
        // Load relasi yang diperlukan
        $produk->load(['kategori', 'satuan']);

        // Tambahkan URL gambar jika ada
        $produk->gambar_url = $produk->gambar ? Storage::url($produk->gambar) : null;

        return response()->json([
            'success' => true,
            'product' => $produk
        ]);
    }

    public function export()
    {
        return Excel::download(new ProdukExport, 'produk-' . date('Y-m-d') . '.xlsx');
    }

    public function import(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            ini_set('memory_limit', '512M'); // atau '1G' jika perlu
            set_time_limit(300); // jika belum ada
            DB::beginTransaction();
            Excel::import(new ProdukImport, $request->file('file'));
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data produk berhasil diimport!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal import data: ' . $e->getMessage()
            ], 422);
        }
    }


    public function downloadTemplate()
    {
        return Excel::download(new TemplateExport, fileName: 'template-produk.xlsx');
    }
    public function generateCode()
    {
        $prefix = 'PRD';
        $lastProduct = Produk::orderBy('created_at', 'desc')->first();

        if ($lastProduct) {
            $lastNumber = intval(substr($lastProduct->kode, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $code = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        return response()->json([
            'success' => true,
            'code' => $code
        ]);
    }

    protected function validationRules($id = null)
    {
        return [
            'kode' => 'required|unique:produk,kode,' . $id . '|max:50',
            'nama' => 'required|max:255',
            'product_sku' => 'nullable|max:100',
            'kategori_id' => 'required|exists:kategori_produk,id',
            'jenis_id' => 'nullable|exists:jenis_produk,id',
            'ukuran' => 'nullable|max:50',
            'satuan_id' => 'required|exists:satuan,id',
            'merek' => 'nullable|max:100',
            'sub_kategori' => 'nullable|max:100',
            'type_material' => 'nullable|max:100',
            'kualitas' => 'nullable|max:100',
            'deskripsi' => 'nullable|string',
            'harga_beli' => 'nullable|numeric|min:0', // Add this
            'harga_jual' => 'required|numeric|min:0',
            'stok_minimum' => 'nullable|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'sometimes|boolean',
        ];
    }
}