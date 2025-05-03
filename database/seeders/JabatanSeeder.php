<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatan = [
            ['nama' => 'Direktur Utama', 'kode' => 'DIR-1', 'deskripsi' => 'Direktur Utama Perusahaan'],
            ['nama' => 'Manajer Keuangan', 'kode' => 'MNG-FIN', 'deskripsi' => 'Manajer Departemen Keuangan'],
            ['nama' => 'Manajer Penjualan', 'kode' => 'MNG-SAL', 'deskripsi' => 'Manajer Departemen Penjualan'],
            ['nama' => 'Manajer Operasional', 'kode' => 'MNG-OPS', 'deskripsi' => 'Manajer Departemen Operasional'],
            ['nama' => 'Manajer Produksi', 'kode' => 'MNG-PRD', 'deskripsi' => 'Manajer Departemen Produksi'],
            ['nama' => 'Manajer Gudang', 'kode' => 'MNG-LOG', 'deskripsi' => 'Manajer Departemen Gudang'],
            ['nama' => 'Manajer SDM', 'kode' => 'MNG-HRD', 'deskripsi' => 'Manajer Departemen SDM'],
            ['nama' => 'Manajer Teknik', 'kode' => 'MNG-ENG', 'deskripsi' => 'Manajer Departemen Teknik'],
            ['nama' => 'Staff Keuangan', 'kode' => 'STF-FIN', 'deskripsi' => 'Staff Departemen Keuangan'],
            ['nama' => 'Staff Penjualan', 'kode' => 'STF-SAL', 'deskripsi' => 'Staff Departemen Penjualan'],
            ['nama' => 'Staff Produksi', 'kode' => 'STF-PRD', 'deskripsi' => 'Staff Departemen Produksi'],
            ['nama' => 'Staff Gudang', 'kode' => 'STF-LOG', 'deskripsi' => 'Staff Departemen Gudang'],
            ['nama' => 'Staff SDM', 'kode' => 'STF-HRD', 'deskripsi' => 'Staff Departemen SDM'],
            ['nama' => 'Staff Teknik', 'kode' => 'STF-ENG', 'deskripsi' => 'Staff Departemen Teknik'],
            ['nama' => 'Teknisi', 'kode' => 'TEK', 'deskripsi' => 'Teknisi Lapangan'],
            ['nama' => 'Admin', 'kode' => 'ADM', 'deskripsi' => 'Administrator Sistem']
        ];

        foreach ($jabatan as $item) {
            Jabatan::create($item);
        }
    }
}