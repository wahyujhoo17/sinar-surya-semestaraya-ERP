# Fix Status Sales Order pada Partial Invoice

## Masalah yang Diperbaiki

Masalah: Status sales order langsung menjadi "lunas" ketika satu invoice dibayar penuh, padahal masih ada invoice lain yang belum dibuat atau dibayar.

**Contoh Kasus:**

-   Total Sales Order: Rp 20.000.000
-   Invoice pertama: Rp 10.000.000 (dibayar lunas)
-   Invoice kedua: Rp 10.000.000 (belum dibuat)
-   Status SO seharusnya: "sebagian" (bukan "lunas")

## Root Cause

1. **PembayaranPiutangController.php** langsung mengupdate status sales order ke "lunas" ketika satu invoice dibayar penuh, tanpa mempertimbangkan apakah semua invoice telah dibuat dan dibayar.

2. **InvoiceController.php** memiliki logika yang salah dalam `updateSalesOrderStatus()` - membandingkan total pembayaran dengan total sales order, bukan dengan total yang sudah di-invoice.

## Solusi yang Diterapkan

### 1. Perbaikan Logika Status Sales Order

**File:** `app/Http/Controllers/Penjualan/InvoiceController.php`

```php
// Sales order can only be "lunas" if it's fully invoiced AND all invoices are paid
if ($totalInvoiced >= $totalSalesOrder && $totalTerbayar >= $totalInvoiced) {
    $salesOrder->status_pembayaran = 'lunas';
} elseif ($totalTerbayar > 0) {
    $salesOrder->status_pembayaran = 'sebagian';
} else {
    $salesOrder->status_pembayaran = 'belum_bayar';
}
```

**Logika Baru:**

-   Sales order hanya bisa "lunas" jika:
    1. **Fully invoiced** (`$totalInvoiced >= $totalSalesOrder`)
    2. **All invoices paid** (`$totalTerbayar >= $totalInvoiced`)

### 2. Sentralisasi Update Status

**File:** `app/Http/Controllers/Penjualan/InvoiceController.php`

Menambahkan method public static untuk dipanggil dari controller lain:

```php
public static function updateSalesOrderStatusFromPayment($salesOrderId)
{
    $instance = new self();
    return $instance->updateSalesOrderStatus($salesOrderId);
}
```

### 3. Perbaikan PembayaranPiutangController

**File:** `app/Http/Controllers/Keuangan/PembayaranPiutangController.php`

Mengganti direct update status dengan centralized method:

```php
// Before
$salesOrder->status_pembayaran = 'lunas';

// After
InvoiceController::updateSalesOrderStatusFromPayment($salesOrder->id);
```

## Test Case

### Scenario 1: Partial Invoice dengan Pembayaran Penuh pada Invoice Pertama

1. **Setup:**

    - Sales Order: Rp 20.000.000
    - Invoice 1: Rp 10.000.000 (dibayar lunas)
    - Invoice 2: Belum dibuat

2. **Expected Result:**
    - Status SO: "sebagian" (bukan "lunas")
    - Status Invoice 1: "lunas"

### Scenario 2: Semua Invoice Dibuat dan Dibayar

1. **Setup:**

    - Sales Order: Rp 20.000.000
    - Invoice 1: Rp 10.000.000 (dibayar lunas)
    - Invoice 2: Rp 10.000.000 (dibayar lunas)

2. **Expected Result:**
    - Status SO: "lunas"
    - Status Invoice 1: "lunas"
    - Status Invoice 2: "lunas"

### Scenario 3: Fully Invoiced tapi Belum Semua Dibayar

1. **Setup:**

    - Sales Order: Rp 20.000.000
    - Invoice 1: Rp 10.000.000 (dibayar lunas)
    - Invoice 2: Rp 10.000.000 (belum dibayar)

2. **Expected Result:**
    - Status SO: "sebagian" (bukan "lunas")
    - Status Invoice 1: "lunas"
    - Status Invoice 2: "belum_bayar"

## Status Fields yang Diupdate

1. **status_invoice**: Berdasarkan total invoice vs total SO

    - `not_invoiced`: Belum ada invoice
    - `partially_invoiced`: Sebagian di-invoice
    - `fully_invoiced`: Semua di-invoice

2. **status_pembayaran**: Berdasarkan pembayaran vs total yang sudah di-invoice
    - `belum_bayar`: Belum ada pembayaran
    - `sebagian`: Sebagian dibayar
    - `lunas`: Fully invoiced DAN semua invoice dibayar

## Files Modified

1. `app/Http/Controllers/Penjualan/InvoiceController.php`
2. `app/Http/Controllers/Keuangan/PembayaranPiutangController.php`

## Date

18 Juli 2025

## Update - 18 Juli 2025 (Perbaikan Kedua)

### Masalah yang Ditemukan:

Setelah fix pertama, status sales order masih tidak berubah menjadi "sebagian" ketika ada pembayaran.

### Root Cause:

**PembayaranPiutangController** memanggil `updateSalesOrderStatusFromPayment()` tetapi kemudian memanggil `$salesOrder->refresh()` dan `$salesOrder->save()` yang menimpa status yang sudah di-update.

### Fix yang Diterapkan:

1. **Menghapus operasi yang konflik:**

    ```php
    // Before (SALAH)
    InvoiceController::updateSalesOrderStatusFromPayment($salesOrder->id);
    $salesOrder->refresh(); // ❌ Ini menimpa status yang sudah di-update
    $salesOrder->total_pembayaran = $totalPaymentsBefore + $pembayaran->jumlah;
    $salesOrder->save(); // ❌ Ini menimpa status lagi

    // After (BENAR)
    $salesOrder->total_pembayaran = $totalPaymentsBefore + $pembayaran->jumlah;
    $salesOrder->save();
    InvoiceController::updateSalesOrderStatusFromPayment($salesOrder->id); // ✅ Dipanggil setelah save
    ```

2. **Menambahkan logging detail untuk debugging:**

    - Status sebelum dan sesudah update
    - Calculation result yang menunjukkan kondisi yang harus dipenuhi
    - Dirty attributes tracking

3. **Memperbaiki urutan operasi:**
    - Save `total_pembayaran` dulu
    - Baru panggil centralized status update method

### Files Modified:

-   `app/Http/Controllers/Keuangan/PembayaranPiutangController.php`
-   `app/Http/Controllers/Penjualan/InvoiceController.php`
-   `SALES_ORDER_STATUS_DEBUG.md` (dokumentasi debug)
