# PDF TEMPLATE BANK INTEGRATION - ATSAKA & HIDAYAH FIX

## Problem Statement

**Issue**: Template PDF 'atsaka' dan 'hidayah' masih menggunakan hardcoded bank settings lama

-   ❌ `setting('company_bank_name', 'Mandiri')`
-   ❌ `setting('company_bank_account', '006.000.301.9563')`
-   ❌ `setting('company_name', 'PT. Sinar Surya Semestaraya')`

**User Request**: "Informasi Pembayaran: masih belum menggunakan setting yang sesuai"

## Solution Implemented

### Template Integration Logic

Kedua template ('atsaka' dan 'hidayah') sudah menggunakan legacy template system, sehingga mereka sudah menerima variabel `$bankAccounts` dan `$primaryBank` dari controller.

Yang dibutuhkan hanya mengupdate view template untuk menggunakan data tersebut.

### Files Updated

#### 1. Template Atsaka

**File**: `resources/views/penjualan/invoice/pdf/atsaka.blade.php`

**Before** (Hardcoded):

```blade
<div style="font-size: 9px; margin-top: 5px;">
    Pembayaran Giro, Cek atau Transfer <br>
    Bank: {{ setting('company_bank_name', 'Mandiri') }}<br>
    No. Rekening: {{ setting('company_bank_account', '006.000.301.9563') }}<br>
    Atas Nama: {{ setting('company_name', 'PT. Sinar Surya Semestaraya') }}
</div>
```

**After** (Dynamic):

```blade
<div style="font-size: 9px; margin-top: 5px;">
    Pembayaran Giro, Cek atau Transfer <br>

    @if(isset($primaryBank) && $primaryBank)
        Bank: {{ $primaryBank->nama_bank }}<br>
        No. Rekening: {{ $primaryBank->nomor_rekening }}<br>
        Atas Nama: {{ $primaryBank->atas_nama }}
    @elseif(isset($bankAccounts) && $bankAccounts->isNotEmpty())
        @php $firstBank = $bankAccounts->first(); @endphp
        Bank: {{ $firstBank->nama_bank }}<br>
        No. Rekening: {{ $firstBank->nomor_rekening }}<br>
        Atas Nama: {{ $firstBank->atas_nama }}
    @else
        Bank: {{ setting('company_bank_name', 'Mandiri') }}<br>
        No. Rekening: {{ setting('company_bank_account', '006.000.301.9563') }}<br>
        Atas Nama: {{ setting('company_name', 'PT. Sinar Surya Semestaraya') }}
    @endif

    @if(isset($bankAccounts) && $bankAccounts->count() > 1)
        <br><strong style="font-size: 8px; color: #666;">Bank Alternatif:</strong><br>
        @foreach($bankAccounts as $bank)
            @if(!$primaryBank || $bank->id != $primaryBank->id)
                <span style="font-size: 8px;">{{ $bank->nama_bank }}: {{ $bank->nomor_rekening }} (a.n. {{ $bank->atas_nama }})</span><br>
            @endif
        @endforeach
    @endif
</div>
```

#### 2. Template Hidayah

**File**: `resources/views/penjualan/invoice/pdf/hidayah.blade.php`

**Before** (Hardcoded):

```blade
<div style="font-size: 9px; margin-top: 5px;">
    Pembayaran Giro, Cek atau Transfer <br>
    Bank: {{ setting('company_bank_name', 'Mandiri') }}<br>
    No. Rekening: {{ setting('company_bank_account', '006.000.301.9563') }}<br>
    Atas Nama: {{ setting('company_name', 'PT. Sinar Surya Semestaraya') }}
</div>
```

**After** (Dynamic):

```blade
<div style="font-size: 9px; margin-top: 5px;">
    Pembayaran Giro, Cek atau Transfer <br>

    @if(isset($primaryBank) && $primaryBank)
        Bank: {{ $primaryBank->nama_bank }}<br>
        No. Rekening: {{ $primaryBank->nomor_rekening }}<br>
        Atas Nama: {{ $primaryBank->atas_nama }}
    @elseif(isset($bankAccounts) && $bankAccounts->isNotEmpty())
        @php $firstBank = $bankAccounts->first(); @endphp
        Bank: {{ $firstBank->nama_bank }}<br>
        No. Rekening: {{ $firstBank->nomor_rekening }}<br>
        Atas Nama: {{ $firstBank->atas_nama }}
    @else
        Bank: {{ setting('company_bank_name', 'Mandiri') }}<br>
        No. Rekening: {{ setting('company_bank_account', '006.000.301.9563') }}<br>
        Atas Nama: {{ setting('company_name', 'PT. Sinar Surya Semestaraya') }}
    @endif

    @if(isset($bankAccounts) && $bankAccounts->count() > 1)
        <br><strong style="font-size: 8px; color: #666;">Bank Alternatif:</strong><br>
        @foreach($bankAccounts as $bank)
            @if(!$primaryBank || $bank->id != $primaryBank->id)
                <span style="font-size: 8px;">{{ $bank->nama_bank }}: {{ $bank->nomor_rekening }} (a.n. {{ $bank->atas_nama }})</span><br>
            @endif
        @endforeach
    @endif
</div>
```

