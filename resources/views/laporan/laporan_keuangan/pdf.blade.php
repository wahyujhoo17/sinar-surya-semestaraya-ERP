<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ $reportType === 'balance_sheet' ? 'Neraca' : ($reportType === 'income_statement' ? 'Laporan Laba Rugi' : 'Laporan Arus Kas') }}
    </title>
    <style>
        @page {
            margin: 2cm 1.5cm;
            size: A4;
        }

        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 100%;
        }

        /* Header Styles */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            gap: 15px;
        }

        .company-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .company-info {
            text-align: center;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .company-tagline {
            font-size: 12px;
            color: #6b7280;
            font-style: italic;
            margin-bottom: 8px;
        }

        .company-address {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 15px;
        }

        .report-title {
            font-size: 18px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-period {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 20px;
            font-weight: 500;
        }

        /* Table Styles */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 25px;
            border: 2px solid #e5e7eb;
        }

        th {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #374151;
            font-weight: bold;
            padding: 12px 10px;
            text-align: left;
            border: 1px solid #d1d5db;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        td {
            padding: 8px 10px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tr:hover {
            background-color: #f3f4f6;
        }

        /* Text Alignment */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        /* Typography */
        .font-bold {
            font-weight: bold;
        }

        .font-semibold {
            font-weight: 600;
        }

        .text-sm {
            font-size: 10px;
        }

        .text-lg {
            font-size: 13px;
        }

        /* Section Headers */
        .section-header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-header td {
            border-color: #1d4ed8;
        }

        .subsection-header {
            background-color: #e0f2fe;
            color: #0369a1;
            font-weight: bold;
            font-style: italic;
        }

        /* Total Rows */
        .total-row {
            background-color: #f1f5f9;
            font-weight: bold;
            border-top: 2px solid #64748b;
            border-bottom: 1px solid #64748b;
        }

        .grand-total {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            font-weight: bold;
            border-top: 3px double #047857;
            border-bottom: 3px double #047857;
            font-size: 12px;
        }

        .grand-total td {
            border-color: #047857;
        }

        /* Special Row Types */
        .category-row {
            background-color: #fef3c7;
            color: #92400e;
            font-weight: 600;
            border-left: 4px solid #f59e0b;
        }

        .expense-category {
            background-color: #fef2f2;
            color: #991b1b;
            font-weight: 600;
            border-left: 4px solid #ef4444;
        }

        .income-row {
            background-color: #f0fdf4;
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        /* Summary Boxes */
        .summary-box {
            margin: 20px 0;
            padding: 15px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            page-break-inside: avoid;
        }

        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 10px;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 5px;
        }

        .summary-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            border-top: 2px solid #e5e7eb;
            padding-top: 15px;
            font-size: 9px;
            color: #6b7280;
            text-align: center;
        }

        .print-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }

        /* Utility Classes */
        .no-border {
            border: none !important;
        }

        .border-double {
            border-top: 3px double #374151;
            border-bottom: 3px double #374151;
        }

        .highlight {
            background-color: #fef3c7;
            font-weight: bold;
        }

        /* Responsive adjustments for print */
        @media print {
            body {
                font-size: 10px;
            }

            .header {
                margin-bottom: 20px;
            }

            .company-name {
                font-size: 20px;
            }

            .report-title {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- Enhanced Header --}}
        <div class="header">
            <div class="logo-container">
                <img src="{{ public_path('img/logo-sinar-surya.png') }}" alt="Logo Sinar Surya Semestaraya"
                    class="company-logo">
                <div class="company-info">
                    <div class="company-name">PT SINAR SURYA SEMESTARAYA</div>
                    <div class="company-tagline">"Bersama Membangun Masa Depan"</div>
                </div>
            </div>
            <div class="company-address">
                Jl. Raya Industri No. 123, Kawasan Industri Sinar Surya<br>
                Jakarta Timur 13920, Indonesia<br>
                Telp: (021) 8765-4321 | Fax: (021) 8765-4322<br>
                Email: info@sinarsurya.co.id | Website: www.sinarsurya.co.id
            </div>
            <div class="report-title">
                {{ $reportType === 'balance_sheet' ? 'NERACA' : ($reportType === 'income_statement' ? 'LAPORAN LABA RUGI' : 'LAPORAN ARUS KAS') }}
            </div>
            <div class="report-period">
                @if ($reportType === 'balance_sheet')
                    Per tanggal:
                    {{ $filters['tanggal_akhir'] ? \Carbon\Carbon::parse($filters['tanggal_akhir'])->translatedFormat('d F Y') : 'N/A' }}
                @else
                    Periode:
                    {{ $filters['tanggal_awal'] ? \Carbon\Carbon::parse($filters['tanggal_awal'])->translatedFormat('d F Y') : 'N/A' }}
                    s/d
                    {{ $filters['tanggal_akhir'] ? \Carbon\Carbon::parse($filters['tanggal_akhir'])->translatedFormat('d F Y') : 'N/A' }}
                @endif
            </div>
        </div>

        @if ($reportType === 'balance_sheet')
            {{-- ENHANCED BALANCE SHEET --}}
            <table>
                <thead>
                    <tr>
                        <th style="width: 12%">Kode</th>
                        <th style="width: 58%">Nama Akun</th>
                        <th style="width: 30%" class="text-right">Saldo (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- ASET --}}
                    <tr class="section-header">
                        <td colspan="3" class="text-center"><strong>ASET</strong></td>
                    </tr>
                    @forelse($data['assets'] as $asset)
                        <tr>
                            <td class="text-sm">{{ $asset['kode'] }}</td>
                            <td>{{ $asset['nama'] }}</td>
                            <td class="text-right font-semibold">{{ number_format($asset['balance'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-sm" style="color: #6b7280; font-style: italic;">
                                Tidak ada data aset</td>
                        </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="2" class="font-bold">TOTAL ASET</td>
                        <td class="text-right font-bold">{{ number_format($data['total_assets'] ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- Spacer --}}
                    <tr class="no-border">
                        <td colspan="3" style="border: none; padding: 10px;">&nbsp;</td>
                    </tr>

                    {{-- KEWAJIBAN --}}
                    <tr class="section-header">
                        <td colspan="3" class="text-center"><strong>KEWAJIBAN</strong></td>
                    </tr>
                    @forelse($data['liabilities'] as $liability)
                        <tr>
                            <td class="text-sm">{{ $liability['kode'] }}</td>
                            <td>{{ $liability['nama'] }}</td>
                            <td class="text-right font-semibold">
                                {{ number_format($liability['balance'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-sm" style="color: #6b7280; font-style: italic;">
                                Tidak ada data kewajiban</td>
                        </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="2" class="font-bold">TOTAL KEWAJIBAN</td>
                        <td class="text-right font-bold">
                            {{ number_format($data['total_liabilities'] ?? 0, 0, ',', '.') }}</td>
                    </tr>

                    {{-- Spacer --}}
                    <tr class="no-border">
                        <td colspan="3" style="border: none; padding: 10px;">&nbsp;</td>
                    </tr>

                    {{-- EKUITAS --}}
                    <tr class="section-header">
                        <td colspan="3" class="text-center"><strong>EKUITAS</strong></td>
                    </tr>
                    @forelse($data['equity'] as $equity)
                        <tr>
                            <td class="text-sm">{{ $equity['kode'] }}</td>
                            <td>{{ $equity['nama'] }}</td>
                            <td class="text-right font-semibold">{{ number_format($equity['balance'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-sm" style="color: #6b7280; font-style: italic;">
                                Tidak ada data ekuitas</td>
                        </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="2" class="font-bold">TOTAL EKUITAS</td>
                        <td class="text-right font-bold">{{ number_format($data['total_equity'] ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- TOTAL KEWAJIBAN DAN EKUITAS --}}
                    <tr class="grand-total">
                        <td colspan="2" class="font-bold">TOTAL KEWAJIBAN DAN EKUITAS</td>
                        <td class="text-right font-bold">
                            {{ number_format(($data['total_liabilities'] ?? 0) + ($data['total_equity'] ?? 0), 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- Balance Verification --}}
            <div class="summary-box">
                <div class="summary-title">Verifikasi Neraca</div>
                @php
                    $totalAssets = $data['total_assets'] ?? 0;
                    $totalLiabEquity = ($data['total_liabilities'] ?? 0) + ($data['total_equity'] ?? 0);
                    $isBalanced = abs($totalAssets - $totalLiabEquity) < 1;
                @endphp
                <p><strong>Total Aset:</strong> Rp {{ number_format($totalAssets, 0, ',', '.') }}</p>
                <p><strong>Total Kewajiban + Ekuitas:</strong> Rp {{ number_format($totalLiabEquity, 0, ',', '.') }}
                </p>
                <p style="color: {{ $isBalanced ? '#059669' : '#dc2626' }}; font-weight: bold;">
                    Status: {{ $isBalanced ? '✓ Neraca Seimbang' : '✗ Neraca Tidak Seimbang' }}
                    @if (!$isBalanced)
                        <br><span style="font-size: 10px;">Selisih: Rp
                            {{ number_format(abs($totalAssets - $totalLiabEquity), 0, ',', '.') }}</span>
                    @endif
                </p>
            </div>
        @elseif($reportType === 'income_statement')
            {{-- ENHANCED INCOME STATEMENT --}}
            <table>
                <thead>
                    <tr>
                        <th style="width: 12%">Kode</th>
                        <th style="width: 58%">Keterangan</th>
                        <th style="width: 30%" class="text-right">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- PENDAPATAN --}}
                    <tr class="section-header">
                        <td colspan="3" class="text-center"><strong>PENDAPATAN</strong></td>
                    </tr>
                    @forelse($data['income'] as $income)
                        <tr class="income-row">
                            <td class="text-sm">{{ $income['kode'] }}</td>
                            <td>{{ $income['nama'] }}</td>
                            <td class="text-right font-semibold">{{ number_format($income['balance'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-sm" style="color: #6b7280; font-style: italic;">
                                Tidak ada data pendapatan</td>
                        </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="2" class="font-bold">TOTAL PENDAPATAN</td>
                        <td class="text-right font-bold">{{ number_format($data['total_income'] ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- Spacer --}}
                    <tr class="no-border">
                        <td colspan="3" style="border: none; padding: 10px;">&nbsp;</td>
                    </tr>

                    {{-- BEBAN OPERASIONAL --}}
                    <tr class="section-header">
                        <td colspan="3" class="text-center"><strong>BEBAN OPERASIONAL</strong></td>
                    </tr>
                    @forelse($data['expenses'] as $expense)
                        <tr class="expense-category">
                            <td class="text-sm">{{ $expense['kode'] }}</td>
                            <td>{{ $expense['nama'] }}</td>
                            <td class="text-right font-semibold">{{ number_format($expense['balance'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-sm" style="color: #6b7280; font-style: italic;">
                                Tidak ada data beban</td>
                        </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="2" class="font-bold">TOTAL BEBAN OPERASIONAL</td>
                        <td class="text-right font-bold">
                            {{ number_format($data['total_expenses'] ?? 0, 0, ',', '.') }}</td>
                    </tr>

                    {{-- LABA/RUGI BERSIH --}}
                    @php
                        $netIncome = ($data['total_income'] ?? 0) - ($data['total_expenses'] ?? 0);
                    @endphp
                    <tr class="grand-total">
                        <td colspan="2" class="font-bold">{{ $netIncome >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' }}
                        </td>
                        <td class="text-right font-bold">{{ number_format(abs($netIncome), 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Financial Summary --}}
            <div class="summary-box">
                <div class="summary-title">Ringkasan Kinerja Keuangan</div>
                <table style="border: none; margin: 0;">
                    <tr style="background: none; border: none;">
                        <td style="border: none; padding: 5px 0; width: 60%;">Total Pendapatan:</td>
                        <td style="border: none; padding: 5px 0; text-align: right; font-weight: bold;">Rp
                            {{ number_format($data['total_income'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="background: none; border: none;">
                        <td style="border: none; padding: 5px 0;">Total Beban:</td>
                        <td style="border: none; padding: 5px 0; text-align: right; font-weight: bold;">Rp
                            {{ number_format($data['total_expenses'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="background: none; border: none;">
                        <td style="border: none; padding: 5px 0; border-top: 2px solid #374151; font-weight: bold;">
                            {{ $netIncome >= 0 ? 'Laba Bersih:' : 'Rugi Bersih:' }}</td>
                        <td
                            style="border: none; padding: 5px 0; border-top: 2px solid #374151; text-align: right; font-weight: bold; color: {{ $netIncome >= 0 ? '#059669' : '#dc2626' }};">
                            Rp {{ number_format(abs($netIncome), 0, ',', '.') }}</td>
                    </tr>
                    @if (($data['total_income'] ?? 0) > 0)
                        <tr style="background: none; border: none;">
                            <td style="border: none; padding: 5px 0; font-size: 10px;">Margin Laba:</td>
                            <td
                                style="border: none; padding: 5px 0; text-align: right; font-size: 10px; font-weight: bold;">
                                {{ number_format(($netIncome / ($data['total_income'] ?? 1)) * 100, 2) }}%</td>
                        </tr>
                    @endif
                </table>
            </div>
        @else
            {{-- ENHANCED CASH FLOW REPORT --}}
            <table>
                <thead>
                    <tr>
                        <th style="width: 50%">Uraian</th>
                        <th style="width: 25%" class="text-right">Kas Masuk (Rp)</th>
                        <th style="width: 25%" class="text-right">Kas Keluar (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- AKTIVITAS OPERASIONAL - KAS --}}
                    <tr class="section-header">
                        <td colspan="3" class="text-center"><strong>AKTIVITAS OPERASIONAL - TRANSAKSI KAS</strong>
                        </td>
                    </tr>
                    @forelse($data['kas_transactions'] as $kasTransaction)
                        <tr>
                            <td>{{ $kasTransaction->kas->nama ?? 'Kas Utama' }}</td>
                            <td class="text-right font-semibold" style="color: #059669;">
                                {{ number_format($kasTransaction->total_masuk, 0, ',', '.') }}</td>
                            <td class="text-right font-semibold" style="color: #dc2626;">
                                {{ number_format($kasTransaction->total_keluar, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-sm"
                                style="color: #6b7280; font-style: italic;">Tidak ada transaksi kas dalam periode ini
                            </td>
                        </tr>
                    @endforelse
                    <tr class="total-row">
                        <td class="font-bold">SUBTOTAL TRANSAKSI KAS</td>
                        <td class="text-right font-bold">
                            {{ number_format($data['total_kas_masuk'] ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right font-bold">
                            {{ number_format($data['total_kas_keluar'] ?? 0, 0, ',', '.') }}</td>
                    </tr>

                    {{-- Spacer --}}
                    <tr class="no-border">
                        <td colspan="3" style="border: none; padding: 10px;">&nbsp;</td>
                    </tr>

                    {{-- AKTIVITAS OPERASIONAL - BANK --}}
                    <tr class="section-header">
                        <td colspan="3" class="text-center"><strong>AKTIVITAS OPERASIONAL - TRANSAKSI BANK</strong>
                        </td>
                    </tr>
                    @forelse($data['bank_transactions'] as $bankTransaction)
                        <tr>
                            <td>{{ $bankTransaction->rekening->nama_rekening ?? ($bankTransaction->rekening->nama_bank ?? 'Rekening Bank') }}
                            </td>
                            <td class="text-right font-semibold" style="color: #059669;">
                                {{ number_format($bankTransaction->total_masuk, 0, ',', '.') }}</td>
                            <td class="text-right font-semibold" style="color: #dc2626;">
                                {{ number_format($bankTransaction->total_keluar, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-sm"
                                style="color: #6b7280; font-style: italic;">Tidak ada transaksi bank dalam periode ini
                            </td>
                        </tr>
                    @endforelse
                    <tr class="total-row">
                        <td class="font-bold">SUBTOTAL TRANSAKSI BANK</td>
                        <td class="text-right font-bold">
                            {{ number_format($data['total_bank_masuk'] ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right font-bold">
                            {{ number_format($data['total_bank_keluar'] ?? 0, 0, ',', '.') }}</td>
                    </tr>

                    {{-- TOTAL ARUS KAS --}}
                    <tr class="grand-total">
                        <td class="font-bold">TOTAL ARUS KAS MASUK</td>
                        <td class="text-right font-bold">{{ number_format($data['total_masuk'] ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="text-right font-bold">-</td>
                    </tr>
                    <tr class="grand-total">
                        <td class="font-bold">TOTAL ARUS KAS KELUAR</td>
                        <td class="text-right font-bold">-</td>
                        <td class="text-right font-bold">{{ number_format($data['total_keluar'] ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>

                    @php
                        $netCashFlow = ($data['total_masuk'] ?? 0) - ($data['total_keluar'] ?? 0);
                    @endphp
                    <tr class="grand-total">
                        <td class="font-bold">
                            {{ $netCashFlow >= 0 ? 'ARUS KAS BERSIH POSITIF' : 'ARUS KAS BERSIH NEGATIF' }}</td>
                        <td colspan="2" class="text-right font-bold"
                            style="color: {{ $netCashFlow >= 0 ? '#059669' : '#dc2626' }};">
                            {{ number_format(abs($netCashFlow), 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- Cash Flow Analysis --}}
            <div class="summary-box">
                <div class="summary-title">Analisis Arus Kas</div>
                <table style="border: none; margin: 0;">
                    <tr style="background: none; border: none;">
                        <td style="border: none; padding: 5px 0; width: 60%;">Total Penerimaan Kas:</td>
                        <td
                            style="border: none; padding: 5px 0; text-align: right; font-weight: bold; color: #059669;">
                            Rp {{ number_format($data['total_masuk'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="background: none; border: none;">
                        <td style="border: none; padding: 5px 0;">Total Pengeluaran Kas:</td>
                        <td
                            style="border: none; padding: 5px 0; text-align: right; font-weight: bold; color: #dc2626;">
                            Rp {{ number_format($data['total_keluar'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="background: none; border: none;">
                        <td style="border: none; padding: 5px 0; border-top: 2px solid #374151; font-weight: bold;">
                            Arus Kas Bersih:</td>
                        <td
                            style="border: none; padding: 5px 0; border-top: 2px solid #374151; text-align: right; font-weight: bold; color: {{ $netCashFlow >= 0 ? '#059669' : '#dc2626' }};">
                            Rp {{ number_format(abs($netCashFlow), 0, ',', '.') }}
                            <span style="font-size: 10px;">({{ $netCashFlow >= 0 ? 'Surplus' : 'Defisit' }})</span>
                        </td>
                    </tr>
                    <tr style="background: none; border: none;">
                        <td style="border: none; padding: 5px 0; font-size: 10px;">Rasio Kas Masuk/Keluar:</td>
                        <td
                            style="border: none; padding: 5px 0; text-align: right; font-size: 10px; font-weight: bold;">
                            {{ ($data['total_keluar'] ?? 0) > 0 ? number_format(($data['total_masuk'] ?? 0) / ($data['total_keluar'] ?? 1), 2) : '∞' }}
                        </td>
                    </tr>
                </table>
            </div>
        @endif

        {{-- Enhanced Footer --}}
        <div class="footer">
            <div class="print-info">
                <div>
                    <strong>Dicetak pada:</strong> {{ now()->translatedFormat('d F Y') }} pukul
                    {{ now()->format('H:i:s') }} WIB
                </div>
                <div>
                    <strong>Halaman:</strong> <span class="pagenum"></span>
                </div>
            </div>
            <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #d1d5db;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 8px; color: #6b7280;">
                        © {{ now()->year }} PT Sinar Surya Semestaraya - Laporan ini dibuat secara otomatis oleh
                        sistem ERP
                    </div>
                    <div style="font-size: 8px; color: #6b7280;">
                        {{ $reportType === 'balance_sheet' ? 'Neraca' : ($reportType === 'income_statement' ? 'Laporan Laba Rugi' : 'Laporan Arus Kas') }}
                    </div>
                </div>
            </div>

            {{-- Report Validation Notes --}}
            <div
                style="margin-top: 15px; padding: 10px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px;">
                <div style="font-size: 9px; color: #374151;">
                    <strong>Catatan:</strong>
                    <ul style="margin: 5px 0 0 15px; padding: 0;">
                        <li>Laporan ini disusun berdasarkan data transaksi yang tercatat dalam sistem periode
                            {{ $filters['tanggal_awal'] ? \Carbon\Carbon::parse($filters['tanggal_awal'])->translatedFormat('d F Y') : '' }}
                            {{ $reportType !== 'balance_sheet' ? 's/d ' . \Carbon\Carbon::parse($filters['tanggal_akhir'])->translatedFormat('d F Y') : '' }}
                        </li>
                        @if ($reportType === 'balance_sheet')
                            <li>Neraca menunjukkan posisi keuangan perusahaan per tanggal
                                {{ \Carbon\Carbon::parse($filters['tanggal_akhir'])->translatedFormat('d F Y') }}</li>
                        @elseif($reportType === 'income_statement')
                            <li>Laporan laba rugi menunjukkan kinerja operasional perusahaan selama periode tersebut
                            </li>
                        @else
                            <li>Laporan arus kas menunjukkan pergerakan kas masuk dan keluar selama periode tersebut
                            </li>
                        @endif
                        <li>Semua angka dalam laporan ini telah disesuaikan dengan prinsip akuntansi yang berlaku umum
                        </li>
                        <li>Dokumen ini telah diverifikasi dan disetujui oleh sistem manajemen PT Sinar Surya
                            Semestaraya</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
