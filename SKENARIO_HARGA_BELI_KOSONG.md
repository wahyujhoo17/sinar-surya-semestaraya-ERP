# Skenario Harga Beli Kosong/0 - Analisis & Solusi

## 🤔 **Pertanyaan User:**

> "Kalau harga beli dari produk masih kosong atau 0 bagaimana? Apa yang terjadi? Apakah tetap menggunakan rata-rata atau ditambahkan biasa?"

## 📊 **Analisis Skenario**

### **Skenario 1: Produk Baru (harga_beli = NULL atau 0)**

```php
// Produk A baru dibuat, belum pernah ada PO
$produk = new Produk([
    'nama_produk' => 'Produk A',
    'harga_beli' => null, // atau 0
]);

// PO pertama selesai: Qty=100, Harga=Rp 10.000
// Hasil: harga_beli = Rp 10.000 (langsung set)
```

### **Skenario 2: Produk Existing dengan Harga Beli Valid**

```php
// Produk B sudah ada
$produk = Produk::find(1); // harga_beli = Rp 8.000

// PO baru selesai: Qty=50, Harga=Rp 12.000
// Hasil: harga_beli = rata-rata tertimbang
// = ((8.000 × 1) + (12.000 × 50)) ÷ (1 + 50) = Rp 11.843
```

### **Skenario 3: Produk dengan History PO Selesai**

```php
// Produk C dengan history PO selesai
// PO#1: Qty=100, Harga=Rp 10.000 (selesai)
// PO#2: Qty=50, Harga=Rp 8.000 (selesai)

// PO#3 baru selesai: Qty=30, Harga=Rp 15.000
// Hasil: rata-rata dari semua PO selesai
// = ((10.000×100) + (8.000×50) + (15.000×30)) ÷ (100+50+30)
// = (1.000.000 + 400.000 + 450.000) ÷ 180 = Rp 10.278
```

## 🔧 **Implementasi Solusi**

### **Logika Decision Tree:**

```php
private function updateHargaBeliRataRata($produk_id, $harga_beli_baru, $quantity_baru, $po_id_saat_ini)
{
    $produk = Produk::find($produk_id);

    // 1. Cek apakah produk baru (harga_beli kosong/0)
    $isProdukBaru = is_null($produk->harga_beli) || $produk->harga_beli <= 0;

    // 2. Ambil history PO yang sudah selesai
    $historyPembelian = /* query history PO selesai */;

    if ($historyPembelian->isEmpty()) {
        if ($isProdukBaru) {
            // SKENARIO 1: Produk baru + belum ada history
            // AKSI: Set langsung harga beli pertama
            $produk->harga_beli = $harga_beli_baru;
        } else {
            // SKENARIO 2: Produk existing + belum ada history PO selesai
            // AKSI: Rata-rata dengan harga existing
            $harga_existing = $produk->harga_beli;
            $harga_rata_rata = (($harga_existing × 1) + ($harga_beli_baru × $quantity_baru))
                             ÷ (1 + $quantity_baru);
            $produk->harga_beli = $harga_rata_rata;
        }
    } else {
        // SKENARIO 3: Ada history PO selesai
        // AKSI: Hitung rata-rata tertimbang dari semua history + PO baru
        $total_nilai = 0;
        $total_quantity = 0;

        foreach ($historyPembelian as $history) {
            $total_nilai += ($history->harga × $history->quantity);
            $total_quantity += $history->quantity;
        }

        // Tambahkan PO yang baru selesai
        $total_nilai += ($harga_beli_baru × $quantity_baru);
        $total_quantity += $quantity_baru;

        $produk->harga_beli = $total_nilai ÷ $total_quantity;
    }
}
```

## 📋 **Tabel Decision Matrix**

| **Kondisi Produk**             | **History PO** | **Aksi**             | **Formula**                                 |
| ------------------------------ | -------------- | -------------------- | ------------------------------------------- |
| **Baru** (harga_beli = NULL/0) | Kosong         | Set langsung         | `harga_beli = harga_baru`                   |
| **Existing** (harga_beli > 0)  | Kosong         | Rata-rata simple     | `(harga_lama×1 + harga_baru×qty) ÷ (1+qty)` |
| **Apapun**                     | Ada history    | Rata-rata tertimbang | `Σ(harga×qty) ÷ Σ(qty)`                     |

