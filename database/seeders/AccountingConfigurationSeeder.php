<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountingConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configurations = [
            // Pembayaran Hutang
            [
                'transaction_type' => 'pembayaran_hutang',
                'account_key' => 'hutang_usaha',
                'account_name' => 'Hutang Usaha',
                'akun_id' => config('accounting.pembayaran_hutang.hutang_usaha'),
                'is_required' => true,
                'description' => 'Akun hutang usaha yang akan dikredit saat pembayaran hutang'
            ],
            [
                'transaction_type' => 'pembayaran_hutang',
                'account_key' => 'kas',
                'account_name' => 'Kas',
                'akun_id' => config('accounting.pembayaran_hutang.kas'),
                'is_required' => false,
                'description' => 'Akun kas yang akan didebit saat pembayaran menggunakan kas'
            ],
            [
                'transaction_type' => 'pembayaran_hutang',
                'account_key' => 'bank',
                'account_name' => 'Bank',
                'akun_id' => config('accounting.pembayaran_hutang.bank'),
                'is_required' => false,
                'description' => 'Akun bank yang akan didebit saat pembayaran menggunakan transfer'
            ],

            // Penggajian
            [
                'transaction_type' => 'penggajian',
                'account_key' => 'beban_gaji',
                'account_name' => 'Beban Gaji',
                'akun_id' => config('accounting.penggajian.beban_gaji'),
                'is_required' => true,
                'description' => 'Akun beban gaji yang akan didebit saat penggajian'
            ],
            [
                'transaction_type' => 'penggajian',
                'account_key' => 'beban_utilitas',
                'account_name' => 'Beban Utilitas',
                'akun_id' => config('accounting.penggajian.beban_utilitas'),
                'is_required' => false,
                'description' => 'Akun beban utilitas yang akan didebit saat penggajian'
            ],
            [
                'transaction_type' => 'penggajian',
                'account_key' => 'beban_atk',
                'account_name' => 'Beban ATK',
                'akun_id' => config('accounting.penggajian.beban_atk'),
                'is_required' => false,
                'description' => 'Akun beban alat tulis kantor yang akan didebit saat penggajian'
            ],
            [
                'transaction_type' => 'penggajian',
                'account_key' => 'kas',
                'account_name' => 'Kas',
                'akun_id' => config('accounting.penggajian.kas'),
                'is_required' => false,
                'description' => 'Akun kas yang akan dikredit saat penggajian tunai'
            ],
            [
                'transaction_type' => 'penggajian',
                'account_key' => 'bank',
                'account_name' => 'Bank',
                'akun_id' => config('accounting.penggajian.bank'),
                'is_required' => false,
                'description' => 'Akun bank yang akan dikredit saat penggajian transfer'
            ],

            // Penjualan
            [
                'transaction_type' => 'penjualan',
                'account_key' => 'piutang_usaha',
                'account_name' => 'Piutang Usaha',
                'akun_id' => config('accounting.penjualan.piutang_usaha'),
                'is_required' => true,
                'description' => 'Akun piutang usaha yang akan didebit saat penjualan kredit'
            ],
            [
                'transaction_type' => 'penjualan',
                'account_key' => 'penjualan',
                'account_name' => 'Penjualan',
                'akun_id' => config('accounting.penjualan.pendapatan_penjualan'),
                'is_required' => true,
                'description' => 'Akun penjualan yang akan dikredit saat penjualan'
            ],
            [
                'transaction_type' => 'penjualan',
                'account_key' => 'ppn_keluaran',
                'account_name' => 'PPN Keluaran',
                'akun_id' => config('accounting.penjualan.ppn_keluaran'),
                'is_required' => false,
                'description' => 'Akun PPN keluaran yang akan dikredit saat penjualan dengan PPN'
            ],

            // Pembelian
            [
                'transaction_type' => 'pembelian',
                'account_key' => 'pembelian',
                'account_name' => 'Pembelian',
                'akun_id' => config('accounting.pembelian.pembelian'),
                'is_required' => true,
                'description' => 'Akun pembelian yang akan didebit saat pembelian untuk keperluan laporan HPP'
            ],
            [
                'transaction_type' => 'pembelian',
                'account_key' => 'persediaan',
                'account_name' => 'Persediaan Barang Dagang',
                'akun_id' => config('accounting.pembelian.persediaan'),
                'is_required' => true,
                'description' => 'Akun persediaan yang akan didebit saat pembelian'
            ],
            [
                'transaction_type' => 'pembelian',
                'account_key' => 'ppn_masuk',
                'account_name' => 'PPN Masukan',
                'akun_id' => config('accounting.pembelian.ppn_masuk'),
                'is_required' => false,
                'description' => 'Akun PPN masukan yang akan didebit saat pembelian'
            ],
            [
                'transaction_type' => 'pembelian',
                'account_key' => 'hutang_usaha',
                'account_name' => 'Hutang Usaha',
                'akun_id' => config('accounting.pembelian.hutang_usaha'),
                'is_required' => true,
                'description' => 'Akun hutang usaha yang akan dikredit saat pembelian kredit'
            ],

            // Pembayaran Piutang
            [
                'transaction_type' => 'pembayaran_piutang',
                'account_key' => 'piutang_usaha',
                'account_name' => 'Piutang Usaha',
                'akun_id' => config('accounting.pembayaran_piutang.piutang_usaha'),
                'is_required' => true,
                'description' => 'Akun piutang usaha yang akan dikredit saat penerimaan pembayaran'
            ],
            [
                'transaction_type' => 'pembayaran_piutang',
                'account_key' => 'kas',
                'account_name' => 'Kas',
                'akun_id' => config('accounting.pembayaran_piutang.kas'),
                'is_required' => false,
                'description' => 'Akun kas yang akan didebit saat penerimaan pembayaran tunai'
            ],
            [
                'transaction_type' => 'pembayaran_piutang',
                'account_key' => 'bank',
                'account_name' => 'Bank',
                'akun_id' => config('accounting.pembayaran_piutang.bank'),
                'is_required' => false,
                'description' => 'Akun bank yang akan didebit saat penerimaan pembayaran transfer'
            ],

            // Uang Muka Penjualan
            [
                'transaction_type' => 'uang_muka_penjualan',
                'account_key' => 'hutang_uang_muka_penjualan',
                'account_name' => 'Hutang Uang Muka Penjualan',
                'akun_id' => config('accounting.uang_muka_penjualan.hutang_uang_muka_penjualan'),
                'is_required' => true,
                'description' => 'Akun hutang uang muka penjualan yang akan dikredit saat penerimaan uang muka dan didebit saat aplikasi ke invoice'
            ],

            // Saldo Awal (Opening Balance)
            [
                'transaction_type' => 'saldo_awal',
                'account_key' => 'modal_pemilik',
                'account_name' => 'Modal Pemilik / Ekuitas',
                'akun_id' => config('accounting.saldo_awal.modal_pemilik'),
                'is_required' => true,
                'description' => 'Akun modal/ekuitas yang akan dikredit saat membuat jurnal pembukaan saldo awal kas/bank'
            ],

            // Header/Parent untuk Auto-Create COA
            [
                'transaction_type' => 'header',
                'account_key' => 'kas_bank',
                'account_name' => 'Header Kas & Bank',
                'akun_id' => config('accounting.headers.kas_bank'),
                'is_required' => true,
                'description' => 'Akun header yang menjadi parent untuk semua akun kas dan bank yang dibuat otomatis'
            ],
        ];

        foreach ($configurations as $config) {
            \App\Models\AccountingConfiguration::create($config);
        }
    }
}