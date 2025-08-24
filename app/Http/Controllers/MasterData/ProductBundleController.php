<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\ProductBundle;
use App\Models\ProductBundleItem;
use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Services\ProductBundleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductBundleController extends Controller
{
    protected $bundleService;

    public function __construct(ProductBundleService $bundleService)
    {
        $this->bundleService = $bundleService;
    }

    /**
     * Display a listing of product bundles
     */
    public function index(Request $request)
    {
        $query = ProductBundle::with(['kategori', 'items.produk']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by category
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $bundles = $query->orderBy('created_at', 'desc')->paginate(15);
        $kategoris = KategoriProduk::where('is_active', true)->orderBy('nama')->get();

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Master Data', 'url' => '#'],
            ['label' => 'Product Bundle', 'url' => null]
        ];
        $currentPage = 'Product Bundle';

        return view('master-data.product-bundle.index', compact('bundles', 'kategoris', 'breadcrumbs', 'currentPage'));
    }

    /**
     * Show the form for creating a new bundle
     */
    public function create()
    {
        $kategoris = KategoriProduk::where('is_active', true)->orderBy('nama')->get();
        $produk = Produk::where('is_active', true)->with(['kategori', 'satuan'])->orderBy('nama')->get();

        // Generate bundle code
        $lastBundle = ProductBundle::latest('id')->first();
        $lastNumber = $lastBundle ? intval(substr($lastBundle->kode, 4)) : 0;
        $kode = 'BDL-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Master Data', 'url' => '#'],
            ['label' => 'Product Bundle', 'url' => route('master.product-bundle.index')],
            ['label' => 'Tambah', 'url' => null]
        ];
        $currentPage = 'Tambah Product Bundle';

        return view('master-data.product-bundle.create', compact('kategoris', 'produk', 'kode', 'breadcrumbs', 'currentPage'));
    }

    /**
     * Store a newly created bundle
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode' => 'required|string|unique:product_bundles,kode',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_bundle' => 'required|numeric|min:0',
            'kategori_id' => 'nullable|exists:kategori_produk,id',
            'is_active' => 'boolean',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'nullable|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            // Handle image upload
            if ($request->hasFile('gambar')) {
                $validatedData['gambar'] = $request->file('gambar')->store('bundle_images', 'public');
            }

            // Calculate normal price
            $normalPrice = 0;
            foreach ($request->items as $item) {
                $produk = Produk::find($item['produk_id']);
                $harga = $item['harga_satuan'] ?? $produk->harga_jual;
                $normalPrice += $harga * $item['quantity'];
            }

            $validatedData['harga_normal'] = $normalPrice;
            $validatedData['diskon_persen'] = $normalPrice > 0 ?
                (($normalPrice - $validatedData['harga_bundle']) / $normalPrice) * 100 : 0;

            // Create bundle
            $bundle = ProductBundle::create($validatedData);

            // Create bundle items
            foreach ($request->items as $item) {
                ProductBundleItem::create([
                    'bundle_id' => $bundle->id,
                    'produk_id' => $item['produk_id'],
                    'quantity' => $item['quantity'],
                    'harga_satuan' => $item['harga_satuan'] ?? null
                ]);
            }

            DB::commit();

            return redirect()->route('master.product-bundle.index')->with('success', "Bundle '{$bundle->nama}' berhasil dibuat!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating product bundle: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat bundle: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified bundle
     */
    public function show(ProductBundle $productBundle)
    {
        $productBundle->load(['kategori', 'items.produk.satuan', 'items.produk.kategori', 'items.produk.stok']);
        $priceCalculation = $this->bundleService->getBundlePriceCalculation($productBundle);

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Master Data', 'url' => '#'],
            ['label' => 'Product Bundle', 'url' => route('master.product-bundle.index')],
            ['label' => $productBundle->nama, 'url' => null]
        ];
        $currentPage = 'Detail Product Bundle';

        return view('master-data.product-bundle.show', compact('productBundle', 'priceCalculation', 'breadcrumbs', 'currentPage'));
    }

    /**
     * Show the form for editing the specified bundle
     */
    public function edit(ProductBundle $productBundle)
    {
        $productBundle->load(['items.produk']);
        $kategoris = KategoriProduk::where('is_active', true)->orderBy('nama')->get();
        $produk = Produk::where('is_active', true)->with(['kategori', 'satuan'])->orderBy('nama')->get();

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Master Data', 'url' => '#'],
            ['label' => 'Product Bundle', 'url' => route('master.product-bundle.index')],
            ['label' => $productBundle->nama, 'url' => route('master.product-bundle.show', $productBundle)],
            ['label' => 'Edit', 'url' => null]
        ];
        $currentPage = 'Edit Product Bundle';

        return view('master-data.product-bundle.edit', compact('productBundle', 'kategoris', 'produk', 'breadcrumbs', 'currentPage'));
    }

    /**
     * Update the specified bundle
     */
    public function update(Request $request, ProductBundle $productBundle)
    {
        $validatedData = $request->validate([
            'kode' => 'required|string|unique:product_bundles,kode,' . $productBundle->id,
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_bundle' => 'required|numeric|min:0',
            'kategori_id' => 'nullable|exists:kategori_produk,id',
            'is_active' => 'boolean',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'nullable|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            // Handle image upload
            if ($request->hasFile('gambar')) {
                // Delete old image if exists
                if ($productBundle->gambar) {
                    Storage::disk('public')->delete($productBundle->gambar);
                }
                $validatedData['gambar'] = $request->file('gambar')->store('bundle_images', 'public');
            }

            // Calculate normal price
            $normalPrice = 0;
            foreach ($request->items as $item) {
                $produk = Produk::find($item['produk_id']);
                $harga = $item['harga_satuan'] ?? $produk->harga_jual;
                $normalPrice += $harga * $item['quantity'];
            }

            $validatedData['harga_normal'] = $normalPrice;
            $validatedData['diskon_persen'] = $normalPrice > 0 ?
                (($normalPrice - $validatedData['harga_bundle']) / $normalPrice) * 100 : 0;

            // Update bundle
            $productBundle->update($validatedData);

            // Delete existing items and recreate
            $productBundle->items()->delete();

            foreach ($request->items as $item) {
                ProductBundleItem::create([
                    'bundle_id' => $productBundle->id,
                    'produk_id' => $item['produk_id'],
                    'quantity' => $item['quantity'],
                    'harga_satuan' => $item['harga_satuan'] ?? null
                ]);
            }

            DB::commit();

            return redirect()->route('master.product-bundle.index')->with('success', "Bundle '{$productBundle->nama}' berhasil diupdate!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating product bundle: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate bundle: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified bundle
     */
    public function destroy(ProductBundle $productBundle)
    {
        try {
            // Check if bundle is used in any sales orders
            $isUsed = DB::table('sales_order_detail')
                ->where('bundle_id', $productBundle->id)
                ->exists();

            if ($isUsed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bundle tidak dapat dihapus karena sudah digunakan dalam sales order.'
                ], 400);
            }

            // Delete image if exists
            if ($productBundle->gambar) {
                Storage::disk('public')->delete($productBundle->gambar);
            }

            $productBundle->delete();

            return response()->json([
                'success' => true,
                'message' => "Bundle '{$productBundle->nama}' berhasil dihapus!"
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting product bundle: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus bundle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get bundle data for API/AJAX calls
     */
    public function getBundleData($id)
    {
        try {
            $bundle = ProductBundle::with(['items.produk.satuan'])->findOrFail($id);
            $priceCalculation = $this->bundleService->getBundlePriceCalculation($bundle);

            return response()->json([
                'success' => true,
                'bundle' => $bundle,
                'price_calculation' => $priceCalculation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bundle tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Check bundle stock availability
     */
    public function checkStock(Request $request, $id)
    {
        try {
            $quantity = $request->input('quantity', 1);
            $gudangId = $request->input('gudang_id');

            $stockValidation = $this->bundleService->validateBundleStock($id, $quantity, $gudangId);

            return response()->json([
                'success' => true,
                'stock_validation' => $stockValidation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking stock: ' . $e->getMessage()
            ], 500);
        }
    }
}
