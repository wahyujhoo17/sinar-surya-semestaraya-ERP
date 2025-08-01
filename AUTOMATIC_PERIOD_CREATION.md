# AUTOMATIC PERIOD CREATION SYSTEM

## Overview

Sistem ini telah dikonfigurasi untuk membuat periode akuntansi secara otomatis. Periode untuk tahun 2026 dan tahun-tahun berikutnya akan dibuat otomatis tanpa intervensi manual.

## Fitur Otomatis

### 1. Laravel Scheduler (Cron Jobs)

-   **Monthly Creation**: Setiap tanggal 1 dan 25 pada pukul 00:01, sistem akan membuat periode bulan berikutnya
-   **Yearly Creation**: Setiap 1 Januari pada pukul 00:05, sistem akan membuat semua periode untuk tahun tersebut

### 2. Startup Auto-Creation

-   Saat aplikasi dimulai, sistem otomatis membuat periode untuk 6 bulan ke depan

### 3. Manual Commands (Jika diperlukan)

#### Membuat periode bulanan otomatis:

```bash
# Membuat periode untuk 3 bulan ke depan (default)
php artisan periods:auto-create-monthly

# Membuat periode untuk 6 bulan ke depan
php artisan periods:auto-create-monthly --months=6

# Preview tanpa membuat (dry-run)
php artisan periods:auto-create-monthly --dry-run
```

#### Membuat periode tahunan:

```bash
# Membuat semua periode untuk tahun saat ini dan 2 tahun ke depan
php artisan periods:auto-create-yearly

# Membuat periode untuk 5 tahun ke depan
php artisan periods:auto-create-yearly --years=5

# Preview tanpa membuat (dry-run)
php artisan periods:auto-create-yearly --dry-run
```

#### Membuat periode untuk tahun spesifik:

```bash
# Membuat periode untuk tahun 2026
php artisan periods:create-monthly 2026

# Dengan force (override jika sudah ada)
php artisan periods:create-monthly 2026 --force
```

#### Update data existing:

```bash
# Update periode existing untuk mengisi kolom tahun dan bulan
php artisan periods:update-year-month
```

## Struktur Database

### Tabel: periode_akuntansi

Kolom baru yang ditambahkan:

-   `tahun` (integer): Tahun periode
-   `bulan` (integer): Bulan periode (1-12)
-   `keterangan` (text): Keterangan tambahan

### Index untuk Performance:

-   Index pada `(tahun, bulan)`
-   Index pada `tahun`

## Setup Production (Cron Job)

Untuk production server, tambahkan cron job berikut:

```bash
# Edit crontab
crontab -e

# Tambahkan baris berikut:
# Jalankan Laravel scheduler setiap menit
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Monitoring & Logging

### Log Files:

-   `storage/logs/scheduler.log`: Log dari Laravel scheduler
-   `storage/logs/laravel.log`: Log umum aplikasi termasuk period creation

### Check Status:

```bash
# Cek periode yang ada
php artisan tinker
>>> App\Models\PeriodeAkuntansi::where('tahun', 2026)->get()

# Cek periode yang hilang untuk tahun tertentu
>>> App\Models\PeriodeAkuntansi::getMissingMonthsForYear(2026)
```

## Troubleshooting

### Jika periode tidak dibuat otomatis:

1. Check apakah Laravel scheduler berjalan
2. Check log files untuk error
3. Jalankan manual command untuk test
4. Pastikan database migration sudah dijalankan

### Manual Fix:

```bash
# Buat periode yang hilang untuk tahun 2026
php artisan periods:auto-create-yearly --years=1

# Atau buat semua periode untuk 2026
php artisan periods:create-monthly 2026
```

## Status Implementation

✅ **COMPLETE**: Sistem otomatis periode akuntansi

-   ✅ Laravel Scheduler configured
-   ✅ Auto-creation commands created
-   ✅ Database migration completed
-   ✅ Existing data updated
-   ✅ Startup script configured
-   ✅ Manual commands available

## Periode 2026 Status

Berdasarkan test, periode untuk tahun 2026 akan otomatis dibuat:

-   Januari 2026: ✅ Sudah ada
-   Februari - Desember 2026: 🔄 Akan dibuat otomatis oleh sistem

### Next Steps:

Sistem sudah siap dan akan otomatis membuat periode 2026 sesuai schedule yang telah dikonfigurasi.
