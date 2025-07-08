<?php

require_once 'vendor/autoload.php';

// Boot Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Testing Quotation model salesOrders relation...\n";

    // Test Quotation model
    $quotation = new App\Models\Quotation();
    $relation = $quotation->salesOrders();
    echo "✓ Quotation::salesOrders() relation exists\n";

    // Test conversion rate query
    $totalQuotation = App\Models\Quotation::count();
    $convertedQuotation = App\Models\Quotation::whereHas('salesOrders')->count();

    echo "Total Quotations: $totalQuotation\n";
    echo "Converted Quotations: $convertedQuotation\n";
    echo "✓ whereHas('salesOrders') query works\n";

    echo "\nTesting Invoice model salesOrder relation...\n";
    $invoice = new App\Models\Invoice();
    $relation = $invoice->salesOrder();
    echo "✓ Invoice::salesOrder() relation exists\n";

    echo "\n✅ All tests passed! Dashboard should work now.\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
