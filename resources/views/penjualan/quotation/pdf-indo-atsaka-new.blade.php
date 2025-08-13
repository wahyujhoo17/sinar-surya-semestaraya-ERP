<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quotation - {{ $quotation->nomor }}</title>
    <style>
        body {
            background: #ffffff;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 11px;
            line-height: 1.3;
        }

        .sheet {
            max-width: 100%;
            margin: 0;
            background: #ffffff;
            padding: 0;
            min-height: 100vh;
        }

        @page {
            size: A4;
            margin: 8mm 6mm;
        }

        /* Content layout */
        .main-content {
            min-height: calc(100vh - 60px);
            width: 100%;
            max-width: 100%;
        }

        /* Clear floats */
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        /* Header Section */
        .header-section {
            width: calc(100% - 10px);
            background-color: #1F2A44;
            color: white;
            padding: 15px 5px;
            margin: 0 5px 15px 5px;
            position: relative;
        }

        .header-content {
            width: 100%;
        }

        .company-info {
            float: left;
            width: 65%;
        }

        .invoice-info {
            float: right;
            width: 30%;
            text-align: right;
        }

        .company-logo {
            float: left;
            margin-right: 15px;
            max-height: 50px;
            max-width: 80px;
        }

        .company-name {
            font-weight: bold;
            font-size: 16px;
            margin: 5px 0 2px 0;
        }

        .company-tagline {
            font-size: 12px;
            color: #cbd5e1;
            margin: 0;
        }

        .invoice-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .invoice-meta div {
            margin: 2px 0;
            font-size: 11px;
        }

        /* Invoice Details */
        .invoice-details {
            width: calc(100% - 10px);
            margin: 0 5px 15px 5px;
            padding: 12px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .invoice-to {
            float: left;
            width: 48%;
        }

        .payment-method {
            float: right;
            width: 48%;
        }

        .section-title {
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 8px;
            font-size: 12px;
            border-bottom: 1px solid #E74C3C;
            padding-bottom: 2px;
        }

        .customer-name {
            font-weight: bold;
            color: #1e293b;
            margin: 5px 0;
            font-size: 12px;
        }

        .customer-details p {
            margin: 2px 0;
            color: #475569;
            font-size: 10px;
        }

        /* Table Styles */
        .invoice-table {
            width: calc(100% - 10px);
            border-collapse: collapse;
            margin: 10px 5px;
            font-size: 10px;
        }

        .table-header {
            background-color: #1F2A44;
            color: white;
            font-weight: bold;
        }

        .table-header th {
            padding: 10px 6px;
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

        .table-header .price-col {
            width: 14%;
            text-align: center;
        }

        .table-header .disc-col {
            width: 12%;
            text-align: center;
        }

        .table-header .total-col {
            width: 16%;
            text-align: center;
        }

        .table-row td {
            padding: 8px 6px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .product-name {
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .product-desc {
            font-size: 9px;
            color: #64748b;
            line-height: 1.2;
        }

        /* Total Summary Section */
        .total-summary {
            width: calc(100% - 10px);
            margin: 15px 5px 10px 5px;
            page-break-inside: avoid;
            overflow: hidden;
            clear: both;
        }

        .total-due-section {
            float: left;
            width: 53%;
            padding-right: 10px;
        }

        .summary-section {
            float: right;
            width: 44%;
            padding-left: 10px;
        }

        .total-due-label {
            color: #475569;
            font-size: 13px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .total-due-line {
            height: 2px;
            background-color: #E74C3C;
            width: 180px;
            margin: 8px 0;
        }

        .total-due-amount {
            font-size: 20px;
            font-weight: bold;
            color: #E74C3C;
            margin: 10px 0;
            letter-spacing: 0.5px;
        }

        .summary-item {
            width: 100%;
            margin-bottom: 5px;
            font-size: 11px;
            overflow: hidden;
            clear: both;
        }

        .summary-item .label {
            float: left;
            width: 55%;
            line-height: 1.4;
        }

        .summary-item .amount {
            float: right;
            font-weight: bold;
            text-align: right;
            width: 43%;
            line-height: 1.4;
        }

        .summary-highlight {
            background-color: rgba(231, 76, 60, 0.1);
            padding: 6px 8px;
            margin: 4px 0;
            border-radius: 3px;
        }

        .summary-total {
            background-color: #E74C3C;
            color: white;
            padding: 12px 10px;
            margin-top: 10px;
            font-weight: bold;
            font-size: 13px;
            overflow: hidden;
            clear: both;
            border-radius: 4px;
            page-break-inside: avoid;
            box-sizing: border-box;
            width: 100%;
        }

        .summary-total .label {
            float: left;
            width: 55%;
            line-height: 1.4;
        }

        .summary-total .amount {
            float: right;
            text-align: right;
            width: 43%;
            line-height: 1.4;
            font-weight: bold;
        }

        /* Terms and Signature */
        .terms-signature {
            width: calc(100% - 10px);
            margin: 15px 5px;
            padding: 0 10px;
            font-size: 10px;
            page-break-inside: avoid;
        }

        .terms-section {
            float: left;
            width: 60%;
        }

        .signature-section {
            float: right;
            width: 35%;
            text-align: right;
        }

        .terms-title {
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 8px;
            font-size: 11px;
            border-bottom: 1px solid #E74C3C;
            padding-bottom: 2px;
        }

        .terms-content {
            color: #475569;
            line-height: 1.4;
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

        /* Footer with Thank You */
        .footer-thank-you {
            margin: 20px 5px 10px 5px;
            font-size: 13px;
            color: #334155;
            text-align: center;
            padding: 15px 20px;
            font-weight: bold;
            background-color: #f8fafc;
            border-top: 2px solid #E74C3C;
        }

        /* Footer decoration */
        .footer-decoration {
            width: 100%;
            height: 25px;
            background-color: #1F2A44;
            margin: 0;
        }

        /* Additional spacing optimizations */
        p {
            margin: 2px 0;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="sheet">
        <div class="main-content">
            @php
                // Logo configuration
                $logoPath = public_path('img/atsaka.PNG');
                $logoExists = file_exists($logoPath);
                $logoBase64 = null;

                if ($logoExists) {
                    $logoData = base64_encode(file_get_contents($logoPath));
                    $logoBase64 = 'data:image/png;base64,' . $logoData;
                }

                // Template configuration
                $template_config = [
                    'company_name' => 'PT INDO ATSAKA INDUSTRI',
                    'company_tagline' => 'INDUSTRIAL SUPPLIER',
                ];
            @endphp

            <!-- Header Section -->
            <div class="header-section">
                <div class="header-content clearfix">
                    <div class="company-info">
                        @if ($logoExists && $logoBase64)
                            <img src="{{ $logoBase64 }}" alt="Indo Atsaka Logo" class="company-logo">
                        @else
                            <div class="company-logo"
                                style="width: 50px; height: 50px; background-color: #E74C3C; border-radius: 50%;"></div>
                        @endif

                        <div style="margin-left: 100px;">
                            <p class="company-name">{{ $template_config['company_name'] ?? 'PT INDO ATSAKA INDUSTRI' }}
                            </p>
                            <p class="company-tagline">INDUSTRIAL SUPPLIER</p>
                        </div>
                    </div>
                    <div class="invoice-info">
                        <p class="invoice-title">QUOTATION</p>
                        <div class="invoice-meta">
                            <div>Nomor: <strong>{{ $quotation->nomor }}</strong></div>
                            <div>Tanggal:
                                <strong>{{ \Carbon\Carbon::parse($quotation->tanggal)->format('d/m/Y') }}</strong>
                            </div>
                            <div>Status: <strong style="text-transform: uppercase;">{{ $quotation->status }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Details -->
            <div class="invoice-details clearfix">
                <div class="invoice-to">
                    <p class="section-title">KEPADA YTH:</p>
                    <p class="customer-name">{{ $quotation->customer->company ?? $quotation->customer->nama }}</p>
                    <div class="customer-details">
                        <p>{{ $quotation->customer->alamat ?? '-' }}</p>
                        @if ($quotation->customer->telepon)
                            <p>Telp: {{ $quotation->customer->telepon }}</p>
                        @endif
                        @if ($quotation->customer->email)
                            <p>Email: {{ $quotation->customer->email }}</p>
                        @endif
                        @if ($quotation->customer->kontak_person)
                            <p>Kontak: {{ $quotation->customer->kontak_person }}
                                @if ($quotation->customer->no_hp_kontak)
                                    ({{ $quotation->customer->no_hp_kontak }})
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
                <div class="payment-method">
                    <p class="section-title">Info Perusahaan</p>
                    <div class="customer-details">
                        <p><strong>{{ $template_config['company_name'] ?? 'PT INDO ATSAKA INDUSTRI' }}</strong></p>
                        <p>Jl. Raya Bekasi Km. 28 No. 1A</p>
                        <p>Bekasi Timur 17141</p>
                        <p>Telp: (021) 8250-8851</p>
                        <p>Email: info@indoatsaka.com</p>
                    </div>
                </div>
            </div>

            @if ($quotation->periode_start && $quotation->periode_end)
                <div
                    style="border: 2px solid #E74C3C; padding: 10px; margin: 15px 5px; background-color: #fff5f5; border-radius: 4px;">
                    <strong style="color: #E74C3C; font-size: 12px;">Periode Penawaran:</strong>
                    <span style="font-weight: bold; color: #1F2A44;">
                        {{ \Carbon\Carbon::parse($quotation->periode_start)->format('d/m/Y') }} s/d
                        {{ \Carbon\Carbon::parse($quotation->periode_end)->format('d/m/Y') }}
                    </span>
                </div>
            @endif

            <!-- Table Section -->
            <table class="invoice-table">
                <thead class="table-header">
                    <tr>
                        <th class="no-col text-center">No</th>
                        <th class="desc-col">Nama Produk</th>
                        <th class="qty-col text-center">Qty</th>
                        <th class="price-col text-center">Satuan</th>
                        <th class="price-col text-center">Harga</th>
                        <th class="disc-col text-center">Diskon</th>
                        <th class="total-col text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($quotation->details as $detail)
                        <tr class="table-row">
                            <td class="text-center">{{ $no++ }}</td>
                            <td>
                                <div class="product-name">
                                    {{ $detail->nama_item ?? ($detail->produk->nama ?? 'Produk') }}</div>
                                @if ($detail->deskripsi)
                                    <p class="product-desc">{{ $detail->deskripsi }}</p>
                                @endif
                            </td>
                            <td class="text-center">{{ number_format($detail->quantity, 0) }}</td>
                            <td class="text-center">{{ $detail->satuan->nama ?? '-' }}</td>
                            <td class="text-center">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if ($detail->diskon_persen > 0)
                                    {{ number_format($detail->diskon_persen, 1) }}%
                                @endif
                                @if ($detail->diskon_nominal > 0)
                                    Rp {{ number_format($detail->diskon_nominal, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Totals -->
            <div class="total-summary clearfix">
                <div class="total-due-section">
                    <p class="total-due-label">Total Penawaran</p>
                    <div class="total-due-line"></div>
                    <p class="total-due-amount">Rp {{ number_format($quotation->total, 0, ',', '.') }}</p>
                </div>
                <div class="summary-section">
                    <div class="summary-item clearfix">
                        <span class="label">Subtotal:</span>
                        <span class="amount">Rp {{ number_format($quotation->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($quotation->diskon_nominal > 0)
                        <div class="summary-item summary-highlight clearfix">
                            <span class="label">Diskon ({{ number_format($quotation->diskon_persen, 1) }}%):</span>
                            <span class="amount">Rp {{ number_format($quotation->diskon_nominal, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if ($quotation->ppn > 0)
                        <div class="summary-item clearfix">
                            <span class="label">PPN ({{ $quotation->ppn }}%):</span>
                            <span class="amount">Rp
                                {{ number_format($quotation->subtotal * ($quotation->ppn / 100), 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if ($quotation->ongkos_kirim > 0)
                        <div class="summary-item clearfix">
                            <span class="label">Ongkos Kirim:</span>
                            <span class="amount">Rp {{ number_format($quotation->ongkos_kirim, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="summary-total clearfix">
                        <span class="label">Total:</span>
                        <span class="amount">Rp {{ number_format($quotation->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            @if ($quotation->catatan)
                <div
                    style="margin: 10px 5px; border-left: 3px solid #E74C3C; padding-left: 10px; background-color: #f8fafc;">
                    <strong style="color: #2c3e50; font-size: 10px;">Catatan:</strong>
                    <p style="font-size: 9px; margin: 2px 0;">{{ $quotation->catatan }}</p>
                </div>
            @endif

            <!-- Terms & Signature -->
            <div class="terms-signature clearfix">
                <div class="terms-section">
                    <p class="terms-title">Syarat & Ketentuan:</p>
                    <div class="terms-content">
                        @if ($quotation->syarat_ketentuan)
                            {{ $quotation->syarat_ketentuan }}
                        @else
                            <div style="font-size: 8px;">
                                1. Penawaran berlaku sesuai periode yang tertera<br>
                                2. Harga belum termasuk pajak dan ongkos kirim<br>
                                3. Pembayaran sesuai kesepakatan kedua belah pihak<br>
                                4. Pengiriman setelah pembayaran diterima
                            </div>
                        @endif
                    </div>
                </div>
                <div class="signature-section">
                    <div class="signature-line"></div>
                    <p class="signature-label">{{ $quotation->user->name ?? 'Sales' }}</p>
                    <p style="font-size: 8px; margin: 0;">Sales Representative</p>
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
