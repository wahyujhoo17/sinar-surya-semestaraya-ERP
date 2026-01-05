# PANDUAN IMPORT PRODUK + STOK

## Tanggal: 6 Januari 2026

---

## ✨ FITUR BARU: Import Produk dengan Stok Sekaligus

Sekarang Anda bisa **import produk dan stok sekaligus** menggunakan satu file Excel!

---

## 📋 KOLOM EXCEL TEMPLATE

### **Kolom Wajib:**

-   ✅ **Nama** - Nama produk (WAJIB)

### **Kolom Optional:**

-   Kode - Auto-generate jika kosong
-   Jenis - **Skip jika kosong/null** (tidak akan buat jenis baru)
-   SKU
-   Kategori - Dibuat otomatis jika belum ada
-   Merek
-   Sub Kategori
-   Satuan - Dibuat otomatis jika belum ada
-   Ukuran
-   Tipe Material
-   Kualitas
-   Harga Beli
-   Harga Jual
-   Stok Minimum
-   Status - Aktif/Nonaktif
-   **Gudang** - **BARU!** Nama gudang untuk stok
-   **Qty** - **BARU!** Jumlah stok

---

## 🎯 CARA KERJA

### **1. Import Produk Saja**

```excel
Nama         | Kategori | Satuan | Harga Beli | Gudang | Qty
Produk A     | Cat 1    | Pcs    | 10000      |        |
Produk B     | Cat 2    | Box    | 20000      |        |
```

**Hasil:**

-   ✅ Produk masuk ke database
-   ❌ Stok TIDAK dibuat (karena Gudang & Qty kosong)

---

### **2. Import Produk + Stok**

```excel
Nama      | Kategori | Satuan | Harga Beli | Gudang       | Qty
Produk A  | Cat 1    | Pcs    | 10000      | Gudang Utama | 100
Produk B  | Cat 2    | Box    | 20000      | Gudang Utama | 50
```

**Hasil:**

-   ✅ Produk masuk ke database
-   ✅ Stok otomatis dibuat di `stok_produk`
-   ✅ Qty tercatat di gudang yang dipilih

---

### **3. Jenis Produk Kosong**

```excel
Nama      | Jenis | Kategori | Satuan
Produk A  |       | Cat 1    | Pcs
Produk B  | null  | Cat 2    | Box
Produk C  | Metal | Cat 3    | Kg
```

**Hasil:**

-   Produk A & B: `jenis_id` = **NULL** (tidak buat jenis baru)
-   Produk C: Jenis "Metal" dibuat/digunakan

---

## ⚠️ PENTING: Rules Import Stok

### **Stok akan dibuat HANYA jika:**

1. ✅ Kolom **Gudang** ADA dan TIDAK KOSONG
2. ✅ Kolom **Qty** ADA dan TIDAK KOSONG
3. ✅ Gudang dengan nama tersebut **ADA di database**
4. ✅ Gudang dalam status **Aktif**
5. ✅ Qty > 0

### **Stok TIDAK akan dibuat jika:**

-   ❌ Gudang kosong
-   ❌ Qty kosong atau 0
-   ❌ Gudang tidak ditemukan
-   ❌ Gudang tidak aktif

---

## 📝 CONTOH FILE EXCEL

### **Skenario: Migrasi Stok Awal**

```excel
Kode  | Nama           | Jenis | Kategori | Satuan | Harga Beli | Gudang Utama | Qty
      | Baut M10       |       | Hardware | Pcs    | 5000       | Gudang Utama | 1000
      | Mur M10        |       | Hardware | Pcs    | 3000       | Gudang Utama | 2000
      | Besi Hollow 4x4| Metal | Material | Batang | 150000     | Gudang Utama | 50
```

**Hasil:**

```
✅ 3 Produk dibuat
✅ Kategori "Hardware" & "Material" dibuat otomatis
✅ Satuan "Pcs" & "Batang" dibuat otomatis
✅ Jenis "Metal" dibuat (untuk Besi Hollow)
✅ Jenis TIDAK dibuat untuk Baut & Mur (kolom kosong)
✅ 3 Record stok_produk dibuat di Gudang Utama
```

---

## 🚀 CARA IMPORT

### **Step 1: Download Template**

```
Menu: Master Data → Produk → Download Template
```

### **Step 2: Isi Template**

```
- Nama produk (wajib)
- Harga beli (recommended untuk kalibrasi)
- Gudang & Qty (jika ingin langsung input stok)
```

