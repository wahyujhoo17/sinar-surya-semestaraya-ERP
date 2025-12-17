<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExport;
use App\Imports\CustomerImport;

class CustomerController extends Controller
{
    public function __construct()
    {
        // Apply permission middleware to controller methods
        $this->middleware('permission:pelanggan.view')->only(['index', 'show', 'getById']);
        $this->middleware('permission:pelanggan.create')->only(['create', 'store', 'ajaxStore']);
        $this->middleware('permission:pelanggan.edit')->only(['edit', 'update', 'ajaxUpdate']);
        $this->middleware('permission:pelanggan.delete')->only(['destroy', 'bulkDestroy']);
        $this->middleware('permission:pelanggan.export')->only(['export']);
        $this->middleware('permission:pelanggan.import')->only(['import']);
    }

    /**
     * Check if current user can access all customers (admin/manager_penjualan)
     */
    private function canAccessAllCustomers()
    {
        return Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager_penjualan');
    }

    public function index(Request $request)
    {
        // Base query dengan role-based access control
        if ($this->canAccessAllCustomers()) {
            $query = Customer::query();
        } else {
            $query = Customer::where('sales_id', Auth::id());
        }

        // Get active users for sales_id dropdown
        $salesUsers = \App\Models\User::where('is_active', true)->orderBy('name')->get();

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
            'visibleColumns', // Pass for initial state if needed
            'salesUsers'
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

        // Untuk sales, otomatis set sales_id ke ID mereka sendiri
        if (!$this->canAccessAllCustomers()) {
            $validated['sales_id'] = Auth::id();
            $validated['sales_name'] = Auth::user()->name;
        }

        $customer = Customer::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pelanggan <strong>' . $customer->nama . '</strong> berhasil ditambahkan.',
            'customer' => $customer
        ]);
    }

    public function show(Customer $pelanggan)
    {
        // Role-based access control untuk show
        if (!$this->canAccessAllCustomers()) {
            if ($pelanggan->sales_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke customer ini.');
            }
        }

        $breadcrumbs = [
            'Pelanggan' => route('master.pelanggan.index')
        ];
        $currentPage = 'Detail Pelanggan';
        $salesUsers = \App\Models\User::where('is_active', true)->orderBy('name')->get();
        return view('master-data.pelanggan.show', [
            'customer' => $pelanggan,
            'breadcrumbs' => $breadcrumbs,
            'currentPage' => $currentPage,
            'salesUsers' => $salesUsers
        ]);
    }

    public function edit(Customer $pelanggan)
    {
        // Role-based access control untuk edit
        if (!$this->canAccessAllCustomers()) {
            if ($pelanggan->sales_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke customer ini.');
            }
        }

        $breadcrumbs = [
            'Pelanggan' => route('master.pelanggan.index')
        ];
        $currentPage = 'Edit Pelanggan';
        $salesUsers = \App\Models\User::where('is_active', true)->orderBy('name')->get();
        return view('master-data.pelanggan.edit', [
            'customer' => $pelanggan,
            'breadcrumbs' => $breadcrumbs,
            'currentPage' => $currentPage,
            'salesUsers' => $salesUsers
        ]);
    }

    public function update(Request $request, Customer $pelanggan)
    {
        // Role-based access control untuk update
        if (!$this->canAccessAllCustomers()) {
            if ($pelanggan->sales_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengubah customer ini.'
                ], 403);
            }
        }

        $validated = $request->validate($this->validationRules($pelanggan->id));
        $validated['is_active'] = $request->boolean('is_active');

        // Untuk sales, pastikan sales_id tidak berubah
        if (!$this->canAccessAllCustomers()) {
            $validated['sales_id'] = Auth::id();
            $validated['sales_name'] = Auth::user()->name;
        }

        $pelanggan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pelanggan <strong>' . $pelanggan->nama . '</strong> berhasil diperbarui.',
            'customer' => $pelanggan
        ]);
    }

    public function destroy(Customer $pelanggan)
    {
        // Role-based access control untuk destroy
        if (!$this->canAccessAllCustomers()) {
            if ($pelanggan->sales_id !== Auth::id()) {
                return redirect()->route('master.pelanggan.index')
                    ->with('error', 'Anda tidak memiliki akses untuk menghapus customer ini.');
            }
        }

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
            // Role-based access control untuk bulk destroy
            if ($this->canAccessAllCustomers()) {
                $query = Customer::whereIn('id', $ids);
            } else {
                $query = Customer::whereIn('id', $ids)->where('sales_id', Auth::id());
            }

            $count = $query->count();
            $query->delete();

            return redirect()->route('master.pelanggan.index')
                ->with('success', '<strong>' . $count . '</strong> pelanggan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('master.pelanggan.index')
                ->with('error', 'Gagal menghapus pelanggan. Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getById(Customer $pelanggan)
    {
        // Role-based access control untuk getById
        if (!$this->canAccessAllCustomers()) {
            if ($pelanggan->sales_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke customer ini.'
                ], 403);
            }
        }

        $salesUsers = \App\Models\User::where('is_active', true)->orderBy('name')->get();
        return response()->json([
            'success' => true,
            'customer' => $pelanggan,
            'salesUsers' => $salesUsers
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

    public function getSalesUsers()
    {
        $salesUsers = \App\Models\User::where('is_active', true)->orderBy('name')->get();
        return response()->json(['success' => true, 'salesUsers' => $salesUsers]);
    }

    public function getCustomerGroups()
    {
        $groups = Customer::whereNotNull('group')
            ->where('group', '!=', '')
            ->distinct()
            ->pluck('group')
            ->sort()
            ->values();

        return response()->json(['success' => true, 'groups' => $groups]);
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
            'nama' => 'nullable|max:255',
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
            'sales_id' => 'nullable|exists:users,id',
            'alamat' => 'nullable|max:255',
            'alamat_pengiriman' => 'nullable|max:255',
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
