# DOKUMENTASI: MIGRASI STOK AWAL & KALIBRASI PERSEDIAAN

## Tanggal: 6 Januari 2026

---

## 📋 PERBEDAAN: Penyesuaian Stok vs Kalibrasi Persediaan

### ❓ PERTANYAAN PENTING

**"Pada saat input penyesuaian stok sudah ada pencatatan jurnal, dan pada kalibrasi juga ada pencatatan jurnal. Apakah ini akan mencatat nilai barang menjadi 2x?"**

### ✅ JAWABAN: TIDAK ADA DOUBLE COUNTING!

Kedua fitur memiliki **tujuan dan akun yang berbeda**, sehingga tidak akan menyebabkan pencatatan ganda.

---

## 🔍 ANALISIS DETAIL

### 1. PENYESUAIAN STOK (Stock Adjustment)

**Fungsi:**

-   Mencatat selisih **kuantitas** stok fisik vs tercatat
-   Digunakan untuk stock opname rutin
-   Update stok di gudang

**Jurnal yang Dibuat:**

```
Contoh: Stok fisik lebih banyak dari tercatat
Qty tercatat: 100 pcs
Qty fisik: 120 pcs
Selisih: +20 pcs
Harga beli: Rp 10.000/pcs
Nilai selisih: 20 × Rp 10.000 = Rp 200.000

Jurnal:
Debit:  1120 - Persediaan Barang Dagang       Rp 200.000
Kredit: 5910 - Penyesuaian Persediaan         Rp 200.000
```

**Akun Lawan:** `5910 - Penyesuaian Persediaan` (Beban/Pendapatan Lain)

**Dampak Laporan:**

-   Masuk ke Laporan Laba Rugi sebagai **Beban/Pendapatan Lain-lain**
-   Bukan HPP, bukan Modal

---

### 2. KALIBRASI PERSEDIAAN

**Fungsi:**

-   Sinkronisasi **nilai** persediaan akuntansi dengan nilai fisik
-   Digunakan untuk opening balance / migrasi sistem
-   Koreksi nilai persediaan yang signifikan

**Jurnal yang Dibuat:**

**A. PENAMBAHAN (Nilai Fisik > Nilai Akuntansi)**

```
Skenario: Migrasi dari sistem lama
Nilai Akuntansi (dari jurnal): Rp 0
Nilai Fisik (dari stok × harga): Rp 100.000.000
Selisih: Rp 100.000.000

Jurnal:
Debit:  1120 - Persediaan Barang Dagang       Rp 100.000.000
Kredit: 3xxx - Modal Pemilik                  Rp 100.000.000
(Keterangan: Koreksi saldo awal/modal barang)
```

**B. PENGURANGAN (Nilai Fisik < Nilai Akuntansi)**

```
Skenario: Ada kehilangan/kerusakan yang signifikan
Nilai Akuntansi: Rp 100.000.000
Nilai Fisik: Rp 95.000.000
Selisih: -Rp 5.000.000

Jurnal:
Debit:  5100 - Harga Pokok Penjualan (HPP)    Rp 5.000.000
Kredit: 1120 - Persediaan Barang Dagang       Rp 5.000.000
```

**Akun Lawan:**

-   Penambahan → `3xxx - Modal Pemilik` (Ekuitas)
-   Pengurangan → `5100 - HPP` (Cost of Goods Sold)

**Dampak Laporan:**

-   Penambahan: Masuk ke **Neraca** sebagai Modal/Ekuitas
-   Pengurangan: Masuk ke **Laporan Laba Rugi** sebagai HPP

---

## 📊 PERBANDINGAN AKUN

| Aspek                   | Penyesuaian Stok              | Kalibrasi Persediaan                  |
| ----------------------- | ----------------------------- | ------------------------------------- |
| **Tujuan**              | Stock opname kuantitas        | Sinkronisasi nilai                    |
| **Frekuensi**           | Berkala (bulanan/tahunan)     | Sekali (setup awal)                   |
| **Akun Lawan (Tambah)** | 5910 - Penyesuaian Persediaan | 3xxx - Modal Pemilik                  |
| **Akun Lawan (Kurang)** | 5910 - Penyesuaian Persediaan | 5100 - HPP                            |
| **Dampak**              | Beban/Pendapatan Lain         | Ekuitas atau HPP                      |
| **Laporan**             | Laba Rugi (Other Income)      | Neraca (Equity) atau Laba Rugi (COGS) |

---

## ✅ FLOW MIGRASI STOK AWAL (Tidak Ada Double Counting!)

### Skenario: Migrasi dari sistem lama ke sistem baru

