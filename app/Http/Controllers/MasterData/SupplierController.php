<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Exports\SupplierExport;
use App\Imports\SupplierImport;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort', 'nama');
        $sortDirection = $request->input('direction', 'asc');

        // Decode visible columns from request, default to all true if not provided or invalid
        $visibleColumnsInput = $request->input('visible_columns', '{}');
        $visibleColumns = json_decode($visibleColumnsInput, true);
        if (!is_array($visibleColumns)) {
            $visibleColumns = [
                'kode' => true,
                'nama' => true,
                'telepon' => true,
                'email' => true,
                'no_hp' => true, // Add no_hp
                'type_produksi' => true,
                'status' => true,
            ];
        } else {
            // Ensure all keys exist, default to true if missing
            $defaults = ['kode' => true, 'nama' => true, 'telepon' => true, 'email' => true, 'no_hp' => true, 'type_produksi' => true, 'status' => true]; // Add no_hp
            $visibleColumns = array_merge($defaults, $visibleColumns);
        }

        $allowedSortFields = ['kode', 'nama', 'type_produksi'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'nama';
        }

        $query = Supplier::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('telepon', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%") // Add no_hp to search
                    ->orWhere('type_produksi', 'like', "%{$search}%");
            });
        }

        $query->orderBy($sortField, $sortDirection);

        $suppliers = $query->paginate($perPage)->withQueryString();

        if ($request->ajax()) {
            $paginationHtml = view('vendor.pagination.tailwind-custom', ['paginator' => $suppliers])->render();
            return response()->json([
                'success' => true,
                // Pass visibleColumns to the partial view
                'table_html' => view('master-data.supplier._table_body', compact('suppliers', 'visibleColumns'))->render(),
                'pagination_html' => $paginationHtml,
                'total_results' => $suppliers->total(),
                'first_item' => $suppliers->firstItem(),
                'last_item' => $suppliers->lastItem(),
            ]);
        }

        $breadcrumbs = [
            ['name' => 'Master Data', 'url' => '#'],
            ['name' => 'Supplier', 'url' => route('master.supplier.index')],
        ];
        $currentPage = 'Daftar Supplier';

        // Pass sort and visible columns to the main view for initial Alpine state
        return view('master-data.supplier.index', compact(
            'suppliers',
            'breadcrumbs',
            'currentPage',
            'sortField',
            'sortDirection',
            'visibleColumns' // Pass for initial state if needed
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = [
            'Supplier' => route('master.supplier.index')
        ];
        $currentPage = 'Tambah Supplier';

        return view('master-data.supplier.create', compact('breadcrumbs', 'currentPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode' => 'required|unique:supplier,kode|max:50',
            'nama' => 'required|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'nama_kontak' => 'nullable|string|max:255', // Tambah validasi nama_kontak
            'no_hp' => 'nullable|string|max:20', // Add validation for no_hp
            'type_produksi' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Ubah cara handle is_active
        $validatedData['is_active'] = $request->boolean('is_active');

        $supplier = Supplier::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil ditambahkan.',
            'supplier' => $supplier
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $breadcrumbs = [
            'Supplier' => route('master.supplier.index')
        ];
        $currentPage = 'Detail Supplier';
        return view('master-data.supplier.show', compact('supplier', 'breadcrumbs', 'currentPage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $breadcrumbs = [
            'Supplier' => route('master.supplier.index')
        ];
        $currentPage = 'Edit Supplier';

        return view('master-data.supplier.edit', compact('supplier', 'breadcrumbs', 'currentPage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validatedData = $request->validate([
            'kode' => 'required|unique:supplier,kode,' . $supplier->id . '|max:50',
            'nama' => 'required|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'nama_kontak' => 'nullable|string|max:255', // Tambah validasi nama_kontak
            'no_hp' => 'nullable|string|max:20', // Add validation for no_hp
            'type_produksi' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Ubah cara handle is_active
        $validatedData['is_active'] = $request->boolean('is_active');

        $supplier->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil diperbarui.',
            'supplier' => $supplier
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            // Cek relasi sebelum menghapus (contoh: PurchaseOrder, PenerimaanBarang, ReturPembelian)
            if ($supplier->purchaseOrders()->exists() || $supplier->penerimaanBarang()->exists()) { // Tambahkan cek relasi lain
                return redirect()->route('master.supplier.index')
                    ->with('error', 'Gagal menghapus. Supplier ini masih memiliki data transaksi terkait (PO, Penerimaan, dll).');
            }

            $supplier->delete();
            return redirect()->route('master.supplier.index')
                ->with('success', 'Supplier berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            // Handle other potential foreign key issues
            return redirect()->route('master.supplier.index')
                ->with('error', 'Gagal menghapus supplier. Terjadi kesalahan database: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('master.supplier.index')
                ->with('error', 'Gagal menghapus supplier. Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get supplier by ID for AJAX edit.
     */
    public function getById(Supplier $supplier)
    {
        return response()->json([
            'success' => true,
            'supplier' => $supplier
        ]);
    }

    /**
     * Bulk delete suppliers.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids)) $ids = [];
        $ids = array_filter($ids, function ($id) {
            return is_numeric($id);
        });

        if (empty($ids)) {
            return redirect()->route('master.supplier.index')
                ->with('warning', 'Tidak ada supplier yang dipilih untuk dihapus.');
        }

        try {
            $suppliers = Supplier::whereIn('id', $ids)->get();
            $count = $suppliers->count();

            // Cek relasi sebelum hapus
            foreach ($suppliers as $supplier) {
                if ($supplier->purchaseOrders()->exists() || $supplier->penerimaanBarang()->exists()) {
                    return redirect()->route('master.supplier.index')
                        ->with('error', 'Tidak bisa hapus supplier yang masih punya transaksi.');
                }
            }

            Supplier::whereIn('id', $ids)->delete();

            return redirect()->route('master.supplier.index')
                ->with('success', '<strong>' . $count . '</strong> supplier berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('master.supplier.index')
                ->with('error', 'Gagal menghapus supplier. Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export suppliers to Excel.
     */
    public function export()
    {
        return Excel::download(new SupplierExport, 'supplier.xlsx');
    }

    /**
     * Import suppliers from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        try {
            ini_set('memory_limit', '512M'); // atau '1G' jika perlu
            set_time_limit(300); // jika belum ada
            Excel::import(new SupplierImport, $request->file('file'));
            return response()->json([
                'success' => true,
                'message' => 'Data Supplier berhasil diimport!'
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    /**
     * Generate a new supplier code.
     */
    public function generateCode()
    {
        // Contoh format: SUP-0001, SUP-0002, dst
        $lastSupplier = Supplier::orderByDesc('id')->first();
        $lastNumber = 1;
        if ($lastSupplier && preg_match('/SUP-(\d+)/', $lastSupplier->kode, $matches)) {
            $lastNumber = intval($matches[1]) + 1;
        } elseif ($lastSupplier) {
            // Jika kode tidak sesuai format, tetap lanjutkan increment id
            $lastNumber = $lastSupplier->id + 1;
        }
        $newCode = 'SUP-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);

        return response()->json([
            'success' => true,
            'kode' => $newCode
        ]);
    }
}
