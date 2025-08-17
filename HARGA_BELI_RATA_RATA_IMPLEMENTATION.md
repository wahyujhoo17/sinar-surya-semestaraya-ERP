# Implementasi Sistem Harga Beli Rata-rata

## 📋 **Overview**

Sistem ini mengimplementasikan pengelolaan harga beli produk yang lebih akurat dengan menggunakan **harga beli rata-rata tertimbang** dan **update otomatis saat Purchase Order selesai**.

## 🎯 **Tujuan Implementasi**

### **Masalah Sebelumnya:**

-   ❌ Harga beli diupdate setiap kali PO dibuat/diedit (bahkan saat draft)
-   ❌ Komisi berubah-ubah karena harga beli tidak stabil
-   ❌ Sales order lama terpengaruh perubahan harga mendadak
-   ❌ Tidak akurat untuk perhitungan margin

### **Solusi Baru:**

-   ✅ Harga beli menggunakan **rata-rata tertimbang**
-   ✅ Update hanya saat status PO = **"selesai"**
-   ✅ Komisi lebih stabil dan konsisten
-   ✅ Historical accuracy terjaga

## 🔧 **Implementasi Teknis**

### **1. Struktur Database**

```sql
-- purchase_order_detail
- id
- po_id
- produk_id
- harga (SEBAGAI HARGA BELI) ← KOLOM INI YANG DIGUNAKAN
- quantity
- ...

-- produk
- id
- nama_produk
- harga_beli (RATA-RATA TERTIMBANG) ← DIUPDATE OTOMATIS
- ...
```

### **2. Alur Kerja Sistem**

#### **Fase 1: Draft/Approval (TIDAK ADA UPDATE)**

```php
// Saat create/edit PO dengan status draft/diproses
PurchaseOrderDetail::create([
    'harga' => $harga_beli_dari_supplier, // Disimpan sebagai referensi
    // ... field lain
]);

// TIDAK ada update ke produk.harga_beli
// Harga tetap menggunakan nilai sebelumnya
```

#### **Fase 2: Status Selesai (UPDATE HARGA RATA-RATA)**

```php
// Ketika status PO berubah ke "selesai"
if ($status === 'selesai') {
    foreach ($po->details as $detail) {
        $this->updateHargaBeliRataRata($detail->produk_id, $detail->harga);
    }
}
```

### **3. Algoritma Harga Beli Rata-rata**

```php
private function updateHargaBeliRataRata($produk_id, $harga_beli_baru)
{
    // 1. Ambil history pembelian dari PO yang sudah SELESAI
    $historyPembelian = DB::table('purchase_order_detail')
        ->join('purchase_order', 'purchase_order_detail.po_id', '=', 'purchase_order.id')
        ->where('purchase_order_detail.produk_id', $produk_id)
        ->where('purchase_order.status', 'selesai') // HANYA yang selesai
        ->select('purchase_order_detail.harga', 'purchase_order_detail.quantity')
        ->get();

    // 2. Hitung rata-rata tertimbang berdasarkan quantity
    $totalNilai = 0;
    $totalQuantity = 0;

    foreach ($historyPembelian as $history) {
        $totalNilai += ($history->harga * $history->quantity);
        $totalQuantity += $history->quantity;
    }

    // 3. Hitung harga beli rata-rata
    $hargaBeliRataRata = $totalQuantity > 0 ? $totalNilai / $totalQuantity : $harga_beli_baru;

    // 4. Update produk dengan harga beli baru
    $produk->update(['harga_beli' => round($hargaBeliRataRata, 2)]);
}
```

## 📊 **Contoh Perhitungan**

### **Skenario:**

-   **Produk A** dengan harga beli awal: **Rp 10.000**

### **Purchase Order 1 (Selesai):**

-   Quantity: 100 pcs
-   Harga beli: Rp 12.000
-   Status: **selesai**

**Hasil:** Harga beli rata-rata = Rp 12.000

### **Purchase Order 2 (Selesai):**

-   Quantity: 50 pcs
-   Harga beli: Rp 8.000
-   Status: **selesai**

**Perhitungan:**

```
Total Nilai = (100 × 12.000) + (50 × 8.000) = 1.200.000 + 400.000 = 1.600.000
Total Quantity = 100 + 50 = 150
Harga Rata-rata = 1.600.000 ÷ 150 = Rp 10.667
```

