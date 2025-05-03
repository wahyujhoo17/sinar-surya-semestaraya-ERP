<?php

namespace Database\Seeders;

use App\Models\KategoriProduk;
use Illuminate\Database\Seeder;

class KategoriProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            [
                'nama' => 'Pompa',
                'kode' => 'PMP',
                'deskripsi' => 'Pompa pemadam kebakaran'
            ],
            [
                'nama' => 'Aksesoris',
                'kode' => 'AKS',
                'deskripsi' => 'Aksesoris pemadam kebakaran'
            ],
            [
                'nama' => 'Sparepart',
                'kode' => 'SPT',
                'deskripsi' => 'Sparepart untuk perbaikan dan pemeliharaan'
            ],
            [
                'nama' => 'Perlengkapan Safety',
                'kode' => 'SFT',
                'deskripsi' => 'Perlengkapan keselamatan'
            ],
        ];

        foreach ($kategori as $item) {
            KategoriProduk::create(attributes: $item);
        }
    }
}