<?php

use App\Models\SalesOrder;
use App\Models\Invoice;
use App\Models\PembayaranPiutang;
use App\Models\UangMukaAplikasi;
use App\Http\Controllers\Penjualan\InvoiceController;

// Get recent sales orders
$salesOrders = SalesOrder::with('customer')->orderBy('id', 'desc')->take(10)->get();

echo "=== RECENT SALES ORDERS ===\n";
foreach ($salesOrders as $so) {
    echo "ID: {$so->id}, Nomor: {$so->nomor}, Customer: " . ($so->customer->nama ?? 'N/A') .
        ", Total: " . number_format($so->total, 2) .
        ", Status Invoice: " . ($so->status_invoice ?? 'N/A') .
        ", Status Pembayaran: " . ($so->status_pembayaran ?? 'N/A') . "\n";
}

// Ask user for sales order ID
echo "\nEnter Sales Order ID to debug: ";
$salesOrderId = trim(fgets(STDIN));

$salesOrder = SalesOrder::find($salesOrderId);
if (!$salesOrder) {
    echo "Sales Order not found!\n";
    exit;
}

echo "\n=== SALES ORDER DETAILS ===\n";
echo "ID: {$salesOrder->id}\n";
echo "Nomor: {$salesOrder->nomor}\n";
echo "Customer: " . ($salesOrder->customer->nama ?? 'N/A') . "\n";
echo "Total: " . number_format($salesOrder->total, 2) . "\n";
echo "Current Status Invoice: " . ($salesOrder->status_invoice ?? 'N/A') . "\n";
echo "Current Status Pembayaran: " . ($salesOrder->status_pembayaran ?? 'N/A') . "\n";

// Get invoices
$invoices = Invoice::where('sales_order_id', $salesOrderId)->get();
echo "\n=== INVOICES ===\n";
if ($invoices->count() > 0) {
    foreach ($invoices as $inv) {
        echo "ID: {$inv->id}, Nomor: {$inv->nomor}, Total: " . number_format($inv->total, 2) .
            ", Kredit Terapkan: " . number_format($inv->kredit_terapkan ?? 0, 2) .
            ", Sisa Piutang: " . number_format($inv->sisa_piutang ?? 0, 2) . "\n";
    }
} else {
    echo "No invoices found\n";
}

// Get payments
$payments = PembayaranPiutang::whereHas('invoice', function ($query) use ($salesOrderId) {
    $query->where('sales_order_id', $salesOrderId);
})->with('invoice')->get();

echo "\n=== PAYMENTS ===\n";
if ($payments->count() > 0) {
    foreach ($payments as $pay) {
        echo "ID: {$pay->id}, Nomor: {$pay->nomor}, Invoice: " . ($pay->invoice->nomor ?? 'N/A') .
            ", Jumlah: " . number_format($pay->jumlah, 2) .
            ", Tanggal: {$pay->tanggal_pembayaran}\n";
    }
} else {
    echo "No payments found\n";
}

// Get uang muka applications
$uangMukaAplikasi = UangMukaAplikasi::whereHas('invoice', function ($query) use ($salesOrderId) {
    $query->where('sales_order_id', $salesOrderId);
})->with('invoice')->get();

echo "\n=== UANG MUKA APPLICATIONS ===\n";
if ($uangMukaAplikasi->count() > 0) {
    foreach ($uangMukaAplikasi as $uma) {
        echo "ID: {$uma->id}, Invoice: " . ($uma->invoice->nomor ?? 'N/A') .
            ", Jumlah Aplikasi: " . number_format((float)$uma->jumlah_aplikasi, 2) . "\n";
    }
} else {
    echo "No uang muka applications found\n";
}

// Manual calculation
$totalInvoiced = $invoices->sum('total');
$totalPembayaran = $payments->sum('jumlah');
$totalUangMuka = $uangMukaAplikasi->sum('jumlah_aplikasi');
$totalKredit = $invoices->sum('kredit_terapkan') ?? 0;
$totalTerbayar = $totalPembayaran + $totalUangMuka + $totalKredit;

echo "\n=== MANUAL CALCULATION ===\n";
echo "Total Sales Order: " . number_format($salesOrder->total, 2) . "\n";
echo "Total Invoiced: " . number_format($totalInvoiced, 2) . "\n";
echo "Total Pembayaran: " . number_format($totalPembayaran, 2) . "\n";
echo "Total Uang Muka: " . number_format($totalUangMuka, 2) . "\n";
echo "Total Kredit: " . number_format($totalKredit, 2) . "\n";
echo "Total Terbayar: " . number_format($totalTerbayar, 2) . "\n";

// Status calculation
$totalSalesOrderRounded = round($salesOrder->total, 2);
$totalInvoicedRounded = round($totalInvoiced, 2);
$totalTerbayarRounded = round($totalTerbayar, 2);

$isFullyInvoiced = ($totalInvoicedRounded >= $totalSalesOrderRounded - 0.001);
$isFullyPaid = ($totalTerbayarRounded >= $totalInvoicedRounded - 0.001);

echo "\n=== STATUS CALCULATION ===\n";
echo "Total Sales Order Rounded: " . $totalSalesOrderRounded . "\n";
echo "Total Invoiced Rounded: " . $totalInvoicedRounded . "\n";
echo "Total Terbayar Rounded: " . $totalTerbayarRounded . "\n";
echo "Is Fully Invoiced: " . ($isFullyInvoiced ? 'YES' : 'NO') . "\n";
echo "Is Fully Paid: " . ($isFullyPaid ? 'YES' : 'NO') . "\n";

// Expected status
if ($isFullyInvoiced && $isFullyPaid) {
    $expectedStatus = 'lunas';
} elseif ($totalTerbayarRounded > 0) {
    $expectedStatus = 'sebagian';
} else {
    $expectedStatus = 'belum_bayar';
}

echo "Expected Status Pembayaran: " . $expectedStatus . "\n";
echo "Current Status Pembayaran: " . ($salesOrder->status_pembayaran ?? 'N/A') . "\n";

// Check if status matches
if ($expectedStatus === $salesOrder->status_pembayaran) {
    echo "✅ Status is CORRECT\n";
} else {
    echo "❌ Status is INCORRECT\n";
}

echo "\nDo you want to update the status now? (y/n): ";
$answer = trim(fgets(STDIN));
if (strtolower($answer) === 'y') {
    echo "Updating status...\n";
    InvoiceController::updateSalesOrderStatusFromPayment($salesOrderId);

    // Refresh and show new status
    $salesOrder->refresh();
    echo "New Status Pembayaran: " . $salesOrder->status_pembayaran . "\n";
    echo "New Status Invoice: " . $salesOrder->status_invoice . "\n";
}

echo "\n=== CHECK LOGS ===\n";
echo "Check storage/logs/laravel.log for detailed calculation logs\n";
