<?php

// Include Laravel bootstrap
require_once __DIR__ . '/bootstrap/app.php';

$app = \Illuminate\Foundation\Application::getInstance();
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

echo "=== Debug Data Laporan Pembelian ===\n";

try {
    // Test query seperti di export
    $tanggalAwal = '2024-01-01';
    $tanggalAkhir = date('Y-m-d');

    echo "Testing query with date range: $tanggalAwal to $tanggalAkhir\n\n";

    $query = PurchaseOrder::query()
        ->select(
            'purchase_order.id',
            'purchase_order.nomor as nomor_faktur',
            'purchase_order.tanggal',
            'purchase_order.supplier_id',
            'purchase_order.status_pembayaran as status',
            'purchase_order.total',
            DB::raw('COALESCE(
                (SELECT SUM(CAST(jumlah AS DECIMAL(15,2))) FROM pembayaran_hutang WHERE purchase_order_id = purchase_order.id), 
                0
            ) as total_bayar'),
            'purchase_order.catatan as keterangan',
            'purchase_order.created_at',
            'purchase_order.updated_at',
            'supplier.nama as supplier_nama',
            'supplier.kode as supplier_kode',
            'users.name as nama_petugas'
        )
        ->join('supplier', 'purchase_order.supplier_id', '=', 'supplier.id')
        ->leftJoin('users', 'purchase_order.user_id', '=', 'users.id')
        ->whereBetween('purchase_order.tanggal', [$tanggalAwal, $tanggalAkhir])
        ->orderBy('purchase_order.tanggal', 'desc')
        ->limit(5);

    $dataPembelian = $query->get();

    echo "Found " . count($dataPembelian) . " records\n\n";

    if (count($dataPembelian) > 0) {
        echo "=== Sample Data ===\n";
        foreach ($dataPembelian as $index => $item) {
            echo "Record " . ($index + 1) . ":\n";
            echo "- Nomor: {$item->nomor_faktur}\n";
            echo "- Total (raw): " . var_export($item->total, true) . " (type: " . gettype($item->total) . ")\n";
            echo "- Total Bayar (raw): " . var_export($item->total_bayar, true) . " (type: " . gettype($item->total_bayar) . ")\n";

            // Transform seperti di export
            $total = (float) $item->total;
            $totalBayar = (float) $item->total_bayar;
            $sisa = $total - $totalBayar;

            echo "- Total (float): $total\n";
            echo "- Total Bayar (float): $totalBayar\n";
            echo "- Sisa: $sisa\n";
            echo "- Supplier: {$item->supplier_nama}\n";
            echo "---\n";

            if ($index >= 2) break; // Only show first 3 records
        }

        // Test collection transformation
        $transformed = $dataPembelian->map(function ($item) {
            $item->total = (float) $item->total;
            $item->total_bayar = (float) $item->total_bayar;
            return $item;
        });

        echo "\n=== After Transform ===\n";
        $sample = $transformed->first();
        echo "First record after transform:\n";
        echo "- Total: " . var_export($sample->total, true) . " (type: " . gettype($sample->total) . ")\n";
        echo "- Total Bayar: " . var_export($sample->total_bayar, true) . " (type: " . gettype($sample->total_bayar) . ")\n";

        // Test totals
        $totalPembelian = $transformed->sum('total');
        $totalDibayar = $transformed->sum('total_bayar');
        $sisaPembayaran = $totalPembelian - $totalDibayar;

        echo "\n=== Totals ===\n";
        echo "Total Pembelian: $totalPembelian\n";
        echo "Total Dibayar: $totalDibayar\n";
        echo "Sisa Pembayaran: $sisaPembayaran\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";
