<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\PeriodeAkuntansi;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Original auto-create command (manual trigger)
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

// Enhanced auto-create command for multiple upcoming periods
Artisan::command('periods:auto-create-upcoming', function () {
    $this->info('Creating upcoming periods...');

    $periods = PeriodeAkuntansi::autoCreateUpcomingPeriods(3);

    if (count($periods) > 0) {
        $this->info("Created " . count($periods) . " new periods:");
        foreach ($periods as $period) {
            $this->line("- {$period->nama}");
        }
    } else {
        $this->info('All upcoming periods already exist.');
    }
})->purpose('Automatically create upcoming accounting periods');
