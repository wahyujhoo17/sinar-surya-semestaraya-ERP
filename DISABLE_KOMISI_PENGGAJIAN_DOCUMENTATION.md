# Dokumentasi Penonaktifan Sementara Komisi Penjualan pada Penggajian & Slip Gaji

> **Status:** DINONAKTIFKAN SEMENTARA (TEMPORARILY DISABLED)  
> **Tanggal:** 4 Agustus 2026  
> **Tag Kode:** `[KOMISI_DISABLED_TEMPORARILY]`  

---

## 📌 Ringkasan Kebijakan

Fitur **Komisi Penjualan** pada modul **Penggajian dan Tunjangan** serta **PDF Slip Gaji** dinonaktifkan sementara atas permintaan sistem/manajemen. 

- **Tidak Ada Kode Yang Dihapus**: Seluruh struktur basis data, rumus tier komisi, serta logika perhitungan komisi berbasis margin penjualan tetap dipertahankan secara utuh di dalam basis kode (*codebase*).
- **Semua Perubahan Disertai Tag**: Setiap titik penyesuaian ditandai dengan pencarian label `[KOMISI_DISABLED_TEMPORARILY]`.

---

## 🛠️ Daftar File & Perubahan yang Dilakukan

### 1. `app/Http/Controllers/hr_karyawan/PenggajianController.php`
- **Method `hitungKomisiKaryawan()`**: Ditambahkan variabel `$enableKomisi = false;` di awal method yang mengembalikan `['komisi' => 0, 'salesOrderIds' => [], 'details' => []]`.
- **Method `getKomisiKaryawan()`**: Ditambahkan variabel `$enableKomisi = false;` yang mengembalikan respon JSON komisi = 0.
- **Method `store()`**: Ditambahkan flag `$enableKomisi = false;`. Komisi diset ke `0` dan komponen `Komisi Penjualan` tidak ditambahkan ke rincian gaji.
- **Method `update()`**: Ditambahkan flag `$enableKomisi = false;`. Perhitungan ulang komisi diset ke `0`.

### 2. `resources/views/hr_karyawan/penggajian_dan_tunjangan/pdf.blade.php`
- **Tabel Pendapatan**: Filter `$manualKomponenPendapatan` menolak (*reject*) komponen dengan `nama_komponen === 'Komisi Penjualan'`.
- **Total Gaji Bersih**: Menggunakan rumus `$displayTotalGajiBersih = $totalPendapatan - $totalPotonganVal` sehingga nilai total bersih pada PDF selalu presisi dengan rincian pendapatan dikurangi potongan.

### 3. `resources/views/hr_karyawan/penggajian_dan_tunjangan/show.blade.php`
- **Tabel Rincian**: Filter `$manualKomponenPendapatan` menolak `Komisi Penjualan`.
- **Kartu Rincian Komisi**: Dibungkus dengan kondisi `@if(false && $komisiKomponen->count() > 0)` agar kartu rincian komisi tidak dirender di web.

### 4. `resources/views/hr_karyawan/penggajian_dan_tunjangan/create.blade.php`
- **Kartu Perhitungan Komisi**: Diubah atributnya menjadi `x-show="false && commissionData.orders.length > 0"` agar blok komisi tidak tampil pada form pembuatan penggajian baru.

### 5. `resources/views/hr_karyawan/penggajian_dan_tunjangan/edit.blade.php`
- **Kartu Perhitungan Komisi**: Diubah atributnya menjadi `x-show="false && commissionData.orders.length > 0"` agar blok komisi tidak tampil pada form edit penggajian.

---

## 🔄 Panduan Pemulihan / Mengaktifkan Kembali Komisi (Restore Guide)

Jika di kemudian hari fitur Komisi Penjualan ingin diaktifkan kembali, ikuti langkah sederhana berikut:

### Langkah 1: Aktifkan Backend Controller
Buka file `app/Http/Controllers/hr_karyawan/PenggajianController.php`:
1. Cari tag `[KOMISI_DISABLED_TEMPORARILY]`.
2. Ubah `$enableKomisi = false;` menjadi `$enableKomisi = true;` pada 4 lokasi method:
   - `hitungKomisiKaryawan()`
   - `getKomisiKaryawan()`
   - `store()`
   - `update()`

### Langkah 2: Aktifkan Tampilan PDF Slip Gaji
Buka file `resources/views/hr_karyawan/penggajian_dan_tunjangan/pdf.blade.php`:
1. Pada `$manualKomponenPendapatan`, hapus kondisi `|| $komponen->nama_komponen === 'Komisi Penjualan'`.

### Langkah 3: Aktifkan Tampilan Detail Web (Show)
Buka file `resources/views/hr_karyawan/penggajian_dan_tunjangan/show.blade.php`:
1. Pada `$manualKomponenPendapatan`, hapus kondisi `|| $komponen->nama_komponen === 'Komisi Penjualan'`.
2. Cari `@if (false && $komisiKomponen->count() > 0)` dan ubah `false` kembali menjadi `true` (atau hapus `false &&`).

### Langkah 4: Aktifkan Form Create & Edit
1. Di `resources/views/hr_karyawan/penggajian_dan_tunjangan/create.blade.php`:
   Ubah `x-show="false && commissionData.orders.length > 0"` kembali menjadi `x-show="commissionData.orders.length > 0"`.
2. Di `resources/views/hr_karyawan/penggajian_dan_tunjangan/edit.blade.php`:
   Ubah `x-show="false && commissionData.orders.length > 0"` kembali menjadi `x-show="commissionData.orders.length > 0"`.

---

*Dokumen ini dibuat secara otomatis sebagai panduan pemulihan sistem ERP Sinar Surya Semestaraya.*
