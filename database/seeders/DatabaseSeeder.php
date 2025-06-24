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
            ComprehensivePermissionSeeder::class, // Seeder komprehensif untuk roles dan permissions
            UserSeeder::class,                    // Kedua karena departemen memerlukan user sebagai penanggung jawab
            DepartmentSeeder::class,
            JabatanSeeder::class,
            KategoriProdukSeeder::class,
            SatuanSeeder::class,
            GudangSeeder::class,
            JenisProdukSeeder::class,
            ReturPenjualanSeeder::class,          // Seeder untuk Retur Penjualan
            NotaKreditSeeder::class,              // Seeder untuk Nota Kredit
            SettingsSeeder::class,                // Seeder untuk pengaturan umum
        ]);
    }
}
