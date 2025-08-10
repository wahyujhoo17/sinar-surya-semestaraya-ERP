<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Surat Jalan {{ $deliveryOrder->nomor }} - PT Indo Atsaka Industri</title>
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
            color: #2E86AB;
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
        }

        /* Header styles with blue-red gradient */
        .header {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 3px solid transparent;
            background: linear-gradient(90deg, #2E86AB 0%, #DC2626 100%);
            background-clip: border-box;
            padding-bottom: 10px;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #2E86AB 0%, #DC2626 100%);
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
            background: linear-gradient(90deg, #2E86AB 0%, #DC2626 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            border: 1px dashed #2E86AB;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #2E86AB;
            background: linear-gradient(45deg, #f0f8ff 0%, #fff5f5 100%);
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
            background: linear-gradient(90deg, #2E86AB 0%, #DC2626 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            color: #2E86AB;
        }

        .info-separator {
            width: 5%;
            text-align: center;
            color: #DC2626;
        }

        .info-value {
            width: 70%;
            color: #1F2937;
        }

        /* Customer info box */
        .customer-box {
            border: 2px solid transparent;
            background: linear-gradient(white, white) padding-box,
                linear-gradient(90deg, #2E86AB 0%, #DC2626 100%) border-box;
            border-radius: 4px;
            padding: 8px;
            margin-bottom: 15px;
            background-color: #f8fafc;
        }

        .customer-title {
            font-weight: bold;
            background: linear-gradient(90deg, #2E86AB 0%, #DC2626 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            background: linear-gradient(135deg, #059669 0%, #10B981 100%);
            color: black;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
            text-shadow: none;
        }

        .items-table td {
            font-size: 9px;
        }

        .items-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
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
            border: 1px solid #2E86AB;
            height: 60px;
            margin-bottom: 5px;
            position: relative;
            background: linear-gradient(45deg, transparent 0%, rgba(46, 134, 171, 0.05) 100%);
        }

        .signature-label {
            font-size: 9px;
            font-weight: bold;
            color: #2E86AB;
        }

        /* Notes section */
        .notes-section {
            margin-top: 15px;
            padding: 8px;
            border: 2px solid transparent;
            background: linear-gradient(white, white) padding-box,
                linear-gradient(90deg, #2E86AB 0%, #DC2626 100%) border-box;
            border-radius: 4px;
            background-color: #f9fafb;
        }

        .notes-title {
            font-weight: bold;
            font-size: 10px;
            background: linear-gradient(90deg, #2E86AB 0%, #DC2626 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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

        /* Accent elements */
        .accent-line {
            height: 2px;
            background: linear-gradient(90deg, #2E86AB 0%, #DC2626 100%);
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <!-- Watermark -->
    <div class="watermark-bg">INDO ATSAKA</div>

    <div class="content-wrapper">
        <!-- Header -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="company-info">
                        <div class="company-name">PT INDO ATSAKA INDUSTRI</div>
                        <div class="company-details">
                            Jl. Industri Raya No. 456, Kawasan Industri Pulogadung<br>
                            Jakarta Timur 13260, Indonesia<br>
                            Telp: (021) 4681-2345 | Fax: (021) 4681-2346<br>
                            Email: info@indoatsaka.co.id | www.indoatsaka.co.id
                        </div>
                    </td>
                    <td class="logo-container">
                        @if (!empty($logoBase64))
                            <img src="{{ $logoBase64 }}" alt="Indo Atsaka Logo">
                        @else
                            <div class="logo-placeholder">
                                LOGO INDO ATSAKA
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <!-- Accent Line -->
        <div class="accent-line"></div>

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
                @forelse ($deliveryOrder->details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $detail->produk->kode ?? '-' }}</td>
                        <td>{{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}</td>
                        <td class="text-right">{{ number_format($detail->quantity, 2, ',', '.') }}</td>
                        <td class="text-center">{{ $detail->produk->satuan->nama ?? '-' }}</td>
                        <td>{{ $detail->keterangan ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 20px;">
                            Tidak ada item untuk dikirim
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Notes Section -->
        <div class="notes-section">
            <div class="notes-title">KETERANGAN:</div>
            <div class="notes-content">
                {{ $deliveryOrder->keterangan ?: 'Barang dikirim dalam kondisi baik dan sesuai dengan pesanan. Terima kasih atas kepercayaan Anda kepada PT Indo Atsaka Industri.' }}
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
            <div class="accent-line"></div>
            <p style="text-align: center; color: #6b7280; font-size: 8px; margin-top: 10px;">
                Dokumen ini dicetak secara otomatis pada {{ $currentDate }} pukul {{ $currentTime }}<br>
                PT Indo Atsaka Industri - Solusi Industri Terdepan
            </p>
        </div>
    </div>
</body>

</html>
