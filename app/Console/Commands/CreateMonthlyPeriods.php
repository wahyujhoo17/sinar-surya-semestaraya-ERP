<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeriodeAkuntansi;
use Carbon\Carbon;

class CreateMonthlyPeriods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'periods:create-monthly {year?} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create monthly accounting periods for specified year (default: current year)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->argument('year') ?: Carbon::now()->year;
        $force = $this->option('force');

        $this->info("Creating monthly periods for year {$year}...");

        try {
            $periods = PeriodeAkuntansi::createMonthlyPeriods($year);

            if (empty($periods)) {
                $this->warn("All periods for year {$year} already exist!");
                return 0;
            }

            $this->info("Successfully created " . count($periods) . " periods:");

            foreach ($periods as $period) {
                $this->line("- {$period->nama} ({$period->tanggal_mulai->format('d/m/Y')} - {$period->tanggal_akhir->format('d/m/Y')})");
            }

            return 0;
        } catch (\Exception $e) {
            $this->error("Error creating periods: " . $e->getMessage());
            return 1;
        }
    }
}
