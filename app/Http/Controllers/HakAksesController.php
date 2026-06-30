<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;


class HakAksesController extends Controller
{
    // Tampilkan halaman pengaturan hak akses
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        $breadcrumbs = [
            ['label' => 'Pengaturan', 'url' => '#'],
            ['label' => 'Hak Akses', 'url' => route('pengaturan.hak-akses.index')],
        ];
        $currentPage = 'Hak Akses';
        return view('pengaturan.hak-akses.index', compact('roles', 'permissions', 'breadcrumbs', 'currentPage'));
    }

    // Simpan perubahan hak akses
    public function update(Request $request)
    {
        $data = [];
        if ($request->has('permissions_json')) {
            $data = json_decode($request->input('permissions_json'), true) ?? [];
        } else {
            $data = $request->input('permissions', []); // fallback
        }
        
        $roleIds = Role::pluck('id')->toArray();
        $permissionIds = Permission::pluck('id')->toArray();

        DB::beginTransaction();
        try {
            foreach ($roleIds as $roleId) {
                $role = Role::find($roleId);
                if ($role) {
                    $ids = isset($data[$roleId]) ? array_intersect($data[$roleId], $permissionIds) : [];
                    $role->permissions()->sync($ids);
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Hak akses berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui hak akses: ' . $e->getMessage());
        }
    }

    // Toggle a single permission via AJAX for auto-save
    public function togglePermission(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
            'state' => 'required|boolean',
        ]);

        $role = Role::findOrFail($request->role_id);
        
        if ($request->state) {
            $role->permissions()->syncWithoutDetaching([$request->permission_id]);
        } else {
            $role->permissions()->detach($request->permission_id);
        }

        return response()->json(['status' => 'success', 'message' => 'Hak akses berhasil diperbarui otomatis.']);
    }

    // Menambahkan permission baru via AJAX
    public function storePermission(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:permissions,kode',
            'modul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $modul = strtolower(str_replace(' ', '_', $request->modul));
        $kode = strtolower(str_replace(' ', '_', $request->kode)); // Safe formatting

        $permission = Permission::create([
            'nama' => $request->nama,
            'kode' => $request->kode, // Use provided kode
            'modul' => $modul,
            'deskripsi' => $request->deskripsi
        ]);

        // Return the new permission so UI can update
        return response()->json([
            'status' => 'success',
            'message' => 'Permission baru berhasil ditambahkan.',
            'permission' => $permission
        ]);
    }
}
