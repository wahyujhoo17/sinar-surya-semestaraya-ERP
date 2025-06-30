<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PurchaseModuleEnhancementPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Additional permissions that might be missing for existing purchase modules
        $additionalPermissions = [
            // Purchase Request enhancements
            [
                'kode' => 'purchase_request.export',
                'nama' => 'Purchase Request - Export',
                'modul' => 'purchase_request',
                'deskripsi' => 'Dapat mengeksport data permintaan pembelian'
            ],
            [
                'kode' => 'purchase_request.reject',
                'nama' => 'Purchase Request - Reject',
                'modul' => 'purchase_request',
                'deskripsi' => 'Dapat menolak permintaan pembelian'
            ],

            // Purchase Order enhancements
            [
                'kode' => 'purchase_order.export',
                'nama' => 'Purchase Order - Export',
                'modul' => 'purchase_order',
                'deskripsi' => 'Dapat mengeksport data purchase order'
            ],
            [
                'kode' => 'purchase_order.receive',
                'nama' => 'Purchase Order - Receive',
                'modul' => 'purchase_order',
                'deskripsi' => 'Dapat melakukan penerimaan barang dari purchase order'
            ],

            // Penerimaan Barang enhancements
            [
                'kode' => 'penerimaan_barang.export',
                'nama' => 'Penerimaan Barang - Export',
                'modul' => 'penerimaan_barang',
                'deskripsi' => 'Dapat mengeksport data penerimaan barang'
            ],
            [
                'kode' => 'penerimaan_barang.change_status',
                'nama' => 'Penerimaan Barang - Change Status',
                'modul' => 'penerimaan_barang',
                'deskripsi' => 'Dapat mengubah status penerimaan barang'
            ],

            // Retur Pembelian enhancements
            [
                'kode' => 'retur_pembelian.export',
                'nama' => 'Retur Pembelian - Export',
                'modul' => 'retur_pembelian',
                'deskripsi' => 'Dapat mengeksport data retur pembelian'
            ],
            [
                'kode' => 'retur_pembelian.approve',
                'nama' => 'Retur Pembelian - Approve',
                'modul' => 'retur_pembelian',
                'deskripsi' => 'Dapat menyetujui retur pembelian'
            ],
            [
                'kode' => 'retur_pembelian.change_status',
                'nama' => 'Retur Pembelian - Change Status',
                'modul' => 'retur_pembelian',
                'deskripsi' => 'Dapat mengubah status retur pembelian'
            ],
            [
                'kode' => 'retur_pembelian.receive_replacement',
                'nama' => 'Retur Pembelian - Receive Replacement',
                'modul' => 'retur_pembelian',
                'deskripsi' => 'Dapat menerima barang pengganti retur'
            ],
            [
                'kode' => 'retur_pembelian.create_refund',
                'nama' => 'Retur Pembelian - Create Refund',
                'modul' => 'retur_pembelian',
                'deskripsi' => 'Dapat membuat pengembalian dana dari retur'
            ],

            // Riwayat Transaksi permissions (main missing module)
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
            ],
            [
                'kode' => 'riwayat_transaksi.detail',
                'nama' => 'Riwayat Transaksi - Detail',
                'modul' => 'riwayat_transaksi',
                'deskripsi' => 'Dapat melihat detail riwayat transaksi'
            ]
        ];

        $newPermissionsCount = 0;
        $existingPermissionsCount = 0;

        foreach ($additionalPermissions as $permissionData) {
            $permission = Permission::firstOrCreate(
                ['kode' => $permissionData['kode']],
                $permissionData
            );

            if ($permission->wasRecentlyCreated) {
                $newPermissionsCount++;
                $this->command->info("Created: {$permissionData['kode']} - {$permissionData['nama']}");
            } else {
                $existingPermissionsCount++;
            }
        }

        // Assign all new permissions to Administrator role
        $adminRole = Role::where('nama', 'Administrator')->first();
        if ($adminRole) {
            $allPermissionCodes = collect($additionalPermissions)->pluck('kode')->toArray();
            $newPermissions = Permission::whereIn('kode', $allPermissionCodes)->get();

            $assignedCount = 0;
            foreach ($newPermissions as $permission) {
                if (!$adminRole->permissions()->where('permission_id', $permission->id)->exists()) {
                    $adminRole->permissions()->attach($permission->id);
                    $assignedCount++;
                }
            }

            $this->command->info("Assigned {$assignedCount} permissions to Administrator role");
        }

        $this->command->info("Summary:");
        $this->command->info("- New permissions created: {$newPermissionsCount}");
        $this->command->info("- Existing permissions: {$existingPermissionsCount}");
        $this->command->info("Purchase module permissions enhancement completed!");
    }
}