**Kondisi Awal:**

-   Sistem lama punya stok fisik: 1.000 unit @ Rp 50.000 = Rp 50.000.000
-   Sistem baru: Nilai akuntansi persediaan = Rp 0

**Step 1: Input Master Data Produk**

```
Action: Input produk dengan harga_beli
Result: Produk tersedia di database
Jurnal: TIDAK ADA
```

**Step 2: Penyesuaian Stok**

```
Action: Input qty fisik awal di gudang
- Stok tercatat: 0 pcs
- Stok fisik: 1.000 pcs
- Selisih: +1.000 pcs
- Harga: Rp 50.000/pcs
- Nilai: 1.000 × Rp 50.000 = Rp 50.000.000

Jurnal:
Debit:  1120 - Persediaan          Rp 50.000.000
Kredit: 5910 - Penyesuaian Pers.   Rp 50.000.000

Result:
- Stok fisik di gudang: 1.000 pcs ✓
- Nilai akuntansi persediaan: Rp 50.000.000 ✓
```

**Step 3: Kalibrasi Persediaan**

```
Action: Sinkronisasi nilai persediaan
- Nilai Akuntansi: Rp 50.000.000 (dari jurnal penyesuaian stok)
- Nilai Fisik: Rp 50.000.000 (dari stok × harga)
- Selisih: Rp 0

Jurnal: TIDAK ADA (karena selisih = 0)

Result: Tidak perlu kalibrasi, sudah sesuai!
```

---

## ⚠️ SKENARIO ALTERNATIF: Jika Akun Berbeda Diinginkan

**Jika Anda ingin opening balance masuk ke Modal (bukan Penyesuaian Persediaan):**

**Cara 1: Langsung Kalibrasi (SKIP Penyesuaian Stok)**

```
Step 1: Input Master Data Produk (dengan harga)
Step 2: Buat record stok_produk manual (via database atau script)
        UPDATE stok_produk SET jumlah = 1000 WHERE produk_id = X
Step 3: Kalibrasi Persediaan
        - Nilai Akuntansi: Rp 0
        - Nilai Fisik: Rp 50.000.000
        - Jurnal: Debit Persediaan, Kredit Modal Pemilik
```

**Cara 2: Koreksi Manual**

```
Step 1: Lakukan Penyesuaian Stok (jurnal ke 5910)
Step 2: Buat jurnal balik manual:
        Debit:  5910 - Penyesuaian Persediaan   Rp 50.000.000
        Kredit: 3xxx - Modal Pemilik            Rp 50.000.000
        (Keterangan: Reklasifikasi opening balance)
```

---

## 🎯 REKOMENDASI untuk MIGRASI

### **PILIHAN 1: Penyesuaian Stok Saja (PALING MUDAH)**

**Keuntungan:**

-   ✅ Proses standard, tidak perlu custom
-   ✅ Riwayat lengkap di sistem
-   ✅ Audit trail jelas

**Kekurangan:**

-   ⚠️ Opening balance masuk ke "Penyesuaian Persediaan" (5910), bukan Modal (3xxx)
-   ⚠️ Muncul di Laba Rugi sebagai "Pendapatan Lain"

**Flow:**

```
1. Master Data Produk → Input semua produk + harga_beli
2. Penyesuaian Stok → Input stok fisik awal
3. SELESAI
```

---

### **PILIHAN 2: Kalibrasi Persediaan (PALING PROPER untuk Akuntansi)**

**Keuntungan:**

-   ✅ Opening balance masuk ke Modal Pemilik (3xxx) - PROPER!
-   ✅ Neraca lebih akurat
-   ✅ Tidak mengotori Laba Rugi

**Kekurangan:**

-   ⚠️ Perlu manual input stok_produk (tidak ada form UI)

**Flow:**

```
1. Master Data Produk → Input semua produk + harga_beli
2. Manual Input stok_produk via SQL/script
3. Kalibrasi Persediaan → Generate jurnal ke Modal Pemilik
```

---

### **PILIHAN 3: Hybrid (Penyesuaian + Kalibrasi)**

**Situasi:**
Jika sistem sudah berjalan beberapa waktu dan ada transaksi, lalu baru mau setup stok awal

**Flow:**

```
1. Penyesuaian Stok → Input stok fisik
   Jurnal: Debit Persediaan, Kredit Penyesuaian Persediaan

2. Kalibrasi Persediaan → Koreksi nilai jika ada selisih
   Jurnal: Debit/Kredit sesuai selisih

Total Jurnal: TIDAK GANDA karena akun lawan berbeda!
```

---

