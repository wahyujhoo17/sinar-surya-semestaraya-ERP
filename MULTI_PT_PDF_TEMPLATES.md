# Dokumentasi Template PDF Multi-PT untuk Quotation

## Gambaran Umum

Implementasi ini menambahkan dukungan untuk 3 template PDF berbeda untuk sistem quotation:

1. **PT Sinar Surya Semestaraya** (Default) - Template existing
2. **PT Indo Atsaka Industri** (Baru) - Template modern dengan desain profesional
3. **PT Hidayah Cahaya Berkah** (Baru) - Template elegan dengan nuansa Islami

## Fitur Utama

### 1. Dropdown Pilihan Template

-   Button "Download PDF" di halaman detail quotation sekarang memiliki dropdown
-   User dapat memilih template mana yang ingin digunakan
-   Setiap template memiliki ikon dan deskripsi yang berbeda

### 2. Template Design

#### Template Sinar Surya (Default)

-   File: `resources/views/penjualan/quotation/pdf.blade.php`
-   Desain: Clean dan professional dengan logo SS
-   Warna utama: Biru (#4a6fa5)
-   Watermark: "SINAR SURYA SEMESTARAYA"

#### Template Indo Atsaka

-   File: `resources/views/penjualan/quotation/pdf-indo-atsaka.blade.php`
-   Desain: Modern dengan gradient merah-oranye
-   Warna utama: Merah (#e74c3c)
-   Logo: PT Indo Atsaka Industri-26.png
-   Watermark: "PT INDO ATSAKA INDUSTRI"
-   Fitur: Modern cards, gradient backgrounds, professional layout

#### Template Hidayah Cahaya Berkah

-   File: `resources/views/penjualan/quotation/pdf-hidayah-cahaya.blade.php`
-   Desain: Elegan dengan nuansa Islami
-   Warna utama: Hijau (#27ae60)
-   Logo: Logo HCB-01.png
-   Watermark: "PT HIDAYAH CAHAYA BERKAH"
-   Fitur: Bismillah section, Islamic quotes, elegant design
-   Font: Georgia serif untuk nuansa formal dan Islami

## Implementasi Teknis

### 1. Routes

```php
// File: routes/web.php
Route::get('quotation/{id}/pdf', [QuotationController::class, 'exportPdf'])->name('quotation.pdf');
Route::get('quotation/{id}/pdf/{template}', [QuotationController::class, 'exportPdf'])->name('quotation.pdf.template');
```

### 2. Controller Method

```php
// File: app/Http/Controllers/Penjualan/QuotationController.php
public function exportPdf($id, $template = 'default')
{
    $templates = [
        'default' => [
            'view' => 'penjualan.quotation.pdf',
            'company_name' => 'PT. SINAR SURYA SEMESTARAYA',
            'filename_prefix' => 'Quotation-SS'
        ],
        'indo-atsaka' => [
            'view' => 'penjualan.quotation.pdf-indo-atsaka',
            'company_name' => 'PT INDO ATSAKA INDUSTRI',
            'filename_prefix' => 'Quotation-IAI'
        ],
        'hidayah-cahaya' => [
            'view' => 'penjualan.quotation.pdf-hidayah-cahaya',
            'company_name' => 'PT HIDAYAH CAHAYA BERKAH',
            'filename_prefix' => 'Quotation-HCB'
        ]
    ];

    // Logic untuk memilih template dan generate PDF
}
```

### 3. Frontend Dropdown

-   Menggunakan Alpine.js untuk interaktivity
-   Responsive design dengan Tailwind CSS
-   Icons yang berbeda untuk setiap template

## File yang Dibuat/Dimodifikasi

### Files Baru:

1. `resources/views/penjualan/quotation/pdf-indo-atsaka.blade.php`
2. `resources/views/penjualan/quotation/pdf-hidayah-cahaya.blade.php`

### Files Dimodifikasi:

1. `routes/web.php` - Menambah route untuk template selection
2. `app/Http/Controllers/Penjualan/QuotationController.php` - Modifikasi method exportPdf
3. `resources/views/penjualan/quotation/show.blade.php` - Menambah dropdown UI

### Assets yang Digunakan:

1. `public/img/PT Indo atsaka industri-26.png` - Logo Indo Atsaka
2. `public/img/Logo HCB-01.png` - Logo Hidayah Cahaya Berkah

## Cara Penggunaan

1. Buka halaman detail quotation
2. Klik button "Download PDF"
3. Pilih template yang diinginkan dari dropdown:
    - PT Sinar Surya Semestaraya (Default)
    - PT Indo Atsaka Industri
    - PT Hidayah Cahaya Berkah
4. PDF akan didownload dengan nama file yang sesuai template

## URL Structure

-   Default: `/quotation/{id}/pdf`
-   Template specific: `/quotation/{id}/pdf/{template}`
-   Template options: `default`, `indo-atsaka`, `hidayah-cahaya`

## Styling Features

### Template Indo Atsaka:

-   Modern gradient backgrounds
-   Professional card layouts
-   Red color scheme (#e74c3c)
-   Clean typography
-   Hover effects pada tables

### Template Hidayah Cahaya Berkah:

-   Islamic elements (Bismillah section)
-   Elegant green color scheme (#27ae60)
-   Serif font (Georgia) untuk formalitas
-   Islamic quotes dan blessing di footer
-   Geometric patterns dengan CSS

## Security & Error Handling

-   Validasi template parameter
-   Fallback ke template default jika template tidak valid
-   Error handling untuk logo missing
-   Proper PDF generation timeout (120 seconds)

## Future Enhancements

1. Admin panel untuk manage templates
2. Custom branding settings per template
3. Template preview sebelum download
4. Bulk download dengan multiple templates
