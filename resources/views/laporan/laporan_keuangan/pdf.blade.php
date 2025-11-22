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
            color: #000000;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .company-tagline {
            font-size: 12px;
            color: #000000;
            font-style: italic;
            margin-bottom: 8px;
        }

        .company-address {
            font-size: 10px;
            color: #000000;
            margin-bottom: 15px;
        }

        .report-title {
            font-size: 18px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-period {
            font-size: 12px;
            color: #000000;
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
            background-color: #ffffff;
            color: #000000;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: 2px solid #000000;
            border-bottom: 2px solid #000000;
        }

        .section-header td {
            padding: 10px;
            color: #000000;
        }

        .subsection-header {
            background-color: #ffffff;
            color: #000000;
            font-weight: bold;
            font-style: normal;
        }

        .subsection-header td {
            padding: 8px 10px;
        }

        /* Subtotal Row */
        .subtotal-row {
            background-color: #ffffff;
            font-weight: 600;
            border-top: 1px solid #000000;
        }

        .subtotal-row td {
            padding: 8px 10px;
        }

        /* Total Rows */
        .total-row {
            background-color: #ffffff;
            color: #000000;
            font-weight: bold;
            border-top: 2px solid #000000;
            border-bottom: 1px solid #000000;
        }

        .grand-total {
            background-color: #ffffff;
            color: #000000;
            font-weight: bold;
            border-top: 3px double #000000;
            border-bottom: 3px double #000000;
            font-size: 12px;
        }

        .grand-total td {
            padding: 10px;
            border-color: #000000;
        }

        /* Special Row Types */
        .category-row {
            background-color: #ffffff;
            color: #000000;
            font-weight: 600;
        }

        .expense-category {
            background-color: #ffffff;
            color: #000000;
            font-weight: 600;
        }

        .income-row {
            background-color: #ffffff;
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
                    <img src="{{ $logoSrc }}" alt="Logo {{ $company->nama ?? 'Perusahaan' }}" class="company-logo">
                @endif

                <div class="company-info">
                    <div class="company-name">{{ $company->nama ?? 'PT SINAR SURYA SEMESTARAYA' }}</div>
                    {{-- <div class="company-tagline">{{ $company->footer_text ?? 'Bersama Membangun Masa Depan' }}</div> --}}
                </div>
            </div>
            <div class="company-address">
                @if ($company)
                    {{ $company->alamat ?? '' }}
                    @if ($company->kota)
                        {{ $company->kota }}
                    @endif
                    @if ($company->provinsi)
                        {{ $company->provinsi }}
                    @endif
                    @if ($company->kode_pos)
                        {{ $company->kode_pos }}
                    @endif
                    <br>
                    @if ($company->telepon)
                        Telp: {{ $company->telepon }}
                    @endif
                    @if ($company->email)
                        | Email: {{ $company->email }}
                    @endif
                    @if ($company->website)
                        | Website: {{ $company->website }}
                    @endif
                    @if ($company->npwp)
                        <br>NPWP: {{ $company->npwp }}
                    @endif
                @else
                    Jl. Raya Industri No. 123, Kawasan Industri Sinar Surya<br>
                    Jakarta Timur 13920, Indonesia<br>
                    Telp: (021) 8765-4321 | Fax: (021) 8765-4322<br>
                    Email: info@sinarsurya.co.id | Website: www.sinarsurya.co.id
                @endif
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
            {{-- ENHANCED BALANCE SHEET WITH DETAILED GROUPING --}}
            <table>
                <thead>
                    <tr>
                        <th style="width: 70%; text-align: left;">Keterangan</th>
                        <th style="width: 30%; text-align: right;">PER
                            {{ strtoupper(\Carbon\Carbon::parse($filters['tanggal_akhir'])->translatedFormat('d-m-Y')) }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {{-- AKTIVA (ASET) --}}
                    <tr class="section-header">
                        <td colspan="2"><strong>AKTIVA</strong></td>
                    </tr>

                    @if (isset($data['assets_grouped']['groups']))
                        @foreach ($data['assets_grouped']['groups'] as $group)
                            {{-- Group Header --}}
                            <tr class="subsection-header">
                                <td colspan="2" style="padding-left: 10px;"><strong>{{ $group['name'] }}</strong>
                                </td>
                            </tr>

                            {{-- Group Accounts --}}
                            @foreach ($group['accounts'] as $account)
                                <tr>
                                    <td style="padding-left: 20px;">- {{ strtoupper($account['nama']) }}</td>
                                    <td class="text-right">{{ number_format($account['balance'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach

                            {{-- Group Subtotal --}}
                            @if (isset($group['show_depreciation']) && $group['show_depreciation'])
                                <tr>
                                    <td style="padding-left: 30px; font-style: italic;">Nilai Perolehan</td>
                                    <td class="text-right">{{ number_format($group['subtotal'], 0, ',', '.') }}</td>
                                </tr>
                            @else
                                <tr class="subtotal-row">
                                    <td style="padding-left: 20px;"><strong>Jumlah {{ $group['name'] }}</strong></td>
                                    <td class="text-right font-semibold">
                                        {{ number_format($group['subtotal'], 0, ',', '.') }}</td>
                                </tr>
                            @endif

                            {{-- Spacer --}}
                            <tr class="no-border">
                                <td colspan="2" style="border: none; padding: 5px;">&nbsp;</td>
                            </tr>
                        @endforeach
                    @endif

                    {{-- TOTAL AKTIVA --}}
                    <tr class="total-row">
                        <td><strong>TOTAL AKTIVA</strong></td>
                        <td class="text-right font-bold">{{ number_format($data['total_assets'] ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- Main Spacer --}}
                    <tr class="no-border">
                        <td colspan="2" style="border: none; padding: 15px;">&nbsp;</td>
                    </tr>

                    {{-- KEWAJIBAN DAN EKUITAS --}}
                    <tr class="section-header">
                        <td colspan="2"><strong>KEWAJIBAN DAN EKUITAS</strong></td>
                    </tr>

                    {{-- KEWAJIBAN --}}
                    @if (isset($data['liabilities_grouped']['groups']))
                        @foreach ($data['liabilities_grouped']['groups'] as $group)
                            {{-- Group Header --}}
                            <tr class="subsection-header">
                                <td colspan="2" style="padding-left: 10px;"><strong>{{ $group['name'] }}</strong>
                                </td>
                            </tr>

                            {{-- Group Accounts --}}
                            @foreach ($group['accounts'] as $account)
                                <tr>
                                    <td style="padding-left: 20px;">- {{ strtoupper($account['nama']) }}</td>
                                    <td class="text-right">{{ number_format($account['balance'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach

                            {{-- Group Subtotal --}}
                            <tr class="subtotal-row">
                                <td style="padding-left: 20px;"><strong>Jumlah {{ $group['name'] }}</strong></td>
                                <td class="text-right font-semibold">
                                    {{ number_format($group['subtotal'], 0, ',', '.') }}</td>
                            </tr>

                            {{-- Spacer --}}
                            <tr class="no-border">
                                <td colspan="2" style="border: none; padding: 5px;">&nbsp;</td>
                            </tr>
                        @endforeach
                    @endif

                    {{-- TOTAL KEWAJIBAN --}}
                    <tr class="total-row">
                        <td><strong>TOTAL KEWAJIBAN</strong></td>
                        <td class="text-right font-bold">
                            {{ number_format($data['total_liabilities'] ?? 0, 0, ',', '.') }}</td>
                    </tr>

                    {{-- Spacer --}}
                    <tr class="no-border">
                        <td colspan="2" style="border: none; padding: 10px;">&nbsp;</td>
                    </tr>

                    {{-- EKUITAS --}}
                    @if (isset($data['equity_grouped']['groups']))
                        @foreach ($data['equity_grouped']['groups'] as $group)
                            {{-- Group Header --}}
                            <tr class="subsection-header">
                                <td colspan="2" style="padding-left: 10px;"><strong>{{ $group['name'] }}</strong>
                                </td>
                            </tr>

                            {{-- Group Accounts --}}
                            @foreach ($group['accounts'] as $account)
                                <tr>
                                    <td style="padding-left: 20px;">- {{ strtoupper($account['nama']) }}</td>
                                    <td class="text-right">{{ number_format($account['balance'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach

                            {{-- Group Subtotal --}}
                            <tr class="subtotal-row">
                                <td style="padding-left: 20px;"><strong>Jumlah {{ $group['name'] }}</strong></td>
                                <td class="text-right font-semibold">
                                    {{ number_format($group['subtotal'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif

                    {{-- TOTAL KEWAJIBAN DAN MODAL --}}
                    <tr class="grand-total"
                        style="background-color: #ffffff; color: #000000; font-weight: bold; border-top: 3px double #000000; border-bottom: 3px double #000000;">
                        <td style="color: #000000; font-weight: bold; padding: 10px;"><strong>TOTAL KEWAJIBAN DAN
                                MODAL</strong></td>
                        <td class="text-right font-bold" style="color: #000000; font-weight: bold; padding: 10px;">
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
                <p><strong>Total Aktiva:</strong> Rp {{ number_format($totalAssets, 0, ',', '.') }}</p>
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
            {{-- ENHANCED INCOME STATEMENT WITH DETAILED GROUPING --}}
            <table>
                <thead>
                    <tr>
                        <th style="width: 70%; text-align: left;">Keterangan</th>
                        <th style="width: 30%; text-align: right;">PER
                            {{ strtoupper(\Carbon\Carbon::parse($filters['tanggal_akhir'])->translatedFormat('d F Y')) }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($data['income_grouped']))
                        @php
                            $grouped = $data['income_grouped'];
                        @endphp

                        {{-- PENJUALAN / REVENUE --}}
                        <tr class="section-header">
                            <td colspan="2"><strong>PENJUALAN</strong></td>
                        </tr>

                        @if (count($grouped['revenue']['penjualan']) > 0)
                            @foreach ($grouped['revenue']['penjualan'] as $account)
                                <tr>
                                    <td style="padding-left: 20px;">{{ ucwords(strtolower($account['nama'])) }}</td>
                                    <td class="text-right">{{ number_format($account['balance'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td style="padding-left: 20px;">Trading</td>
                                <td class="text-right">{{ number_format($data['total_income'] ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif

                        {{-- Spacer --}}
                        <tr class="no-border">
                            <td colspan="2" style="border: none; padding: 3px;">&nbsp;</td>
                        </tr>

                        {{-- HARGA POKOK PENJUALAN --}}
                        <tr class="subsection-header">
                            <td colspan="2"><strong>HARGA POKOK PENJUALAN</strong></td>
                        </tr>

                        @if (count($grouped['revenue']['harga_pokok']) > 0)
                            @php
                                $persediaanAwal = 0;
                                $pembelian = 0;
                                $persediaanAkhir = 0;

                                foreach ($grouped['revenue']['harga_pokok'] as $account) {
                                    if (stripos($account['nama'], 'persediaan awal') !== false) {
                                        $persediaanAwal += $account['balance'];
                                    } elseif (stripos($account['nama'], 'pembelian') !== false) {
                                        $pembelian += $account['balance'];
                                    } elseif (stripos($account['nama'], 'persediaan akhir') !== false) {
                                        $persediaanAkhir += $account['balance'];
                                    }
                                }

                                $jumlahPersediaan = $persediaanAwal + $pembelian;
                                $hpp = $jumlahPersediaan - $persediaanAkhir;
                            @endphp

                            @if ($persediaanAwal > 0)
                                <tr>
                                    <td style="padding-left: 20px;">Persediaan Awal</td>
                                    <td class="text-right">{{ number_format($persediaanAwal, 0, ',', '.') }}</td>
                                </tr>
                            @endif

                            @if ($pembelian > 0)
                                <tr>
                                    <td style="padding-left: 20px;">Pembelian</td>
                                    <td class="text-right">{{ number_format($pembelian, 0, ',', '.') }}</td>
                                </tr>
                            @endif

                            @if ($persediaanAwal > 0 || $pembelian > 0)
                                <tr>
                                    <td style="padding-left: 20px;">Jumlah Persediaan</td>
                                    <td class="text-right">{{ number_format($jumlahPersediaan, 0, ',', '.') }}</td>
                                </tr>
                            @endif

                            @if ($persediaanAkhir > 0)
                                <tr>
                                    <td style="padding-left: 20px;">Persediaan Akhir</td>
                                    <td class="text-right">{{ number_format($persediaanAkhir, 0, ',', '.') }}</td>
                                </tr>
                            @endif

                            {{-- Harga Pokok Penjualan Total --}}
                            <tr class="subtotal-row">
                                <td style="padding-left: 30px;"><strong>Harga Pokok Penjualan</strong></td>
                                <td class="text-right font-semibold">
                                    {{ number_format($hpp > 0 ? $hpp : array_sum(array_column($grouped['revenue']['harga_pokok'], 'balance')), 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif

                        {{-- Spacer --}}
                        <tr class="no-border">
                            <td colspan="2" style="border: none; padding: 3px;">&nbsp;</td>
                        </tr>

                        {{-- LABA KOTOR --}}
                        <tr class="total-row">
                            <td style="padding-left: 30px;"><strong>LABA KOTOR</strong></td>
                            <td class="text-right font-bold">
                                {{ number_format($grouped['revenue']['laba_kotor'], 0, ',', '.') }}</td>
                        </tr>

                        {{-- Spacer --}}
                        <tr class="no-border">
                            <td colspan="2" style="border: none; padding: 10px;">&nbsp;</td>
                        </tr>

                        {{-- BIAYA OPERASIONAL --}}
                        <tr class="section-header">
                            <td colspan="2"><strong>BIAYA OPERASIONAL</strong></td>
                        </tr>

                        @if (count($grouped['operational']['biaya_umum']) > 0)
                            @foreach ($grouped['operational']['biaya_umum'] as $account)
                                <tr>
                                    <td style="padding-left: 20px;">{{ ucwords(strtolower($account['nama'])) }}</td>
                                    <td class="text-right">{{ number_format($account['balance'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif

                        @if (count($grouped['operational']['biaya_pemasaran']) > 0)
                            @foreach ($grouped['operational']['biaya_pemasaran'] as $account)
                                <tr>
                                    <td style="padding-left: 20px;">{{ ucwords(strtolower($account['nama'])) }}</td>
                                    <td class="text-right">{{ number_format($account['balance'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif

                        @if (count($grouped['operational']['biaya_penyusutan']) > 0)
                            @foreach ($grouped['operational']['biaya_penyusutan'] as $account)
                                <tr>
                                    <td style="padding-left: 20px;">{{ ucwords(strtolower($account['nama'])) }}</td>
                                    <td class="text-right">{{ number_format($account['balance'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif

                        {{-- Show all other operational expenses if no specific grouping --}}
                        @if (count($grouped['operational']['biaya_umum']) === 0 &&
                                count($grouped['operational']['biaya_pemasaran']) === 0 &&
                                count($grouped['operational']['biaya_penyusutan']) === 0)
                            @foreach ($data['expenses'] as $expense)
                                <tr>
                                    <td style="padding-left: 20px;">{{ ucwords(strtolower($expense['nama'])) }}</td>
                                    <td class="text-right">{{ number_format($expense['balance'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif

                        {{-- Jumlah Biaya Operasional --}}
                        <tr class="subtotal-row">
                            <td style="padding-left: 30px;"><strong>Jumlah Biaya Operasional</strong></td>
                            <td class="text-right font-semibold">
                                {{ number_format($grouped['operational']['total_operasional'], 0, ',', '.') }}</td>
                        </tr>

                        {{-- Spacer --}}
                        <tr class="no-border">
                            <td colspan="2" style="border: none; padding: 3px;">&nbsp;</td>
                        </tr>

                        {{-- LABA OPERASI --}}
                        <tr class="total-row">
                            <td style="padding-left: 20px;"><strong>Laba Operasi</strong></td>
                            <td class="text-right font-bold">
                                {{ number_format($grouped['operational']['laba_operasi'], 0, ',', '.') }}</td>
                        </tr>

                        {{-- Spacer --}}
                        <tr class="no-border">
                            <td colspan="2" style="border: none; padding: 10px;">&nbsp;</td>
                        </tr>

                        {{-- PENDAPATAN LAIN-LAIN & BIAYA NON OPERASIONAL --}}
                        @if (count($grouped['other']['pendapatan_lain']) > 0 || count($grouped['other']['biaya_lain']) > 0)
                            <tr class="section-header">
                                <td colspan="2"><strong>PENDAPATAN LAIN-LAIN & BIAYA NON OPERASIONAL</strong></td>
                            </tr>

                            @foreach ($grouped['other']['pendapatan_lain'] as $account)
                                <tr>
                                    <td style="padding-left: 20px;">{{ ucwords(strtolower($account['nama'])) }}</td>
                                    <td class="text-right">{{ number_format($account['balance'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach

                            @foreach ($grouped['other']['biaya_lain'] as $account)
                                <tr>
                                    <td style="padding-left: 20px;">{{ ucwords(strtolower($account['nama'])) }}</td>
                                    <td class="text-right">{{ number_format($account['balance'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach

                            <tr class="subtotal-row">
                                <td style="padding-left: 30px;"><strong>Jumlah</strong></td>
                                <td class="text-right font-semibold">
                                    {{ number_format($grouped['other']['total_other'], 0, ',', '.') }}</td>
                            </tr>

                            {{-- Spacer --}}
                            <tr class="no-border">
                                <td colspan="2" style="border: none; padding: 3px;">&nbsp;</td>
                            </tr>
                        @endif

                        {{-- LABA SEBELUM PAJAK --}}
                        <tr class="total-row">
                            <td><strong>LABA SEBELUM PAJAK</strong></td>
                            <td class="text-right font-bold">
                                {{ number_format($grouped['tax']['laba_sebelum_pajak'], 0, ',', '.') }}</td>
                        </tr>

                        {{-- PAJAK PENDAPATAN --}}
                        @if (isset($grouped['tax']['pajak_accounts']) && count($grouped['tax']['pajak_accounts']) > 0)
                            <tr>
                                <td><strong>PAJAK PENDAPATAN</strong></td>
                                <td class="text-right font-semibold">
                                    {{ number_format($grouped['tax']['pajak'], 0, ',', '.') }}</td>
                            </tr>
                        @endif

                        {{-- LABA (RUGI) BERSIH --}}
                        <tr class="grand-total">
                            <td><strong>LABA (RUGI) BERSIH</strong></td>
                            <td class="text-right font-bold">
                                {{ number_format($grouped['tax']['laba_bersih'], 0, ',', '.') }}</td>
                        </tr>
                    @else
                        {{-- Fallback to simple format if grouping not available --}}
                        <tr class="section-header">
                            <td colspan="2"><strong>PENDAPATAN</strong></td>
                        </tr>
                        @forelse($data['income'] as $income)
                            <tr>
                                <td style="padding-left: 20px;">{{ $income['nama'] }}</td>
                                <td class="text-right">{{ number_format($income['balance'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-sm"
                                    style="color: #6b7280; font-style: italic;">
                                    Tidak ada data pendapatan</td>
                            </tr>
                        @endforelse
                        <tr class="total-row">
                            <td><strong>TOTAL PENDAPATAN</strong></td>
                            <td class="text-right font-bold">
                                {{ number_format($data['total_income'] ?? 0, 0, ',', '.') }}</td>
                        </tr>

                        <tr class="no-border">
                            <td colspan="2" style="border: none; padding: 10px;">&nbsp;</td>
                        </tr>

                        <tr class="section-header">
                            <td colspan="2"><strong>BEBAN OPERASIONAL</strong></td>
                        </tr>
                        @forelse($data['expenses'] as $expense)
                            <tr>
                                <td style="padding-left: 20px;">{{ $expense['nama'] }}</td>
                                <td class="text-right">{{ number_format($expense['balance'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-sm"
                                    style="color: #6b7280; font-style: italic;">
                                    Tidak ada data beban</td>
                            </tr>
                        @endforelse
                        <tr class="total-row">
                            <td><strong>TOTAL BEBAN</strong></td>
                            <td class="text-right font-bold">
                                {{ number_format($data['total_expenses'] ?? 0, 0, ',', '.') }}</td>
                        </tr>

                        @php
                            $netIncome = ($data['total_income'] ?? 0) - ($data['total_expenses'] ?? 0);
                        @endphp
                        <tr class="grand-total">
                            <td><strong>{{ $netIncome >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' }}</strong></td>
                            <td class="text-right font-bold">{{ number_format(abs($netIncome), 0, ',', '.') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
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
                        © {{ now()->year }} {{ $company->nama ?? 'PT Sinar Surya Semestaraya' }} - Laporan ini
                        dibuat secara otomatis oleh
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
                        <li>Dokumen ini telah diverifikasi dan disetujui oleh sistem manajemen
                            {{ $company->nama ?? 'PT Sinar Surya Semestaraya' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
