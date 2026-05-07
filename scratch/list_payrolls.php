<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Penggajian;

$payrolls = Penggajian::with('karyawan')
    ->get()
    ->map(function($p) {
        return [
            'id' => $p->id,
            'karyawan' => $p->karyawan->nama_lengkap,
            'bulan' => $p->bulan,
            'tahun' => $p->tahun,
            'total_gaji' => $p->total_gaji,
            'komisi' => $p->komisi
        ];
    });

echo json_encode($payrolls, JSON_PRETTY_PRINT);
