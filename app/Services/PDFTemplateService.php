<?php

namespace App\Services;

use TCPDF;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\Log;

class PDFTemplateService
{
    /**
     * Fill delivery order template PDF with data using FPDI
     */
    public function fillDeliveryOrderTemplate($deliveryOrder)
    {

        // Tambahkan parameter opsional untuk menonaktifkan template background
        $useTemplate = config('app.print_with_template', false); // default true, bisa diatur di config/app.php
        $templatePath = public_path('pdf/Surat-Jalan.pdf');

        $customWidth = 165;
        $customHeight = 212;

        try {
            // Create FPDI instance
            $pdf = new Fpdi();

            // Set PDF properties before importing
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetAutoPageBreak(false);
            $pdf->SetMargins(0, 0, 0);

            // Add a page with the custom dimensions
            $orientation = $customHeight > $customWidth ? 'P' : 'L';
            $pdf->AddPage($orientation, [$customWidth, $customHeight]);

            // Jika ingin pakai template background
            if ($useTemplate && file_exists($templatePath)) {
                $pageCount = $pdf->setSourceFile($templatePath);
                $tplId = $pdf->importPage(1);
                $pdf->useTemplate($tplId, 0, 0, $customWidth, $customHeight);
            }


            // Set document information
            $pdf->SetCreator('ERP Sinar Surya');
            $pdf->SetAuthor('ERP System');
            $pdf->SetTitle('Surat Jalan - ' . $deliveryOrder->nomor);

            // Set font for text overlay - smaller for better fit
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(0, 0, 0);

            // Log template size for debugging
            Log::info('Template dimensions: ' . $customWidth . 'x' . $customHeight . ' mm (custom), useTemplate=' . ($useTemplate ? 'yes' : 'no'));


            // --- Penempatan absolut sesuai layout PDF asli ---
            // Silakan sesuaikan nilai X/Y di bawah sesuai hasil preview PDF asli
            // Nomor surat jalan (koordinat baru: X=10mm, Y=18mm)
            $nomorX = 31;
            $nomorY = 42.5;
            $pdf->SetXY($nomorX, $nomorY);
            $pdf->Cell(40, 0, $deliveryOrder->nomor, 0, 0, 'L');

            if (!empty($deliveryOrder->user) && !empty($deliveryOrder->user->name)) {
                $userX = 130;
                $userY = 200;
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetXY($userX, $userY);
                $pdf->Cell(60, 0, $deliveryOrder->user->name, 0, 0, 'L');
            }

            // Tanggal (di bawah nomor)
            $tanggalX = 120;
            $tanggalY = 20;
            $pdf->SetXY($tanggalX, $tanggalY);
            // Format tanggal seperti "Jakarta, 3 July 2025"
            $tanggal = $deliveryOrder->tanggal_kirim ?? $deliveryOrder->created_at;
            $tanggalObj = \Carbon\Carbon::parse($tanggal);
            $formattedTanggal = 'Jakarta, ' . $tanggalObj->day . ' ' . $tanggalObj->format('F Y');
            $pdf->Cell(60, 0, $formattedTanggal, 0, 0, 'L');

            // Customer (kiri atas, X=111mm, Y=35mm), dengan max width agar tidak melebihi halaman
            $customerX = 90;
            $customerY = 40;
            $maxCustomerWidth = 50;

            // Nama customer bold
            // Nama customer bold
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetXY($customerX, $customerY);
            $pdf->MultiCell($maxCustomerWidth, 5, $deliveryOrder->customer->company ?? $deliveryOrder->customer->nama, 0, 'L');

            // Alamat customer (jika ada), font normal, di bawah company/nama
            if (!empty($deliveryOrder->customer)) {
                $pdf->SetFont('helvetica', '', 8);
                $alamatY = $customerY + 5.5; // geser ke bawah 5.5mm dari nama
                $pdf->SetXY($customerX, $alamatY);
                $pdf->MultiCell($maxCustomerWidth, 4, $deliveryOrder->customer->alamat_pengiriman, 0, 'L');
            }

            $pdf->SetFont('helvetica', '', 8);

            // Items table (misal mulai X=15mm, Y=55mm)
            $itemsStartY = 74;
            $lineHeight = 6;
            $currentY = $itemsStartY;
            $maxItemsY = 170;

            // Kolom tabel: No | Nama Barang | Kode | Qty | Satuan
            $noCol = 11;
            $namaCol = 24;
            $kodeCol = 103;
            $qtyCol = 114;
            $satuanCol = 145;

            // Group bundle items first
            $bundleGroups = [];
            $nonBundleItems = [];

            foreach ($deliveryOrder->details as $detail) {
                if (!empty($detail->is_bundle_item) && !empty($detail->bundle_name)) {
                    $bundleGroups[$detail->bundle_name][] = $detail;
                } else {
                    $nonBundleItems[] = $detail;
                }
            }

            $itemNumber = 1;

            // Render Bundle Groups first
            foreach ($bundleGroups as $bundleName => $bundleItems) {
                if ($currentY > $maxItemsY) break;

                // Bundle Header - Show bundle name
                $pdf->SetXY($noCol, $currentY);
                $pdf->Cell(8, $lineHeight, $itemNumber++, 0, 0, 'C');

                // Bundle name in bold
                $pdf->SetXY($namaCol, $currentY);
                $pdf->SetFont('helvetica', 'B', 9); // Bold font for bundle name
                $pdf->Cell($kodeCol - $namaCol - 2, $lineHeight, $bundleName . ':', 0, 0, 'L');

                // Empty cells for bundle header
                $pdf->SetXY($kodeCol, $currentY);
                $pdf->Cell($qtyCol - $kodeCol - 2, $lineHeight, '-', 0, 0, 'L');
                $pdf->SetXY($qtyCol - 5, $currentY);
                $pdf->Cell(($satuanCol - $qtyCol - 2) + 3, $lineHeight, 'Paket', 0, 0, 'R');

                $currentY += $lineHeight;

                // Bundle Items - Show each item in the bundle
                foreach ($bundleItems as $detail) {
                    if ($currentY > $maxItemsY) break;

                    $pdf->SetFont('helvetica', '', 9); // Reset to normal font

                    // No urut (empty for bundle items)
                    $pdf->SetXY($noCol, $currentY);
                    $pdf->Cell(8, $lineHeight, '', 0, 0, 'C');

                    // Item name with indent
                    $itemName = '  • ' . $detail->produk->nama; // Indent with bullet
                    $itemName = strlen($itemName) > 35 ? substr($itemName, 0, 32) . '...' : $itemName;

                    $pdf->SetXY($namaCol, $currentY);
                    $pdf->Cell($kodeCol - $namaCol - 2, $lineHeight, $itemName, 0, 0, 'L');

                    // Kode barang
                    $detailNomor = $detail->produk->kode ?? '-';
                    $pdf->SetXY($kodeCol, $currentY);
                    $pdf->Cell($qtyCol - $kodeCol - 2, $lineHeight, $detailNomor, 0, 0, 'L');

                    // Qty & Satuan
                    $qtyValue = number_format($detail->quantity, 0);
                    $satuanValue = $detail->satuan->nama ?? 'PCS';
                    $pdf->SetXY($qtyCol - 5, $currentY);
                    $pdf->Cell(($satuanCol - $qtyCol - 2) + 3, $lineHeight, $qtyValue . ' ' . $satuanValue, 0, 0, 'R');

                    $currentY += $lineHeight;
                }
            }

            // Render Non-Bundle Items
            foreach ($nonBundleItems as $detail) {
                if ($currentY > $maxItemsY) break;

                $pdf->SetFont('helvetica', '', 9); // Normal font

                // No urut
                $pdf->SetXY($noCol, $currentY);
                $pdf->Cell(8, $lineHeight, $itemNumber++, 0, 0, 'C');

                // Nama produk
                $namaProduk = strlen($detail->produk->nama) > 35 ?
                    substr($detail->produk->nama, 0, 32) . '...' :
                    $detail->produk->nama;

                $pdf->SetXY($namaCol, $currentY);
                $pdf->Cell($kodeCol - $namaCol - 2, $lineHeight, $namaProduk, 0, 0, 'L');

                // Kode barang
                $detailNomor = $detail->produk->kode ?? '-';
                $pdf->SetXY($kodeCol, $currentY);
                $pdf->Cell($qtyCol - $kodeCol - 2, $lineHeight, $detailNomor, 0, 0, 'L');

                // Qty & Satuan
                $qtyValue = number_format($detail->quantity, 0);
                $satuanValue = $detail->satuan->nama ?? 'PCS';
                $pdf->SetXY($qtyCol - 5, $currentY);
                $pdf->Cell(($satuanCol - $qtyCol - 2) + 3, $lineHeight, $qtyValue . ' ' . $satuanValue, 0, 0, 'R');

                $currentY += $lineHeight;
            }
            return $pdf;
        } catch (\Exception $e) {
            Log::error('FPDI Error: ' . $e->getMessage());
            Log::error('FPDI Error Stack: ' . $e->getTraceAsString());
            throw new \Exception('Gagal menggunakan template PDF: ' . $e->getMessage());
        }
    }

