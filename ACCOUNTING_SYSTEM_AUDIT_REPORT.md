# 📊 LAPORAN AUDIT SISTEM AKUNTANSI ERP

## Sinar Surya Semestaraya

**Tanggal Audit:** 22 November 2025  
**Auditor:** System Analysis  
**Scope:** Koneksi COA dengan Sistem Pembelian, Penjualan, dan Pembayaran

---

## 🎯 EXECUTIVE SUMMARY

### Status Keseluruhan: ⚠️ **MEMERLUKAN PERBAIKAN**

Sistem akuntansi ERP memiliki **infrastruktur database-driven yang baik**, namun **belum terimplementasi penuh** pada model-model transaksi. Terdapat **inkonsistensi** antara konfigurasi database dengan penggunaan config file static.

### Temuan Utama:

1. ✅ **Konfigurasi Database Sudah Dibuat** - 16 mapping akun tersimpan di database
2. ❌ **Model Belum Menggunakan Database** - Masih menggunakan `config('accounting.*')`
3. ✅ **Helper Function Sudah Ada** - `accounting($key)` sudah tersedia
4. ⚠️ **Invoice Model Bermasalah** - Menggunakan field yang tidak ada di config

---

## 📋 DETAIL TEMUAN

### 1. STATUS KONFIGURASI AKUN (Database)

#### ✅ Sudah Terkonfigurasi (13 dari 16):

**Pembayaran Hutang** (3/3):

-   ✅ `pembayaran_hutang.hutang_usaha` → 2111 - Hutang Usaha
-   ✅ `pembayaran_hutang.kas` → 1001 - KAS 1
-   ✅ `pembayaran_hutang.bank` → 1002 - Rekening Mandiri

**Penggajian** (4/5):

-   ✅ `penggajian.beban_gaji` → 60301 - BIAYA GAJI BAG. KANTOR
-   ✅ `penggajian.beban_atk` → 61014 - Biaya ATK
-   ✅ `penggajian.kas` → 1001 - KAS 1
-   ✅ `penggajian.bank` → 1002 - Rekening Mandiri
-   ❌ `penggajian.beban_utilitas` → **BELUM DIKONFIGURASI**

**Penjualan** (2/2):

-   ✅ `penjualan.piutang_usaha` → 1110 - Piutang Usaha
-   ✅ `penjualan.penjualan` → 40001 - PENJUALAN

**Pembelian** (3/3):

-   ✅ `pembelian.persediaan` → 1120 - Persediaan Barang Dagang
-   ✅ `pembelian.ppn_masuk` → 1130 - PPN Masukan
-   ✅ `pembelian.hutang_usaha` → 2111 - Hutang Usaha

**Pembayaran Piutang** (3/3):

-   ✅ `pembayaran_piutang.piutang_usaha` → 1110 - Piutang Usaha
-   ✅ `pembayaran_piutang.kas` → 1001 - KAS 1
-   ✅ `pembayaran_piutang.bank` → 1002 - Rekening Mandiri

### 2. MASALAH PADA MODEL TRANSAKSI

#### ❌ Model Pembelian (`app/Models/Pembelian.php`)

**Baris 92-94:**

```php
$akunHutangUsaha = config('accounting.pembelian.hutang_usaha');
$akunPersediaan = config('accounting.pembelian.persediaan');
$akunPpnMasukan = config('accounting.pembelian.ppn_masukan');
```

**Masalah:** Menggunakan `config()` static, tidak mengambil dari database.

#### ❌ Model Invoice (`app/Models/Invoice.php`)

**Baris 223-225:**

```php
$akunPiutangUsaha = config('accounting.penjualan.piutang_usaha');
$akunPendapatanPenjualan = config('accounting.penjualan.pendapatan_penjualan');
$akunPpnKeluaran = config('accounting.penjualan.ppn_keluaran');
```

**Masalah Kritis:**

1. Menggunakan `config()` static
2. Field `pendapatan_penjualan` **TIDAK ADA** di config/accounting.php
3. Field `ppn_keluaran` **TIDAK ADA** di database seeder
4. Database hanya punya `penjualan.penjualan` dan `penjualan.piutang_usaha`

#### ❌ Model PembayaranHutang (`app/Models/PembayaranHutang.php`)

**Baris 93-95:**

