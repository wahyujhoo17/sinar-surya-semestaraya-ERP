<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestQRCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:test {phone?} {--type=Purchase Order} {--number=PO-TEST-001}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test QR Code generation for WhatsApp verification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phone = $this->argument('phone') ?? '081234567890';
        $type = $this->option('type');
        $number = $this->option('number');

        $this->info('Testing QR Code Generation...');
        $this->line('');
        $this->line("Phone: {$phone}");
        $this->line("Document Type: {$type}");
        $this->line("Document Number: {$number}");
        $this->line('');

        // Check if library exists
        if (!class_exists('\SimpleSoftwareIO\QrCode\Facades\QrCode')) {
            $this->error('❌ SimpleSoftwareIO QrCode library not found!');
            $this->line('Run: composer require simplesoftwareio/simple-qrcode');
            return 1;
        }

        $this->info('✅ QR Code library found');

        // Test URL generation
        try {
            $url = generateWhatsAppQRUrl($phone, $type, $number);
            $this->info('✅ WhatsApp URL generated successfully');
            $this->line("URL: {$url}");
            $this->line('');
        } catch (\Exception $e) {
            $this->error('❌ Failed to generate WhatsApp URL: ' . $e->getMessage());
            return 1;
        }

        // Test QR Code generation
        try {
            $qrCode = generateWhatsAppQRCode($phone, $type, $number, 150);

            if ($qrCode) {
                $this->info('✅ QR Code generated successfully!');
                $this->line('Base64 length: ' . strlen($qrCode));
                $this->line('');
                $this->info('QR Code is ready to use in PDF templates.');
            } else {
                $this->error('❌ QR Code generation returned null');
                $this->line('Check Laravel logs for more details:');
                $this->line('  tail -f storage/logs/laravel.log');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('❌ Failed to generate QR Code: ' . $e->getMessage());
            $this->line('');
            $this->line('Error details:');
            $this->line($e->getTraceAsString());
            return 1;
        }

        // Check PHP extensions
        $this->line('');
        $this->info('PHP Extensions:');
        $this->line('  GD: ' . (extension_loaded('gd') ? '✅ Installed' : '❌ Not installed'));
        $this->line('  Imagick: ' . (extension_loaded('imagick') ? '✅ Installed' : '❌ Not installed (not required for SVG)'));
        $this->line('');

        $this->info('✅ All tests passed!');
        return 0;
    }
}
