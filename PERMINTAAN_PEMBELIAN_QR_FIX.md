# Fix QR Code "Disetujui oleh" pada Permintaan Pembelian

## Masalah yang Ditemukan

QR Code untuk "Disetujui oleh" masih kosong karena:

1. **Modul yang salah**: Controller menggunakan `'Permintaan Pembelian'` tapi di database tersimpan sebagai `'permintaan_pembelian'`
2. **Pattern pencarian yang salah**: Mencari di kolom `aktivitas` padahal informasi ada di kolom `detail`
3. **Query yang tidak tepat**: Tidak menggunakan pattern yang sesuai dengan format data yang tersimpan

## Analisis Database

### Struktur LogAktivitas untuk Permintaan Pembelian:

-   **Modul**: `'permintaan_pembelian'` (bukan `'Permintaan Pembelian'`)
-   **Aktivitas**: `'change_status'` (untuk semua perubahan status)
-   **Detail**: Format: `"Status dari [status_lama] ke [status_baru] untuk PR: [nomor]"`

### Pattern Approval:

-   Approval: `"Status dari diajukan ke disetujui untuk PR: PR-20250505-002"`
-   Final: `"Status dari disetujui ke selesai untuk PR: PR-20250503-002"`

## Perbaikan yang Dilakukan

### 1. Update Query di Controller

**Sebelum:**

```php
$processLog = LogAktivitas::with('user')
    ->where('modul', 'Permintaan Pembelian')  // ❌ Salah
    ->where('data_id', $permintaanPembelian->id)
    ->where(function ($q) {
        $q->where('aktivitas', 'like', '%menyetujui%')     // ❌ Salah kolom
          ->orWhere('aktivitas', 'like', '%approve%')      // ❌ Salah kolom
          ->orWhere('aktivitas', 'like', '%disetujui%');   // ❌ Salah kolom
    })
    ->latest()
    ->first();
```

**Sesudah:**

```php
$processLog = LogAktivitas::with('user')
    ->where('modul', 'permintaan_pembelian')      // ✅ Benar
    ->where('data_id', $permintaanPembelian->id)
    ->where('aktivitas', 'change_status')         // ✅ Benar
    ->where('detail', 'like', '%ke disetujui%')   // ✅ Benar kolom dan pattern
    ->latest()
    ->first();
```

### 2. File yang Diupdate

-   `app/Http/Controllers/Pembelian/PermintaanPembelianController.php`
    -   Method `exportPdf()`
    -   Method `printPdf()`

## Testing Results

### 1. Database Query Test ✅

```
Testing dengan Permintaan ID: 4
Nomor: PR-20250505-002
Status: disetujui
Log ditemukan! ✅
Detail: Status dari diajukan ke disetujui untuk PR: PR-20250505-002
User: Administrator
Created: 2025-05-14 13:55:51
```

### 2. QR Code Generation Test ✅

```
Testing QR Code generation untuk PR: PR-20250505-002
Created by: Administrator
Processed by: Administrator ✅

QR Codes generated:
- created_qr: Generated (110086 chars) ✅
- processed_qr: Generated (110650 chars) ✅
- document_qr: Generated (104902 chars) ✅
```

## Hasil Perbaikan

### ✅ Yang Fixed:

1. **QR Code "Disetujui oleh"** sekarang muncul untuk dokumen yang sudah approved
2. **Data approver** terdeteksi dengan benar dari LogAktivitas
3. **Timestamp approval** ditampilkan dengan akurat
4. **Email approver** ditampilkan di signature

### ✅ Fitur yang Berfungsi:

-   **Creator QR Code**: Selalu muncul untuk semua dokumen
-   **Approver QR Code**: Muncul hanya untuk dokumen yang sudah disetujui
-   **Document QR Code**: Muncul di header untuk verifikasi
-   **Digital Signatures**: Dengan timestamp dan informasi lengkap
-   **Status Detection**: Otomatis detect approval dari log aktivitas

## Validation Checklist

-   [x] Query menggunakan modul yang benar (`permintaan_pembelian`)
-   [x] Pencarian menggunakan kolom `detail` dengan pattern `%ke disetujui%`
-   [x] QR Code generated dengan data yang lengkap
-   [x] Approver information terdeteksi dan ditampilkan
-   [x] Timestamp approval akurat
-   [x] Kedua method (`exportPdf` dan `printPdf`) sudah diupdate

## Testing Recommendations

Untuk memvalidasi fix ini:

1. **Test dengan dokumen approved**:

    ```
    GET /pembelian/permintaan-pembelian/{id}/print
    ```

    Dimana {id} adalah permintaan dengan status 'disetujui' atau 'selesai'

2. **Verify QR Code**:

    - QR Code "Dibuat oleh" harus selalu ada
    - QR Code "Disetujui oleh" harus ada untuk status approved
    - QR Code header untuk verifikasi dokumen

3. **Check signature info**:
    - Nama approver
    - Email approver
    - Timestamp approval yang akurat

Fix ini memastikan QR Code dan signature system berfungsi dengan benar sesuai dengan sistem logging yang sudah ada.
