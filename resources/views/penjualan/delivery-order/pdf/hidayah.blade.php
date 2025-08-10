<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Surat Jalan {{ $deliveryOrder->nomor }} - PT Hidayah Cahaya Berkah</title>
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
            color: #059669;
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
        }

        /* Islamic decorative border */
        .islamic-border {
            position: relative;
        }

        .islamic-border::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: repeating-linear-gradient(90deg,
                    #059669 0px,
                    #059669 10px,
                    #10B981 10px,
                    #10B981 20px);
        }

        .islamic-border::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: repeating-linear-gradient(90deg,
                    #059669 0px,
                    #059669 10px,
                    #10B981 10px,
                    #10B981 20px);
        }

        /* Header styles */
        .header {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 2px solid #059669;
            padding: 10px 0;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 4px 4px 0 0;
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
            color: #059669;
            margin-bottom: 5px;
            text-shadow: 0 1px 2px rgba(5, 150, 105, 0.1);
        }

        .company-details {
            font-size: 9px;
            color: #065f46;
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
            border: 1px dashed #059669;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #059669;
            background-color: #f0fdf4;
            margin-left: auto;
            border-radius: 4px;
        }

        /* Islamic greeting */
        .islamic-greeting {
            text-align: center;
            margin: 10px 0;
            padding: 5px;
            background: linear-gradient(90deg, #f0fdf4 0%, #dcfce7 50%, #f0fdf4 100%);
            border-radius: 20px;
            border: 1px solid #059669;
        }

        .bismillah {
            font-size: 12px;
            color: #059669;
            font-weight: bold;
            font-style: italic;
        }

        /* Document title */
        .document-title {
            text-align: center;
            margin: 15px 0;
        }

        .document-title h1 {
            font-size: 18px;
            font-weight: bold;
            color: #059669;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(5, 150, 105, 0.1);
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
            color: #065f46;
        }

        .info-separator {
            width: 5%;
            text-align: center;
            color: #059669;
        }

        .info-value {
            width: 70%;
            color: #1F2937;
        }

        /* Customer info box */
        .customer-box {
            border: 2px solid #059669;
            border-radius: 8px;
            padding: 8px;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            position: relative;
        }

        .customer-box::before {
            content: '🕌';
            position: absolute;
            top: -5px;
            right: 10px;
            font-size: 16px;
            background: white;
            padding: 0 5px;
        }

        .customer-title {
            font-weight: bold;
            color: #059669;
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
            background-color: #f0fdf4;
        }

        .items-table tbody tr:hover {
            background-color: #dcfce7;
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
            border: 1px solid #059669;
            height: 60px;
            margin-bottom: 5px;
            position: relative;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 4px;
        }

        .signature-label {
            font-size: 9px;
            font-weight: bold;
            color: #065f46;
        }

        /* Notes section */
        .notes-section {
            margin-top: 15px;
            padding: 8px;
            border: 2px solid #059669;
            border-radius: 8px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }

        .notes-title {
            font-weight: bold;
            font-size: 10px;
            color: #065f46;
            margin-bottom: 5px;
        }

        .notes-content {
            font-size: 9px;
            color: #047857;
            min-height: 20px;
        }

        /* Islamic closing */
        .islamic-closing {
            text-align: center;
            margin-top: 15px;
            padding: 8px;
            background: linear-gradient(90deg, #f0fdf4 0%, #dcfce7 50%, #f0fdf4 100%);
            border-radius: 20px;
            border: 1px solid #059669;
        }

        .closing-text {
            font-size: 9px;
            color: #059669;
            font-style: italic;
        }

        /* Print optimizations */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Decorative elements */
        .decorative-line {
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, #059669 20%, #10B981 50%, #059669 80%, transparent 100%);
            margin: 8px 0;
        }
    </style>
</head>

<body>
    <!-- Watermark -->
    <div class="watermark-bg">BERKAH</div>

    <div class="content-wrapper islamic-border">
        <!-- Header -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="company-info">
                        <div class="company-name">PT HIDAYAH CAHAYA BERKAH</div>
                        <div class="company-details">
                            Jl. Raya Keberkahan No. 789, Komplek Islami<br>
                            Jakarta Selatan 12560, Indonesia<br>
                            Telp: (021) 7890-1234 | Fax: (021) 7890-1235<br>
                            Email: info@hidayahcahaya.co.id | www.hidayahcahaya.co.id
                        </div>
                    </td>
                    <td class="logo-container">
                        @if (!empty($logoBase64))
                            <img src="{{ $logoBase64 }}" alt="Hidayah Cahaya Logo">
                        @else
                            <div class="logo-placeholder">
                                LOGO HIDAYAH CAHAYA
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <!-- Decorative Line -->
        <div class="decorative-line"></div>

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
                {{ $deliveryOrder->keterangan ?:
                    'Barang dikirim dalam kondisi baik dan sesuai dengan pesanan. Terima kasih atas kepercayaan Anda kepada PT HIDAYAH CAHAYA BERKAH.' }}
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

        <!-- Decorative Line -->
        <div class="decorative-line"></div>

        <!-- Footer Information -->
        <div class="footer">
            <p style="text-align: center; color: #047857; font-size: 8px; margin-top: 10px;">
                Dokumen ini dicetak secara otomatis pada {{ $currentDate }} pukul {{ $currentTime }}<br>
                PT Hidayah Cahaya Berkah - Berbagi Berkah untuk Semua
            </p>
        </div>
    </div>
</body>

</html>