```php
$akunHutangUsaha = config('accounting.pembayaran_hutang.hutang_usaha');
$akunKasDefault = config('accounting.pembayaran_hutang.kas');
$akunBankDefault = config('accounting.pembayaran_hutang.bank');
```

**Masalah:** Menggunakan `config()` static, tidak mengambil dari database.

#### ❌ Model PembayaranPiutang (`app/Models/PembayaranPiutang.php`)

**Baris 95-97:**

```php
$akunPiutangUsaha = config('accounting.pembayaran_piutang.piutang_usaha');
$akunKasDefault = config('accounting.pembayaran_piutang.kas');
$akunBankDefault = config('accounting.pembayaran_piutang.bank');
```

**Masalah:** Menggunakan `config()` static, tidak mengambil dari database.

---

## 🔧 REKOMENDASI PERBAIKAN

### PRIORITAS TINGGI (Segera)

#### 1. Update Seeder - Tambah Konfigurasi yang Hilang

**File:** `database/seeders/AccountingConfigurationSeeder.php`

Tambahkan konfigurasi berikut:

```php
// Penjualan - Tambahan
[
    'transaction_type' => 'penjualan',
    'account_key' => 'pendapatan_penjualan',
    'account_name' => 'Pendapatan Penjualan',
    'akun_id' => null, // Perlu dikalibrasi user
    'is_required' => true,
    'description' => 'Akun pendapatan yang akan dikredit saat penjualan'
],
[
    'transaction_type' => 'penjualan',
    'account_key' => 'ppn_keluaran',
    'account_name' => 'PPN Keluaran',
    'akun_id' => null, // Perlu dikalibrasi user
    'is_required' => false,
    'description' => 'Akun PPN keluaran yang akan dikredit saat penjualan dengan PPN'
],

// Penggajian - Lengkapi yang kurang
[
    'transaction_type' => 'penggajian',
    'account_key' => 'beban_utilitas',
    'account_name' => 'Beban Utilitas',
    'akun_id' => 173, // BIAYA LISTRIK, TELP/INTERNET & PAM
    'is_required' => false,
    'description' => 'Akun beban utilitas yang akan didebit saat penggajian'
],
```

#### 2. Update Model Pembelian

**File:** `app/Models/Pembelian.php` - Line 92-94

**GANTI:**

```php
$akunHutangUsaha = config('accounting.pembelian.hutang_usaha');
$akunPersediaan = config('accounting.pembelian.persediaan');
$akunPpnMasukan = config('accounting.pembelian.ppn_masukan');
```

**DENGAN:**

```php
use App\Models\AccountingConfiguration;

$akunHutangUsaha = AccountingConfiguration::getAccountId('pembelian.hutang_usaha');
$akunPersediaan = AccountingConfiguration::getAccountId('pembelian.persediaan');
$akunPpnMasukan = AccountingConfiguration::getAccountId('pembelian.ppn_masuk');
```

#### 3. Update Model Invoice

**File:** `app/Models/Invoice.php` - Line 223-225

**GANTI:**

```php
$akunPiutangUsaha = config('accounting.penjualan.piutang_usaha');
$akunPendapatanPenjualan = config('accounting.penjualan.pendapatan_penjualan');
$akunPpnKeluaran = config('accounting.penjualan.ppn_keluaran');
```

**DENGAN:**

```php
use App\Models\AccountingConfiguration;

$akunPiutangUsaha = AccountingConfiguration::getAccountId('penjualan.piutang_usaha');
$akunPendapatanPenjualan = AccountingConfiguration::getAccountId('penjualan.pendapatan_penjualan');
$akunPpnKeluaran = AccountingConfiguration::getAccountId('penjualan.ppn_keluaran');
```

#### 4. Update Model PembayaranHutang

**File:** `app/Models/PembayaranHutang.php` - Line 93-95

**GANTI:**

```php
$akunHutangUsaha = config('accounting.pembayaran_hutang.hutang_usaha');
$akunKasDefault = config('accounting.pembayaran_hutang.kas');
$akunBankDefault = config('accounting.pembayaran_hutang.bank');
```

**DENGAN:**

```php
use App\Models\AccountingConfiguration;

$akunHutangUsaha = AccountingConfiguration::getAccountId('pembayaran_hutang.hutang_usaha');
$akunKasDefault = AccountingConfiguration::getAccountId('pembayaran_hutang.kas');
$akunBankDefault = AccountingConfiguration::getAccountId('pembayaran_hutang.bank');
```

