<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\KomponenGaji;
use App\Models\Penggajian;

$commissions = KomponenGaji::where('nama_komponen', 'Komisi Penjualan')
    ->with('penggajian.karyawan')
    ->get()
    ->groupBy(function($c) {
        return $c->penggajian->karyawan->nama_lengkap . '|' . $c->penggajian->bulan . '|' . $c->penggajian->tahun;
    })
    ->map(function($group) {
        $first = $group->first();
        return [
            'karyawan' => $first->penggajian->karyawan->nama_lengkap,
            'bulan' => $first->penggajian->bulan,
            'tahun' => $first->penggajian->tahun,
            'total_komisi' => $group->sum('nilai'),
            'jumlah_so' => $group->count()
        ];
    })
    ->values()
    ->toArray();

echo json_encode($commissions, JSON_PRETTY_PRINT);
