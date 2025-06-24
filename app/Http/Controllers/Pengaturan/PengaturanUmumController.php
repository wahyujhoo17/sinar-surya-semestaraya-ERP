<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PengaturanUmumController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['name' => 'Pengaturan', 'url' => '#'],
            ['name' => 'Pengaturan Umum', 'url' => null]
        ];

        $currentPage = 'Pengaturan Umum';

        // Ambil semua pengaturan yang dikelompokkan
        $settings = Setting::getAllGrouped();

        // Siapkan data pengaturan dengan nilai default jika tidak ada
        $companySettings = $this->getCompanySettings($settings);
        $applicationSettings = $this->getApplicationSettings($settings);
        $documentSettings = $this->getDocumentSettings($settings);
        $systemSettings = $this->getSystemSettings($settings);

        return view('pengaturan.pengaturan-umum.index', compact(
            'breadcrumbs',
            'currentPage',
            'companySettings',
            'applicationSettings',
            'documentSettings',
            'systemSettings'
        ));
    }

    public function update(Request $request)
    {
        Log::info('Pengaturan Umum Update Request Started', [
            'request_data' => $request->all(),
            'files' => $request->hasFile('company_logo') ? 'has_logo_file' : 'no_logo_file'
        ]);

        $request->validate([
            // Company Settings
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string',
            'company_phone' => 'required|string|max:20',
            'company_email' => 'required|email|max:255',
            'company_email_2' => 'nullable|email|max:255',
            'company_email_3' => 'nullable|email|max:255',
            'company_city' => 'required|string|max:100',
            'company_postal_code' => 'required|string|max:10',
            'company_npwp' => 'nullable|string|max:30',
            'company_website' => 'nullable|url|max:255',

            // Application Settings
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'default_currency' => 'required|string|max:10',
            'date_format' => 'required|string|max:20',
            'decimal_separator' => 'required|string|max:1',
            'thousand_separator' => 'required|string|max:1',
            'timezone' => 'required|string|max:50',
            'language' => 'required|string|max:10',
            'items_per_page' => 'required|integer|min:5|max:100',

            // Document Settings
            'quotation_prefix' => 'required|string|max:10',
            'sales_order_prefix' => 'required|string|max:10',
            'purchase_request_prefix' => 'required|string|max:10',
            'purchase_order_prefix' => 'required|string|max:10',
            'delivery_order_prefix' => 'required|string|max:10',
            'invoice_prefix' => 'required|string|max:10',
            'quotation_terms' => 'nullable|string',
            'invoice_terms' => 'nullable|string',
            'invoice_footer' => 'nullable|string',

            // System Settings
            'session_lifetime' => 'required|integer|min:5|max:1440',
            'backup_frequency' => 'required|string|in:daily,weekly,monthly',
            'enable_notifications' => 'nullable|in:on',
            'enable_email_notifications' => 'nullable|in:on',
            'enable_multi_currency' => 'nullable|in:on',
            'enable_barcode' => 'nullable|in:on',
            'enable_auto_backup' => 'nullable|in:on',
            'debug_mode' => 'nullable|in:on',
            'enable_audit_log' => 'nullable|in:on',
            'max_login_attempts' => 'required|integer|min:3|max:10',

            // Logo upload
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        Log::info('Validation passed successfully');

        DB::beginTransaction();
        try {
            Log::info('Starting database transaction for settings update');

            // Handle logo upload
            if ($request->hasFile('company_logo')) {
                Log::info('Processing logo upload');
                $logoPath = $request->file('company_logo')->store('logos', 'public');
                Setting::setValue('company_logo', $logoPath, 'company', 'Logo perusahaan');
                Log::info('Logo uploaded and saved', ['path' => $logoPath]);
            }

            // Company Settings
            $companySettings = [
                'company_name' => ['value' => $request->company_name, 'desc' => 'Nama perusahaan'],
                'company_address' => ['value' => $request->company_address, 'desc' => 'Alamat perusahaan'],
                'company_phone' => ['value' => $request->company_phone, 'desc' => 'Nomor telepon perusahaan'],
                'company_email' => ['value' => $request->company_email, 'desc' => 'Email perusahaan'],
                'company_email_2' => ['value' => $request->company_email_2, 'desc' => 'Email perusahaan kedua'],
                'company_email_3' => ['value' => $request->company_email_3, 'desc' => 'Email perusahaan ketiga'],
                'company_city' => ['value' => $request->company_city, 'desc' => 'Kota perusahaan'],
                'company_postal_code' => ['value' => $request->company_postal_code, 'desc' => 'Kode pos perusahaan'],
                'company_npwp' => ['value' => $request->company_npwp, 'desc' => 'NPWP perusahaan'],
                'company_website' => ['value' => $request->company_website, 'desc' => 'Website perusahaan'],
            ];

            foreach ($companySettings as $key => $data) {
                Setting::setValue($key, $data['value'], 'company', $data['desc']);
            }
            Log::info('Company settings updated');

            // Application Settings
            $applicationSettings = [
                'tax_percentage' => ['value' => $request->tax_percentage, 'desc' => 'Persentase pajak (PPN)'],
                'default_currency' => ['value' => $request->default_currency, 'desc' => 'Mata uang default'],
                'date_format' => ['value' => $request->date_format, 'desc' => 'Format tanggal'],
                'decimal_separator' => ['value' => $request->decimal_separator, 'desc' => 'Pemisah desimal'],
                'thousand_separator' => ['value' => $request->thousand_separator, 'desc' => 'Pemisah ribuan'],
                'timezone' => ['value' => $request->timezone, 'desc' => 'Zona waktu'],
                'language' => ['value' => $request->language, 'desc' => 'Bahasa sistem'],
                'items_per_page' => ['value' => $request->items_per_page, 'desc' => 'Jumlah item per halaman'],
            ];

            foreach ($applicationSettings as $key => $data) {
                Setting::setValue($key, $data['value'], 'application', $data['desc']);
            }
            Log::info('Application settings updated');

            // Document Settings
            $documentSettings = [
                'quotation_prefix' => ['value' => $request->quotation_prefix, 'desc' => 'Prefix nomor quotation'],
                'sales_order_prefix' => ['value' => $request->sales_order_prefix, 'desc' => 'Prefix nomor sales order'],
                'purchase_request_prefix' => ['value' => $request->purchase_request_prefix, 'desc' => 'Prefix nomor purchase request'],
                'purchase_order_prefix' => ['value' => $request->purchase_order_prefix, 'desc' => 'Prefix nomor purchase order'],
                'delivery_order_prefix' => ['value' => $request->delivery_order_prefix, 'desc' => 'Prefix nomor delivery order'],
                'invoice_prefix' => ['value' => $request->invoice_prefix, 'desc' => 'Prefix nomor invoice'],
                'quotation_terms' => ['value' => $request->quotation_terms, 'desc' => 'Syarat dan ketentuan quotation'],
                'invoice_terms' => ['value' => $request->invoice_terms, 'desc' => 'Syarat dan ketentuan invoice'],
                'invoice_footer' => ['value' => $request->invoice_footer, 'desc' => 'Footer invoice'],
            ];

            foreach ($documentSettings as $key => $data) {
                Setting::setValue($key, $data['value'], 'document', $data['desc']);
            }
            Log::info('Document settings updated');

            // System Settings
            $systemSettings = [
                'session_lifetime' => ['value' => $request->session_lifetime, 'desc' => 'Waktu sesi (menit)'],
                'backup_frequency' => ['value' => $request->backup_frequency, 'desc' => 'Frekuensi backup'],
                'enable_notifications' => ['value' => $request->has('enable_notifications') ? '1' : '0', 'desc' => 'Aktifkan notifikasi'],
                'enable_email_notifications' => ['value' => $request->has('enable_email_notifications') ? '1' : '0', 'desc' => 'Aktifkan notifikasi email'],
                'enable_multi_currency' => ['value' => $request->has('enable_multi_currency') ? '1' : '0', 'desc' => 'Aktifkan multi mata uang'],
                'enable_barcode' => ['value' => $request->has('enable_barcode') ? '1' : '0', 'desc' => 'Aktifkan barcode/QR code'],
                'enable_auto_backup' => ['value' => $request->has('enable_auto_backup') ? '1' : '0', 'desc' => 'Aktifkan auto backup'],
                'debug_mode' => ['value' => $request->has('debug_mode') ? '1' : '0', 'desc' => 'Mode debug'],
                'enable_audit_log' => ['value' => $request->has('enable_audit_log') ? '1' : '0', 'desc' => 'Aktifkan audit log'],
                'max_login_attempts' => ['value' => $request->max_login_attempts, 'desc' => 'Maksimal percobaan login'],
            ];

            foreach ($systemSettings as $key => $data) {
                Setting::setValue($key, $data['value'], 'system', $data['desc']);
            }
            Log::info('System settings updated');

            DB::commit();

            Log::info('Settings updated successfully');

            return redirect()->route('pengaturan.umum')
                ->with('success', 'Pengaturan umum berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function getCompanySettings($settings)
    {
        $company = $settings->get('company', collect());
        $companySettings = $company->pluck('value', 'key');

        return [
            'company_name' => $companySettings->get('company_name', 'PT. Sinar Surya Semestaraya'),
            'company_address' => $companySettings->get('company_address', 'Jl. Contoh No. 123, Jakarta'),
            'company_phone' => $companySettings->get('company_phone', '021-12345678'),
            'company_email' => $companySettings->get('company_email', 'info@sinar-surya.com'),
            'company_email_2' => $companySettings->get('company_email_2', ''),
            'company_email_3' => $companySettings->get('company_email_3', ''),
            'company_city' => $companySettings->get('company_city', 'Jakarta'),
            'company_postal_code' => $companySettings->get('company_postal_code', '12345'),
            'company_logo' => $companySettings->get('company_logo', 'logo.png'),
            'company_npwp' => $companySettings->get('company_npwp', '01.234.567.8-901.000'),
            'company_website' => $companySettings->get('company_website', 'https://sinar-surya.com'),
        ];
    }

    private function getApplicationSettings($settings)
    {
        $application = $settings->get('application', collect());
        $applicationSettings = $application->pluck('value', 'key');

        return [
            'tax_percentage' => $applicationSettings->get('tax_percentage', '11'),
            'default_currency' => $applicationSettings->get('default_currency', 'IDR'),
            'date_format' => $applicationSettings->get('date_format', 'd/m/Y'),
            'decimal_separator' => $applicationSettings->get('decimal_separator', ','),
            'thousand_separator' => $applicationSettings->get('thousand_separator', '.'),
            'timezone' => $applicationSettings->get('timezone', 'Asia/Jakarta'),
            'language' => $applicationSettings->get('language', 'id'),
            'items_per_page' => $applicationSettings->get('items_per_page', '15'),
        ];
    }

    private function getDocumentSettings($settings)
    {
        $document = $settings->get('document', collect());
        $documentSettings = $document->pluck('value', 'key');

        return [
            'quotation_prefix' => $documentSettings->get('quotation_prefix', 'QT'),
            'sales_order_prefix' => $documentSettings->get('sales_order_prefix', 'SO'),
            'purchase_request_prefix' => $documentSettings->get('purchase_request_prefix', 'PR'),
            'purchase_order_prefix' => $documentSettings->get('purchase_order_prefix', 'PO'),
            'delivery_order_prefix' => $documentSettings->get('delivery_order_prefix', 'DO'),
            'invoice_prefix' => $documentSettings->get('invoice_prefix', 'INV'),
            'quotation_terms' => $documentSettings->get('quotation_terms', "1. Penawaran berlaku selama 30 hari.\n2. Pembayaran 50% di muka, 50% setelah barang diterima.\n3. Pengiriman dilakukan setelah pembayaran pertama diterima."),
            'invoice_terms' => $documentSettings->get('invoice_terms', "1. Pembayaran paling lambat 30 hari setelah tanggal invoice.\n2. Keterlambatan pembayaran dikenakan denda 2% per bulan.\n3. Barang yang sudah dibeli tidak dapat dikembalikan."),
            'invoice_footer' => $documentSettings->get('invoice_footer', "Terima kasih atas kepercayaan Anda kepada kami."),
        ];
    }

    private function getSystemSettings($settings)
    {
        $system = $settings->get('system', collect());
        $systemSettings = $system->pluck('value', 'key');

        return [
            'session_lifetime' => $systemSettings->get('session_lifetime', '120'),
            'backup_frequency' => $systemSettings->get('backup_frequency', 'daily'),
            'enable_notifications' => $systemSettings->get('enable_notifications', '1'),
            'enable_email_notifications' => $systemSettings->get('enable_email_notifications', '1'),
            'enable_multi_currency' => $systemSettings->get('enable_multi_currency', '0'),
            'enable_barcode' => $systemSettings->get('enable_barcode', '1'),
            'enable_auto_backup' => $systemSettings->get('enable_auto_backup', '1'),
            'debug_mode' => $systemSettings->get('debug_mode', '0'),
            'enable_audit_log' => $systemSettings->get('enable_audit_log', '1'),
            'max_login_attempts' => $systemSettings->get('max_login_attempts', '5'),
        ];
    }
}
