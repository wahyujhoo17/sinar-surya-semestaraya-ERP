<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Buku Besar</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            line-height: 1.4;
            color: #2d3748;
            margin: 10px 15px;
        }

        .header {
            margin-bottom: 10px;
        }

        .header table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 60px;
            vertical-align: middle;
            padding-right: 8px;
        }

        .logo {
            width: 50px;
            height: auto;
        }

        .header-text {
            vertical-align: middle;
        }

        .company-name {
            font-size: 12px;
            font-weight: bold;
            color: #1e293b;
            margin: 0 0 2px 0;
        }

        .report-title {
            font-size: 10px;
            font-weight: bold;
            color: #334155;
            margin: 0;
        }

        .report-period {
            font-size: 7px;
            color: #64748b;
            margin: 2px 0 0 0;
        }

        .print-info {
            text-align: right;
            vertical-align: middle;
        }

        .print-date {
            font-size: 6.5px;
            color: #64748b;
        }

        .divider {
            border-top: 2px solid #3b82f6;
            margin: 6px 0;
        }

        .filter-info {
            margin-bottom: 8px;
            background: #f8fafc;
            padding: 5px 6px;
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
            width: 80px;
            font-weight: 600;
            color: #334155;
        }

        .filter-separator {
            width: 8px;
            text-align: center;
        }

        /* Account Section */
        .account-section {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .account-header {
            background: #f1f5f9;
            padding: 5px 6px;
            margin-bottom: 3px;
            border-left: 3px solid #3b82f6;
        }

        .account-code {
            font-size: 8px;
            font-weight: bold;
            color: #1e293b;
        }

        .account-name {
            font-size: 7px;
            color: #334155;
            margin-top: 1px;
        }

        .account-category {
            font-size: 6.5px;
            color: #64748b;
            margin-top: 1px;
        }

        /* Transaction Table */
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .transaction-table thead tr {
            background: #f9fafb;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        .transaction-table th {
            font-size: 6.5px;
            font-weight: 600;
            color: #475569;
            text-align: left;
            padding: 3px 4px;
        }

        .transaction-table th.text-center {
            text-align: center;
        }

        .transaction-table th.text-right {
            text-align: right;
        }

        .transaction-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
        }

        .transaction-table td {
            font-size: 7px;
            padding: 3px 4px;
            color: #334155;
            vertical-align: top;
        }

        .transaction-table td.text-center {
            text-align: center;
        }

        .transaction-table td.text-right {
            text-align: right;
        }

        .opening-balance {
            background: #fef3c7;
            font-weight: 600;
        }

        .closing-balance {
            background: #e0f2fe;
            font-weight: 600;
        }

        .balance-positive {
            color: #059669;
        }

        .balance-negative {
            color: #dc2626;
        }

        /* Summary Table for All Accounts View */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .summary-table thead tr {
            background: #1e293b;
            color: white;
        }

        .summary-table th {
            font-size: 7px;
            font-weight: 600;
            padding: 4px;
            text-align: left;
        }

        .summary-table th.text-right {
            text-align: right;
        }

        .summary-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .summary-table tbody tr:hover {
            background: #f8fafc;
        }

        .summary-table td {
            font-size: 7px;
            padding: 3px 4px;
        }

        .summary-table td.text-right {
            text-align: right;
        }

        .category-header {
            background: #f1f5f9;
            font-weight: bold;
            color: #1e293b;
        }

        .category-total {
            background: #dbeafe;
            font-weight: bold;
        }

        .grand-total {
            background: #1e293b;
            color: white;
            font-weight: bold;
            font-size: 8px;
        }

        .page-number {
            position: fixed;
            bottom: 8px;
            right: 15px;
            font-size: 6.5px;
            color: #64748b;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #94a3b8;
            font-size: 7.5px;
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
                    <h2 class="report-title">BUKU BESAR</h2>
                    <p class="report-period">
                        Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') }} -
                        {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}
                        @if ($selectedPeriode)
                            ({{ $selectedPeriode->nama }})
                        @endif
                    </p>
                </td>
                <td class="print-info">
                    <div class="print-date">
                        Dicetak: {{ now()->format('d/m/Y H:i') }}
                    </div>
                    @if ($includeDrafts)
                        <div class="print-date" style="color: #dc2626; font-weight: bold;">
                            *Termasuk Draft
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    {{-- Display detailed transactions for all accounts --}}
    @forelse($bukuBesarData as $data)
        <div class="account-section">
            <div class="account-header">
                <div class="account-code">{{ $data['akun']->kode }} - {{ $data['akun']->nama }}</div>
                <div class="account-category">
                    Kategori: {{ ucfirst($data['akun']->kategori) }} | Tipe: {{ ucfirst($data['akun']->tipe) }}
                </div>
            </div>

            @if (count($data['transaksi']) > 0 || $data['opening_balance'] != 0)
                <table class="transaction-table">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Tanggal</th>
                            <th style="width: 15%;">No. Referensi</th>
                            <th style="width: 30%;">Keterangan</th>
                            <th style="width: 13%;" class="text-right">Debit</th>
                            <th style="width: 13%;" class="text-right">Kredit</th>
                            <th style="width: 19%;" class="text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Opening Balance --}}
                        <tr class="opening-balance">
                            <td>{{ \Carbon\Carbon::parse($tanggalAwal)->subDay()->format('d/m/Y') }}</td>
                            <td>-</td>
                            <td>SALDO AWAL PERIODE</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td
                                class="text-right {{ $data['opening_balance'] >= 0 ? 'balance-positive' : 'balance-negative' }}">
                                Rp
                                {{ number_format(abs($data['opening_balance']), 0, ',', '.') }}{{ $data['opening_balance'] < 0 ? ' (-)' : '' }}
                            </td>
                        </tr>

                        {{-- Transactions --}}
                        @foreach ($data['transaksi'] as $item)
                            @php
                                $trx = $item['transaksi'];
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}</td>
                                <td>{{ $trx->no_referensi ?? '-' }}</td>
                                <td>{{ $trx->keterangan ?? '-' }}</td>
                                <td class="text-right">
                                    {{ $trx->debit > 0 ? 'Rp ' . number_format($trx->debit, 0, ',', '.') : '-' }}
                                </td>
                                <td class="text-right">
                                    {{ $trx->kredit > 0 ? 'Rp ' . number_format($trx->kredit, 0, ',', '.') : '-' }}
                                </td>
                                <td
                                    class="text-right {{ $item['saldo'] >= 0 ? 'balance-positive' : 'balance-negative' }}">
                                    Rp
                                    {{ number_format(abs($item['saldo']), 0, ',', '.') }}{{ $item['saldo'] < 0 ? ' (-)' : '' }}
                                </td>
                            </tr>
                        @endforeach

                        {{-- Period Total --}}
                        <tr style="background: #f9fafb; border-top: 1px solid #cbd5e1;">
                            <td colspan="3" style="text-align: right; font-weight: 600; padding-right: 5px;">
                                Total Mutasi:
                            </td>
                            <td class="text-right" style="font-weight: 600;">
                                Rp {{ number_format($data['period_debit'], 0, ',', '.') }}
                            </td>
                            <td class="text-right" style="font-weight: 600;">
                                Rp {{ number_format($data['period_kredit'], 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>

                        {{-- Closing Balance --}}
                        <tr class="closing-balance">
                            <td colspan="5" style="padding-left: 6px; font-weight: 600;">Saldo Akhir</td>
                            <td
                                class="text-right {{ $data['ending_balance'] >= 0 ? 'balance-positive' : 'balance-negative' }}">
                                Rp
                                {{ number_format(abs($data['ending_balance']), 0, ',', '.') }}{{ $data['ending_balance'] < 0 ? ' (-)' : '' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            @else
                <div class="no-data" style="padding: 10px;">
                    Tidak ada transaksi untuk periode ini
                </div>
            @endif
        </div>
    @empty
        <div class="no-data">
            Tidak ada data buku besar untuk periode yang dipilih
        </div>
    @endforelse

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $font = $fontMetrics->get_font("Arial", "normal");
            $size = 6.5;
            $width = $fontMetrics->get_text_width($text, $font, $size);
            $x = $pdf->get_width() - $width - 15;
            $y = $pdf->get_height() - 12;
            $pdf->text($x, $y, $text, $font, $size, array(0.4, 0.4, 0.4));
        }
    </script>
</body>

</html>
