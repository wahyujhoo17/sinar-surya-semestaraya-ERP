<?php
// filepath: app/Helpers/helpers.php


if (!function_exists('isMenuActive')) {
    /**
     * Check if the current route matches any of the given patterns.
     *
     * @param array|string $patterns Route name patterns (e.g., 'users.*' or ['users.*', 'profile'])
     * @param string $activeClass CSS class to return if active (optional)
     * @param string $inactiveClass CSS class to return if inactive (optional)
     * @return bool|string Returns boolean if no classes provided, otherwise the corresponding class string.
     */
    function isMenuActive($patterns, $activeClass = null, $inactiveClass = null)
    {
        $patterns = (array) $patterns;
        $isActive = false;

        // Use the fully qualified namespace for Route facade
        foreach ($patterns as $pattern) {
            if (\Illuminate\Support\Facades\Route::is($pattern)) {
                $isActive = true;
                break;
            }
        }

        if ($activeClass === null && $inactiveClass === null) {
            return $isActive;
        }

        return $isActive ? $activeClass : $inactiveClass;
    }
}

if (!function_exists('tanggal_indo')) {
    /**
     * Format tanggal ke format Indonesia (dd/mm/yyyy atau dd Month yyyy)
     * @param string|\DateTimeInterface $tanggal
     * @param bool $withMonthName
     * @return string
     */
    function tanggal_indo($tanggal, $withMonthName = false)
    {
        if (!$tanggal) return '';
        if ($tanggal instanceof \DateTimeInterface) {
            $tanggal = $tanggal->format('Y-m-d');
        }
        $bulan = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];
        $parts = explode('-', $tanggal);
        if (count($parts) !== 3) return $tanggal;
        if ($withMonthName) {
            return ltrim($parts[2], '0') . ' ' . $bulan[(int)$parts[1]] . ' ' . $parts[0];
        }
        return $parts[2] . '/' . $parts[1] . '/' . $parts[0];
    }
}

if (!function_exists('format_rupiah')) {
    /**
     * Format number to Rupiah currency format.
     *
     * @param float $number
     * @param bool $withRpPrefix
     * @return string
     */
    function format_rupiah($number, $withRpPrefix = true)
    {
        if (!is_numeric($number)) {
            return $number; // or return 'Invalid Number';
        }
        $formatted = number_format($number, 0, ',', '.');
        return $withRpPrefix ? 'Rp ' . $formatted : $formatted;
    }
}

if (!function_exists('terbilang')) {
    /**
     * Convert number to Indonesian words
     * 
     * @param float $number
     * @return string
     */
    function terbilang($number)
    {
        if (!is_numeric($number)) {
            return 'bukan angka';
        }

        if ($number < 0) {
            return 'minus ' . terbilang(abs($number));
        }

        $number = floor($number);

        $words = '';

        $digits = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];

        if ($number < 12) {
            $words = $digits[$number];
        } elseif ($number < 20) {
            $words = $digits[$number - 10] . ' belas';
        } elseif ($number < 100) {
            $words = $digits[floor($number / 10)] . ' puluh ' . $digits[$number % 10];
        } elseif ($number < 200) {
            $words = 'seratus ' . terbilang($number - 100);
        } elseif ($number < 1000) {
            $words = $digits[floor($number / 100)] . ' ratus ' . terbilang($number % 100);
        } elseif ($number < 2000) {
            $words = 'seribu ' . terbilang($number - 1000);
        } elseif ($number < 1000000) {
            $words = terbilang(floor($number / 1000)) . ' ribu ' . terbilang($number % 1000);
        } elseif ($number < 1000000000) {
            $words = terbilang(floor($number / 1000000)) . ' juta ' . terbilang($number % 1000000);
        } elseif ($number < 1000000000000) {
            $words = terbilang(floor($number / 1000000000)) . ' milyar ' . terbilang($number % 1000000000);
        } elseif ($number < 1000000000000000) {
            $words = terbilang(floor($number / 1000000000000)) . ' trilyun ' . terbilang($number % 1000000000000);
        }

        return trim($words);
    }
}