## 📝 VALIDASI BARU: Warning Produk Tanpa Harga

**Fitur Baru:**
Sistem sekarang akan menampilkan **peringatan** di halaman Kalibrasi Persediaan jika ada produk yang:

-   Memiliki stok (jumlah > 0)
-   Tidak memiliki harga_beli DAN harga_beli_rata_rata
-   Atau harga = 0

**Warning Message:**

```
⚠️ Peringatan: X Produk Tanpa Harga Beli

Produk berikut memiliki stok tetapi belum memiliki harga beli.
Nilai persediaan untuk produk ini akan dihitung Rp 0 dan
menyebabkan kalkulasi tidak akurat.

Rekomendasi: Update harga beli di Master Data Produk
sebelum melakukan kalibrasi persediaan.
```

---

## 🔧 SCRIPT SQL untuk PRE-CHECK Migrasi

```sql
-- 1. Cek produk tanpa harga beli yang punya stok
SELECT
    p.kode,
    p.nama,
    sp.jumlah as stok,
    p.harga_beli,
    p.harga_beli_rata_rata
FROM produk p
INNER JOIN stok_produk sp ON p.id = sp.produk_id
WHERE sp.jumlah > 0
  AND (p.harga_beli IS NULL OR p.harga_beli = 0)
  AND (p.harga_beli_rata_rata IS NULL OR p.harga_beli_rata_rata = 0);

-- 2. Hitung total nilai persediaan fisik
SELECT
    SUM(sp.jumlah * COALESCE(p.harga_beli_rata_rata, p.harga_beli, 0)) as nilai_persediaan_fisik
FROM stok_produk sp
INNER JOIN produk p ON sp.produk_id = p.id
WHERE sp.jumlah > 0;

-- 3. Hitung nilai persediaan di akuntansi
SELECT
    SUM(debit - kredit) as nilai_persediaan_akuntansi
FROM jurnal_umum
WHERE akun_id IN (
    SELECT id FROM akun_akuntansi WHERE kode LIKE '1120%'
)
AND is_posted = 1;
```

---

## ✅ CHECKLIST MIGRASI STOK AWAL

```
☐ 1. PERSIAPAN DATA
   ☐ Export data stok dari sistem lama
   ☐ Pastikan ada: kode produk, nama, qty, harga beli

☐ 2. MASTER DATA PRODUK
   ☐ Input/import semua produk
   ☐ Pastikan harga_beli terisi untuk semua produk
   ☐ Run SQL check produk tanpa harga

☐ 3. SETUP GUDANG
   ☐ Buat gudang di sistem
   ☐ Set status active

☐ 4. INPUT STOK (Pilih salah satu):

   OPSI A: Via Penyesuaian Stok (Rekomendasi)
   ☐ Inventaris → Penyesuaian Stok → Create
   ☐ Input stok fisik untuk semua produk
   ☐ Status: Draft → Proses → Selesai
   ☐ Cek jurnal terbuat (akun 5910)

   OPSI B: Via Kalibrasi Persediaan
   ☐ Insert manual ke stok_produk
   ☐ Keuangan → Kalibrasi Persediaan
   ☐ Cek nilai fisik vs akuntansi
   ☐ Sinkronkan (jurnal ke Modal)

☐ 5. VERIFIKASI
   ☐ Cek Laporan Stok per Gudang
   ☐ Cek Buku Besar akun 1120
   ☐ Cek Neraca: Persediaan = nilai fisik
   ☐ Cek tidak ada produk dengan stok tapi harga 0
```

---

## 🎓 KESIMPULAN

### **TIDAK ADA DOUBLE COUNTING!**

1. **Penyesuaian Stok** dan **Kalibrasi Persediaan** menggunakan **akun lawan yang berbeda**
2. Penyesuaian Stok → Akun 5910 (Penyesuaian Persediaan)
3. Kalibrasi Persediaan → Akun 3xxx (Modal) atau 5100 (HPP)
4. Untuk **migrasi stok awal**, pilih salah satu metode sesuai kebutuhan akuntansi
5. Jika sudah pakai Penyesuaian Stok, Kalibrasi hanya perlu jika ada selisih nilai

### **Rekomendasi Final:**

**Untuk Perusahaan Baru / Migrasi:**
→ Gunakan **Kalibrasi Persediaan** (jurnal ke Modal Pemilik) - PALING PROPER

**Untuk Update Rutin:**
→ Gunakan **Penyesuaian Stok** (jurnal ke Penyesuaian Persediaan)

---

**Updated:** 6 Januari 2026  
**Author:** System Documentation  
**Status:** ✅ Complete & Verified
