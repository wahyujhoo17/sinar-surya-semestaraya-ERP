<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

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
            [
                'key' => 'company_email_2',
                'value' => 'sinar.surya@hotmail.com',
                'group' => 'company',
                'description' => 'Email perusahaan kedua'
            ],
            [
                'key' => 'company_email_3',
                'value' => 'sinarsurya.sr@gmail.com',
                'group' => 'company',
                'description' => 'Email perusahaan ketiga'
            ],
            [
                'key' => 'company_website',
                'value' => 'https://kliksinarsurya.com',
                'group' => 'company',
                'description' => 'Website perusahaan'
            ],
            [
                'key' => 'company_city',
                'value' => 'Jakarta',
                'group' => 'company',
                'description' => 'Kota perusahaan'
            ],
            [
                'key' => 'company_postal_code',
                'value' => '12345',
                'group' => 'company',
                'description' => 'Kode pos perusahaan'
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
            [
                'key' => 'app_version',
                'value' => '1.0.0',
                'group' => 'application',
                'description' => 'Versi aplikasi'
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
            ],

            // Additional company settings
            [
                'key' => 'company_website',
                'value' => 'https://sinar-surya.com',
                'group' => 'company',
                'description' => 'Website perusahaan'
            ],

            // Additional application settings
            [
                'key' => 'timezone',
                'value' => 'Asia/Jakarta',
                'group' => 'application',
                'description' => 'Zona waktu'
            ],
            [
                'key' => 'language',
                'value' => 'id',
                'group' => 'application',
                'description' => 'Bahasa sistem'
            ],
            [
                'key' => 'items_per_page',
                'value' => '15',
                'group' => 'application',
                'description' => 'Jumlah item per halaman'
            ],

            // Additional document settings
            [
                'key' => 'invoice_terms',
                'value' => "1. Pembayaran paling lambat 30 hari setelah tanggal invoice.\n2. Keterlambatan pembayaran dikenakan denda 2% per bulan.\n3. Barang yang sudah dibeli tidak dapat dikembalikan.",
                'group' => 'document',
                'description' => 'Syarat dan ketentuan default untuk invoice'
            ],
            [
                'key' => 'invoice_footer',
                'value' => 'Terima kasih atas kepercayaan Anda kepada kami.',
                'group' => 'document',
                'description' => 'Footer default untuk invoice'
            ],

            // System settings
            [
                'key' => 'session_lifetime',
                'value' => '120',
                'group' => 'system',
                'description' => 'Waktu sesi dalam menit'
            ],
            [
                'key' => 'backup_frequency',
                'value' => 'daily',
                'group' => 'system',
                'description' => 'Frekuensi backup sistem'
            ],
            [
                'key' => 'enable_notifications',
                'value' => '1',
                'group' => 'system',
                'description' => 'Aktifkan notifikasi sistem'
            ],
            [
                'key' => 'enable_email_notifications',
                'value' => '1',
                'group' => 'system',
                'description' => 'Aktifkan notifikasi email'
            ],
            [
                'key' => 'enable_audit_log',
                'value' => '1',
                'group' => 'system',
                'description' => 'Aktifkan audit log'
            ],
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'group' => 'system',
                'description' => 'Maksimal percobaan login'
            ],
            [
                'key' => 'enable_multi_currency',
                'value' => '0',
                'group' => 'system',
                'description' => 'Aktifkan multi mata uang'
            ],
            [
                'key' => 'enable_barcode',
                'value' => '1',
                'group' => 'system',
                'description' => 'Aktifkan barcode/QR code'
            ],
            [
                'key' => 'enable_auto_backup',
                'value' => '1',
                'group' => 'system',
                'description' => 'Aktifkan auto backup'
            ],
            [
                'key' => 'debug_mode',
                'value' => '0',
                'group' => 'system',
                'description' => 'Mode debug'
            ],

            // Bank settings
            [
                'key' => 'enabled_bank_accounts',
                'value' => '[]',
                'group' => 'bank',
                'description' => 'Bank rekening yang diaktifkan untuk invoice'
            ],
            [
                'key' => 'primary_bank_account',
                'value' => '',
                'group' => 'bank',
                'description' => 'Bank rekening utama untuk invoice'
            ]
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'group' => $setting['group'],
                    'description' => $setting['description']
                ]
            );
        }
    }
}
