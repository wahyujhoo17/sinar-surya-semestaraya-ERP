<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeriodeAkuntansi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoCreateYearlyPeriods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'periods:auto-create-yearly {--years=2 : Number of years ahead to create} {--dry-run : Show what would be created without actually creating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically create yearly accounting periods (all 12 months) if they don\'t exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $yearsAhead = (int) $this->option('years');
        $dryRun = $this->option('dry-run');

        $this->info("📅 Auto Creating Yearly Periods");
        $this->info("==========================================");

        if ($dryRun) {
            $this->warn("DRY RUN MODE - No periods will be created");
            $this->line("");
        }

        $totalCreated = 0;
        $totalSkipped = 0;
        $currentYear = Carbon::now()->year;

        // Create periods for current year and next few years
        for ($i = 0; $i <= $yearsAhead; $i++) {
            $targetYear = $currentYear + $i;

            $this->info("📆 Processing year: {$targetYear}");

            if (!$dryRun) {
                try {
                    $periods = PeriodeAkuntansi::createMonthlyPeriods($targetYear);

                    if (empty($periods)) {
                        $this->line("   ✓ All periods for {$targetYear} already exist");
                        $totalSkipped += 12; // Assume all 12 months exist
                    } else {
                        $this->info("   ✅ Created " . count($periods) . " periods for {$targetYear}:");
                        foreach ($periods as $period) {
                            $this->line("      - {$period->nama} ({$period->tanggal_mulai->format('d/m/Y')} - {$period->tanggal_akhir->format('d/m/Y')})");
                        }
                        $totalCreated += count($periods);
                        $totalSkipped += (12 - count($periods));

                        // Log the creation
                        Log::info('Auto-created yearly accounting periods', [
                            'year' => $targetYear,
                            'periods_created' => count($periods),
                            'period_names' => collect($periods)->pluck('nama')->toArray()
                        ]);
                    }
                } catch (\Exception $e) {
                    $this->error("   ❌ Failed to create periods for {$targetYear}: {$e->getMessage()}");
                    Log::error('Failed to auto-create yearly accounting periods', [
                        'year' => $targetYear,
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                // Dry run: check which periods would be created
                $existingPeriods = PeriodeAkuntansi::where('tahun', $targetYear)->count();
                $periodsToCreate = 12 - $existingPeriods;

                if ($periodsToCreate > 0) {
                    $this->info("   📝 Would create {$periodsToCreate} periods for {$targetYear}");
                    $totalCreated += $periodsToCreate;
                    $totalSkipped += $existingPeriods;

                    // Show which months would be created
                    for ($month = 1; $month <= 12; $month++) {
                        $existing = PeriodeAkuntansi::where('tahun', $targetYear)
                            ->where('bulan', $month)
                            ->exists();

                        if (!$existing) {
                            $monthName = Carbon::create($targetYear, $month, 1)->format('F Y');
                            $this->line("      - Would create: {$monthName}");
                        }
                    }
                } else {
                    $this->line("   ✓ All periods for {$targetYear} already exist");
                    $totalSkipped += 12;
                }
            }
        }

        $this->line("");
        $this->info("📊 Summary:");
        $this->info("   Created: {$totalCreated}");
        $this->info("   Skipped: {$totalSkipped}");
        $this->info("   Years processed: " . ($yearsAhead + 1));

        if ($totalCreated > 0 && !$dryRun) {
            $this->info("✅ Successfully auto-created {$totalCreated} accounting periods");
        } elseif ($dryRun && $totalCreated > 0) {
            $this->info("📋 Would create {$totalCreated} accounting periods");
        } else {
            $this->info("ℹ️  All required periods already exist");
        }

        return 0;
    }
}
