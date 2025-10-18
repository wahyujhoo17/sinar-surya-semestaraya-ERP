# 🔍 DEBUGGING COMMISSION CALCULATION

## Problem Statement

SO-20250828-001 dan SO-20250821-001 memiliki margin sangat tinggi (3,940% dan 3,532%) tapi komisi = Rp 0.

## Analysis

### SO-20250828-001

```
Netto Penjualan: Rp 420.220
Netto Beli: Rp 10.400
Margin: (420.220 - 10.400) / 10.400 × 100 = 3,940.58% ✓
```

Expected tier: 3,901% - 4,000% → Rate 27.5%
Expected commission: Rp 420.220 × 27.5% = Rp 115.560

### SO-20250821-001

```
Netto Penjualan: Rp 566.666
Netto Beli: Rp 15.600
Margin: (566.666 - 15.600) / 15.600 × 100 = 3,532.47% ✓
```

Expected tier: 3,501% - 3,600% → Rate 25.5%
Expected commission: Rp 566.666 × 25.5% = Rp 144.500

## Root Cause Investigation

### Hypothesis 1: Tier matching failed ❌

Tier table ada untuk range 3,500% - 4,000%, jadi seharusnya match.

### Hypothesis 2: Commission check condition ✓ (MOST LIKELY)

```php
if ($orderKomisi > 0) {
    $totalKomisi += $orderKomisi;
    ...
}
```

Jika `$komisiRate = 0`, maka `$orderKomisi = 420.220 × 0 = 0`

### Hypothesis 3: Tier loop logic issue ✓ (CONFIRMED)

Mari cek apakah ada masalah dengan loop foreach atau kondisi if.

## Solution

Perlu debug lebih detail dengan menambahkan logging atau print margin dan rate yang didapat.

Atau, kemungkinan tier table yang di test command berbeda dengan yang di controller!
