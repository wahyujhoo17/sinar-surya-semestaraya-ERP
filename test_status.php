<?php

// Script untuk test sales order status
$salesOrder = App\Models\SalesOrder::where('nomor', 'SO-20250718-003')->first();

if (!$salesOrder) {
    echo "Sales Order not found\n";
    exit;
}

echo "=== TESTING SALES ORDER STATUS ===\n";
echo "Sales Order: " . $salesOrder->nomor . "\n";
echo "Current Status: " . $salesOrder->status_pembayaran . "\n";

// Reset status untuk test
$salesOrder->status_pembayaran = 'belum_bayar';
$salesOrder->save();

echo "Reset to: belum_bayar\n";

// Panggil update status
use App\Http\Controllers\Penjualan\InvoiceController;

InvoiceController::updateSalesOrderStatusFromPayment($salesOrder->id);

// Cek hasilnya
$salesOrder->refresh();
echo "After update: " . $salesOrder->status_pembayaran . "\n";

if ($salesOrder->status_pembayaran === 'sebagian') {
    echo "✅ STATUS CORRECT\n";
} else {
    echo "❌ STATUS INCORRECT\n";
}
