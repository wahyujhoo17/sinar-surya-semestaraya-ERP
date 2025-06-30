<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PurchaseRiwayatTransaksiPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Riwayat Transaksi Permissions
        $riwayatTransaksiPermissions = [
            [
                'kode' => 'riwayat_transaksi.view',
                'nama' => 'Riwayat Transaksi - View',
                'modul' => 'riwayat_transaksi',
                'deskripsi' => 'Dapat melihat riwayat transaksi pembelian'
            ],
            [
                'kode' => 'riwayat_transaksi.export',
                'nama' => 'Riwayat Transaksi - Export',
                'modul' => 'riwayat_transaksi',
                'deskripsi' => 'Dapat mengeksport data riwayat transaksi'
            ],
            [
                'kode' => 'riwayat_transaksi.filter',
                'nama' => 'Riwayat Transaksi - Filter',
                'modul' => 'riwayat_transaksi',
                'deskripsi' => 'Dapat menggunakan filter pada riwayat transaksi'
            ]
        ];

        foreach ($riwayatTransaksiPermissions as $permission) {
            Permission::firstOrCreate(
                ['kode' => $permission['kode']],
                $permission
            );
        }

        // Assign all permissions to Administrator role
        $adminRole = Role::where('nama', 'Administrator')->first();
        if ($adminRole) {
            $newPermissions = Permission::whereIn('kode', [
                'riwayat_transaksi.view',
                'riwayat_transaksi.export',
                'riwayat_transaksi.filter'
            ])->get();

            foreach ($newPermissions as $permission) {
                if (!$adminRole->permissions()->where('permission_id', $permission->id)->exists()) {
                    $adminRole->permissions()->attach($permission->id);
                }
            }

            $this->command->info('Added ' . $newPermissions->count() . ' Riwayat Transaksi permissions to Administrator role');
        }

        $this->command->info('Purchase Riwayat Transaksi permissions have been created successfully!');
    }
}
