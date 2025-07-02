<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeService
{
    /**
     * Generate QR Code with logo in the center
     *
     * @param string $data Data to encode in QR code
     * @param string $userName User name for signature validation
     * @param int $size QR code size (default: 200)
     * @param string $logoPath Path to company logo (optional, currently unused)
     * @return string Base64 encoded QR code image
     */
    public function generateQRCodeWithLogo($data, $userName, $size = 200, $logoPath = null)
    {
        // Create QR code data with user information
        $qrData = [
            'document_data' => $data,
            'signed_by' => $userName,
            'generated_at' => now()->toISOString(),
            'company' => setting('company_name', 'Sinar Surya Semestayata'),
            'system' => 'ERP System'
        ];

        // Convert to JSON string
        $qrContent = json_encode($qrData);

        // Try to generate QR code with logo
        if (!$logoPath) {
            $logoPath = public_path('img/Logo.png');
        }

        return $this->generateQRWithCenterLogo($qrContent, $size, $logoPath);
    }
    /**
     * Generate simple QR code with optional logo
     *
     * @param string $content QR code content
     * @param int $size QR code size
     * @param bool $withLogo Whether to include logo
     * @return string Base64 encoded QR code image
     */
    public function generateSimpleQRCode($content, $size = 200, $withLogo = false)
    {
        if ($withLogo) {
            $logoService = new \App\Services\QRCodeWithLogoService();
            $logoPath = public_path('img/Logo.png');
            return $logoService->generateQRWithLogo($content, $size, $logoPath);
        }

        // Use PDF-optimized QR code generation without logo
        return $this->generateQRCodeForPDF($content, $size);
    }

    /**
     * Generate a placeholder QR code when all else fails
     *
     * @param int $size
     * @return string
     */
    private function generatePlaceholderQR($size = 200): string
    {
        // Create a simple placeholder image
        $image = imagecreate($size, $size);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);

        imagefill($image, 0, 0, $white);
        imagerectangle($image, 10, 10, $size - 10, $size - 10, $black);

        // Add "QR" text in center
        $fontSize = (int) ($size / 10);
        $textWidth = imagefontwidth(5) * 2; // "QR" = 2 characters
        $textHeight = imagefontheight(5);
        $x = (int) (($size - $textWidth) / 2);
        $y = (int) (($size - $textHeight) / 2);

        imagestring($image, 5, $x, $y, 'QR', $black);

        ob_start();
        imagepng($image);
        $imageData = ob_get_contents();
        ob_end_clean();

        imagedestroy($image);

        return 'data:image/png;base64,' . base64_encode($imageData);
    }

    /**
     * Generate QR code for PDF documents
     *
     * @param string $documentType Type of document (e.g., 'penyesuaian_stok')
     * @param string $documentNumber Document number
     * @param string $userName User who created/processed the document
     * @param array $additionalData Additional data to include
     * @return string Base64 encoded QR code image
     */
    public function generateDocumentQRCode($documentType, $documentNumber, $userName, $additionalData = [])
    {
        $documentData = array_merge([
            'document_type' => $documentType,
            'document_number' => $documentNumber,
            'signed_by' => $userName,
            'generated_at' => now()->toISOString(),
            'company' => setting('company_name', 'Sinar Surya'),
            'verification_url' => url("/verify-document/{$documentType}/{$documentNumber}")
        ], $additionalData);

        $qrContent = json_encode($documentData);

        return $this->generateSimpleQRCode($qrContent, 200, true); // With logo
    }

    /**
     * Generate signature QR code for user authentication
     *
     * @param string $userName User name
     * @param string $userEmail User email
     * @param string $role User role
     * @param string $action Action performed (e.g., 'created', 'approved', 'processed')
     * @return string Base64 encoded QR code image
     */
    public function generateSignatureQRCode($userName, $userEmail, $role, $action = 'signed')
    {
        $signatureData = [
            'user_name' => $userName,
            'user_email' => $userEmail,
            'user_role' => $role,
            'action' => $action,
            'timestamp' => now()->toISOString(),
            'company' => setting('company_name', 'Sinar Surya'),
            'system' => 'ERP Digital Signature'
        ];

        $qrContent = json_encode($signatureData);

        return $this->generateSimpleQRCode($qrContent, 150, true); // With logo, smaller size for signatures
    }

    /**
     * Save QR code as file (for testing purposes)
     *
     * @param string $content QR code content
     * @param int $size QR code size
     * @param string $filename Output filename
     * @return string File path
     */
    public function saveQRCodeAsFile($content, $size = 200, $filename = null)
    {
        try {
            if (!$filename) {
                $filename = 'qr_' . time() . '.png';
            }

            $qrCode = QrCode::size($size)
                ->format('png')
                ->margin(2)
                ->generate($content);

            $path = public_path('img/qr_codes');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $fullPath = $path . '/' . $filename;
            file_put_contents($fullPath, $qrCode);

            return 'img/qr_codes/' . $filename;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate QR code and return both base64 and file path
     *
     * @param string $content QR code content
     * @param int $size QR code size
     * @return array
     */
    public function generateQRCodeBoth($content, $size = 200)
    {
        try {
            $qrCode = QrCode::size($size)
                ->format('png')
                ->margin(2)
                ->generate($content);

            $base64 = 'data:image/png;base64,' . base64_encode($qrCode);

            // Also save as file
            $filename = 'qr_' . md5($content . time()) . '.png';
            $path = public_path('img/qr_codes');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $fullPath = $path . '/' . $filename;
            file_put_contents($fullPath, $qrCode);
            $filePath = 'img/qr_codes/' . $filename;

            return [
                'base64' => $base64,
                'file_path' => $filePath,
                'url' => asset($filePath)
            ];
        } catch (\Exception $e) {
            return [
                'base64' => $this->generatePlaceholderQR($size),
                'file_path' => null,
                'url' => null
            ];
        }
    }

    /**
     * Generate QR code specifically for PDF (using SVG format)
     *
     * @param string $content QR code content
     * @param int $size QR code size
     * @return string SVG string or base64 PNG as fallback
     */
    public function generateQRCodeForPDF($content, $size = 200)
    {
        try {
            // Try SVG format first (better for PDF)
            $qrCode = QrCode::size($size)
                ->format('svg')
                ->margin(2)
                ->generate($content);

            // Return SVG as data URI
            return 'data:image/svg+xml;base64,' . base64_encode($qrCode);
        } catch (\Exception $e) {
            // Fallback to PNG if SVG fails
            try {
                $qrCode = QrCode::size($size)
                    ->format('png')
                    ->margin(2)
                    ->errorCorrection('H') // High error correction for better scanning
                    ->generate($content);

                return 'data:image/png;base64,' . base64_encode($qrCode);
            } catch (\Exception $e2) {
                // Last resort: placeholder
                return $this->generatePlaceholderQR($size);
            }
        }
    }

    /**
     * Generate QR code with logo in center
     *
     * @param string $content QR code content
     * @param int $size QR code size
     * @param string $logoPath Path to logo file
     * @return string Base64 encoded QR code image
     */
    private function generateQRWithCenterLogo($content, $size = 200, $logoPath = null)
    {
        try {
            // Check if logo file exists
            if (!$logoPath || !file_exists($logoPath)) {
                // Fallback to simple QR if no logo
                return $this->generateQRCodeForPDF($content, $size);
            }

            // Generate QR code with high error correction for logo overlay
            $qrCode = QrCode::size($size)
                ->format('png')
                ->margin(2)
                ->errorCorrection('H') // High error correction allows for logo overlay
                ->generate($content);

            // Create image from QR code
            $qrImage = imagecreatefromstring($qrCode);
            if (!$qrImage) {
                return $this->generateQRCodeForPDF($content, $size);
            }

            // Load logo
            $logoInfo = getimagesize($logoPath);
            if (!$logoInfo) {
                imagedestroy($qrImage);
                return $this->generateQRCodeForPDF($content, $size);
            }

            switch ($logoInfo[2]) {
                case IMAGETYPE_PNG:
                    $logo = imagecreatefrompng($logoPath);
                    break;
                case IMAGETYPE_JPEG:
                    $logo = imagecreatefromjpeg($logoPath);
                    break;
                case IMAGETYPE_GIF:
                    $logo = imagecreatefromgif($logoPath);
                    break;
                default:
                    imagedestroy($qrImage);
                    return $this->generateQRCodeForPDF($content, $size);
            }

            if (!$logo) {
                imagedestroy($qrImage);
                return $this->generateQRCodeForPDF($content, $size);
            }

            // Calculate logo size (about 20% of QR code size)
            $logoSize = intval($size * 0.2);
            $logoX = intval(($size - $logoSize) / 2);
            $logoY = intval(($size - $logoSize) / 2);

            // Create white background circle for logo
            $circleRadius = intval($logoSize / 2) + 5;
            $circleCenterX = intval($size / 2);
            $circleCenterY = intval($size / 2);

            $white = imagecolorallocate($qrImage, 255, 255, 255);
            imagefilledellipse($qrImage, $circleCenterX, $circleCenterY, $circleRadius * 2, $circleRadius * 2, $white);

            // Resize and copy logo to center of QR code
            imagecopyresampled(
                $qrImage,
                $logo,
                $logoX,
                $logoY,
                0,
                0,
                $logoSize,
                $logoSize,
                imagesx($logo),
                imagesy($logo)
            );

            // Clean up logo image
            imagedestroy($logo);

            // Convert to base64
            ob_start();
            imagepng($qrImage);
            $imageData = ob_get_contents();
            ob_end_clean();

            imagedestroy($qrImage);

            return 'data:image/png;base64,' . base64_encode($imageData);
        } catch (\Exception $e) {
            // Fallback to simple QR code if anything fails
            return $this->generateQRCodeForPDF($content, $size);
        }
    }

    /**
     * Generate QR code using GD backend to avoid imagick dependency
     */
    private function generateQRCodeWithGD($content, $size = 200)
    {
        try {
            // Configure QR code with specific backend
            $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle($size),
                new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
            );
            $writer = new \BaconQrCode\Writer($renderer);
            $qrCodeSvg = $writer->writeString($content);

            // Convert SVG to PNG using GD
            $image = imagecreate($size, $size);
            $white = imagecolorallocate($image, 255, 255, 255);
            $black = imagecolorallocate($image, 0, 0, 0);

            // Fill background
            imagefill($image, 0, 0, $white);

            // For now, create a simple QR pattern manually
            // This is a simplified approach - in production you'd want to parse the SVG
            $qrSize = $size - 20; // Margin
            $moduleSize = intval($qrSize / 25); // Assume 25x25 module grid
            $startX = 10;
            $startY = 10;

            // Create a simple QR-like pattern
            for ($i = 0; $i < 25; $i++) {
                for ($j = 0; $j < 25; $j++) {
                    if (($i + $j) % 3 == 0) { // Simple pattern
                        $x = $startX + ($i * $moduleSize);
                        $y = $startY + ($j * $moduleSize);
                        imagefilledrectangle($image, $x, $y, $x + $moduleSize - 1, $y + $moduleSize - 1, $black);
                    }
                }
            }

            ob_start();
            imagepng($image);
            $imageData = ob_get_contents();
            ob_end_clean();

            imagedestroy($image);

            return $imageData;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Test method to debug QR code with logo generation
     */
    public function testQRWithLogo($content = 'Test QR', $size = 200)
    {
        $logoPath = public_path('img/Logo.png');

        echo "=== QR WITH LOGO DEBUG ===\n";
        echo "Content: $content\n";
        echo "Size: $size\n";
        echo "Logo path: $logoPath\n";
        echo "Logo exists: " . (file_exists($logoPath) ? 'YES' : 'NO') . "\n";

        if (!file_exists($logoPath)) {
            return "Logo file not found!";
        }

        try {
            // Step 1: Generate base QR code
            echo "\n--- Generating base QR code ---\n";
            $qrCode = QrCode::size($size)
                ->format('png')
                ->margin(2)
                ->errorCorrection('H')
                ->generate($content);
            echo "QR code generated, size: " . strlen($qrCode) . " bytes\n";

            // Step 2: Create QR image
            echo "\n--- Creating QR image ---\n";
            $qrImage = imagecreatefromstring($qrCode);
            if (!$qrImage) {
                return "Failed to create QR image from string";
            }
            echo "QR image created successfully\n";
            echo "QR dimensions: " . imagesx($qrImage) . "x" . imagesy($qrImage) . "\n";

            // Step 3: Load logo
            echo "\n--- Loading logo ---\n";
            $logoInfo = getimagesize($logoPath);
            echo "Logo dimensions: " . $logoInfo[0] . "x" . $logoInfo[1] . "\n";
            echo "Logo type: " . $logoInfo[2] . " (3=PNG)\n";

            $logo = imagecreatefrompng($logoPath);
            if (!$logo) {
                imagedestroy($qrImage);
                return "Failed to create logo image";
            }
            echo "Logo image created successfully\n";
            echo "Logo dimensions: " . imagesx($logo) . "x" . imagesy($logo) . "\n";

            // Step 4: Calculate positions
            echo "\n--- Calculating positions ---\n";
            $logoSize = intval($size * 0.2);
            $logoX = intval(($size - $logoSize) / 2);
            $logoY = intval(($size - $logoSize) / 2);
            echo "Logo will be resized to: {$logoSize}x{$logoSize}\n";
            echo "Logo position: ({$logoX}, {$logoY})\n";

            // Step 5: Create white background
            echo "\n--- Creating white background ---\n";
            $circleRadius = intval($logoSize / 2) + 5;
            $circleCenterX = intval($size / 2);
            $circleCenterY = intval($size / 2);

            $white = imagecolorallocate($qrImage, 255, 255, 255);
            imagefilledellipse($qrImage, $circleCenterX, $circleCenterY, $circleRadius * 2, $circleRadius * 2, $white);
            echo "White circle created at ({$circleCenterX}, {$circleCenterY}) with radius {$circleRadius}\n";

            // Step 6: Copy logo
            echo "\n--- Copying logo ---\n";
            $copyResult = imagecopyresampled(
                $qrImage,
                $logo,
                $logoX,
                $logoY,
                0,
                0,
                $logoSize,
                $logoSize,
                imagesx($logo),
                imagesy($logo)
            );
            echo "Logo copy result: " . ($copyResult ? 'SUCCESS' : 'FAILED') . "\n";

            // Step 7: Generate final image
            echo "\n--- Generating final image ---\n";
            imagedestroy($logo);

            ob_start();
            imagepng($qrImage);
            $imageData = ob_get_contents();
            ob_end_clean();

            imagedestroy($qrImage);

            echo "Final image size: " . strlen($imageData) . " bytes\n";
            echo "Base64 length: " . strlen(base64_encode($imageData)) . " chars\n";

            return 'data:image/png;base64,' . base64_encode($imageData);
        } catch (\Exception $e) {
            return "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
        }
    }
}
