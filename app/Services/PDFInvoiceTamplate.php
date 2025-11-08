<?php

namespace App\Services;

use TCPDF;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\Log;

class PDFInvoiceTamplate
{
    /**
     * Generate Invoice PDF (mirip Delivery Order, bisa pakai template atau hanya text)
     * @param object $invoice Invoice model
     * @param string $namaDirektur Nama direktur untuk tanda tangan
     * @param float $dpAmount Jumlah DP yang akan ditampilkan (optional, tidak disimpan ke database)
     */
    public function fillInvoiceTemplate($invoice, $namaDirektur = '', $dpAmount = 0)
    {
        $useTemplate = config(
            'app.print_with_template',
            false
        ); // default false, bisa diatur di config/app.php
        $templatePath = public_path('pdf/Invoice-New.pdf');

        // XY = 214x 163
        $customWidth = 214;
        $customHeight = 163;

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
            $pdf->SetTitle('Invoice - ' . $invoice->nomor);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(0, 0, 0);

            $pdf->SetFont('helvetica', '', 10);
            $pdf->SetXY(97, 36.5);
            $pdf->Cell(0, 0, $invoice->nomor, 0, 0, 'L');
            $pdf->SetXY(156, 13.5);
            $pdf->Cell(0, 0, (\Carbon\Carbon::parse($invoice->tanggal)->format('d/m/Y')), 0, 0, 'L');

            // --- Customer ---
            $customerX = 12;
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
            $salesOrderX = 158;
            $salesOrderY = 31.8;
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
            $maxItemsPerPage = 7; // Maksimal 7 item per halaman

            // Fixed summary position: reserve space at bottom so summary is always at the same Y
            $yTotal = $customHeight - 47; // posisi paling bawah (fix), digeser 3 unit ke bawah
            $summaryReserveLines = 5; // jumlah baris maksimal yang dipakai summary (Total, Ongkos Kirim, PPN, Diskon, Subtotal)
            $itemsMaxY = $yTotal - ($summaryReserveLines * 2) - 2; // sisa ruang untuk items

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
                    $pdf->SetXY(97, 36.5);
                    $pdf->Cell(0, 0, $invoice->nomor . ' (Hal. ' . $pageNumber . ')', 0, 0, 'L');
                    $pdf->SetXY(156, 13.5);
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

                    // Table header
                    $pdf->SetFont('helvetica', 'B', 8);
                    $pdf->SetXY(22.5, $itemsStartY - 2);
                    $pdf->Cell(35, $lineHeight, 'Kode Barang', 0, 0, 'L');
                    $pdf->SetFont('helvetica', '', 8);
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
                $discX = $baseX + 57;
                $pdf->SetXY($discX, $yNama);
                $pdf->Cell(11, $namaHeight, rtrim(rtrim(number_format($detail->diskon_persen, 2, '.', ''), '0'), '.') . '', 0, 0, 'L');

                // SUBTOTAL keep original starting X = baseX + 68, moved left by 1 -> baseX + 67
                $subtotalX = $baseX + 67;
                $pdf->SetXY($subtotalX, $yNama);
                $pdf->Cell(26, $namaHeight, number_format($detail->subtotal, 0, ',', '.'), 0, 1, 'R');
                $currentY += $namaHeight;
                $itemCount++;
            }

            // --- Summary (dari bawah ke atas) ---
            $pdf->SetFont('helvetica', 'B', 8);
            // geser seluruh blok summary 3mm ke atas
            $summaryY = $yTotal - 3;

