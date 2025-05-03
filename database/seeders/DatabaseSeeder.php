<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class, // Harus pertama karena User membutuhkan Role
            UserSeeder::class,           // Kedua karena departemen memerlukan user sebagai penanggung jawab
            DepartmentSeeder::class,
            JabatanSeeder::class,
            KategoriProdukSeeder::class,
            SatuanSeeder::class,
            GudangSeeder::class,
            JenisProdukSeeder::class,
        ]);
    }
}
