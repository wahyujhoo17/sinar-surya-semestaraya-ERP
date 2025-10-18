# Implementasi Default Quotation Terms

## Masalah yang Dipecahkan

Pada halaman create quotation, field syarat dan ketentuan tidak memiliki default value dari pengaturan sistem. User harus mengetik ulang syarat dan ketentuan setiap kali membuat quotation baru.

## Solusi Implementasi

Sistem sekarang sudah diubah untuk mengambil default value dari setting `quotation_terms` (ID: 18) yang dapat diubah melalui halaman **Pengaturan > Pengaturan Umum**.

### Database Setting yang Digunakan

| Setting Key       | Default Value                                                                                                                                                   | Deskripsi                      |
| ----------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------ |
| `quotation_terms` | `"1. Penawaran berlaku selama 30 hari.\n2. Pembayaran 50% di muka, 50% setelah barang diterima.\n3. Pengiriman dilakukan setelah pembayaran pertama diterima."` | Syarat dan ketentuan quotation |

### File yang Diubah

#### 1. QuotationController.php

**Method create()**

```php
// Menambahkan quotation_terms dari settings
$quotation_terms = setting('quotation_terms', "1. Penawaran berlaku selama 30 hari.\n2. Pembayaran 50% di muka, 50% setelah barang diterima.\n3. Pengiriman dilakukan setelah pembayaran pertama diterima.");

// Menambahkan quotation_terms ke compact
return view('penjualan.quotation.create', compact('customers', 'products', 'bundles', 'satuans', 'nomor', 'tanggal', 'tanggal_berlaku', 'quotation_terms', 'statuses'));
```

**Method edit()**

```php
// Menambahkan quotation_terms dari settings untuk fallback
$quotation_terms = setting('quotation_terms', "1. Penawaran berlaku selama 30 hari.\n2. Pembayaran 50% di muka, 50% setelah barang diterima.\n3. Pengiriman dilakukan setelah pembayaran pertama diterima.");

// Menambahkan quotation_terms ke compact
return view('penjualan.quotation.edit', compact('quotation', 'customers', 'products', 'bundles', 'satuans', 'quotation_terms', 'statuses'));
```

#### 2. create.blade.php

```blade
<!-- Menggunakan quotation_terms sebagai default value -->
<textarea id="syarat_pembayaran" name="syarat_ketentuan" rows="2"
    class="..."
    placeholder="Syarat pembayaran...">{{ old('syarat_ketentuan', $quotation_terms ?? '') }}</textarea>
```

#### 3. edit.blade.php

```blade
<!-- Menggunakan quotation_terms sebagai fallback jika quotation belum memiliki syarat_ketentuan -->
<textarea id="syarat_pembayaran" name="syarat_ketentuan" rows="2"
    class="..."
    placeholder="Syarat pembayaran...">{{ old('syarat_ketentuan', $quotation->syarat_ketentuan ?: $quotation_terms) }}</textarea>
```

## Cara Kerja

1. **Create Quotation**: Field syarat dan ketentuan akan otomatis terisi dengan value dari setting `quotation_terms`
2. **Edit Quotation**:
    - Jika quotation sudah memiliki syarat_ketentuan, akan ditampilkan value yang sudah ada
    - Jika quotation belum memiliki syarat_ketentuan (kosong), akan menggunakan default dari setting `quotation_terms`

## Cara Mengubah Default Terms

1. Akses **Pengaturan > Pengaturan Umum**
2. Pada tab **Pengaturan Dokumen**
3. Ubah field **Syarat dan Ketentuan Quotation**
4. Simpan pengaturan

Perubahan akan berlaku untuk quotation baru yang akan dibuat setelah pengaturan disimpan.

## Keuntungan

✅ User tidak perlu mengetik ulang syarat dan ketentuan setiap kali membuat quotation  
✅ Konsistensi syarat dan ketentuan di seluruh quotation  
✅ Dapat disesuaikan per perusahaan melalui pengaturan sistem  
✅ Tetap memungkinkan modifikasi per quotation jika diperlukan
