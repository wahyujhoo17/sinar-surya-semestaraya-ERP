## 📋 Template PDF Indo Atsaka - Quotation Update

### 🔄 **Perubahan yang Dilakukan:**

#### **1. Header & Company Information:**

-   ✅ **Title:** Diubah dari "Invoice" menjadi "Quotation"
-   ✅ **Company Name:** Menggunakan data dari `$template_config['company_name']`
-   ✅ **Company Info:** PT INDO ATSAKA INDUSTRI dengan alamat lengkap
-   ✅ **Quotation Data:** Nomor, tanggal, dan status dari database

#### **2. Customer Information:**

-   ✅ **Customer Data:** Menggunakan data customer dari quotation
-   ✅ **Customer Details:** Nama, alamat, telepon, email, kontak person
-   ✅ **Layout:** Informasi customer dan perusahaan berdampingan

#### **3. Table Structure:**

-   ✅ **Columns:** No, Nama Produk, Qty, Satuan, Harga, Diskon, Total
-   ✅ **Dynamic Data:** Loop produk dari `$quotation->details`
-   ✅ **Product Info:** Nama item, deskripsi, quantity, satuan, harga
-   ✅ **Pricing:** Format rupiah dengan separator

#### **4. Summary Section:**

-   ✅ **Subtotal:** Dari database quotation
-   ✅ **Diskon:** Persen dan nominal jika ada
-   ✅ **PPN:** Perhitungan pajak jika ada
-   ✅ **Ongkos Kirim:** Jika ada biaya pengiriman
-   ✅ **Total:** Grand total dengan format rupiah

#### **5. Additional Features:**

-   ✅ **Periode Penawaran:** Ditampilkan jika ada data periode
-   ✅ **Catatan:** Dari database jika ada
-   ✅ **Syarat & Ketentuan:** Custom atau default
-   ✅ **Watermark:** Nama perusahaan sebagai background
-   ✅ **Signature:** Nama sales dari database

#### **6. Layout Optimizations:**

-   ✅ **Margin:** Dioptimalkan untuk penggunaan halaman maksimal
-   ✅ **Font Size:** Disesuaikan untuk readability dan efisiensi ruang
-   ✅ **Spacing:** Dikurangi tanpa mengorbankan tampilan professional

### 🎯 **Data Variables yang Digunakan:**

```php
// Company Info
$template_config['company_name']

// Quotation Data
$quotation->nomor
$quotation->tanggal
$quotation->status
$quotation->periode_start
$quotation->periode_end
$quotation->catatan
$quotation->syarat_ketentuan

// Customer Data
$quotation->customer->company
$quotation->customer->nama
$quotation->customer->alamat
$quotation->customer->telepon
$quotation->customer->email
$quotation->customer->kontak_person
$quotation->customer->no_hp_kontak

// Product Details
$quotation->details (collection)
$detail->nama_item
$detail->produk->nama
$detail->deskripsi
$detail->quantity
$detail->satuan->nama
$detail->harga
$detail->diskon_persen
$detail->diskon_nominal
$detail->subtotal

// Financial Summary
$quotation->subtotal
$quotation->diskon_persen
$quotation->diskon_nominal
$quotation->ppn
$quotation->ongkos_kirim
$quotation->total

// User Info
$quotation->user->name
```

### 🧪 **Testing:**

1. **Test dengan quotation yang memiliki:**

    - ✅ Multiple items
    - ✅ Diskon
    - ✅ PPN
    - ✅ Ongkos kirim
    - ✅ Catatan
    - ✅ Periode penawaran

2. **URL Testing:**
    ```
    /quotation/{id}/pdf/indo-atsaka
    ```

### 📐 **Layout Features:**

-   📄 **Paper Size:** A4 Portrait
-   🔧 **Margins:** 6-8mm optimized
-   🎨 **Color Scheme:** Navy Blue (#1F2A44) & Red (#E74C3C)
-   📱 **PDF Compatible:** Table layout untuk kompatibilitas DomPDF
-   🖨️ **Print Ready:** Optimized untuk printing

### 🚀 **Next Steps:**

1. Test dengan data quotation yang beragam
2. Sesuaikan company information jika diperlukan
3. Customize terms & conditions sesuai kebutuhan
4. Test printing untuk memastikan layout optimal

Template sekarang sudah fully functional sebagai quotation document dengan data dinamis dari database!
