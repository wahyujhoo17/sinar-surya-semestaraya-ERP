# Implementasi Pengaturan Bank untuk Invoice

## Masalah yang Dipecahkan

Sistem sebelumnya tidak memiliki pengaturan untuk mengelola rekening bank mana yang akan ditampilkan pada invoice. Hal ini menyebabkan:

-   Semua rekening bank aktif ditampilkan tanpa kontrol
-   Tidak ada rekening bank utama yang menjadi default
-   Kurang modular dan fleksibel untuk pemilihan bank account

## Solusi Implementasi

Ditambahkan pengaturan bank khusus untuk invoice yang memungkinkan:

-   Memilih rekening bank mana yang akan diaktifkan untuk invoice
-   Menetapkan rekening bank utama sebagai default
-   Kontrol modular terhadap tampilan rekening bank pada sistem

### Database Settings Baru

| Setting Key             | Default Value | Group  | Deskripsi                                     |
| ----------------------- | ------------- | ------ | --------------------------------------------- |
| `enabled_bank_accounts` | `[]`          | `bank` | Array ID rekening bank yang diaktifkan (JSON) |
| `primary_bank_account`  | `""`          | `bank` | ID rekening bank utama/default                |

### File yang Dibuat/Diubah

#### 1. PengaturanUmumController.php

**Validasi Baru:**

```php
// Bank Settings for Invoice
'enabled_bank_accounts' => 'nullable|array',
'enabled_bank_accounts.*' => 'exists:rekening_bank,id',
'primary_bank_account' => 'nullable|exists:rekening_bank,id',
```

**Method Baru:**

```php
private function getBankSettings($settings)
{
    $bank = $settings->get('bank', collect());
    $bankSettings = $bank->pluck('value', 'key');

    $enabledAccounts = $bankSettings->get('enabled_bank_accounts', '[]');
    $enabledAccountsArray = json_decode($enabledAccounts, true) ?: [];

    return [
        'enabled_bank_accounts' => $enabledAccountsArray,
        'primary_bank_account' => $bankSettings->get('primary_bank_account', ''),
    ];
}
```

**Data Tambahan:**

```php
// Ambil data rekening bank untuk dropdown
$bankAccounts = \App\Models\RekeningBank::where('is_aktif', true)
    ->where('is_perusahaan', true)
    ->orderBy('nama_bank')
    ->get();
```

#### 2. index.blade.php (View Pengaturan Umum)

**Tab Baru:**

```blade
<!-- Tab Navigation -->
<button type="button" @click="activeTab = 'bank'">
    <div class="flex items-center">
        <svg><!-- Bank Icon --></svg>
        Pengaturan Bank
    </div>
</button>

<!-- Content Area -->
<div x-show="activeTab === 'bank'" x-transition>
    <!-- Bank account checkboxes -->
    <!-- Primary bank selection -->
    <!-- Information panel -->
</div>
```

**Fitur:**

-   Checkbox untuk memilih rekening bank yang akan diaktifkan
-   Dropdown untuk memilih rekening bank utama
-   Informasi status dan saldo setiap rekening
-   Panel informasi penggunaan

#### 3. SettingsSeeder.php

**Setting Baru:**

```php
[
    'key' => 'enabled_bank_accounts',
    'value' => '[]',
    'group' => 'bank',
    'description' => 'Bank rekening yang diaktifkan untuk invoice'
],
[
    'key' => 'primary_bank_account',
    'value' => '',
    'group' => 'bank',
    'description' => 'Bank rekening utama untuk invoice'
]
```

#### 4. SettingsHelper.php

**Helper Functions Baru:**

```php
// Mendapatkan rekening bank yang diaktifkan
function get_enabled_bank_accounts()

// Mendapatkan rekening bank utama
function get_primary_bank_account()

// Mendapatkan rekening bank untuk invoice dengan fallback
function get_bank_accounts_for_invoice()
```

## Cara Penggunaan

### 1. Mengatur Bank untuk Invoice

1. Buka **Pengaturan > Pengaturan Umum**
2. Klik tab **Pengaturan Bank**
3. Centang rekening bank yang ingin diaktifkan untuk invoice
4. Pilih rekening bank utama dari dropdown
5. Klik **Simpan Pengaturan**

### 2. Menggunakan Helper Functions

```php
// Mendapatkan semua rekening yang diaktifkan
$enabledBanks = get_enabled_bank_accounts();

// Mendapatkan rekening utama
$primaryBank = get_primary_bank_account();

// Mendapatkan rekening untuk invoice (dengan fallback logic)
$invoiceBanks = get_bank_accounts_for_invoice();
```

## Logika Fallback

Sistem menggunakan logika fallback untuk memastikan selalu ada rekening bank yang tersedia:

1. **Jika ada rekening yang diaktifkan**: Tampilkan hanya rekening yang dicentang
2. **Jika tidak ada yang diaktifkan tapi ada rekening utama**: Tampilkan rekening utama
3. **Jika tidak ada pengaturan**: Tampilkan semua rekening bank aktif perusahaan

## Integrasi dengan Invoice

Helper functions ini dapat digunakan pada:

-   **Form pembuatan invoice**: Untuk dropdown pilihan rekening bank
-   **Template invoice**: Untuk menampilkan informasi bank penerima
-   **PDF invoice**: Untuk menyertakan detail rekening bank

## Keuntungan

✅ **Modular**: Admin dapat mengontrol rekening mana yang ditampilkan  
✅ **Fleksible**: Dapat mengaktifkan/menonaktifkan rekening sesuai kebutuhan  
✅ **User-friendly**: Rekening utama otomatis terpilih sebagai default  
✅ **Fallback**: Sistem tetap berfungsi meski tidak ada pengaturan khusus  
✅ **Informative**: Menampilkan informasi lengkap setiap rekening (saldo, nama, dll)

## Contoh Penggunaan di Invoice Controller

```php
public function create()
{
    // Mendapatkan rekening bank untuk pilihan di form
    $bankAccounts = get_bank_accounts_for_invoice();
    $primaryBank = get_primary_bank_account();

    return view('penjualan.invoice.create', compact('bankAccounts', 'primaryBank'));
}
```

## Catatan Penting

-   Pengaturan hanya berlaku untuk rekening bank dengan `is_aktif = true` dan `is_perusahaan = true`
-   Jika tidak ada pengaturan, sistem akan menampilkan semua rekening bank aktif
-   Helper functions sudah menangani kasus ketika tidak ada data atau pengaturan kosong
-   Perubahan pengaturan langsung berlaku untuk form/tampilan baru
