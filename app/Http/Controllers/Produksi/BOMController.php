<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\BillOfMaterial;
use App\Models\BillOfMaterialDetail;
use App\Models\Produk;
use App\Models\Satuan;
use App\Services\BOMCostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BOMController extends Controller
{
    protected $bomCostService;

    public function __construct(BOMCostService $bomCostService)
    {
        $this->bomCostService = $bomCostService;
        $this->middleware('permission:bill_of_material.view')->only(['index', 'show', 'getById', 'data', 'getComponentUnit', 'getCostBreakdown']);
        $this->middleware('permission:bill_of_material.create')->only(['create', 'store', 'addComponent']);
        $this->middleware('permission:bill_of_material.edit')->only(['edit', 'update', 'updateComponent']);
        $this->middleware('permission:bill_of_material.delete')->only(['destroy', 'deleteComponent']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $breadcrumbs = [
            ['name' => 'Produksi', 'url' => '#'],
            ['name' => 'Bill of Material', 'url' => route('produksi.bom.index')]
        ];
        $currentPage = 'Bill of Material';

        $search = $request->input('search');
        $status = $request->input('status');
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort', 'kode');
        $sortDirection = $request->input('direction', 'asc');

        $query = BillOfMaterial::with('produk');

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%")
                    ->orWhereHas('produk', function ($query) use ($search) {
                        $query->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        // Apply status filter
        if ($status !== null && $status !== '') {
            $isActive = $status == '1';
            $query->where('is_active', $isActive);
        }

        // Apply sorting
        $query->orderBy($sortField, $sortDirection);

        $boms = $query->paginate($perPage);

        // Stats for cards
        $totalBOM = BillOfMaterial::count();
        $activeBOM = BillOfMaterial::where('is_active', true)->count();
        $inactiveBOM = BillOfMaterial::where('is_active', false)->count();

        // Get all products for the dropdown in create/edit modal
        $produks = Produk::where('is_active', true)->orderBy('nama')->get();

        if ($request->ajax()) {
            return response()->json([
                'table_html' => view('produksi.BOM._table_body', compact('boms'))->render(),
                'pagination_html' => $boms->links('vendor.pagination.tailwind-custom')->toHtml(),
                'total_results' => $boms->total(),
                'first_item' => $boms->firstItem(),
                'last_item' => $boms->lastItem(),
            ]);
        }

        return view('produksi.BOM.index', compact(
            'boms',
            'totalBOM',
            'activeBOM',
            'inactiveBOM',
            'produks',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:bill_of_materials,kode',
            'produk_id' => 'required|exists:produk,id',
            'deskripsi' => 'nullable|string',
            'versi' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $bom = BillOfMaterial::create($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'BOM berhasil ditambahkan',
                'data' => $bom
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating BOM: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan BOM: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $breadcrumbs = [
            ['name' => 'Produksi', 'url' => '#'],
            ['name' => 'Bill of Material', 'url' => route('produksi.bom.index')],
            ['name' => 'Detail BOM', 'url' => '#']
        ];
        $currentPage = 'Detail Bill of Material';

        $bom = BillOfMaterial::with(['produk', 'details.komponen', 'details.satuan'])
            ->findOrFail($id);

        return view('produksi.BOM.show', compact('bom', 'breadcrumbs', 'currentPage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $breadcrumbs = [
            ['name' => 'Produksi', 'url' => '#'],
            ['name' => 'Bill of Material', 'url' => route('produksi.bom.index')],
            ['name' => 'Edit BOM', 'url' => '#']
        ];
        $currentPage = 'Edit Bill of Material';

        $bom = BillOfMaterial::with(['produk', 'details.komponen', 'details.satuan'])
            ->findOrFail($id);

        $produks = Produk::where('is_active', true)->orderBy('nama')->get();
        $komponens = Produk::where('is_active', true)
            ->where('id', '!=', $bom->produk_id)
            ->orderBy('nama')
            ->get();
        $satuans = Satuan::orderBy('nama')->get();

        return view('produksi.BOM.edit', compact(
            'bom',
            'produks',
            'komponens',
            'satuans',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            Log::info("BOM update called with ID: {$id}");
            Log::info("Request data: " . json_encode($request->all()));

            $bom = BillOfMaterial::findOrFail($id);
            Log::info("BOM found: {$bom->nama}");

            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|max:50|unique:bill_of_materials,kode,' . $id,
                'produk_id' => 'required|exists:produk,id',
                'deskripsi' => 'nullable|string',
                'versi' => 'nullable|string|max:20',
                'is_active' => 'boolean'
            ]);

            Log::info("Validation passed");

            DB::beginTransaction();

            $bom->update($validated);
            Log::info("BOM updated successfully");

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'BOM berhasil diperbarui',
                'data' => $bom
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in BOM update: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating BOM: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui BOM: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $bom = BillOfMaterial::findOrFail($id);

            // Check if BOM is being used in Work Orders
            if ($bom->workOrders->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'BOM tidak dapat dihapus karena sedang digunakan dalam Work Order'
                ], 400);
            }

            // Delete all BOM details first
            BillOfMaterialDetail::where('bom_id', $id)->delete();

            // Then delete the BOM
            $bom->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'BOM berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting BOM: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus BOM: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a component to a BOM
     */
    public function addComponent(Request $request, string $id)
    {
        $request->validate([
            'komponen_id' => 'required|exists:produk,id',
            'quantity' => 'required|numeric|min:0.01',
            'satuan_id' => 'required|exists:satuan,id',
            'catatan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Check if BOM exists
            $bom = BillOfMaterial::findOrFail($id);

            // Create new BOM component
            $component = BillOfMaterialDetail::create([
                'bom_id' => $id,
                'komponen_id' => $request->komponen_id,
                'quantity' => $request->quantity,
                'satuan_id' => $request->satuan_id,
                'catatan' => $request->catatan
            ]);

            // Update harga beli produk berdasarkan BOM setelah menambah komponen
            $this->bomCostService->updateProductCostFromBOM($bom->produk_id, $id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Komponen berhasil ditambahkan',
                'data' => $component
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding BOM component: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan komponen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a BOM component
     */
    public function updateComponent(Request $request, string $id)
    {
        $request->validate([
            'komponen_id' => 'required|exists:produk,id',
            'quantity' => 'required|numeric|min:0.01',
            'satuan_id' => 'required|exists:satuan,id',
            'catatan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Find the component
            $component = BillOfMaterialDetail::findOrFail($id);

            // Update component
            $component->update([
                'komponen_id' => $request->komponen_id,
                'quantity' => $request->quantity,
                'satuan_id' => $request->satuan_id,
                'catatan' => $request->catatan
            ]);

            // Update harga beli produk berdasarkan BOM setelah update komponen
            $bom = $component->bom;
            $this->bomCostService->updateProductCostFromBOM($bom->produk_id, $bom->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Komponen berhasil diperbarui',
                'data' => $component
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating BOM component: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui komponen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a BOM component
     */
    public function deleteComponent(string $id)
    {
        try {
            DB::beginTransaction();

            // Find the component
            $component = BillOfMaterialDetail::findOrFail($id);
            $bom = $component->bom;

            // Delete the component
            $component->delete();

            // Update harga beli produk berdasarkan BOM setelah hapus komponen
            $this->bomCostService->updateProductCostFromBOM($bom->produk_id, $bom->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Komponen berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting BOM component: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus komponen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the satuan_id (unit) for a component (product)
     */
    public function getComponentUnit(string $id)
    {
        try {
            $produk = Produk::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'satuan_id' => $produk->satuan_id,
                    'nama_satuan' => $produk->satuan ? $produk->satuan->nama : null
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting component unit info: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan informasi satuan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get BOM data for AJAX requests with filters and sorting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        // Get filter parameters from request
        $search = $request->input('search_query');
        $produkId = $request->input('produk_id');
        $status = $request->input('status');
        $sortField = $request->input('sort_field', 'kode');
        $sortDirection = $request->input('sort_direction', 'asc');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        // Start query builder
        $query = BillOfMaterial::with('produk');

        // Apply search filter if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('produk', function ($query) use ($search) {
                        $query->where('nama', 'like', "%{$search}%")
                            ->orWhere('kode', 'like', "%{$search}%");
                    });
            });
        }

        // Apply product filter if provided
        if ($produkId) {
            $query->where('produk_id', $produkId);
        }

        // Date range filter has been removed

        // Apply status filter if provided
        if ($status !== '' && $status !== null) {
            $query->where('is_active', $status == '1');
        }

        // Apply sorting
        switch ($sortField) {
            case 'produk':
                $query->join('produk', 'bill_of_materials.produk_id', '=', 'produk.id')
                    ->orderBy('produk.nama', $sortDirection)
                    ->select('bill_of_materials.*');
                break;
            case 'is_active':
                $query->orderBy('is_active', $sortDirection);
                break;
            default:
                $query->orderBy($sortField, $sortDirection);
                break;
        }

        // Get paginated results
        $boms = $query->paginate($perPage, ['*'], 'page', $page);

        // Check if there are any results
        $empty = $boms->isEmpty();

        // Prepare response
        $response = [
            'html' => view('produksi.BOM._table_body', compact('boms'))->render(),
            'pagination' => view('vendor.pagination.tailwind-custom', ['paginator' => $boms])->render(),
            'empty' => $empty,
            'from' => $boms->firstItem(),
            'to' => $boms->lastItem(),
            'total' => $boms->total(),
            'current_page' => $boms->currentPage(),
            'last_page' => $boms->lastPage(),
        ];

        return response()->json($response);
    }

    /**
     * Get cost breakdown untuk produk dengan BOM
     */
    public function getCostBreakdown(string $id)
    {
        try {
            // $id adalah BOM ID, kita perlu mendapatkan produk_id dari BOM
            $bom = BillOfMaterial::findOrFail($id);
            $breakdown = $this->bomCostService->getCostBreakdown($bom->produk_id);

            return response()->json([
                'success' => true,
                'data' => $breakdown
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan breakdown cost: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Manual update harga beli produk berdasarkan BOM
     */
    public function updateProductCost(Request $request, string $id)
    {
        try {
            Log::info("updateProductCost called with BOM ID: {$id}");

            $bom = BillOfMaterial::findOrFail($id);
            Log::info("BOM found: {$bom->nama}, Product ID: {$bom->produk_id}");

            $result = $this->bomCostService->updateProductCostFromBOM($bom->produk_id, $id);
            Log::info("Service result: " . json_encode($result));

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Harga beli produk berhasil diupdate berdasarkan BOM',
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate harga beli produk'
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error in updateProductCost: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate harga beli: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Batch update semua produk dengan BOM aktif
     */
    public function batchUpdateCosts()
    {
        try {
            $results = $this->bomCostService->batchUpdateAllBOMProducts();

            return response()->json([
                'success' => true,
                'message' => 'Batch update harga beli berhasil dilakukan',
                'data' => $results,
                'updated_count' => count($results)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan batch update: ' . $e->getMessage()
            ], 500);
        }
    }
}