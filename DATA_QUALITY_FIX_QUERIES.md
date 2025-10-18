# DATA QUALITY ANALYSIS & FIX QUERIES

## Generated: 18 Oktober 2025

---

## 1. PRODUCTS WITH MISSING PURCHASE COST

### Query to Find Products with Zero/Null harga_beli

```sql
SELECT
    p.id,
    p.nama,
    p.kode,
    p.harga_beli,
    p.harga_jual,
    COUNT(DISTINCT pod.id) as total_purchases,
    AVG(pod.harga) as avg_purchase_price,
    MAX(pod.harga) as last_purchase_price
FROM produk p
LEFT JOIN purchase_order_detail pod ON p.id = pod.produk_id
LEFT JOIN purchase_order po ON pod.po_id = po.id
WHERE (p.harga_beli = 0 OR p.harga_beli IS NULL)
  AND po.status = 'selesai'
GROUP BY p.id, p.nama, p.kode, p.harga_beli, p.harga_jual
HAVING total_purchases > 0
ORDER BY total_purchases DESC;
```

### Fix Query: Update harga_beli from Purchase History

```sql
-- Update harga_beli dengan weighted average dari purchase order yang selesai
UPDATE produk p
JOIN (
    SELECT
        pod.produk_id,
        SUM(pod.harga * pod.quantity) / SUM(pod.quantity) as weighted_avg_price
    FROM purchase_order_detail pod
    JOIN purchase_order po ON pod.po_id = po.id
    WHERE po.status = 'selesai'
    GROUP BY pod.produk_id
) calc ON p.id = calc.produk_id
SET p.harga_beli = calc.weighted_avg_price
WHERE p.harga_beli = 0 OR p.harga_beli IS NULL;
```

**Expected Impact:**

-   Updates products with purchase history but missing harga_beli
-   Uses weighted average for accuracy
-   Only affects completed purchase orders

---

## 2. PRODUCTS WITH NO PURCHASE HISTORY

### Query to Find Products Never Purchased

```sql
SELECT
    p.id,
    p.nama,
    p.kode,
    p.harga_beli,
    p.harga_jual,
    sod.total_sold
FROM produk p
LEFT JOIN (
    SELECT produk_id, COUNT(*) as total_sold
    FROM sales_order_detail
    GROUP BY produk_id
) sod ON p.id = sod.produk_id
WHERE NOT EXISTS (
    SELECT 1
    FROM purchase_order_detail pod
    JOIN purchase_order po ON pod.po_id = po.id
    WHERE pod.produk_id = p.id
      AND po.status = 'selesai'
)
AND sod.total_sold > 0
ORDER BY sod.total_sold DESC;
```

**Action Required:**

-   Review these products manually
-   Either:
    -   Input manual harga_beli based on market price
    -   Create retroactive purchase orders
    -   Mark as discontinued if no longer used

---

## 3. SALES ORDERS WITH MISSING PURCHASE DATA

### Query to Find Affected Sales Orders

```sql
SELECT
    so.nomor,
    so.tanggal,
    c.nama as customer,
    COUNT(DISTINCT sod.id) as total_items,
    SUM(CASE
        WHEN p.harga_beli = 0 OR p.harga_beli IS NULL THEN 1
        ELSE 0
    END) as items_without_cost,
    SUM(sod.harga * sod.quantity) as total_sales,
    so.ppn
FROM sales_order so
JOIN sales_order_detail sod ON so.id = sod.sales_order_id
JOIN produk p ON sod.produk_id = p.id
LEFT JOIN customer c ON so.customer_id = c.id
WHERE so.status_pembayaran = 'lunas'
GROUP BY so.id, so.nomor, so.tanggal, c.nama, so.ppn
HAVING items_without_cost > 0
ORDER BY so.tanggal DESC;
```

**Expected Results:**

-   List of sales orders that would be affected by missing cost data
-   Helps prioritize which products to fix first

---

## 4. PPN CONSISTENCY CHECK

### Query to Check PPN Consistency in Purchase Orders

```sql
SELECT
    p.nama as product_name,
    p.id as product_id,
    COUNT(DISTINCT po.id) as total_po,
    SUM(CASE WHEN po.ppn > 0 THEN 1 ELSE 0 END) as po_with_ppn,
    SUM(CASE WHEN po.ppn = 0 OR po.ppn IS NULL THEN 1 ELSE 0 END) as po_without_ppn,
    GROUP_CONCAT(DISTINCT po.ppn ORDER BY po.tanggal DESC) as ppn_history
FROM produk p
JOIN purchase_order_detail pod ON p.id = pod.produk_id
JOIN purchase_order po ON pod.po_id = po.id
WHERE po.status = 'selesai'
GROUP BY p.id, p.nama
HAVING po_with_ppn > 0 AND po_without_ppn > 0
ORDER BY total_po DESC;
```

