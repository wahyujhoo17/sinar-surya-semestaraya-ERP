<?php
// filepath: database/seeders/JenisProdukSeeder.php

namespace Database\Seeders;

use App\Models\JenisProduk;
use Illuminate\Database\Seeder;

class JenisProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisProduk = [
            [
                'nama' => 'Kimia',
                'kode' => 'KIM',
                'deskripsi' => 'Produk kimia laboratory'
            ],
            // Tambahkan jenis produk lainnya jika perlu
        ];

        foreach ($jenisProduk as $jenis) {
            JenisProduk::create($jenis);
        }
    }
}