if (!function_exists('generateWhatsAppQRUrl')) {
    /**
     * Generate WhatsApp URL for QR Code with document verification message
     * 
     * @param string $phoneNumber Phone number from user table
     * @param string $documentType Type of document (PO, Invoice, SO, etc.)
     * @param string $documentNumber Document number
     * @return string WhatsApp URL
     */
    function generateWhatsAppQRUrl($phoneNumber, $documentType, $documentNumber)
    {
        // Clean phone number - remove non-numeric characters
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Convert Indonesian phone format to international format
        if (substr($cleanPhone, 0, 1) === '0') {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        } elseif (substr($cleanPhone, 0, 2) !== '62') {
            $cleanPhone = '62' . $cleanPhone;
        }

        // Template message - Professional and formal
        // Keep it simple for iPhone scanner compatibility
        $message = "Yth. Bapak/Ibu,\n\n";
        $message .= "Saya ingin melakukan verifikasi dokumen {$documentType} dengan nomor {$documentNumber}.\n\n";
        $message .= "Mohon bantuan untuk dapat mengkonfirmasi keaslian dokumen ini.\n\n";
        $message .= "Terima kasih atas perhatian dan kerjasamanya.";

        // Gunakan urlencode standar (bukan rawurlencode) untuk kompatibilitas iOS
        $encodedMessage = urlencode($message);

        // PENTING: Gunakan format wa.me yang lebih pendek dan iOS-friendly
        // Format ini paling reliable untuk iPhone camera scanner
        $whatsappUrl = "https://wa.me/{$cleanPhone}?text={$encodedMessage}";

        return $whatsappUrl;
    }
}

if (!function_exists('generateWhatsAppQRCode')) {
    /**
     * Generate QR Code for WhatsApp verification dengan fallback format
     * Optimized untuk iPhone camera scanner dan Railway deployment
     * Try PNG first (better DomPDF support), fallback to SVG
     * 
     * @param string $phoneNumber Phone number from user table
     * @param string $documentType Type of document
     * @param string $documentNumber Document number
     * @param int $size QR Code size in pixels (default 150)
     * @return string|null Base64 encoded QR Code or null on failure
     */
    function generateWhatsAppQRCode($phoneNumber, $documentType, $documentNumber, $size = 150)
    {
        try {
            // Check if QR Code library is available
            if (!class_exists('\SimpleSoftwareIO\QrCode\Facades\QrCode')) {
                \Illuminate\Support\Facades\Log::error('QR Code library not available');
                return null;
            }

            // Generate WhatsApp URL
            $whatsappUrl = generateWhatsAppQRUrl($phoneNumber, $documentType, $documentNumber);

            // DEBUG: Log URL untuk troubleshooting
            \Illuminate\Support\Facades\Log::info('WhatsApp QR Code Generation Attempt:', [
                'phone' => $phoneNumber,
                'type' => $documentType,
                'number' => $documentNumber,
                'url' => $whatsappUrl,
                'url_length' => strlen($whatsappUrl),
                'environment' => app()->environment()
            ]);

            // Try PNG first (better DomPDF compatibility di Railway production)
            $qrCode = null;
            $format = 'png';
            $mimeType = 'image/png';

            try {
                if (extension_loaded('gd')) {
                    $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                        ->size($size)
                        ->margin(2)
                        ->errorCorrection('H')
                        ->generate($whatsappUrl);

                    \Illuminate\Support\Facades\Log::info('QR Code generated with PNG format');
                }
            } catch (\Exception $pngError) {
                \Illuminate\Support\Facades\Log::warning('PNG generation failed, trying SVG fallback', [
                    'error' => $pngError->getMessage()
                ]);
            }

            // Fallback to SVG if PNG failed or GD not available
            if (empty($qrCode)) {
                $format = 'svg';
                $mimeType = 'image/svg+xml';
                $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                    ->size($size)
                    ->margin(2)
                    ->errorCorrection('H')
                    ->generate($whatsappUrl);

                \Illuminate\Support\Facades\Log::info('QR Code generated with SVG fallback');
            }

            // Verify QR code was generated
            if (empty($qrCode)) {
                \Illuminate\Support\Facades\Log::error('QR Code generation returned empty result');
                return null;
            }

            // Convert to base64 for embedding in PDF
            $base64 = base64_encode($qrCode);

            \Illuminate\Support\Facades\Log::info('QR Code generated successfully', [
                'format' => $format,
                'size' => strlen($base64),
                'has_gd' => extension_loaded('gd'),
                'environment' => app()->environment()
            ]);

            return 'data:' . $mimeType . ';base64,' . $base64;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to generate WhatsApp QR Code', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'phone' => $phoneNumber,
                'environment' => app()->environment()
            ]);
            return null;
        }
    }
}