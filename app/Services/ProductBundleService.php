<?php

namespace App\Services;

use App\Models\ProductBundle;
use App\Models\ProductBundleItem;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\StokProduk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductBundleService
{
    /**
     * Process bundle dalam sales order
     * 
     * @param SalesOrder $salesOrder
     * @param array $bundleData ['bundle_id' => 1, 'quantity' => 2, 'harga' => 100000]
     * @return SalesOrderDetail
     */
    public function processBundleInSalesOrder(SalesOrder $salesOrder, array $bundleData)
    {
        $bundle = ProductBundle::with('items.produk')->findOrFail($bundleData['bundle_id']);

        DB::beginTransaction();
        try {
            // 1. Create main bundle detail
            $bundleDetail = SalesOrderDetail::create([
                'sales_order_id' => $salesOrder->id,
                'item_type' => 'bundle',
                'bundle_id' => $bundle->id,
                'produk_id' => null, // Bundle doesn't have single product
                'deskripsi' => "Bundle: {$bundle->nama}",
                'quantity' => $bundleData['quantity'],
                'quantity_terkirim' => 0,
                'satuan_id' => 1, // Default satuan for bundle
                'harga' => $bundleData['harga'] ?? $bundle->harga_bundle,
                'diskon_persen' => $bundleData['diskon_persen'] ?? 0,
                'diskon_nominal' => $bundleData['diskon_nominal'] ?? 0,
                'subtotal' => ($bundleData['harga'] ?? $bundle->harga_bundle) * $bundleData['quantity']
            ]);

            // 2. Create breakdown details untuk setiap produk dalam bundle
            foreach ($bundle->items as $bundleItem) {
                $itemQuantity = $bundleItem->quantity * $bundleData['quantity'];
                $itemPrice = $bundleItem->harga_satuan ?? $bundleItem->produk->harga_jual;

                SalesOrderDetail::create([
                    'sales_order_id' => $salesOrder->id,
                    'item_type' => 'produk',
                    'produk_id' => $bundleItem->produk_id,
                    'bundle_id' => $bundle->id,
                    'is_bundle_item' => true,
                    'parent_detail_id' => $bundleDetail->id,
                    'deskripsi' => "Dari bundle: {$bundle->nama} - {$bundleItem->produk->nama}",
                    'quantity' => $itemQuantity,
                    'quantity_terkirim' => 0,
                    'satuan_id' => $bundleItem->produk->satuan_id,
                    'harga' => $itemPrice,
                    'subtotal' => $itemPrice * $itemQuantity
                ]);
            }

            DB::commit();
            return $bundleDetail;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing bundle in sales order: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Validate bundle stock availability
     * 
     * @param int $bundleId
     * @param int $quantity
     * @param int|null $gudangId
     * @return array
     */
    public function validateBundleStock($bundleId, $quantity, $gudangId = null)
    {
        $bundle = ProductBundle::with('items.produk')->findOrFail($bundleId);
        $stockIssues = [];
        $isAvailable = true;

        foreach ($bundle->items as $bundleItem) {
            $requiredQty = $bundleItem->quantity * $quantity;

            if ($gudangId) {
                $availableStock = StokProduk::where('produk_id', $bundleItem->produk_id)
                    ->where('gudang_id', $gudangId)
                    ->sum('jumlah');
            } else {
                $availableStock = $bundleItem->produk->total_stok;
            }

            if ($availableStock < $requiredQty) {
                $isAvailable = false;
                $stockIssues[] = [
                    'produk_id' => $bundleItem->produk_id,
                    'produk_nama' => $bundleItem->produk->nama,
                    'required' => $requiredQty,
                    'available' => $availableStock,
                    'shortage' => $requiredQty - $availableStock
                ];
            }
        }

        return [
            'is_available' => $isAvailable,
            'stock_issues' => $stockIssues,
            'max_quantity' => $bundle->getMaxAvailableQuantity($gudangId)
        ];
    }

    /**
     * Process stock deduction for bundle delivery
     * 
     * @param SalesOrderDetail $bundleDetail
     * @param int $deliveredQuantity
     * @param int $gudangId
     * @return void
     */
    public function processStockDeductionForBundle(SalesOrderDetail $bundleDetail, $deliveredQuantity, $gudangId)
    {
        if (!$bundleDetail->isBundleItem()) {
            throw new \Exception('This is not a bundle detail');
        }

        DB::beginTransaction();
        try {
            // Process stock deduction untuk setiap produk dalam bundle
            foreach ($bundleDetail->childDetails as $childDetail) {
                $productDeductionQty = ($childDetail->quantity / $bundleDetail->quantity) * $deliveredQuantity;

                $this->deductProductStock(
                    $childDetail->produk_id,
                    $gudangId,
                    $productDeductionQty,
                    "Bundle delivery: {$bundleDetail->bundle->nama}"
                );

                // Update quantity terkirim untuk child detail
                $childDetail->quantity_terkirim += $productDeductionQty;
                $childDetail->save();
            }

            // Update quantity terkirim untuk bundle detail
            $bundleDetail->quantity_terkirim += $deliveredQuantity;
            $bundleDetail->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing stock deduction for bundle: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Deduct product stock
     */
    private function deductProductStock($produkId, $gudangId, $quantity, $keterangan)
    {
        $stokProduk = StokProduk::where('produk_id', $produkId)
            ->where('gudang_id', $gudangId)
            ->first();

        if (!$stokProduk || $stokProduk->jumlah < $quantity) {
            throw new \Exception("Insufficient stock for product ID {$produkId}");
        }

        $stokProduk->jumlah -= $quantity;
        $stokProduk->save();

        // Log stock movement (assuming you have stock movement logging)
        // This would need to be implemented based on your existing stock logging system
    }

    /**
     * Get bundle price calculation
     * 
     * @param ProductBundle $bundle
     * @return array
     */
    public function getBundlePriceCalculation(ProductBundle $bundle)
    {
        $normalPrice = 0;
        $bundlePrice = $bundle->harga_bundle;

        foreach ($bundle->items as $item) {
            $itemPrice = $item->harga_satuan ?? $item->produk->harga_jual;
            $normalPrice += $itemPrice * $item->quantity;
        }

        $savings = $normalPrice - $bundlePrice;
        $savingsPercent = $normalPrice > 0 ? ($savings / $normalPrice) * 100 : 0;

        return [
            'normal_price' => $normalPrice,
            'bundle_price' => $bundlePrice,
            'savings_amount' => $savings,
            'savings_percent' => $savingsPercent
        ];
    }
}
