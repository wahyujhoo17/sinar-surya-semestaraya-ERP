<?php

namespace App\Helpers;

/**
 * Class for debugging and tracking changes in ReturPenjualan
 */
class ReturPenjualanDebugger
{
    /**
     * Log detailed changes for audit trail
     * 
     * @param string $action Action being performed
     * @param array $before Previous state data
     * @param array $after Current state data
     * @param string $description Human-readable description
     * 
     * @return array Structured data for logging
     */
    public static function logChanges($action, $before = null, $after = null, $description = null)
    {
        // (existing code here)
    }

    /**
     * Debug date filter parameters
     * 
     * @param string $dateFilter The date filter type 
     * @param string|null $dateStart Start date for range filter
     * @param string|null $dateEnd End date for range filter
     * @param \Carbon\Carbon $today Current date reference
     * 
     * @return array Debug information
     */
    public static function debugDateFilter($dateFilter, $dateStart = null, $dateEnd = null, $today = null)
    {
        $today = $today ?: \Carbon\Carbon::today();
        $debugInfo = [
            'filter_type' => $dateFilter,
            'date_start' => $dateStart,
            'date_end' => $dateEnd,
            'reference_date' => $today->toDateString(),
        ];

        // Calculate what the date ranges should be for the given filter
        switch ($dateFilter) {
            case 'today':
                $debugInfo['expected_range'] = [
                    'start' => $today->toDateString(),
                    'end' => $today->toDateString()
                ];
                break;
            case 'yesterday':
                $debugInfo['expected_range'] = [
                    'start' => $today->copy()->subDay()->toDateString(),
                    'end' => $today->copy()->subDay()->toDateString()
                ];
                break;
            case 'this_week':
                $debugInfo['expected_range'] = [
                    'start' => $today->copy()->startOfWeek()->toDateString(),
                    'end' => $today->copy()->endOfWeek()->toDateString()
                ];
                break;
            case 'last_week':
                $debugInfo['expected_range'] = [
                    'start' => $today->copy()->subWeek()->startOfWeek()->toDateString(),
                    'end' => $today->copy()->subWeek()->endOfWeek()->toDateString()
                ];
                break;
            case 'this_month':
                $debugInfo['expected_range'] = [
                    'start' => $today->copy()->startOfMonth()->toDateString(),
                    'end' => $today->copy()->endOfMonth()->toDateString()
                ];
                break;
            case 'last_month':
                $debugInfo['expected_range'] = [
                    'start' => $today->copy()->subMonth()->startOfMonth()->toDateString(),
                    'end' => $today->copy()->subMonth()->endOfMonth()->toDateString()
                ];
                break;
            case 'range':
                $debugInfo['expected_range'] = [
                    'start' => $dateStart,
                    'end' => $dateEnd
                ];
                break;
        }

        \Log::debug('Date filter debug info', $debugInfo);
        return $debugInfo;
    }

    /**
     * Log detailed changes for audit trail
     *
     * @param string $action Action being performed
     * @param array $before Previous state data
     * @param array $after Current state data
     * 
     * @return array Structured log data
     */
    public static function logDetailedChanges($action, $before = null, $after = null)
    {
        $logData = [
            'action' => $action,
            'timestamp' => now()->toIso8601String(),
        ];

        if ($before) {
            $logData['before'] = $before;
        }

        if ($after) {
            $logData['after'] = $after;
        }

        return json_encode($logData);
    }

    /**
     * Format ReturPenjualan data for detailed logging
     * 
     * @param \App\Models\ReturPenjualan $returPenjualan
     * @return array
     */
    public static function formatReturData($returPenjualan)
    {
        $data = [
            'id' => $returPenjualan->id,
            'nomor' => $returPenjualan->nomor,
            'tanggal' => $returPenjualan->tanggal,
            'sales_order_id' => $returPenjualan->sales_order_id,
            'customer_id' => $returPenjualan->customer_id,
            'user_id' => $returPenjualan->user_id,
            'total' => $returPenjualan->total,
            'status' => $returPenjualan->status,
            'created_at' => $returPenjualan->created_at,
            'updated_at' => $returPenjualan->updated_at,
        ];

        return $data;
    }

    /**
     * Get status change text for logging
     * 
     * @param string $oldStatus
     * @param string $newStatus
     * @return string
     */
    public static function getStatusChangeText($oldStatus, $newStatus)
    {
        $statusMap = [
            'draft' => 'Draft',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak'
        ];

        $oldStatusText = $statusMap[$oldStatus] ?? $oldStatus;
        $newStatusText = $statusMap[$newStatus] ?? $newStatus;

        return "Status berubah dari {$oldStatusText} menjadi {$newStatusText}";
    }

