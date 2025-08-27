<?php

namespace App\Services;

use TCPDF;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\Log;

class PDFInvoiceTamplate
{
    /**
     * Generate Invoice PDF (mirip Delivery Order, bisa pakai template atau hanya text)
     */
    public function fillInvoiceTemplate($invoice, $namaDirektur = '')
    {
        $useTemplate = config('app.print_with_template', false); // default true, bisa diatur di config/app.php
        $templatePath = public_path('pdf/Invoice-New.pdf');

        // XY = 214x 163
        $customWidth = 214;
        $customHeight = 163;

        try {
            $pdf = new Fpdi();
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetAutoPageBreak(false, 0);
            $pdf->SetMargins(0, 0, 0, true); // no margin at all, full page

            $orientation = $customHeight > $customWidth ? 'P' : 'L';
            $pdf->AddPage($orientation, [$customWidth, $customHeight]);

            if ($useTemplate && file_exists($templatePath)) {
                $pageCount = $pdf->setSourceFile($templatePath);
                $tplId = $pdf->importPage(1);
                $pdf->useTemplate($tplId, 0, 0, $customWidth, $customHeight);
            }

            $pdf->SetCreator('ERP Sinar Surya');
            $pdf->SetAuthor('ERP System');
            $pdf->SetTitle('Invoice - ' . $invoice->nomor);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(0, 0, 0);

            $pdf->SetFont('helvetica', '', 10);
            $pdf->SetXY(94, 37.5);
            $pdf->Cell(0, 0, $invoice->nomor, 0, 0, 'L');
            $pdf->SetXY(154, 13.5);
            $pdf->Cell(0, 0, (\Carbon\Carbon::parse($invoice->tanggal)->format('d/m/Y')), 0, 0, 'L');

            // --- Customer ---
            $customerX = 10;
            $customerY = 37;
            $maxCustomerWidth = 70;
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetXY($customerX, $customerY);
            $customerName = $invoice->customer->company ?? $invoice->customer->nama;
            // Hitung tinggi nama customer (bisa lebih dari 1 baris)
            $customerNameHeight = $pdf->getStringHeight($maxCustomerWidth, $customerName);
            $pdf->MultiCell($maxCustomerWidth, 5, $customerName, 0, 'L');

            // Alamat mulai setelah nama customer (dinamis)
            $alamatY = $customerY + $customerNameHeight + 1; // +1 agar tidak terlalu rapat
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetXY($customerX, $alamatY);
            $alamatText = $invoice->customer->alamat ?? '-';
            $pdf->MultiCell($maxCustomerWidth, 4, $alamatText, 0, 'L');

            // DETAIL INV
            $maxDetilWidth = 45;
            $salesOrderX = 155;
            $salesOrderY = 32.8;
            // NOMOR PO
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetXY($salesOrderX, $salesOrderY);
            $nomorPOText = $invoice->salesOrder->nomor_po ?? '-';
            $pdf->MultiCell($maxDetilWidth, 4, $nomorPOText, 0, 'L');
            // NOMOR SJ
            $pdf->SetXY($salesOrderX, $salesOrderY + 5);
            $nomorSJ = '-';
            if (!empty($invoice->salesOrder->deliveryOrders) && count($invoice->salesOrder->deliveryOrders) > 0) {
                $deliveryOrders = collect($invoice->salesOrder->deliveryOrders);
                $lastDO = $deliveryOrders->last();
                $nomorSJ = $lastDO && isset($lastDO['nomor']) ? $lastDO['nomor'] : (isset($lastDO->nomor) ? $lastDO->nomor : '-');
            }
            $pdf->MultiCell($maxDetilWidth, 4, $nomorSJ, 0, 'L');

            //Pembayaran
            $pdf->SetXY($salesOrderX, $salesOrderY + 11.5);
            $nomorPembayaran = '-';
            if (!empty($invoice->SalesOrder)) {
                $nomorPembayaran = $invoice->SalesOrder->terms_pembayaran_hari ?? '-';
            }
            $pdf->MultiCell($maxDetilWidth, 4, $nomorPembayaran, 0, align: 'L');

            //Sales
            $pdf->SetXY($salesOrderX, $salesOrderY + 16.8);
            $salesName = $invoice->salesOrder->customer->sales->name ?? '-';
            $pdf->MultiCell($maxDetilWidth, 4, $salesName, 0, 'L');



            // --- Items Table Header ---
            $itemsStartY = 61;
            $lineHeight = 6;

            // --- Items ---
            $pdf->SetFont('helvetica', '', 8);
            $currentY = $itemsStartY + $lineHeight;
            foreach ($invoice->details as $index => $detail) {
                if ($currentY > 150) break;
                $pdf->SetXY(6, $currentY);
                $pdf->Cell(11.5, $lineHeight, ($index + 1), 0, 0, 'C');
                $pdf->Cell(35, $lineHeight, $detail->produk->kode ?? '-', 0, 0, 'L');

                // Nama produk dengan max width dan wrap, hitung tinggi baris
                $maxNamaWidth = 56.5;
                $maxNamaLength = 38;
                $namaProduk = $detail->produk->nama ?? '-';
                if (mb_strlen($namaProduk) > $maxNamaLength) {
                    $namaProduk = mb_substr($namaProduk, 0, $maxNamaLength - 3) . '...';
                }
                $xNama = $pdf->GetX();
                $yNama = $pdf->GetY();
                // Hitung tinggi MultiCell tanpa mencetak
                $namaHeight = $pdf->getStringHeight($maxNamaWidth, $namaProduk);
                // Cetak MultiCell nama produk (parameter sesuai dokumentasi TCPDF)
                $pdf->MultiCell($maxNamaWidth, $namaHeight, $namaProduk, 0, 'L', false, 0);
                // Kembali ke baris awal, setelah kolom nama
                $pdf->SetXY($xNama + $maxNamaWidth, $yNama);
                $pdf->Cell(25, $namaHeight, number_format($detail->quantity, 0, ',', '.'), 0, 0, 'C');
                $pdf->Cell(31, $namaHeight, 'Rp ' . number_format($detail->harga, 0, ',', '.'), 0, 0, 'C');
                $pdf->Cell(8, $namaHeight, rtrim(rtrim(number_format($detail->diskon_persen, 2, '.', ''), '0'), '.') . '%', 0, 0, 'C');
                $pdf->Cell(29, $namaHeight, 'Rp ' . number_format($detail->subtotal, 0, ',', '.'), 0, 1, 'C');
                $currentY += $namaHeight;
            }

            // --- Summary (dari bawah ke atas) ---
            $yTotal = $customHeight - 50; // posisi paling bawah (atur sesuai kebutuhan layout)
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(137, $yTotal);
            $pdf->Cell(30, 6, 'Total', 0, 0, 'L');
            $pdf->Cell(25, 6, 'Rp ' . number_format($invoice->total, 0, ',', '.'), 0, 1, 'R');

            $y = $yTotal - 6;
            if ($invoice->ongkos_kirim > 0) {
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetXY(137, $y);
                $pdf->Cell(30, 6, 'Ongkos Kirim', 0, 0, 'L');
                $pdf->Cell(25, 6, 'Rp ' . number_format($invoice->ongkos_kirim, 0, ',', '.'), 0, 1, 'R');
                $y -= 6;
            }
            if ($invoice->ppn > 0) {
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetXY(137, $y);
                $pdf->Cell(30, 6, 'PPN 11%', 0, 0, 'L');
                $pdf->Cell(25, 6, 'Rp ' . number_format($invoice->ppn, 0, ',', '.'), 0, 1, 'R');
                $y -= 6;
            }
            if ($invoice->diskon_nominal > 0) {
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetXY(137, $y);
                $pdf->Cell(30, 6, 'Diskon', 0, 0, 'L');
                $pdf->Cell(25, 6, 'Rp ' . number_format($invoice->diskon_nominal, 0, ',', '.'), 0, 1, 'R');
                $y -= 6;
            }
            // Subtotal selalu tampil
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetXY(137, $y);
            $pdf->Cell(30, 6, 'Subtotal', 0, 0, 'L');
            $pdf->Cell(25, 6, 'Rp ' . number_format($invoice->subtotal, 0, ',', '.'), 0, 1, 'R');

            // Tambahkan garis horizontal sebelum total
            $pdf->SetDrawColor(0, 0, 0); // warna hitam
            $pdf->SetLineWidth(0.3);
            $pdf->Line(137, $yTotal, 198, $yTotal);

            // Untuk posisi catatan, simpan $summaryY agar tidak error
            $summaryY = $yTotal;

            // TERBILANG

            $pdf->SetXY(10, 123);
            // Konversi total ke terbilang (pastikan helper terbilang tersedia di project)
            $Terbilang = function_exists('terbilang') ? ucwords(terbilang((int) $invoice->total) . ' Rupiah ') : '-';
            $pdf->SetFont('helvetica', 'BI', 9); // Set font bold italic
            $pdf->MultiCell(125, 4, $Terbilang, 0, 'L');
            $pdf->SetFont('helvetica', '', 9); // Kembalikan font normal

            // --- Catatan ---
            $nomorINV = $invoice->nomor;
            if (preg_match("/INV-(\\d{8}-\\d+)/", $invoice->nomor, $matches)) {
                $nomorINV = $matches[1];
            }
            // Ganti <br> dengan new line agar MultiCell TCPDF bisa menampilkan baris baru
            $CatatanText = "PERHATIAN:\nMohon Dicantumkan NO. $nomorINV\nPada Berita Transfer.";
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetXY(90, 137);
            $pdf->MultiCell(120, 4,  $CatatanText, 0, 'L');


            // Garis bawah untuk tanda tangan
            $pdf->SetXY(157, $customHeight - 15);
            $pdf->Cell(40, 0, '', 'T', 2, 'C');
            // Nama direktur (ganti sesuai kebutuhan)
            $pdf->SetXY(157, $customHeight - 15);
            $namaDirektur = trim($namaDirektur) !== '' ? $namaDirektur : 'Ir. Arief Rahman Hamid';
            $pdf->Cell(40, 6, $namaDirektur, 0, 2, 'C');

            return $pdf;
        } catch (\Exception $e) {
            Log::error('FPDI Invoice Error: ' . $e->getMessage());
            throw new \Exception('Gagal membuat PDF Invoice: ' . $e->getMessage());
        }
    }
}