## Logic Flow

### Primary Bank Priority

```
IF primaryBank exists AND is set:
    → Display primary bank details
ELSE IF bankAccounts exist AND not empty:
    → Display first available bank
ELSE:
    → Display original hardcoded data (fallback)
```

### Alternative Banks Display

```
IF bankAccounts count > 1:
    → Show "Bank Alternatif:" section
    → Display all banks EXCEPT primary bank
    → Format: BankName: AccountNumber (a.n. AccountHolder)
```

### Fallback Safety

-   Tetap ada fallback ke settings lama jika tidak ada bank settings
-   Memastikan PDF tidak error jika bank settings belum dikonfigurasi
-   Backward compatibility terjaga

## Data Flow Integration

### Controller Level (Already Implemented)

**File**: `InvoiceController.php` - method `exportPdf()`

```php
// Get bank accounts for template
$bankAccounts = get_bank_accounts_for_invoice();
$primaryBank = get_primary_bank_account();

// For legacy template system (atsaka & hidayah)
$data = [
    'invoice' => $invoice,
    'template' => $templateConfig,
    'logoBase64' => $logoBase64,
    'bankAccounts' => $bankAccounts,    // ← Available for templates
    'primaryBank' => $primaryBank,       // ← Available for templates
    'currentDate' => now()->format('d F Y'),
    'currentTime' => now()->format('H:i:s')
];
```

### Template Level (Now Fixed)

-   Template 'atsaka' sekarang menggunakan `$primaryBank` dan `$bankAccounts`
-   Template 'hidayah' sekarang menggunakan `$primaryBank` dan `$bankAccounts`
-   Template 'sinar-surya' tetap tidak berubah (sesuai user requirement)

## Enhanced Features

### 1. Multiple Bank Support

-   Jika ada multiple banks, semua ditampilkan dalam "Bank Alternatif" section
-   Primary bank tetap di-highlight sebagai utama

### 2. Responsive Design for PDF

-   Font size disesuaikan untuk PDF (8px untuk alternatif, 9px untuk utama)
-   Styling tetap konsisten dengan theme masing-masing template

### 3. Data Validation

-   Check `isset()` untuk memastikan variabel tersedia
-   Multiple fallback levels untuk reliability

## Testing Checklist

### Template Atsaka

-   [ ] Primary bank ditampilkan jika ada
-   [ ] First available bank ditampilkan jika tidak ada primary
-   [ ] Fallback ke settings lama jika tidak ada bank settings
-   [ ] Bank alternatif ditampilkan jika ada multiple banks
-   [ ] Field `atas_nama` menggunakan data yang benar

### Template Hidayah

-   [ ] Primary bank ditampilkan jika ada
-   [ ] First available bank ditampilkan jika tidak ada primary
-   [ ] Fallback ke settings lama jika tidak ada bank settings
-   [ ] Bank alternatif ditampilkan jika ada multiple banks
-   [ ] Field `atas_nama` menggunakan data yang benar

### Integration Tests

-   [ ] Export PDF dengan template=atsaka menggunakan bank settings
-   [ ] Export PDF dengan template=hidayah menggunakan bank settings
-   [ ] Export PDF tanpa bank settings masih berfungsi (fallback)
-   [ ] Multiple banks ditampilkan dengan benar di kedua template

## Status

🎉 **FIXED** - Template 'atsaka' dan 'hidayah' sekarang menggunakan bank settings system

**Impact**:

-   PDF export untuk template atsaka dan hidayah sekarang dinamis
-   Informasi pembayaran menggunakan bank settings dari pengaturan umum
-   Konsisten dengan system bank settings yang sudah diimplementasikan
-   Template sinar-surya tetap tidak berubah sesuai user requirement

**Next Actions**: Test PDF export untuk memastikan bank information tampil dengan benar.