    /**
     * Format monetary values for display
     * 
     * @param float $amount
     * @return string
     */
    public static function formatMoney($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Debug quantity values in sales order details
     * 
     * @param array $details The sales order details data
     * @return array Normalized quantity data
     */
    public static function debugQuantityValues($details)
    {
        $result = [];

        foreach ($details as $key => $detail) {
            // Extract all quantity values
            $qtyTerkirim = isset($detail['qty_terkirim']) ? (float) $detail['qty_terkirim'] : 0;
            $quantityTerkirim = isset($detail['quantity_terkirim']) ? (float) $detail['quantity_terkirim'] : 0;
            $qtySo = isset($detail['qty_so']) ? (float) $detail['qty_so'] : 0;
            $qty = isset($detail['qty']) ? (float) $detail['qty'] : 0;

            // Determine which value should be used as the delivered quantity
            $usedValue = $qtyTerkirim > 0 ? 'qty_terkirim' : ($quantityTerkirim > 0 ? 'quantity_terkirim' : ($qty > 0 ? 'qty' : 'qty_so'));

            // Create a normalized record
            $result[$key] = [
                'produk_id' => $detail['produk_id'] ?? null,
                'produk_nama' => $detail['produk_nama'] ?? null,
                'qty_terkirim' => $qtyTerkirim,
                'quantity_terkirim' => $quantityTerkirim,
                'qty_so' => $qtySo,
                'qty' => $qty,
                'used_value' => $usedValue,
                'effective_quantity' => max($qtyTerkirim, $quantityTerkirim, $qty, $qtySo)
            ];
        }

        return $result;
    }

    /**
     * Analyze return reasons to generate a summary
     * 
     * @param \Illuminate\Database\Eloquent\Collection $returDetails
     * @return array
     */
    public static function analyzeReturnReasons($returDetails)
    {
        $reasonCounts = [];
        $reasonValues = [];

        foreach ($returDetails as $detail) {
            $reason = $detail->alasan;

            if (!isset($reasonCounts[$reason])) {
                $reasonCounts[$reason] = 0;
                $reasonValues[$reason] = 0;
            }

            $reasonCounts[$reason]++;

            // Get the sales order detail to calculate the value
            $salesOrderDetail = \App\Models\SalesOrderDetail::where('sales_order_id', $detail->returPenjualan->sales_order_id)
                ->where('produk_id', $detail->produk_id)
                ->first();

            $hargaSatuan = $salesOrderDetail ? $salesOrderDetail->harga : 0;
            $subtotal = $hargaSatuan * $detail->quantity;

            $reasonValues[$reason] += $subtotal;
        }

        $result = [];
        foreach ($reasonCounts as $reason => $count) {
            $result[] = [
                'reason' => $reason,
                'count' => $count,
                'value' => $reasonValues[$reason],
                'value_formatted' => self::formatMoney($reasonValues[$reason]),
                'percentage' => array_sum($reasonCounts) > 0 ? round(($count / array_sum($reasonCounts)) * 100, 2) : 0
            ];
        }

        return $result;
    }

    /**
     * Generate a detailed summary of a retur penjualan
     * 
     * @param \App\Models\ReturPenjualan $returPenjualan
     * @return array
     */
    public static function generateReturSummary($returPenjualan)
    {
        // Make sure the relations are loaded
        if (!$returPenjualan->relationLoaded('details')) {
            $returPenjualan->load('details.produk', 'details.satuan');
        }

        if (!$returPenjualan->relationLoaded('customer')) {
            $returPenjualan->load('customer');
        }

        if (!$returPenjualan->relationLoaded('salesOrder')) {
            $returPenjualan->load('salesOrder');
        }

        $reasonAnalysis = self::analyzeReturnReasons($returPenjualan->details);

        return [
            'basic_info' => [
                'nomor' => $returPenjualan->nomor,
                'tanggal' => $returPenjualan->tanggal,
                'status' => $returPenjualan->status,
                'total' => $returPenjualan->total,
                'total_formatted' => self::formatMoney($returPenjualan->total ?? 0),
                'item_count' => $returPenjualan->details->count(),
            ],
            'customer' => [
                'id' => $returPenjualan->customer_id,
                'nama' => $returPenjualan->customer->nama ?? 'Unknown',
                'company' => $returPenjualan->customer->company ?? null,
            ],
            'sales_order' => [
                'id' => $returPenjualan->sales_order_id,
                'nomor' => $returPenjualan->salesOrder->nomor ?? 'Unknown',
            ],
            'reason_analysis' => $reasonAnalysis,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
