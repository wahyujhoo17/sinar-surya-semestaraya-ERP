<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeriodeAkuntansi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoCreateMonthlyPeriods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'periods:auto-create-monthly {--months=3 : Number of months ahead to create} {--dry-run : Show what would be created without actually creating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically create monthly accounting periods if they don\'t exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $monthsAhead = (int) $this->option('months');
        $dryRun = $this->option('dry-run');

        $this->info("🗓️  Auto Creating Monthly Periods");
        $this->info("==========================================");

        if ($dryRun) {
            $this->warn("DRY RUN MODE - No periods will be created");
            $this->line("");
        }

        $created = 0;
        $skipped = 0;
        $currentDate = Carbon::now();

        // Create periods for current month and next few months
        for ($i = 0; $i <= $monthsAhead; $i++) {
            $targetDate = $currentDate->copy()->addMonths($i);
            $year = $targetDate->year;
            $month = $targetDate->month;

            $this->info("📅 Checking period for: {$targetDate->format('F Y')}");

            // Check if period already exists
            $existingPeriod = PeriodeAkuntansi::where('tahun', $year)
                ->where('bulan', $month)
                ->first();

            if ($existingPeriod) {
                $this->line("   ✓ Period already exists: {$existingPeriod->nama}");
                $skipped++;
                continue;
            }

            if (!$dryRun) {
                try {
                    // Create the period
                    $startDate = Carbon::create($year, $month, 1)->startOfMonth();
                    $endDate = $startDate->copy()->endOfMonth();

                    $periode = PeriodeAkuntansi::create([
                        'nama' => $startDate->format('F Y'),
                        'tahun' => $year,
                        'bulan' => $month,
                        'tanggal_mulai' => $startDate,
                        'tanggal_akhir' => $endDate,
                        'status' => 'open',
                        'keterangan' => 'Auto-created by system scheduler'
                    ]);

                    $this->info("   ✅ Created: {$periode->nama} ({$periode->tanggal_mulai->format('d/m/Y')} - {$periode->tanggal_akhir->format('d/m/Y')})");
                    $created++;

                    // Log the creation
                    Log::info('Auto-created accounting period', [
                        'periode_id' => $periode->id,
                        'nama' => $periode->nama,
                        'tahun' => $periode->tahun,
                        'bulan' => $periode->bulan,
                        'tanggal_mulai' => $periode->tanggal_mulai->format('Y-m-d'),
                        'tanggal_akhir' => $periode->tanggal_akhir->format('Y-m-d')
                    ]);
                } catch (\Exception $e) {
                    $this->error("   ❌ Failed to create period for {$targetDate->format('F Y')}: {$e->getMessage()}");
                    Log::error('Failed to auto-create accounting period', [
                        'year' => $year,
                        'month' => $month,
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                $startDate = Carbon::create($year, $month, 1)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
                $this->info("   📝 Would create: {$startDate->format('F Y')} ({$startDate->format('d/m/Y')} - {$endDate->format('d/m/Y')})");
                $created++;
            }
        }

        $this->line("");
        $this->info("📊 Summary:");
        $this->info("   Created: {$created}");
        $this->info("   Skipped: {$skipped}");

        if ($created > 0 && !$dryRun) {
            $this->info("✅ Successfully auto-created {$created} accounting periods");
        } elseif ($dryRun && $created > 0) {
            $this->info("📋 Would create {$created} accounting periods");
        } else {
            $this->info("ℹ️  All required periods already exist");
        }

        return 0;
    }
}
