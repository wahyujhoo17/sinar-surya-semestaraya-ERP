# 📊 COMMISSION PPN CALCULATION - VISUAL GUIDE

## Flow Diagram: How PPN Rules Are Applied

```
┌─────────────────────────────────────────────────────────────┐
│                 COMMISSION CALCULATION START                 │
│                  For each Sales Order Item                   │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
        ┌────────────────────────────────────┐
        │  Check: Sales Order has PPN?       │
        │  (sales_order.ppn > 0)             │
        └────────┬──────────────┬────────────┘
                 │              │
        YES ─────┘              └───── NO
         │                             │
         ▼                             ▼
┌─────────────────────┐      ┌──────────────────────┐
│ Sales HAS PPN       │      │ Sales NO PPN         │
│ (11% typically)     │      │ (ppn = 0 or NULL)    │
└─────────┬───────────┘      └──────────┬───────────┘
          │                              │
          ▼                              ▼
┌──────────────────────────┐   ┌──────────────────────────┐
│ Check Purchase PPN       │   │ Check Purchase PPN       │
│ Query latest PO for      │   │ Query latest PO for      │
│ this product             │   │ this product             │
└─────────┬────────────────┘   └─────────┬────────────────┘
          │                              │
          ▼                              ▼
  ┌───────┴────────┐             ┌──────┴────────┐
  │ Purchase PPN?  │             │ Purchase PPN? │
  └───┬────────┬───┘             └───┬───────┬───┘
      │        │                     │       │
  YES │        │ NO              YES │       │ NO
      │        │                     │       │
      ▼        ▼                     ▼       ▼
┌──────────┐ ┌──────────┐      ┌─────────┐ ┌──────────┐
│ RULE 2   │ │ RULE 1   │      │ RULE 3  │ │ NO PPN   │
│Both PPN  │ │Sales PPN │      │Purch.PPN│ │No adjust │
└────┬─────┘ └────┬─────┘      └────┬────┘ └────┬─────┘
     │            │                  │           │
     ▼            ▼                  ▼           ▼
```

---

## Rule 1: Sales PPN + Purchase Non-PPN

### Visual Example

```
INPUT:
┌──────────────────────────────────────┐
│ SALES ORDER                          │
│ ├─ PPN: 11% ✓                       │
│ └─ Harga Jual: Rp 1.110.000         │
└──────────────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────┐
│ PRODUCT PURCHASE HISTORY             │
│ ├─ Last PO: PO-20250508-001         │
│ ├─ PPN: 0% ✗                        │
│ └─ Harga Beli: Rp 800.000           │
└──────────────────────────────────────┘

CALCULATION:
┌──────────────────────────────────────┐
│ 🔧 ADJUSTMENT NEEDED                 │
│                                       │
│ Netto Jual Original: Rp 1.110.000    │
│ ─────────────────────────────────────│
│ Remove Sales PPN:                    │
│ 1.110.000 ÷ 1.11 = Rp 1.000.000 ⭐  │
│ ─────────────────────────────────────│
│ Netto Beli: Rp 800.000 (unchanged)  │
│ ─────────────────────────────────────│
│ Margin: (1M - 800K) / 800K = 25%    │
│ Commission Rate: 1.5% (tier 25%)    │
│ Commission: 1M × 1.5% = Rp 15.000   │
└──────────────────────────────────────┘

WHY? PPN penjualan bukan profit, jadi harus dikeluarkan
```

---

## Rule 2: Sales PPN + Purchase PPN

### Visual Example

```
INPUT:
┌──────────────────────────────────────┐
│ SALES ORDER                          │
│ ├─ PPN: 11% ✓                       │
│ └─ Harga Jual: Rp 1.110.000         │
└──────────────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────┐
│ PRODUCT PURCHASE HISTORY             │
│ ├─ Last PO: PO-20250508-001         │
│ ├─ PPN: 11% ✓                       │
│ └─ Harga Beli: Rp 888.000           │
└──────────────────────────────────────┘

CALCULATION:
┌──────────────────────────────────────┐
│ ✅ NO ADJUSTMENT NEEDED               │
│                                       │
│ Netto Jual: Rp 1.110.000 (as is)    │
│ Netto Beli: Rp 888.000 (as is)      │
│ ─────────────────────────────────────│
│ Margin: (1.11M - 888K) / 888K = 25% │
│ Commission Rate: 1.5% (tier 25%)    │
│ Commission: 1.11M × 1.5% = Rp 16.650│
└──────────────────────────────────────┘

WHY? Kedua nilai sudah include PPN, representing true business scenario
```

