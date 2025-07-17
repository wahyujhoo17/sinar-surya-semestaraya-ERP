# Error Fixes untuk Invoice Form

## Error yang Diperbaiki

### 1. **Alpine.js Variable Undefined Errors**

-   **Error**: `gunakanUangMuka is not defined`
-   **Fix**: Menambahkan inisialisasi variabel yang proper di `invoiceForm()` function
-   **Variabel yang ditambahkan**:
    -   `gunakanUangMuka: false`
    -   `caraAplikasiUangMuka: 'otomatis'`
    -   `selectedAdvancePayments: []`
    -   `customerAdvancePayments: []`

### 2. **Null/Undefined Value Handling**

-   **Error**: JavaScript errors saat mengakses undefined properties
-   **Fix**: Menambahkan fallback values dengan `||` operator
-   **Methods yang diperbaiki**:
    -   `init()`: Inisialisasi semua variabel dengan fallback
    -   `fetchSalesOrderData()`: Fallback untuk semua data yang diterima
    -   `calculateSubtotal()`: Fallback untuk parseFloat
    -   `calculateTotal()`: Fallback untuk semua perhitungan
    -   `formatRupiah()`: Fallback untuk value parsing
    -   `submitForm()`: Fallback untuk form validation

### 3. **Error Handling Improvements**

-   **Error**: Unhandled exceptions dalam methods
-   **Fix**: Menambahkan try-catch dan null checks
-   **Methods yang diperbaiki**:
    -   `calculateDueDate()`: Try-catch untuk date operations
    -   `updateItemSubtotal()`: Null checks untuk items array
    -   `resetForm()`: Null checks untuk DOM elements
    -   `submitForm()`: Null checks untuk form element

## Perubahan Detail

### 1. **Function `init()`**

```javascript
init() {
    console.log('Initializing invoice form...');

    // Initialize all variables to prevent undefined errors
    this.customerId = this.customerId || '';
    this.customerName = this.customerName || '';
    this.customerAdvancePayments = this.customerAdvancePayments || [];
    this.gunakanUangMuka = this.gunakanUangMuka || false;
    // ... dan seterusnya
}
```

### 2. **Function `fetchSalesOrderData()`**

```javascript
// Set customer data with fallbacks
this.customerId = data.sales_order.customer_id || "";
this.customerName = data.sales_order.customer
    ? data.sales_order.customer.company
        ? `${data.sales_order.customer.nama} (${data.sales_order.customer.company})`
        : data.sales_order.customer.nama
    : "Unknown Customer";
```

### 3. **Function `updateItemSubtotal()`**

```javascript
updateItemSubtotal(index) {
    console.log('Updating item subtotal for index:', index);

    if (!this.items[index]) {
        console.error('Item not found at index:', index);
        return;
    }

    const item = this.items[index];
    const qty = parseFloat(item.qty) || 0;
    const harga = parseFloat(item.harga) || 0;
    // ... dengan fallback values
}
```

### 4. **Function `calculateSubtotal()`**

```javascript
calculateSubtotal() {
    this.subtotal = this.items.reduce((sum, item) => {
        const subtotal = parseFloat(item.subtotal) || 0;
        return sum + subtotal;
    }, 0);
}
```

### 5. **Function `calculateTotal()`**

```javascript
calculateTotal() {
    // Calculate diskon nominal
    const diskonPersen = parseFloat(this.diskonPersen) || 0;
    this.diskonNominal = this.subtotal * (diskonPersen / 100);

    // Calculate PPN nominal (applied after discount)
    const afterDiscount = this.subtotal - this.diskonNominal;
    const ppnPersen = parseFloat(this.ppnPersen) || 0;
    this.ppnNominal = afterDiscount * (ppnPersen / 100);

    // Calculate total (including ongkos kirim)
    const ongkosKirim = parseFloat(this.ongkosKirim) || 0;
    this.total = afterDiscount + this.ppnNominal + ongkosKirim;
}
```

## Hasil Perbaikan

### ✅ **Error yang Telah Diperbaiki:**

1. `gunakanUangMuka is not defined` - FIXED
2. `caraAplikasiUangMuka is not defined` - FIXED
3. `selectedAdvancePayments is not defined` - FIXED
4. `customerAdvancePayments is not defined` - FIXED
5. Null/undefined value errors dalam calculations - FIXED
6. DOM element access errors - FIXED

### ✅ **Fitur yang Berfungsi:**

1. Dropdown Sales Order - ✅
2. Auto-fill customer data - ✅
3. Item qty editing - ✅
4. Subtotal calculation - ✅
5. Total calculation - ✅
6. Uang muka options - ✅
7. Form validation - ✅
8. Form submission - ✅

### 📋 **Debug Features Added:**

1. Console logging untuk troubleshooting
2. Error messages yang informatif
3. Null checks di semua function
4. Fallback values untuk semua variabel

## Testing Checklist

Untuk memastikan semua error telah diperbaiki:

1. ✅ Buka halaman create invoice
2. ✅ Pilih sales order dari dropdown
3. ✅ Pastikan customer data ter-fill otomatis
4. ✅ Pastikan item muncul dengan qty yang bisa diedit
5. ✅ Ubah qty item dan pastikan subtotal update
6. ✅ Cek opsi uang muka (jika ada)
7. ✅ Submit form dan pastikan tidak ada error

## Notes

-   Semua error Alpine.js telah diperbaiki
-   Form sekarang lebih robust dengan error handling
-   Console logging membantu debugging
-   Fallback values mencegah undefined errors
-   Partial invoice functionality berfungsi dengan baik
