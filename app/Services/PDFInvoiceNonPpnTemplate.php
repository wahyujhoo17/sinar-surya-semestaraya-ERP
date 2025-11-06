<?php

namespace App\Services;

use TCPDF;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\Log;

class PDFInvoiceNonPpnTemplate
{
    /**
     * Generate Invoice PDF untuk template Non PPN
     */
    public function fillInvoiceTemplate($invoice, $namaDirektur = '', $bankAccounts = null, $primaryBank = null)
    {
        $useTemplate = config('app.print_with_template', false); // default true, bisa diatur di config/app.php
        $templatePath = public_path('pdf/Invoice Non PPn.pdf');

        // XY = 214x 154
        $customWidth = 214;
        $customHeight = 154;

        try {
            $pdf = new Fpdi();
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetAutoPageBreak(false, 0);
            $pdf->SetMargins(0, 0, 0, false); // no margin at all, full page

            $orientation = $customHeight > $customWidth ? 'P' : 'L';
            $pdf->AddPage($orientation, [$customWidth, $customHeight]);

            if ($useTemplate && file_exists($templatePath)) {
                $pageCount = $pdf->setSourceFile($templatePath);
                $tplId = $pdf->importPage(1);
                $pdf->useTemplate($tplId, 0, 0, $customWidth, $customHeight);
            }

            $pdf->SetCreator('ERP Sinar Surya');
            $pdf->SetAuthor('ERP System');
            $pdf->SetTitle('Invoice Non PPN - ' . $invoice->nomor);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(0, 0, 0);

            $pdf->SetFont('helvetica', '', 10);
            $pdf->SetXY(100, 31);
            $pdf->Cell(0, 0, $invoice->nomor, 0, 0, 'L');
            $pdf->SetXY(163, 9);
            $pdf->Cell(0, 0, (\Carbon\Carbon::parse($invoice->tanggal)->format('d/m/Y')), 0, 0, 'L');

            // --- Customer ---
            $customerX = 12;
            $customerY = 29;
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
            $salesOrderX = 160;
            $salesOrderY = 19;
            // NOMOR PO
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetXY($salesOrderX, $salesOrderY);
            $nomorPOText = $invoice->salesOrder->nomor_po ?? '-';
            $pdf->MultiCell($maxDetilWidth, 4, $nomorPOText, 0, 'L');

            //Pembayaran
            $pdf->SetXY($salesOrderX, $salesOrderY + 10);
            $nomorPembayaran = '-';
            if (!empty($invoice->SalesOrder)) {
                $nomorPembayaran = $invoice->SalesOrder->terms_pembayaran_hari ?? '-';
            }
            $pdf->MultiCell($maxDetilWidth, 4, $nomorPembayaran, 0, align: 'L');

            //Sales
            $pdf->SetXY($salesOrderX, $salesOrderY + 20);
            $salesName = $invoice->salesOrder->customer->sales->name ?? '-';
            $pdf->MultiCell($maxDetilWidth, 4, $salesName, 0, 'L');



            // --- Items Table Header ---
            $itemsStartY = 54;
            $lineHeight = 5.5;
            $maxItemsPerPage = 7; // Maksimal 7 item per halaman

            // Fixed summary position: reserve space at bottom so summary is always at the same Y
            $yTotal = $customHeight - 40; // posisi paling bawah (fix), disesuaikan untuk tinggi 154, digeser ke atas 6mm
            $summaryReserveLines = 5; // jumlah baris maksimal yang dipakai summary (Total, Ongkos Kirim, Diskon, Subtotal)
            $itemsMaxY = $yTotal - ($summaryReserveLines * 1.8) - 2; // sisa ruang untuk items

            // Print table header for KODE BARANG
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(22.5, $itemsStartY - 2); // Position for KODE BARANG column, moved up by 2 and right by 1
            $pdf->Cell(35, $lineHeight, 'Kode Barang', 0, 0, 'L');

            // --- Items ---
            $pdf->SetFont('helvetica', '', 8);
            $currentY = $itemsStartY + $lineHeight;
            $itemCount = 0;
            $pageNumber = 1;
            $totalItems = count($invoice->details);

            // Fungsi helper untuk menampilkan informasi pembayaran
            $printPaymentInfo = function () use ($pdf, $bankAccounts, $primaryBank, $customHeight) {
                $paymentInfoX = 41;
                $paymentInfoY = 127;

                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetXY($paymentInfoX, $paymentInfoY);
                $pdf->Cell(0, 4, 'Informasi Pembayaran:', 0, 1, 'L');

                $pdf->SetFont('helvetica', '', 7.5);
                $currentPaymentY = $paymentInfoY + 4;

                // Tentukan bank yang akan ditampilkan
                $displayBank = null;
                if ($primaryBank) {
                    $displayBank = $primaryBank;
                } elseif ($bankAccounts && $bankAccounts->isNotEmpty()) {
                    $displayBank = $bankAccounts->first();
                }

                if ($displayBank) {
                    // Tampilkan primary/first bank
                    $pdf->SetXY($paymentInfoX, $currentPaymentY);
                    $pdf->MultiCell(70, 3, 'Bank: ' . ($displayBank->nama_bank ?? 'Mandiri'), 0, 'L');
                    $currentPaymentY = $pdf->GetY();

                    $pdf->SetXY($paymentInfoX, $currentPaymentY);
                    $pdf->MultiCell(70, 3, 'No. Rekening: ' . ($displayBank->nomor_rekening ?? '006.000.301.9563'), 0, 'L');
                    $currentPaymentY = $pdf->GetY();

                    $pdf->SetXY($paymentInfoX, $currentPaymentY);
                    $pdf->MultiCell(70, 3, 'Atas Nama: ' . ($displayBank->atas_nama ?? setting('company_name', 'PT. Sinar Surya Semestaraya')), 0, 'L');
                    $currentPaymentY = $pdf->GetY();

                    // Tampilkan bank alternatif jika ada
                    if ($bankAccounts && $bankAccounts->count() > 1) {
                        $alternativeBanks = $bankAccounts->filter(function ($bank) use ($displayBank) {
                            return $bank->id !== $displayBank->id;
                        });

                        if ($alternativeBanks->isNotEmpty()) {
                            $currentPaymentY += 1;
                            $pdf->SetFont('helvetica', 'B', 7);
                            $pdf->SetXY($paymentInfoX, $currentPaymentY);
                            $pdf->Cell(0, 3, 'Bank Alternatif:', 0, 1, 'L');
                            $currentPaymentY = $pdf->GetY();

                            $pdf->SetFont('helvetica', '', 7);
                            foreach ($alternativeBanks as $altBank) {
                                $pdf->SetXY($paymentInfoX, $currentPaymentY);
                                $altBankText = $altBank->nama_bank . ': ' . $altBank->nomor_rekening .
                                    ' (a.n. ' . $altBank->atas_nama . ')';
                                $pdf->MultiCell(70, 3, $altBankText, 0, 'L');
                                $currentPaymentY = $pdf->GetY();
                            }
                        }
                    }
                } else {
                    // Fallback ke hardcoded data jika tidak ada bank settings
                    $pdf->SetXY($paymentInfoX, $currentPaymentY);
                    $pdf->MultiCell(70, 3, 'Bank: ' . setting('company_bank_name', 'Mandiri'), 0, 'L');
                    $currentPaymentY = $pdf->GetY();

                    $pdf->SetXY($paymentInfoX, $currentPaymentY);
                    $pdf->MultiCell(70, 3, 'No. Rekening: ' . setting('company_bank_account', '006.000.301.9563'), 0, 'L');
                    $currentPaymentY = $pdf->GetY();

                    $pdf->SetXY($paymentInfoX, $currentPaymentY);
                    $pdf->MultiCell(70, 3, 'Atas Nama: ' . setting('company_name', 'PT. Sinar Surya Semestaraya'), 0, 'L');
                }
            };

            // Tampilkan informasi pembayaran di halaman pertama
            $printPaymentInfo();

            foreach ($invoice->details as $index => $detail) {
                // Cek apakah perlu halaman baru
                if ($itemCount >= $maxItemsPerPage && $index < $totalItems) {
                    // Tambah halaman baru
                    $pageNumber++;
                    $pdf->AddPage($orientation, [$customWidth, $customHeight]);

                    // Terapkan template jika ada
                    if ($useTemplate && file_exists($templatePath)) {
                        $tplId = $pdf->importPage(1);
                        $pdf->useTemplate($tplId, 0, 0, $customWidth, $customHeight);
                    }

                    // Reset posisi dan counter
                    $currentY = $itemsStartY + $lineHeight;
                    $itemCount = 0;

                    // Cetak ulang header untuk halaman baru
                    $pdf->SetFont('helvetica', '', 10);
                    $pdf->SetXY(100, 31);
                    $pdf->Cell(0, 0, $invoice->nomor . ' (Hal. ' . $pageNumber . ')', 0, 0, 'L');
                    $pdf->SetXY(163, 9);
                    $pdf->Cell(0, 0, (\Carbon\Carbon::parse($invoice->tanggal)->format('d/m/Y')), 0, 0, 'L');

                    // Customer info
                    $pdf->SetFont('helvetica', 'B', 9);
                    $pdf->SetXY($customerX, $customerY);
                    $customerNameHeight = $pdf->getStringHeight($maxCustomerWidth, $customerName);
                    $pdf->MultiCell($maxCustomerWidth, 5, $customerName, 0, 'L');

                    $alamatY = $customerY + $customerNameHeight + 1;
                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->SetXY($customerX, $alamatY);
                    $pdf->MultiCell($maxCustomerWidth, 4, $alamatText, 0, 'L');

                    // DETAIL INV
                    $pdf->SetFont('helvetica', '', 9);
                    $pdf->SetXY($salesOrderX, $salesOrderY);
                    $pdf->MultiCell($maxDetilWidth, 4, $nomorPOText, 0, 'L');

                    $pdf->SetXY($salesOrderX, $salesOrderY + 10);
                    $pdf->MultiCell($maxDetilWidth, 4, $nomorPembayaran, 0, align: 'L');

                    $pdf->SetXY($salesOrderX, $salesOrderY + 20);
                    $pdf->MultiCell($maxDetilWidth, 4, $salesName, 0, 'L');

                    // Table header
                    $pdf->SetFont('helvetica', 'B', 8);
                    $pdf->SetXY(22.5, $itemsStartY - 2);
                    $pdf->Cell(35, $lineHeight, 'Kode Barang', 0, 0, 'L');
                    $pdf->SetFont('helvetica', '', 8);

                    // Tampilkan informasi pembayaran di halaman baru
                    $printPaymentInfo();
                }

                if ($currentY > $itemsMaxY) break;

                // Hitung tinggi baris berdasarkan nama produk terlebih dahulu
                $maxNamaWidth = 56.5;
                $maxNamaLength = 38;
                $namaProduk = $detail->produk->nama ?? '-';
                if (mb_strlen($namaProduk) > $maxNamaLength) {
                    $namaProduk = mb_substr($namaProduk, 0, $maxNamaLength - 3) . '...';
                }
                $namaHeight = $pdf->getStringHeight($maxNamaWidth, $namaProduk);

                // Gunakan tinggi yang sama untuk semua kolom dalam baris ini
                // Geser nomor dan kode 3mm ke kanan dari origin sebelumnya (startX 8 -> 11)
                $pdf->SetXY(11, $currentY);
                $pdf->Cell(11.5, $namaHeight, ($index + 1), 0, 0, 'C');
                $pdf->Cell(35, $namaHeight, $detail->produk->kode ?? '-', 0, 0, 'L');

                // Nama produk tetap mulai pada X = 54.5 (agar kolom lain tidak terpengaruh)
                $xNama = 54.5;
                $yNama = $pdf->GetY();
                $pdf->SetXY($xNama, $yNama);
                // Cetak MultiCell nama produk (parameter sesuai dokumentasi TCPDF)
                $pdf->MultiCell($maxNamaWidth, $namaHeight, $namaProduk, 0, 'L', false, 0);
                // Kembali ke baris awal, setelah kolom nama
                // Use explicit X positions so we can shift HARGA 1 unit left without affecting other columns
                $baseX = $xNama + $maxNamaWidth; // original starting X for the first numeric column

                // QTY stays at baseX with width 28
                $pdf->SetXY($baseX, $yNama);
                $pdf->Cell(28, $namaHeight, number_format($detail->quantity, 0, ',', '.'), 0, 0, 'C');

                // HARGA should be 1 unit to the left of its original position (original was baseX + 28)
                $hargaX = $baseX + 27; // move left by 1
                $pdf->SetXY($hargaX, $yNama);
                $pdf->Cell(29, $namaHeight, number_format($detail->harga, 0, ',', '.'), 0, 0, 'R');

                // DISC keep its original starting X = baseX + 28 + 29 = baseX + 57
                $discX = $baseX + 62;
                $pdf->SetXY($discX, $yNama);
                $pdf->Cell(11, $namaHeight, rtrim(rtrim(number_format($detail->diskon_persen, 2, '.', ''), '0'), '.') . '', 0, 0, 'L');

                // SUBTOTAL keep original starting X = baseX + 68, moved left by 1 -> baseX + 67
                $subtotalX = $baseX + 67;
                $pdf->SetXY($subtotalX, $yNama);
                $pdf->Cell(26, $namaHeight, number_format($detail->subtotal, 0, ',', '.'), 0, 1, 'R');
                $currentY += $namaHeight;
                $itemCount++;
            }

            // --- Summary (dari bawah ke atas) - UNTUK NON PPN, SKIP PPN CALCULATION ---
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(146, $yTotal - 7);
            $pdf->Cell(37, 4, 'Total', 0, 0, 'L');
            // Untuk Non PPN, total = subtotal + ongkos kirim - diskon (tanpa PPN)
            $totalNonPpn = $invoice->subtotal + $invoice->ongkos_kirim - $invoice->diskon_nominal;
            $pdf->Cell(25, 4, number_format($totalNonPpn, 0, ',', '.'), 0, 1, 'R');

            $y = $yTotal - 7 - 4;
            if ($invoice->ongkos_kirim > 0) {
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetXY(146, $y);
                $pdf->Cell(37, 4, 'Ongkos Kirim', 0, 0, 'L');
                $pdf->Cell(25, 4, number_format($invoice->ongkos_kirim, 0, ',', '.'), 0, 1, 'R');
                $y -= 4;
            }
            // SKIP PPN untuk template Non PPN
            if ($invoice->diskon_nominal > 0) {
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetXY(146, $y);
                $pdf->Cell(37, 4, 'Diskon', 0, 0, 'L');
                $pdf->Cell(25, 4, number_format($invoice->diskon_nominal, 0, ',', '.'), 0, 1, 'R');
                $y -= 4;
            }
            // Subtotal selalu tampil
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetXY(146, $y);
            $pdf->Cell(37, 4, 'Subtotal', 0, 0, 'L');
            $pdf->Cell(25, 4, number_format($invoice->subtotal, 0, ',', '.'), 0, 1, 'R');

            // Tambahkan garis horizontal sebelum total
            $pdf->SetDrawColor(0, 0, 0); // warna hitam
            $pdf->SetLineWidth(0.3);
            $pdf->Line(144, $yTotal - 7, 205, $yTotal - 7);

            // Untuk posisi catatan, simpan $summaryY agar tidak error
            $summaryY = $yTotal - 7;


            // TERBILANG - gunakan total Non PPN

            // disesuaikan untuk tinggi 154
            $pdf->SetXY(14, 114);
            // Konversi total ke terbilang (pastikan helper terbilang tersedia di project)
            $Terbilang = function_exists('terbilang') ? ucwords(terbilang((int) $totalNonPpn) . ' Rupiah ') : '-';
            $pdf->SetFont('helvetica', 'BI', 9); // Set font bold italic
            $pdf->MultiCell(125, 4, $Terbilang, 0, 'L');
            $pdf->SetFont('helvetica', '', 9); // Kembalikan font normal

            // --- Informasi Pembayaran (Pojok Kiri Bawah) - Tampilkan di semua halaman ---
            $printPaymentInfo();

            // --- Catatan ---
            $nomorINV = $invoice->nomor;
            if (preg_match("/INV-(\\d{8}-\\d+)/", $invoice->nomor, $matches)) {
                $nomorINV = $matches[1];
            }
            // // Ganti <br> dengan new line agar MultiCell TCPDF bisa menampilkan baris baru
            // $CatatanText = "PERHATIAN:\nMohon Dicantumkan NO. $nomorINV\nPada Berita Transfer.";
            // $pdf->SetFont('helvetica', '', 8);
            // $pdf->SetXY(90, 137);
            // $pdf->MultiCell(120, 4,  $CatatanText, 0, 'L');


            // Garis bawah untuk tanda tangan
            $pdf->SetXY(157, $customHeight - 10);
            $pdf->Cell(40, 0, '', 'T', 2, 'C');
            // Nama direktur (ganti sesuai kebutuhan)
            $pdf->SetXY(157, $customHeight - 10);
            $namaDirektur = trim($namaDirektur) !== '' ? $namaDirektur : 'Ir. Arief Rahman Hamid';
            $pdf->Cell(40, 5, $namaDirektur, 0, 2, 'C');

            return $pdf;
        } catch (\Exception $e) {
            Log::error('FPDI Invoice Non PPN Error: ' . $e->getMessage());
            throw new \Exception('Gagal membuat PDF Invoice Non PPN: ' . $e->getMessage());
        }
    }
}
