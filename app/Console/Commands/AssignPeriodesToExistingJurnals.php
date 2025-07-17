<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JurnalUmum;
use App\Models\PeriodeAkuntansi;

class AssignPeriodesToExistingJurnals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'periods:assign-existing {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign periods to existing jurnal entries based on their dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info('Assigning periods to existing jurnal entries...');

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        // Get all jurnal entries without periode_id
        $jurnals = JurnalUmum::whereNull('periode_id')->get();

        $this->info("Found {$jurnals->count()} jurnal entries without periode_id");

        if ($jurnals->isEmpty()) {
            $this->info('No jurnal entries need period assignment.');
            return 0;
        }

        $updated = 0;
        $failed = 0;

        foreach ($jurnals as $jurnal) {
            $periode = PeriodeAkuntansi::getPeriodeForDate($jurnal->tanggal);

            if ($periode) {
                if (!$dryRun) {
                    $jurnal->update(['periode_id' => $periode->id]);
                }
                $updated++;
                $this->line("✓ {$jurnal->no_referensi} ({$jurnal->tanggal}) → {$periode->nama}");
            } else {
                $failed++;
                $this->error("✗ {$jurnal->no_referensi} ({$jurnal->tanggal}) → No period found");
            }
        }

        $this->info("\nSummary:");
        $this->info("Updated: {$updated}");
        $this->info("Failed: {$failed}");

        if ($failed > 0) {
            $this->warn("\nSome entries failed because no matching period exists.");
            $this->info("Consider creating periods for those dates first using:");
            $this->info("php artisan periods:create-monthly <year>");
        }

        return 0;
    }
}