    /**
     * Fallback PDF generation using regular TCPDF
     */
    public function fallbackPDFGeneration($deliveryOrder)
    {
        // Create new PDF instance
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('ERP Sinar Surya');
        $pdf->SetAuthor('ERP System');
        $pdf->SetTitle('Surat Jalan - ' . $deliveryOrder->nomor);
        $pdf->SetSubject('Delivery Order');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 10);

        // Simple text-based layout
        $pdf->Cell(0, 10, 'SURAT JALAN', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->Cell(0, 8, 'Nomor: ' . $deliveryOrder->nomor, 0, 1, 'L');
        $pdf->Cell(0, 8, 'Tanggal: ' . ($deliveryOrder->tanggal_kirim ?
            \Carbon\Carbon::parse($deliveryOrder->tanggal_kirim)->format('d/m/Y') :
            \Carbon\Carbon::parse($deliveryOrder->created_at)->format('d/m/Y')), 0, 1, 'L');
        $pdf->Ln(5);

        // Customer info
        $pdf->Cell(0, 8, 'Kepada:', 0, 1, 'L');
        $pdf->Cell(0, 8, $deliveryOrder->customer->nama, 0, 1, 'L');
        if ($deliveryOrder->customer->alamat) {
            $pdf->MultiCell(0, 8, $deliveryOrder->customer->alamat, 0, 'L');
        }
        $pdf->Ln(10);

        // Items table header
        $pdf->Cell(10, 8, 'No', 1, 0, 'C');
        $pdf->Cell(80, 8, 'Nama Barang', 1, 0, 'C');
        $pdf->Cell(30, 8, 'Qty', 1, 0, 'C');
        $pdf->Cell(30, 8, 'Satuan', 1, 0, 'C');
        $pdf->Cell(40, 8, 'Keterangan', 1, 1, 'C');

        // Group bundle items first
        $bundleGroups = [];
        $nonBundleItems = [];

        foreach ($deliveryOrder->details as $detail) {
            if (!empty($detail->is_bundle_item) && !empty($detail->bundle_name)) {
                $bundleGroups[$detail->bundle_name][] = $detail;
            } else {
                $nonBundleItems[] = $detail;
            }
        }

        $itemNumber = 1;

        // Render Bundle Groups first
        foreach ($bundleGroups as $bundleName => $bundleItems) {
            // Bundle Header
            $pdf->Cell(10, 8, $itemNumber++, 1, 0, 'C');
            $pdf->SetFont('helvetica', 'B', 10); // Bold for bundle name
            $pdf->Cell(80, 8, $bundleName . ':', 1, 0, 'L');
            $pdf->SetFont('helvetica', '', 10); // Reset font
            $pdf->Cell(30, 8, '-', 1, 0, 'C');
            $pdf->Cell(30, 8, 'Paket', 1, 0, 'C');
            $pdf->Cell(40, 8, 'Bundle Package', 1, 1, 'L');

            // Bundle Items
            foreach ($bundleItems as $detail) {
                $pdf->Cell(10, 8, '', 1, 0, 'C'); // Empty number for bundle items
                $itemName = '  • ' . $detail->produk->nama; // Indent with bullet
                $pdf->Cell(80, 8, $itemName, 1, 0, 'L');
                $pdf->Cell(30, 8, number_format($detail->quantity, 0), 1, 0, 'R');
                $pdf->Cell(30, 8, $detail->satuan->nama ?? 'PCS', 1, 0, 'C');

                $keterangan = 'Item dari ' . $detail->bundle_name;
                $pdf->Cell(40, 8, $keterangan, 1, 1, 'L');
            }
        }

        // Render Non-Bundle Items
        foreach ($nonBundleItems as $detail) {
            $pdf->Cell(10, 8, $itemNumber++, 1, 0, 'C');
            $pdf->Cell(80, 8, $detail->produk->nama, 1, 0, 'L');
            $pdf->Cell(30, 8, number_format($detail->quantity, 0), 1, 0, 'R');
            $pdf->Cell(30, 8, $detail->satuan->nama ?? 'PCS', 1, 0, 'C');
            $pdf->Cell(40, 8, $detail->keterangan ?? '', 1, 1, 'L');
        }
        return $pdf;
    }

