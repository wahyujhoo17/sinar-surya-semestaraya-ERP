<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pembelian Rincian</title>
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

        .po-section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .po-header {
            background: #f1f5f9;
            padding: 6px 8px;
            margin-bottom: 4px;
            border-left: 3px solid #3b82f6;
        }

        .po-header table {
            width: 100%;
            border-collapse: collapse;
        }

        .po-main-info {
            width: 65%;
            vertical-align: top;
        }

        .po-number {
            font-size: 9px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 3px;
        }

        .po-supplier {
            font-size: 8px;
            color: #334155;
            margin-bottom: 1px;
        }

        .po-meta {
            font-size: 7px;
            color: #64748b;
        }

        .po-status-cell {
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

        .status-lunas {
            background: #d1fae5;
            color: #065f46;
        }

        .status-sebagian {
            background: #fef3c7;
            color: #92400e;
        }

        .status-belum {
            background: #fee2e2;
            color: #b91c1c;
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

        .po-summary {
            margin-top: 6px;
        }

        .po-summary table {
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

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 7px;
            color: #94a3b8;
            text-align: center;
            padding: 8px 20px;
            border-top: 1px solid #e2e8f0;
            background: white;
        }

        @page {
            margin-bottom: 40px;
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
    </style>
</head>

<body>
    <div class="header">
        <table>
            <tr>
                <td class="logo-cell">
                    @php
                        $logoSrc = null;

                        // Try company logo first
                        if (isset($company) && $company && $company->logo) {
                            $logoPath = public_path('storage/' . $company->logo);
                            if (file_exists($logoPath)) {
                                $logoData = base64_encode(file_get_contents($logoPath));
                                $logoMimeType = mime_content_type($logoPath);
                                $logoSrc = 'data:' . $logoMimeType . ';base64,' . $logoData;
                            }
                        }

                        // Fallback to default logo
                        if (!$logoSrc) {
                            $defaultLogoPath = public_path('img/logo-sinar-surya.png');
                            if (file_exists($defaultLogoPath)) {
                                $defaultLogoData = base64_encode(file_get_contents($defaultLogoPath));
                                $defaultLogoMimeType = mime_content_type($defaultLogoPath);
                                $logoSrc = 'data:' . $defaultLogoMimeType . ';base64,' . $defaultLogoData;
                            }
                        }
                    @endphp
                    @if ($logoSrc)
                        <img src="{{ $logoSrc }}" alt="Logo {{ $company->nama ?? 'Perusahaan' }}" class="logo">
                    @endif
                </td>
                <td class="header-text">
                    <h1 class="company-name">{{ $company->nama ?? 'PT SINAR SURYA SEMESTARAYA' }}</h1>
                    <h2 class="report-title">LAPORAN PEMBELIAN RINCIAN</h2>
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
                <td class="filter-label">Supplier</td>
                <td class="filter-separator">:</td>
                <td>{{ $filters['supplier_id'] ? 'Filter diterapkan' : 'Semua Supplier' }}</td>
                <td class="filter-label" style="width: 110px;">Status Pembayaran</td>
                <td class="filter-separator">:</td>
                <td>{{ $filters['status_pembayaran'] ? ucfirst($filters['status_pembayaran']) : 'Semua Status' }}</td>
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

    @forelse($dataPembelian as $index => $po)
        <div class="po-section">
            <div class="po-header">
                <table>
                    <tr>
                        <td class="po-main-info">
                            <div class="po-number">{{ $po->nomor }}</div>
                            <div class="po-supplier">Supplier: {{ $po->supplier->nama ?? 'N/A' }}
                                ({{ $po->supplier->kode ?? '-' }})
                            </div>
                            <div class="po-meta">
                                Tanggal: {{ \Carbon\Carbon::parse($po->tanggal)->format('d/m/Y') }} |
                                Petugas: {{ $po->user->name ?? '-' }}
                            </div>
                        </td>
                        <td class="po-status-cell">
                            @if ($po->status_pembayaran == 'lunas')
                                <div class="status-badge status-lunas">LUNAS</div>
                            @elseif($po->status_pembayaran == 'sebagian')
                                <div class="status-badge status-sebagian">SEBAGIAN</div>
                            @else
                                <div class="status-badge status-belum">BELUM BAYAR</div>
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
                    @foreach ($po->details as $detailIndex => $detail)
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

            <div class="po-summary">
                <table>
                    <tr>
                        <td class="summary-left"></td>
                        <td class="summary-right">
                            <table class="summary-table">
                                <tr>
                                    <td class="summary-label">Subtotal Item</td>
                                    <td class="summary-value">Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if ($po->ongkos_kirim > 0)
                                    <tr>
                                        <td class="summary-label">Ongkos Kirim</td>
                                        <td class="summary-value">Rp
                                            {{ number_format($po->ongkos_kirim, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @if ($po->ppn > 0)
                                    @php
                                        // PPN adalah persentase (0-100), bukan nominal
                                        $ppnPercentage = floatval($po->ppn);
                                        $ppnNominal = ($ppnPercentage / 100) * $po->subtotal;
                                    @endphp
                                    <tr>
                                        <td class="summary-label">PPN ({{ number_format($ppnPercentage, 0) }}%)</td>
                                        <td class="summary-value">Rp {{ number_format($ppnNominal, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr class="summary-total">
                                    <td class="summary-label">Total Pembelian</td>
                                    <td class="summary-value">Rp {{ number_format($po->total, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="summary-paid">
                                    <td class="summary-label">Total Dibayar</td>
                                    <td class="summary-value">Rp {{ number_format($po->total_bayar, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @if ($po->total - $po->total_bayar > 0)
                                    <tr class="summary-remaining">
                                        <td class="summary-label">Sisa</td>
                                        <td class="summary-value">Rp
                                            {{ number_format($po->total - $po->total_bayar, 0, ',', '.') }}</td>
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
            Tidak ada data pembelian untuk periode yang dipilih
        </div>
    @endforelse

    @if ($dataPembelian->count() > 0)
        <div class="grand-summary">
            <table>
                <tr>
                    <td class="grand-summary-left">
                        <strong style="font-size: 8.5px; color: #475569;">RINGKASAN KESELURUHAN</strong>
                        <div style="font-size: 7px; color: #64748b; margin-top: 2px;">
                            Total {{ $dataPembelian->count() }} transaksi pembelian
                        </div>
                    </td>
                    <td class="grand-summary-right">
                        <table class="grand-summary-table">
                            <tr>
                                <td class="grand-label">Total Pembelian</td>
                                <td class="grand-value">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="grand-label">Total Dibayar</td>
                                <td class="grand-value" style="color: #059669;">Rp
                                    {{ number_format($totalDibayar, 0, ',', '.') }}</td>
                            </tr>
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
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem ERP SemestaPro</p>
        <p>Laporan Pembelian Rincian - {{ now()->format('Y') }}</p>
    </div>

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
