# Perbaikan Jurnal Kalibrasi Persediaan

## Tanggal: 2 Desember 2025

## Masalah Sebelumnya

### ❌ Jurnal Lama (TIDAK SESUAI Prinsip Akuntansi)

**Kasus Pengurangan (Nilai Fisik < Akuntansi):**

```
Debit: Penyesuaian Persediaan (akun tidak jelas)
Kredit: Persediaan (1120)
```

**Kasus Penambahan (Nilai Fisik > Akuntansi):**

```
Debit: Persediaan (1120)
Kredit: Penyesuaian Persediaan (akun tidak jelas)
```

**Masalah:**

1. ❌ Akun "Penyesuaian Persediaan" tidak jelas posisinya di COA
2. ❌ Tidak masuk ke Laba/Rugi
3. ❌ Melanggar prinsip matching (selisih persediaan = beban/pendapatan)
4. ❌ Tidak mencerminkan realitas ekonomi (kehilangan = beban)

---

## ✅ Perbaikan Sesuai Prinsip Akuntansi

### Kasus 1: Pengurangan Persediaan (Paling Umum)

**Situasi:** Nilai Fisik < Nilai Akuntansi (ada kehilangan/kerusakan/selisih)

**Jurnal:**

```
Debit: 5300 - Beban Penyesuaian Persediaan  Rp xxx
Kredit: 1120 - Persediaan Barang Dagang      Rp xxx
```

**Penjelasan:**

-   Mengurangi aset persediaan (kredit)
-   Mengakui sebagai beban operasional (debit)
-   Masuk ke Laba/Rugi → mengurangi laba
-   Sesuai prinsip: Kehilangan = Beban yang harus diakui

**Contoh:**
Persediaan di buku Rp 100.000.000, fisik hanya Rp 95.000.000

```
Debit: Beban Penyesuaian Persediaan    Rp 5.000.000
Kredit: Persediaan Barang Dagang       Rp 5.000.000
```

---

### Kasus 2: Penambahan Persediaan (Jarang Terjadi)

**Situasi:** Nilai Fisik > Nilai Akuntansi (ada koreksi/temuan barang)

**Jurnal:**

```
Debit: 1120 - Persediaan Barang Dagang       Rp xxx
Kredit: 70500 - Pendapatan Lain-lain         Rp xxx
```

**Penjelasan:**

-   Menambah aset persediaan (debit)
-   Mengakui sebagai pendapatan lain-lain (kredit)
-   Masuk ke Laba/Rugi → menambah laba
-   Sesuai prinsip: Temuan barang = Gain/Pendapatan

**Contoh:**
Persediaan di buku Rp 95.000.000, fisik ternyata Rp 100.000.000

```
Debit: Persediaan Barang Dagang        Rp 5.000.000
Kredit: Pendapatan Lain-lain           Rp 5.000.000
```

---

## Konfigurasi Akun yang Digunakan

### Pengurangan (Kehilangan):

-   **Akun Beban:** 5300 - Penyesuaian Persediaan
-   **Konfigurasi:** `penyesuaian_stok` → `penyesuaian_persediaan`
-   **Posisi:** Beban Operasional (5xxx)

### Penambahan (Temuan):

-   **Akun Pendapatan:** 70500 - PENDAPATAN LAIN-LAIN
-   **Posisi:** Pendapatan Lain-lain (7xxx)

### Persediaan:

-   **Akun:** 1120 - Persediaan Barang Dagang
-   **Posisi:** Aset Lancar (1xxx)

---

## Dampak ke Laporan Keuangan

### Laporan Laba Rugi:

✅ **Pengurangan Persediaan:**

-   Masuk sebagai "Beban Penyesuaian Persediaan"
-   Mengurangi laba bersih
-   Mencerminkan biaya ekonomi yang sebenarnya

✅ **Penambahan Persediaan:**

-   Masuk sebagai "Pendapatan Lain-lain"
-   Menambah laba bersih
-   Mencerminkan gain dari temuan

### Neraca:

✅ Nilai persediaan di neraca = Nilai fisik yang sebenarnya
✅ Balance dengan laba/rugi yang terpengaruh

---

## Prinsip Akuntansi yang Diterapkan

### 1. **Matching Principle (Prinsip Penandingan)**

-   Beban diakui pada periode terjadinya
-   Kehilangan persediaan = beban periode berjalan

### 2. **Historical Cost Principle**

-   Persediaan dicatat sebesar nilai perolehannya
-   Penyesuaian berdasarkan nilai fisik yang terverifikasi

### 3. **Consistency Principle**

-   Metode penyesuaian konsisten setiap periode
-   Dokumentasi jelas dengan nomor referensi JP-PERS-xxxxx

### 4. **Full Disclosure Principle**

-   Keterangan jurnal jelas: "Kalibrasi persediaan - Penyesuaian nilai..."
-   Dapat dilacak ke dokumen pendukung

---

## Implementasi Teknis

### File yang Diubah:

`app/Http/Controllers/Keuangan/JurnalPenyesuaianPersediaanController.php`

### Perubahan:

1. Mengganti `$akunPenyesuaian` menjadi:

    - `$akunBebanSelisih` (untuk pengurangan)
    - `$akunPendapatanLain` (untuk penambahan)

2. Logika jurnal yang diperbaiki:

    ```php
    if ($selisih > 0) {
        // Debit: Persediaan
        // Kredit: Pendapatan Lain-lain
    } else {
        // Debit: Beban Selisih Persediaan
        // Kredit: Persediaan
    }
    ```

3. Jenis jurnal: `'penyesuaian'` (sesuai ENUM)

---

## Testing

### Scenario 1: Pengurangan

1. Nilai Akuntansi: Rp 785.880.489,78
2. Nilai Fisik: Rp 780.000.000
3. Selisih: -Rp 5.880.489,78

**Expected Jurnal:**

```
Debit: 5300 - Beban Penyesuaian Persediaan  Rp 5.880.489,78
Kredit: 1120 - Persediaan Barang Dagang     Rp 5.880.489,78
```

### Scenario 2: Penambahan

1. Nilai Akuntansi: Rp 100.000.000
2. Nilai Fisik: Rp 105.000.000
3. Selisih: +Rp 5.000.000

**Expected Jurnal:**

```
Debit: 1120 - Persediaan Barang Dagang      Rp 5.000.000
Kredit: 70500 - Pendapatan Lain-lain        Rp 5.000.000
```

---

## Kesimpulan

✅ Jurnal penyesuaian persediaan sekarang **SESUAI dengan prinsip akuntansi**
✅ Selisih persediaan **mempengaruhi Laba/Rugi** sebagaimana seharusnya
✅ Neraca mencerminkan **nilai persediaan yang sebenarnya**
✅ Dapat diaudit dan **traceable** melalui nomor referensi
✅ **Dokumentasi lengkap** untuk keperluan audit

---

## Referensi Standar Akuntansi

-   **PSAK 14:** Persediaan
-   **Matching Principle:** GAAP & IFRS
-   **Prinsip Konservatisme:** Mengakui beban segera, pendapatan dengan hati-hati