### **Step 3: Upload**

```
Menu: Master Data → Produk → Import Excel
```

### **Step 4: Verifikasi**

```
Setelah import berhasil:
1. Cek Master Data Produk → Produk tersimpan ✓
2. Cek Laporan Stok → Qty muncul ✓
3. Cek jurnal (TIDAK ADA) - import stok TIDAK buat jurnal
```

---

## ⚠️ CATATAN PENTING

### **Import Stok vs Penyesuaian Stok**

| Fitur             | Import Produk + Stok | Penyesuaian Stok   |
| ----------------- | -------------------- | ------------------ |
| **Input Produk**  | ✅ Ya                | ❌ Tidak           |
| **Input Stok**    | ✅ Ya                | ✅ Ya              |
| **Buat Jurnal**   | ❌ **TIDAK**         | ✅ **YA**          |
| **Untuk Migrasi** | ⚠️ Bisa, tapi...     | ✅ **REKOMENDASI** |

### **❗ PERBEDAAN KRUSIAL:**

**Import Produk + Stok:**

```
- Hanya update tabel stok_produk
- TIDAK membuat jurnal akuntansi
- Nilai persediaan di akuntansi = 0
- Butuh Kalibrasi Persediaan setelahnya
```

**Penyesuaian Stok:**

```
- Update tabel stok_produk
- OTOMATIS buat jurnal akuntansi
- Nilai persediaan di akuntansi = qty × harga
- Siap untuk Kalibrasi Persediaan
```

---

## 🎯 REKOMENDASI UNTUK MIGRASI STOK AWAL

### **OPSI A: Import + Penyesuaian Stok** ⭐ (Terbaik)

```
1. Import Excel Produk (tanpa Gudang & Qty)
   → Produk + harga_beli tersimpan

2. Penyesuaian Stok (manual via form)
   → Input qty per gudang
   → Jurnal otomatis dibuat

3. Kalibrasi Persediaan
   → Review nilai
   → Sinkronkan
```

**Keuntungan:** Jurnal lengkap, audit trail jelas

---

### **OPSI B: Import + Manual Jurnal** (Advanced)

```
1. Import Excel Produk dengan Gudang & Qty
   → Produk + stok langsung masuk
   → Nilai akuntansi masih 0

2. Kalibrasi Persediaan
   → Sistem deteksi selisih
   → Buat jurnal: Debit Persediaan, Kredit Modal
```

**Keuntungan:** Lebih cepat untuk data banyak

---

## 🔍 TROUBLESHOOTING

### **Q: Stok tidak masuk setelah import?**

**A: Cek:**

1. ✅ Kolom "Gudang" terisi?
2. ✅ Kolom "Qty" terisi dan > 0?
3. ✅ Nama gudang sesuai di Master Data Gudang?
4. ✅ Gudang dalam status Aktif?

**Solusi:**

```sql
-- Cek gudang yang tersedia
SELECT nama, kode, is_active FROM gudang;

-- Pastikan nama persis sama di Excel
```

---

### **Q: Jenis produk tetap dibuat padahal kolom kosong?**

**A:** Pastikan kolom benar-benar kosong, bukan spasi atau karakter tersembunyi.

**Fix:**

```
Di Excel:
1. Select kolom Jenis
2. Find & Replace
3. Find: (kosongkan)
4. Replace: (kosongkan)
5. Replace All
```

---

### **Q: Error "Gudang tidak ditemukan"?**

**A:** Nama gudang di Excel harus **PERSIS SAMA** dengan di database.

**Contoh:**

```
❌ SALAH:
Excel: "gudang utama"
Database: "Gudang Utama"

✅ BENAR:
Excel: "Gudang Utama"
Database: "Gudang Utama"
```

---

## 📊 SUMMARY

### **Yang Berubah:**

1. ✅ Template Excel punya kolom **Gudang** & **Qty**
2. ✅ Import bisa langsung input stok (jika diisi)
3. ✅ Jenis produk **SKIP** jika kosong (tidak buat baru)
4. ✅ Log lengkap untuk tracking stok yang dibuat

### **Yang Tetap Sama:**

-   Kategori & Satuan tetap dibuat otomatis
-   Validasi tetap berjalan
-   Statistics tracking tetap ada

---

**Updated:** 6 Januari 2026  
**Status:** ✅ Implemented & Tested
