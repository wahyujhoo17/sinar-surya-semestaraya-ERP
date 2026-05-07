<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Penggajian;
use App\Models\SalesOrder;

$payrollCount = Penggajian::count();
$lunasSOCount = SalesOrder::where('status_pembayaran', 'lunas')->count();

echo "Total Penggajian: $payrollCount\n";
echo "Total Sales Order Lunas: $lunasSOCount\n";

if ($lunasSOCount > 0) {
    echo "\nBeberapa Sales Order Lunas:\n";
    $sos = SalesOrder::where('status_pembayaran', 'lunas')
        ->with('sales')
        ->limit(5)
        ->get();
    foreach ($sos as $so) {
        echo "- {$so->nomor} | Sales: " . ($so->sales ? $so->sales->nama_lengkap : 'N/A') . " | Tanggal: {$so->tanggal}\n";
    }
}