---

## Rule 3: Sales Non-PPN + Purchase PPN

### Visual Example

```
INPUT:
┌──────────────────────────────────────┐
│ SALES ORDER                          │
│ ├─ PPN: 0% ✗                        │
│ └─ Harga Jual: Rp 1.000.000         │
└──────────────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────┐
│ PRODUCT PURCHASE HISTORY             │
│ ├─ Last PO: PO-20250508-001         │
│ ├─ PPN: 11% ✓                       │
│ └─ Harga Beli: Rp 888.000           │
└──────────────────────────────────────┘

CALCULATION:
┌──────────────────────────────────────┐
│ ✅ NO ADJUSTMENT NEEDED               │
│                                       │
│ Netto Jual: Rp 1.000.000 (no PPN)   │
│ Netto Beli: Rp 888.000 (incl PPN)   │
│ ─────────────────────────────────────│
│ Margin: (1M - 888K) / 888K = 12.6%  │
│ Commission Rate: 0% (below min 18%) │
│ Commission: Rp 0                     │
└──────────────────────────────────────┘

WHY? Purchase PPN adalah biaya riil yang harus dihitung
```

---

## Commission Tier Table

```
┌────────────────┬──────────────┬────────────────┐
│ Margin Range   │ Commission % │ Example        │
├────────────────┼──────────────┼────────────────┤
│ < 18%          │ 0.00%        │ Not qualified  │
│ 18.00-20.00%   │ 1.00%        │ Baseline       │
│ 20.50-25.00%   │ 1.25%        │ Good           │
│ 25.50-30.00%   │ 1.50%        │ Better         │
│ 30.50-35.00%   │ 1.75%        │ Great          │
│ 35.50-40.00%   │ 2.00%        │ Excellent      │
│ ...            │ ...          │ ...            │
│ 95.50-100.00%  │ 5.00%        │ Outstanding    │
│ 101.00-110.00% │ 5.25%        │ Exceptional    │
│ 126.00-140.00% │ 5.75%        │ ⭐ SO-20250829 │
│ ...            │ ...          │ ...            │
│ 1401.00-1500%  │ 15.00%       │ Maximum        │
│ > 1500%        │ 0.00%        │ Invalid data   │
└────────────────┴──────────────┴────────────────┘
```

---

## Real Test Case Breakdown: SO-20250829-001

```
SALES ORDER: SO-20250829-001
Customer: PT Palm Lampung Persada
Sales PPN: 11%

┌─────────────────────────────────────────────────────────────┐
│ PRODUCT 1: Acetic Acid Glacial                             │
├─────────────────────────────────────────────────────────────┤
│ Qty: 1 × Rp 1.750.000                                      │
│ Purchase PPN: 11% (from PO-20250508-001)                   │
│ ➜ RULE 2: Both PPN                                         │
│                                                             │
│ Netto Jual: 1.750.000 (no adjustment)                      │
│ Netto Beli: 1.250.000 (no adjustment)                      │
│ Contribution: Jual = Rp 1.750.000 | Beli = Rp 1.250.000   │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ PRODUCT 2: 1-Amino-2-Hydroxy-4-Naphthalein                │
├─────────────────────────────────────────────────────────────┤
│ Qty: 1 × Rp 200.000                                        │
│ Purchase PPN: 11% (from PO-20250508-001)                   │
│ ➜ RULE 2: Both PPN                                         │
│                                                             │
│ Netto Jual: 200.000 (no adjustment)                        │
│ Netto Beli: 0 (⚠️ missing data)                            │
│ Contribution: Jual = Rp 200.000 | Beli = Rp 0             │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ PRODUCT 3: Barbituric acid                                 │
├─────────────────────────────────────────────────────────────┤
│ Qty: 1 × Rp 1.450.000                                      │
│ Purchase PPN: No PPN (no purchase history)                 │
│ ➜ RULE 1: Sales PPN only ⭐                                │
│                                                             │
│ Netto Jual Original: 1.450.000                             │
│ Adjustment: 1.450.000 / 1.11 = 1.306.306                   │
│ Netto Beli: 0 (⚠️ missing data)                            │
│ Contribution: Jual = Rp 1.306.306 | Beli = Rp 0           │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ PRODUCT 4: Perchloric Acid                                 │
├─────────────────────────────────────────────────────────────┤
│ Qty: 1 × Rp 500.000                                        │
│ Purchase PPN: No PPN (no purchase history)                 │
│ ➜ RULE 1: Sales PPN only ⭐                                │
│                                                             │
│ Netto Jual Original: 500.000                               │
│ Adjustment: 500.000 / 1.11 = 450.450                       │
│ Netto Beli: 350.000                                        │
│ Contribution: Jual = Rp 450.450 | Beli = Rp 350.000       │
└─────────────────────────────────────────────────────────────┘

═══════════════════════════════════════════════════════════════
                         TOTALS
═══════════════════════════════════════════════════════════════

Total Netto Penjualan: Rp 3.706.757
  = 1.750.000 + 200.000 + 1.306.306 + 450.450

Total Netto Beli: Rp 1.600.000
  = 1.250.000 + 0 + 0 + 350.000

Margin = (3.706.757 - 1.600.000) / 1.600.000 × 100
       = 2.106.757 / 1.600.000 × 100
       = 131.67%

Commission Rate: 5.75% (tier 126-140%)

💰 COMMISSION = 3.706.757 × 5.75%
              = Rp 213.138,53
              ≈ Rp 213.139
```

