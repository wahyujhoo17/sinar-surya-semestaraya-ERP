# Perbaikan Laporan Laba Rugi (Income Statement)

## Tanggal: 19 November 2025

## Ringkasan Masalah

Laporan Laba Rugi memiliki beberapa masalah dalam perhitungan dan penampilan data yang menyebabkan ketidakakuratan:

### 1. **Masalah Pendapatan (Revenue)**

-   **Sebelumnya**: Menggunakan total Sales Order tanpa mempertimbangkan apakah sudah ada invoice
-   **Masalah**: Pendapatan diakui sebelum waktunya (belum ada invoice)
-   **Dampak**: Overstating pendapatan

### 2. **Masalah HPP (COGS)**

-   **Sebelumnya**: Menggunakan total Purchase Order secara langsung
-   **Masalah**: Tidak sesuai prinsip akrual - pembelian ≠ HPP
-   **Dampak**: COGS tidak akurat karena tidak mempertimbangkan perubahan persediaan

### 3. **Masalah Duplikasi Gaji**

-   **Sebelumnya**: Menghitung gaji dari sistem payroll DAN jurnal
-   **Masalah**: Double counting - gaji yang sama dihitung 2x
-   **Dampak**: Beban operasional terlalu besar (overstated)

## Perbaikan yang Dilakukan

### 1. **Perbaikan Revenue Recognition**

```php
// Sekarang: Hanya SO yang sudah punya invoice
$salesRevenue = DB::table('sales_order')
    ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('invoice')
            ->whereColumn('invoice.sales_order_id', 'sales_order.id')
            ->whereNotNull('invoice.nomor');
    })
    ->whereNotNull('total')
    ->where('total', '>', 0)
    ->sum('total');

// Menggunakan jurnal sebagai sumber utama
$allIncome = $this->getAccountBalanceForPeriod('income', $tanggalAwal, $tanggalAkhir);

// Pisahkan penjualan utama vs pendapatan lain
$mainSalesAccounts = $allIncome->filter(function ($account) {
    $nama = strtolower($account['nama']);
    $kode = $account['kode'];
    return preg_match('/^4[- ]?1/', $kode) ||
           str_contains($nama, 'penjualan') ||
           str_contains($nama, 'sales');
});

$otherIncome = $allIncome->filter(function ($account) use ($mainSalesAccounts) {
    return !$mainSalesAccounts->contains('id', $account['id']);
});

// Gunakan jurnal jika ada, kalau tidak ada gunakan SO
$finalSalesRevenue = $totalSalesFromJournal > 0 ? $totalSalesFromJournal : $salesRevenue;
```

**Penjelasan**:

-   Mengutamakan data dari **jurnal umum** (basis akrual yang benar)
-   Hanya mengakui pendapatan dari SO yang **sudah memiliki invoice**
-   Memisahkan **pendapatan penjualan** vs **pendapatan lain-lain**
-   Filter akun berdasarkan kode (4-1xxx) dan nama

### 2. **Perbaikan COGS (Harga Pokok Penjualan)**

```php
// Sekarang: Hanya dari jurnal HPP
$cogsAccounts = AkunAkuntansi::where('kategori', 'expense')
    ->where('is_active', true)
    ->where(function ($q) {
        $q->where('nama', 'LIKE', '%harga pokok%')
            ->orWhere('nama', 'LIKE', '%cost of goods%')
            ->orWhere('nama', 'LIKE', '%cogs%')
            ->orWhere('nama', 'LIKE', '%hpp%')
            ->orWhere('kode', 'LIKE', '51%')
            ->orWhere('kode', 'LIKE', '5-1%');
    })
    ->pluck('id');

$totalCogs = JurnalUmum::whereIn('akun_id', $cogsAccounts)
    ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
    ->sum(DB::raw('debit - kredit'));
```

**Penjelasan**:

-   **TIDAK lagi menggunakan** total Purchase Order
-   Hanya mengambil dari **akun HPP di jurnal** (basis akrual)
-   Filter berdasarkan kode akun 51xx atau 5-1xx
-   HPP harus sudah di-jurnal dengan benar saat terjadi penjualan

### 3. **Perbaikan Beban Gaji (Menghilangkan Duplikasi)**

```php
// Sekarang: HANYA dari jurnal
$salaryRelatedAccounts = $operationalExpenseAccounts->filter(function ($expense) {
    $nama = strtolower($expense['nama']);
    $kode = $expense['kode'];

    $salaryCodePattern = preg_match('/^(51|5211|611|621|6211|5-2-11|6-1-1|6-2-1)/', $kode);
    $salaryNamePattern = str_contains($nama, 'gaji') ||
        str_contains($nama, 'salary') ||
        str_contains($nama, 'upah') ||
        str_contains($nama, 'tunjangan') ||
        str_contains($nama, 'payroll');

    return $salaryCodePattern || $salaryNamePattern;
});

// Total hanya dari jurnal (TIDAK ada lagi dari sistem payroll)
$totalOperatingExpenses = $totalSalaryFromJournal + $totalUtilities + ...
```

