# Template Settings Integration Guide

## Overview

Semua template PDF dan print telah diintegrasikan dengan sistem pengaturan umum untuk menggunakan informasi perusahaan yang dinamis.

## Template yang Telah Diupdate

### 1. Template PDF Penjualan

-   ✅ **Sales Order PDF** (`resources/views/penjualan/sales-order/pdf.blade.php`)
-   ✅ **Quotation PDF** (`resources/views/penjualan/quotation/pdf.blade.php`)
-   ✅ **Invoice Print** (`resources/views/penjualan/invoice/print.blade.php`)
-   ✅ **Delivery Order Print** (`resources/views/penjualan/delivery-order/print.blade.php`)
-   ✅ **Retur Penjualan PDF** (`resources/views/penjualan/retur_penjualan/pdf.blade.php`)
-   ✅ **Nota Kredit PDF** (`resources/views/penjualan/nota_kredit/pdf.blade.php`)

### 2. Template PDF Pembelian

-   ✅ **Purchase Order PDF** (`resources/views/pembelian/purchase_order/pdf.blade.php`)
-   ✅ **Permintaan Pembelian PDF** (`resources/views/pembelian/permintaan_pembelian/pdf.blade.php`)

### 3. Template PDF Inventaris

-   ✅ **Penyesuaian Stok PDF** (`resources/views/inventaris/penyesuaian_stok/pdf.blade.php`)

### 4. Layout dan Komponen

-   ✅ **Sidebar** (`resources/views/layouts/sidebar.blade.php`)
-   ✅ **Master Data Pelanggan Index** (`resources/views/master-data/pelanggan/index.blade.php`)

## Helper Functions yang Tersedia

### 1. Basic Setting Function

```php
// Mengambil setting berdasarkan key
setting('company_name', 'Default Value')
```

### 2. Company Information Helper

```php
// Mengambil informasi perusahaan lengkap
$company = company_info();
/*
Returns:
[
    'name' => 'PT. SINAR SURYA SEMESTARAYA',
    'address' => 'Jl. Condet Raya No. 6 Balekambang',
    'city' => 'Jakarta Timur',
    'postal_code' => '13530',
    'phone' => '(021) 80876624 - 80876642',
    'email' => 'admin@kliksinarsurya.com',
    'email_2' => 'sinar.surya@hotmail.com',
    'email_3' => 'sinarsurya.sr@gmail.com',
    'website' => 'https://sinar-surya.com',
    'logo_path' => 'storage/company_logo.png'
]
*/
```

### 3. Formatted Address Helper

```php
// Alamat perusahaan dalam satu baris
company_address_line()
// Returns: "Jl. Condet Raya No. 6 Balekambang, Jakarta Timur 13530"
```

### 4. Contact Information Helper

```php
// Informasi kontak perusahaan
$contact = company_contact_info();
/*
Returns:
[
    'phone' => '(021) 80876624 - 80876642',
    'emails' => ['admin@kliksinarsurya.com', 'sinar.surya@hotmail.com'],
    'website' => 'https://sinar-surya.com'
]
*/
```

## Penggunaan di Template

### Di Template PDF

```php
<!-- Watermark Background -->
<div class="watermark-bg">{{ strtoupper(setting('company_name', 'SINAR SURYA SEMESTARAYA')) }}</div>

<!-- Company Information Section -->
<div class="section-title">Info Perusahaan</div>
<div style="padding: 5px;">
    <strong>{{ setting('company_name', 'PT. SINAR SURYA SEMESTARAYA') }}</strong><br>
    {{ setting('company_address', 'Jl. Condet Raya No. 6 Balekambang') }}<br>
    {{ setting('company_city', 'Jakarta Timur') }} {{ setting('company_postal_code', '13530') }}<br>
    Telp. {{ setting('company_phone', '(021) 80876624 - 80876642') }}<br>
    E-mail: {{ setting('company_email', 'admin@kliksinarsurya.com') }}<br>
    @if(setting('company_email_2')){{ setting('company_email_2') }}<br>@endif
    @if(setting('company_email_3')){{ setting('company_email_3') }}@endif
</div>

<!-- Footer -->
<div class="footer-text">
    <p>Dokumen ini dicetak secara digital pada {{ now()->format('d M Y, H:i') }} WIB | {{ setting('company_name', 'PT. SINAR SURYA SEMESTARAYA') }}</p>
</div>
```

### Di Template Blade Regular

```php
<!-- Company Name in Page Title -->
<h1>Master Data Pelanggan - {{ setting('company_name', 'PT Sinar Surya Semestaraya') }}</h1>

<!-- Company Information Display -->
<div class="company-info">
    <p>{{ setting('company_name') }}</p>
    <p>{{ company_address_line() }}</p>
    <p>{{ setting('company_phone') }}</p>
</div>
```

## Settings Keys yang Digunakan

### Company Information

-   `company_name` - Nama perusahaan
-   `company_address` - Alamat perusahaan
-   `company_city` - Kota
-   `company_postal_code` - Kode pos
-   `company_phone` - Nomor telepon
-   `company_email` - Email utama
-   `company_email_2` - Email kedua
-   `company_email_3` - Email ketiga
-   `company_website` - Website perusahaan
-   `company_logo` - Logo perusahaan

### Application Settings

-   `app_version` - Versi aplikasi

## Manfaat Integrasi Settings

### 1. **Centralized Management**

-   Semua informasi perusahaan dikelola dari satu tempat
-   Mudah diupdate tanpa harus edit banyak file

### 2. **Consistency**

-   Informasi perusahaan konsisten di seluruh dokumen
-   Tidak ada lagi informasi yang berbeda-beda

### 3. **Flexibility**

-   Mudah mengubah informasi untuk keperluan demo atau testing
-   Support multiple environment dengan setting berbeda

### 4. **Professional**

-   Dokumen PDF terlihat lebih profesional dan konsisten
-   Logo dan informasi perusahaan dapat disesuaikan

## Testing

Untuk menguji integrasi settings:

1. **Update Company Information**

    - Masuk ke halaman Pengaturan Umum
    - Update informasi perusahaan
    - Generate PDF dari berbagai modul

2. **Check Consistency**

    - Pastikan semua PDF menggunakan informasi yang sama
    - Verifikasi tidak ada hard-coded information yang tertinggal

3. **Logo Testing**
    - Upload logo perusahaan
    - Pastikan logo muncul di template yang support gambar

## Next Steps

1. **Add Logo Integration**

    - Integrate company logo dalam template PDF
    - Support untuk upload dan preview logo

2. **Email Template Integration**

    - Integrate settings dengan email templates
    - Dynamic email signatures

3. **Additional Company Settings**
    - NPWP perusahaan
    - Nomor izin usaha
    - Informasi bank untuk invoice

## Troubleshooting

### Helper Function Not Found

Pastikan `composer dump-autoload` sudah dijalankan setelah menambahkan helper functions.

### Settings Not Loading

Pastikan database settings sudah di-seed dengan menjalankan:

```bash
php artisan db:seed --class=SettingsSeeder
```

### PDF Not Updating

Clear cache dan pastikan PDF engine dapat mengakses helper functions:

```bash
php artisan config:clear
php artisan view:clear
```
