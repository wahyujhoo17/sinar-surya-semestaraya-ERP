<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Surat Jalan {{ $deliveryOrder->nomor }} - PT Sinar Surya Semestaraya</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 10px;
            background-color: white;
        }

        /* Page setup for 165x212mm */
        @page {
            size: 165mm 212mm;
            margin: 10mm;
        }

        /* Watermark background */
        .watermark-bg {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            text-align: center;
            z-index: 0;
            opacity: 0.05;
            font-size: 48px;
            font-weight: bold;
            color: #1E40AF;
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
        }

        /* Header styles */
        .header {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 2px solid #1E40AF;
            padding-bottom: 10px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            padding: 5px;
        }

        .company-info {
            width: 60%;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #1E40AF;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 9px;
            color: #666;
            line-height: 1.4;
        }

        .logo-container {
            width: 40%;
            text-align: right;
        }

        .logo-container img {
            max-height: 45px;
            max-width: 120px;
            object-fit: contain;
        }

        .logo-placeholder {
            height: 45px;
            width: 120px;
            border: 1px dashed #1E40AF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #1E40AF;
            background-color: #f0f8ff;
            margin-left: auto;
        }

        /* Document title */
        .document-title {
            text-align: center;
            margin: 15px 0;
        }

        .document-title h1 {
            font-size: 18px;
            font-weight: bold;
            color: #1E40AF;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Document info */
        .document-info {
            width: 100%;
            margin-bottom: 15px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 8px;
            font-size: 10px;
            vertical-align: top;
        }

        .info-label {
            width: 25%;
            font-weight: bold;
            color: #374151;
        }

        .info-separator {
            width: 5%;
            text-align: center;
        }

        .info-value {
            width: 70%;
            color: #1F2937;
        }

        /* Customer info box */
        .customer-box {
            border: 1px solid #1E40AF;
            border-radius: 4px;
            padding: 8px;
            margin-bottom: 15px;
            background-color: #f8fafc;
        }

        .customer-title {
            font-weight: bold;
            color: #1E40AF;
            font-size: 11px;
            margin-bottom: 5px;
        }

        /* Items table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #d1d5db;
            padding: 6px 4px;
            text-align: left;
        }

        .items-table th {
            background-color: #1E40AF;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }

        .items-table td {
            font-size: 9px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Bundle styling */
        .bundle-header {
            background-color: #f8f9fa !important;
            border-left: 3px solid #1E40AF !important;
        }

        .bundle-item {
            background-color: #fefffe !important;
            border-left: 2px solid #10B981 !important;
        }

        .bundle-item-name {
            padding-left: 15px !important;
            font-style: italic;
            color: #374151 !important;
        }

        .bundle-arrow {
            color: #10B981;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            font-size: 9px;
        }

        .signature-section {
            width: 100%;
            margin-top: 20px;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 5px;
        }

        .signature-box {
            border: 1px solid #d1d5db;
            height: 60px;
            margin-bottom: 5px;
            position: relative;
        }

        .signature-label {
            font-size: 9px;
            font-weight: bold;
            color: #374151;
        }

        /* Notes section */
        .notes-section {
            margin-top: 15px;
            padding: 8px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            background-color: #f9fafb;
        }

        .notes-title {
            font-weight: bold;
            font-size: 10px;
            color: #374151;
            margin-bottom: 5px;
        }

        .notes-content {
            font-size: 9px;
            color: #6b7280;
            min-height: 20px;
        }

        /* Print optimizations */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <!-- Watermark -->
    <div class="watermark-bg">SINAR SURYA</div>

    <div class="content-wrapper">
        <!-- Header -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="company-info">
                        <div class="company-name">PT SINAR SURYA SEMESTARAYA</div>
                        <div class="company-details">
                            Jl. Raya Industri No. 123, Kawasan Industri<br>
                            Jakarta Timur 13920, Indonesia<br>
                            Telp: (021) 8888-9999 | Fax: (021) 8888-9998<br>
                            Email: info@sinarsurya.co.id | www.sinarsurya.co.id
                        </div>
                    </td>
                    <td class="logo-container">
                        @if (!empty($logoBase64))
                            <img src="{{ $logoBase64 }}" alt="Sinar Surya Logo">
                        @else
                            <div class="logo-placeholder">
                                LOGO SINAR SURYA
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <!-- Document Title -->
        <div class="document-title">
            <h1>Surat Jalan</h1>
        </div>

        <!-- Document Information -->
        <div class="document-info">
            <table class="info-table">
                <tr>
                    <td class="info-label">Nomor</td>
                    <td class="info-separator">:</td>
                    <td class="info-value">{{ $deliveryOrder->nomor }}</td>
                </tr>
                <tr>
                    <td class="info-label">Tanggal</td>
                    <td class="info-separator">:</td>
                    <td class="info-value">{{ \Carbon\Carbon::parse($deliveryOrder->tanggal)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="info-label">Sales Order</td>
                    <td class="info-separator">:</td>
                    <td class="info-value">{{ $deliveryOrder->salesOrder ? $deliveryOrder->salesOrder->nomor : '-' }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Customer Information -->
        <div class="customer-box">
            <div class="customer-title">KIRIM KEPADA:</div>
            <table class="info-table">
                <tr>
                    <td class="info-label">Nama</td>
                    <td class="info-separator">:</td>
                    <td class="info-value">
                        <strong>{{ $deliveryOrder->customer->company ?? ($deliveryOrder->customer->nama ?? 'Customer tidak ditemukan') }}</strong>
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Alamat</td>
                    <td class="info-separator">:</td>
                    <td class="info-value">{{ $deliveryOrder->alamat_pengiriman }}</td>
                </tr>
                @if ($deliveryOrder->customer && $deliveryOrder->customer->telepon)
                    <tr>
                        <td class="info-label">Telepon</td>
                        <td class="info-separator">:</td>
                        <td class="info-value">{{ $deliveryOrder->customer->telepon }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Kode</th>
                    <th width="40%">Nama Produk</th>
                    <th width="10%">Qty</th>
                    <th width="10%">Satuan</th>
                    <th width="20%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Group items by bundle for better presentation
                    $bundleGroups = [];
                    $nonBundleItems = [];

                    foreach ($deliveryOrder->details as $detail) {
                        if ($detail->is_bundle_item && $detail->bundle_name) {
                            if (!isset($bundleGroups[$detail->bundle_name])) {
                                $bundleGroups[$detail->bundle_name] = [];
                            }
                            $bundleGroups[$detail->bundle_name][] = $detail;
                        } else {
                            $nonBundleItems[] = $detail;
                        }
                    }

                    $itemNumber = 1;
                @endphp

                @if (count($bundleGroups) > 0 || count($nonBundleItems) > 0)
                    {{-- Render Bundle Groups --}}
                    @foreach ($bundleGroups as $bundleName => $bundleItems)
                        {{-- Bundle Header --}}
                        <tr class="bundle-row">
                            <td class="text-center">{{ $itemNumber++ }}</td>
                            <td style="vertical-align: top;">
                                <span style="color: transparent;">-</span><br>
                                @foreach ($bundleItems as $detail)
                                    <span style="font-size: 10px; color: #666;">
                                        {{ $detail->produk->kode ?? '-' }}
                                    </span><br>
                                @endforeach
                            </td>
                            <td style="font-weight: bold; color: #1E40AF; vertical-align: top;">
                                <strong>{{ $bundleName }}:</strong><br>
                                @foreach ($bundleItems as $index => $detail)
                                    <span style="font-weight: normal; font-size: 10px; color: #666; margin-left: 10px;">
                                        • {{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}
                                    </span><br>
                                @endforeach
                            </td>
                            <td class="text-right" style="vertical-align: top;">
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
                        <tr>
                            <td class="text-center">{{ $itemNumber++ }}</td>
                            <td>{{ $detail->produk->kode ?? '-' }}</td>
                            <td>{{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}</td>
                            <td class="text-right">{{ number_format($detail->quantity, 2, ',', '.') }}</td>
                            <td class="text-center">{{ $detail->produk->satuan->nama ?? '-' }}</td>
                            <td>{{ $detail->keterangan ?: '-' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 20px;">
                            Tidak ada item untuk dikirim
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Notes Section -->
        <div class="notes-section">
            <div class="notes-title">KETERANGAN:</div>
            <div class="notes-content">
                {{ $deliveryOrder->keterangan ?: 'Barang dikirim dalam kondisi baik dan sesuai dengan pesanan.' }}
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td>
                        <div class="signature-label">Dibuat Oleh</div>
                        <div class="signature-box"></div>
                        <div class="signature-label">{{ $deliveryOrder->user->name ?? 'Staff' }}</div>
                    </td>
                    <td>
                        <div class="signature-label">Dikirim Oleh</div>
                        <div class="signature-box"></div>
                        <div class="signature-label">Driver</div>
                    </td>
                    <td>
                        <div class="signature-label">Diterima Oleh</div>
                        <div class="signature-box"></div>
                        <div class="signature-label">Customer</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Footer Information -->
        <div class="footer">
            <p style="text-align: center; color: #6b7280; font-size: 8px; margin-top: 15px;">
                Dokumen ini dicetak secara otomatis pada {{ $currentDate }} pukul {{ $currentTime }}<br>
                PT Sinar Surya Semestaraya - Sistem ERP Terintegrasi
            </p>
        </div>
    </div>
</body>

</html>
