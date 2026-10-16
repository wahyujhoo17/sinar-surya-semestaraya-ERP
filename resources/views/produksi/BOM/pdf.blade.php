<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bill of Material (BOM)</title>
    <style>
        @page {
            margin: 20px 25px 30px 25px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #1F2937;
            line-height: 1.3;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #1E3A8A;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .logo {
            max-height: 55px;
            max-width: 90px;
        }

        .company-name {
            font-size: 15px;
            font-weight: bold;
            color: #1E3A8A;
            text-transform: uppercase;
        }

        .company-sub {
            font-size: 8.5px;
            color: #4B5563;
        }

        .report-title-box {
            text-align: right;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-meta {
            font-size: 8.5px;
            color: #6B7280;
            margin-top: 2px;
        }

        /* Filter & Summary */
        .info-grid {
            width: 100%;
            margin-bottom: 12px;
        }

        .info-card {
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 4px;
            padding: 6px 10px;
        }

        .info-label {
            font-weight: bold;
            color: #4B5563;
            font-size: 8.5px;
            text-transform: uppercase;
        }

        .info-value {
            color: #111827;
            font-size: 9.5px;
            font-weight: 500;
        }

        .stat-badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 3px;
        }

        .stat-active {
            background-color: #DEF7EC;
            color: #03543F;
        }

        .stat-inactive {
            background-color: #FDE8E8;
            color: #9B1C1C;
        }

        /* Data Table */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table.data-table th {
            background-color: #1E3A8A;
            color: #FFFFFF;
            font-weight: bold;
            font-size: 8.5px;
            text-transform: uppercase;
            padding: 6px 4px;
            border: 1px solid #1E3A8A;
            text-align: center;
        }

        table.data-table td {
            border: 1px solid #D1D5DB;
            padding: 5px 4px;
            font-size: 8.5px;
            vertical-align: middle;
        }

        table.data-table tr:nth-child(even) {
            background-color: #F9FAFB;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .total-row td {
            background-color: #F3F4F6 !important;
            font-weight: bold;
            border-top: 2px solid #1E3A8A;
            font-size: 9px;
        }

        /* Signatures */
        .signature-table {
            width: 100%;
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .signature-table td {
            text-align: center;
            vertical-align: top;
            width: 33.3%;
            padding: 0 15px;
        }

        .signature-space {
            height: 48px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            font-size: 9.5px;
        }

        .signature-title {
            font-size: 8px;
            color: #6B7280;
        }
    </style>
</head>
<body>
    @php
        $logoSrc = null;
        if (isset($company) && $company && $company->logo) {
            $logoPath = public_path('storage/' . $company->logo);
            if (file_exists($logoPath)) {
                $logoData = base64_encode(file_get_contents($logoPath));
                $logoMimeType = mime_content_type($logoPath);
                $logoSrc = 'data:' . $logoMimeType . ';base64,' . $logoData;
            }
        }
        if (!$logoSrc) {
            $defaultLogoPath = public_path('img/logo-sinar-surya.png');
            if (file_exists($defaultLogoPath)) {
                $defaultLogoData = base64_encode(file_get_contents($defaultLogoPath));
                $defaultLogoMimeType = mime_content_type($defaultLogoPath);
                $logoSrc = 'data:' . $defaultLogoMimeType . ';base64,' . $defaultLogoData;
            }
        }
    @endphp

    {{-- Header --}}
    <table class="header-table">
        <tr>
            <td style="width: 10%; vertical-align: middle;">
                @if ($logoSrc)
                    <img src="{{ $logoSrc }}" class="logo" alt="Logo">
                @endif
            </td>
            <td style="width: 50%; vertical-align: middle; padding-left: 8px;">
                <div class="company-name">{{ $company->nama ?? 'PT SINAR SURYA SEMESTARAYA' }}</div>
                <div class="company-sub">
                    {{ $company->alamat ?? 'Jl. Raya Industri No. 88' }}
                    @if(!empty($company->kota)), {{ $company->kota }}@endif
                    @if(!empty($company->telepon)) | Telp: {{ $company->telepon }}@endif
                    @if(!empty($company->email)) | Email: {{ $company->email }}@endif
                </div>
            </td>
            <td class="report-title-box" style="width: 40%; vertical-align: middle;">
                <div class="report-title">Laporan Bill of Material</div>
                <div class="report-meta">
                    Tanggal Cetak: {{ now()->translatedFormat('d F Y H:i') }} | User: {{ auth()->user()->name ?? 'Admin' }}
                </div>
            </td>
        </tr>
    </table>

    {{-- Filter Information & Summary Bar --}}
    <table class="info-grid">
        <tr>
            <td style="width: 60%; vertical-align: top;">
                <div class="info-card">
                    <table style="width: 100%;">
                        <tr>
                            <td class="info-label" style="width: 25%;">Periode Filter</td>
                            <td class="info-value">
                                @if(!empty($filters['start_date']) || !empty($filters['end_date']))
                                    {{ !empty($filters['start_date']) ? \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') : 'Awal' }}
                                    s/d
                                    {{ !empty($filters['end_date']) ? \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') : 'Sekarang' }}
                                @else
                                    Semua Periode
                                @endif
                            </td>
                            <td class="info-label" style="width: 20%;">Status BOM</td>
                            <td class="info-value">
                                @if(isset($filters['status']) && $filters['status'] !== '')
                                    {{ $filters['status'] == '1' ? 'Hanya BOM Aktif' : 'Hanya BOM Non Aktif' }}
                                @else
                                    Semua Status
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">Pencarian</td>
                            <td class="info-value">{{ !empty($filters['search_query']) ? $filters['search_query'] : (!empty($filters['search']) ? $filters['search'] : '-') }}</td>
                            <td class="info-label">Status Biaya</td>
                            <td class="info-value">
                                @if(!empty($filters['cost_status']))
                                    {{ $filters['cost_status'] === 'ready' ? 'Siap Produksi' : ($filters['cost_status'] === 'no_cost' ? 'Belum Ada Harga' : 'Kosong') }}
                                @else
                                    Semua
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td style="width: 2%;"></td>
            <td style="width: 38%; vertical-align: top;">
                <div class="info-card" style="background-color: #EFF6FF; border-color: #BFDBFE;">
                    <table style="width: 100%; text-align: center;">
                        <tr>
                            <td>
                                <div class="info-label">Total BOM</div>
                                <div style="font-size: 13px; font-weight: bold; color: #1E3A8A;">{{ $totalBOM }}</div>
                            </td>
                            <td>
                                <div class="info-label">Aktif</div>
                                <div style="font-size: 13px; font-weight: bold; color: #047857;">{{ $activeBOM }}</div>
                            </td>
                            <td>
                                <div class="info-label">Non-Aktif</div>
                                <div style="font-size: 13px; font-weight: bold; color: #B91C1C;">{{ $inactiveBOM }}</div>
                            </td>
                            <td>
                                <div class="info-label">Grand Total HPP</div>
                                <div style="font-size: 11px; font-weight: bold; color: #1E3A8A;">Rp {{ number_format($grandTotalCost, 0, ',', '.') }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- Data Table --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 14%;">Kode BOM</th>
                <th style="width: 17%;">Nama BOM</th>
                <th style="width: 17%;">Produk Jadi</th>
                <th style="width: 8%;">Kategori</th>
                <th style="width: 4%;">Versi</th>
                <th style="width: 6%;">Status</th>
                <th style="width: 6%;">Jml Komp</th>
                <th style="width: 8%;">Biaya Material</th>
                <th style="width: 8%;">Overhead</th>
                <th style="width: 9%;">Estimasi HPP</th>
            </tr>
        </thead>
        <tbody>
            @forelse($boms as $index => $bom)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $bom->kode }}</td>
                    <td>{{ $bom->nama }}</td>
                    <td>
                        {{ $bom->produk->nama ?? '-' }}
                        <span style="color: #6B7280; font-size: 7.5px;">({{ $bom->produk->kode ?? '-' }})</span>
                    </td>
                    <td>{{ $bom->produk->kategori->nama ?? '-' }}</td>
                    <td class="text-center">{{ $bom->versi ?? '1.0' }}</td>
                    <td class="text-center">
                        @if($bom->is_active)
                            <span class="stat-badge stat-active">Aktif</span>
                        @else
                            <span class="stat-badge stat-inactive">Non Aktif</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $bom->component_count }} item</td>
                    <td class="text-right">Rp {{ number_format($bom->calculated_material_cost, 0, ',', '.') }}</td>
                    <td class="text-right">
                        @if($bom->calculated_overhead_cost > 0)
                            Rp {{ number_format($bom->calculated_overhead_cost, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right font-bold" style="color: #1E3A8A;">
                        Rp {{ number_format($bom->calculated_total_cost, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center" style="padding: 15px; color: #6B7280;">
                        Tidak ada data Bill of Material yang sesuai dengan filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($boms->count() > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="7" class="text-right" style="padding-right: 8px;">TOTAL KESELURUHAN ({{ $boms->count() }} BOM):</td>
                    <td class="text-center">{{ $boms->sum('component_count') }} item</td>
                    <td class="text-right">Rp {{ number_format($totalMaterialCost, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($totalOverheadCost, 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #1E3A8A;">Rp {{ number_format($grandTotalCost, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    {{-- Info Pembuat Dokumen --}}
    <table style="width: 100%; margin-top: 20px; page-break-inside: avoid;">
        <tr>
            <td style="width: 70%; vertical-align: bottom; font-size: 8px; color: #6B7280;">
                Dokumen ini dicetak otomatis dari Sistem ERP PT Sinar Surya Semestaraya pada {{ now()->translatedFormat('d F Y H:i') }}.
            </td>
            <td style="width: 30%; text-align: center; vertical-align: top;">
                @if($boms->count() === 1)
                    <div style="font-size: 9px; color: #4B5563; margin-bottom: 35px;">Dibuat Oleh,</div>
                    <div style="font-size: 9.5px; font-weight: bold; text-decoration: underline;">{{ $boms->first()->creator_name }}</div>
                @else
                    <div style="font-size: 9px; color: #4B5563; margin-bottom: 35px;">Dicetak Oleh,</div>
                    <div style="font-size: 9.5px; font-weight: bold; text-decoration: underline;">{{ auth()->user()->name ?? 'Administrator' }}</div>
                @endif
            </td>
        </tr>
    </table>

    {{-- Script page numbering for DomPDF --}}
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->getFont("Arial", "normal");
            $pdf->page_text(740, 570, "Halaman {PAGE_NUM} dari {PAGE_COUNT}", $font, 8, array(0.4, 0.4, 0.4));
        }
    </script>
</body>
</html>
