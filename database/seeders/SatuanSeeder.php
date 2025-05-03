<?php

namespace Database\Seeders;

use App\Models\Satuan;
use Illuminate\Database\Seeder;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $satuan = [
            ['nama' => 'Piece', 'kode' => 'Pcs'],
            ['nama' => 'Unit', 'kode' => 'Unit'],
            ['nama' => 'Box', 'kode' => 'Box'],
            ['nama' => 'Kilogram', 'kode' => 'Kg'],
            ['nama' => 'Gram', 'kode' => 'g'],
            ['nama' => 'Meter', 'kode' => 'm'],
            ['nama' => 'Centimeter', 'kode' => 'cm'],
            ['nama' => 'Liter', 'kode' => 'L'],
            ['nama' => 'Mililiter', 'kode' => 'ml'],
            ['nama' => 'Set', 'kode' => 'Set'],
            ['nama' => 'Roll', 'kode' => 'Roll'],
        ];

        foreach ($satuan as $item) {
            Satuan::create($item);
        }
    }
}