<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class ProductBundlePermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['nama' => 'Product Bundle - View', 'kode' => 'product_bundle.view', 'modul' => 'product_bundle', 'deskripsi' => 'Dapat melihat data product bundle'],
            ['nama' => 'Product Bundle - Create', 'kode' => 'product_bundle.create', 'modul' => 'product_bundle', 'deskripsi' => 'Dapat membuat product bundle'],
            ['nama' => 'Product Bundle - Edit', 'kode' => 'product_bundle.edit', 'modul' => 'product_bundle', 'deskripsi' => 'Dapat mengedit product bundle'],
            ['nama' => 'Product Bundle - Delete', 'kode' => 'product_bundle.delete', 'modul' => 'product_bundle', 'deskripsi' => 'Dapat menghapus product bundle'],
            ['nama' => 'Product Bundle - Stock Check', 'kode' => 'product_bundle.stock_check', 'modul' => 'product_bundle', 'deskripsi' => 'Dapat cek stok product bundle'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['kode' => $permission['kode']],
                $permission
            );
        }
    }
}
