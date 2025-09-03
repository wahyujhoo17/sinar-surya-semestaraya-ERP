<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BOMCostService;

class UpdateBOMCosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bom:update-costs {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update harga beli semua produk yang memiliki BOM aktif berdasarkan komponen BOM';

    protected $bomCostService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BOMCostService $bomCostService)
    {
        parent::__construct();
        $this->bomCostService = $bomCostService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info('🚀 Memulai update harga beli produk berdasarkan BOM...');

        if ($dryRun) {
            $this->warn('⚠️  DRY RUN MODE: Tidak ada perubahan yang akan disimpan');
        }

        try {
            if ($dryRun) {
                // Untuk dry run, kita perlu implementasi terpisah
                $this->performDryRun();
            } else {
                $results = $this->bomCostService->batchUpdateAllBOMProducts();
                $this->displayResults($results);
            }

            $this->info('✅ Proses selesai!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function performDryRun()
    {
        $activeBOMs = \App\Models\BillOfMaterial::where('is_active', true)
            ->with(['produk', 'details.komponen'])
            ->get();

        $this->info("📊 Ditemukan {$activeBOMs->count()} BOM aktif untuk diproses:");
        $this->newLine();

        $table = [];

        foreach ($activeBOMs as $bom) {
            $costCalculation = $this->bomCostService->calculateBOMCost($bom->id);

            if ($costCalculation) {
                $currentCost = $bom->produk->harga_beli ?? 0;
                $newCost = $costCalculation['cost_per_unit'];
                $difference = $newCost - $currentCost;

                $table[] = [
                    'BOM' => $bom->kode,
                    'Produk' => $bom->produk->nama,
                    'Harga Saat Ini' => 'Rp ' . number_format($currentCost, 0, ',', '.'),
                    'Harga Baru' => 'Rp ' . number_format($newCost, 0, ',', '.'),
                    'Selisih' => 'Rp ' . number_format($difference, 0, ',', '.'),
                    'Status' => $difference == 0 ? '✓ Sama' : ($difference > 0 ? '↑ Naik' : '↓ Turun')
                ];
            }
        }

        if (!empty($table)) {
            $this->table([
                'BOM Code',
                'Produk',
                'Harga Saat Ini',
                'Harga Baru',
                'Selisih',
                'Status'
            ], $table);
        }

        $this->newLine();
        $this->info('💡 Gunakan perintah tanpa --dry-run untuk menerapkan perubahan');
    }

    private function displayResults($results)
    {
        if (empty($results)) {
            $this->warn('⚠️  Tidak ada produk yang diupdate');
            return;
        }

        $this->info("✅ Berhasil mengupdate {" . count($results) . "} produk:");
        $this->newLine();

        $table = [];

        foreach ($results as $result) {
            $produkData = $result['result'];
            $table[] = [
                'BOM ID' => $result['bom_id'],
                'Produk' => $result['produk_nama'],
                'Harga Lama' => 'Rp ' . number_format($produkData['old_cost'], 0, ',', '.'),
                'Harga Baru' => 'Rp ' . number_format($produkData['new_cost'], 0, ',', '.'),
                'Selisih' => 'Rp ' . number_format($produkData['new_cost'] - $produkData['old_cost'], 0, ',', '.'),
            ];
        }

        $this->table([
            'BOM ID',
            'Produk',
            'Harga Lama',
            'Harga Baru',
            'Selisih'
        ], $table);
    }
}
