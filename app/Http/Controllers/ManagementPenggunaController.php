<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ManagementPenggunaController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'karyawan'])->latest();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhereHas('karyawan', function ($subQ) use ($search) {
                        $subQ->where('nama_lengkap', 'like', '%' . $search . '%')
                            ->orWhere('nip', 'like', '%' . $search . '%');
                    });
            });
        }

        // Role filter
        if ($request->filled('role_id')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role_id);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $status);
        }

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $allowedSortFields = ['name', 'email', 'created_at', 'last_login_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $users = $query->paginate($request->input('per_page', 10))->withQueryString();
        $roles = Role::orderBy('nama')->get();

        // AJAX request for table data
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'users' => $users,
                'table_body' => view('pengaturan.management_pengguna._table_body', ['users' => $users])->render(),
            ]);
        }

        $breadcrumbs = [
            ['label' => 'Pengaturan', 'url' => '#'],
            ['label' => 'Management Pengguna', 'url' => route('pengaturan.management-pengguna.index')],
        ];
        $currentPage = 'Management Pengguna';

        return view('pengaturan.management_pengguna.index', compact(
            'users',
            'roles',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::orderBy('nama')->get();

        $breadcrumbs = [
            ['label' => 'Pengaturan', 'url' => '#'],
            ['label' => 'Management Pengguna', 'url' => route('pengaturan.management-pengguna.index')],
            ['label' => 'Tambah Pengguna', 'url' => '#'],
        ];
        $currentPage = 'Tambah Pengguna';

        return view('pengaturan.management_pengguna.create', compact('roles', 'breadcrumbs', 'currentPage'));
    }

    /**
     * Display the specified user
     */
    public function show(User $management_pengguna)
    {
        $user = $management_pengguna;
        $user->load(['roles', 'karyawan']);

        // Format dates for display
        $user->created_at_formatted = $user->created_at->format('d/m/Y H:i');
        $user->updated_at_formatted = $user->updated_at->format('d/m/Y H:i');
        $user->last_login_at_formatted = $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : null;

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        }

        $breadcrumbs = [
            ['label' => 'Pengaturan', 'url' => '#'],
            ['label' => 'Management Pengguna', 'url' => route('pengaturan.management-pengguna.index')],
            ['label' => 'Detail Pengguna', 'url' => '#'],
        ];
        $currentPage = 'Detail Pengguna';

        return view('pengaturan.management_pengguna.show', compact('user', 'breadcrumbs', 'currentPage'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'exists:roles,id',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_active' => $request->boolean('is_active', true),
                'email_verified_at' => now(), // Auto verify for admin created users
            ]);

            // Assign roles
            $user->roles()->attach($request->role_ids);

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Membuat pengguna baru: ' . $user->name,
                'modul' => 'management_pengguna',
                'data_id' => $user->id,
                'ip_address' => request()->ip(),
                'detail' => 'Email: ' . $user->email . ', Roles: ' . implode(', ', Role::whereIn('id', $request->role_ids)->pluck('nama')->toArray())
            ]);

            DB::commit();

            return redirect()->route('pengaturan.management-pengguna.index')
                ->with('success', 'Pengguna berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing user
     */
    public function edit(User $management_pengguna)
    {
        $user = $management_pengguna;
        $user->load('roles');
        $roles = Role::orderBy('nama')->get();
        $userRoleIds = $user->roles->pluck('id')->toArray();

        $breadcrumbs = [
            ['label' => 'Pengaturan', 'url' => '#'],
            ['label' => 'Management Pengguna', 'url' => route('pengaturan.management-pengguna.index')],
            ['label' => 'Edit Pengguna', 'url' => '#'],
        ];
        $currentPage = 'Edit Pengguna';

        return view('pengaturan.management_pengguna.edit', compact(
            'user',
            'roles',
            'userRoleIds',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $management_pengguna)
    {
        $user = $management_pengguna;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'exists:roles,id',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $oldRoles = $user->roles->pluck('nama')->toArray();

            // Update user basic info
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'is_active' => $request->boolean('is_active', true),
            ];

            // Update password if provided
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // Update roles
            $user->roles()->sync($request->role_ids);
            $newRoles = Role::whereIn('id', $request->role_ids)->pluck('nama')->toArray();

            // Log activity
            $changes = [];
            if ($request->filled('password')) {
                $changes[] = 'Password diubah';
            }
            if ($oldRoles !== $newRoles) {
                $changes[] = 'Roles: ' . implode(', ', $oldRoles) . ' → ' . implode(', ', $newRoles);
            }

            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Mengubah data pengguna: ' . $user->name,
                'modul' => 'management_pengguna',
                'data_id' => $user->id,
                'ip_address' => request()->ip(),
                'detail' => empty($changes) ? 'Data profil diperbarui' : implode(', ', $changes)
            ]);

            DB::commit();

            return redirect()->route('pengaturan.management-pengguna.index')
                ->with('success', 'Data pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data pengguna: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $management_pengguna)
    {
        $user = $management_pengguna;
        // Prevent deleting current user
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Prevent deleting super admin (first user)
        if ($user->id === 1) {
            return redirect()->back()
                ->with('error', 'Super admin tidak dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            $userName = $user->name;
            $userEmail = $user->email;

            // Remove user roles
            $user->roles()->detach();

            // Log activity before deletion
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Menghapus pengguna: ' . $userName,
                'modul' => 'management_pengguna',
                'data_id' => $user->id,
                'ip_address' => request()->ip(),
                'detail' => 'Email: ' . $userEmail
            ]);

            // Delete user
            $user->delete();

            DB::commit();

            return redirect()->route('pengaturan.management-pengguna.index')
                ->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $management_pengguna)
    {
        $user = $management_pengguna;
        // Prevent deactivating current user
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.'
            ]);
        }

        // Prevent deactivating super admin
        if ($user->id === 1) {
            return response()->json([
                'success' => false,
                'message' => 'Super admin tidak dapat dinonaktifkan.'
            ]);
        }

        DB::beginTransaction();
        try {
            $newStatus = !$user->is_active;
            $user->update(['is_active' => $newStatus]);

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => ($newStatus ? 'Mengaktifkan' : 'Menonaktifkan') . ' pengguna: ' . $user->name,
                'modul' => 'management_pengguna',
                'data_id' => $user->id,
                'ip_address' => request()->ip(),
                'detail' => 'Status: ' . ($newStatus ? 'Aktif' : 'Nonaktif')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status pengguna berhasil diubah.',
                'new_status' => $newStatus
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status pengguna: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, $management_pengguna)
    {
        // Find user by ID since route model binding might be failing
        $user = User::find($management_pengguna);

        // Check if user exists
        if (!$user) {
            Log::error('Reset password: User not found', [
                'requested_user_id' => $management_pengguna,
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan dengan ID: ' . $management_pengguna
            ]);
        }

        Log::info('Reset password attempt', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'requested_by' => Auth::id(),
            'request_user_id' => $management_pengguna
        ]);

        // Prevent resetting own password through this method
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat mereset password Anda sendiri melalui cara ini.'
            ]);
        }

        // Prevent non-admin from resetting admin password
        $userIsAdmin = $user->roles->contains('kode', 'admin');
        $authUserIsAdmin = Auth::user()->roles->contains('kode', 'admin');

        if ($userIsAdmin && !$authUserIsAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mereset password Administrator.'
            ]);
        }

        $request->validate([
            'new_password' => 'required|min:8|max:255',
            'password_confirmation' => 'required|same:new_password',
            'force_change' => 'boolean'
        ]);

        DB::beginTransaction();
        try {
            $newPassword = $request->new_password;
            $forceChange = $request->boolean('force_change', true);

            // Debug: Log the old password hash for comparison
            $oldPasswordHash = $user->password;
            Log::info('Reset password debug', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'old_password_hash' => $oldPasswordHash,
                'new_password_length' => strlen($newPassword),
                'force_change' => $forceChange
            ]);
            $newPasswordHash = Hash::make($newPassword);

            // Debug: Log the new hash before update
            Log::info('Before password update', [
                'user_id' => $user->id,
                'old_password' => $oldPasswordHash,
                'new_password_hash' => $newPasswordHash,
                'new_password_plain' => $newPassword // Only for debugging, remove in production
            ]);

            // Try direct database update first
            $updateResult = DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'password' => $newPasswordHash,
                    'email_verified_at' => $forceChange ? null : now(),
                    'updated_at' => now()
                ]);

            Log::info('Database update result', [
                'user_id' => $user->id,
                'rows_affected' => $updateResult,
                'update_successful' => $updateResult > 0
            ]);

            // Verify the update by fetching fresh data
            $updatedUser = DB::table('users')->where('id', $user->id)->first();
            Log::info('Password verification after update', [
                'user_id' => $user->id,
                'old_hash' => $oldPasswordHash,
                'new_hash_from_db' => $updatedUser->password,
                'hash_actually_changed' => $oldPasswordHash !== $updatedUser->password,
                'password_verify_check' => Hash::check($newPassword, $updatedUser->password)
            ]);

            // If the password didn't change, try alternative update method
            if ($oldPasswordHash === $updatedUser->password) {
                Log::warning('Password not updated, trying alternative method');

                // Try using raw SQL
                $rawUpdateResult = DB::statement(
                    'UPDATE users SET password = ?, email_verified_at = ?, updated_at = ? WHERE id = ?',
                    [$newPasswordHash, $forceChange ? null : now(), now(), $user->id]
                );

                Log::info('Raw SQL update result', ['result' => $rawUpdateResult]);

                // Verify again
                $finalUser = DB::table('users')->where('id', $user->id)->first();
                Log::info('Final verification', [
                    'password_updated' => $oldPasswordHash !== $finalUser->password,
                    'final_hash' => $finalUser->password
                ]);
            }

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Reset password pengguna: ' . $user->name,
                'modul' => 'management_pengguna',
                'data_id' => $user->id,
                'ip_address' => request()->ip(),
                'detail' => 'Password direset' . ($forceChange ? ' (harus diganti saat login)' : '')
            ]);

            DB::commit();

            // Final verification after commit
            $finalVerification = DB::table('users')->where('id', $user->id)->first();
            $isPasswordUpdated = $oldPasswordHash !== $finalVerification->password;

            Log::info('Final password reset verification', [
                'user_id' => $user->id,
                'password_updated' => $isPasswordUpdated,
                'can_login_with_new_password' => Hash::check($newPassword, $finalVerification->password)
            ]);

            if (!$isPasswordUpdated) {
                Log::error('Password reset failed - password not updated in database');
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal reset password: Password tidak berubah di database'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Password pengguna {$user->name} berhasil direset." . ($forceChange ? ' Pengguna akan diminta mengganti password saat login berikutnya.' : ''),
                'debug' => [
                    'password_changed' => $isPasswordUpdated,
                    'new_password_works' => Hash::check($newPassword, $finalVerification->password)
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal reset password: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Bulk delete users
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:users,id'
        ]);

        $userIds = collect($request->ids)
            ->filter(function ($id) {
                // Filter out current user and super admin
                return $id != Auth::id() && $id != 1;
            })
            ->toArray();

        if (empty($userIds)) {
            return redirect()->back()
                ->with('error', 'Tidak ada pengguna yang dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            $users = User::whereIn('id', $userIds)->get();
            $deletedCount = 0;

            foreach ($users as $user) {
                // Remove roles
                $user->roles()->detach();

                // Log activity
                LogAktivitas::create([
                    'user_id' => Auth::id(),
                    'aktivitas' => 'Menghapus pengguna (bulk): ' . $user->name,
                    'modul' => 'management_pengguna',
                    'data_id' => $user->id,
                    'ip_address' => request()->ip(),
                    'detail' => 'Bulk delete'
                ]);

                $user->delete();
                $deletedCount++;
            }

            DB::commit();

            return redirect()->route('pengaturan.management-pengguna.index')
                ->with('success', "{$deletedCount} pengguna berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Get roles list for AJAX
     */
    public function getRoles(Request $request)
    {
        $query = Role::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('kode', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        $roles = $query->withCount('users')->latest()->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $roles
            ]);
        }

        return $roles;
    }

    /**
     * Store a new role
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:roles,kode',
            'deskripsi' => 'nullable|string|max:500'
        ], [
            'nama.required' => 'Nama role harus diisi',
            'nama.max' => 'Nama role maksimal 255 karakter',
            'kode.required' => 'Kode role harus diisi',
            'kode.max' => 'Kode role maksimal 50 karakter',
            'kode.unique' => 'Kode role sudah digunakan',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter'
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'nama' => $request->nama,
                'kode' => strtolower(str_replace(' ', '_', $request->kode)),
                'deskripsi' => $request->deskripsi
            ]);

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Membuat role baru: ' . $role->nama,
                'modul' => 'management_pengguna',
                'data_id' => $role->id,
                'ip_address' => request()->ip(),
                'detail' => 'Kode: ' . $role->kode . ', Deskripsi: ' . ($role->deskripsi ?? 'Tidak ada deskripsi')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil ditambahkan.',
                'role' => $role->loadCount('users')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan role: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update role
     */
    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:roles,kode,' . $role->id,
            'deskripsi' => 'nullable|string|max:500'
        ], [
            'nama.required' => 'Nama role harus diisi',
            'nama.max' => 'Nama role maksimal 255 karakter',
            'kode.required' => 'Kode role harus diisi',
            'kode.max' => 'Kode role maksimal 50 karakter',
            'kode.unique' => 'Kode role sudah digunakan',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter'
        ]);

        DB::beginTransaction();
        try {
            $oldData = $role->toArray();

            $role->update([
                'nama' => $request->nama,
                'kode' => strtolower(str_replace(' ', '_', $request->kode)),
                'deskripsi' => $request->deskripsi
            ]);

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Mengubah role: ' . $role->nama,
                'modul' => 'management_pengguna',
                'data_id' => $role->id,
                'ip_address' => request()->ip(),
                'detail' => 'Data lama: ' . json_encode($oldData) . ', Data baru: ' . json_encode($role->toArray())
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil diperbarui.',
                'role' => $role->loadCount('users')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui role: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete role
     */
    public function destroyRole(Role $role)
    {
        // Prevent deleting role if it has users
        if ($role->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak dapat dihapus karena masih digunakan oleh ' . $role->users()->count() . ' pengguna.'
            ]);
        }

        // Prevent deleting system roles
        $systemRoles = ['admin', 'user', 'super_admin'];
        if (in_array($role->kode, $systemRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Role sistem tidak dapat dihapus.'
            ]);
        }

        DB::beginTransaction();
        try {
            $roleName = $role->nama;

            // Remove permissions
            $role->permissions()->detach();

            // Delete role
            $role->delete();

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Menghapus role: ' . $roleName,
                'modul' => 'management_pengguna',
                'data_id' => null,
                'ip_address' => request()->ip(),
                'detail' => 'Role dihapus dari sistem'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus role: ' . $e->getMessage()
            ]);
        }
    }
}
