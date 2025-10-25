<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

Route::get('/test-qr', function () {
    $results = [];
    
    // 1. Check library
    $results['library_exists'] = class_exists('\SimpleSoftwareIO\QrCode\Facades\QrCode');
    
    // 2. Check helper function
    $results['helper_exists'] = function_exists('generateWhatsAppQRCode');
    
    // 3. Try generate
    try {
        $qr = generateWhatsAppQRCode('081234567890', 'Test Order', 'TEST-001', 100);
        $results['qr_generated'] = !empty($qr);
        $results['qr_size'] = $qr ? strlen($qr) : 0;
        $results['qr_preview'] = $qr ? substr($qr, 0, 100) . '...' : null;
    } catch (\Exception $e) {
        $results['qr_error'] = $e->getMessage();
        Log::error('Test QR Error: ' . $e->getMessage());
    }
    
    // 4. Check PHP extensions
    $results['extensions'] = [
        'gd' => extension_loaded('gd'),
        'xml' => extension_loaded('xml'),
        'imagick' => extension_loaded('imagick'),
    ];
    
    // 5. Environment
    $results['environment'] = app()->environment();
    $results['php_version'] = PHP_VERSION;
    
    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
});