## 💡 **Contoh Praktis**

### **Case 1: Produk Baru**

```
Produk: "Laptop Dell"
Status awal: harga_beli = NULL

PO#1 selesai: 10 unit × Rp 8.000.000
HASIL: harga_beli = Rp 8.000.000

Log: "Set Harga Beli Produk Baru: Laptop Dell, Harga Beli Pertama: Rp 8.000.000"
```

### **Case 2: Produk Existing tanpa History**

```
Produk: "Mouse Wireless"
Status awal: harga_beli = Rp 50.000 (dari input manual)

PO#1 selesai: 100 unit × Rp 45.000
HASIL: harga_beli = ((50.000×1) + (45.000×100)) ÷ (1+100) = Rp 45.050

Log: "Update Harga Beli Rata-rata (Existing): Mouse Wireless, Harga Lama: Rp 50.000, Harga Baru: Rp 45.050"
```

### **Case 3: Produk dengan History**

```
Produk: "Keyboard Mechanical"
History PO selesai:
- PO#1: 50 unit × Rp 200.000
- PO#2: 30 unit × Rp 180.000

PO#3 selesai: 20 unit × Rp 220.000
Perhitungan:
Total Nilai = (50×200.000) + (30×180.000) + (20×220.000) = 19.800.000
Total Qty = 50 + 30 + 20 = 100
HASIL: harga_beli = 19.800.000 ÷ 100 = Rp 198.000

Log: "Update Harga Beli Rata-rata: Keyboard Mechanical, Harga Lama: Rp xxx, Harga Baru: Rp 198.000, Total PO: 3"
```

## ⚠️ **Edge Cases & Handling**

### **1. Harga Beli 0 (Zero)**

```php
$isProdukBaru = is_null($produk->harga_beli) || $produk->harga_beli <= 0;
// Memperlakukan harga 0 sama dengan NULL (produk baru)
```

### **2. Quantity 0 atau Negatif**

```php
if ($quantity_baru <= 0) {
    // Skip update atau throw exception
    return;
}
```

### **3. PO dengan Harga 0**

```php
->where('purchase_order_detail.harga', '>', 0) // Filter harga invalid
```

### **4. Produk Tidak Ditemukan**

```php
if (!$produk) return; // Exit gracefully
```

## 🎯 **Keuntungan Pendekatan Ini**

### **✅ Fleksibilitas**

-   Handle produk baru dengan elegant
-   Tidak memaksa input harga beli manual
-   Adaptif dengan kondisi existing data

### **✅ Akurasi**

-   Produk baru: langsung accurate dari pembelian pertama
-   Produk existing: mempertimbangkan history yang ada
-   Weighted average: mencerminkan volume pembelian

### **✅ Konsistensi**

-   Semua skenario menggunakan logika yang sama
-   Logging yang comprehensive untuk audit
-   Predictable behavior untuk user

### **✅ Business Logic**

-   Harga beli kosong = produk baru → normal
-   Harga beli existing → tetap dihitung rata-rata
-   History PO → data yang paling akurat

## 📈 **Impact ke Komisi**

### **Komisi Tetap Stabil:**

```php
// Sales Order lama tetap menggunakan harga beli saat itu
$margin_lama = (($harga_jual - $harga_beli_saat_itu) / $harga_jual) * 100;

// Sales Order baru menggunakan harga beli rata-rata terbaru
$margin_baru = (($harga_jual - $harga_beli_rata_rata) / $harga_jual) * 100;
```

### **Prediktabilitas:**

-   User tahu kapan harga beli akan berubah (saat PO selesai)
-   Tidak ada surprize di tengah proses
-   Historical accuracy terjaga

---

## 🎯 **Kesimpulan**

**Jawaban untuk pertanyaan user:**

> **"Apakah tetap menggunakan rata-rata atau ditambahkan biasa?"**

**JAWAB: HYBRID APPROACH** 🎯

1. **Produk Baru** (harga_beli kosong/0) → **Set langsung** dari PO pertama
2. **Produk Existing** tanpa history → **Rata-rata simple** (existing + baru)
3. **Produk dengan History** → **Rata-rata tertimbang** dari semua PO selesai

**Sistem ini memberikan fleksibilitas maksimal sambil tetap menjaga akurasi dan konsistensi perhitungan komisi!** ✨
