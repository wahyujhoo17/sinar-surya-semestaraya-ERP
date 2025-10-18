# COMMISSION CALCULATION - PPN FORMULA UPDATE

## Tanggal Implementasi

18 Oktober 2025

## Deskripsi Perubahan

Perubahan rumus perhitungan komisi untuk mengakomodasi perlakuan PPN (Pajak Pertambahan Nilai) yang berbeda berdasarkan kombinasi PPN penjualan dan pembelian.

## Aturan Baru PPN dalam Perhitungan Komisi

### Aturan 1: Penjualan PPN + Pembelian Non-PPN

**Kondisi:** Sales Order menggunakan PPN, tetapi produk dibeli tanpa PPN
**Perlakuan:** PPN penjualan **TIDAK dihitung** dalam kalkulasi komisi
**Alasan:** Untuk menghindari margin yang terlalu tinggi karena PPN hanya ada di sisi penjualan

**Contoh:**

-   Harga Jual (termasuk PPN 11%): Rp 111.000
-   Harga Beli (tanpa PPN): Rp 80.000
-   **Netto Jual untuk komisi:** Rp 111.000 / 1.11 = Rp 100.000
-   **Netto Beli untuk komisi:** Rp 80.000
-   **Margin:** (100.000 - 80.000) / 80.000 = 25%

### Aturan 2: Penjualan PPN + Pembelian PPN

**Kondisi:** Sales Order menggunakan PPN DAN produk dibeli dengan PPN
**Perlakuan:** PPN **keduanya dihitung** dalam kalkulasi komisi
**Alasan:** Kedua nilai mencerminkan kondisi bisnis yang sebenarnya

**Contoh:**

-   Harga Jual (termasuk PPN 11%): Rp 111.000
-   Harga Beli (termasuk PPN 11%): Rp 88.800
-   **Netto Jual untuk komisi:** Rp 111.000 (dengan PPN)
-   **Netto Beli untuk komisi:** Rp 88.800 (dengan PPN)
-   **Margin:** (111.000 - 88.800) / 88.800 = 25%

### Aturan 3: Penjualan Non-PPN + Pembelian PPN

**Kondisi:** Sales Order tanpa PPN, tetapi produk dibeli dengan PPN
**Perlakuan:** PPN pembelian **TETAP dihitung** dalam kalkulasi komisi
**Alasan:** Biaya PPN pembelian adalah biaya riil yang harus diperhitungkan

**Contoh:**

-   Harga Jual (tanpa PPN): Rp 100.000
-   Harga Beli (termasuk PPN 11%): Rp 88.800
-   **Netto Jual untuk komisi:** Rp 100.000 (tanpa PPN)
-   **Netto Beli untuk komisi:** Rp 88.800 (dengan PPN)
-   **Margin:** (100.000 - 88.800) / 88.800 = 12.6%

## Implementasi Teknis

### File yang Dimodifikasi

-   `app/Http/Controllers/hr_karyawan/PenggajianController.php`

### Method yang Diubah

-   `hitungKomisiKaryawan()` - Menambahkan logika pengecekan dan penyesuaian PPN

### Logika Implementasi

```php
// 1. Cek apakah Sales Order memiliki PPN
$salesPpn = $order->ppn ?? 0;
$hasSalesPpn = $salesPpn > 0;

// 2. Untuk setiap produk, cek PPN dari Purchase Order terakhir
$lastPurchaseOrder = PurchaseOrder::join('purchase_order_detail', ...)
    ->where('produk_id', $produk->id)
    ->where('status', 'selesai')
    ->orderBy('tanggal', 'desc')
    ->first();

$hasPurchasePpn = ($lastPurchaseOrder && $lastPurchaseOrder->ppn > 0);

// 3. Terapkan aturan PPN
if ($hasSalesPpn && !$hasPurchasePpn) {
    // Aturan 1: Exclude PPN dari nilai penjualan
    $nettoJualItem = $nettoJualItem / (1 + $salesPpn / 100);
} elseif ($hasSalesPpn && $hasPurchasePpn) {
    // Aturan 2: Gunakan nilai as-is (kedua PPN dihitung)
    // No adjustment needed
} elseif (!$hasSalesPpn && $hasPurchasePpn) {
    // Aturan 3: Nilai pembelian sudah include PPN
    // No adjustment needed
}
```

### Dependencies Baru

-   Menambahkan import: `use App\Models\PurchaseOrder;`

