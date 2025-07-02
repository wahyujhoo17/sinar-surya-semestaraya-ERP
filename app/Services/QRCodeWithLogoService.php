<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeWithLogoService
{
    /**
     * Generate QR code with logo overlay using SVG base and PNG logo
     */
    public function generateQRWithLogo($content, $size = 200, $logoPath = null)
    {
        try {
            if (!$logoPath || !file_exists($logoPath)) {
                // Return SVG QR code without logo
                return $this->generateSVGQR($content, $size);
            }

            // Generate base QR code as SVG
            $svgQR = QrCode::size($size)
                ->format('svg')
                ->margin(2)
                ->errorCorrection('H')
                ->generate($content);

            // Load logo
            $logoInfo = getimagesize($logoPath);
            if (!$logoInfo) {
                return $this->generateSVGQR($content, $size);
            }

            // Create base64 logo
            $logoData = file_get_contents($logoPath);
            $logoBase64 = base64_encode($logoData);
            $logoMimeType = 'image/' . ($logoInfo[2] == IMAGETYPE_PNG ? 'png' : ($logoInfo[2] == IMAGETYPE_JPEG ? 'jpeg' : 'gif'));

            // Calculate logo size and position (20% of QR size, centered)
            $logoSize = intval($size * 0.2);
            $logoX = intval(($size - $logoSize) / 2);
            $logoY = intval(($size - $logoSize) / 2);
            $circleRadius = intval($logoSize / 2) + 8;
            $circleCenterX = intval($size / 2);
            $circleCenterY = intval($size / 2);

            // Add logo to SVG
            $logoSvg = '
                <!-- White background circle for logo -->
                <circle cx="' . $circleCenterX . '" cy="' . $circleCenterY . '" r="' . $circleRadius . '" fill="white" stroke="white" stroke-width="2"/>
                
                <!-- Company logo -->
                <image x="' . $logoX . '" y="' . $logoY . '" width="' . $logoSize . '" height="' . $logoSize . '" 
                       href="data:' . $logoMimeType . ';base64,' . $logoBase64 . '"/>
            ';

            // Insert logo into SVG before closing tag
            $svgWithLogo = str_replace('</svg>', $logoSvg . '</svg>', $svgQR);

            return 'data:image/svg+xml;base64,' . base64_encode($svgWithLogo);
        } catch (\Exception $e) {
            // Fallback to simple SVG QR
            return $this->generateSVGQR($content, $size);
        }
    }

    /**
     * Generate simple SVG QR code
     */
    private function generateSVGQR($content, $size = 200)
    {
        try {
            $qrCode = QrCode::size($size)
                ->format('svg')
                ->margin(2)
                ->errorCorrection('H')
                ->generate($content);

            return 'data:image/svg+xml;base64,' . base64_encode($qrCode);
        } catch (\Exception $e) {
            return null;
        }
    }
}