**Purpose:**

-   Find products purchased with MIXED PPN status
-   These products need careful review for commission calculation

---

## 5. COMMISSION CALCULATION VALIDATION

### Query to Compare Old vs New Formula

```sql
-- This query shows potential commission differences
SELECT
    so.nomor,
    so.tanggal,
    so.ppn as sales_ppn,
    SUM(sod.harga * sod.quantity) as total_sales_original,
    SUM(
        CASE
            WHEN so.ppn > 0 AND (
                SELECT COALESCE(po.ppn, 0)
                FROM purchase_order po
                JOIN purchase_order_detail pod ON po.id = pod.po_id
                WHERE pod.produk_id = sod.produk_id
                  AND po.status = 'selesai'
                ORDER BY po.tanggal DESC
                LIMIT 1
            ) = 0
            THEN (sod.harga * sod.quantity) / (1 + so.ppn/100)
            ELSE sod.harga * sod.quantity
        END
    ) as total_sales_adjusted,
    SUM(p.harga_beli * sod.quantity) as total_cost,
    (
        (SUM(sod.harga * sod.quantity) - SUM(p.harga_beli * sod.quantity)) /
        NULLIF(SUM(p.harga_beli * sod.quantity), 0) * 100
    ) as margin_old_formula,
    (
        (SUM(
            CASE
                WHEN so.ppn > 0 AND (
                    SELECT COALESCE(po.ppn, 0)
                    FROM purchase_order po
                    JOIN purchase_order_detail pod ON po.id = pod.po_id
                    WHERE pod.produk_id = sod.produk_id
                      AND po.status = 'selesai'
                    ORDER BY po.tanggal DESC
                    LIMIT 1
                ) = 0
                THEN (sod.harga * sod.quantity) / (1 + so.ppn/100)
                ELSE sod.harga * sod.quantity
            END
        ) - SUM(p.harga_beli * sod.quantity)) /
        NULLIF(SUM(p.harga_beli * sod.quantity), 0) * 100
    ) as margin_new_formula
FROM sales_order so
JOIN sales_order_detail sod ON so.id = sod.sales_order_id
JOIN produk p ON sod.produk_id = p.id
WHERE so.status_pembayaran = 'lunas'
  AND so.tanggal >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
  AND p.harga_beli > 0
GROUP BY so.id, so.nomor, so.tanggal, so.ppn
HAVING total_cost > 0
ORDER BY ABS(margin_new_formula - margin_old_formula) DESC
LIMIT 20;
```

**Purpose:**

-   Shows sales orders with biggest margin differences
-   Helps validate new formula impact

---

## 6. PRODUCT PPN STATUS SUMMARY

### Query for Product-Level PPN Analysis

```sql
SELECT
    p.id,
    p.nama,
    p.kode,
    p.harga_beli,
    CASE
        WHEN latest_po.ppn > 0 THEN CONCAT(latest_po.ppn, '%')
        WHEN latest_po.ppn = 0 THEN 'No PPN'
        ELSE 'No Purchase History'
    END as purchase_ppn_status,
    latest_po.nomor as latest_po_number,
    latest_po.tanggal as latest_po_date,
    sales_summary.total_sold,
    sales_summary.total_revenue
FROM produk p
LEFT JOIN (
    SELECT
        pod.produk_id,
        po.ppn,
        po.nomor,
        po.tanggal,
        ROW_NUMBER() OVER (PARTITION BY pod.produk_id ORDER BY po.tanggal DESC) as rn
    FROM purchase_order_detail pod
    JOIN purchase_order po ON pod.po_id = po.id
    WHERE po.status = 'selesai'
) latest_po ON p.id = latest_po.produk_id AND latest_po.rn = 1
LEFT JOIN (
    SELECT
        produk_id,
        COUNT(*) as total_sold,
        SUM(harga * quantity) as total_revenue
    FROM sales_order_detail
    GROUP BY produk_id
) sales_summary ON p.id = sales_summary.produk_id
WHERE sales_summary.total_sold > 0
ORDER BY sales_summary.total_revenue DESC
LIMIT 50;
```

