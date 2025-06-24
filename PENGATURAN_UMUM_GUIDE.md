# Pengaturan Umum ERP Sinar Surya

## Overview

Halaman Pengaturan Umum telah dibuat untuk mengelola konfigurasi sistem ERP Sinar Surya. Pengaturan dibagi menjadi 4 kategori utama:

## 1. Informasi Perusahaan

Berisi data dasar perusahaan yang akan muncul di berbagai dokumen dan laporan:

-   **Nama Perusahaan**: Nama resmi perusahaan
-   **Alamat**: Alamat lengkap perusahaan
-   **Telepon & Email**: Kontak perusahaan
-   **NPWP**: Nomor Pokok Wajib Pajak
-   **Website**: URL website perusahaan
-   **Logo**: Upload logo perusahaan (PNG, JPG, GIF max 2MB)

## 2. Pengaturan Aplikasi

Konfigurasi teknis aplikasi:

-   **Persentase Pajak (PPN)**: Default 11%, dapat disesuaikan
-   **Mata Uang**: IDR, USD, EUR
-   **Format Tanggal**: DD/MM/YYYY, MM/DD/YYYY, YYYY-MM-DD, DD-MM-YYYY
-   **Zona Waktu**: WIB, WITA, WIT
-   **Pemisah Desimal & Ribuan**: Koma/titik untuk formatting angka
-   **Bahasa Sistem**: Indonesia/English
-   **Item per Halaman**: 10, 15, 25, 50, 100

## 3. Pengaturan Dokumen

Konfigurasi untuk dokumen bisnis:

### Prefix Nomor Dokumen:

-   Quotation (default: QT)
-   Sales Order (default: SO)
-   Purchase Request (default: PR)
-   Purchase Order (default: PO)
-   Delivery Order (default: DO)
-   Invoice (default: INV)

### Syarat dan Ketentuan:

-   Template default untuk Quotation
-   Template default untuk Invoice
-   Footer untuk Invoice

## 4. Pengaturan Sistem

Konfigurasi keamanan dan sistem:

-   **Waktu Sesi**: 5-1440 menit (default: 120)
-   **Maksimal Percobaan Login**: 3-10 kali (default: 5)
-   **Frekuensi Backup**: Harian/Mingguan/Bulanan
-   **Toggle Fitur**:
    -   Notifikasi Sistem
    -   Notifikasi Email
    -   Audit Log

## Akses & Route

-   **URL**: `/pengaturan/umum`
-   **Route Name**: `pengaturan.umum`
-   **Controller**: `App\Http\Controllers\Pengaturan\PengaturanUmumController`
-   **Permission**: Sesuai hak akses yang sudah dikonfigurasi

## Helper Functions

### Setting Helper

```php
// Mengambil nilai setting tunggal
$companyName = setting('company_name', 'Default Company');

// Mengambil setting berdasarkan group
$companySettings = settings('company');

// Shortcut untuk company settings
$companyName = company_setting('name');

// Format currency berdasarkan setting
$formatted = format_currency(1000000); // Output: "Rp 1.000.000,00"

// Format tanggal berdasarkan setting
$formatted = format_date_setting(now()); // Output sesuai format yang dipilih

// Cek prefix dokumen
$prefix = get_document_prefix('invoice'); // Output: "INV"

// Cek fitur yang aktif
$isEnabled = is_feature_enabled('notifications'); // true/false
```

## Integrasi dengan Sistem

### 1. Dokumen PDF

Logo dan info perusahaan otomatis digunakan di:

-   Invoice
-   Quotation
-   Sales Order
-   Purchase Order
-   Laporan

### 2. Format Angka

Semua tampilan currency menggunakan setting:

-   Dashboard
-   Laporan keuangan
-   Dokumen transaksi

### 3. Format Tanggal

Konsisten di seluruh sistem:

-   Tabel data
-   Form input
-   Laporan
-   Notifikasi

### 4. Prefix Dokumen

Auto-generate nomor dokumen:

-   QT-001, QT-002 (Quotation)
-   SO-001, SO-002 (Sales Order)
-   INV-001, INV-002 (Invoice)

## File Structure

```
app/
├── Models/Setting.php (Model untuk settings)
├── Http/Controllers/Pengaturan/PengaturanUmumController.php
└── Helpers/SettingsHelper.php (Helper functions)

resources/views/pengaturan/pengaturan-umum/
└── index.blade.php (Interface utama)

database/
├── migrations/2023_04_25_create_settings_table.php
└── seeders/SettingsSeeder.php (Data default)
```

## Keamanan

-   Validasi input lengkap
-   Upload file aman (image only, max 2MB)
-   CSRF protection
-   XSS protection via Blade templating

## UI/UX Features

-   Responsive design (mobile-friendly)
-   Dark mode support
-   Tab navigation dengan URL hash
-   Loading states
-   Form validation real-time
-   File upload preview

## Testing

Untuk testing fitur ini:

1. **Akses halaman**: `/pengaturan/umum`
2. **Test tab navigation**: Klik antar tab, URL hash berubah
3. **Test form submission**: Ubah setting, submit, cek apakah tersimpan
4. **Test file upload**: Upload logo, cek preview dan storage
5. **Test helper functions**: Panggil di controller/view lain

## Troubleshooting

### Issue: Settings tidak tersimpan

**Solusi**:

-   Cek database connection
-   Pastikan tabel `settings` ada
-   Jalankan seeder: `php artisan db:seed --class=SettingsSeeder`

### Issue: Helper functions tidak dikenali

**Solusi**:

-   Jalankan: `composer dump-autoload`
-   Cek file `composer.json` ada entry helpers

### Issue: Logo tidak muncul

**Solusi**:

-   Cek folder `storage/app/public/logos` ada dan writable
-   Jalankan: `php artisan storage:link`

## Pengembangan Lanjutan

### Menambah Setting Baru:

1. Tambah ke `SettingsSeeder.php`
2. Update controller getter method
3. Tambah ke view form
4. Update validation rules
5. Jalankan seeder

### Menambah Helper Function:

1. Edit `app/Helpers/SettingsHelper.php`
2. Jalankan `composer dump-autoload`
3. Test di controller/view

### Menambah Kategori Baru:

1. Tambah tab di view
2. Update controller dengan getter method baru
3. Tambah validation rules
4. Update Alpine.js data

## Performance Tips

-   Settings di-cache otomatis oleh Laravel
-   Helper functions efficient (tidak query berulang)
-   Use `setting()` helper daripada query langsung
-   Logo di-optimize untuk web (compress sebelum upload)