            // Jika ada DP, hitung DP dengan PPN jika ada
            if ($dpAmount > 0) {
                // Hitung subtotal sebelum PPN
                $dpSubtotal = $dpAmount;

                // Hitung PPN untuk DP jika invoice memiliki PPN
                $dpPPN = 0;
                if ($invoice->ppn > 0) {
                    // Hitung persentase PPN dari invoice
                    $ppnRate = $invoice->subtotal > 0 ? ($invoice->ppn / $invoice->subtotal) : 0;
                    $dpPPN = $dpAmount * $ppnRate;
                }

                // Total DP (DP + PPN DP)
                $totalDP = $dpSubtotal + $dpPPN;

                // Tampilkan Total DP (bold)
                $pdf->SetXY(142, $summaryY);
                $pdf->Cell(37, 4, 'Total DP', 0, 0, 'L');
                $pdf->Cell(25, 4, number_format($totalDP, 0, ',', '.'), 0, 1, 'R');

                $y = $summaryY - 4;

                // Tampilkan PPN DP jika ada
                if ($dpPPN > 0) {
                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->SetXY(142, $y);
                    $pdf->Cell(37, 4, 'PPN 11%', 0, 0, 'L');
                    $pdf->Cell(25, 4, number_format($dpPPN, 0, ',', '.'), 0, 1, 'R');
                    $y -= 4;
                }

                // Tampilkan Subtotal DP
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetXY(142, $y);
                $pdf->Cell(37, 4, 'Uang Muka (DP)', 0, 0, 'L');
                $pdf->Cell(25, 4, number_format($dpSubtotal, 0, ',', '.'), 0, 1, 'R');

                // Gunakan totalDP untuk terbilang dan set uangMuka = 0 untuk invoice DP
                $grandTotal = $totalDP;
                $uangMuka = 0; // Invoice DP tidak ada uang muka terapkan
                $totalTagihan = $totalDP;
            } else {
                // ===== INVOICE NORMAL SUMMARY =====
                // Hitung semua nilai yang diperlukan
                $grandTotal = $invoice->total;
                $uangMuka = $invoice->uang_muka_terapkan ?? 0;
                $ongkosKirim = $invoice->ongkos_kirim ?? 0;
                $diskon = $invoice->diskon_nominal ?? 0;
                $subtotalItems = $invoice->subtotal ?? 0;
                $ppnAmount = $invoice->ppn ?? 0;

                // Hitung total tagihan setelah uang muka
                // Formula: Subtotal + PPN - Uang Muka
                $totalTagihan = $grandTotal - $uangMuka;

                // Hitung pembayaran yang sudah diterima
                $totalPembayaran = 0;
                if (method_exists($invoice, 'pembayaranPiutang')) {
                    $totalPembayaran = $invoice->pembayaranPiutang()->sum('jumlah') ?? 0;
                }

                // Hitung kredit yang diterapkan
                $totalKredit = $invoice->kredit_terapkan ?? 0;

                // Mulai tampilkan summary dari atas ke bawah (dalam PDF, Y lebih kecil = lebih atas)
                $y = $summaryY;
                $subtotalItems = $invoice->subtotal ?? 0;

                // ===== Tampilkan dengan Uang Muka (Format Akuntansi) =====
                if ($uangMuka > 0) {
                    // BAGIAN ATAS: Total (Bold) - hasil akhir yang harus dibayar
                    $pdf->SetFont('helvetica', 'B', 8);
                    $pdf->SetXY(142, $y);
                    $pdf->Cell(37, 3, 'Total', 0, 0, 'L');
                    $pdf->Cell(25, 3, number_format($totalTagihan, 0, ',', '.'), 0, 1, 'R');
                    $y -= 3;

                    // Garis pemisah atas
                    $pdf->SetDrawColor(200, 200, 200);
                    $pdf->SetLineWidth(0.2);
                    $y -= 1;

                    // PPN 11% dari Pelunasan
                    $sisaSetelahUM = $grandTotal - $uangMuka; // Sisa yang harus dibayar (termasuk PPN)
                    $pelunasanBersih = $sisaSetelahUM / 1.11; // Nilai bersih tanpa PPN
                    $ppnPelunasan = $pelunasanBersih * 0.11; // PPN 11% dari pelunasan bersih

                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->SetXY(142, $y);
                    $pdf->Cell(37, 3, 'PPN 11%', 0, 0, 'L');
                    $pdf->Cell(25, 3, number_format($ppnPelunasan, 0, ',', '.'), 0, 1, 'R');
                    $y -= 3;

                    // Pelunasan (bersih tanpa PPN)
                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->SetXY(142, $y);
                    $pdf->Cell(37, 3, 'Pelunasan', 0, 0, 'L');
                    $pdf->Cell(25, 3, number_format($pelunasanBersih, 0, ',', '.'), 0, 1, 'R');
                    $y -= 2;

                    // Garis pemisah tengah
                    $pdf->SetDrawColor(200, 200, 200);
                    $pdf->SetLineWidth(0.2);
                    $pdf->Line(142, $y + 2, 204, $y + 2);
                    $y -= 2;

                    // Uang Muka
                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->SetXY(142, $y);
                    $pdf->Cell(37, 3, 'Uang Muka', 0, 0, 'L');
                    $pdf->Cell(25, 3, '(' . number_format($uangMuka, 0, ',', '.') . ')', 0, 1, 'R');
                    $y -= 3;

                    // Jmh.Total (sudah termasuk PPN awal) - untuk invoice dengan uang muka
                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->SetXY(142, $y);
                    $pdf->Cell(37, 3, 'Jmh.Total', 0, 0, 'L');
                    $pdf->Cell(25, 3, number_format($grandTotal, 0, ',', '.'), 0, 1, 'R');
                } else {
                    // ===== TANPA UANG MUKA: Format Normal =====

                    // Check if LUNAS
                    $sisaBayar = $grandTotal - $totalPembayaran - $totalKredit;
                    $isLunas = ($sisaBayar <= 0);

                    // Tampilkan status pembayaran jika ada pembayaran
                    if ($totalPembayaran > 0) {
                        $pdf->SetFont('helvetica', 'B', 8);
                        $pdf->SetXY(142, $y);
                        if ($isLunas) {
                            $pdf->Cell(37, 4, 'LUNAS', 0, 0, 'L');
                            $pdf->Cell(25, 4, '0', 0, 1, 'R');
                        } else {
                            $pdf->Cell(37, 4, 'Sisa Tagihan', 0, 0, 'L');
                            $pdf->Cell(25, 4, number_format($sisaBayar, 0, ',', '.'), 0, 1, 'R');
                        }
                        $y -= 4;

                        // Total Pembayaran
                        $pdf->SetFont('helvetica', '', 8);
                        $pdf->SetXY(142, $y);
                        $pdf->Cell(37, 4, 'Total Pembayaran', 0, 0, 'L');
                        $pdf->Cell(25, 4, '(' . number_format($totalPembayaran, 0, ',', '.') . ')', 0, 1, 'R');
                        $y -= 4;
                    }

                    // Total (Bold)
                    $pdf->SetFont('helvetica', 'B', 8);
                    $pdf->SetXY(142, $y);
                    $pdf->Cell(37, 4, 'Total', 0, 0, 'L');
                    $pdf->Cell(25, 4, number_format($grandTotal, 0, ',', '.'), 0, 1, 'R');
                    $y -= 4;

                    // PPN 11%
                    if ($ppnAmount > 0) {
                        $pdf->SetFont('helvetica', '', 8);
                        $pdf->SetXY(142, $y);
                        $pdf->Cell(37, 4, 'PPN 11%', 0, 0, 'L');
                        $pdf->Cell(25, 4, number_format($ppnAmount, 0, ',', '.'), 0, 1, 'R');
                        $y -= 4;
                    }

                    // Diskon (jika ada)
                    $diskon = $invoice->diskon_nominal ?? 0;
                    if ($diskon > 0) {
                        $pdf->SetFont('helvetica', '', 8);
                        $pdf->SetXY(142, $y);
                        $pdf->Cell(37, 4, 'Diskon', 0, 0, 'L');
                        $pdf->Cell(25, 4, '(' . number_format($diskon, 0, ',', '.') . ')', 0, 1, 'R');
                        $y -= 4;
                    }

                    // Ongkos Kirim (jika ada)
                    $ongkosKirim = $invoice->ongkos_kirim ?? 0;
                    if ($ongkosKirim > 0) {
                        $pdf->SetFont('helvetica', '', 8);
                        $pdf->SetXY(142, $y);
                        $pdf->Cell(37, 4, 'Ongkos Kirim', 0, 0, 'L');
                        $pdf->Cell(25, 4, number_format($ongkosKirim, 0, ',', '.'), 0, 1, 'R');
                        $y -= 4;
                    }

                    // Subtotal Items
                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->SetXY(142, $y);
                    $pdf->Cell(37, 4, 'Subtotal', 0, 0, 'L');
                    $pdf->Cell(25, 4, number_format($subtotalItems, 0, ',', '.'), 0, 1, 'R');
                }
            }

