<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Management Pajak - {{ $laporanPajak->nomor }}</title>
    <!-- Performance optimizations for PDF rendering -->
    <meta name="dompdf.enable_php" content="false">
    <meta name="dompdf.enable_javascript" content="false">
    <meta name="dompdf.enable_remote" content="false">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Watermark background */
        .watermark-bg {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            text-align: center;
            z-index: 0;
            opacity: 0.07;
            font-size: 70px;
            font-weight: bold;
            color: #4a6fa5;
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }

        .page-break {
            page-break-after: always;
        }

        /* Simple table-based layout for better printing support */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #4a6fa5;
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            vertical-align: top;
            padding: 5px;
            width: 50%;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .details-table th,
        .details-table td {
            border: 1px solid #b8c4d6;
            padding: 8px;
            text-align: left;
        }

        .details-table th {
            background-color: #e8f0fa;
            color: #2c3e50;
            font-weight: bold;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #b8c4d6;
            padding: 6px;
            text-align: left;
        }

        .items-table th {
            background-color: #e8f0fa;
            color: #2c3e50;
        }

        .section-title {
            background-color: #e8f0fa;
            color: #2c3e50;
            padding: 10px 15px;
            font-weight: bold;
            border-left: 4px solid #4a6fa5;
            margin: 20px 0 10px 0;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 0 8px 8px 0;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-table {
            border-collapse: collapse;
            width: 45%;
            margin-left: 55%;
            margin-bottom: 15px;
        }

        .summary-table td {
            padding: 10px;
            border: 1px solid #b8c4d6;
        }

        .summary-table td:first-child {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 60%;
            color: #2c3e50;
        }

        .total-row {
            font-weight: bold;
            border-top: 2px solid #4a6fa5;
            color: #2c3e50;
            background-color: #e8f0fa !important;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }

        .signature-table td {
            width: 33.33%;
            vertical-align: bottom;
            text-align: center;
            padding: 10px;
        }

        .signature-line {
            border-top: 1px solid #b8c4d6;
            width: 80%;
            margin: 60px auto 10px auto;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
            font-size: 11px;
            text-transform: uppercase;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-draft {
            background-color: #FEF3C7;
            color: #D97706;
            border: 1px solid #F59E0B;
        }

        .status-final {
            background-color: #D1FAE5;
            color: #059669;
            border: 1px solid #10B981;
        }

        .status-belum-bayar {
            background-color: #FEE2E2;
            color: #B91C1C;
            border: 1px solid #EF4444;
        }

        .status-sudah-bayar {
            background-color: #D1FAE5;
            color: #059669;
            border: 1px solid #10B981;
        }

        .status-lebih-bayar {
            background-color: #DBEAFE;
            color: #1E40AF;
            border: 1px solid #3B82F6;
        }

        .tax-summary-box {
            background-color: #f8f9fa;
            border: 2px solid #4a6fa5;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .calculation-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .calculation-table td {
            padding: 12px;
            border: 1px solid #b8c4d6;
        }

        .calculation-table td:first-child {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 40%;
            color: #2c3e50;
        }

        .footer {
            text-align: center;
            margin-top: 45px;
            border-top: 2px solid #4a6fa5;
            padding-top: 25px;
            background-color: #f9fafb;
        }

        .footer-text {
            font-size: 9.5px;
            color: #6b7280;
            margin-top: 15px;
            padding-bottom: 12px;
        }

        .company-address {
            font-size: 10px;
            color: #6b7280;
            line-height: 1.4;
        }

        .highlight-box {
            background-color: #f8fafc;
            border-left: 4px solid #4a6fa5;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 8px 8px 0;
        }

        .amount-highlight {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
            background-color: #e8f0fa;
            padding: 8px 12px;
            border-radius: 6px;
            display: inline-block;
        }

        /* Print-specific styling */
        @page {
            size: A4;
            margin: 1cm;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="watermark-bg">SINAR SURYA SEMESTARAYA</div>

    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td style="width: 50%; vertical-align: middle;">
                @php
                    $logoPath = public_path('img/logo_nama3.png');
                    $defaultLogoPath = public_path('img/logo-default.png');
                    $logoSrc = file_exists($logoPath) ? $logoPath : $defaultLogoPath;
                @endphp
                <img src="{{ $logoSrc }}" alt="Sinar Surya Logo" style="height: 60px;">
            </td>
            <td style="width: 50%; text-align: right; vertical-align: middle;">
                <h2
                    style="color: #4a6fa5; margin: 0 0 5px 0; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">
                    LAPORAN MANAGEMENT PAJAK</h2>
                <div style="font-size: 11px; line-height: 1.5;">
                    <strong>Nomor:</strong> {{ $laporanPajak->nomor }}<br>
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($laporanPajak->tanggal)->format('d/m/Y') }}<br>
                    <strong>Periode:</strong> {{ \Carbon\Carbon::parse($laporanPajak->periode)->format('F Y') }}<br>
                    @if ($laporanPajak->no_faktur_pajak)
                        <strong>No. Faktur Pajak:</strong> {{ $laporanPajak->no_faktur_pajak }}<br>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Company and Tax Info Section -->
    <table class="info-table">
        <tr>
            <td>
                <div class="section-title">
                    INFO PERUSAHAAN
                </div>
                <div class="highlight-box">
                    <strong style="color: #2c3e50; font-size: 13px;">PT. SINAR SURYA SEMESTARAYA</strong><br>
                    <div class="company-address" style="margin-top: 8px;">
                        Alamat: Jl. Condet Raya No. 6 Balekambang<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jakarta Timur 13530<br>
                        Telp: (021) 80876624 - 80876642<br>
                        E-mail: admin@kliksinarsurya.com<br>
                        NPWP: 01.234.567.8-901.000
                    </div>
                </div>
            </td>
            <td>
                <div class="section-title">
                    INFORMASI PAJAK
                </div>
                <div class="highlight-box">
                    <strong>Jenis Pajak:</strong>
                    {{ strtoupper(str_replace('_', ' ', $laporanPajak->jenis_pajak)) }}<br><br>
                    <strong>Status:</strong>
                    <span class="status-badge status-{{ $laporanPajak->status }}">
                        {{ $laporanPajak->status === 'draft' ? 'Draft' : 'Final' }}
                    </span><br><br>
                    <strong>Status Pembayaran:</strong>
                    <span class="status-badge status-{{ str_replace('_', '-', $laporanPajak->status_pembayaran) }}">
                        {{ ucwords(str_replace('_', ' ', $laporanPajak->status_pembayaran)) }}
                    </span><br><br>
                    @if ($laporanPajak->npwp)
                        <strong>NPWP Terkait:</strong> {{ $laporanPajak->npwp }}<br><br>
                    @endif
                    @if ($laporanPajak->tanggal_jatuh_tempo)
                        <strong>Jatuh Tempo:</strong>
                        {{ \Carbon\Carbon::parse($laporanPajak->tanggal_jatuh_tempo)->format('d F Y') }}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Tax Calculation Summary -->
    <div class="tax-summary-box">
        <div style="font-weight: bold; color: #4a6fa5; margin-bottom: 15px; font-size: 14px;">
            RINGKASAN PERHITUNGAN PAJAK
        </div>
        <table class="calculation-table">
            <tr>
                <td>Dasar Pengenaan Pajak (DPP)</td>
                <td class="text-right">
                    <span class="amount-highlight">Rp
                        {{ number_format($laporanPajak->dasar_pengenaan_pajak, 0, ',', '.') }}</span>
                </td>
            </tr>
            <tr>
                <td>Tarif Pajak</td>
                <td class="text-right">
                    <span class="amount-highlight">{{ number_format($laporanPajak->tarif_pajak, 2) }}%</span>
                </td>
            </tr>
            <tr class="total-row">
                <td>Jumlah Pajak</td>
                <td class="text-right">
                    <strong style="font-size: 14px; color: #2c3e50;">Rp
                        {{ number_format($laporanPajak->jumlah_pajak, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- Detailed Tax Information -->
    <div class="section-title">
        DETAIL INFORMASI PAJAK
    </div>
    <table class="details-table">
        <tr>
            <th style="width: 25%;">Komponen</th>
            <th style="width: 25%;">Nilai</th>
            <th style="width: 50%;">Keterangan</th>
        </tr>
        <tr>
            <td><strong>Jenis Pajak</strong></td>
            <td>{{ strtoupper(str_replace('_', ' ', $laporanPajak->jenis_pajak)) }}</td>
            <td>
                @if ($laporanPajak->jenis_pajak === 'ppn_keluaran')
                    Pajak Pertambahan Nilai atas penjualan barang/jasa
                @elseif($laporanPajak->jenis_pajak === 'ppn_masukan')
                    Pajak Pertambahan Nilai atas pembelian barang/jasa
                @elseif($laporanPajak->jenis_pajak === 'pph21')
                    Pajak Penghasilan Pasal 21 atas gaji dan tunjangan
                @elseif($laporanPajak->jenis_pajak === 'pph23')
                    Pajak Penghasilan Pasal 23 atas jasa dan royalti
                @elseif($laporanPajak->jenis_pajak === 'pph4_ayat2')
                    Pajak Penghasilan Pasal 4 Ayat 2 (Final)
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>DPP</strong></td>
            <td class="text-right">Rp {{ number_format($laporanPajak->dasar_pengenaan_pajak, 0, ',', '.') }}</td>
            <td>Dasar pengenaan pajak sebelum dikenakan tarif</td>
        </tr>
        <tr>
            <td><strong>Tarif</strong></td>
            <td class="text-center">{{ number_format($laporanPajak->tarif_pajak, 2) }}%</td>
            <td>Tarif pajak yang berlaku sesuai regulasi</td>
        </tr>
        <tr>
            <td><strong>Periode Pajak</strong></td>
            <td>{{ \Carbon\Carbon::parse($laporanPajak->periode)->format('F Y') }}</td>
            <td>Masa pajak yang dilaporkan</td>
        </tr>
        @if ($laporanPajak->tanggal_faktur)
            <tr>
                <td><strong>Tanggal Faktur</strong></td>
                <td>{{ \Carbon\Carbon::parse($laporanPajak->tanggal_faktur)->format('d F Y') }}</td>
                <td>Tanggal penerbitan faktur pajak</td>
            </tr>
        @endif
    </table>

    <!-- Related Transactions -->
    @if ($relatedTransactions && $relatedTransactions->count() > 0)
        <div class="section-title">
            TRANSAKSI TERKAIT
        </div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 12%">Tanggal</th>
                    <th style="width: 18%">No. Dokumen</th>
                    <th style="width: 20%">Partner</th>
                    <th style="width: 15%">DPP</th>
                    <th style="width: 15%">PPN</th>
                    <th style="width: 15%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($relatedTransactions as $index => $transaction)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($transaction['tanggal'])->format('d/m/Y') }}
                        </td>
                        <td>{{ $transaction['nomor'] ?? '-' }}</td>
                        <td>{{ $transaction['partner'] ?? '-' }}</td>
                        <td class="text-right">{{ number_format($transaction['base_amount'] ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($transaction['tax_amount'] ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($transaction['total'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr style="background-color: #e8f0fa; color: #2c3e50; font-weight: bold;">
                    <td colspan="4" class="text-center"><strong>TOTAL</strong></td>
                    <td class="text-right">
                        <strong>{{ number_format($relatedTransactions->sum('base_amount'), 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-right">
                        <strong>{{ number_format($relatedTransactions->sum('tax_amount'), 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-right">
                        <strong>{{ number_format($relatedTransactions->sum('total'), 0, ',', '.') }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    @endif

    <!-- Additional Information -->
    @if ($laporanPajak->keterangan)
        <div class="section-title">
            KETERANGAN
        </div>
        <div class="highlight-box" style="margin-bottom: 20px;">
            {{ $laporanPajak->keterangan }}
        </div>
    @endif

    <!-- Summary Section -->
    <table class="summary-table">
        <tr>
            <td>Dasar Pengenaan Pajak</td>
            <td class="text-right">
                <span class="amount-highlight">Rp
                    {{ number_format($laporanPajak->dasar_pengenaan_pajak, 0, ',', '.') }}</span>
            </td>
        </tr>
        <tr>
            <td>Tarif Pajak ({{ number_format($laporanPajak->tarif_pajak, 2) }}%)</td>
            <td class="text-right">
                <span class="amount-highlight">{{ number_format($laporanPajak->tarif_pajak, 2) }}%</span>
            </td>
        </tr>
        <tr class="total-row">
            <td>TOTAL PAJAK</td>
            <td class="text-right">
                <strong style="font-size: 14px; color: #2c3e50;">Rp
                    {{ number_format($laporanPajak->jumlah_pajak, 0, ',', '.') }}</strong>
            </td>
        </tr>
    </table>

    <!-- Signature Section -->
    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-title">DISIAPKAN OLEH</div>
                <div class="signature-line"></div>
                <div style="margin-top: 10px;">
                    <strong style="color: #2c3e50;">{{ $laporanPajak->user->name ?? 'Staff Keuangan' }}</strong><br>
                    <small style="color: #6b7280;">Staff Keuangan</small><br>
                    <small style="color: #6b7280;">Tanggal:
                        {{ \Carbon\Carbon::parse($laporanPajak->tanggal)->format('d/m/Y') }}</small>
                </div>
            </td>
            <td>
                <div class="signature-title">DIPERIKSA OLEH</div>
                <div class="signature-line"></div>
                <div style="margin-top: 10px;">
                    <strong style="color: #2c3e50;">Supervisor Keuangan</strong><br>
                    <small style="color: #6b7280;">Supervisor Keuangan</small><br>
                    <small style="color: #6b7280;">Tanggal: _______________</small>
                </div>
            </td>
            <td>
                <div class="signature-title">DISETUJUI OLEH</div>
                <div class="signature-line"></div>
                <div style="margin-top: 10px;">
                    <strong style="color: #2c3e50;">Manager Keuangan</strong><br>
                    <small style="color: #6b7280;">Manager Keuangan</small><br>
                    <small style="color: #6b7280;">Tanggal: _______________</small>
                </div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        {{-- <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 15px;">
            <img src="{{ public_path('img/atsaka.webp') }}" alt="Atsaka Logo" onerror="this.style.display='none'"
                style="height: 35px; margin: 0 15px;">
            <img src="{{ public_path('img/polylab.webp') }}" alt="Polylab Logo" onerror="this.style.display='none'"
                style="height: 30px; margin: 0 15px;">
            <img src="{{ public_path('img/sumbunesia.webp') }}" alt="Sumbunesia Logo"
                onerror="this.style.display='none'" style="height: 35px; margin: 0 15px;">
        </div> --}}
        <div style="border-top: 2px solid #4a6fa5; padding-top: 15px; margin-top: 20px;">
            <strong style="color: #4a6fa5; font-size: 12px;">PT. SINAR SURYA SEMESTARAYA</strong><br>
            <div class="company-address" style="margin-top: 8px;">
                Finance Department - Tax Management Division<br>
                Generated on {{ now()->format('d F Y \a\t H:i') }} WIB<br>
                Document ID: {{ $laporanPajak->nomor }}
            </div>
        </div>
        <div class="footer-text">
            <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #b8c4d6;">
                Dokumen ini dibuat secara otomatis oleh sistem ERP Sinar Surya<br>
                Untuk informasi lebih lanjut hubungi: admin@kliksinarsurya.com<br>
                <div style="font-size: 8px; color: #aaa; margin-top: 5px;">
                    © {{ date('Y') }} PT. SINAR SURYA SEMESTARAYA. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</body>

</html>
