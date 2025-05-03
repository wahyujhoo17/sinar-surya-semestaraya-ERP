<?php

namespace Database\Seeders;

use App\Models\Gudang;
use Illuminate\Database\Seeder;

class GudangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gudang = [
            [
                'nama' => 'Gudang Utama',
                'kode' => 'GU',
                'alamat' => 'Jl. Utama No. 123, Jakarta',
                'telepon' => '021-1234567',
                'jenis' => 'utama'
            ],
            [
                'nama' => 'Gudang Cabang Surabaya',
                'kode' => 'GCS',
                'alamat' => 'Jl. Cabang No. 456, Surabaya',
                'telepon' => '031-7654321',
                'jenis' => 'cabang'
            ],
            [
                'nama' => 'Gudang Produksi',
                'kode' => 'GPD',
                'alamat' => 'Jl. Produksi No. 789, Jakarta',
                'telepon' => '021-9876543',
                'jenis' => 'produksi'
            ]
        ];

        foreach ($gudang as $item) {
            Gudang::create($item);
        }
    }
}