            // Tambahkan garis horizontal sebelum total
            $pdf->SetDrawColor(0, 0, 0); // warna hitam
            $pdf->SetLineWidth(0.3);
            $pdf->Line(142, $yTotal - 3, 203, $yTotal - 3);

            // Untuk posisi catatan, simpan $summaryY agar tidak error
            $summaryY = $yTotal;

            // TERBILANG

            // moved up by 1mm as requested
            $pdf->SetXY(14, 121);
            // Konversi total ke terbilang (gunakan totalTagihan jika ada uang muka, jika tidak gunakan grandTotal)
            $nilaiTerbilang = ($uangMuka > 0) ? $totalTagihan : $grandTotal;
            $Terbilang = function_exists('terbilang') ? ucwords(terbilang((int) $nilaiTerbilang) . ' Rupiah ') : '-';
            $pdf->SetFont('helvetica', 'BI', 9); // Set font bold italic
            $pdf->MultiCell(125, 4, $Terbilang, 0, 'L');
            $pdf->SetFont('helvetica', '', 9); // Kembalikan font normal

            // --- Catatan DP (di samping kiri summary, jika ada DP) ---
            if ($dpAmount > 0) {
                $nomorPO = $invoice->salesOrder->nomor_po ?? '-';
                $totalPO = $invoice->salesOrder->total ?? $invoice->total;
                // Hitung persentase dari total invoice, bukan dari total PO
                $persenDP = $invoice->total > 0 ? round(($dpAmount / $invoice->total) * 100, 2) : 0;

                $dpNoteX = 21; // Posisi X di area kolom nama barang, sejajar dengan yang sebelumnya
                $dpNoteY = $summaryY - 15; // Sejajar dengan bagian atas summary

                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetXY($dpNoteX, $dpNoteY);
                $pdf->Cell(0, 4, 'Note:', 0, 1, 'L');

                $pdf->SetFont('helvetica', '', 7.5);
                $dpNoteY += 4;
                $pdf->SetXY($dpNoteX, $dpNoteY);
                $dpText = "Pembayaran DP " . rtrim(rtrim(number_format($persenDP, 2, '.', ''), '0'), '.') . "% untuk PO: " . $nomorPO;
                $pdf->MultiCell(120, 3.5, $dpText, 0, 'L');

                $dpNoteY = $pdf->GetY();
                $pdf->SetXY($dpNoteX, $dpNoteY);
                $dpTotalText = "Dengan total PO senilai Rp. " . number_format($totalPO, 0, ',', '.');
                $pdf->MultiCell(120, 3.5, $dpTotalText, 0, 'L');
            }

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