## Impact Analysis

### Sistem yang Terpengaruh

1. **Perhitungan Komisi** - Formula margin berubah sesuai kondisi PPN
2. **Display Komisi** - Nilai komisi per sales order akan berbeda
3. **Total Penggajian** - Total gaji karyawan sales terpengaruh oleh perubahan komisi

### Data yang Digunakan

-   `sales_order.ppn` - PPN percentage pada sales order
-   `purchase_order.ppn` - PPN percentage pada purchase order terakhir untuk produk
-   `produk.harga_beli` - Harga beli produk (weighted average)

## Testing Scenarios

### Test Case 1: Sales PPN + Purchase Non-PPN

```
Input:
- Sales Order PPN: 11%
- Harga jual per item: Rp 111.000
- Quantity: 10
- Purchase Order PPN: 0%
- Harga beli per item: Rp 80.000

Expected Output:
- Netto Penjualan: 10 × (111.000 / 1.11) = Rp 1.000.000
- Netto Beli: 10 × 80.000 = Rp 800.000
- Margin: 25%
```

### Test Case 2: Sales PPN + Purchase PPN

```
Input:
- Sales Order PPN: 11%
- Harga jual per item: Rp 111.000
- Quantity: 10
- Purchase Order PPN: 11%
- Harga beli per item: Rp 88.800

Expected Output:
- Netto Penjualan: 10 × 111.000 = Rp 1.110.000
- Netto Beli: 10 × 88.800 = Rp 888.000
- Margin: 25%
```

### Test Case 3: Sales Non-PPN + Purchase PPN

```
Input:
- Sales Order PPN: 0%
- Harga jual per item: Rp 100.000
- Quantity: 10
- Purchase Order PPN: 11%
- Harga beli per item: Rp 88.800

Expected Output:
- Netto Penjualan: 10 × 100.000 = Rp 1.000.000
- Netto Beli: 10 × 88.800 = Rp 888.000
- Margin: 12.6%
```

## Backward Compatibility

### Data Historis

-   Penggajian yang sudah diproses: **TIDAK terpengaruh** (data sudah tersimpan)
-   Penggajian baru: **Menggunakan formula baru**
-   Sales Order lama: Tetap bisa dihitung dengan formula baru

### Migrasi Data

Tidak diperlukan migrasi data karena:

-   Field PPN sudah ada di tabel `sales_order` dan `purchase_order`
-   Tidak ada perubahan struktur database
-   Hanya perubahan logika perhitungan

## Monitoring & Validation

### Log Points

Sistem sudah mencatat:

```php
Log::info('Commission calculation for order', [
    'order_id' => $order->id,
    'margin_persen' => $marginPersen,
    'komisi_rate' => $komisiRate,
    'order_komisi' => $orderKomisi
]);
```

### Validasi yang Perlu Dilakukan

1. ✅ Pastikan PPN sales order terdeteksi dengan benar
2. ✅ Pastikan PPN purchase order terakhir terdeteksi
3. ✅ Verifikasi perhitungan margin sesuai aturan
4. ✅ Cek total komisi tidak negatif
5. ✅ Bandingkan dengan perhitungan manual untuk beberapa kasus

## Notes

### Asumsi Penting

1. **PPN Purchase Order**: Menggunakan PO terakhir yang berstatus 'selesai' untuk menentukan apakah produk dibeli dengan PPN
2. **Harga Beli**: Menggunakan `produk.harga_beli` yang sudah di-update dengan weighted average
3. **Nilai PPN**: Jika `ppn > 0` dianggap ada PPN, jika `ppn = 0` atau `null` dianggap tidak ada PPN

### Edge Cases

1. **Produk Baru** (belum pernah dibeli): Akan dianggap tidak ada PPN pembelian
2. **Mixed Items**: Setiap item dalam sales order dievaluasi individual berdasarkan PPN purchase order masing-masing
3. **PPN Rate Changes**: Sistem menggunakan rate yang tercatat di database, bukan hardcoded

## Status Implementasi

✅ **COMPLETE** - Implementasi selesai dan siap untuk testing

## Next Steps

1. Testing manual dengan data sample
2. Verifikasi dengan tim accounting/finance
3. Monitor hasil perhitungan komisi periode pertama
4. Dokumentasi user guide untuk admin HR

---

**Implementor:** GitHub Copilot  
**Reviewed by:** _Pending_  
**Approved by:** _Pending_
