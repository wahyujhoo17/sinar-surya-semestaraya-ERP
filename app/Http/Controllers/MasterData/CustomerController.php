<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\LogAktivitas;
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
        return Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager_penjualan') || Auth::user()->hasRole('admin_penjualan');
    }

    public function index(Request $request)
    {
        // Base query dengan role-based access control
        if ($this->canAccessAllCustomers()) {
            $query = Customer::query();
        } else {
            $query = Customer::where('sales_id', Auth::id());
        }

        // Get only users who are assigned as sales for at least one customer
        $salesUsers = \App\Models\User::whereIn('id', function($q) {
            $q->select('sales_id')->from('customer')->whereNotNull('sales_id')->distinct();
        })->where('is_active', true)->orderBy('name')->get();

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
        if ($request->filled('sales_id')) {
            if ($request->sales_id === 'none') {
                $query->whereNull('sales_id');
            } else {
                $query->where('sales_id', $request->sales_id);
            }
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

        $this->logActivity('create', $customer, $request->ip());

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

        // Riwayat aktivitas/perubahan pelanggan
        $logs = LogAktivitas::with('user')
            ->where('modul', 'customer')
            ->where('data_id', $pelanggan->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('master-data.pelanggan.show', [
            'customer' => $pelanggan,
            'breadcrumbs' => $breadcrumbs,
            'currentPage' => $currentPage,
            'salesUsers' => $salesUsers,
            'logs' => $logs,
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

        // Konversi is_active ke boolean — HANYA set jika memang berubah nilainya
        // agar tidak selalu masuk ke getChanges() dan mencemari log
        $newIsActive = $request->boolean('is_active');
        if ((bool) $pelanggan->is_active !== $newIsActive) {
            $validated['is_active'] = $newIsActive;
        } else {
            // Jaga nilai lama supaya field tidak dianggap dirty oleh Eloquent
            $validated['is_active'] = (bool) $pelanggan->is_active;
        }

        // Untuk sales, pastikan sales_id tidak berubah
        if (!$this->canAccessAllCustomers()) {
            $validated['sales_id'] = Auth::id();
            $validated['sales_name'] = Auth::user()->name;
        }

        // Simpan nilai lama sebelum update untuk keperluan log
        $original = $pelanggan->getOriginal();

        $pelanggan->update($validated);

        $this->logActivity('update', $pelanggan, $request->ip(), $original);

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
            $this->logActivity('delete', $pelanggan, request()->ip());

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
    /**
     * Mapping label field untuk ditampilkan pada log aktivitas.
     */
    protected function fieldLabels()
    {
        return [
            'kode' => 'Kode',
            'nama' => 'Nama',
            'tipe' => 'Tipe',
            'jalan' => 'Jalan',
            'kota' => 'Kota',
            'provinsi' => 'Provinsi',
            'kode_pos' => 'Kode Pos',
            'negara' => 'Negara',
            'company' => 'Perusahaan',
            'group' => 'Grup',
            'industri' => 'Industri',
            'sales_name' => 'Nama Sales',
            'sales_id' => 'Sales',
            'alamat' => 'Alamat',
            'alamat_pengiriman' => 'Alamat Pengiriman',
            'telepon' => 'Telepon',
            'email' => 'Email',
            'npwp' => 'NPWP',
            'kontak_person' => 'Kontak Person',
            'jabatan_kontak' => 'Jabatan Kontak',
            'no_hp_kontak' => 'No HP Kontak',
            'catatan' => 'Catatan',
            'is_active' => 'Status',
        ];
    }

    /**
     * Catat aktivitas perubahan data pelanggan ke log_aktivitas.
     *
     * @param string     $aktivitas  create|update|delete
     * @param Customer   $customer
     * @param string|null $ipAddress
     * @param array|null $original   Nilai field sebelum update
     */
    protected function logActivity($aktivitas, Customer $customer, $ipAddress = null, array $original = null)
    {
        $labels = $this->fieldLabels();
        $detail = [
            'nama' => $customer->nama,
            'kode' => $customer->kode,
        ];

        if ($aktivitas === 'update' && $original !== null) {
            $changes = [];
            // Ambil field yang berubah setelah update
            foreach ($customer->getChanges() as $field => $newValue) {
                if (in_array($field, ['updated_at'])) {
                    continue;
                }
                $oldValue = $original[$field] ?? null;

                // Normalisasi status aktif menjadi teks yang mudah dibaca
                if ($field === 'is_active') {
                    $oldBool = filter_var($oldValue, FILTER_VALIDATE_BOOLEAN);
                    $newBool = filter_var($newValue, FILTER_VALIDATE_BOOLEAN);
                    // Skip jika efektif tidak berubah
                    if ($oldBool === $newBool) {
                        continue;
                    }
                    $oldValue = $oldBool ? 'Aktif' : 'Nonaktif';
                    $newValue = $newBool ? 'Aktif' : 'Nonaktif';
                }

                // Ganti sales_id mentah dengan nama sales agar log lebih readable
                if ($field === 'sales_id') {
                    $oldUser = $oldValue ? \App\Models\User::find($oldValue) : null;
                    $newUser = $newValue ? \App\Models\User::find($newValue) : null;
                    $oldValue = $oldUser ? $oldUser->name : ($oldValue ? "#$oldValue" : null);
                    $newValue = $newUser ? $newUser->name : ($newValue ? "#$newValue" : null);
                    // Gunakan label "Sales" bukan id numerik
                    $changes[$field] = [
                        'label' => 'Sales',
                        'old'   => $oldValue,
                        'new'   => $newValue,
                    ];
                    continue;
                }

                // Lewati sales_name jika sales_id sudah tercatat (redundant)
                if ($field === 'sales_name' && isset($changes['sales_id'])) {
                    continue;
                }

                $changes[$field] = [
                    'label' => $labels[$field] ?? ucfirst(str_replace('_', ' ', $field)),
                    'old'   => $oldValue,
                    'new'   => $newValue,
                ];
            }
            $detail['changes'] = $changes;

            // Jangan catat log bila tidak ada perubahan nyata
            if (empty($changes)) {
                return;
            }
        }

        try {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => $aktivitas,
                'modul' => 'customer',
                'data_id' => $customer->id,
                'ip_address' => $ipAddress,
                'detail' => json_encode($detail, JSON_UNESCAPED_UNICODE),
            ]);
        } catch (\Exception $e) {
            Log::warning('Gagal mencatat log aktivitas pelanggan: ' . $e->getMessage());
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
