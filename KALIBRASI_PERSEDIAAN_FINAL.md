# Jurnal Kalibrasi Persediaan - SISTEM PERPETUAL (FINAL)

## Tanggal: 2 Desember 2025

## 🎯 Konsep Penting: Sistem Perpetual

Dalam **sistem perpetual**, semua perubahan persediaan harus tercermin di **HPP (Harga Pokok Penjualan)**. Ini berbeda dengan sistem periodik.

### Formula HPP Sistem Perpetual:

```
HPP = Persediaan Awal + Pembelian - Persediaan Akhir

Di mana:
- Persediaan Akhir sudah termasuk penyesuaian
- Selisih persediaan = bagian dari HPP
```

---

## ✅ Jurnal Penyesuaian yang BENAR

### Kasus 1: Pengurangan Persediaan (Paling Umum)

**Situasi:** Nilai Fisik < Nilai Akuntansi (ada kehilangan/kerusakan/selisih)

**Jurnal:**

```
Debit: 5100 - Harga Pokok Penjualan       Rp xxx
Kredit: 1120 - Persediaan Barang Dagang   Rp xxx
```

**Mengapa masuk ke HPP (5100)?**

1. ✅ Dalam sistem perpetual, selisih persediaan = bagian dari cost of goods
2. ✅ HPP di laporan otomatis naik sesuai selisih
3. ✅ Formula balance: HPP = Persediaan Awal + Pembelian - Persediaan Akhir
4. ✅ Konsisten dengan pencatatan penjualan (debit HPP, kredit Persediaan)

**Contoh Real dari User:**

```
Laporan Laba Rugi:
- Persediaan Awal:     Rp   5.729.788
- Pembelian:          Rp   6.000.000
- Jumlah Persediaan:  Rp  11.729.788
- Persediaan Akhir:   Rp  11.661.788  ← Sudah disesuaikan
- HPP:                Rp      68.000  ← Dari penjualan + penyesuaian
```

**Penjelasan:**

-   HPP Rp 68.000 = HPP dari penjualan + HPP dari penyesuaian
-   Jika ada selisih persediaan, masuk ke debit HPP (5100)
-   Sehingga HPP total mencerminkan seluruh cost of goods yang terjual + hilang

---

### Kasus 2: Penambahan Persediaan (Jarang Terjadi)

**Situasi:** Nilai Fisik > Nilai Akuntansi (ada koreksi/temuan barang)

**Jurnal:**

```
Debit: 1120 - Persediaan Barang Dagang       Rp xxx
Kredit: 70500 - Pendapatan Lain-lain         Rp xxx
```

**Penjelasan:**

-   Menambah aset persediaan (debit 1120)
-   Mengakui sebagai pendapatan lain-lain (kredit 70500)
-   Tidak masuk HPP karena ini adalah "gain" bukan "cost"
-   Masuk ke Laba/Rugi sebagai pendapatan lain

---

## 📊 Dampak ke Laporan Keuangan

### Laporan Laba Rugi - Pengurangan Persediaan:

```
PENJUALAN                              Rp 100.000.000

HARGA POKOK PENJUALAN:
  Persediaan Awal           5.729.788
  Pembelian                 6.000.000
  Jumlah Persediaan        11.729.788
  Persediaan Akhir        (11.661.788)
  ────────────────────────────────────
  Harga Pokok Penjualan                Rp     68.000  ← Termasuk penyesuaian!
  ────────────────────────────────────

LABA KOTOR                             Rp  99.932.000
```

**Keterangan:**

-   HPP Rp 68.000 sudah termasuk:
    -   HPP dari penjualan normal
    -   HPP dari penyesuaian persediaan (kehilangan/kerusakan)
-   Formula: 5.729.788 + 6.000.000 - 11.661.788 = 68.000 ✅

---

## 🔍 Perbandingan: HPP vs Beban Operasional

### ❌ Jika Masuk Beban Operasional (5300):

