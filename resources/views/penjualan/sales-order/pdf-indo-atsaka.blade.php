<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sales Order - {{ $salesOrder->nomor }}</title>
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
            margin: 8mm 5mm;
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
            width: calc(100% - 20px);
            background-color: #1F2A44;
            color: white;
            padding: 15px 10px;
            margin: 0 10px 15px 10px;
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
            width: calc(100% - 20px);
            margin: 0 10px 15px 10px;
            padding: 15px;
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

        .payment-info p {
            margin: 2px 0;
            color: #475569;
            font-size: 10px;
        }

        .payment-table {
            width: 100%;
            margin-top: 5px;
        }

        .payment-table td {
            padding: 2px 0;
            font-size: 10px;
            margin: 3px 0;
        }

        .payment-table .label {
            width: 50%;
        }

        .payment-table .value {
            text-align: right;
            font-weight: bold;
        }

        /* Header Section */
        .header-section {
            width: calc(100% - 60px);
            background-color: #1F2A44;
            color: white;
            padding: 15px 20px;
            margin: 0 10px 15px 10px;
            position: relative;
            box-sizing: border-box;
        }

        .header-content {
            width: 100%;
        }


        .invoice-info {
            float: right;
            width: 30%;
            text-align: right;
            padding-right: 8px;
        }

        .company-logo {
            float: left;
            margin-right: 15px;
            max-height: 50px;
            max-width: 50px;
            background-color: white;
            border-radius: 50%;
            padding: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .company-name {
            font-weight: bold;
            font-size: 16px;
            margin: 5px 0 2px 0;
            color: white;
        }

        .company-tagline {
            font-size: 11px;
            margin: 0;
            opacity: 0.9;
            color: #94a3b8;
        }

        .invoice-title {
            color: #E74C3C;
            font-weight: bold;
            font-size: 22px;
            margin: 0 0 8px 0;
            letter-spacing: 1.5px;
        }

        .invoice-meta {
            font-size: 11px;
            color: #94a3b8;
        }

        .invoice-meta div {
            margin-bottom: 2px;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        /* Invoice Details Section */
        .invoice-details {
            width: calc(100% - 60px);
            margin: 0 10px 15px 10px;
            padding: 15px 20px;
        }

        .invoice-to {
            float: left;
            width: 60%;
        }

        .payment-method {
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
            font-size: 13px;
        }

        .customer-details {
            color: #475569;
            line-height: 1.4;
            font-size: 11px;
        }

        .customer-details p {
            margin: 3px 0;
        }

        .payment-table .label {
            width: 50%;
        }

        .payment-table .value {
            text-align: right;
            font-weight: bold;
        }

        /* Table Styles */
        .invoice-table {
            width: calc(100% - 20px);
            border-collapse: collapse;
            margin: 10px 10px;
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

        .table-header .price-col {
            width: 14%;
            text-align: center;
        }

        .table-header .total-col {
            width: 16%;
            text-align: right;
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

        /* Total Summary */
        .total-summary {
            width: calc(100% - 60px);
            margin: 20px 30px;
            padding: 0;
            page-break-inside: avoid;
        }

        .summary-section {
            float: right;
            width: 40%;
            padding: 0;
        }

        .summary-item {
            width: 100%;
            margin-bottom: 6px;
            font-size: 11px;
            clear: both;
            box-sizing: border-box;
            padding: 2px 0;
            display: table;
            table-layout: fixed;
        }

        .summary-item .label {
            display: table-cell;
            width: 65%;
            line-height: 1.4;
            color: #475569 !important;
            font-weight: normal;
            vertical-align: top;
        }

        .summary-item .amount {
            display: table-cell;
            font-weight: bold;
            text-align: right;
            width: 35%;
            line-height: 1.4;
            color: #0f172a !important;
            vertical-align: top;
        }

        .summary-highlight {
            background-color: rgba(239, 68, 68, 0.1);
            padding: 4px 6px;
            margin: 2px -6px;
            border-radius: 3px;
        }

        .summary-highlight .label {
            color: #dc2626 !important;
            font-weight: 500;
        }

        .summary-highlight .amount {
            color: #dc2626 !important;
            font-weight: bold;
        }

        /* Total at bottom */
        .total-final {
            width: 100%;
            margin-top: 15px;
            padding: 12px 0;
            border-top: 2px solid #E74C3C;
            font-size: 14px;
            font-weight: bold;
            color: #1F2A44;
            clear: both;
            display: table;
            table-layout: fixed;
        }

        .total-final .label {
            display: table-cell;
            width: 65%;
            vertical-align: top;
        }

        .total-final .amount {
            display: table-cell;
            text-align: right;
            width: 55%;
            color: #E74C3C;
            vertical-align: top;
        }

        /* Signature */
        .terms-signature {
            width: calc(100% - 60px);
            margin: 25px 30px;
            padding: 0;
            font-size: 10px;
            page-break-inside: avoid;
        }

        .signature-section {
            width: 100%;
            margin-top: 20px;
            clear: both;
        }

        .signature-row {
            width: 100%;
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

        /* Thank you message - now as footer */
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

        /* Footer decoration - simplified for PDF */
        .footer-decoration {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 25px;
            background-color: #1F2A44;
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

        /* Reduce excessive whitespace */
        * {
            box-sizing: border-box;
        }

        .table-row:last-child td {
            border-bottom: 2px solid #1F2A44;
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
        <div class="watermark-bg">{{ strtoupper($template_config['company_name'] ?? 'PT INDO ATSAKA INDUSTRI') }}</div>
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

                        <div style="margin-left: 100px;">
                            <p class="company-name">{{ $template_config['company_name'] ?? 'PT INDO ATSAKA INDUSTRI' }}
                            </p>
                            <p class="company-tagline">INDUSTRIAL SUPPLIER</p>
                        </div>
                    </div>
                    <div class="invoice-info">
                        <p class="invoice-title">SALES ORDER</p>
                        <div class="invoice-meta">
                            <div>Nomor: <strong>{{ $salesOrder->nomor }}</strong></div>
                            @if ($salesOrder->nomor_po)
                                <div>No. PO: <strong>{{ $salesOrder->nomor_po }}</strong></div>
                            @endif
                            <div>Tanggal:
                                <strong>{{ \Carbon\Carbon::parse($salesOrder->tanggal)->format('d/m/Y') }}</strong>
                            </div>
                            <div>Status Pembayaran: <strong
                                    style="text-transform: uppercase;">{{ $salesOrder->status_pembayaran }}</strong>
                            </div>
                            <div>Status Pengiriman: <strong
                                    style="text-transform: uppercase;">{{ $salesOrder->status_pengiriman }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Details -->
            <div class="invoice-details clearfix">
                <div class="invoice-to">
                    <p class="section-title">KEPADA YTH:</p>
                    <p class="customer-name">{{ $salesOrder->customer->company ?? $salesOrder->customer->nama }}</p>
                    <div class="customer-details">
                        <p>{{ $salesOrder->customer->alamat ?? '-' }}</p>
                        @if ($salesOrder->customer->telepon)
                            <p>Telp: {{ $salesOrder->customer->telepon }}</p>
                        @endif
                        @if ($salesOrder->customer->email)
                            <p>Email: {{ $salesOrder->customer->email }}</p>
                        @endif
                        @if ($salesOrder->customer->kontak_person)
                            <p>Kontak: {{ $salesOrder->customer->kontak_person }}
                                @if ($salesOrder->customer->no_hp_kontak)
                                    ({{ $salesOrder->customer->no_hp_kontak }})
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
                    </div>
                </div>
            </div>

            @if ($salesOrder->tanggal_kirim)
                <div style="border: 2px solid #E74C3C; padding: 10px; margin: 15px 10px; background-color: #fff5f5;">
                    <strong style="color: #E74C3C; font-size: 12px;">Tanggal Pengiriman:</strong>
                    <span style="font-weight: bold; color: #1F2A44;">
                        {{ \Carbon\Carbon::parse($salesOrder->tanggal_kirim)->format('d/m/Y') }}
                    </span>
                </div>
            @endif

            @if ($salesOrder->quotation_id)
                <div style="border: 2px solid #E74C3C; padding: 10px; margin: 15px 10px; background-color: #fff5f5;">
                    <strong style="color: #E74C3C; font-size: 12px;">Referensi Quotation:</strong>
                    <span style="font-weight: bold; color: #1F2A44;">
                        {{ $salesOrder->quotation->nomor ?? '-' }}
                    </span>
                </div>
            @endif

            @if ($salesOrder->alamat_pengiriman)
                <div style="border: 2px solid #E74C3C; padding: 10px; margin: 15px 10px; background-color: #fff5f5;">
                    <strong style="color: #E74C3C; font-size: 12px;">Alamat Pengiriman:</strong><br>
                    <span style="font-weight: bold; color: #1F2A44;">{{ $salesOrder->alamat_pengiriman }}</span>
                </div>
            @endif

            <!-- Table Section -->
            <div class="table-section">
                <table class="invoice-table">
                    <thead class="table-header">
                        <tr>
                            <th class="no-col text-center">No</th>
                            <th class="desc-col red-header">Nama Produk</th>
                            <th class="qty-col text-center">Qty</th>
                            <th class="price-col text-center">Satuan</th>
                            <th class="price-col text-center">Harga</th>
                            <th class="price-col text-center">Diskon</th>
                            <th class="total-col text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @php
                            $no = 1;
                            $processedBundles = [];
                        @endphp
                        @foreach ($salesOrder->details as $detail)
                            @if ($detail->bundle_id && !in_array($detail->bundle_id, $processedBundles))
                                @php
                                    $processedBundles[] = $detail->bundle_id;
                                    $bundleHeader = $salesOrder->details
                                        ->where('bundle_id', $detail->bundle_id)
                                        ->where('is_bundle_item', '!=', true)
                                        ->first();
                                    $bundleItems = $salesOrder->details
                                        ->where('bundle_id', $detail->bundle_id)
                                        ->where('is_bundle_item', true);
                                    if (!$bundleHeader) {
                                        $bundleHeader = $salesOrder->details
                                            ->where('bundle_id', $detail->bundle_id)
                                            ->first();
                                    }
                                @endphp
                                <tr class="table-row">
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>
                                        <div class="product-name" style="font-weight:700;color:#1F2A44;">PAKET:
                                            {{ $bundleHeader->bundle->nama ?? ($bundleHeader->deskripsi ?? 'Paket Bundle #' . $detail->bundle_id) }}
                                        </div>
                                        @if ($bundleHeader->bundle && $bundleHeader->bundle->kode)
                                            <div style="font-size:9px;color:#666;">Kode:
                                                {{ $bundleHeader->bundle->kode }}</div>
                                        @endif
                                        <div
                                            style="margin-top:5px;padding:5px;background-color:#f3f4f6;border-radius:3px;">
                                            <div style="font-size:9px;color:#555;font-weight:bold;">Isi Paket:</div>
                                            @foreach ($bundleItems as $bundleItem)
                                                <div style="font-size:9px;color:#666;margin-left:10px;">
                                                    •
                                                    @if ($bundleItem->produk && $bundleItem->produk->nama)
                                                        {{ $bundleItem->produk->nama }}
                                                    @elseif ($bundleItem->deskripsi)
                                                        {{ preg_replace('/^└─\s*/', '', preg_replace('/\s*\(dari bundle.*\)$/', '', $bundleItem->deskripsi)) }}
                                                    @else
                                                        Item Bundle
                                                    @endif
                                                    (@if (floor($bundleItem->quantity) == $bundleItem->quantity)
                                                        {{ number_format($bundleItem->quantity, 0) }}@else{{ number_format($bundleItem->quantity, 2) }}
                                                    @endif
                                                    {{ $bundleItem->satuan->nama ?? 'pcs' }})
                                                    @if ($bundleItem->produk && $bundleItem->produk->kode)
                                                        - {{ $bundleItem->produk->kode }}
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if (floor($bundleHeader->quantity) == $bundleHeader->quantity)
                                            {{ number_format($bundleHeader->quantity, 0) }}
                                        @else
                                            {{ number_format($bundleHeader->quantity, 2) }}
                                        @endif
                                    </td>
                                    <td class="text-center">Paket</td>
                                    <td class="text-center">Rp {{ number_format($bundleHeader->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if ($bundleHeader->diskon_persen > 0)
                                            {{ number_format($bundleHeader->diskon_persen, 1) }}%
                                        @endif
                                    </td>
                                    <td class="text-right">Rp {{ number_format($bundleHeader->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @elseif (!$detail->bundle_id)
                                <tr class="table-row">
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>
                                        <div class="product-name">{{ $detail->produk->nama ?? 'Produk' }}</div>
                                        @if ($detail->deskripsi)
                                            <p class="product-desc">{{ $detail->deskripsi }}</p>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (floor($detail->quantity) == $detail->quantity)
                                            {{ number_format($detail->quantity, 0) }}
                                        @else
                                            {{ number_format($detail->quantity, 2) }}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $detail->satuan->nama ?? '-' }}</td>
                                    <td class="text-center">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if ($detail->diskon_persen > 0)
                                            {{ number_format($detail->diskon_persen, 1) }}%
                                        @endif
                                    </td>
                                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="total-summary clearfix">
                <div class="summary-section">
                    <div class="summary-item clearfix">
                        <span class="label">Subtotal:</span>
                        <span class="amount">Rp {{ number_format($salesOrder->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($salesOrder->diskon_nominal > 0)
                        <div class="summary-item summary-highlight clearfix">
                            <span class="label">Diskon ({{ number_format($salesOrder->diskon_persen, 1) }}%):</span>
                            <span class="amount">-Rp
                                {{ number_format($salesOrder->diskon_nominal, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if ($salesOrder->ppn > 0)
                        <div class="summary-item clearfix">
                            <span class="label">PPN ({{ $salesOrder->ppn }}%):</span>
                            <span class="amount">Rp
                                {{ number_format($salesOrder->subtotal * ($salesOrder->ppn / 100), 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if ($salesOrder->ongkos_kirim > 0)
                        <div class="summary-item clearfix">
                            <span class="label">Ongkos Kirim:</span>
                            <span class="amount">Rp {{ number_format($salesOrder->ongkos_kirim, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="total-final clearfix">
                        <span class="label">TOTAL:</span>
                        <span class="amount">Rp {{ number_format($salesOrder->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            @if ($salesOrder->catatan)
                <div
                    style="margin: 10px 10px; border-left: 3px solid #E74C3C; padding-left: 10px; background-color: #f8fafc;">
                    <strong style="color: #2c3e50; font-size: 10px;">Catatan:</strong>
                    <p style="font-size: 9px; margin: 2px 0;">{{ $salesOrder->catatan }}</p>
                </div>
            @endif

            @if ($salesOrder->terms_pembayaran)
                <div
                    style="margin: 10px 10px; border-left: 3px solid #E74C3C; padding-left: 10px; background-color: #f8fafc;">
                    <strong style="color: #2c3e50; font-size: 10px;">Terms Pembayaran:</strong>
                    <p style="font-size: 9px; margin: 2px 0;">
                        {{ $salesOrder->terms_pembayaran }}
                        @if ($salesOrder->terms_pembayaran_hari)
                            ({{ $salesOrder->terms_pembayaran_hari }} hari)
                        @endif
                    </p>
                </div>
            @endif

            <!-- Signature -->
            <div class="terms-signature clearfix">
                <div class="signature-section">
                    <div class="signature-row">
                        <div class="signature-item">
                            <div class="signature-line"></div>
                            <p class="signature-label">{{ $salesOrder->user->name ?? 'Sales' }}</p>
                            <p style="font-size: 8px; margin: 2px 0; color: #64748b;">Sales Representative</p>
                        </div>
                        <div class="signature-item">
                            <div class="signature-line"></div>
                            <p class="signature-label">{{ $template_config['direktur_nama'] ?? 'Direktur' }}</p>
                            <p style="font-size: 8px; margin: 2px 0; color: #64748b;">PT Indo Atsaka Industri</p>
                        </div>
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
