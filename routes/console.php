<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\PeriodeAkuntansi;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automatic period creation
Artisan::command('periods:auto-create', function () {
    $this->info('Checking for new periods to create...');

    // Create next month period if it doesn't exist
    $period = PeriodeAkuntansi::createNextPeriod();

    if ($period) {
        $this->info("Created new period: {$period->nama}");
    } else {
        $this->info('No new periods needed.');
    }
})->purpose('Automatically create next accounting period');
