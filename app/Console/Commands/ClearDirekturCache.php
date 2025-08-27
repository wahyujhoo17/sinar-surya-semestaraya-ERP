<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DirekturUtamaService;

class ClearDirekturCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-direktur {--force : Force clear cache without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear direktur utama cache to force refresh';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('Are you sure you want to clear direktur utama cache?')) {
            $this->info('Cache clear cancelled.');
            return Command::FAILURE;
        }

        DirekturUtamaService::clearCache();

        $this->info('Direktur utama cache cleared successfully.');

        // Test that fresh data is loaded
        $direktur = DirekturUtamaService::getDirekturUtama();
        $this->line("Current direktur utama: <info>{$direktur}</info>");

        return Command::SUCCESS;
    }
}
