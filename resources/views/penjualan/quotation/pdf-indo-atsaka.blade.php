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

        .company-info {
            float: left;
            width: 65%;
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

        /* Terms and Signature */
        .terms-signature {
            width: calc(100% - 60px);
            margin: 25px 30px;
            padding: 0;
            font-size: 10px;
            page-break-inside: avoid;
        }

        .terms-section {
            float: left;
            width: 100%;
            margin-bottom: 30px;
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
            height: 30px;
            border-bottom: 1px solid #cbd5e1;
            margin-bottom: 8px;
            margin-top: 5px;
        }

        .signature-label {
            color: #334155;
            font-size: 10px;
            font-weight: bold;
        }

        .qr-signature {
            text-align: center;
            margin: 5px 0;
        }

        .qr-label {
            font-size: 8px;
            color: #64748b;
            margin-bottom: 3px;
        }

        .qr-code-small {
            width: 60px;
            height: 60px;
            border: 1px solid #e5e7eb;
            padding: 3px;
            margin-bottom: 5px;
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
                        <p class="invoice-title">QUOTATION</p>
                        <div class="invoice-meta">
                            <div>Nomor: <strong>{{ $quotation->nomor }}</strong></div>
                            <div>Tanggal:
                                <strong>{{ \Carbon\Carbon::parse($quotation->tanggal)->format('d/m/Y') }}</strong>
                            </div>
                            @if (isset($quotation->status) && $quotation->status !== 'draft')
                                <div>Status: <strong
                                        style="text-transform: uppercase;">{{ $quotation->status }}</strong></div>
                            @endif
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
                        @if ($quotation->customer->sales)
                            <p style="margin-top: 8px;"><strong>Sales Person:</strong></p>
                            <p>{{ $quotation->customer->sales->name }}</p>
                            @if ($quotation->customer->sales->email)
                                <p>{{ $quotation->customer->sales->email }}</p>
                            @endif
                            @if ($quotation->customer->sales->karyawan && $quotation->customer->sales->karyawan->telepon)
                                <p>{{ $quotation->customer->sales->karyawan->telepon }}</p>
                            @endif
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

            @if ($quotation->periode_start && $quotation->periode_end)
                <div style="border: 2px solid #E74C3C; padding: 10px; margin: 15px 10px; background-color: #fff5f5;">
                    <strong style="color: #E74C3C; font-size: 12px;">Periode Penawaran:</strong>
                    <span style="font-weight: bold; color: #1F2A44;">
                        {{ \Carbon\Carbon::parse($quotation->periode_start)->format('d/m/Y') }} s/d
                        {{ \Carbon\Carbon::parse($quotation->periode_end)->format('d/m/Y') }}
                    </span>
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
                        @php
                            $displayIndex = 1;
                            $processedBundles = [];
                        @endphp

                        @foreach ($quotation->details as $index => $detail)
                            @if ($detail->bundle_id && !in_array($detail->bundle_id, $processedBundles))
                                @php
                                    $processedBundles[] = $detail->bundle_id;
                                    // Find bundle header (the main bundle item)
                                    $bundleHeader = $quotation->details
                                        ->where('bundle_id', $detail->bundle_id)
                                        ->where('is_bundle_item', '!=', true)
                                        ->first();
                                    // Find all bundle items
                                    $bundleItems = $quotation->details
                                        ->where('bundle_id', $detail->bundle_id)
                                        ->where('is_bundle_item', true);

                                    // Use first item if no clear header found
                                    if (!$bundleHeader) {
                                        $bundleHeader = $quotation->details
                                            ->where('bundle_id', $detail->bundle_id)
                                            ->first();
                                    }
                                @endphp

                                {{-- Bundle Header --}}
                                <tr class="table-row"
                                    style="background-color: #f8fafc; border-left: 3px solid #1F2A44;">
                                    <td class="text-center">{{ $displayIndex++ }}</td>
                                    <td>
                                        <div class="product-name" style="font-weight: bold;">
                                            PAKET:
                                            @if ($bundleHeader->bundle && $bundleHeader->bundle->nama)
                                                {{ $bundleHeader->bundle->nama }}
                                            @elseif (str_contains($bundleHeader->deskripsi ?? '', 'Bundle:'))
                                                {{ str_replace('Bundle: ', '', $bundleHeader->deskripsi) }}
                                            @else
                                                Paket Bundle #{{ $detail->bundle_id }}
                                            @endif
                                        </div>
                                        @if ($bundleHeader->bundle && $bundleHeader->bundle->kode)
                                            <div style="font-size: 10px; color: #666;">Kode:
                                                {{ $bundleHeader->bundle->kode }}</div>
                                        @endif

                                        {{-- Bundle Items Details in same row --}}
                                        <div
                                            style="margin-top: 5px; padding: 5px; background-color: #f9f9f9; border-radius: 3px;">
                                            <div style="font-size: 10px; color: #555; font-weight: bold;">Isi Paket:
                                            </div>
                                            @foreach ($bundleItems as $bundleItem)
                                                <div style="font-size: 10px; color: #666; margin-left: 10px;">
                                                    •
                                                    @if ($bundleItem->produk && $bundleItem->produk->nama)
                                                        {{ $bundleItem->produk->nama }}
                                                    @elseif ($bundleItem->deskripsi)
                                                        {{ preg_replace('/^└─\s*/', '', preg_replace('/\s*\(dari bundle.*\)$/', '', $bundleItem->deskripsi)) }}
                                                    @else
                                                        Item Bundle
                                                    @endif
                                                    (@if (floor($bundleItem->quantity) == $bundleItem->quantity)
                                                        {{ number_format($bundleItem->quantity, 0, ',', '.') }}@else{{ number_format($bundleItem->quantity, 2, ',', '.') }}
                                                    @endif
                                                    @if ($bundleItem->satuan && $bundleItem->satuan->nama)
                                                        {{ $bundleItem->satuan->nama }}
                                                    @else
                                                        pcs
                                                    @endif)
                                                    @if ($bundleItem->produk && $bundleItem->produk->kode)
                                                        - {{ $bundleItem->produk->kode }}
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if (floor($bundleHeader->quantity) == $bundleHeader->quantity)
                                            {{ number_format($bundleHeader->quantity, 0, ',', '.') }}
                                        @else
                                            {{ number_format($bundleHeader->quantity, 2, ',', '.') }}
                                        @endif
                                    </td>
                                    <td class="text-center">Paket</td>
                                    <td class="text-center">Rp
                                        {{ number_format($bundleHeader->bundle->harga_bundle ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">-</td>
                                    <td class="text-right">Rp
                                        {{ number_format(($bundleHeader->bundle->harga_bundle ?? 0) * $bundleHeader->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @elseif (!$detail->bundle_id)
                                {{-- Regular Product (not part of any bundle) --}}
                                <tr class="table-row">
                                    <td class="text-center">{{ $displayIndex++ }}</td>
                                    <td>
                                        <div class="product-name">
                                            @if ($detail->produk && $detail->produk->nama)
                                                {{ $detail->produk->nama }}
                                            @elseif ($detail->deskripsi)
                                                {{ $detail->deskripsi }}
                                            @else
                                                Produk tidak ditemukan
                                            @endif
                                        </div>
                                        @if ($detail->produk && $detail->produk->kode)
                                            <div style="font-size: 10px;">{{ $detail->produk->kode }}</div>
                                        @endif
                                        @if ($detail->deskripsi && $detail->produk && $detail->produk->nama != $detail->deskripsi)
                                            <p class="product-desc">{{ $detail->deskripsi }}</p>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (floor($detail->quantity) == $detail->quantity)
                                            {{ number_format($detail->quantity, 0, ',', '.') }}
                                        @else
                                            {{ number_format($detail->quantity, 2, ',', '.') }}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $detail->satuan->nama ?? 'pcs' }}</td>
                                    <td class="text-center">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if ($detail->diskon_persen > 0)
                                            {{ number_format($detail->diskon_persen, 1, ',', '.') }}%
                                        @else
                                            -
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
                        <span class="amount">Rp {{ number_format($quotation->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($quotation->diskon_nominal > 0)
                        <div class="summary-item summary-highlight clearfix">
                            <span class="label">Diskon ({{ number_format($quotation->diskon_persen, 1) }}%):</span>
                            <span class="amount">-Rp
                                {{ number_format($quotation->diskon_nominal, 0, ',', '.') }}</span>
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
                    <div class="total-final clearfix">
                        <span class="label">TOTAL:</span>
                        <span class="amount">Rp {{ number_format($quotation->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            @if ($quotation->catatan)
                <div
                    style="margin: 10px 10px; border-left: 3px solid #E74C3C; padding-left: 10px; background-color: #f8fafc;">
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
                            <div style="font-size: 9px; line-height: 1.4; white-space: pre-line;">
                                {!! nl2br(e($quotation->syarat_ketentuan)) !!}</div>
                        @else
                            <div style="font-size: 9px; line-height: 1.4;">
                                1. Penawaran berlaku sesuai periode yang tertera<br>
                                2. Harga belum termasuk pajak dan ongkos kirim<br>
                                3. Pembayaran sesuai kesepakatan kedua belah pihak<br>
                                4. Pengiriman setelah pembayaran diterima<br>
                                5. Barang yang sudah dibeli tidak dapat dikembalikan
                            </div>
                        @endif
                    </div>
                </div>

                <div class="signature-section">
                    <div class="signature-row" style="justify-content: center;">
                        <div class="signature-item" style="width: 40%; margin: 0 auto;">
                            {{-- WhatsApp QR Code for Creator --}}
                            @if (isset($whatsappQR) && $whatsappQR)
                                <div class="qr-signature">
                                    <div class="qr-label">Scan untuk Verifikasi via WhatsApp</div>
                                    <img src="{{ $whatsappQR }}" alt="WhatsApp Verification QR Code"
                                        class="qr-code-small">
                                </div>
                            @else
                                <div style="height: 60px; margin: 5px 0;"></div>
                            @endif

                            <div class="signature-line"></div>
                            <p class="signature-label">{{ $quotation->user->name ?? 'Sales' }}</p>
                            <p style="font-size: 8px; margin: 2px 0; color: #64748b;">Sales Representative</p>
                            <p style="font-size: 8px; margin: 2px 0; color: #64748b;">
                                {{ \Carbon\Carbon::parse($quotation->created_at)->format('d/m/Y H:i') }}
                            </p>
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
