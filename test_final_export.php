<?php

// Include Laravel bootstrap
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Set up database connection
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection(require __DIR__ . '/config/database.php');
$capsule->setAsGlobal();
$capsule->bootEloquent();

use App\Exports\LaporanPembelianExport;
use Maatwebsite\Excel\Facades\Excel;

echo "=== Testing Final Excel Export ===\n";

try {
    // Test parameters
    $filters = [
        'supplier' => null,
        'tgl_mulai' => '2024-01-01',
        'tgl_selesai' => date('Y-m-d')
    ];

    echo "Creating export with parameters:\n";
    echo "- Supplier: " . ($filters['supplier'] ?: 'All') . "\n";
    echo "- Date Range: {$filters['tgl_mulai']} to {$filters['tgl_selesai']}\n\n";

    // Create export instance
    $export = new LaporanPembelianExport($filters);

    // Get the data first
    echo "Getting export data...\n";
    $view = $export->view();
    $data = $view->getData()['dataPembelian'] ?? [];

    echo "Found " . count($data) . " records\n";

    if (count($data) > 0) {
        echo "\n=== Sample Data Check ===\n";
        $sample = $data->first();
        echo "First record:\n";
        echo "- Nomor Faktur: " . $sample->nomor_faktur . "\n";
        echo "- Total (raw): " . var_export($sample->total, true) . " (type: " . gettype($sample->total) . ")\n";
        echo "- Total Bayar (raw): " . var_export($sample->total_bayar, true) . " (type: " . gettype($sample->total_bayar) . ")\n";
        echo "- Total (float): " . (float) $sample->total . "\n";
        echo "- Total Bayar (float): " . (float) $sample->total_bayar . "\n";
        echo "- Sisa: " . ((float) $sample->total - (float) $sample->total_bayar) . "\n";

        // Format check
        echo "\n=== Format Check ===\n";
        $total = (float) $sample->total;
        $totalBayar = (float) $sample->total_bayar;
        echo "Total formatted: " . number_format($total, 0, ',', '.') . "\n";
        echo "Total Bayar formatted: " . number_format($totalBayar, 0, ',', '.') . "\n";
    }

    // Try to generate actual Excel file
    echo "\n=== Generating Excel File ===\n";
    $filename = 'test_laporan_pembelian_' . date('YmdHis') . '.xlsx';
    $filepath = public_path($filename);

    Excel::store($export, $filename, 'public');

    if (file_exists($filepath)) {
        $filesize = filesize($filepath);
        echo "✅ Excel file created successfully!\n";
        echo "- File: $filename\n";
        echo "- Size: " . number_format($filesize) . " bytes\n";
        echo "- Path: $filepath\n";

        // Clean up
        unlink($filepath);
        echo "- Test file cleaned up\n";
    } else {
        echo "❌ Excel file was not created\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";
