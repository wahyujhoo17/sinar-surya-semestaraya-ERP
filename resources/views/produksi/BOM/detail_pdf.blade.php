<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BOM - {{ $bom->kode }}</title>
    <style>
        @page {
            margin: 25px 30px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #1F2937;
            line-height: 1.4;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #1E3A8A;
            padding-bottom: 8px;
            margin-bottom: 15px;
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

        .doc-title-box {
            text-align: right;
        }

        .doc-title {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .doc-code {
            font-size: 11px;
            font-weight: bold;
            color: #1E3A8A;
            margin-top: 2px;
        }

        /* Detail Information Box */
        .info-box {
            width: 100%;
            border: 1px solid #CBD5E1;
            border-radius: 4px;
            background-color: #F8FAFC;
            margin-bottom: 15px;
            padding: 8px 12px;
        }

        .info-table {
            width: 100%;
        }

        .info-label {
            font-size: 9px;
            font-weight: bold;
            color: #475569;
            width: 18%;
        }

        .info-val {
            font-size: 9.5px;
            color: #0F172A;
            width: 32%;
        }

        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 3px;
            font-size: 8.5px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #DEF7EC;
            color: #03543F;
        }

        .badge-danger {
            background-color: #FDE8E8;
            color: #9B1C1C;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #1E3A8A;
            text-transform: uppercase;
            margin-bottom: 6px;
            border-left: 3px solid #1E3A8A;
            padding-left: 6px;
        }

        /* Component Table */
        table.comp-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table.comp-table th {
            background-color: #1E3A8A;
            color: #FFFFFF;
            font-weight: bold;
            font-size: 8.5px;
            text-transform: uppercase;
            padding: 6px 5px;
            border: 1px solid #1E3A8A;
            text-align: center;
        }

        table.comp-table td {
            border: 1px solid #D1D5DB;
            padding: 5px 6px;
            font-size: 9px;
            vertical-align: middle;
        }

        table.comp-table tr:nth-child(even) {
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

        /* Cost Summary Table */
        .cost-summary-box {
            width: 100%;
            margin-bottom: 20px;
        }

        .cost-table {
            width: 50%;
            margin-left: auto;
            border-collapse: collapse;
        }

        .cost-table td {
            padding: 4px 8px;
            font-size: 9.5px;
            border-bottom: 1px solid #E2E8F0;
        }

        .cost-total-row td {
            border-top: 2px solid #1E3A8A;
            border-bottom: 2px solid #1E3A8A;
            background-color: #EFF6FF;
            font-weight: bold;
            font-size: 10.5px;
            color: #1E3A8A;
        }

        /* Signatures */
        .signature-table {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .signature-table td {
            text-align: center;
            vertical-align: top;
            width: 33.3%;
            padding: 0 10px;
        }

        .signature-space {
            height: 50px;
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
            <td style="width: 12%; vertical-align: middle;">
                @if ($logoSrc)
                    <img src="{{ $logoSrc }}" class="logo" alt="Logo">
                @endif
            </td>
            <td style="width: 48%; vertical-align: middle; padding-left: 8px;">
                <div class="company-name">{{ $company->nama ?? 'PT SINAR SURYA SEMESTARAYA' }}</div>
                <div class="company-sub">
                    {{ $company->alamat ?? 'Jl. Raya Industri No. 88' }}
                    @if(!empty($company->kota)), {{ $company->kota }}@endif
                    @if(!empty($company->telepon)) | Telp: {{ $company->telepon }}@endif
                </div>
            </td>
            <td class="doc-title-box" style="width: 40%; vertical-align: middle;">
                <div class="doc-title">Spesifikasi Bill of Material</div>
                <div class="doc-code">{{ $bom->kode }}</div>
                <div style="font-size: 8.5px; color: #6B7280; margin-top: 2px;">
                    Cetak: {{ now()->translatedFormat('d F Y H:i') }}
                </div>
            </td>
        </tr>
    </table>

    {{-- BOM Details Info Box --}}
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td class="info-label">Kode BOM</td>
                <td class="info-val">: <strong>{{ $bom->kode }}</strong></td>
                <td class="info-label">Produk Jadi</td>
                <td class="info-val">: <strong>{{ $bom->produk->nama ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td class="info-label">Nama BOM</td>
                <td class="info-val">: {{ $bom->nama }}</td>
                <td class="info-label">Kode Produk</td>
                <td class="info-val">: {{ $bom->produk->kode ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Versi</td>
                <td class="info-val">: {{ $bom->versi ?? '1.0' }}</td>
                <td class="info-label">Kategori / Satuan</td>
                <td class="info-val">: {{ $bom->produk->kategori->nama ?? '-' }} / {{ $bom->produk->satuan->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Status</td>
                <td class="info-val">: 
                    @if($bom->is_active)
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-danger">Non Aktif</span>
                    @endif
                </td>
                <td class="info-label">Tanggal Dibuat</td>
                <td class="info-val">: {{ $bom->created_at ? $bom->created_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Dibuat Oleh</td>
                <td class="info-val">: <strong>{{ $bom->creator_name }}</strong></td>
                <td class="info-label">Deskripsi / Catatan</td>
                <td class="info-val">: {{ $bom->deskripsi ?? '-' }}</td>
            </tr>
        </table>
    </div>

    {{-- Components Table --}}
    <div class="section-title">Daftar Bahan Baku & Komponen ({{ $bom->details->count() }} item)</div>
    <table class="comp-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 16%;">Kode Komponen</th>
                <th style="width: 32%;">Nama Bahan Baku / Komponen</th>
                <th style="width: 10%;">Jumlah</th>
                <th style="width: 8%;">Satuan</th>
                <th style="width: 13%;">Harga Beli (Rp)</th>
                <th style="width: 16%;">Subtotal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotalMaterial = 0;
            @endphp
            @forelse($bom->details as $idx => $detail)
                @php
                    $qty = (float) $detail->quantity;
                    $hargaBeli = (float) ($detail->komponen->harga_beli ?? 0);
                    $subtotal = $qty * $hargaBeli;
                    $subtotalMaterial += $subtotal;
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="font-bold">{{ $detail->komponen->kode ?? '-' }}</td>
                    <td>
                        {{ $detail->komponen->nama ?? '-' }}
                        @if(!empty($detail->catatan))
                            <div style="font-size: 8px; color: #6B7280; font-style: italic;">* {{ $detail->catatan }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($qty, 2, ',', '.') }}</td>
                    <td class="text-center">{{ $detail->satuan->nama ?? ($detail->komponen->satuan->nama ?? '-') }}</td>
                    <td class="text-right">Rp {{ number_format($hargaBeli, 0, ',', '.') }}</td>
                    <td class="text-right font-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 15px; color: #6B7280;">
                        Belum ada komponen pada Bill of Material ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Cost Summary Breakdown --}}
    @php
        $overhead = (float) ($bom->overhead_cost ?? 0);
        $totalHpp = $subtotalMaterial + $overhead;
    @endphp
    <div class="cost-summary-box">
        <table class="cost-table">
            <tr>
                <td style="width: 60%; font-weight: bold;">Subtotal Biaya Bahan Baku</td>
                <td style="width: 40%; text-align: right; font-weight: bold;">Rp {{ number_format($subtotalMaterial, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Biaya Overhead Pabrik</td>
                <td style="text-align: right; font-weight: bold;">
                    @if($overhead > 0)
                        Rp {{ number_format($overhead, 0, ',', '.') }}
                    @else
                        Rp 0
                    @endif
                </td>
            </tr>
            <tr class="cost-total-row">
                <td>ESTIMASI TOTAL HPP / UNIT</td>
                <td style="text-align: right;">Rp {{ number_format($totalHpp, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    {{-- Info Pembuat Dokumen --}}
    <table style="width: 100%; margin-top: 25px; page-break-inside: avoid;">
        <tr>
            <td style="width: 65%; vertical-align: bottom; font-size: 8px; color: #6B7280;">
                Dokumen ini dicetak otomatis dari Sistem ERP PT Sinar Surya Semestaraya pada {{ now()->translatedFormat('d F Y H:i') }}.
            </td>
            <td style="width: 35%; text-align: center; vertical-align: top;">
                <div style="font-size: 9px; color: #4B5563; margin-bottom: 35px;">Dibuat Oleh,</div>
                <div style="font-size: 9.5px; font-weight: bold; text-decoration: underline;">{{ $bom->creator_name }}</div>
            </td>
        </tr>
    </table>

    {{-- Script page numbering for DomPDF --}}
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->getFont("Arial", "normal");
            $pdf->page_text(520, 800, "Hal {PAGE_NUM} dari {PAGE_COUNT}", $font, 8, array(0.4, 0.4, 0.4));
        }
    </script>
</body>
</html>