**Purpose:**

-   Overview of top products by revenue
-   Shows their purchase PPN status for commission calculation
-   Helps identify high-impact products

---

## 7. AUTOMATED FIX SCRIPT

### Laravel Artisan Command to Fix Data

```php
<?php
// File: app/Console/Commands/FixProductPurchaseCost.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Produk;
use App\Models\PurchaseOrderDetail;
use Illuminate\Support\Facades\DB;

class FixProductPurchaseCost extends Command
{
    protected $signature = 'fix:product-cost {--dry-run : Run without making changes}';
    protected $description = 'Fix missing harga_beli using purchase order history';

    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        // Get products with missing cost but have purchase history
        $products = Produk::whereRaw('(harga_beli = 0 OR harga_beli IS NULL)')
            ->whereHas('supplierProduks.purchaseOrderDetails.purchaseOrder', function($q) {
                $q->where('status', 'selesai');
            })
            ->get();

        $this->info("Found {$products->count()} products to fix");

        $updated = 0;
        $skipped = 0;

        foreach ($products as $product) {
            // Calculate weighted average
            $avgCost = PurchaseOrderDetail::join('purchase_order', 'purchase_order_detail.po_id', '=', 'purchase_order.id')
                ->where('purchase_order_detail.produk_id', $product->id)
                ->where('purchase_order.status', 'selesai')
                ->selectRaw('SUM(purchase_order_detail.harga * purchase_order_detail.quantity) / SUM(purchase_order_detail.quantity) as weighted_avg')
                ->value('weighted_avg');

            if ($avgCost && $avgCost > 0) {
                $this->line("Product: {$product->nama}");
                $this->line("  Current: Rp " . number_format($product->harga_beli, 0, ',', '.'));
                $this->line("  New: Rp " . number_format($avgCost, 0, ',', '.'));

                if (!$dryRun) {
                    $product->harga_beli = $avgCost;
                    $product->save();
                    $this->info("  ✓ Updated");
                }

                $updated++;
            } else {
                $this->warn("  ✗ Skipped (no valid purchase data)");
                $skipped++;
            }
        }

        $this->newLine();
        $this->info("Summary:");
        $this->line("  Updated: {$updated}");
        $this->line("  Skipped: {$skipped}");

        if ($dryRun) {
            $this->warn("Run without --dry-run to apply changes");
        }

        return 0;
    }
}
```

### Usage:

```bash
# Test first with dry run
php artisan fix:product-cost --dry-run

# Apply changes
php artisan fix:product-cost
```

---

## 8. MONITORING QUERIES

### Daily Commission Calculation Monitor

```sql
-- Run this daily to check commission calculations
SELECT
    DATE(pp.tanggal) as payment_date,
    COUNT(DISTINCT so.id) as total_orders,
    COUNT(DISTINCT k.id) as total_karyawan,
    SUM(kg.nominal) as total_commission
FROM pembayaran_piutang pp
JOIN invoice i ON pp.invoice_id = i.id
JOIN sales_order so ON i.sales_order_id = so.id
JOIN customer c ON so.customer_id = c.id
JOIN users u ON c.sales_id = u.id
JOIN karyawan k ON u.id = k.user_id
LEFT JOIN penggajian pg ON k.id = pg.karyawan_id
    AND MONTH(pp.tanggal) = pg.bulan
    AND YEAR(pp.tanggal) = pg.tahun
LEFT JOIN komponen_gaji kg ON pg.id = kg.penggajian_id
    AND kg.nama_komponen LIKE '%Komisi%'
WHERE pp.tanggal >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY DATE(pp.tanggal)
ORDER BY payment_date DESC;
```

---

## EXECUTION CHECKLIST

### Before Running Fixes:

-   [ ] Backup database
-   [ ] Run all SELECT queries to understand impact
-   [ ] Review dry-run results
-   [ ] Get approval from finance/accounting team

### After Running Fixes:

-   [ ] Re-run test commission calculation
-   [ ] Compare results with expected values
-   [ ] Update documentation
-   [ ] Monitor first payroll cycle with new formula

### Validation Steps:

-   [ ] Check margin calculations make sense
-   [ ] Verify commission amounts are reasonable
-   [ ] Confirm PPN rules applied correctly
-   [ ] Test edge cases (no PPN, mixed PPN, etc.)

---

**Document Version:** 1.0  
**Last Updated:** 18 Oktober 2025  
**Status:** Ready for Review
