<?php

namespace App\Services;

use App\Models\BillOfMaterial;
use App\Models\BillOfMaterialDetail;
use App\Models\Produk;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BOMCostService
{
    /**
     * Menghitung harga pokok produk berdasarkan BOM
     */
    public function calculateBOMCost($bomId, $quantity = 1)
    {
        try {
            $bom = BillOfMaterial::with(['details.komponen'])->findOrFail($bomId);
            $totalCost = 0;
            $costBreakdown = [];

            Log::info("Calculating BOM cost for BOM ID: {$bomId}, Details count: " . $bom->details->count());

            foreach ($bom->details as $component) {
                Log::info("Processing component ID: {$component->komponen_id}, has komponen: " . ($component->komponen ? 'yes' : 'no'));

                if ($component->komponen) {
                    $componentUnitCost = $component->komponen->harga_beli ?? 0;
                    $componentTotalCost = $componentUnitCost * $component->quantity;
                    $totalCost += $componentTotalCost;

                    Log::info("Component: {$component->komponen->nama}, Unit cost: {$componentUnitCost}, Quantity: {$component->quantity}, Total: {$componentTotalCost}");

                    $costBreakdown[] = [
                        'komponen_id' => $component->komponen_id,
                        'komponen_nama' => $component->komponen->nama,
                        'quantity' => $component->quantity,
                        'harga_satuan' => $componentUnitCost,
                        'total_cost' => $componentTotalCost
                    ];
                }
            }

            $costPerUnit = $quantity > 0 ? $totalCost / $quantity : 0;

            Log::info("Final calculation - Total cost: {$totalCost}, Cost per unit: {$costPerUnit}");

            return [
                'cost_per_unit' => round($costPerUnit, 2),
                'total_cost' => round($totalCost, 2),
                'breakdown' => $costBreakdown,
                'bom_id' => $bomId,
                'quantity' => $quantity
            ];
        } catch (\Exception $e) {
            Log::error('Error calculating BOM cost: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    /**
     * Update harga beli produk berdasarkan BOM
     */
    public function updateProductCostFromBOM($produkId, $bomId = null)
    {
        try {
            Log::info("updateProductCostFromBOM called with Product ID: {$produkId}, BOM ID: {$bomId}");

            $produk = Produk::findOrFail($produkId);
            Log::info("Product found: {$produk->nama}");

            // Jika BOM ID tidak diberikan, cari BOM aktif untuk produk ini
            if (!$bomId) {
                $activeBOM = BillOfMaterial::where('produk_id', $produkId)
                    ->where('is_active', true)
                    ->first();

                if (!$activeBOM) {
                    Log::info("No active BOM found for product ID: {$produkId}");
                    return false;
                }

                $bomId = $activeBOM->id;
            }

            Log::info("Using BOM ID: {$bomId}");

            // Hitung cost dari BOM
            $costCalculation = $this->calculateBOMCost($bomId);

            if (!$costCalculation) {
                Log::error("Failed to calculate BOM cost for BOM ID: {$bomId}");
                return false;
            }

            $newCost = $costCalculation['cost_per_unit'];
            $oldCost = $produk->harga_beli;

            Log::info("Cost calculation result - Old: {$oldCost}, New: {$newCost}");

            // Update harga beli produk
            $produk->update(['harga_beli' => $newCost]);

            Log::info("Product cost updated successfully");

            // Log aktivitas
            $this->logCostUpdate($produk, $bomId, $oldCost, $newCost, $costCalculation['breakdown']);

            return [
                'success' => true,
                'old_cost' => $oldCost,
                'new_cost' => $newCost,
                'calculation' => $costCalculation
            ];
        } catch (\Exception $e) {
            Log::error('Error updating product cost from BOM: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Update semua produk yang bergantung pada komponen tertentu
     */
    public function updateDependentProducts($komponenId)
    {
        try {
            // Cari semua BOM yang menggunakan produk ini sebagai komponen
            $dependentBOMs = BillOfMaterialDetail::where('komponen_id', $komponenId)
                ->with(['bom'])
                ->get();

            $updatedProducts = [];

            foreach ($dependentBOMs as $detail) {
                if ($detail->bom && $detail->bom->is_active) {
                    $result = $this->updateProductCostFromBOM($detail->bom->produk_id, $detail->bom->id);
                    if ($result) {
                        $updatedProducts[] = [
                            'produk_id' => $detail->bom->produk_id,
                            'bom_id' => $detail->bom->id,
                            'result' => $result
                        ];
                    }
                }
            }

            return $updatedProducts;
        } catch (\Exception $e) {
            Log::error('Error updating dependent products: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get cost breakdown untuk produk dengan BOM
     */
    public function getCostBreakdown($produkId)
    {
        try {
            $produk = Produk::findOrFail($produkId);
            $activeBOM = BillOfMaterial::where('produk_id', $produkId)
                ->where('is_active', true)
                ->first();

            if (!$activeBOM) {
                return [
                    'has_bom' => false,
                    'message' => 'Produk tidak memiliki BOM aktif'
                ];
            }

            $costCalculation = $this->calculateBOMCost($activeBOM->id);

            if (!$costCalculation) {
                return [
                    'has_bom' => true,
                    'produk' => $produk,
                    'bom' => $activeBOM,
                    'current_cost' => $produk->harga_beli,
                    'calculated_cost' => 0,
                    'cost_difference' => $produk->harga_beli,
                    'breakdown' => [],
                    'error' => 'Gagal menghitung cost dari BOM'
                ];
            }

            return [
                'has_bom' => true,
                'produk' => $produk,
                'bom' => $activeBOM,
                'current_cost' => $produk->harga_beli,
                'calculated_cost' => $costCalculation['cost_per_unit'],
                'cost_difference' => $produk->harga_beli - $costCalculation['cost_per_unit'],
                'breakdown' => $costCalculation['breakdown']
            ];
        } catch (\Exception $e) {
            Log::error('Error getting cost breakdown: ' . $e->getMessage());
            return [
                'has_bom' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Batch update semua produk dengan BOM aktif
     */
    public function batchUpdateAllBOMProducts()
    {
        try {
            $activeBOMs = BillOfMaterial::where('is_active', true)
                ->with(['produk'])
                ->get();

            $results = [];

            foreach ($activeBOMs as $bom) {
                $result = $this->updateProductCostFromBOM($bom->produk_id, $bom->id);
                if ($result) {
                    $results[] = [
                        'bom_id' => $bom->id,
                        'produk_id' => $bom->produk_id,
                        'produk_nama' => $bom->produk->nama,
                        'result' => $result
                    ];
                }
            }

            return $results;
        } catch (\Exception $e) {
            Log::error('Error in batch update BOM products: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Log aktivitas update cost
     */
    private function logCostUpdate($produk, $bomId, $oldCost, $newCost, $breakdown)
    {
        try {
            LogAktivitas::create([
                'user_id' => Auth::id() ?? 1, // Default ke user 1 jika tidak ada auth
                'aktivitas' => 'update_harga_beli_bom',
                'modul' => 'produk',
                'data_id' => $produk->id,
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'detail' => json_encode([
                    'produk_nama' => $produk->nama,
                    'bom_id' => $bomId,
                    'harga_lama' => $oldCost,
                    'harga_baru' => $newCost,
                    'selisih' => $newCost - $oldCost,
                    'metode' => 'BOM_calculation',
                    'breakdown' => $breakdown
                ])
            ]);
        } catch (\Exception $e) {
            Log::error('Error logging cost update: ' . $e->getMessage());
        }
    }
}
