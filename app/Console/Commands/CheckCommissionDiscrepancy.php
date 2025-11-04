<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\PurchaseOrder;
use App\Models\Produk;
use App\Models\KomponenGaji;
use App\Models\Penggajian;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;

class CheckCommissionDiscrepancy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:commission-discrepancy {so_number} {--employee=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check commission calculation discrepancy for a specific Sales Order';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $soNumber = $this->argument('so_number');
        $employeeName = $this->option('employee');

        $this->info('=== COMMISSION DISCREPANCY CHECKER ===');
        $this->info('Date: ' . now()->format('Y-m-d H:i:s'));
        $this->info('Sales Order: ' . $soNumber);
        $this->newLine();

        // Find Sales Order
        $salesOrder = SalesOrder::with(['customer', 'details.produk'])
            ->where('nomor', $soNumber)
            ->first();

        if (!$salesOrder) {
            $this->error("Sales Order {$soNumber} not found!");
            return 1;
        }

        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("SALES ORDER INFORMATION");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("Number: {$salesOrder->nomor}");
        $this->info("Date: {$salesOrder->tanggal->format('Y-m-d')}");
        $this->info("Customer: " . ($salesOrder->customer->company ?? $salesOrder->customer->nama ?? 'N/A'));
        $this->info("PPN: " . ($salesOrder->ppn > 0 ? "{$salesOrder->ppn}%" : "No PPN"));
        $this->info("Status: {$salesOrder->status_pembayaran}");
        $this->newLine();

        // Calculate original SO value
        $totalNilaiJual = 0;
        $details = $salesOrder->details;

        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("SALES ORDER ITEMS");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        foreach ($details as $detail) {
            $lineTotal = $detail->harga * $detail->quantity;
            $totalNilaiJual += $lineTotal;

            $this->info("Product: {$detail->produk->nama}");
            $this->info("  Quantity: {$detail->quantity}");
            $this->info("  Unit Price: Rp " . number_format($detail->harga, 0, ',', '.'));
            $this->info("  Line Total: Rp " . number_format($lineTotal, 0, ',', '.'));
            $this->newLine();
        }

        $this->info("TOTAL NILAI JUAL (Original): Rp " . number_format($totalNilaiJual, 0, ',', '.'));

        if ($salesOrder->ppn > 0) {
            $totalWithPPN = $totalNilaiJual * (1 + $salesOrder->ppn / 100);
            $this->info("TOTAL WITH PPN ({$salesOrder->ppn}%): Rp " . number_format($totalWithPPN, 0, ',', '.'));
        }
        $this->newLine();

        // Check commission calculation with PPN rules
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("COMMISSION CALCULATION (with PPN Rules)");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        $hasSalesPpn = $salesOrder->ppn > 0;
        $totalNettoPenjualanAdjusted = 0;
        $totalNettoBeli = 0;

        foreach ($details as $detail) {
            $produk = $detail->produk;
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

            // Determine PPN rule
            $rule = 'No PPN';
            $nettoJualAdjusted = $nettoJualItem;

            if ($hasSalesPpn && !$hasPurchasePpn) {
                $rule = 'Rule 1: Sales PPN + Purchase Non-PPN';
                $nettoJualAdjusted = $nettoJualItem / (1 + $salesOrder->ppn / 100);
            } elseif ($hasSalesPpn && $hasPurchasePpn) {
                $rule = 'Rule 2: Sales PPN + Purchase PPN';
            } elseif (!$hasSalesPpn && $hasPurchasePpn) {
                $rule = 'Rule 3: Sales Non-PPN + Purchase PPN';
            }

            $totalNettoPenjualanAdjusted += $nettoJualAdjusted;
            $totalNettoBeli += $nettoBeliItem;

            $this->info("Product: {$produk->nama}");
            $this->info("  Sales PPN: " . ($hasSalesPpn ? "{$salesOrder->ppn}%" : "No"));
            $this->info("  Purchase PPN: " . ($hasPurchasePpn ? "{$purchasePpn}%" : "No"));
            $this->info("  Last PO: {$purchaseOrderInfo}");
            $this->info("  Rule Applied: {$rule}");
            $this->info("  Netto Jual Original: Rp " . number_format($nettoJualItem, 0, ',', '.'));
            $this->info("  Netto Jual Adjusted: Rp " . number_format($nettoJualAdjusted, 0, ',', '.'));
            if ($nettoJualItem != $nettoJualAdjusted) {
                $difference = $nettoJualItem - $nettoJualAdjusted;
                $this->warn("  ADJUSTMENT: -Rp " . number_format($difference, 0, ',', '.'));
            }
            $this->newLine();
        }

