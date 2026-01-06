<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChartOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate table if needed (optional - uncomment if you want to clear existing data)
        // DB::table('chart_of_accounts')->truncate();

        // Data dikelompokkan berdasarkan level hierarki
        // Level 1: Parent NULL (root accounts)
        $level1 = [
            ['id' => 1, 'kode' => '10000', 'nama' => 'Aset', 'tipe' => 'asset', 'level' => 'header', 'parent_id' => null, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 6, 'kode' => '20000', 'nama' => 'Hutang', 'tipe' => 'liability', 'level' => 'header', 'parent_id' => null, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 10, 'kode' => '30000', 'nama' => 'Modal', 'tipe' => 'equity', 'level' => 'header', 'parent_id' => null, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 12, 'kode' => '40000', 'nama' => 'Pendapatan', 'tipe' => 'income', 'level' => 'header', 'parent_id' => null, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 14, 'kode' => '60000', 'nama' => 'Biaya', 'tipe' => 'expense', 'level' => 'header', 'parent_id' => null, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 123, 'kode' => '50000', 'nama' => 'Pembelian', 'tipe' => 'purchase', 'level' => 'header', 'parent_id' => null, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 128, 'kode' => '70000', 'nama' => 'Pendapatan Di Luar Usaha', 'tipe' => 'other_income', 'level' => 'header', 'parent_id' => null, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 134, 'kode' => '80000', 'nama' => 'Biaya Di Luar Usaha', 'tipe' => 'other_expense', 'level' => 'header', 'parent_id' => null, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
        ];

        // Level 2: Children of Level 1
        $level2 = [
            ['id' => 2, 'kode' => '11000', 'nama' => 'Aset Lancar', 'tipe' => 'asset', 'level' => 'header', 'parent_id' => 1, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 86, 'kode' => '12000', 'nama' => 'Aset Tetap', 'tipe' => 'asset', 'level' => 'header', 'parent_id' => 1, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 7, 'kode' => '20010', 'nama' => 'Hutang Jangka Panjang', 'tipe' => 'liability', 'level' => 'header', 'parent_id' => 6, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 120, 'kode' => '20020', 'nama' => 'Hutang Jangka Pendek', 'tipe' => 'liability', 'level' => 'header', 'parent_id' => 6, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 11, 'kode' => '30010', 'nama' => 'Modal Usaha', 'tipe' => 'equity', 'level' => 'header', 'parent_id' => 10, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 35, 'kode' => '30040', 'nama' => 'R/L Tahun Berjalan', 'tipe' => 'equity', 'level' => 'detail', 'parent_id' => 10, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 118, 'kode' => '30020', 'nama' => 'Deviden', 'tipe' => 'equity', 'level' => 'detail', 'parent_id' => 10, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 119, 'kode' => '30030', 'nama' => 'Prive (Pengurang Modal)', 'tipe' => 'equity', 'level' => 'detail', 'parent_id' => 10, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 165, 'kode' => '3300', 'nama' => 'Ikhtisar Laba Rugi', 'tipe' => 'equity', 'level' => 'detail', 'parent_id' => 10, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 13, 'kode' => '40001', 'nama' => 'Penjualan', 'tipe' => 'income', 'level' => 'detail', 'parent_id' => 12, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 34, 'kode' => '40002', 'nama' => 'Uang Muka Penjualan', 'tipe' => 'income', 'level' => 'detail', 'parent_id' => 12, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 111, 'kode' => '40900', 'nama' => 'Retur Penjualan', 'tipe' => 'income', 'level' => 'detail', 'parent_id' => 12, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 112, 'kode' => '41000', 'nama' => 'Potongan Penjualan', 'tipe' => 'income', 'level' => 'detail', 'parent_id' => 12, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 15, 'kode' => '60500', 'nama' => 'Biaya Operasional', 'tipe' => 'expense', 'level' => 'header', 'parent_id' => 14, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 28, 'kode' => '5100', 'nama' => 'Harga Pokok Penjualan', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 14, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 33, 'kode' => '5300', 'nama' => 'Penyesuaian Persediaan', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 14, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 49, 'kode' => '60300', 'nama' => 'Biaya Produksi', 'tipe' => 'expense', 'level' => 'header', 'parent_id' => 14, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 53, 'kode' => '60200', 'nama' => 'Biaya Penjualan', 'tipe' => 'expense', 'level' => 'header', 'parent_id' => 14, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 127, 'kode' => '60100', 'nama' => 'Biaya Pembelian', 'tipe' => 'expense', 'level' => 'header', 'parent_id' => 14, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 138, 'kode' => '60400', 'nama' => 'Biaya Karyawan', 'tipe' => 'expense', 'level' => 'header', 'parent_id' => 14, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 139, 'kode' => '60700', 'nama' => 'Biaya Pemeliharaan', 'tipe' => 'expense', 'level' => 'header', 'parent_id' => 14, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 142, 'kode' => '60600', 'nama' => 'Biaya ADM & Umum', 'tipe' => 'expense', 'level' => 'header', 'parent_id' => 14, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 158, 'kode' => '60800', 'nama' => 'Biaya Pajak', 'tipe' => 'expense', 'level' => 'header', 'parent_id' => 14, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 124, 'kode' => '50001', 'nama' => 'Pembelian', 'tipe' => 'purchase', 'level' => 'detail', 'parent_id' => 123, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 125, 'kode' => '50900', 'nama' => 'Retur Pembelian', 'tipe' => 'purchase', 'level' => 'detail', 'parent_id' => 123, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 126, 'kode' => '51000', 'nama' => 'Potongan Pembelian', 'tipe' => 'purchase', 'level' => 'detail', 'parent_id' => 123, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 129, 'kode' => '70100', 'nama' => 'Pendapatan Sewa Tempat', 'tipe' => 'other_income', 'level' => 'detail', 'parent_id' => 128, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 130, 'kode' => '70200', 'nama' => 'Pendapatan Sewa Bendera', 'tipe' => 'other_income', 'level' => 'detail', 'parent_id' => 128, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 131, 'kode' => '70300', 'nama' => 'Pendapatan Bunga', 'tipe' => 'other_income', 'level' => 'detail', 'parent_id' => 128, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 132, 'kode' => '70400', 'nama' => 'Pendapatan Selisih Kurs', 'tipe' => 'other_income', 'level' => 'detail', 'parent_id' => 128, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 133, 'kode' => '70500', 'nama' => 'Pendapatan Lain-lain', 'tipe' => 'other_income', 'level' => 'detail', 'parent_id' => 128, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 135, 'kode' => '80100', 'nama' => 'Biaya Bank', 'tipe' => 'other_expense', 'level' => 'detail', 'parent_id' => 134, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 136, 'kode' => '80200', 'nama' => 'Biaya Selisih Kurs', 'tipe' => 'other_expense', 'level' => 'detail', 'parent_id' => 134, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
        ];

        // Level 3: Children of Level 2
        $level3 = [
            ['id' => 19, 'kode' => '11001', 'nama' => 'Kas Kantor', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 2, 'aktif' => 1, 'linkable_id' => 1, 'linkable_type' => 'App\\Models\\Kas'],
            ['id' => 25, 'kode' => '11200', 'nama' => 'Persediaan', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 2, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 26, 'kode' => '11250', 'nama' => 'PPN Masukan', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 2, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 37, 'kode' => '11800', 'nama' => 'Project dalam Pengerjaan', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 2, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 45, 'kode' => '11100', 'nama' => 'Piutang Usaha', 'tipe' => 'asset', 'level' => 'header', 'parent_id' => 2, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 89, 'kode' => '11050', 'nama' => 'Kas Bank', 'tipe' => 'asset', 'level' => 'header', 'parent_id' => 2, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 92, 'kode' => '11002', 'nama' => 'Kas Toko', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 2, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 96, 'kode' => '11300', 'nama' => 'Peralatan Kantor', 'tipe' => 'asset', 'level' => 'header', 'parent_id' => 2, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 97, 'kode' => '11400', 'nama' => 'Perlengkapan Kantor', 'tipe' => 'asset', 'level' => 'header', 'parent_id' => 2, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 98, 'kode' => '11500', 'nama' => 'Kendaraan', 'tipe' => 'asset', 'level' => 'header', 'parent_id' => 2, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 101, 'kode' => '11600', 'nama' => 'Beban Dibayar Dimuka', 'tipe' => 'asset', 'level' => 'header', 'parent_id' => 2, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 106, 'kode' => '11700', 'nama' => 'Akumulasi Penyusutan', 'tipe' => 'asset', 'level' => 'header', 'parent_id' => 2, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 84, 'kode' => '12050', 'nama' => 'Bangunan', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 86, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 87, 'kode' => '12001', 'nama' => 'Tanah', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 86, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 9, 'kode' => '20011', 'nama' => 'Hutang Bank', 'tipe' => 'liability', 'level' => 'detail', 'parent_id' => 7, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 121, 'kode' => '20012', 'nama' => 'Hutang Leasing', 'tipe' => 'liability', 'level' => 'detail', 'parent_id' => 7, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 8, 'kode' => '20022', 'nama' => 'Hutang Dagang', 'tipe' => 'liability', 'level' => 'detail', 'parent_id' => 120, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 27, 'kode' => '20021', 'nama' => 'Hutang Pajak', 'tipe' => 'liability', 'level' => 'detail', 'parent_id' => 120, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 47, 'kode' => '20024', 'nama' => 'Hutang Uang Muka Pembelian', 'tipe' => 'liability', 'level' => 'detail', 'parent_id' => 120, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 122, 'kode' => '20023', 'nama' => 'Hutang Karyawan', 'tipe' => 'liability', 'level' => 'detail', 'parent_id' => 120, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 36, 'kode' => '30011', 'nama' => 'Modal Sendiri', 'tipe' => 'equity', 'level' => 'detail', 'parent_id' => 11, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 116, 'kode' => '30012', 'nama' => 'Modal Saham', 'tipe' => 'equity', 'level' => 'detail', 'parent_id' => 11, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 117, 'kode' => '30013', 'nama' => 'Laba Ditahan', 'tipe' => 'equity', 'level' => 'detail', 'parent_id' => 11, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 20, 'kode' => '60501', 'nama' => 'Biaya Listrik', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 15, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 29, 'kode' => '60504', 'nama' => 'Biaya Sewa', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 15, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 30, 'kode' => '60505', 'nama' => 'Biaya Entertaint ADM', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 15, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 32, 'kode' => '5216', 'nama' => 'Beban Lainnya', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 15, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 69, 'kode' => '60605', 'nama' => 'Biaya ATK', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 15, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 70, 'kode' => '61015', 'nama' => 'Biaya Air', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 15, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 76, 'kode' => '60506', 'nama' => 'Biaya Perjalanan Dinas', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 15, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 77, 'kode' => '60507', 'nama' => 'Biaya Operasional Lainnya', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 15, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 137, 'kode' => '60503', 'nama' => 'Biaya Telepon', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 15, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 163, 'kode' => '60312', 'nama' => 'Biaya Internet', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 15, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 50, 'kode' => '603001', 'nama' => 'Biaya Packing Produksi', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 49, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 51, 'kode' => '603003', 'nama' => 'Biaya Produksi Lainnya', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 49, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 55, 'kode' => '60209', 'nama' => 'Bi Penjualan Sambunesia', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 53, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 56, 'kode' => '60202', 'nama' => 'Biaya Ekspedisi Kirim Barang', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 53, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 68, 'kode' => '60205', 'nama' => 'Biaya Entertaint Penjualan', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 53, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 73, 'kode' => '60207', 'nama' => 'Biaya Promosi & Iklan', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 53, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 143, 'kode' => '60201', 'nama' => 'Biaya Transportasi Kirim Barang', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 53, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 144, 'kode' => '60203', 'nama' => 'Biaya Kuli Angkut Kirim Barang', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 53, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 145, 'kode' => '60204', 'nama' => 'Biaya Packing Kirim Barang', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 53, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 146, 'kode' => '60206', 'nama' => 'Biaya Asuransi Penjualan', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 53, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 147, 'kode' => '60208', 'nama' => 'Biaya Lain-lain', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 53, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 114, 'kode' => '60101', 'nama' => 'Biaya Transportasi Beli Barang', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 127, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 148, 'kode' => '60102', 'nama' => 'Biaya Ekspedisi Beli Barang', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 127, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 149, 'kode' => '60103', 'nama' => 'Biaya Kuli Angkut Beli Barang', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 127, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 150, 'kode' => '60104', 'nama' => 'Biaya Packing Beli Barang', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 127, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 151, 'kode' => '60105', 'nama' => 'Biaya Entertaint Pembelian', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 127, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 152, 'kode' => '60106', 'nama' => 'Biaya Asuransi Pembelian', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 127, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 153, 'kode' => '60107', 'nama' => 'Biaya Lain-lain', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 127, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 16, 'kode' => '60401', 'nama' => 'Biaya Gaji', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 138, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 63, 'kode' => '60405', 'nama' => 'Biaya Tunjangan Tahunan', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 138, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 64, 'kode' => '60406', 'nama' => 'Biaya Tunjangan Transport Karyawan', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 138, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 65, 'kode' => '60409', 'nama' => 'Bi Tunjangan Lainnya', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 138, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 66, 'kode' => '60404', 'nama' => 'Biaya Kesehatan Karyawan', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 138, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 75, 'kode' => '60408', 'nama' => 'Biaya Training', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 138, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 140, 'kode' => '60403', 'nama' => 'Biaya Tunjangan Hari Raya', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 138, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 141, 'kode' => '60402', 'nama' => 'Biaya Lembur', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 138, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 58, 'kode' => '60701', 'nama' => 'Biaya Pemeliharaan Bangunan', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 139, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 59, 'kode' => '60702', 'nama' => 'Biaya Pemeliharaan Peralatan', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 139, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 61, 'kode' => '60703', 'nama' => 'Biaya Pemeliharaan Kend Roda 4', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 139, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 62, 'kode' => '60704', 'nama' => 'Bi Pemeliharaan Kend Roda 2', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 139, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 154, 'kode' => '60315', 'nama' => 'Bi Akumulasi Penyusutan Gedung', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 139, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 155, 'kode' => '60318', 'nama' => 'Bi Akumulasi Penyusutan Kend Roda 4', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 139, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 156, 'kode' => '60320', 'nama' => 'Bi Akumulasi Peny Kend Roda 2', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 139, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 157, 'kode' => '60322', 'nama' => 'Bi Akumulasi Penyusutan Peralatan', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 139, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 60, 'kode' => '60606', 'nama' => 'Biaya Perlengkapan', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 142, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 67, 'kode' => '60604', 'nama' => 'Biaya Konsumsi', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 142, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 71, 'kode' => '60601', 'nama' => 'Biaya Legalitas dan Perizinan', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 142, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 72, 'kode' => '60607', 'nama' => 'Biaya Jasa', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 142, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 74, 'kode' => '60608', 'nama' => 'Biaya Pajak', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 142, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 78, 'kode' => '60610', 'nama' => 'Biaya Transfer', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 142, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 79, 'kode' => '60603', 'nama' => 'Biaya Sosial', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 142, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 80, 'kode' => '60609', 'nama' => 'Biaya Bunga', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 142, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 81, 'kode' => '60602', 'nama' => 'Bi Admin Bank', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 142, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 82, 'kode' => '60611', 'nama' => 'Biaya Materai', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 142, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 83, 'kode' => '60612', 'nama' => 'Bi Bank Lainnya', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 142, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 159, 'kode' => '60323', 'nama' => 'Bi Pajak PPN DN', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 158, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 160, 'kode' => '60324', 'nama' => 'Bi Pajak PPH 21', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 158, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 161, 'kode' => '60325', 'nama' => 'Bi Pajak PPH 25', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 158, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 162, 'kode' => '60326', 'nama' => 'Bi Pajak Lain lain', 'tipe' => 'expense', 'level' => 'detail', 'parent_id' => 158, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
        ];

        // Level 4: Children of Level 3
        $level4 = [
            ['id' => 24, 'kode' => '11101', 'nama' => 'Piutang Dagang', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 45, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 46, 'kode' => '11102', 'nama' => 'Piutang Karyawan', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 45, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 93, 'kode' => '11103', 'nama' => 'Cad. Kerugian Piutang', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 45, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 94, 'kode' => '11104', 'nama' => 'Piutang lain-lain', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 45, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 22, 'kode' => '11051', 'nama' => 'Mandiri SSSR 0060003019563', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 89, 'aktif' => 1, 'linkable_id' => 2, 'linkable_type' => 'App\\Models\\RekeningBank'],
            ['id' => 23, 'kode' => '11052', 'nama' => 'BSI SSSR 7292792438', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 89, 'aktif' => 1, 'linkable_id' => 1, 'linkable_type' => 'App\\Models\\RekeningBank'],
            ['id' => 90, 'kode' => '11053', 'nama' => 'BCA ARH 2721460161', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 89, 'aktif' => 1, 'linkable_id' => 3, 'linkable_type' => 'App\\Models\\RekeningBank'],
            ['id' => 91, 'kode' => '11054', 'nama' => 'BCA ARH 2730108306', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 89, 'aktif' => 1, 'linkable_id' => 7, 'linkable_type' => 'App\\Models\\RekeningBank'],
            ['id' => 99, 'kode' => '11501', 'nama' => 'Kendaraan Roda 4', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 98, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 100, 'kode' => '11502', 'nama' => 'Kendaraan Roda 2', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 98, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 102, 'kode' => '11601', 'nama' => 'Penjualan Dibayar Dimuka', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 101, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 103, 'kode' => '11602', 'nama' => 'Pembelian Dibayar Dimuka', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 101, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 104, 'kode' => '11603', 'nama' => 'Sewa Dibayar Dimuka', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 101, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 105, 'kode' => '11604', 'nama' => 'Lain-lain dibayar dimuka', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 101, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 107, 'kode' => '11701', 'nama' => 'Akum. Peny Kendaraan Roda 4', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 106, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 108, 'kode' => '11702', 'nama' => 'Akum. Peny Kendaraan Roda 2', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 106, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 109, 'kode' => '11703', 'nama' => 'Akum. Peny Bangunan', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 106, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
            ['id' => 110, 'kode' => '11704', 'nama' => 'Akum. Peny Peralatan', 'tipe' => 'asset', 'level' => 'detail', 'parent_id' => 106, 'aktif' => 1, 'linkable_id' => null, 'linkable_type' => null],
        ];

        // Insert data level by level
        foreach ($level1 as $data) {
            DB::table('chart_of_accounts')->insert($data);
        }

        foreach ($level2 as $data) {
            DB::table('chart_of_accounts')->insert($data);
        }

        foreach ($level3 as $data) {
            DB::table('chart_of_accounts')->insert($data);
        }

        foreach ($level4 as $data) {
            DB::table('chart_of_accounts')->insert($data);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Chart of Accounts data berhasil diimport!');
    }
}
