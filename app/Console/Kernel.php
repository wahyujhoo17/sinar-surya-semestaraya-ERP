<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Jalankan setiap hari tanggal 1 pukul 00:01 untuk membuat periode bulan berikutnya
        $schedule->command('periods:auto-create-monthly')
            ->monthlyOn(1, '00:01')
            ->withoutOverlapping()
            ->onOneServer()
            ->runInBackground();

        // Jalankan setiap hari tanggal 25 untuk persiapan periode bulan berikutnya
        $schedule->command('periods:auto-create-monthly')
            ->monthlyOn(25, '00:01')
            ->withoutOverlapping()
            ->onOneServer()
            ->runInBackground();

        // Jalankan di awal tahun untuk memastikan semua periode tahun tersebut tersedia
        $schedule->command('periods:auto-create-yearly')
            ->yearlyOn(1, 1, '00:05')
            ->withoutOverlapping()
            ->onOneServer()
            ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