**Hasil:** Harga beli rata-rata = **Rp 10.667**

### **Purchase Order 3 (Draft):**

-   Quantity: 200 pcs
-   Harga beli: Rp 15.000
-   Status: **draft**

**Hasil:** Harga beli tetap **Rp 10.667** (tidak berubah)

## 🚀 **Keuntungan Sistem Baru**

### **1. Stabilitas Komisi**

```php
// Komisi akan lebih stabil karena harga beli tidak berubah-ubah
$margin = (($harga_jual - $harga_beli_rata_rata) / $harga_jual) * 100;
$komisi = $this->getKomisiRateByMargin($margin) * $nilai_penjualan;
```

### **2. Akurasi Cost**

-   Menggunakan data historis yang sudah confirmed (status selesai)
-   Mencerminkan cost aktual yang lebih akurat
-   Tertimbang berdasarkan volume pembelian

### **3. Timing yang Tepat**

-   Update hanya saat barang benar-benar diterima
-   Tidak ada perubahan mendadak saat masih draft
-   Konsisten dengan proses bisnis

### **4. Historical Accuracy**

-   Sales order lama tidak terpengaruh
-   Komisi sudah dibayar tetap valid
-   Audit trail yang lebih baik

## 🔄 **Trigger Update Harga Beli**

### **Method 1: Update PO (Method update)**

```php
if ($validated['status'] === 'selesai') {
    foreach ($purchaseOrder->details as $detail) {
        if ($detail->produk_id) {
            $this->updateHargaBeliRataRata($detail->produk_id, $detail->harga);
        }
    }
}
```

### **Method 2: Change Status (Method changeStatus)**

```php
if ($newStatus === 'selesai') {
    foreach ($purchaseOrder->details as $detail) {
        if ($detail->produk_id) {
            $this->updateHargaBeliRataRata($detail->produk_id, $detail->harga);
        }
    }
}
```

## 📝 **Log Aktivitas**

Sistem akan mencatat setiap perubahan harga beli:

```php
$this->logUserAktivitas(
    'Update Harga Beli Rata-rata',
    'Purchase Order',
    $produk->id,
    "Produk: {$produk->nama_produk}, Harga Lama: " . number_format($harga_lama, 2) .
    ", Harga Baru: " . number_format($harga_baru, 2)
);
```

## ⚠️ **Perhatian Implementasi**

### **1. Validasi Status Selesai**

-   PO hanya bisa selesai jika pembayaran lunas
-   PO hanya bisa selesai jika barang sudah diterima
-   Validasi ini sudah ada di `changeStatus` method

### **2. Impact ke Komisi**

-   Komisi yang sudah dibayar **TIDAK BERUBAH**
-   Hanya komisi untuk sales order baru yang terpengaruh
-   Sistem lebih stabil dan predictable

### **3. Data Migration**

-   Produk yang sudah ada akan tetap menggunakan harga beli existing
-   Hanya akan terupdate saat ada PO baru yang selesai
-   Tidak ada perubahan retroaktif

## 🎯 **Status Implementasi**

-   ✅ **Helper function** `updateHargaBeliRataRata()` - **COMPLETED**
-   ✅ **Update method store()** - menghilangkan update langsung - **COMPLETED**
-   ✅ **Update method update()** - menghilangkan update langsung - **COMPLETED**
-   ✅ **Trigger di method update()** - update saat status selesai - **COMPLETED**
-   ✅ **Trigger di method changeStatus()** - update saat ubah status - **COMPLETED**
-   ✅ **Database structure** - menggunakan kolom `harga` sebagai harga beli - **COMPLETED**
-   ✅ **Documentation** - **COMPLETED**

## 📈 **Hasil yang Diharapkan**

1. **Komisi lebih stabil** - tidak berubah-ubah karena harga beli lebih konsisten
2. **Akurasi cost** - menggunakan rata-rata historical yang lebih akurat
3. **Timing yang tepat** - update hanya saat confirmed (selesai)
4. **Historical integrity** - sales order lama tidak terpengaruh
5. **Business process alignment** - sesuai dengan alur bisnis yang sebenarnya

---

**Implementasi ini memberikan stabilitas dan akurasi yang lebih baik untuk perhitungan komisi dan manajemen cost produk.**
