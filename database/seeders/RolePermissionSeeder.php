<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat roles
        $roles = [
            ['nama' => 'Administrator', 'kode' => 'admin', 'deskripsi' => 'Akses penuh ke seluruh sistem'],
            ['nama' => 'Penjualan', 'kode' => 'sales', 'deskripsi' => 'Mengelola penjualan dan pelanggan'],
            ['nama' => 'Gudang', 'kode' => 'warehouse', 'deskripsi' => 'Mengelola stok dan inventaris'],
            ['nama' => 'Produksi', 'kode' => 'production', 'deskripsi' => 'Mengelola produksi dan perakitan'],
            ['nama' => 'Pembelian', 'kode' => 'purchasing', 'deskripsi' => 'Mengelola pembelian dan supplier'],
            ['nama' => 'Keuangan', 'kode' => 'finance', 'deskripsi' => 'Mengelola keuangan dan akuntansi'],
            ['nama' => 'HRD', 'kode' => 'hrd', 'deskripsi' => 'Mengelola karyawan dan penggajian'],
            ['nama' => 'Manager', 'kode' => 'manager', 'deskripsi' => 'Melihat laporan dan persetujuan']
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // Membuat permissions
        $modules = [
            'produk' => ['view', 'create', 'edit', 'delete'],
            'kategori_produk' => ['view', 'create', 'edit', 'delete'],
            'gudang' => ['view', 'create', 'edit', 'delete'],
            'customer' => ['view', 'create', 'edit', 'delete'],
            'supplier' => ['view', 'create', 'edit', 'delete'],
            'quotation' => ['view', 'create', 'edit', 'delete', 'approve'],
            'sales_order' => ['view', 'create', 'edit', 'delete', 'approve'],
            'purchase_request' => ['view', 'create', 'edit', 'delete', 'approve'],
            'purchase_order' => ['view', 'create', 'edit', 'delete', 'approve'],
            'delivery_order' => ['view', 'create', 'edit', 'delete'],
            'penerimaan_barang' => ['view', 'create', 'edit', 'delete'],
            'invoice' => ['view', 'create', 'edit', 'delete'],
            'pembayaran_piutang' => ['view', 'create', 'edit', 'delete'],
            'pembayaran_hutang' => ['view', 'create', 'edit', 'delete'],
            'retur_pembelian' => ['view', 'create', 'edit', 'delete'],
            'retur_penjualan' => ['view', 'create', 'edit', 'delete'],
            'transfer_barang' => ['view', 'create', 'edit', 'delete', 'approve'],
            'bill_of_material' => ['view', 'create', 'edit', 'delete'],
            'work_order' => ['view', 'create', 'edit', 'delete'],
            'karyawan' => ['view', 'create', 'edit', 'delete'],
            'department' => ['view', 'create', 'edit', 'delete'],
            'jabatan' => ['view', 'create', 'edit', 'delete'],
            'absensi' => ['view', 'create', 'edit', 'delete'],
            'cuti' => ['view', 'create', 'edit', 'delete', 'approve'],
            'penggajian' => ['view', 'create', 'edit', 'delete', 'approve'],
            'penyesuaian_stok' => ['view', 'create', 'edit', 'delete', 'approve'],
            'user' => ['view', 'create', 'edit', 'delete'],
            'role' => ['view', 'create', 'edit', 'delete'],
            'permission' => ['view', 'create', 'edit', 'delete'],
            'laporan' => ['view']
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::create([
                    'nama' => ucwords(str_replace('_', ' ', $module)) . ' - ' . ucfirst($action),
                    'kode' => $module . '.' . $action,
                    'modul' => $module,
                    'deskripsi' => 'Dapat ' . $action . ' ' . str_replace('_', ' ', $module)
                ]);
            }
        }

        // Assign semua permission ke admin
        $adminRole = Role::where('kode', 'admin')->first();
        $allPermissions = Permission::all();

        foreach ($allPermissions as $permission) {
            $adminRole->permissions()->attach($permission->id);
        }
    }
}
