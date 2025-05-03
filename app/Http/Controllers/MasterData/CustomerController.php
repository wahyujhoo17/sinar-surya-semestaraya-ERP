<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExport;
use App\Imports\CustomerImport;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        // Decode visible columns from request, default to all true if not provided or invalid
        $visibleColumnsInput = $request->input('visible_columns', '{}');
        $visibleColumns = json_decode($visibleColumnsInput, true);
        // Define defaults matching the JS defaults
        $defaults = [
            'kode' => true,
            'nama' => true,
            'tipe' => false,
            'company' => true,
            'telepon' => true,
            'email' => true,
            'alamat' => true, // Add alamat back
            /* 'kota' => false, */ /* 'provinsi' => false, */
            'sales_name' => false,
            'status' => true,
        ];
        if (!is_array($visibleColumns)) {
            $visibleColumns = $defaults;
        } else {
            // Ensure all keys exist, merge stored with defaults
            $visibleColumns = array_merge($defaults, $visibleColumns);
            // Explicitly remove old keys if they exist from old storage
            unset($visibleColumns['kota']);
            unset($visibleColumns['provinsi']);
            // Ensure alamat exists if migrating from old storage without it
            if (!array_key_exists('alamat', $visibleColumns)) {
                $visibleColumns['alamat'] = $defaults['alamat'];
            }
        }

        // Search & filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                    ->orWhere('kode', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('telepon', 'like', "%$search%")
                    ->orWhere('company', 'like', "%$search%");
            });
        }
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Sorting
        $sortField = $request->input('sort', 'nama'); // Default to nama
        $sortDirection = $request->input('direction', 'asc'); // Default to asc
        $allowedSortFields = ['nama', 'kode', 'company', 'created_at']; // Add other sortable fields if needed
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('nama', 'asc'); // Fallback sort
        }

        // Pagination
        $perPage = $request->input('per_page', 10);
        $customers = $query->paginate($perPage)->withQueryString();

        // AJAX response for table refresh
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            try {
                // Pass visibleColumns to the partial view
                $tableHtml = view('master-data.pelanggan._table_body', compact('customers', 'visibleColumns'))->render();
                $paginationHtml = view('vendor.pagination.tailwind-custom', ['paginator' => $customers])->render();

                return response()->json([
                    'table_html' => $tableHtml,
                    'pagination_html' => $paginationHtml,
                    'total_results' => $customers->total(),
                    'first_item' => $customers->firstItem(),
                    'last_item' => $customers->lastItem(),
                    'success' => true,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error rendering table: ' . $e->getMessage(),
                ], 500);
            }
        }

        $breadcrumbs = [];
        $currentPage = 'Pelanggan';
        // Pass sort, direction, and visible columns to the main view for initial Alpine state
        return view('master-data.pelanggan.index', compact(
            'customers',
            'breadcrumbs',
            'currentPage',
            'sortField',
            'sortDirection',
            'visibleColumns' // Pass for initial state if needed
        ));
    }

    public function create()
    {
        return redirect()->route('master.pelanggan.index', ['open_modal' => 'create']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());
        $validated['is_active'] = $request->has('is_active');
        $customer = Customer::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pelanggan <strong>' . $customer->nama . '</strong> berhasil ditambahkan.',
            'customer' => $customer
        ]);
    }

    public function show(Customer $pelanggan)
    {
        $breadcrumbs = [
            'Pelanggan' => route('master.pelanggan.index')
        ];
        $currentPage = 'Detail Pelanggan';
        return view('master-data.pelanggan.show', [
            'customer' => $pelanggan,
            'breadcrumbs' => $breadcrumbs,
            'currentPage' => $currentPage
        ]);
    }

    public function edit(Customer $pelanggan)
    {
        $breadcrumbs = [
            'Pelanggan' => route('master.pelanggan.index')
        ];
        $currentPage = 'Edit Pelanggan';
        return view('master-data.pelanggan.edit', [
            'customer' => $pelanggan,
            'breadcrumbs' => $breadcrumbs,
            'currentPage' => $currentPage
        ]);
    }

    public function update(Request $request, Customer $pelanggan)
    {
        $validated = $request->validate($this->validationRules($pelanggan->id));
        $validated['is_active'] = $request->boolean('is_active');
        $pelanggan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pelanggan <strong>' . $pelanggan->nama . '</strong> berhasil diperbarui.',
            'customer' => $pelanggan
        ]);
    }

    public function destroy(Customer $pelanggan)
    {
        $nama = $pelanggan->nama;
        try {
            $pelanggan->delete();
            return redirect()->route('master.pelanggan.index')
                ->with('success', 'Pelanggan <strong>' . $nama . '</strong> berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('master.pelanggan.index')
                ->with('error', 'Gagal menghapus pelanggan. Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids)) $ids = [];
        $ids = array_filter($ids, fn($id) => is_numeric($id));
        if (empty($ids)) {
            return redirect()->route('master.pelanggan.index')
                ->with('warning', 'Tidak ada pelanggan yang dipilih untuk dihapus.');
        }
        try {
            $count = Customer::whereIn('id', $ids)->count();
            Customer::whereIn('id', $ids)->delete();
            return redirect()->route('master.pelanggan.index')
                ->with('success', '<strong>' . $count . '</strong> pelanggan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('master.pelanggan.index')
                ->with('error', 'Gagal menghapus pelanggan. Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getById(Customer $pelanggan)
    {
        return response()->json([
            'success' => true,
            'customer' => $pelanggan
        ]);
    }

    public function generateCode()
    {
        $prefix = 'CUST';
        $last = Customer::orderByDesc('id')->first();
        $lastNumber = 0;
        if ($last && preg_match('/^CUST(\d+)$/', $last->kode, $matches)) {
            $lastNumber = (int)$matches[1];
        }
        $newNumber = $lastNumber + 1;
        $code = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        return response()->json(['success' => true, 'code' => $code]);
    }

    public function export()
    {
        return Excel::download(new CustomerExport, 'pelanggan-' . date('Y-m-d') . '.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            ini_set('memory_limit', '512M'); // atau '1G' jika perlu
            set_time_limit(300); // jika belum ada
            Excel::import(new CustomerImport, $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Data pelanggan berhasil diimport',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimport data: ' . $e->getMessage(),
            ], 422);
        }
    }
    protected function validationRules($id = null)
    {
        return [
            'kode' => 'required|unique:customer,kode,' . $id . '|max:50',
            'nama' => 'required|max:255',
            'tipe' => 'nullable|max:50',
            'jalan' => 'nullable|max:255',
            'kota' => 'nullable|max:100',
            'provinsi' => 'nullable|max:100',
            'kode_pos' => 'nullable|max:20',
            'negara' => 'nullable|max:100',
            'company' => 'nullable|max:100',
            'group' => 'nullable|max:100',
            'industri' => 'nullable|max:100',
            'sales_name' => 'nullable|max:100',
            'alamat' => 'nullable|max:255',
            'telepon' => 'nullable|max:50',
            'email' => 'nullable|email|max:100',
            'npwp' => 'nullable|max:50',
            'kontak_person' => 'nullable|max:100',
            'jabatan_kontak' => 'nullable|max:100',
            'no_hp_kontak' => 'nullable|max:50',
            'catatan' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ];
    }
}