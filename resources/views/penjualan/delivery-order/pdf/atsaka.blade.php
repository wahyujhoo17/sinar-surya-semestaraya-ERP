<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Surat Jalan - {{ $deliveryOrder->nomor }}</title>
    <style>
        body {
            background: #ffffff;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 11px;
            line-height: 1.3;
        }

        @page {
            size: 165mm 212mm;
            margin: 8mm 5mm;
        }

        .sheet {
            max-width: 100%;
            margin: 0;
            background: #ffffff;
            padding: 0;
            min-height: 100vh;
        }

        @page {
            size: 165mm 212mm;
            margin: 0;
        }

        .main-content {
            min-height: calc(100vh - 60px);
            width: calc(100% - 20px);
            max-width: 100%;
            margin: 10px 10px;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        .header-section {
            width: calc(100% - 32px);
            background-color: #1F2A44;
            color: white;
            padding: 8px 12px 8px 12px;
            padding: 8px 16px 8px 16px;
            margin: 0 0 8px 0;
            position: relative;
        }

        .header-content {
            width: 100%;
        }

        .company-info {
            float: left;
            width: 65%;
            margin-top: 5%;
        }

        .delivery-info {
            float: right;
            width: 30%;
            text-align: right;
        }

        .company-logo {
            float: left;
            margin-right: 12px;
            max-height: 36px;
            max-width: 50px;
            background-color: white;
            border-radius: 50%;
            padding: 6px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .company-name {
            font-weight: bold;
            font-size: 13px;
            margin: 3px 0 1px 0;
            color: white;
        }

        .company-tagline {
            font-size: 9px;
            margin: 0;
            opacity: 0.9;
            color: #94a3b8;
        }

        .delivery-title {
            color: #E74C3C;
            font-weight: bold;
            font-size: 16px;
            margin: 0 0 2px 0;
            letter-spacing: 1px;
        }

        .delivery-meta {
            font-size: 11px;
            color: #94a3b8;
        }

        .delivery-meta div {
            margin-bottom: 2px;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .info-section {
            width: 95.8%;
            margin: 0 0 12px 0;
            padding: 12px 12px 12px 12px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            font-size: 9px;
        }

        .info-to {
            float: left;
            width: 60%;
        }

        .info-company {
            float: right;
            width: 35%;
        }

        .section-title {
            color: #E74C3C;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 12px;
            border-bottom: 1px solid #E74C3C;
            padding-bottom: 2px;
        }

        .customer-name {
            font-weight: bold;
            color: #0f172a;
            margin: 5px 0;
            font-size: 10px;
        }

        .customer-details {
            color: #475569;
            line-height: 1.4;
            font-size: 9px;
        }

        .customer-details p {
            margin: 3px 0;
        }

        .company-details {
            color: #475569;
            line-height: 1.4;
            font-size: 9px;
        }

        .company-details p {
            margin: 3px 0;
        }

        .items-section {
            margin: 0 0 10px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            font-size: 10px;
        }

        .table-header {
            background-color: #1F2A44;
            color: white;
            font-weight: bold;
        }

        .table-header th {
            padding: 10px 8px;
            text-align: left;
            border: none;
        }

        .table-header .no-col {
            width: 6%;
            text-align: center;
        }

        .table-header .desc-col {
            width: 30%;
        }

        .table-header .qty-col {
            width: 8%;
            text-align: center;
        }

        .table-header .satuan-col {
            width: 10%;
            text-align: center;
        }

        .table-header .ket-col {
            width: 30%;
        }

        .red-header {
            background-color: #E74C3C !important;
            position: relative;
        }

        .table-row td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .table-row:nth-child(even) {
            background-color: #f8fafc;
        }

        .product-name {
            font-weight: bold;
            margin-bottom: 2px;
            font-size: 10px;
        }

        .product-desc {
            font-size: 9px;
            color: #475569;
            margin: 0;
            line-height: 1.3;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .notes-section {
            margin: 10px 0;
            border-left: 3px solid #E74C3C;
            padding-left: 10px;
            background-color: #f8fafc;
        }

        .notes-title {
            color: #2c3e50;
            font-size: 10px;
            font-weight: bold;
        }

        .signature-section {
            width: 100%;
            position: fixed;
            left: 0;
            bottom: 100px;
            padding: 0 12px;
            font-size: 10px;
            background: #fff;
            z-index: 10;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.04);
        }

        .signature-row {
            width: 95%;
            display: table;
            table-layout: fixed;
            margin-top: 15px;
        }

        .signature-item {
            display: table-cell;
            width: 45%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }

        .signature-line {
            height: 40px;
            border-bottom: 1px solid #cbd5e1;
            margin-bottom: 8px;
        }

        .signature-label {
            color: #334155;
            font-size: 10px;
            font-weight: bold;
        }

        .footer-thank-you {
            position: fixed;
            bottom: 30px;
            left: 0;
            right: 0;
            font-size: 13px;
            color: #334155;
            text-align: center;
            padding: 15px 20px;
            font-weight: bold;
            background-color: #f8fafc;
            border-top: 2px solid #E74C3C;
        }

        .footer-decoration {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 25px;
            background-color: #1F2A44;
        }

        .watermark-bg {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            text-align: center;
            z-index: 0;
            opacity: 0.05;
            font-size: 50px;
            font-weight: bold;
            color: #1F2A44;
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="sheet">
        <div class="watermark-bg">{{ strtoupper('PT INDO ATSAKA INDUSTRI') }}</div>
        <div class="main-content">
            <!-- Header -->
            <div class="header-section">
                <div class="header-content clearfix">
                    <div class="company-info">
                        @php
                            $logoPath = public_path('img/atsaka.webp');
                            $logoExists = file_exists($logoPath);
                            $logoBase64 = '';
                            if ($logoExists) {
                                $logoData = file_get_contents($logoPath);
                                $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
                            }
                        @endphp
                        @if ($logoExists && $logoBase64)
                            <img src="{{ $logoBase64 }}" alt="Indo Atsaka Logo" class="company-logo">
                        @else
                            <div class="company-logo"
                                style="width: 80px; height: 80px; background-color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2); border: 2px solid rgba(255, 255, 255, 0.1);">
                                <div
                                    style="width: 50px; height: 50px; background-color: #E74C3C; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: white; font-weight: bold; font-size: 18px;">IA</span>
                                </div>
                            </div>
                        @endif
                        <div style="margin-left: 60px;">
                            <p class="company-name">PT INDO ATSAKA INDUSTRI</p>
                            <p class="company-tagline">INDUSTRIAL SUPPLIER</p>
                        </div>
                    </div>
                    <div class="delivery-info">
                        <p class="delivery-title">SURAT JALAN</p>
                        <div class="delivery-meta">
                            <div>No: <strong>{{ $deliveryOrder->nomor }}</strong></div>
                            <div>Tanggal:
                                <strong>{{ \Carbon\Carbon::parse($deliveryOrder->tanggal)->format('d/m/Y') }}</strong>
                            </div>
                            @if ($deliveryOrder->salesOrder)
                                <div>SO: <strong>{{ $deliveryOrder->salesOrder->nomor }}</strong></div>
                            @endif
                            <div>Status: <strong
                                    style="text-transform: uppercase;">{{ $deliveryOrder->status }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Info Section -->
            <div class="info-section clearfix">
                <div class="info-to">
                    <p class="section-title">Dikirim Kepada:</p>
                    <p class="customer-name">{{ $deliveryOrder->customer->company ?? $deliveryOrder->customer->nama }}
                    </p>
                    <div class="customer-details">
                        <p>{{ $deliveryOrder->alamat_pengiriman ?? ($deliveryOrder->customer->alamat ?? '-') }}</p>
                        @if ($deliveryOrder->customer->telepon)
                            <p>Telp: {{ $deliveryOrder->customer->telepon }}</p>
                        @endif
                        @if ($deliveryOrder->customer->email)
                            <p>Email: {{ $deliveryOrder->customer->email }}</p>
                        @endif
                    </div>
                </div>
                <div class="info-company">
                    <p class="section-title">Info Perusahaan</p>
                    <div class="company-details">
                        <p><strong>PT INDO ATSAKA INDUSTRI</strong></p>
                        <p>Jl. Raya Bekasi Km. 28 No. 1A</p>
                        <p>Bekasi Timur 17141</p>
                        <p>Telp: (021) 8250-8851</p>
                    </div>
                </div>
            </div>
            <!-- Table Section -->
            <div class="items-section">
                <table class="items-table">
                    <thead class="table-header">
                        <tr>
                            <th class="no-col text-center">No</th>
                            <th class="desc-col red-header">Nama Produk</th>
                            <th class="qty-col text-center">Qty</th>
                            <th class="satuan-col text-center">Satuan</th>
                            <th class="ket-col">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $itemNumber = 1;
                            $bundleGroups = [];
                            $nonBundleItems = [];

                            foreach ($deliveryOrder->details as $detail) {
                                if ($detail->is_bundle_item && $detail->bundle_name) {
                                    $bundleGroups[$detail->bundle_name][] = $detail;
                                } else {
                                    $nonBundleItems[] = $detail;
                                }
                            }
                        @endphp

                        @if (count($bundleGroups) > 0 || count($nonBundleItems) > 0)
                            {{-- Render Bundle Groups --}}
                            @foreach ($bundleGroups as $bundleName => $bundleItems)
                                {{-- Bundle Header --}}
                                <tr class="bundle-row">
                                    <td class="text-center">{{ $itemNumber++ }}</td>
                                    <td style="font-weight: bold; color: #DC2626; vertical-align: top;">
                                        <strong>{{ $bundleName }}:</strong><br>
                                        @foreach ($bundleItems as $index => $detail)
                                            <span
                                                style="font-weight: normal; font-size: 10px; color: #666; margin-left: 10px;">
                                                • {{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}
                                            </span><br>
                                        @endforeach
                                    </td>
                                    <td class="text-center" style="vertical-align: top;">
                                        <span style="color: transparent;">-</span><br>
                                        @foreach ($bundleItems as $detail)
                                            <span style="font-size: 10px; color: #666;">
                                                {{ number_format($detail->quantity, 0, ',', '.') }}
                                            </span><br>
                                        @endforeach
                                    </td>
                                    <td class="text-center" style="vertical-align: top;">
                                        Paket<br>
                                        @foreach ($bundleItems as $detail)
                                            <span style="font-size: 10px; color: #666;">
                                                {{ $detail->produk->satuan->nama ?? '-' }}
                                            </span><br>
                                        @endforeach
                                    </td>
                                    <td style="font-style: italic; vertical-align: top;">Bundle Package</td>
                                </tr>
                            @endforeach

                            {{-- Render Non-Bundle Items --}}
                            @foreach ($nonBundleItems as $detail)
                                <tr class="table-row">
                                    <td class="text-center">{{ $itemNumber++ }}</td>
                                    <td>
                                        <div class="product-name">
                                            {{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}
                                        </div>
                                        @if ($detail->produk->deskripsi)
                                            <p class="product-desc">{{ $detail->produk->deskripsi }}</p>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ number_format($detail->quantity, 0) }}</td>
                                    <td class="text-center">{{ $detail->produk->satuan->nama ?? '-' }}</td>
                                    <td>{{ $detail->keterangan ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center" style="padding: 20px;">
                                    Tidak ada item untuk dikirim
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- Notes Section -->
            @if ($deliveryOrder->keterangan)
                <div class="notes-section">
                    <strong class="notes-title">Catatan:</strong>
                    <p style="font-size: 9px; margin: 2px 0;">{{ $deliveryOrder->keterangan }}</p>
                </div>
            @endif
            <!-- Signature Section -->
            <div class="signature-section clearfix">
                <div class="signature-row">
                    <div class="signature-item">
                        <div class="signature-line"></div>
                        <p class="signature-label">{{ $deliveryOrder->user->name ?? 'Sales' }}</p>
                        <p style="font-size: 8px; margin: 2px 0; color: #64748b;">Sales Representative</p>
                    </div>
                    <div class="signature-item">
                        <div class="signature-line"></div>
                        <p style="font-size: 8px; margin: 2px 0; color: #64748b;">Penerima Barang</p>
                        <div style="height:18px; border-bottom:1px dashed #cbd5e1; margin:6px 0 2px 0;"></div>
                        <p style="font-size:8px; color:#64748b; margin:0;">(Nama penerima)</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer with Thank You -->
        <div class="footer-thank-you">
            Terima kasih atas kepercayaan Anda
        </div>
        <div class="footer-decoration"></div>
    </div>
</body>

</html>
