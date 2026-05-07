<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\KomponenGaji;
use App\Models\Penggajian;

$orphans = KomponenGaji::whereNotNull('sales_order_id')
    ->get()
    ->filter(function($k) {
        return !Penggajian::where('id', $k->penggajian_id)->exists();
    });

echo "Orphan KomponenGaji: " . $orphans->count() . "\n";
foreach ($orphans as $o) {
    echo "- ID: {$o->id}, Penggajian ID: {$o->penggajian_id}, SO ID: {$o->sales_order_id}\n";
}
