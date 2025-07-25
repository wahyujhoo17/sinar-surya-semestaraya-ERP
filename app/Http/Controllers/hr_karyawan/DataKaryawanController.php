<?php

namespace App\Http\Controllers\hr_karyawan;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Department;
use App\Models\Jabatan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KaryawanExport;
use App\Exports\KaryawanTemplateExport;
use App\Imports\KaryawanImport;

class DataKaryawanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:karyawan.view')->only(['index', 'show']);
        $this->middleware('permission:karyawan.create')->only(['create', 'store']);
        $this->middleware('permission:karyawan.edit')->only(['edit', 'update']);
        $this->middleware('permission:karyawan.delete')->only(['destroy', 'bulkDestroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Base query dengan eager loading untuk department dan jabatan
        $query = Karyawan::with(['department', 'jabatan', 'user.roles'])->latest();

        // Pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nip', 'like', '%' . $search . '%')
                    ->orWhere('nama_lengkap', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhereHas('department', function ($q) use ($search) {
                        $q->where('nama', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('jabatan', function ($q) use ($search) {
                        $q->where('nama', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter berdasarkan departemen
        if ($request->has('department_id') && !empty($request->department_id)) {
            $query->where('department_id', $request->department_id);
        }

        // Filter berdasarkan status (single value dari dropdown)
        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal masuk
        if ($request->has('tanggal_masuk') && !empty($request->tanggal_masuk)) {
            $query->whereDate('tanggal_masuk', $request->tanggal_masuk);
        }

        // Pengurutan
        $sortField = $request->input('sort', 'nama_lengkap');
        $sortDirection = $request->input('direction', 'asc');

        // Validasi field yang diizinkan untuk sort
        $allowedSortFields = ['nama_lengkap', 'nip', 'department_id', 'jabatan_id', 'status', 'tanggal_masuk', 'created_at'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('nama_lengkap', 'asc');
        }

        // Semua departemen untuk filter dropdown
        $departments = Department::orderBy('nama')->get();

        // Pagination
        $perPage = $request->input('per_page', 10);
        // $perPage = 1;
        $karyawan = $query->paginate($perPage);

        // Handle AJAX request
        if ($request->ajax()) {
            $view = view('hr_karyawan.data_karyawan._table_body', compact('karyawan'))->render();
            $pagination = $karyawan->links('vendor.pagination.tailwind-custom')->render();

            return response()->json([
                'success' => true,
                'html' => $view,
                'pagination' => $pagination,
                'total' => $karyawan->total(),
                'first_item' => $karyawan->firstItem(),
                'last_item' => $karyawan->lastItem(),
            ]);
        }

        // Response untuk request normal
        return view('hr_karyawan.data_karyawan.index', [
            'karyawan' => $karyawan,
            'departments' => $departments,
            'currentPage' => 'Data Karyawan',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'HR', 'url' => '#'],
                ['name' => 'Data Karyawan']
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        $jabatans = Jabatan::all();
        $roles = Role::all();
        return view('hr_karyawan.data_karyawan.create', compact('departments', 'jabatans', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:karyawan,nip|max:20',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'required|email|unique:karyawan,email|unique:users,email',
            'department_id' => 'required|exists:department,id',
            'jabatan_id' => 'required|exists:jabatan,id',
            'gaji_pokok' => 'required|numeric|min:0',
            'tanggal_masuk' => 'required|date',
            'status' => 'required|in:aktif,nonaktif,cuti,keluar',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'role_id' => 'required|exists:roles,id', // User role
        ]);

        $data = $request->except('foto'); // Exclude foto initially

        // Buat user baru untuk karyawan
        $user = User::create([
            'name' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make('password'), // Password default
            'is_active' => true,
        ]);
        $data['user_id'] = $user->id;

        // Assign role to user
        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => $request->role_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('karyawan_fotos', 'public');
            $data['foto'] = $path;
        }

        try {
            Karyawan::create($data);

            // Grab the role name for the message
            $roleName = Role::find($request->role_id)->nama;

            return redirect()->route('hr.karyawan.index')->with('success', "Data Karyawan berhasil ditambahkan. Akun user telah dibuat dengan role '$roleName'.");
        } catch (\Exception $e) {
            Log::error('Error storing karyawan: ' . $e->getMessage()); // Log the error
            return redirect()->back()->with('error', 'Gagal menambahkan data karyawan. Silakan coba lagi.')->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Karyawan $karyawan)
    {
        // Eager load relationships
        $karyawan->load(['department', 'jabatan', 'user.roles', 'absensi', 'cuti']);
        return view('hr_karyawan.data_karyawan.show', compact('karyawan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan)
    {
        $departments = Department::all();
        $jabatans = Jabatan::all();
        $roles = Role::all();

        // Get the employee's current role id if it exists
        $userRoleId = null;
        if ($karyawan->user && $karyawan->user->roles->isNotEmpty()) {
            $userRoleId = $karyawan->user->roles->first()->id;
        }

        return view('hr_karyawan.data_karyawan.edit', compact('karyawan', 'departments', 'jabatans', 'roles', 'userRoleId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nip' => 'required|max:20|unique:karyawan,nip,' . $karyawan->id,
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'required|email|unique:karyawan,email,' . $karyawan->id . '|unique:users,email,' . $karyawan->user_id,
            'department_id' => 'required|exists:department,id',
            'jabatan_id' => 'required|exists:jabatan,id',
            'gaji_pokok' => 'required|numeric|min:0',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date|after_or_equal:tanggal_masuk',
            'status' => 'required|in:aktif,nonaktif,cuti,keluar',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'role_id' => 'required|exists:roles,id', // User role
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($karyawan->foto) {
                $oldPath = $karyawan->foto;
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('foto')->store('karyawan_fotos', 'public');
            $data['foto'] = $path;
        }

        try {
            $karyawan->update($data);

            // Update user role if user exists
            if ($karyawan->user) {
                // Delete existing roles
                DB::table('user_roles')->where('user_id', $karyawan->user_id)->delete();

                // Assign new role
                DB::table('user_roles')->insert([
                    'user_id' => $karyawan->user_id,
                    'role_id' => $request->role_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Grab the role name for the message
            $roleName = Role::find($request->role_id)->nama;

            return redirect()->route('hr.karyawan.index')->with('success', "Data Karyawan berhasil diperbarui dengan role '$roleName'.");
        } catch (\Exception $e) {
            Log::error('Error updating karyawan: ' . $e->getMessage()); // Log the error
            return redirect()->back()->with('error', 'Gagal memperbarui data karyawan. Silakan coba lagi.')->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan)
    {
        try {
            // Delete photo if exists
            if ($karyawan->foto) {
                $oldPath = $karyawan->foto;
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $karyawan->delete();
            return redirect()->route('hr.karyawan.index')->with('success', 'Data Karyawan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting karyawan: ' . $e->getMessage()); // Log the error
            // Handle potential foreign key constraint errors, etc.
            if (str_contains($e->getMessage(), 'constraint violation')) {
                return redirect()->route('hr.karyawan.index')->with('error', 'Gagal menghapus karyawan karena masih memiliki data terkait (misal: absensi, cuti).');
            }
            return redirect()->route('hr.karyawan.index')->with('error', 'Gagal menghapus data karyawan. Silakan coba lagi.');
        }
    }

    /**
     * Remove multiple resources from storage.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|numeric|exists:karyawan,id',
        ]);

        $count = 0;
        $errors = [];

        foreach ($request->ids as $id) {
            try {
                $karyawan = Karyawan::findOrFail($id);

                // Delete foto if exists
                if ($karyawan->foto) {
                    $oldPath = $karyawan->foto;
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                $karyawan->delete();
                $count++;
            } catch (\Exception $e) {
                Log::error('Error deleting karyawan id ' . $id . ': ' . $e->getMessage());
                $errors[] = "ID: {$id} - " . $e->getMessage();
            }
        }

        if (count($errors) > 0) {
            return redirect()->route('hr.karyawan.index')
                ->with('warning', "{$count} karyawan berhasil dihapus, namun beberapa gagal dihapus: " . implode(", ", $errors));
        }

        return redirect()->route('hr.karyawan.index')
            ->with('success', "{$count} karyawan berhasil dihapus");
    }

    /**
     * Export karyawan data to Excel
     */
    public function export(Request $request)
    {
        $filters = $request->only(['search', 'department_id', 'status', 'tanggal_masuk']);

        return Excel::download(
            new KaryawanExport($filters),
            'data-karyawan-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Download template for import
     */
    public function downloadTemplate()
    {
        return Excel::download(
            new KaryawanTemplateExport(),
            'template-import-karyawan.xlsx'
        );
    }

    /**
     * Import karyawan data from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ], [
            'file.required' => 'File wajib diupload',
            'file.mimes' => 'File harus berformat Excel (.xlsx, .xls) atau CSV (.csv)',
            'file.max' => 'Ukuran file maksimal 2MB'
        ]);

        try {
            $import = new KaryawanImport();
            Excel::import($import, $request->file('file'));

            $results = $import->getResults();

            if ($results['failed_count'] > 0) {
                $message = "Import selesai. {$results['success_count']} data berhasil diimport, {$results['failed_count']} data gagal.";

                if (!empty($results['errors'])) {
                    $errorMessages = implode('; ', array_slice($results['errors'], 0, 5));
                    if (count($results['errors']) > 5) {
                        $errorMessages .= ' dan ' . (count($results['errors']) - 5) . ' error lainnya.';
                    }
                    $message .= " Error: " . $errorMessages;
                }

                return redirect()->route('hr.karyawan.index')->with('warning', $message);
            }

            return redirect()->route('hr.karyawan.index')
                ->with('success', "Import berhasil! {$results['success_count']} data karyawan telah diimport.");
        } catch (\Exception $e) {
            Log::error('Error importing karyawan: ' . $e->getMessage());
            return redirect()->route('hr.karyawan.index')
                ->with('error', 'Terjadi kesalahan saat import data: ' . $e->getMessage());
        }
    }
}
