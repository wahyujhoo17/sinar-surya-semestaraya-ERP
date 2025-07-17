# Implementasi Partial Invoice dengan Qty Editable

## Fitur yang Telah Diimplementasikan

### 1. **Qty Editable pada Invoice**

-   User dapat mengubah qty produk pada form invoice
-   Input qty dibatasi dengan nilai maksimum sesuai sisa qty yang tersedia
-   Validasi real-time saat user mengubah qty

### 2. **Kalkulasi Otomatis**

-   Subtotal produk otomatis dihitung ulang saat qty berubah
-   Diskon produk diperhitungkan dalam kalkulasi
-   Total invoice diperbarui secara otomatis

### 3. **Validasi Qty Tersedia**

-   Backend menghitung qty yang sudah di-invoice dari SO yang sama
-   Qty yang dapat diinput = Qty SO - Qty yang sudah di-invoice
-   Validasi di frontend dan backend untuk mencegah over-invoice

### 4. **Informasi Qty yang Jelas**

-   Tampilan "SO Qty" untuk menunjukkan qty asli dari Sales Order
-   Tampilan "Max: X" untuk menunjukkan qty maksimum yang dapat diinput
-   Info tentang sistem partial invoice

## Perubahan File

### Frontend (create.blade.php)

1. **Input Qty Editable**:

    - Mengubah display qty dari readonly menjadi input number
    - Menambahkan atribut `min`, `max`, dan `step`
    - Menambahkan event handler `@input="updateItemSubtotal(index)"`

2. **JavaScript Functions**:

    - `updateItemSubtotal(index)`: Menghitung ulang subtotal saat qty berubah
    - `submitForm()`: Validasi sebelum submit form
    - Validasi qty tidak melebihi max_available_qty
    - Validasi qty tidak kurang dari 0.01

3. **UI/UX Improvements**:
    - Petunjuk penggunaan partial invoice
    - Informasi qty SO asli dan qty maksimum
    - Pesan error yang informatif

### Backend (InvoiceController.php)

1. **getSalesOrderData()**:

    - Menghitung qty yang sudah di-invoice per produk
    - Mengirim `original_qty` dan `max_available_qty` ke frontend
    - Filter produk yang qty-nya sudah habis

2. **store()**:

    - Menambahkan custom validation `validateInvoiceQuantities()`
    - Validasi qty sebelum menyimpan ke database

3. **validateInvoiceQuantities()**:
    - Method baru untuk validasi qty berdasarkan sisa qty SO
    - Mengecek setiap produk apakah qty tidak melebihi yang tersedia
    - Memberikan pesan error yang informatif

## Cara Kerja Sistem

### 1. **Memilih Sales Order**

-   User memilih SO yang belum fully invoiced
-   Sistem menghitung sisa invoice dan qty per produk

### 2. **Mengubah Qty Produk**

-   User dapat mengubah qty produk dalam batas yang tersedia
-   Sistem otomatis menghitung ulang subtotal
-   Validasi real-time mencegah input berlebihan

### 3. **Validasi Backend**

-   Saat submit, backend memvalidasi semua qty
-   Cek apakah qty tidak melebihi sisa qty SO
-   Tolak jika ada qty yang berlebihan

### 4. **Penyimpanan Data**

-   Invoice disimpan dengan qty yang diinput user
-   Status SO diperbarui berdasarkan total invoice
-   Sistem siap untuk invoice berikutnya

## Keuntungan Implementasi

1. **Fleksibilitas**: User dapat membuat partial invoice sesuai kebutuhan
2. **Kontrol**: Qty dapat diubah manual, tidak hanya proporsional
3. **Keamanan**: Validasi ketat mencegah over-invoice
4. **User-Friendly**: Interface yang intuitif dengan petunjuk jelas
5. **Konsistensi**: Status SO dan sisa invoice selalu akurat

## Contoh Penggunaan

**Skenario**: SO dengan 2 produk (A: 100 pcs, B: 50 pcs)

**Invoice 1**:

-   Produk A: 60 pcs (dari 100 pcs)
-   Produk B: 20 pcs (dari 50 pcs)

**Invoice 2** (kemudian):

-   Produk A: 40 pcs (sisa: 100-60=40)
-   Produk B: 30 pcs (sisa: 50-20=30)

Sistem akan:

-   Menampilkan qty maksimum yang dapat diinput
-   Validasi input tidak melebihi sisa
-   Hitung subtotal berdasarkan qty yang diinput
-   Update status SO setelah invoice dibuat

## Fitur Tambahan yang Dapat Dikembangkan

1. **Batch Invoice**: Buat multiple invoice sekaligus
2. **Template Invoice**: Simpan template qty untuk invoice berulang
3. **Riwayat Invoice**: Tampilkan invoice history per SO
4. **Notifikasi**: Alert jika SO hampir fully invoiced
5. **Report**: Laporan partial invoice dan sisanya
