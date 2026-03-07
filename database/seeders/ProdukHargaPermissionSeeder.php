<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class ProdukHargaPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Permission ini mengontrol siapa yang boleh melihat harga beli,
     * harga jual, dan detail margin/harga pada data produk.
     */
    public function run(): void
    {
        $this->command->info('=== MEMBUAT PERMISSION LIHAT HARGA PRODUK ===');

        // Buat permission
        $permission = Permission::firstOrCreate(
            ['kode' => 'produk.lihat_harga'],
            [
                'nama'      => 'Produk - Lihat Harga',
                'kode'      => 'produk.lihat_harga',
                'modul'     => 'produk',
                'deskripsi' => 'Dapat melihat harga beli, harga jual, dan detail margin produk',
            ]
        );

        $this->command->info('Permission "produk.lihat_harga" ' . ($permission->wasRecentlyCreated ? 'dibuat.' : 'sudah ada.'));

        $this->command->info('Selesai! Silakan assign permission ini ke role yang diinginkan melalui UI manajemen role.');
    }
}