        $this->info("TOTAL NETTO PENJUALAN (Original): Rp " . number_format($totalNilaiJual, 0, ',', '.'));
        $this->info("TOTAL NETTO PENJUALAN (Adjusted): Rp " . number_format($totalNettoPenjualanAdjusted, 0, ',', '.'));

        if ($totalNilaiJual != $totalNettoPenjualanAdjusted) {
            $totalAdjustment = $totalNilaiJual - $totalNettoPenjualanAdjusted;
            $percentageAdjustment = ($totalAdjustment / $totalNilaiJual) * 100;
            $this->warn("TOTAL ADJUSTMENT: -Rp " . number_format($totalAdjustment, 0, ',', '.') .
                " ({$percentageAdjustment}%)");
        }
        $this->newLine();

        // Check component gaji records
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("COMMISSION RECORDS IN DATABASE");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        $komponenGaji = KomponenGaji::with(['penggajian.karyawan'])
            ->where('sales_order_id', $salesOrder->id)
            ->get();

        if ($komponenGaji->isEmpty()) {
            $this->warn("No commission records found for this Sales Order");
        } else {
            foreach ($komponenGaji as $kg) {
                $penggajian = $kg->penggajian;
                $karyawan = $penggajian->karyawan;

                // Skip if employee filter is set and doesn't match
                if ($employeeName && stripos($karyawan->nama_lengkap, $employeeName) === false) {
                    continue;
                }

                $this->info("Employee: {$karyawan->nama_lengkap}");
                $this->info("Period: {$penggajian->bulan}/{$penggajian->tahun}");
                $this->info("Commission: Rp " . number_format((float)($kg->nilai ?? 0), 0, ',', '.'));
                $this->info("Netto Penjualan Original: Rp " . number_format((float)($kg->netto_penjualan_original ?? 0), 0, ',', '.'));
                $this->info("Netto Penjualan Adjusted: Rp " . number_format((float)($kg->netto_penjualan_adjusted ?? 0), 0, ',', '.'));

                if (($kg->cashback_nominal ?? 0) > 0) {
                    $this->warn("Cashback Applied: -Rp " . number_format((float)$kg->cashback_nominal, 0, ',', '.'));
                }

                if (($kg->overhead_persen ?? 0) > 0) {
                    $this->warn("Overhead Applied: +{$kg->overhead_persen}%");
                }

                $this->info("Margin: " . number_format($kg->margin_persen ?? 0, 2) . "%");
                $this->info("Commission Rate: {$kg->komisi_rate}%");
                $this->newLine();
            }
        }

        // Summary
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("SUMMARY");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        $this->table(
            ['Description', 'Value'],
            [
                ['SO Original Value', 'Rp ' . number_format($totalNilaiJual, 0, ',', '.')],
                ['Adjusted Value (for Commission)', 'Rp ' . number_format($totalNettoPenjualanAdjusted, 0, ',', '.')],
                ['Difference', 'Rp ' . number_format($totalNilaiJual - $totalNettoPenjualanAdjusted, 0, ',', '.')],
                ['Has Sales PPN', $hasSalesPpn ? 'Yes (' . $salesOrder->ppn . '%)' : 'No'],
                ['Commission Records Found', $komponenGaji->count()],
            ]
        );

        $this->newLine();

        if ($totalNilaiJual != $totalNettoPenjualanAdjusted) {
            $this->warn("⚠️  VALUE DIFFERENCE DETECTED!");
            $this->info("This is likely due to PPN adjustment rules.");
            $this->info("The adjusted value is used for fair commission calculation.");
        } else {
            $this->info("✅ No PPN adjustment needed for this Sales Order.");
        }

        return 0;
    }
}
