<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PembayaranPiutang;
use App\Models\Karyawan;

$payments = PembayaranPiutang::whereMonth('tanggal', 4)
    ->whereYear('tanggal', 2026)
    ->with('invoice.salesOrder.customer')
    ->get();

$salesMap = [];

foreach ($payments as $payment) {
    if ($payment->invoice && $payment->invoice->salesOrder && $payment->invoice->salesOrder->customer) {
        $salesId = $payment->invoice->salesOrder->customer->sales_id;
        if ($salesId) {
            $karyawan = Karyawan::where('user_id', $salesId)->first();
            $name = $karyawan ? $karyawan->nama_lengkap : "User ID $salesId";
            
            if (!isset($salesMap[$name])) {
                $salesMap[$name] = [
                    'count' => 0,
                    'so_list' => []
                ];
            }
            $salesMap[$name]['count']++;
            $salesMap[$name]['so_list'][] = $payment->invoice->salesOrder->nomor;
        }
    }
}

echo json_encode($salesMap, JSON_PRETTY_PRINT);
