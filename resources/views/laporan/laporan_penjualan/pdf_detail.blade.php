<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Rincian</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            line-height: 1.5;
            color: #2d3748;
            margin: 15px 20px;
        }

        .header {
            margin-bottom: 12px;
        }

        .header table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 70px;
            vertical-align: middle;
            padding-right: 10px;
        }

        .logo {
            width: 60px;
            height: auto;
        }

        .header-text {
            vertical-align: middle;
        }

        .company-name {
            font-size: 13px;
            font-weight: bold;
            color: #1e293b;
            margin: 0 0 2px 0;
        }

        .report-title {
            font-size: 11px;
            font-weight: bold;
            color: #334155;
            margin: 0;
        }

        .report-period {
            font-size: 7.5px;
            color: #64748b;
            margin: 2px 0 0 0;
        }

        .print-info {
            text-align: right;
            vertical-align: middle;
        }

        .print-date {
            font-size: 7px;
            color: #64748b;
        }

        .divider {
            border-top: 2px solid #3b82f6;
            margin: 8px 0;
        }

        .filter-info {
            margin-bottom: 10px;
            background: #f8fafc;
            padding: 6px 8px;
        }

        .filter-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .filter-info td {
            font-size: 7px;
            color: #475569;
            padding: 1px 0;
            vertical-align: top;
        }

        .filter-label {
            width: 90px;
            font-weight: 600;
            color: #334155;
        }

        .filter-separator {
            width: 10px;
            text-align: center;
        }

        .so-section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .so-header {
            background: #f1f5f9;
            padding: 6px 8px;
            margin-bottom: 4px;
            border-left: 3px solid #3b82f6;
        }

        .so-header table {
            width: 100%;
            border-collapse: collapse;
        }

        .so-main-info {
            width: 65%;
            vertical-align: top;
        }

        .so-number {
            font-size: 9px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 3px;
        }

        .so-customer {
            font-size: 8px;
            color: #334155;
            margin-bottom: 1px;
        }

        .so-meta {
            font-size: 7px;
            color: #64748b;
        }

        .so-status-cell {
            width: 35%;
            text-align: right;
            vertical-align: top;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 7px;
            font-weight: 600;
            border-radius: 3px;
            margin-bottom: 3px;
        }

        .status-lunas,
        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .status-sebagian,
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-belum,
        .badge-danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .items-table thead tr {
            background: #f9fafb;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        .items-table th {
            font-size: 7px;
            font-weight: 600;
            color: #475569;
            text-align: left;
            padding: 4px 5px;
        }

        .items-table th.text-center {
            text-align: center;
        }

        .items-table th.text-right {
            text-align: right;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
        }

        .items-table td {
            font-size: 7.5px;
            padding: 4px 5px;
            color: #334155;
            vertical-align: top;
        }

        .items-table td.text-center {
            text-align: center;
        }

        .items-table td.text-right {
            text-align: right;
        }

        .item-name {
            font-weight: 600;
            color: #1e293b;
        }

        .so-summary {
            margin-top: 6px;
        }

        .so-summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-left {
            width: 60%;
            vertical-align: top;
            padding-right: 10px;
        }

        .summary-right {
            width: 40%;
            vertical-align: top;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table tr {
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-table tr:last-child {
            border-bottom: none;
        }

        .summary-table td {
            font-size: 7.5px;
            padding: 3px 5px;
        }

        .summary-label {
            color: #64748b;
            width: 110px;
        }

        .summary-value {
            text-align: right;
            color: #334155;
            font-weight: 600;
        }

        .summary-total {
            font-size: 8.5px;
            font-weight: bold;
            background: #f8fafc;
            color: #1e293b;
        }

        .summary-paid {
            color: #059669;
        }

        .summary-remaining {
            color: #dc2626;
        }

        .grand-summary {
            margin-top: 15px;
            background: #f8fafc;
            padding: 8px 10px;
            border-top: 2px solid #3b82f6;
        }

        .grand-summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .grand-summary-left {
            width: 60%;
            vertical-align: top;
        }

        .grand-summary-right {
            width: 40%;
            vertical-align: top;
        }

        .grand-summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .grand-summary-table td {
            font-size: 8.5px;
            padding: 3px 5px;
        }

        .grand-label {
            color: #475569;
            font-weight: 600;
            width: 140px;
        }

        .grand-value {
            text-align: right;
            font-weight: bold;
            color: #1e293b;
        }

        .grand-total {
            font-size: 10px;
            background: #e0f2fe;
            color: #0c4a6e;
        }

        .page-number {
            position: fixed;
            bottom: 10px;
            right: 20px;
            font-size: 7px;
            color: #64748b;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #94a3b8;
            font-size: 8px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <table>
            <tr>
                <td class="logo-cell">
                    @php
                        $logoPath = public_path('img/SemestaPro.PNG');
                        $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
                    @endphp
                    @if ($logoData)
                        <img src="data:image/png;base64,{{ $logoData }}" alt="Logo" class="logo">
                    @endif
                </td>
                <td class="header-text">
                    <h1 class="company-name">SINAR SURYA SEMESTARAYA</h1>
                    <h2 class="report-title">LAPORAN PENJUALAN RINCIAN</h2>
                    <p class="report-period">
                        Periode: {{ \Carbon\Carbon::parse($filters['tanggal_awal'])->format('d M Y') }} -
                        {{ \Carbon\Carbon::parse($filters['tanggal_akhir'])->format('d M Y') }}
                    </p>
                </td>
                <td class="print-info">
                    <div class="print-date">
                        Dicetak: {{ now()->format('d/m/Y H:i') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <div class="filter-info">
        <table>
            <tr>
                <td class="filter-label">Customer</td>
                <td class="filter-separator">:</td>
                <td>{{ $filters['customer_id'] ? 'Filter diterapkan' : 'Semua Customer' }}</td>
                <td class="filter-label" style="width: 110px;">Sales</td>
                <td class="filter-separator">:</td>
                <td>{{ $filters['user_id'] ? 'Filter diterapkan' : 'Semua Sales' }}</td>
            </tr>
            <tr>
                <td class="filter-label">Status Pembayaran</td>
                <td class="filter-separator">:</td>
                <td colspan="4">
                    {{ $filters['status_pembayaran'] ? ucfirst(str_replace('_', ' ', $filters['status_pembayaran'])) : 'Semua Status' }}
                </td>
            </tr>
            @if ($filters['search'])
                <tr>
                    <td class="filter-label">Pencarian</td>
                    <td class="filter-separator">:</td>
                    <td colspan="4">{{ $filters['search'] }}</td>
                </tr>
            @endif
        </table>
    </div>

    @forelse($dataPenjualan as $index => $penjualan)
        <div class="so-section">
            <div class="so-header">
                <table>
                    <tr>
                        <td class="so-main-info">
                            <div class="so-number">{{ $penjualan->nomor }}</div>
                            <div class="so-customer">Customer:
                                {{ $penjualan->customer->company ?: $penjualan->customer->nama }}
                                ({{ $penjualan->customer->kode ?? '-' }})
                            </div>
                            <div class="so-meta">
                                Tanggal: {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d/m/Y') }} |
                                Sales: {{ $penjualan->user->name ?? '-' }}
                            </div>
                        </td>
                        <td class="so-status-cell">
                            @if ($penjualan->status_pembayaran == 'lunas')
                                <div class="status-badge status-lunas">LUNAS</div>
                            @elseif($penjualan->status_pembayaran == 'sebagian')
                                <div class="status-badge status-sebagian">SEBAGIAN</div>
                            @elseif($penjualan->status_pembayaran == 'belum_bayar')
                                <div class="status-badge status-belum">BELUM BAYAR</div>
                            @elseif($penjualan->status_pembayaran == 'kelebihan_bayar')
                                <div class="status-badge badge-info">KELEBIHAN</div>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 4%;" class="text-center">No</th>
                        <th style="width: 35%;">Produk</th>
                        <th style="width: 10%;" class="text-center">Satuan</th>
                        <th style="width: 8%;" class="text-right">Qty</th>
                        <th style="width: 13%;" class="text-right">Harga</th>
                        <th style="width: 8%;" class="text-right">Disc %</th>
                        <th style="width: 11%;" class="text-right">Disc Rp</th>
                        <th style="width: 15%;" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $itemSubtotal = 0;
                    @endphp
                    @foreach ($penjualan->details as $detailIndex => $detail)
                        @php
                            $qty = floatval($detail->quantity ?? 0);
                            $harga = floatval($detail->harga ?? 0);
                            $discPersen = floatval($detail->diskon_persen ?? 0);
                            $discRp = floatval($detail->diskon_nominal ?? 0);

                            $subtotalItem = $qty * $harga - (($qty * $harga * $discPersen) / 100 + $discRp);
                            $itemSubtotal += $subtotalItem;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $detailIndex + 1 }}</td>
                            <td>
                                <span class="item-name">{{ $detail->produk->nama ?? 'N/A' }}</span>
                            </td>
                            <td class="text-center">{{ $detail->produk->satuan->nama ?? '-' }}</td>
                            <td class="text-right">{{ number_format($qty, 2, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($harga, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($discPersen, 2, ',', '.') }}%</td>
                            <td class="text-right">{{ number_format($discRp, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($subtotalItem, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="so-summary">
                <table>
                    <tr>
                        <td class="summary-left"></td>
                        <td class="summary-right">
                            <table class="summary-table">
                                <tr>
                                    <td class="summary-label">Subtotal Item</td>
                                    <td class="summary-value">Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if ($penjualan->ongkos_kirim > 0)
                                    <tr>
                                        <td class="summary-label">Ongkos Kirim</td>
                                        <td class="summary-value">Rp
                                            {{ number_format($penjualan->ongkos_kirim, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @if ($penjualan->ppn > 0)
                                    @php
                                        // PPN adalah persentase (0-100), bukan nominal
                                        $ppnPercentage = floatval($penjualan->ppn);
                                        $ppnNominal = ($ppnPercentage / 100) * $penjualan->subtotal;
                                    @endphp
                                    <tr>
                                        <td class="summary-label">PPN ({{ number_format($ppnPercentage, 0) }}%)</td>
                                        <td class="summary-value">Rp {{ number_format($ppnNominal, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr class="summary-total">
                                    <td class="summary-label">Total Penjualan</td>
                                    <td class="summary-value">Rp {{ number_format($penjualan->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr class="summary-paid">
                                    <td class="summary-label">Total Dibayar</td>
                                    <td class="summary-value">Rp
                                        {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                                </tr>
                                @if ($penjualan->total_uang_muka > 0)
                                    <tr class="summary-paid">
                                        <td class="summary-label">Uang Muka</td>
                                        <td class="summary-value">Rp
                                            {{ number_format($penjualan->total_uang_muka, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @if ($penjualan->total - $penjualan->total_bayar - $penjualan->total_uang_muka > 0)
                                    <tr class="summary-remaining">
                                        <td class="summary-label">Sisa</td>
                                        <td class="summary-value">Rp
                                            {{ number_format($penjualan->total - $penjualan->total_bayar - $penjualan->total_uang_muka, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @empty
        <div class="no-data">
            Tidak ada data penjualan untuk periode yang dipilih
        </div>
    @endforelse

    @if (count($dataPenjualan) > 0)
        <div class="grand-summary">
            <table>
                <tr>
                    <td class="grand-summary-left">
                        <strong style="font-size: 8.5px; color: #475569;">RINGKASAN KESELURUHAN</strong>
                        <div style="font-size: 7px; color: #64748b; margin-top: 2px;">
                            Total {{ $dataPenjualan->count() }} transaksi penjualan
                        </div>
                    </td>
                    <td class="grand-summary-right">
                        <table class="grand-summary-table">
                            <tr>
                                <td class="grand-label">Total Penjualan</td>
                                <td class="grand-value">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="grand-label">Total Dibayar</td>
                                <td class="grand-value" style="color: #059669;">Rp
                                    {{ number_format($totalDibayar, 0, ',', '.') }}</td>
                            </tr>
                            @if ($totalUangMuka > 0)
                                <tr>
                                    <td class="grand-label">Uang Muka</td>
                                    <td class="grand-value" style="color: #059669;">Rp
                                        {{ number_format($totalUangMuka, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            @if ($sisaPembayaran > 0)
                                <tr>
                                    <td class="grand-label">Sisa Pembayaran</td>
                                    <td class="grand-value" style="color: #dc2626;">Rp
                                        {{ number_format($sisaPembayaran, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    @else
        <div class="no-data">
            Tidak ada data penjualan untuk periode yang dipilih
        </div>
    @endif

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $font = $fontMetrics->get_font("Arial", "normal");
            $size = 7;
            $width = $fontMetrics->get_text_width($text, $font, $size);
            $x = $pdf->get_width() - $width - 20;
            $y = $pdf->get_height() - 15;
            $pdf->text($x, $y, $text, $font, $size, array(0.4, 0.4, 0.4));
        }
    </script>
</body>

</html>
