<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Produk;
use App\Models\BillOfMaterial;
use App\Models\ProductBundle;
use App\Services\BOMCostService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SalesMarginService
{
    protected $bomCostService;
    protected $productHppCache = [];
    protected $bundleHppCache = [];

    public function __construct(BOMCostService $bomCostService)
    {
        $this->bomCostService = $bomCostService;
    }

    /**
     * Get HPP per unit for a single product.
     * Takes into account active BOM if present, otherwise uses produk.harga_beli.
     *
     * @param int|Produk $produkInput
     * @return float
     */
    public function getProductHppUnit($produkInput): float
    {
        $produkId = $produkInput instanceof Produk ? $produkInput->id : (int) $produkInput;

        if (array_key_exists($produkId, $this->productHppCache)) {
            return $this->productHppCache[$produkId];
        }

        $produk = $produkInput instanceof Produk ? $produkInput : Produk::find($produkId);
        if (!$produk) {
            $this->productHppCache[$produkId] = 0.0;
            return 0.0;
        }

        // Check if product has an active BOM
        $activeBOM = BillOfMaterial::where('produk_id', $produkId)
            ->where('is_active', true)
            ->first();

        if ($activeBOM) {
            $bomCalculation = $this->bomCostService->calculateBOMCost($activeBOM->id);
            if ($bomCalculation && isset($bomCalculation['cost_per_unit']) && $bomCalculation['cost_per_unit'] > 0) {
                $hpp = (float) $bomCalculation['cost_per_unit'];
                $this->productHppCache[$produkId] = $hpp;
                return $hpp;
            }
        }

        // Prioritas Fallback: harga_beli_rata_rata (> 0) -> harga_beli (> 0) -> 0.0
        $hpp = 0.0;
        if (!empty($produk->harga_beli_rata_rata) && (float) $produk->harga_beli_rata_rata > 0) {
            $hpp = (float) $produk->harga_beli_rata_rata;
        } elseif (!empty($produk->harga_beli) && (float) $produk->harga_beli > 0) {
            $hpp = (float) $produk->harga_beli;
        }

        $this->productHppCache[$produkId] = $hpp;
        return $hpp;
    }

    /**
     * Get HPP per unit for a Product Bundle.
     * Sums up (item_quantity * HPP_item) for all items in the bundle.
     *
     * @param int|ProductBundle $bundleInput
     * @return float
     */
    public function getBundleHppUnit($bundleInput): float
    {
        $bundleId = $bundleInput instanceof ProductBundle ? $bundleInput->id : (int) $bundleInput;

        if (array_key_exists($bundleId, $this->bundleHppCache)) {
            return $this->bundleHppCache[$bundleId];
        }

        $bundle = $bundleInput instanceof ProductBundle ? $bundleInput : ProductBundle::with('items.produk')->find($bundleId);
        if (!$bundle || !$bundle->items) {
            $this->bundleHppCache[$bundleId] = 0.0;
            return 0.0;
        }

        $totalHppUnit = 0.0;
        foreach ($bundle->items as $item) {
            $itemHppUnit = $this->getProductHppUnit($item->produk ?? $item->produk_id);
            $totalHppUnit += ($item->quantity * $itemHppUnit);
        }

        $this->bundleHppCache[$bundleId] = $totalHppUnit;
        return $totalHppUnit;
    }

    /**
     * Calculate HPP, Profit, and Margin for an InvoiceDetail item.
     *
     * @param InvoiceDetail $detail
     * @return array
     */
    public function calculateDetailMargin(InvoiceDetail $detail): array
    {
        $qty = (float) ($detail->quantity ?? $detail->qty ?? 0);
        $hargaSatuan = (float) ($detail->harga ?? 0);
        
        // Calculate item revenue / subtotal
        if (isset($detail->subtotal) && $detail->subtotal > 0) {
            $omzet = (float) $detail->subtotal;
        } else {
            $diskon = (float) ($detail->diskon_nominal ?? 0);
            if ($diskon <= 0 && isset($detail->diskon_persen) && $detail->diskon_persen > 0) {
                $diskon = ($hargaSatuan * $qty) * ($detail->diskon_persen / 100);
            }
            $omzet = ($hargaSatuan * $qty) - $diskon;
        }

        $hppSatuan = 0.0;

        if ($detail->item_type === 'bundle' || ($detail->bundle_id && !$detail->is_bundle_item)) {
            // Main bundle detail
            $hppSatuan = $this->getBundleHppUnit($detail->bundle ?? $detail->bundle_id);
        } elseif ($detail->produk_id) {
            // Regular product
            $hppSatuan = $this->getProductHppUnit($detail->produk ?? $detail->produk_id);
        }

        $totalHpp = $qty * $hppSatuan;
        $hasHpp = ($hppSatuan > 0);

        if ($hasHpp) {
            $labaKotor = $omzet - $totalHpp;
            $marginPersen = $omzet > 0 ? ($labaKotor / $omzet) * 100 : 0.0;
        } else {
            // Jika HPP belum di-set di master produk (harga_beli = 0), laba kotor & margin diset 0 / N/A
            $labaKotor = 0.0;
            $marginPersen = 0.0;
        }

        return [
            'qty' => $qty,
            'omzet' => $omzet,
            'hpp_satuan' => $hppSatuan,
            'total_hpp' => $totalHpp,
            'laba_kotor' => $labaKotor,
            'margin_persen' => round($marginPersen, 2),
            'has_hpp' => $hasHpp
        ];
    }

    /**
     * Calculate total HPP, Profit, and Margin for an Invoice (Header + Details).
     *
     * @param Invoice $invoice
     * @return array
     */
    public function calculateInvoiceMargin(Invoice $invoice): array
    {
        $details = $invoice->relationLoaded('details') ? $invoice->details : $invoice->details()->with(['produk', 'bundle.items.produk'])->get();

        $totalOmzetItems = 0.0;
        $totalHppInvoice = 0.0;
        $itemsWithoutHppCount = 0;
        $detailBreakdowns = [];

        foreach ($details as $detail) {
            // Skip child bundle items if parent detail exists to prevent double counting
            if ($detail->is_bundle_item && $detail->parent_detail_id !== null) {
                continue;
            }

            $calc = $this->calculateDetailMargin($detail);
            $totalOmzetItems += $calc['omzet'];
            $totalHppInvoice += $calc['total_hpp'];

            if (!$calc['has_hpp']) {
                $itemsWithoutHppCount++;
            }

            $detailBreakdowns[] = array_merge([
                'detail_id' => $detail->id,
                'nama_produk' => $detail->nama_produk ?? ($detail->produk->nama ?? 'Produk'),
            ], $calc);
        }

        // Net sales revenue after invoice-level discounts
        $diskonInvoice = (float) ($invoice->diskon_nominal ?? 0);
        if ($diskonInvoice <= 0 && isset($invoice->diskon_persen) && $invoice->diskon_persen > 0) {
            $diskonInvoice = $totalOmzetItems * ($invoice->diskon_persen / 100);
        }

        $netOmzet = max(0.0, $totalOmzetItems - $diskonInvoice);
        
        // If net omzet is 0 or invoice.subtotal is set, use subtotal / net total
        if ($netOmzet <= 0 && isset($invoice->subtotal) && $invoice->subtotal > 0) {
            $netOmzet = (float) $invoice->subtotal - $diskonInvoice;
        }
        if ($netOmzet <= 0 && isset($invoice->total) && $invoice->total > 0) {
            // Subtract ppn and shipping if present
            $netOmzet = (float) $invoice->total - (float) ($invoice->ppn ?? 0) - (float) ($invoice->ongkos_kirim ?? 0);
        }

        $labaKotor = $netOmzet - $totalHppInvoice;
        $marginPersen = $netOmzet > 0 ? ($labaKotor / $netOmzet) * 100 : 0.0;

        return [
            'invoice_id' => $invoice->id,
            'omzet_gross' => $totalOmzetItems,
            'diskon_invoice' => $diskonInvoice,
            'omzet_net' => $netOmzet,
            'total_hpp' => $totalHppInvoice,
            'laba_kotor' => $labaKotor,
            'margin_persen' => round($marginPersen, 2),
            'items_without_hpp_count' => $itemsWithoutHppCount,
            'details' => $detailBreakdowns
        ];
    }

    /**
     * Calculate summary margin metrics across a collection of invoices.
     *
     * @param Collection|array $invoices
     * @return array
     */
    public function calculateAggregateMargin($invoices): array
    {
        $grandOmzet = 0.0;
        $grandHpp = 0.0;
        $grandLaba = 0.0;

        foreach ($invoices as $invoice) {
            if (!$invoice instanceof Invoice) {
                continue;
            }
            $marginData = $this->calculateInvoiceMargin($invoice);
            $grandOmzet += $marginData['omzet_net'];
            $grandHpp += $marginData['total_hpp'];
            $grandLaba += $marginData['laba_kotor'];
        }

        $rataMarginPersen = $grandOmzet > 0 ? ($grandLaba / $grandOmzet) * 100 : 0.0;

        return [
            'total_omzet' => $grandOmzet,
            'total_hpp' => $grandHpp,
            'total_laba_kotor' => $grandLaba,
            'rata_margin_persen' => round($rataMarginPersen, 2)
        ];
    }
}
