<?php

namespace Database\Seeders;

use App\Models\AkunAkuntansi;
use Illuminate\Database\Seeder;

class AkunAkuntansiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        AkunAkuntansi::truncate();
        
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
        
        AkunAkuntansi::create([
            'kode' => '1113',
            'nama' => 'Kas di Bank Mandiri',
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $cashHeader->id,
        ]);
        
        // Accounts Receivable
        $arHeader = AkunAkuntansi::create([
            'kode' => '1120',
            'nama' => 'Piutang Dagang',
            'kategori' => 'asset',
            'tipe' => 'header',
            'parent_id' => $currentAssets->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '1121',
            'nama' => 'Piutang Usaha',
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $arHeader->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '1122',
            'nama' => 'Cadangan Piutang Tak Tertagih',
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $arHeader->id,
        ]);
        
        // Inventory
        $inventoryHeader = AkunAkuntansi::create([
            'kode' => '1130',
            'nama' => 'Persediaan',
            'kategori' => 'asset',
            'tipe' => 'header',
            'parent_id' => $currentAssets->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '1131',
            'nama' => 'Persediaan Barang Jadi',
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $inventoryHeader->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '1132',
            'nama' => 'Persediaan Bahan Baku',
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $inventoryHeader->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '1133',
            'nama' => 'Persediaan Dalam Proses',
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $inventoryHeader->id,
        ]);
        
        // Fixed Assets
        $fixedAssets = AkunAkuntansi::create([
            'kode' => '1200',
            'nama' => 'Aset Tetap',
            'kategori' => 'asset',
            'tipe' => 'header',
            'parent_id' => $assetHeader->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '1210',
            'nama' => 'Tanah',
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $fixedAssets->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '1220',
            'nama' => 'Gedung',
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $fixedAssets->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '1221',
            'nama' => 'Akumulasi Penyusutan Gedung',
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $fixedAssets->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '1230',
            'nama' => 'Kendaraan',
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $fixedAssets->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '1231',
            'nama' => 'Akumulasi Penyusutan Kendaraan',
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $fixedAssets->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '1240',
            'nama' => 'Peralatan dan Mesin',
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $fixedAssets->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '1241',
            'nama' => 'Akumulasi Penyusutan Peralatan dan Mesin',
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $fixedAssets->id,
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
        
        // Tax Payables
        $taxPayables = AkunAkuntansi::create([
            'kode' => '2120',
            'nama' => 'Hutang Pajak',
            'kategori' => 'liability',
            'tipe' => 'header',
            'parent_id' => $currentLiabilities->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '2121',
            'nama' => 'Hutang PPN',
            'kategori' => 'liability',
            'tipe' => 'detail',
            'parent_id' => $taxPayables->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '2122',
            'nama' => 'Hutang PPh 21',
            'kategori' => 'liability',
            'tipe' => 'detail',
            'parent_id' => $taxPayables->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '2123',
            'nama' => 'Hutang PPh 23',
            'kategori' => 'liability',
            'tipe' => 'detail',
            'parent_id' => $taxPayables->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '2124',
            'nama' => 'Hutang PPh 25/29',
            'kategori' => 'liability',
            'tipe' => 'detail',
            'parent_id' => $taxPayables->id,
        ]);
        
        // Long-term Liabilities
        $longTermLiabilities = AkunAkuntansi::create([
            'kode' => '2200',
            'nama' => 'Kewajiban Jangka Panjang',
            'kategori' => 'liability',
            'tipe' => 'header',
            'parent_id' => $liabilityHeader->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '2210',
            'nama' => 'Hutang Bank Jangka Panjang',
            'kategori' => 'liability',
            'tipe' => 'detail',
            'parent_id' => $longTermLiabilities->id,
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
        
        AkunAkuntansi::create([
            'kode' => '3200',
            'nama' => 'Laba Ditahan',
            'kategori' => 'equity',
            'tipe' => 'detail',
            'parent_id' => $equityHeader->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '3300',
            'nama' => 'Laba Tahun Berjalan',
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
        
        AkunAkuntansi::create([
            'kode' => '4200',
            'nama' => 'Pendapatan Jasa',
            'kategori' => 'income',
            'tipe' => 'detail',
            'parent_id' => $incomeHeader->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '4300',
            'nama' => 'Diskon Penjualan',
            'kategori' => 'income',
            'tipe' => 'detail',
            'parent_id' => $incomeHeader->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '4400',
            'nama' => 'Retur Penjualan',
            'kategori' => 'income',
            'tipe' => 'detail',
            'parent_id' => $incomeHeader->id,
        ]);
        
        $otherIncome = AkunAkuntansi::create([
            'kode' => '4900',
            'nama' => 'Pendapatan Lain-lain',
            'kategori' => 'income',
            'tipe' => 'header',
            'parent_id' => $incomeHeader->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '4910',
            'nama' => 'Pendapatan Bunga',
            'kategori' => 'income',
            'tipe' => 'detail',
            'parent_id' => $otherIncome->id,
        ]);
        
        // Expense Accounts (5000-5999)
        $expenseHeader = AkunAkuntansi::create([
            'kode' => '5000',
            'nama' => 'Beban',
            'kategori' => 'expense',
            'tipe' => 'header',
            'parent_id' => null,
        ]);
        
        // Cost of Goods Sold
        $cogs = AkunAkuntansi::create([
            'kode' => '5100',
            'nama' => 'Harga Pokok Penjualan (HPP)',
            'kategori' => 'expense',
            'tipe' => 'header',
            'parent_id' => $expenseHeader->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '5110',
            'nama' => 'Biaya Bahan Baku',
            'kategori' => 'expense',
            'tipe' => 'detail',
            'parent_id' => $cogs->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '5120',
            'nama' => 'Biaya Tenaga Kerja Langsung',
            'kategori' => 'expense',
            'tipe' => 'detail',
            'parent_id' => $cogs->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '5130',
            'nama' => 'Biaya Overhead Produksi',
            'kategori' => 'expense',
            'tipe' => 'detail',
            'parent_id' => $cogs->id,
        ]);
        
        // Operating Expenses
        $operatingExpenses = AkunAkuntansi::create([
            'kode' => '5200',
            'nama' => 'Beban Operasional',
            'kategori' => 'expense',
            'tipe' => 'header',
            'parent_id' => $expenseHeader->id,
        ]);
        
        // Administrative Expenses
        $adminExpenses = AkunAkuntansi::create([
            'kode' => '5210',
            'nama' => 'Beban Administrasi',
            'kategori' => 'expense',
            'tipe' => 'header',
            'parent_id' => $operatingExpenses->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '5211',
            'nama' => 'Beban Gaji dan Tunjangan',
            'kategori' => 'expense',
            'tipe' => 'detail',
            'parent_id' => $adminExpenses->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '5212',
            'nama' => 'Beban Sewa',
            'kategori' => 'expense',
            'tipe' => 'detail',
            'parent_id' => $adminExpenses->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '5213',
            'nama' => 'Beban Listrik, Air, dan Telepon',
            'kategori' => 'expense',
            'tipe' => 'detail',
            'parent_id' => $adminExpenses->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '5214',
            'nama' => 'Beban Penyusutan',
            'kategori' => 'expense',
            'tipe' => 'detail',
            'parent_id' => $adminExpenses->id,
        ]);
        
        // Marketing Expenses
        $marketingExpenses = AkunAkuntansi::create([
            'kode' => '5220',
            'nama' => 'Beban Pemasaran',
            'kategori' => 'expense',
            'tipe' => 'header',
            'parent_id' => $operatingExpenses->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '5221',
            'nama' => 'Beban Iklan dan Promosi',
            'kategori' => 'expense',
            'tipe' => 'detail',
            'parent_id' => $marketingExpenses->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '5222',
            'nama' => 'Beban Komisi Penjualan',
            'kategori' => 'expense',
            'tipe' => 'detail',
            'parent_id' => $marketingExpenses->id,
        ]);
        
        // Financial Expenses
        $financialExpenses = AkunAkuntansi::create([
            'kode' => '5300',
            'nama' => 'Beban Keuangan',
            'kategori' => 'expense',
            'tipe' => 'header',
            'parent_id' => $expenseHeader->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '5310',
            'nama' => 'Beban Bunga',
            'kategori' => 'expense',
            'tipe' => 'detail',
            'parent_id' => $financialExpenses->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '5320',
            'nama' => 'Beban Administrasi Bank',
            'kategori' => 'expense',
            'tipe' => 'detail',
            'parent_id' => $financialExpenses->id,
        ]);
        
        // Tax Expenses
        $taxExpenses = AkunAkuntansi::create([
            'kode' => '5400',
            'nama' => 'Beban Pajak',
            'kategori' => 'expense',
            'tipe' => 'header',
            'parent_id' => $expenseHeader->id,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '5410',
            'nama' => 'Beban Pajak Penghasilan',
            'kategori' => 'expense',
            'tipe' => 'detail',
            'parent_id' => $taxExpenses->id,
        ]);
        
        // Other Accounts (9000-9999)
        $otherHeader = AkunAkuntansi::create([
            'kode' => '9000',
            'nama' => 'Akun Lainnya',
            'kategori' => 'other',
            'tipe' => 'header',
            'parent_id' => null,
        ]);
        
        AkunAkuntansi::create([
            'kode' => '9100',
            'nama' => 'Akun Penampung Sementara',
            'kategori' => 'other',
            'tipe' => 'detail',
            'parent_id' => $otherHeader->id,
        ]);
    }
