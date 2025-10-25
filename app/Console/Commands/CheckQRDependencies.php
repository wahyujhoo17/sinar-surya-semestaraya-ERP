<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckQRDependencies extends Command
{
    protected $signature = 'qr:check';
    protected $description = 'Check QR Code dependencies and environment';

    public function handle()
    {
        $this->info('=== QR Code Dependency Check ===');
        $this->newLine();

        // Check Composer package
        $composerLock = base_path('composer.lock');
        if (file_exists($composerLock)) {
            $content = file_get_contents($composerLock);
            if (strpos($content, 'simplesoftwareio/simple-qrcode') !== false) {
                $this->info('✅ simple-qrcode package found in composer.lock');
            } else {
                $this->error('❌ simple-qrcode package NOT found in composer.lock');
            }
        }

        // Check class exists
        if (class_exists('\SimpleSoftwareIO\QrCode\Facades\QrCode')) {
            $this->info('✅ QrCode Facade class is available');
        } else {
            $this->error('❌ QrCode Facade class NOT available');
            $this->line('Run: composer require simplesoftwareio/simple-qrcode');
            return 1;
        }

        // Check PHP extensions
        $this->newLine();
        $this->info('PHP Extensions:');
        $this->line('  GD: ' . (extension_loaded('gd') ? '✅' : '❌'));
        $this->line('  Imagick: ' . (extension_loaded('imagick') ? '✅' : '❌ (not required for SVG)'));
        $this->line('  XML: ' . (extension_loaded('xml') ? '✅' : '❌'));

        // Test generation
        $this->newLine();
        $this->info('Testing QR Code generation...');

        try {
            $testUrl = 'https://wa.me/081234567890';
            $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(100)
                ->generate($testUrl);

            if (!empty($qr)) {
                $this->info('✅ QR Code generated successfully');
                $this->line('Size: ' . strlen($qr) . ' bytes');
            } else {
                $this->error('❌ QR Code generation returned empty');
            }
        } catch (\Exception $e) {
            $this->error('❌ QR Code generation failed: ' . $e->getMessage());
            return 1;
        }

        // Environment info
        $this->newLine();
        $this->info('Environment:');
        $this->line('  APP_ENV: ' . app()->environment());
        $this->line('  PHP Version: ' . PHP_VERSION);
        $this->line('  Laravel Version: ' . app()->version());

        $this->newLine();
        $this->info('✅ All checks passed!');
        return 0;
    }
}
