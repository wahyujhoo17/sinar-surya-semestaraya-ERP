<?php

namespace App\Services;

use TCPDF;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\Log;

class PDFDeliveryOrderSinarSuryaTemplate
{
    /**
     * Fill delivery order template PDF with data using FPDI untuk Sinar Surya
     */
    public function fillDeliveryOrderTemplate($deliveryOrder)
    {

        // Selalu gunakan template untuk Sinar Surya
        $useTemplate = true; // Selalu aktif untuk Sinar Surya
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
            $nomorY = 42.5; // Moved up by 3 units from 44.5
            $pdf->SetXY($nomorX, $nomorY);
            $pdf->Cell(40, 0, $deliveryOrder->nomor, 0, 0, 'L');

            // if (!empty($deliveryOrder->user) && !empty($deliveryOrder->user->name)) {
            //     $userX = 130;
            //     $userY = 200;
            //     $pdf->SetFont('helvetica', '', 8);
            //     $pdf->SetXY($userX, $userY);
            //     $pdf->Cell(60, 0, $deliveryOrder->user->name, 0, 0, 'L');
            // }

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
            $customerY = 42;
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
            $itemsStartY = 76; // Moved up by 3 units from 79
            $lineHeight = 6;
            $currentY = $itemsStartY;
            $maxItemsY = 170;

            // Kolom tabel: No | Nama Barang | Kode | Qty | Satuan
            $noCol = 9; // Moved left by 2 units from 11
            $namaCol = 23; // Moved left by 1 unit from 24
            $kodeCol = 101; // Moved left by 2 units from 103
            $qtyCol = 120; // Moved right by 6 units total from 114 (3+3)
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
            Log::error('FPDI Delivery Order Sinar Surya Error: ' . $e->getMessage());
            Log::error('FPDI Delivery Order Sinar Surya Error Stack: ' . $e->getTraceAsString());
            throw new \Exception('Gagal menggunakan template PDF Delivery Order Sinar Surya: ' . $e->getMessage());
        }
    }
}
