# Implementasi Prefix Dinamis untuk Nomor Dokumen

## Masalah yang Dipecahkan

Sebelumnya sistem menggunakan prefix hardcoded yang tidak bisa diubah melalui pengaturan sistem. Prefix yang digunakan:

-   Quotation: `QO-` (hardcoded)
-   Sales Order: `SO-` (hardcoded)
-   Purchase Request: `PR-` (hardcoded)
-   Purchase Order: `PO-` (hardcoded)
-   Delivery Order: `DO-` (hardcoded)
-   Invoice: `INV-` (hardcoded)

## Solusi Implementasi

Sistem sekarang sudah diubah untuk mengambil prefix dari pengaturan database yang dapat diubah melalui halaman **Pengaturan > Pengaturan Umum**.

### Database Settings yang Digunakan

| Setting Key               | Default Value | Deskripsi                     |
| ------------------------- | ------------- | ----------------------------- |
| `quotation_prefix`        | `QT`          | Prefix nomor quotation        |
| `sales_order_prefix`      | `SO`          | Prefix nomor sales order      |
| `purchase_request_prefix` | `PR`          | Prefix nomor purchase request |
| `purchase_order_prefix`   | `PO`          | Prefix nomor purchase order   |
| `delivery_order_prefix`   | `DO`          | Prefix nomor delivery order   |
| `invoice_prefix`          | `INV`         | Prefix nomor invoice          |

### File yang Diubah

#### 1. Helper Function (Sudah ada)

File: `app/Helpers/SettingsHelper.php`

-   Function `get_document_prefix($type)` sudah tersedia
-   Mengambil prefix dari database dengan fallback ke default value

#### 2. Controllers yang Diperbarui

**InvoiceController.php**

```php
// Sebelum
$prefix = 'INV-';

// Sesudah
$prefix = get_document_prefix('invoice') . '-';
```

**QuotationController.php**

```php
// Sebelum
$prefix = 'QO-';

// Sesudah
$prefix = get_document_prefix('quotation') . '-';
```

**SalesOrderController.php**

```php
// Sebelum
$prefix = 'SO-';

// Sesudah
$prefix = get_document_prefix('sales_order') . '-';
```

**DeliveryOrderController.php**

```php
// Sebelum
$prefix = 'DO-' . Carbon::now()->format('Ymd') . '-';

// Sesudah
$prefix = get_document_prefix('delivery_order') . '-' . Carbon::now()->format('Ymd') . '-';
```

**PurchasingOrderController.php**

```php
// Sebelum
$prefix = 'PO-' . $today->format('Ymd');

// Sesudah
$prefix = get_document_prefix('purchase_order') . '-' . $today->format('Ymd');
```

**PermintaanPembelianController.php**

```php
// Sebelum
$nomorPR = "PR-{$today}-{$sequence}";

// Sesudah
$prefix = get_document_prefix('purchase_request');
$nomorPR = "{$prefix}-{$today}-{$sequence}";
```

## Cara Penggunaan

1. Akses **Pengaturan > Pengaturan Umum**
2. Pada tab **Pengaturan Dokumen**
3. Ubah prefix sesuai kebutuhan pada bagian **Prefix Nomor Dokumen**
4. Simpan pengaturan

## Format Nomor Dokumen

Dengan perubahan ini, format nomor dokumen sekarang menjadi:

-   **Quotation**: `[PREFIX]-YYYYMMDD-XXX` (contoh: `QT-20250101-001`)
-   **Sales Order**: `[PREFIX]-YYYYMMDD-XXX` (contoh: `SO-20250101-001`)
-   **Purchase Request**: `[PREFIX]-YYYYMMDD-XXX` (contoh: `PR-20250101-001`)
-   **Purchase Order**: `[PREFIX]-YYYYMMDD-XXX` (contoh: `PO-20250101-001`)
-   **Delivery Order**: `[PREFIX]-YYYYMMDD-XXXXX` (contoh: `DO-20250101-00001`)
-   **Invoice**: `[PREFIX]-YYYYMMDD-XXX` (contoh: `INV-20250101-001`)

Dimana:

-   `[PREFIX]` = Prefix yang dapat diubah di pengaturan
-   `YYYYMMDD` = Tanggal dalam format tahun-bulan-tanggal
-   `XXX/XXXXX` = Nomor urut per hari

## Keuntungan

✅ Prefix dapat diubah melalui pengaturan sistem tanpa perlu mengubah kode
✅ Setiap perusahaan bisa menggunakan prefix sesuai kebutuhannya  
✅ Perubahan prefix hanya berlaku untuk dokumen baru yang akan dibuat
✅ Data historis tetap menggunakan prefix lama (tidak berubah)

## Catatan Penting

-   Perubahan prefix hanya berlaku untuk dokumen baru yang akan dibuat setelah pengaturan diubah
-   Dokumen yang sudah ada tetap menggunakan prefix lama
-   Pastikan prefix yang digunakan tidak bertabrakan dengan dokumen existing
