<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hutang Usaha</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20mm 15mm 25mm 15mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            line-height: 1.4;
            color: #2d3748;
            margin: 0;
            padding: 0;
        }

        /* Header dengan Logo */
        .header {
            background: #f8fafc;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-bottom: 2px solid #3b82f6;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
            padding: 5px;
        }

        .header-logo {
            width: 20%;
            text-align: left;
        }

        .logo-img {
            max-width: 90px;
            max-height: 40px;
            height: auto;
            background: white;
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
        }

        .company-name {
            font-size: 11px;
            font-weight: bold;
            color: #1e40af;
            letter-spacing: 0.5px;
        }

        .header-title-section {
            width: 60%;
            text-align: center;
        }

        .header-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
            color: #1e293b;
        }

        .header-subtitle {
            font-size: 8px;
            font-weight: normal;
            color: #64748b;
            margin-top: 3px;
        }

        .header-date {
            width: 20%;
            text-align: right;
            font-size: 7.5px;
            color: #64748b;
        }

        .print-date {
            background: white;
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
        }

        /* Filter Info */
        .filter-info {
            font-size: 8px;
            margin: 5px 0 10px 0;
            padding: 6px 10px;
            background-color: #f9fafb;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #3b82f6;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 7.5px;
        }

        .data-table th {
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 5px 3px;
            text-align: left;
            font-size: 7px;
            font-weight: bold;
            color: #1e293b;
        }

        .data-table td {
            border: 1px solid #e2e8f0;
            padding: 4px 3px;
            font-size: 7.5px;
        }

        .data-table tbody tr:nth-child(odd) {
            background: #f9fafb;
        }

        .data-table tbody tr:nth-child(even) {
            background: white;
        }

        .data-table .text-right {
            text-align: right;
        }

        .data-table .text-center {
            text-align: center;
        }

        .data-table tfoot th,
        .data-table tfoot td {
            border-top: 2px solid #cbd5e0;
            font-weight: bold;
            background-color: #dbeafe;
            color: #1e40af;
            padding: 6px 3px;
            font-size: 8px;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 2px;
            font-size: 6.5px;
            font-weight: bold;
        }

        .status-lunas {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-sebagian {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-kelebihan {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-belum {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* Summary Box */
        .summary-box {
            margin-top: 15px;
            border: 2px solid #cbd5e0;
            padding: 10px 12px;
            background-color: #f1f5f9;
        }

        .summary-title {
            font-weight: bold;
            margin-bottom: 6px;
            font-size: 10px;
            border-bottom: 1px solid #cbd5e0;
            padding-bottom: 4px;
            color: #1e293b;
        }

        .summary-item {
            margin-bottom: 3px;
            font-size: 8px;
        }

        .summary-item table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-label {
            font-size: 8px;
            width: 70%;
        }

        .summary-value {
            font-size: 8px;
            font-weight: bold;
            text-align: right;
            width: 30%;
        }

        /* Payment Details */
        .payment-details {
            font-size: 6.5px;
            color: #dc2626;
            margin-top: 2px;
        }

        /* Footer Section */
        .footer-section {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 7px;
            color: #64748b;
        }
    </style>
</head>

<body>
    {{-- Header dengan Logo --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    @php
                        $logoPath = public_path('img/SemestaPro.PNG');
                        $logoBase64 = '';
                        if (file_exists($logoPath)) {
                            $logoData = file_get_contents($logoPath);
                            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
                        }
                    @endphp
                    @if ($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Logo" class="logo-img">
                    @else
                        <div class="company-name">SEMESTAPRO</div>
                    @endif
                </td>
                <td class="header-title-section">
                    <div class="header-title">Laporan Hutang Usaha</div>
                    <div class="header-subtitle">PT. SINAR SURYA SEMESTARAYA</div>
                </td>
                <td class="header-date">
                    <div class="print-date">
                        <strong>Dicetak:</strong><br>
                        {{ date('d/m/Y') }}<br>
                        {{ date('H:i') }} WIB
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="filter-info">
        <strong>Filter:</strong>
        @if ($supplier)
            Supplier: {{ $supplier->nama }} |
        @endif
        @if ($startDate && $endDate)
            Periode: {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}
        @elseif($startDate)
            Dari Tanggal: {{ date('d/m/Y', strtotime($startDate)) }}
        @elseif($endDate)
            Sampai Tanggal: {{ date('d/m/Y', strtotime($endDate)) }}
        @else
            Semua Periode
        @endif
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th style="width: 10%;">Nomor PO</th>
                <th style="width: 7%;">Tanggal PO</th>
                <th style="width: 14%;">Supplier</th>
                <th style="width: 9%;" class="text-right">Total PO</th>
                <th style="width: 9%;" class="text-right">Total Bayar</th>
                <th style="width: 18%;">Riwayat Pembayaran</th>
                <th style="width: 8%;" class="text-right">Total Retur</th>
                <th style="width: 11%;" class="text-right">Sisa Hutang</th>
                <th style="width: 10%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPO = 0;
                $totalPembayaran = 0;
                $totalRetur = 0;
            @endphp

            @forelse($purchaseOrders as $index => $po)
                @if ($po->status != 'dibatalkan')
                    @php
                        $totalPO += $po->total;
                        $totalPembayaran += $po->total_pembayaran;
                        $totalRetur += $po->total_retur;

                        // Get all payments sorted by date descending
                        $allPayments = $po->pembayaran()->orderBy('tanggal', 'desc')->get();
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $po->nomor }}</td>
                        <td>{{ date('d/m/Y', strtotime($po->tanggal)) }}</td>
                        <td>{{ $po->supplier->nama }}</td>
                        <td class="text-right">{{ number_format($po->total, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($po->total_pembayaran, 0, ',', '.') }}</td>
                        <td>
                            @if ($allPayments->count() > 0)
                                @foreach ($allPayments as $payment)
                                    <div
                                        style="margin-bottom: 3px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 2px;">
                                        <strong style="font-size: 7px;">{{ $payment->nomor ?? '-' }}</strong><br>
                                        <span
                                            style="font-size: 6.5px; color: #64748b;">{{ date('d/m/Y', strtotime($payment->tanggal)) }}</span>
                                        <div class="payment-details">Rp
                                            {{ number_format($payment->jumlah, 0, ',', '.') }}</div>
                                    </div>
                                @endforeach
                            @else
                                <span style="color: #94a3b8; font-size: 7px;">Belum ada pembayaran</span>
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($po->total_retur, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($po->sisa_hutang, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if ($po->status_pembayaran == 'belum_bayar')
                                <span class="status-badge status-belum">BELUM BAYAR</span>
                            @elseif($po->status_pembayaran == 'sebagian')
                                <span class="status-badge status-sebagian">SEBAGIAN</span>
                            @elseif($po->status_pembayaran == 'kelebihan_bayar')
                                <span class="status-badge status-kelebihan">KELEBIHAN</span>
                            @else
                                <span class="status-badge status-lunas">LUNAS</span>
                            @endif
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 20px; color: #94a3b8;">Tidak ada data hutang
                        usaha</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total:</th>
                <th class="text-right">{{ number_format($totalPO, 0, ',', '.') }}</th>
                <th class="text-right">{{ number_format($totalPembayaran, 0, ',', '.') }}</th>
                <th></th>
                <th class="text-right">{{ number_format($totalRetur, 0, ',', '.') }}</th>
                <th class="text-right">{{ number_format($totalSisaHutang, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div class="summary-box">
        <div class="summary-title">RINGKASAN HUTANG</div>
        <div class="summary-item">
            <table>
                <tr>
                    <td class="summary-label">Total Purchase Order:</td>
                    <td class="summary-value">Rp {{ number_format($totalPO, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Total Pembayaran:</td>
                    <td class="summary-value">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Total Retur:</td>
                    <td class="summary-value">Rp {{ number_format($totalRetur, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Total Ongkos Kirim:</td>
                    <td class="summary-value">Rp
                        {{ number_format($purchaseOrders->sum('ongkos_kirim'), 0, ',', '.') }}</td>
                </tr>
                <tr style="border-top: 1px solid #cbd5e0;">
                    <td class="summary-label" style="font-weight: bold; color: #1e293b; padding-top: 4px;">Total Sisa
                        Hutang:</td>
                    <td class="summary-value"
                        style="font-weight: bold; color: #dc2626; font-size: 9px; padding-top: 4px;">Rp
                        {{ number_format($totalSisaHutang, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer-section">
        <p>
            Dokumen ini dicetak otomatis oleh sistem <strong>{{ config('app.name', 'SemestaPro ERP') }}</strong> pada
            {{ date('d/m/Y H:i:s') }}
            <br>
            Dokumen ini sah tanpa tanda tangan dan stempel
        </p>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->getFont("Arial", "bold");
            $size = 8.5;
            
            $pageNum = "{PAGE_NUM}";
            $pageCount = "{PAGE_COUNT}";
            $pageText = "Halaman " . $pageNum . " dari " . $pageCount;
            
            $y = $pdf->get_height() - 30;
            $x = $pdf->get_width() - 150;
            
            $pdf->page_text($x, $y, $pageText, $font, $size, array(0.4, 0.4, 0.4));
        }
    </script>
</body>

</html>
