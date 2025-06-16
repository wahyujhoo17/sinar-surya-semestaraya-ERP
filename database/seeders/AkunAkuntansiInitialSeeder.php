<?php

namespace Database\Seeders;

use App\Models\AkunAkuntansi;
use Illuminate\Database\Seeder;

class AkunAkuntansiInitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asset Accounts (1000-1999)
        $assetHeader = AkunAkuntansi::create([
            'kode' => '1000',
            'nama' => 'Aset',
            'kategori' => 'asset',
            'tipe' => 'header',
            'parent_id' => null,
        ]);

        // Current Assets
        $currentAssets = AkunAkuntansi::create([
            'kode' => '1100',
            'nama' => 'Aset Lancar',
            'kategori' => 'asset',
            'tipe' => 'header',
            'parent_id' => $assetHeader->id,
        ]);

        // Cash and Cash Equivalents
        $cashHeader = AkunAkuntansi::create([
            'kode' => '1110',
            'nama' => 'Kas dan Setara Kas',
            'kategori' => 'asset',
            'tipe' => 'header',
            'parent_id' => $currentAssets->id,
        ]);

        AkunAkuntansi::create([
            'kode' => '1111',
            'nama' => 'Kas Kecil',
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $cashHeader->id,
        ]);

        AkunAkuntansi::create([
            'kode' => '1112',
            'nama' => 'Kas di Bank BCA',
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $cashHeader->id,
        ]);

        // Liability Accounts (2000-2999)
        $liabilityHeader = AkunAkuntansi::create([
            'kode' => '2000',
            'nama' => 'Kewajiban',
            'kategori' => 'liability',
            'tipe' => 'header',
            'parent_id' => null,
        ]);

        // Current Liabilities
        $currentLiabilities = AkunAkuntansi::create([
            'kode' => '2100',
            'nama' => 'Kewajiban Lancar',
            'kategori' => 'liability',
            'tipe' => 'header',
            'parent_id' => $liabilityHeader->id,
        ]);

        // Accounts Payable
        $apHeader = AkunAkuntansi::create([
            'kode' => '2110',
            'nama' => 'Hutang Dagang',
            'kategori' => 'liability',
            'tipe' => 'header',
            'parent_id' => $currentLiabilities->id,
        ]);

        AkunAkuntansi::create([
            'kode' => '2111',
            'nama' => 'Hutang Usaha',
            'kategori' => 'liability',
            'tipe' => 'detail',
            'parent_id' => $apHeader->id,
        ]);

        // Equity Accounts (3000-3999)
        $equityHeader = AkunAkuntansi::create([
            'kode' => '3000',
            'nama' => 'Ekuitas',
            'kategori' => 'equity',
            'tipe' => 'header',
            'parent_id' => null,
        ]);

        AkunAkuntansi::create([
            'kode' => '3100',
            'nama' => 'Modal Disetor',
            'kategori' => 'equity',
            'tipe' => 'detail',
            'parent_id' => $equityHeader->id,
        ]);

        // Income Accounts (4000-4999)
        $incomeHeader = AkunAkuntansi::create([
            'kode' => '4000',
            'nama' => 'Pendapatan',
            'kategori' => 'income',
            'tipe' => 'header',
            'parent_id' => null,
        ]);

        AkunAkuntansi::create([
            'kode' => '4100',
            'nama' => 'Pendapatan Penjualan',
            'kategori' => 'income',
            'tipe' => 'detail',
            'parent_id' => $incomeHeader->id,
        ]);

        // Expense Accounts (5000-5999)
        $expenseHeader = AkunAkuntansi::create([
            'kode' => '5000',
            'nama' => 'Beban',
            'kategori' => 'expense',
            'tipe' => 'header',
            'parent_id' => null,
        ]);

        // Operating Expenses
        $operatingExpenses = AkunAkuntansi::create([
            'kode' => '5200',
            'nama' => 'Beban Operasional',
            'kategori' => 'expense',
            'tipe' => 'header',
            'parent_id' => $expenseHeader->id,
        ]);

        AkunAkuntansi::create([
            'kode' => '5211',
            'nama' => 'Beban Gaji dan Tunjangan',
            'kategori' => 'expense',
            'tipe' => 'detail',
            'parent_id' => $operatingExpenses->id,
        ]);
    }
}
