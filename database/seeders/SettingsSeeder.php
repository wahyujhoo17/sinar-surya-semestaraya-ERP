<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Pengaturan perusahaan
            [
                'key' => 'company_name',
                'value' => 'PT. Sinar Surya Semestaraya',
                'group' => 'company',
                'description' => 'Nama perusahaan'
            ],
            [
                'key' => 'company_address',
                'value' => 'Jl. Contoh No. 123, Jakarta',
                'group' => 'company',
                'description' => 'Alamat perusahaan'
            ],
            [
                'key' => 'company_phone',
                'value' => '021-12345678',
                'group' => 'company',
                'description' => 'Nomor telepon perusahaan'
            ],
            [
                'key' => 'company_email',
                'value' => 'info@sinar-surya.com',
                'group' => 'company',
                'description' => 'Email perusahaan'
            ],
            [
                'key' => 'company_logo',
                'value' => 'logo.png',
                'group' => 'company',
                'description' => 'Logo perusahaan'
            ],
            [
                'key' => 'company_npwp',
                'value' => '01.234.567.8-901.000',
                'group' => 'company',
                'description' => 'NPWP perusahaan'
            ],
            
            // Pengaturan aplikasi
            [
                'key' => 'tax_percentage',
                'value' => '11',
                'group' => 'application',
                'description' => 'Persentase pajak (PPN)'
            ],
            [
                'key' => 'default_currency',
                'value' => 'IDR',
                'group' => 'application',
                'description' => 'Mata uang default'
            ],
            [
                'key' => 'date_format',
                'value' => 'd/m/Y',
                'group' => 'application',
                'description' => 'Format tanggal'
            ],
            [
                'key' => 'decimal_separator',
                'value' => ',',
                'group' => 'application',
                'description' => 'Pemisah desimal'
            ],
            [
                'key' => 'thousand_separator',
                'value' => '.',
                'group' => 'application',
                'description' => 'Pemisah ribuan'
            ],
            
            // Pengaturan dokumen
            [
                'key' => 'quotation_prefix',
                'value' => 'QT',
                'group' => 'document',
                'description' => 'Prefix nomor quotation'
            ],
            [
                'key' => 'sales_order_prefix',
                'value' => 'SO',
                'group' => 'document',
                'description' => 'Prefix nomor sales order'
            ],
            [
                'key' => 'purchase_request_prefix',
                'value' => 'PR',
                'group' => 'document',
                'description' => 'Prefix nomor purchase request'
            ],
            [
                'key' => 'purchase_order_prefix',
                'value' => 'PO',
                'group' => 'document',
                'description' => 'Prefix nomor purchase order'
            ],
            [
                'key' => 'delivery_order_prefix',
                'value' => 'DO',
                'group' => 'document',
                'description' => 'Prefix nomor delivery order'
            ],
            [
                'key' => 'invoice_prefix',
                'value' => 'INV',
                'group' => 'document',
                'description' => 'Prefix nomor invoice'
            ],
            [
                'key' => 'quotation_terms',
                'value' => "1. Penawaran berlaku selama 30 hari.\n2. Pembayaran 50% di muka, 50% setelah barang diterima.\n3. Pengiriman dilakukan setelah pembayaran pertama diterima.",
                'group' => 'document',
                'description' => 'Syarat dan ketentuan default untuk quotation'
            ]
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}