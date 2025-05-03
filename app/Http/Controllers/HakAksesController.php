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
        $data = $request->input('permissions', []); // [role_id => [permission_id, ...], ...]
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
}
