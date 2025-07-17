# Test Sales Order Status Update

## Command to Test

Setelah perubahan ini, untuk menguji apakah fix sudah bekerja:

1. **Buat test payment untuk sales order yang sudah ada invoice**
2. **Cek log di `storage/logs/laravel.log`** untuk melihat detail perhitungan

## Expected Behavior

### Scenario: Sales Order Rp 30.000, Invoice Rp 10.000, Payment Rp 10.000

**Expected Results:**

-   Status Invoice: `partially_invoiced` (10.000 dari 30.000)
-   Status Pembayaran: `sebagian` (10.000 sudah dibayar dari 10.000 yang di-invoice)

**Calculation Details:**

-   `total_sales_order`: 30.000
-   `total_invoiced`: 10.000
-   `total_pembayaran`: 10.000
-   `total_terbayar`: 10.000
-   `is_fully_invoiced`: false (10.000 < 30.000)
-   `is_fully_paid`: true (10.000 >= 10.000)
-   `should_be_sebagian`: true (karena tidak fully invoiced tapi ada pembayaran)

## Problem yang Diperbaiki

1. **PembayaranPiutangController**:

    - Menghapus `$salesOrder->refresh()` yang menimpa status yang sudah di-update
    - Menghapus duplicate `$salesOrder->save()` calls
    - Memanggil `updateSalesOrderStatusFromPayment()` SETELAH menyimpan `total_pembayaran`

2. **InvoiceController**:
    - Menambahkan logging yang lebih detail untuk debugging
    - Menambahkan tolerance untuk floating point comparison

## Cara Test Manual

1. **Buat Sales Order** dengan total Rp 30.000
2. **Buat Invoice** dengan total Rp 10.000 dari Sales Order tersebut
3. **Buat Payment** dengan jumlah Rp 10.000 untuk invoice tersebut
4. **Cek Status** di tabel `sales_order`:
    - `status_invoice` harus `partially_invoiced`
    - `status_pembayaran` harus `sebagian`

## Log yang Harus Dicek

Cek di `storage/logs/laravel.log` untuk log dengan pattern:

```
[timestamp] local.INFO: Sales Order Status Updated {...}
[timestamp] local.INFO: About to save sales order status {...}
```

## Next Steps

Jika masih ada masalah, cek:

1. Apakah ada observer atau listener yang mengubah status sales order setelah save
2. Apakah ada middleware yang interfere dengan update
3. Apakah ada race condition dalam concurrent updates

## Fix Summary

**File yang Diubah:**

1. `app/Http/Controllers/Keuangan/PembayaranPiutangController.php`
2. `app/Http/Controllers/Penjualan/InvoiceController.php`

**Perubahan Utama:**

-   Menghilangkan conflict antara centralized status update dan manual save
-   Menambahkan logging untuk debugging
-   Memperbaiki urutan operasi dalam payment controller
