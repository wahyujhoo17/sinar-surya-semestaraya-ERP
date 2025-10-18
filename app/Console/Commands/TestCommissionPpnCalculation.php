<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\PurchaseOrder;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class TestCommissionPpnCalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:commission-ppn {--limit=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test commission calculation with new PPN formula';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== TESTING COMMISSION PPN CALCULATION ===');
        $this->info('Date: ' . now()->format('Y-m-d H:i:s'));
        $this->newLine();

        $limit = $this->option('limit');

        // Ambil sales order yang sudah lunas
        $salesOrders = SalesOrder::with(['customer', 'details.produk'])
            ->where('status_pembayaran', 'lunas')
            ->whereHas('invoices.pembayaranPiutang')
            ->orderBy('tanggal', 'desc')
            ->limit($limit)
            ->get();

        if ($salesOrders->isEmpty()) {
            $this->warn('No completed sales orders found!');
            return;
        }

        $this->info("Found {$salesOrders->count()} completed sales orders");
        $this->newLine();

        $testResults = [];

        foreach ($salesOrders as $order) {
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->info("Sales Order: {$order->nomor}");
            $this->info("Customer: " . ($order->customer->company ?? $order->customer->nama ?? 'N/A'));
            $this->info("Date: {$order->tanggal->format('Y-m-d')}");

            $salesPpn = $order->ppn ?? 0;
            $hasSalesPpn = $salesPpn > 0;
            $this->info("Sales PPN: " . ($hasSalesPpn ? "{$salesPpn}%" : "No PPN"));
            $this->newLine();

            $details = SalesOrderDetail::where('sales_order_id', $order->id)->get();

            $totalNettoPenjualan = 0;
            $totalNettoBeli = 0;
            $itemResults = [];

            foreach ($details as $detail) {
                $produk = Produk::find($detail->produk_id);
                if (!$produk) continue;

                $nettoJualItem = $detail->harga * $detail->quantity;
                $nettoBeliItem = $produk->harga_beli * $detail->quantity;

                // Check purchase PPN
                $lastPurchaseOrder = PurchaseOrder::join('purchase_order_detail', 'purchase_order.id', '=', 'purchase_order_detail.po_id')
                    ->where('purchase_order_detail.produk_id', $produk->id)
                    ->where('purchase_order.status', 'selesai')
                    ->orderBy('purchase_order.tanggal', 'desc')
                    ->select('purchase_order.ppn', 'purchase_order.nomor', 'purchase_order.tanggal')
                    ->first();

                $purchasePpn = 0;
                $hasPurchasePpn = false;
                $purchaseOrderInfo = 'No Purchase History';

                if ($lastPurchaseOrder) {
                    $purchasePpn = $lastPurchaseOrder->ppn ?? 0;
                    $hasPurchasePpn = $purchasePpn > 0;
                    $purchaseOrderInfo = "{$lastPurchaseOrder->nomor} ({$lastPurchaseOrder->tanggal})";
                }

                // Determine which rule applies
                $rule = 'No PPN';
                $nettoJualAdjusted = $nettoJualItem;
                $nettoBeliAdjusted = $nettoBeliItem;

                if ($hasSalesPpn && !$hasPurchasePpn) {
                    $rule = 'Rule 1: Sales PPN + Purchase Non-PPN';
                    $nettoJualAdjusted = $nettoJualItem / (1 + $salesPpn / 100);
                } elseif ($hasSalesPpn && $hasPurchasePpn) {
                    $rule = 'Rule 2: Sales PPN + Purchase PPN';
                    // No adjustment needed
                } elseif (!$hasSalesPpn && $hasPurchasePpn) {
                    $rule = 'Rule 3: Sales Non-PPN + Purchase PPN';
                    // No adjustment needed
                }

                $totalNettoPenjualan += $nettoJualAdjusted;
                $totalNettoBeli += $nettoBeliAdjusted;

                $itemResults[] = [
                    'product' => $produk->nama,
                    'qty' => $detail->quantity,
                    'harga_jual' => $detail->harga,
                    'harga_beli' => $produk->harga_beli,
                    'netto_jual_original' => $nettoJualItem,
                    'netto_jual_adjusted' => $nettoJualAdjusted,
                    'netto_beli' => $nettoBeliItem,
                    'purchase_ppn' => $hasPurchasePpn ? "{$purchasePpn}%" : "No PPN",
                    'purchase_order' => $purchaseOrderInfo,
                    'rule' => $rule
                ];

                // Display item details
                $this->line("  Product: {$produk->nama}");
                $this->line("  Qty: {$detail->quantity} × Rp " . number_format($detail->harga, 0, ',', '.'));
                $this->line("  Purchase PPN: " . ($hasPurchasePpn ? "{$purchasePpn}%" : "No PPN") . " (PO: {$purchaseOrderInfo})");
                $this->line("  Rule Applied: {$rule}");
                $this->line("  Netto Jual: Rp " . number_format($nettoJualItem, 0, ',', '.') .
                    ($nettoJualItem != $nettoJualAdjusted ? " → Rp " . number_format($nettoJualAdjusted, 0, ',', '.') : ""));
                $this->line("  Netto Beli: Rp " . number_format($nettoBeliItem, 0, ',', '.'));
                $this->newLine();
            }

            // Calculate margin and commission
            if ($totalNettoBeli > 0 && $totalNettoPenjualan > 0) {
                $marginPersen = (($totalNettoPenjualan - $totalNettoBeli) / $totalNettoBeli) * 100;
                $komisiRate = $this->getKomisiRateByMargin($marginPersen);
                $orderKomisi = $totalNettoPenjualan * ($komisiRate / 100);

                $this->info("SUMMARY:");
                $this->line("  Total Netto Penjualan: Rp " . number_format($totalNettoPenjualan, 0, ',', '.'));
                $this->line("  Total Netto Beli: Rp " . number_format($totalNettoBeli, 0, ',', '.'));
                $this->line("  Margin: " . number_format($marginPersen, 2) . "%");
                $this->line("  Commission Rate: {$komisiRate}%");
                $this->line("  Commission Amount: Rp " . number_format($orderKomisi, 0, ',', '.'));

                $testResults[] = [
                    'order_nomor' => $order->nomor,
                    'customer' => $order->customer->company ?? $order->customer->nama ?? 'N/A',
                    'sales_ppn' => $hasSalesPpn ? "{$salesPpn}%" : "No PPN",
                    'netto_jual' => $totalNettoPenjualan,
                    'netto_beli' => $totalNettoBeli,
                    'margin' => $marginPersen,
                    'komisi_rate' => $komisiRate,
                    'komisi' => $orderKomisi,
                    'items' => $itemResults
                ];
            } else {
                $this->warn("  Skipped: Invalid netto values");
            }

            $this->newLine(2);
        }

        // Summary Report
        $this->info('=== SUMMARY REPORT ===');
        $this->newLine();

        $table = [];
        foreach ($testResults as $result) {
            $table[] = [
                $result['order_nomor'],
                $result['sales_ppn'],
                'Rp ' . number_format($result['netto_jual'], 0, ',', '.'),
                'Rp ' . number_format($result['netto_beli'], 0, ',', '.'),
                number_format($result['margin'], 2) . '%',
                $result['komisi_rate'] . '%',
                'Rp ' . number_format($result['komisi'], 0, ',', '.')
            ];
        }

        $this->table(
            ['SO Number', 'Sales PPN', 'Netto Jual', 'Netto Beli', 'Margin', 'Rate', 'Commission'],
            $table
        );

        $this->newLine();
        $this->info("Total tested: " . count($testResults) . " sales orders");

        // Calculate totals
        $totalCommission = array_sum(array_column($testResults, 'komisi'));
        $this->info("Total Commission: Rp " . number_format($totalCommission, 0, ',', '.'));

        return 0;
    }

    /**
     * Get commission rate based on margin percentage
     */
    private function getKomisiRateByMargin($marginPersen)
    {
        $komisiTiers = [
            ['min' => 18.00, 'max' => 20.00, 'rate' => 1.00],
            ['min' => 20.50, 'max' => 25.00, 'rate' => 1.25],
            ['min' => 25.50, 'max' => 30.00, 'rate' => 1.50],
            ['min' => 30.50, 'max' => 35.00, 'rate' => 1.75],
            ['min' => 35.50, 'max' => 40.00, 'rate' => 2.00],
            ['min' => 40.50, 'max' => 45.00, 'rate' => 2.25],
            ['min' => 45.50, 'max' => 50.00, 'rate' => 2.50],
            ['min' => 50.50, 'max' => 55.00, 'rate' => 2.75],
            ['min' => 55.50, 'max' => 60.00, 'rate' => 3.00],
            ['min' => 60.50, 'max' => 65.00, 'rate' => 3.25],
            ['min' => 65.50, 'max' => 70.00, 'rate' => 3.50],
            ['min' => 70.50, 'max' => 75.00, 'rate' => 3.75],
            ['min' => 75.50, 'max' => 80.00, 'rate' => 4.00],
            ['min' => 80.50, 'max' => 85.00, 'rate' => 4.25],
            ['min' => 85.50, 'max' => 90.00, 'rate' => 4.50],
            ['min' => 90.50, 'max' => 95.00, 'rate' => 4.75],
            ['min' => 95.50, 'max' => 100.00, 'rate' => 5.00],
            ['min' => 101.00, 'max' => 110.00, 'rate' => 5.25],
            ['min' => 111.00, 'max' => 125.00, 'rate' => 5.50],
            ['min' => 126.00, 'max' => 140.00, 'rate' => 5.75],
            ['min' => 141.00, 'max' => 160.00, 'rate' => 6.00],
            ['min' => 161.00, 'max' => 180.00, 'rate' => 6.25],
            ['min' => 181.00, 'max' => 200.00, 'rate' => 6.50],
            ['min' => 201.00, 'max' => 225.00, 'rate' => 7.00],
            ['min' => 226.00, 'max' => 250.00, 'rate' => 7.25],
            ['min' => 251.00, 'max' => 275.00, 'rate' => 7.50],
            ['min' => 276.00, 'max' => 300.00, 'rate' => 8.00],
            ['min' => 301.00, 'max' => 325.00, 'rate' => 8.25],
            ['min' => 326.00, 'max' => 350.00, 'rate' => 8.50],
            ['min' => 351.00, 'max' => 400.00, 'rate' => 9.00],
            ['min' => 401.00, 'max' => 450.00, 'rate' => 9.50],
            ['min' => 451.00, 'max' => 500.00, 'rate' => 10.00],
            ['min' => 501.00, 'max' => 600.00, 'rate' => 10.50],
            ['min' => 601.00, 'max' => 700.00, 'rate' => 11.00],
            ['min' => 701.00, 'max' => 800.00, 'rate' => 11.50],
            ['min' => 801.00, 'max' => 900.00, 'rate' => 12.00],
            ['min' => 901.00, 'max' => 1000.00, 'rate' => 12.50],
            ['min' => 1001.00, 'max' => 1100.00, 'rate' => 13.00],
            ['min' => 1101.00, 'max' => 1200.00, 'rate' => 13.50],
            ['min' => 1201.00, 'max' => 1300.00, 'rate' => 14.00],
            ['min' => 1301.00, 'max' => 1400.00, 'rate' => 14.50],
            ['min' => 1401.00, 'max' => 1500.00, 'rate' => 15.00],
            ['min' => 1501.00, 'max' => 1600.00, 'rate' => 15.50],
            ['min' => 1601.00, 'max' => 1700.00, 'rate' => 16.00],
            ['min' => 1701.00, 'max' => 1800.00, 'rate' => 16.50],
            ['min' => 1801.00, 'max' => 1900.00, 'rate' => 17.00],
            ['min' => 1901.00, 'max' => 2000.00, 'rate' => 17.50],
            ['min' => 2001.00, 'max' => 2100.00, 'rate' => 18.00],
            ['min' => 2101.00, 'max' => 2200.00, 'rate' => 18.50],
            ['min' => 2201.00, 'max' => 2300.00, 'rate' => 19.00],
            ['min' => 2301.00, 'max' => 2400.00, 'rate' => 19.50],
            ['min' => 2401.00, 'max' => 2501.00, 'rate' => 20.00],
            ['min' => 2501.00, 'max' => 2600.00, 'rate' => 20.50],
            ['min' => 2601.00, 'max' => 2700.00, 'rate' => 21.00],
            ['min' => 2701.00, 'max' => 2800.00, 'rate' => 21.50],
            ['min' => 2801.00, 'max' => 2900.00, 'rate' => 22.00],
            ['min' => 2901.00, 'max' => 3000.00, 'rate' => 22.50],
            ['min' => 3001.00, 'max' => 3100.00, 'rate' => 23.00],
            ['min' => 3101.00, 'max' => 3200.00, 'rate' => 23.50],
            ['min' => 3201.00, 'max' => 3300.00, 'rate' => 24.00],
            ['min' => 3301.00, 'max' => 3400.00, 'rate' => 24.50],
            ['min' => 3401.00, 'max' => 3500.00, 'rate' => 25.00],
            ['min' => 3501.00, 'max' => 3600.00, 'rate' => 25.50],
            ['min' => 3601.00, 'max' => 3700.00, 'rate' => 26.00],
            ['min' => 3701.00, 'max' => 3800.00, 'rate' => 26.50],
            ['min' => 3801.00, 'max' => 3900.00, 'rate' => 27.00],
            ['min' => 3901.00, 'max' => 4000.00, 'rate' => 27.50],
            ['min' => 4001.00, 'max' => 4100.00, 'rate' => 28.00],
            ['min' => 4101.00, 'max' => 4200.00, 'rate' => 28.50],
            ['min' => 4201.00, 'max' => 4300.00, 'rate' => 29.00],
            ['min' => 4301.00, 'max' => 4400.00, 'rate' => 29.50],
            ['min' => 4401.00, 'max' => 4500.00, 'rate' => 30.00],
        ];

        foreach ($komisiTiers as $tier) {
            if ($marginPersen >= $tier['min'] && $marginPersen <= $tier['max']) {
                return $tier['rate'];
            }
        }

        // Jika margin di atas tier tertinggi (4500%), gunakan rate maksimum (30%)
        if ($marginPersen > 4500.00) {
            return 30.00;
        }

        return 0;
    }
}