**Penjelasan**:

-   **Menghapus** perhitungan gaji dari sistem payroll di laporan laba rugi
-   Hanya menggunakan **data dari jurnal beban gaji**
-   Asumsi: Sistem payroll sudah membuat jurnal otomatis
-   Menghindari **double counting**

### 4. **Perbaikan Filter Beban Operasional**

```php
// Tambah support untuk kode dengan dash separator
$isOperationalByCode = preg_match('/^(5[2-9]|6[1-9]|5-[2-9]|6-[1-9])/', $kode);
```

**Penjelasan**:

-   Mendukung format kode dengan **dash** (5-2, 6-1, dll)
-   Lebih fleksibel dalam mengenali akun operasional

## Perubahan di View (Frontend)

### 1. **Tampilan Pendapatan**

-   Menampilkan **detail akun penjualan** dari jurnal
-   Memisahkan **pendapatan penjualan** dan **pendapatan lain**
-   Menampilkan kode dan nama akun untuk transparansi

### 2. **Tampilan HPP**

-   Hanya menampilkan **Total HPP dari jurnal**
-   Tidak ada lagi "Pembelian" dan "HPP Lainnya"
-   Lebih sederhana dan akurat

### 3. **Tampilan Beban Gaji**

-   Menghapus "Gaji Karyawan (Sistem Payroll)"
-   Hanya menampilkan **"Biaya Gaji & Tunjangan (dari jurnal)"**
-   Menampilkan detail akun gaji

## Logging untuk Debugging

Ditambahkan logging untuk memudahkan debugging:

```php
Log::info('Income Accounts from Journal', [...]);
Log::info('COGS Calculation', [...]);
Log::info('Operating Expenses Accounts', [...]);
Log::info('Operating Expenses Summary', [...]);
```

## Dampak Perubahan

### ✅ **Manfaat**:

1. **Lebih Akurat** - Mengikuti prinsip akuntansi akrual
2. **Tidak Ada Duplikasi** - Menghilangkan double counting gaji
3. **Transparansi** - Menampilkan detail akun untuk verifikasi
4. **Konsisten** - Semua data dari satu sumber (jurnal umum)

### ⚠️ **Perhatian**:

1. **Harus Ada Jurnal** - Semua transaksi harus sudah di-jurnal
2. **HPP Manual** - HPP harus di-jurnal manual saat penjualan
3. **Gaji Harus Dijurnal** - Sistem payroll harus otomatis membuat jurnal

## Rekomendasi Selanjutnya

### 1. **Otomasi Jurnal HPP**

Buat sistem yang otomatis membuat jurnal HPP saat:

-   Invoice dibuat
-   Barang dikirim
-   Berdasarkan metode FIFO/Average

### 2. **Validasi Data**

Tambahkan validasi untuk memastikan:

-   Setiap invoice punya jurnal pendapatan
-   Setiap penjualan punya jurnal HPP
-   Setiap payroll punya jurnal beban gaji

### 3. **Reconciliation Report**

Buat laporan rekonsiliasi untuk membandingkan:

-   Total SO vs Total Jurnal Pendapatan
-   Total PO vs Total Persediaan
-   Total Payroll vs Total Jurnal Gaji

## Testing Checklist

-   [ ] Test dengan periode 1 bulan
-   [ ] Test dengan periode lebih dari 1 bulan
-   [ ] Verifikasi total pendapatan = jurnal pendapatan
-   [ ] Verifikasi HPP hanya dari jurnal
-   [ ] Verifikasi tidak ada duplikasi gaji
-   [ ] Cek semua kategori beban operasional
-   [ ] Export PDF & Excel
-   [ ] Validasi dengan data aktual

## Catatan Penting

> **PENTING**: Laporan ini sekarang sepenuhnya bergantung pada **JURNAL UMUM**.
> Pastikan semua transaksi sudah dijurnal dengan benar:
>
> -   Pendapatan → saat invoice dibuat
> -   HPP → saat penjualan terjadi
> -   Beban Gaji → saat proses payroll
> -   Beban Operasional lainnya → saat terjadi

---

## File yang Diubah

1. `app/Http/Controllers/Laporan/LaporanKeuanganController.php` - Logika perhitungan
2. `resources/views/laporan/laporan_keuangan/index.blade.php` - Tampilan UI

## Author

-   Tim Development ERP SemestaPro
-   Tanggal: 19 November 2025