```
Harga Pokok Penjualan:                 Rp          0
Laba Kotor:                            Rp 100.000.000

Beban Operasional:
  Beban Penyesuaian Persediaan         Rp     68.000
  ────────────────────────────────────
Laba Operasional:                      Rp  99.932.000
```

**Masalah:**

-   HPP = 0 padahal ada persediaan yang berkurang
-   Formula tidak balance: 5.729.788 + 6.000.000 - 11.661.788 ≠ 0 ❌
-   Salah kategori: bukan beban operasional, tapi cost of goods

---

### ✅ Jika Masuk HPP (5100) - BENAR:

```
Harga Pokok Penjualan:                 Rp     68.000  ← Termasuk penyesuaian
Laba Kotor:                            Rp  99.932.000
```

**Keunggulan:**

-   HPP mencerminkan total cost of goods (terjual + hilang)
-   Formula balance: 5.729.788 + 6.000.000 - 11.661.788 = 68.000 ✅
-   Kategori tepat: selisih persediaan = bagian dari cost of goods
-   Konsisten dengan sistem perpetual

---

## 🔧 Konfigurasi Akun yang Digunakan

### Pengurangan (Kehilangan):

-   **Akun HPP:** 5100 - Harga Pokok Penjualan
-   **Konfigurasi:** `hpp` → `harga_pokok_penjualan`
-   **Posisi:** Cost of Goods Sold (5100)

### Penambahan (Temuan):

-   **Akun Pendapatan:** 70500 - PENDAPATAN LAIN-LAIN
-   **Posisi:** Pendapatan Lain-lain (70500)

### Persediaan:

-   **Akun:** 1120 - Persediaan Barang Dagang
-   **Posisi:** Aset Lancar (1120)

---

## 📝 Implementasi Teknis

### File yang Diubah:

`app/Http/Controllers/Keuangan/JurnalPenyesuaianPersediaanController.php`

### Perubahan Key:

```php
// Ambil akun HPP (untuk pengurangan persediaan)
$akunHPP = AccountingConfiguration::where('transaction_type', 'hpp')
    ->where('account_key', 'harga_pokok_penjualan')
    ->first();

// Jurnal pengurangan
if ($selisih < 0) {
    // Debit: HPP (5100) ← Masuk ke HPP
    // Kredit: Persediaan (1120)
}
```

---

## ✅ Kesimpulan

### Sebelum Perbaikan:

-   ❌ Penyesuaian persediaan tidak masuk HPP
-   ❌ Formula tidak balance
-   ❌ HPP di laporan tidak mencerminkan realitas

### Setelah Perbaikan:

-   ✅ Penyesuaian persediaan masuk ke HPP (5100)
-   ✅ Formula balance: HPP = Persediaan Awal + Pembelian - Persediaan Akhir
-   ✅ Laporan Laba Rugi akurat
-   ✅ Konsisten dengan sistem perpetual
-   ✅ Dapat diaudit dan traceable

---

## 🎯 Prinsip yang Diterapkan

1. **Sistem Perpetual:** Setiap perubahan persediaan langsung mempengaruhi HPP
2. **Consistency:** Semua transaksi persediaan konsisten (penjualan & penyesuaian)
3. **Matching Principle:** Cost diakui bersamaan dengan berkurangnya persediaan
4. **Reliability:** Laporan keuangan mencerminkan kondisi sebenarnya

---

## 📌 Catatan Penting

> **"Dalam sistem perpetual, selisih persediaan (kehilangan/kerusakan) adalah bagian dari HPP, bukan beban operasional lain. Ini karena kehilangan persediaan = cost of goods yang tidak terjual tapi sudah hilang/rusak."**

Ini berbeda dengan sistem periodik di mana selisih persediaan mungkin masuk ke akun terpisah. Dalam perpetual, konsistensinya adalah:

-   **Penjualan:** Debit HPP, Kredit Persediaan
-   **Penyesuaian (kehilangan):** Debit HPP, Kredit Persediaan
-   **Penyesuaian (temuan):** Debit Persediaan, Kredit Pendapatan Lain-lain

Semua berkurangnya persediaan yang tidak menghasilkan pendapatan = HPP.