#### 5. Update Model PembayaranPiutang

**File:** `app/Models/PembayaranPiutang.php` - Line 95-97

**GANTI:**

```php
$akunPiutangUsaha = config('accounting.pembayaran_piutang.piutang_usaha');
$akunKasDefault = config('accounting.pembayaran_piutang.kas');
$akunBankDefault = config('accounting.pembayaran_piutang.bank');
```

**DENGAN:**

```php
use App\Models\AccountingConfiguration;

$akunPiutangUsaha = AccountingConfiguration::getAccountId('pembayaran_piutang.piutang_usaha');
$akunKasDefault = AccountingConfiguration::getAccountId('pembayaran_piutang.kas');
$akunBankDefault = AccountingConfiguration::getAccountId('pembayaran_piutang.bank');
```

---

## 📊 CHECKLIST IMPLEMENTASI

### Phase 1: Database Configuration (Sudah Selesai)

-   [x] Create migration for accounting_configurations table
-   [x] Create AccountingConfiguration model
-   [x] Create seeder with initial configurations
-   [x] Create helper function `accounting($key)`
-   [x] Create UI for calibration
-   [x] Add routes and controller

### Phase 2: Model Migration (Belum Selesai - PRIORITAS!)

-   [ ] Update AccountingConfigurationSeeder - tambah konfigurasi yang hilang
-   [ ] Run seeder: `php artisan db:seed --class=AccountingConfigurationSeeder`
-   [ ] Update Model Pembelian
-   [ ] Update Model Invoice
-   [ ] Update Model PembayaranHutang
-   [ ] Update Model PembayaranPiutang
-   [ ] Update Model ReturPembelian (jika ada)
-   [ ] Update Model ReturPenjualan (jika ada)
-   [ ] Update Model Penggajian (jika ada)

### Phase 3: Testing & Validation

-   [ ] Test pembuatan Purchase Order baru
-   [ ] Test pembayaran hutang
-   [ ] Test pembuatan Invoice/Sales Order
-   [ ] Test penerimaan pembayaran piutang
-   [ ] Verify journal entries created correctly
-   [ ] Verify COA balances updated correctly

### Phase 4: Data Migration (Optional)

-   [ ] Backup existing data
-   [ ] Migrate old config values to database
-   [ ] Verify all transactions working correctly
-   [ ] Remove old config file (optional)

---

## ⚠️ RISIKO & MITIGASI

### Risiko 1: Konfigurasi Belum Lengkap

**Dampak:** Transaksi gagal membuat jurnal  
**Mitigasi:** Wajibkan user mengisi kalibrasi sebelum transaksi pertama

### Risiko 2: Data Lama Menggunakan Config Lama

**Dampak:** Inkonsistensi data historical  
**Mitigasi:** Update model dengan fallback ke config jika database kosong

### Risiko 3: User Tidak Paham Kalibrasi

**Dampak:** Salah mapping akun  
**Mitigasi:** Tambahkan dokumentasi dan video tutorial

---

## 📝 KESIMPULAN

Sistem akuntansi ERP **sudah memiliki fondasi yang baik** dengan:

-   ✅ Database-driven configuration system
-   ✅ UI kalibrasi yang user-friendly
-   ✅ Helper function untuk akses configuration

Namun **belum sepenuhnya terintegrasi** karena:

-   ❌ Model transaksi masih menggunakan config file static
-   ❌ Beberapa konfigurasi akun masih hilang di database
-   ❌ Tidak ada validasi kalibrasi sebelum transaksi

**Estimasi Waktu Perbaikan:** 2-3 jam untuk implementasi lengkap

**Rekomendasi:** Segera lakukan Phase 2 (Model Migration) sebelum sistem digunakan untuk transaksi real.

---

## 📞 KONTAK SUPPORT

Jika ada pertanyaan atau butuh bantuan implementasi:

-   Documentation: `/docs/accounting-configuration.md`
-   Kalibrasi UI: `/keuangan/accounting-configuration`
-   Technical Support: Developer Team

---

**Generated by:** System Analysis Tool  
**Report Version:** 1.0  
**Last Updated:** 22 November 2025
