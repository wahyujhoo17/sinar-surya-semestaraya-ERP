<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\KomponenGaji;

$komponents = KomponenGaji::where('penggajian_id', 23)->get();
echo "Komponen Gaji untuk Sudrajat (ID 23):\n";
foreach ($komponents as $k) {
    echo "- {$k->nama_komponen} | Jenis: {$k->jenis} | Nilai: {$k->nilai}\n";
}

$komponents40 = KomponenGaji::where('penggajian_id', 40)->get();
echo "\nKomponen Gaji untuk Ari Alanshari (ID 40):\n";
foreach ($komponents40 as $k) {
    echo "- {$k->nama_komponen} | Jenis: {$k->jenis} | Nilai: {$k->nilai}\n";
}
