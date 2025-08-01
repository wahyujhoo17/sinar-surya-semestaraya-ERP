<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeriodeAkuntansi;
use Carbon\Carbon;

class UpdateExistingPeriodsYearMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'periods:update-year-month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing periods to fill year and month columns';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Updating existing periods with year and month data...');

        $periods = PeriodeAkuntansi::whereNull('tahun')
            ->orWhereNull('bulan')
            ->get();

        if ($periods->isEmpty()) {
            $this->info('✅ All periods already have year and month data');
            return 0;
        }

        $updated = 0;

        foreach ($periods as $period) {
            $startDate = Carbon::parse($period->tanggal_mulai);

            $period->update([
                'tahun' => $startDate->year,
                'bulan' => $startDate->month
            ]);

            $this->line("✓ Updated: {$period->nama} -> Year: {$startDate->year}, Month: {$startDate->month}");
            $updated++;
        }

        $this->info("✅ Successfully updated {$updated} periods");

        return 0;
    }
}
