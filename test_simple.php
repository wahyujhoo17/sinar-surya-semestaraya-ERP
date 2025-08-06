<?php

// Simple test untuk debugging Excel export
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing database connection and query...\n";

try {
    // Test simple query
    $count = PurchaseOrder::count();
    echo "Total Purchase Orders: $count\n";

    // Test the problematic query
    $testQuery = PurchaseOrder::select(
        'purchase_order.id',
        'purchase_order.nomor',
        'purchase_order.total',
        DB::raw('COALESCE(
            (SELECT SUM(CAST(jumlah AS DECIMAL(15,2))) FROM pembayaran_hutang WHERE purchase_order_id = purchase_order.id), 
            0
        ) as total_bayar')
    )->limit(3)->get();

    echo "\nTest query results:\n";
    foreach ($testQuery as $item) {
        echo "PO: {$item->nomor}\n";
        echo "  Total: {$item->total} (type: " . gettype($item->total) . ")\n";
        echo "  Total Bayar: {$item->total_bayar} (type: " . gettype($item->total_bayar) . ")\n";
        echo "  Float conversion: " . (float)$item->total . " / " . (float)$item->total_bayar . "\n";
        echo "  String representation: '" . $item->total . "' / '" . $item->total_bayar . "'\n\n";
    }

    echo "Query test completed successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
