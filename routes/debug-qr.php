<?php

use Illuminate\Support\Facades\Route;

/**
 * Debug QR Code Rendering Endpoint
 * Test apakah QR Code bisa di-render di HTML (tanpa DomPDF)
 */
Route::get('/debug-qr-render', function () {
    // Test data
    $phone = '081234567890';
    $docType = 'Sales Order';
    $docNumber = 'SO-TEST-001';
    
    // Generate QR Code
    $whatsappQR = generateWhatsAppQRCode($phone, $docType, $docNumber, 120);
    
    // Return HTML with QR Code (exactly like PDF template)
    return view('debug.qr-test', compact('whatsappQR'));
})->name('debug.qr.render');

/**
 * Debug Specific Sales Order QR
 * Test QR Code dari SO yang sebenarnya
 */
Route::get('/debug-so-qr/{id}', function ($id) {
    $salesOrder = \App\Models\Penjualan\SalesOrder::with('user')->findOrFail($id);
    
    $createdBy = $salesOrder->user;
    $whatsappQR = null;
    $debugInfo = [];
    
    if ($createdBy && $createdBy->phone) {
        $whatsappQR = generateWhatsAppQRCode(
            $createdBy->phone,
            'Sales Order',
            $salesOrder->nomor,
            120
        );
        
        $debugInfo = [
            'so_number' => $salesOrder->nomor,
            'creator_name' => $createdBy->name,
            'creator_phone' => $createdBy->phone,
            'qr_generated' => !is_null($whatsappQR),
            'qr_length' => $whatsappQR ? strlen($whatsappQR) : 0,
            'qr_format' => $whatsappQR ? (strpos($whatsappQR, 'svg') !== false ? 'SVG' : 'PNG') : 'NONE',
            'has_gd' => extension_loaded('gd'),
            'php_version' => PHP_VERSION,
        ];
    } else {
        $debugInfo = [
            'error' => 'No creator or phone number',
            'has_user' => !is_null($createdBy),
            'has_phone' => $createdBy ? !is_null($createdBy->phone) : false,
        ];
    }
    
    return view('debug.qr-test', compact('whatsappQR', 'debugInfo'));
})->name('debug.so.qr');
