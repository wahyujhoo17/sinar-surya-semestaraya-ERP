<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sales Order {{ $salesOrder->nomor }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Watermark background */
        .watermark-bg {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            text-align: center;
            z-index: 0;
            opacity: 0.07;
            font-size: 70px;
            font-weight: bold;
            color: #4a6fa5;
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }

        .page-break {
            page-break-after: always;
        }

        /* Simple table-based layout for better printing support */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #4a6fa5;
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            vertical-align: top;
            padding: 5px;
            width: 50%;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #b8c4d6;
            padding: 6px;
            text-align: left;
        }

        .items-table th {
            background-color: #e8f0fa;
            color: #2c3e50;
        }

        .section-title {
            background-color: #e8f0fa;
            padding: 5px;
            font-weight: bold;
            border-left: 3px solid #4a6fa5;
            color: #2c3e50;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-table {
            border-collapse: collapse;
            width: 40%;
            margin-left: 60%;
            margin-bottom: 15px;
        }

        .summary-table td {
            padding: 5px;
        }

        .total-row {
            font-weight: bold;
            border-top: 1px solid #4a6fa5;
            color: #2c3e50;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .signature-table td {
            width: 50%;
            vertical-align: bottom;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #b8c4d6;
            width: 80%;
            margin: 50px auto 10px auto;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-belum_bayar,
        .status-belum_dikirim {
            background-color: #FEE2E2;
            color: #B91C1C;
        }

        .status-sebagian {
            background-color: #FEF3C7;
            color: #D97706;
        }

        .status-lunas,
        .status-dikirim {
            background-color: #D1FAE5;
            color: #059669;
        }

        .text-amount {
            text-transform: uppercase;
            font-style: italic;
            font-size: 11px;
            margin-top: 5px;
            color: #4a6fa5;
        }

        .footer {
            text-align: center;
            margin-top: 45px;
            border-top: 1.5px solid #e0e6ed;
            padding-top: 22px;
            background-color: #f9fafb;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.03);
        }

        .footer-text {
            font-size: 9.5px;
            color: #6b7280;
            margin-top: 15px;
            padding-bottom: 12px;
        }

        /* Print-specific styling */
        @page {
            size: A4;
            margin: 1cm;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }
    </style>
</head>

<body>
    <div class="watermark-bg">{{ strtoupper(setting('company_name', 'SINAR SURYA SEMESTARAYA')) }}</div>

    <!-- Header Section -->
    <table class="header-table">
        <tr style="margin-bottom: 10px;">
            <td style="width: 50%; vertical-align: middle;">
                @php
                    $logoPath = public_path('img/logo_nama3.png');
                    $logoData = '';

                    if (file_exists($logoPath)) {
                        $logoData = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
                    } else {
                        // Fallback placeholder
                        $logoData =
                            'data:image/svg+xml;base64,' .
                            base64_encode(
                                '<svg width="200" height="60" xmlns="http://www.w3.org/2000/svg"><rect width="200" height="60" fill="#4a6fa5"/><text x="100" y="35" font-family="Arial" font-size="14" fill="white" text-anchor="middle">SINAR SURYA</text></svg>',
                            );
                    }
                @endphp
                <img src="{{ $logoData }}" alt="Sinar Surya Logo" style="height: 60px;">
            </td>
            <td style="width: 50%; text-align: right; vertical-align: middle;">
                <h2 style="color: #4a6fa5; margin: 0 0 5px 0;">SALES ORDER</h2>
                <div>
                    <strong>Nomor:</strong> {{ $salesOrder->nomor }}<br>
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($salesOrder->tanggal)->format('d/m/Y') }}<br>
                    @if ($salesOrder->nomor_po)
                        <strong>Nomor PO Customer:</strong> {{ $salesOrder->nomor_po }}<br>
                    @endif
                    @if ($salesOrder->quotation)
                        <strong>Berdasarkan Quotation:</strong> {{ $salesOrder->quotation->nomor }}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Company and Customer Info Section -->
    <table class="info-table">
        <tr>
            <td>
                <div class="section-title">Info Perusahaan</div>
                <div style="padding: 5px;">
                    <strong>{{ setting('company_name', 'PT. SINAR SURYA SEMESTARAYA') }}</strong><br>
                    {{ setting('company_address', 'Jl. Condet Raya No. 6 Balekambang') }}<br>
                    {{ setting('company_city', 'Jakarta Timur') }} {{ setting('company_postal_code', '13530') }}<br>
                    Telp. {{ setting('company_phone', '(021) 80876624 - 80876642') }}<br>
                    E-mail: {{ setting('company_email', 'admin@kliksinarsurya.com') }}<br>
                    @if (setting('company_email_2'))
                        {{ setting('company_email_2') }}<br>
                    @endif
                    @if (setting('company_email_3'))
                        {{ setting('company_email_3') }}
                    @endif
                </div>
            </td>
            <td>
                <div class="section-title">Customer</div>
                <div style="padding: 5px;">
                    <strong>{{ $salesOrder->customer->nama }}</strong><br>
                    @if ($salesOrder->customer->company)
                        {{ $salesOrder->customer->company }}<br>
                    @endif
                    {{ $salesOrder->customer->alamat_utama ?? '-' }}<br>
                    @if ($salesOrder->alamat_pengiriman && $salesOrder->alamat_pengiriman != $salesOrder->customer->alamat_utama)
                        <strong>Alamat Pengiriman:</strong> {{ $salesOrder->alamat_pengiriman }}<br>
                    @endif
                    @if ($salesOrder->customer->telepon)
                        Telp: {{ $salesOrder->customer->telepon }}<br>
                    @endif
                    @if ($salesOrder->customer->email)
                        Email: {{ $salesOrder->customer->email }}<br>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Produk</th>
                <th width="10%">Qty</th>
                <th width="10%">Satuan</th>
                <th width="15%">Harga</th>
                <th width="10%">Diskon</th>
                <th width="20%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salesOrder->details as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}</strong>
                        <div style="font-size: 10px;">{{ $detail->produk->kode ?? '' }}</div>
                        @if ($detail->deskripsi)
                            <div style="font-size: 10px; margin-top: 3px;">{{ $detail->deskripsi }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($detail->quantity, 2, ',', '.') }}</td>
                    <td class="text-center">{{ $detail->satuan->nama ?? '' }}</td>
                    <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if ($detail->diskon_persen > 0)
                            {{ number_format($detail->diskon_persen, 2, ',', '.') }}%
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="display: flex; justify-content: space-between;">
        <div style="width: 60%;">
            @if ($salesOrder->catatan)
                <div
                    style="margin-bottom: 15px; border-left: 3px solid #4a6fa5; padding-left: 10px; background-color: #f8fafc;">
                    <strong style="color: #2c3e50;">Catatan:</strong>
                    <p>{{ $salesOrder->catatan }}</p>
                </div>
            @endif

            <div class="text-amount">
                <strong>Terbilang:</strong> {{ ucwords(terbilang((int) $salesOrder->total)) }} Rupiah
            </div>
        </div>

        <!-- Summary Table -->
        <table class="summary-table">
            <tr>
                <td>Subtotal</td>
                <td class="text-right">Rp {{ number_format($salesOrder->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if ($salesOrder->diskon_nominal > 0)
                <tr>
                    <td>Diskon @if ($salesOrder->diskon_persen > 0)
                            ({{ number_format($salesOrder->diskon_persen, 1) }}%)
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($salesOrder->diskon_nominal, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if ($salesOrder->ppn > 0)
                <tr>
                    <td>PPN ({{ $salesOrder->ppn }}%)</td>
                    <td class="text-right">Rp
                        {{ number_format(($salesOrder->subtotal - $salesOrder->diskon_nominal) * ($salesOrder->ppn / 100), 0, ',', '.') }}
                    </td>
                </tr>
            @endif
            <tr class="total-row">
                <td><strong>Total</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($salesOrder->total, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Terms and Conditions -->
    @if ($salesOrder->syarat_ketentuan)
        <div
            style="margin-bottom: 15px; border-left: 3px solid #4a6fa5; padding-left: 10px; background-color: #f8fafc;">
            <strong style="color: #2c3e50;">Syarat & Ketentuan:</strong>
            <div style="margin-top: 5px; white-space: pre-line;">{{ $salesOrder->syarat_ketentuan }}</div>
        </div>
    @else
        <div
            style="margin-bottom: 15px; border-left: 3px solid #4a6fa5; padding-left: 10px; background-color: #f8fafc;">
            <strong style="color: #2c3e50;">Syarat & Ketentuan:</strong>
            <ol style="margin-top: 5px; padding-left: 20px;">
                <li>Barang yang sudah dibeli tidak dapat dikembalikan</li>
                <li>Pembayaran dilakukan sesuai kesepakatan kedua belah pihak</li>
                <li>Pengiriman dilakukan setelah pembayaran diterima (kecuali untuk pelanggan dengan terms tertentu)
                </li>
            </ol>
        </div>
    @endif

    <!-- Signatures -->
    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-line"></div>
                <div><strong style="color: #2c3e50;">{{ $salesOrder->user->name ?? 'Admin' }}</strong></div>
                <div style="color: #7f8c8d;">Sales</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div><strong
                        style="color: #2c3e50;">{{ $salesOrder->customer->nama ?? $salesOrder->customer->company }}</strong>
                </div>
                <div style="color: #7f8c8d;">Customer</div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">

        <div class="footer-text">
            <p>Dokumen ini dicetak secara digital pada {{ now()->format('d M Y, H:i') }} WIB |
                {{ setting('company_name', 'PT. SINAR SURYA SEMESTARAYA') }}</p>
        </div>
    </div>

</body>

</html>