---

## Database Query Flow

```
FOR EACH Sales Order Item:

1️⃣ Get Sales Order PPN
   ┌──────────────────────────────────┐
   │ SELECT ppn FROM sales_order      │
   │ WHERE id = ?                     │
   └──────────────────────────────────┘

2️⃣ Get Product Details
   ┌──────────────────────────────────┐
   │ SELECT * FROM produk             │
   │ WHERE id = ?                     │
   └──────────────────────────────────┘

3️⃣ Get Latest Purchase Order PPN
   ┌────────────────────────────────────────────────┐
   │ SELECT po.ppn                                  │
   │ FROM purchase_order po                         │
   │ JOIN purchase_order_detail pod                 │
   │   ON po.id = pod.po_id                        │
   │ WHERE pod.produk_id = ?                       │
   │   AND po.status = 'selesai'                   │
   │ ORDER BY po.tanggal DESC                      │
   │ LIMIT 1                                       │
   └────────────────────────────────────────────────┘

4️⃣ Apply PPN Logic
   IF sales_ppn > 0 AND purchase_ppn = 0:
       → RULE 1: Adjust netto jual
   ELSEIF sales_ppn > 0 AND purchase_ppn > 0:
       → RULE 2: No adjustment
   ELSEIF sales_ppn = 0 AND purchase_ppn > 0:
       → RULE 3: No adjustment
   ELSE:
       → NO PPN: No adjustment

5️⃣ Calculate Commission
   margin = (netto_jual - netto_beli) / netto_beli × 100
   rate = getTierRate(margin)
   commission = netto_jual × rate / 100
```

---

## Before vs After Comparison

### Scenario: Product sold at Rp 1.110.000 (incl 11% PPN), bought at Rp 800.000 (no PPN)

```
┌─────────────────────────────────────────────────────────┐
│                    BEFORE (OLD FORMULA)                  │
├─────────────────────────────────────────────────────────┤
│ Netto Jual: Rp 1.110.000 (with PPN)                    │
│ Netto Beli: Rp 800.000 (no PPN)                        │
│                                                          │
│ Margin = (1.110.000 - 800.000) / 800.000               │
│        = 310.000 / 800.000                              │
│        = 38.75% ❌                                      │
│                                                          │
│ Commission Rate: 2% (tier 35.50-40.00%)                │
│ Commission = 1.110.000 × 2% = Rp 22.200 ❌             │
│                                                          │
│ PROBLEM: Margin inflated by PPN (Rp 100.000)           │
│          which is not actual profit!                    │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                    AFTER (NEW FORMULA)                   │
├─────────────────────────────────────────────────────────┤
│ Netto Jual: Rp 1.110.000 / 1.11 = Rp 1.000.000 ⭐      │
│ Netto Beli: Rp 800.000 (no PPN)                        │
│                                                          │
│ Margin = (1.000.000 - 800.000) / 800.000               │
│        = 200.000 / 800.000                              │
│        = 25% ✅                                         │
│                                                          │
│ Commission Rate: 1.5% (tier 25.50-30.00%)              │
│ Commission = 1.000.000 × 1.5% = Rp 15.000 ✅           │
│                                                          │
│ CORRECT: True business margin without PPN distortion   │
│          More fair commission calculation               │
└─────────────────────────────────────────────────────────┘

SAVINGS: Rp 22.200 - Rp 15.000 = Rp 7.200 (32.4% reduction)
But more importantly: ACCURATE representation of actual profit!
```

---

**Document:** Visual Guide  
**Version:** 1.0  
**Date:** 18 Oktober 2025