    /**
     * Get coordinates helper - for development/debugging
     */
    public function getCoordinatesHelper()
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        // Draw grid to help find coordinates
        $pdf->SetFont('helvetica', '', 8);

        // Vertical lines
        for ($x = 0; $x <= 210; $x += 10) {
            $pdf->Line($x, 0, $x, 297);
            if ($x % 20 == 0) {
                $pdf->SetXY($x, 5);
                $pdf->Cell(0, 0, $x, 0, 0, 'C');
            }
        }

        // Horizontal lines
        for ($y = 0; $y <= 297; $y += 10) {
            $pdf->Line(0, $y, 210, $y);
            if ($y % 20 == 0) {
                $pdf->SetXY(5, $y);
                $pdf->Cell(0, 0, $y, 0, 0, 'L');
            }
        }

        return $pdf;
    }

    /**
     * Test coordinate positioning with actual template
     */
    public function testCoordinatesWithTemplate($deliveryOrder)
    {
        $templatePath = public_path('pdf/Surat-Jalan.pdf');

        if (!file_exists($templatePath)) {
            throw new \Exception('Template PDF tidak ditemukan: ' . $templatePath);
        }

        try {
            // Create FPDI instance
            $pdf = new Fpdi();

            // Set PDF properties
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetAutoPageBreak(false);
            $pdf->SetMargins(0, 0, 0);

            // Import existing PDF template
            $pageCount = $pdf->setSourceFile($templatePath);
            $tplId = $pdf->importPage(1);
            $templateSize = $pdf->getTemplateSize($tplId);

            // Add a page with exact template dimensions
            $orientation = $templateSize['height'] > $templateSize['width'] ? 'P' : 'L';
            $pdf->AddPage($orientation, [$templateSize['width'], $templateSize['height']]);

            // Use template at full size
            $pdf->useTemplate($tplId, 0, 0, $templateSize['width'], $templateSize['height']);

            // Draw coordinate grid for testing
            $this->drawCoordinateGrid($pdf, $templateSize);

            // Set font for test markers
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetTextColor(255, 0, 0); // Red color for visibility

            // Test coordinates with actual positioning logic
            $testCoordinates = [
                // Nomor surat jalan - koordinat 50x30
                [
                    'x' => 50, // 50mm dari kiri
                    'y' => 30, // 30mm dari atas
                    'text' => 'NOMOR: ' . $deliveryOrder->nomor,
                    'label' => 'Nomor SJ'
                ],
                // Tanggal
                [
                    'x' => 50, // sama dengan nomor
                    'y' => 38, // 8mm di bawah nomor (30+8)
                    'text' => 'TANGGAL: ' . date('d/m/Y'),
                    'label' => 'Tanggal'
                ],
                // Customer name
                [
                    'x' => $templateSize['width'] * 0.12,
                    'y' => $templateSize['height'] * 0.35,
                    'text' => 'CUSTOMER: ' . $deliveryOrder->customer->nama,
                    'label' => 'Nama Customer'
                ],
                // Customer address
                [
                    'x' => $templateSize['width'] * 0.12,
                    'y' => $templateSize['height'] * 0.35 + 6,
                    'text' => 'ALAMAT: ' . ($deliveryOrder->customer->alamat ?? 'N/A'),
                    'label' => 'Alamat'
                ],
                // Items start
                [
                    'x' => $templateSize['width'] * 0.08,
                    'y' => $templateSize['height'] * 0.55,
                    'text' => 'ITEMS TABLE START',
                    'label' => 'Mulai Items'
                ],
                // Signature area
                [
                    'x' => $templateSize['width'] * 0.12,
                    'y' => $templateSize['height'] * 0.9,
                    'text' => 'SIGNATURE AREA',
                    'label' => 'Area Tanda Tangan'
                ],
            ];

            foreach ($testCoordinates as $coord) {
                // Place test text
                $pdf->SetXY($coord['x'], $coord['y']);
                $pdf->Cell(0, 0, $coord['text'], 0, 0, 'L');

                // Add crosshair marker at exact position
                $this->drawCrosshair($pdf, $coord['x'], $coord['y'], $coord['label']);
            }

            // Add template info as overlay
            $pdf->SetTextColor(0, 0, 255); // Blue for info
            $pdf->SetXY(5, 5);
            $pdf->Cell(0, 0, sprintf('Template: %.1f x %.1f mm', $templateSize['width'], $templateSize['height']), 0, 0, 'L');

            return $pdf;
        } catch (\Exception $e) {
            throw new \Exception('Error testing coordinates: ' . $e->getMessage());
        }
    }

    /**
     * Draw coordinate grid on PDF for testing
     */
    private function drawCoordinateGrid($pdf, $templateSize)
    {
        $pdf->SetDrawColor(200, 200, 200); // Light gray
        $pdf->SetTextColor(150, 150, 150);
        $pdf->SetFont('helvetica', '', 6);

        // Vertical lines every 10mm
        for ($x = 0; $x <= $templateSize['width']; $x += 10) {
            $pdf->Line($x, 0, $x, $templateSize['height']);
            if ($x % 20 == 0 && $x > 0) {
                $pdf->SetXY($x - 5, 2);
                $pdf->Cell(10, 0, $x, 0, 0, 'C');
            }
        }

        // Horizontal lines every 10mm
        for ($y = 0; $y <= $templateSize['height']; $y += 10) {
            $pdf->Line(0, $y, $templateSize['width'], $y);
            if ($y % 20 == 0 && $y > 0) {
                $pdf->SetXY(2, $y - 1);
                $pdf->Cell(0, 0, $y, 0, 0, 'L');
            }
        }
    }

    /**
     * Draw crosshair marker at specific coordinates
     */
    private function drawCrosshair($pdf, $x, $y, $label = '')
    {
        $pdf->SetDrawColor(255, 0, 0); // Red
        $size = 2;

        // Draw crosshair
        $pdf->Line($x - $size, $y, $x + $size, $y); // Horizontal line
        $pdf->Line($x, $y - $size, $x, $y + $size); // Vertical line

        // Draw small circle
        $pdf->Circle($x, $y, 0.5, 0, 360, 'D');

        // Add label if provided
        if ($label) {
            $pdf->SetFont('helvetica', '', 6);
            $pdf->SetXY($x + 3, $y - 1);
            $pdf->Cell(0, 0, $label, 0, 0, 'L');
        }
    }

    /**
     * Get template PDF information
     */
    public function getTemplateInfo()
    {
        $templatePath = public_path('pdf/Surat-Jalan.pdf');

        if (!file_exists($templatePath)) {
            throw new \Exception('Template PDF tidak ditemukan: ' . $templatePath);
        }

        try {
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($templatePath);
            $tplId = $pdf->importPage(1);
            $templateSize = $pdf->getTemplateSize($tplId);

            return [
                'file_exists' => true,
                'file_path' => $templatePath,
                'file_size' => filesize($templatePath),
                'file_size_readable' => $this->formatBytes(filesize($templatePath)),
                'page_count' => $pageCount,
                'template_width' => $templateSize['width'],
                'template_height' => $templateSize['height'],
                'template_orientation' => $templateSize['height'] > $templateSize['width'] ? 'Portrait' : 'Landscape',
                'calculated_positions' => [
                    'nomor_x' => 50, // 50mm dari kiri
                    'nomor_y' => 30, // 30mm dari atas
                    'tanggal_x' => 50, // sama dengan nomor
                    'tanggal_y' => 38, // 8mm di bawah nomor (30+8)
                    'customer_x' => $templateSize['width'] * 0.12,
                    'customer_y' => $templateSize['height'] * 0.35,
                    'items_start_y' => $templateSize['height'] * 0.55,
                    'signature_y' => $templateSize['height'] * 0.9,
                ]
            ];
        } catch (\Exception $e) {
            return [
                'file_exists' => true,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($size, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB');
        for ($i = 0; $size >= 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        return round($size, $precision) . ' ' . $units[$i];
    }

    /**
     * Generate PDF with fine-tuned coordinates for specific template adjustments
     */
    public function generateWithCustomCoordinates($deliveryOrder, $coordinates = [])
    {
        $templatePath = public_path('pdf/Surat-Jalan.pdf');

        if (!file_exists($templatePath)) {
            throw new \Exception('Template PDF tidak ditemukan: ' . $templatePath);
        }

        try {
            // Create FPDI instance
            $pdf = new Fpdi();

            // Set PDF properties
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetAutoPageBreak(false);
            $pdf->SetMargins(0, 0, 0);

            // Import template
            $pageCount = $pdf->setSourceFile($templatePath);
            $tplId = $pdf->importPage(1);
            $templateSize = $pdf->getTemplateSize($tplId);

            // Add page with exact template size
            $orientation = $templateSize['height'] > $templateSize['width'] ? 'P' : 'L';
            $pdf->AddPage($orientation, [$templateSize['width'], $templateSize['height']]);

            // Use template at full size
            $pdf->useTemplate($tplId, 0, 0, $templateSize['width'], $templateSize['height']);

            // Set font
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(0, 0, 0);

            // Use custom coordinates if provided, otherwise use defaults
            $defaultCoordinates = [
                'nomor_x' => 50, // 50mm dari kiri
                'nomor_y' => 30, // 30mm dari atas
                'tanggal_x' => 50, // sama dengan nomor
                'tanggal_y' => 38, // 8mm di bawah nomor (30+8)
                'customer_x' => $templateSize['width'] * 0.12,
                'customer_y' => $templateSize['height'] * 0.35,
                'alamat_x' => $templateSize['width'] * 0.12,
                'alamat_y' => $templateSize['height'] * 0.35 + 6,
                'telepon_x' => $templateSize['width'] * 0.12,
                'telepon_y' => $templateSize['height'] * 0.35 + 18,
                'items_no_x' => $templateSize['width'] * 0.08,
                'items_nama_x' => $templateSize['width'] * 0.18,
                'items_qty_x' => $templateSize['width'] * 0.68,
                'items_satuan_x' => $templateSize['width'] * 0.78,
                'items_start_y' => $templateSize['height'] * 0.55,
                'items_line_height' => 5.5,
            ];

            $coords = array_merge($defaultCoordinates, $coordinates);

            // Fill data using coordinates
            // Nomor surat jalan
            $pdf->SetXY($coords['nomor_x'], $coords['nomor_y']);
            $pdf->Cell(0, 0, $deliveryOrder->nomor, 0, 0, 'L');

            // Tanggal
            $pdf->SetXY($coords['tanggal_x'], $coords['tanggal_y']);
            $pdf->Cell(0, 0, $deliveryOrder->tanggal_kirim ?
                \Carbon\Carbon::parse($deliveryOrder->tanggal_kirim)->format('d/m/Y') :
                \Carbon\Carbon::parse($deliveryOrder->created_at)->format('d/m/Y'), 0, 0, 'L');

            // Customer name
            $pdf->SetXY($coords['customer_x'], $coords['customer_y']);
            $pdf->Cell(0, 0, $deliveryOrder->customer->nama, 0, 0, 'L');

            // Customer address
            if ($deliveryOrder->customer->alamat) {
                $pdf->SetXY($coords['alamat_x'], $coords['alamat_y']);
                $maxWidth = $templateSize['width'] * 0.6;
                $pdf->MultiCell($maxWidth, 4, $deliveryOrder->customer->alamat, 0, 'L');
            }

            // Customer phone
            if ($deliveryOrder->customer->telepon) {
                $pdf->SetXY($coords['telepon_x'], $coords['telepon_y']);
                $pdf->Cell(0, 0, 'Telp: ' . $deliveryOrder->customer->telepon, 0, 0, 'L');
            }

            // Items
            $currentY = $coords['items_start_y'];
            $maxItemsY = $templateSize['height'] * 0.85;

            // Group bundle items first
            $bundleGroups = [];
            $nonBundleItems = [];

            foreach ($deliveryOrder->details as $detail) {
                if (!empty($detail->is_bundle_item) && !empty($detail->bundle_name)) {
                    $bundleGroups[$detail->bundle_name][] = $detail;
                } else {
                    $nonBundleItems[] = $detail;
                }
            }

            $itemNumber = 1;

            // Render Bundle Groups first
            foreach ($bundleGroups as $bundleName => $bundleItems) {
                if ($currentY > $maxItemsY) break;

                // Bundle Header
                $pdf->SetXY($coords['items_no_x'], $currentY);
                $pdf->Cell(12, $coords['items_line_height'], $itemNumber++, 0, 0, 'C');

                // Bundle name in bold
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->SetXY($coords['items_nama_x'], $currentY);
                $pdf->Cell(0, $coords['items_line_height'], $bundleName . ':', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 9); // Reset font

                // Quantity column shows "Paket"
                $pdf->SetXY($coords['items_qty_x'], $currentY);
                $pdf->Cell(15, $coords['items_line_height'], '-', 0, 0, 'R');

                // Satuan column
                $pdf->SetXY($coords['items_satuan_x'], $currentY);
                $pdf->Cell(20, $coords['items_line_height'], 'Paket', 0, 0, 'L');

                $currentY += $coords['items_line_height'];

                // Bundle Items
                foreach ($bundleItems as $detail) {
                    if ($currentY > $maxItemsY) break;

                    // Empty number for bundle items
                    $pdf->SetXY($coords['items_no_x'], $currentY);
                    $pdf->Cell(12, $coords['items_line_height'], '', 0, 0, 'C');

                    // Item name with indent
                    $itemName = '  • ' . $detail->produk->nama;
                    $itemName = strlen($itemName) > 35 ? substr($itemName, 0, 32) . '...' : $itemName;

                    $pdf->SetXY($coords['items_nama_x'], $currentY);
                    $pdf->Cell(0, $coords['items_line_height'], $itemName, 0, 0, 'L');

                    // Quantity
                    $pdf->SetXY($coords['items_qty_x'], $currentY);
                    $pdf->Cell(15, $coords['items_line_height'], number_format($detail->quantity, 0), 0, 0, 'R');

                    // Satuan
                    $pdf->SetXY($coords['items_satuan_x'], $currentY);
                    $pdf->Cell(20, $coords['items_line_height'], $detail->satuan->nama ?? 'PCS', 0, 0, 'L');

                    $currentY += $coords['items_line_height'];
                }
            }

            // Render Non-Bundle Items
            foreach ($nonBundleItems as $detail) {
                if ($currentY > $maxItemsY) break;

                // No urut
                $pdf->SetXY($coords['items_no_x'], $currentY);
                $pdf->Cell(12, $coords['items_line_height'], $itemNumber++, 0, 0, 'C');

                // Nama produk
                $namaProduk = strlen($detail->produk->nama) > 35 ?
                    substr($detail->produk->nama, 0, 32) . '...' :
                    $detail->produk->nama;

                $pdf->SetXY($coords['items_nama_x'], $currentY);
                $pdf->Cell(0, $coords['items_line_height'], $namaProduk, 0, 0, 'L');

                // Quantity
                $pdf->SetXY($coords['items_qty_x'], $currentY);
                $pdf->Cell(15, $coords['items_line_height'], number_format($detail->quantity, 0), 0, 0, 'R');

                // Satuan
                $pdf->SetXY($coords['items_satuan_x'], $currentY);
                $pdf->Cell(20, $coords['items_line_height'], $detail->satuan->nama ?? 'PCS', 0, 0, 'L');

                $currentY += $coords['items_line_height'];
            }            // Catatan jika ada
            if ($deliveryOrder->catatan && $currentY < $maxItemsY) {
                $catatanY = $currentY + 8;
                if ($catatanY < $templateSize['height'] * 0.9) {
                    $pdf->SetXY($coords['customer_x'], $catatanY);
                    $maxCatatanWidth = $templateSize['width'] * 0.7;
                    $pdf->MultiCell($maxCatatanWidth, 4, 'Catatan: ' . $deliveryOrder->catatan, 0, 'L');
                }
            }

            return $pdf;
        } catch (\Exception $e) {
            Log::error('Custom coordinates PDF generation error: ' . $e->getMessage());
            throw new \Exception('Gagal membuat PDF dengan koordinat custom: ' . $e->getMessage());
        }
    }
}
