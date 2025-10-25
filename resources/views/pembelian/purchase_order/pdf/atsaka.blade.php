<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Purchase Order - {{ $purchaseOrder->nomor }} - PT Indo Atsaka Industri</title>
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

        .main-content {
            min-height: calc(100vh - 60px);
            width: 100%;
            max-width: 100%;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        .header-section {
            width: calc(100% - 60px);
            background-color: #1F2A44;
            color: white;
            padding: 8px 20px;
            margin: 0 10px 10px 10px;
            position: relative;
            box-sizing: border-box;
        }

        .header-content {
            width: 100%;
        }

        .company-info {
            float: left;
            width: 65%;
            margin-top: 2.5%;
        }

        .po-info {
            float: right;
            width: 30%;
            text-align: right;
            padding-right: 8px;
        }

        .company-logo {
            float: left;
            margin-right: 10px;
            max-height: 35px;
            max-width: 35px;
            background-color: white;
            border-radius: 50%;
            padding: 6px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .company-name {
            font-weight: bold;
            font-size: 14px;
            margin: 2px 0 1px 0;
            color: white;
        }

        .company-tagline {
            font-size: 10px;
            margin: 0;
            opacity: 0.9;
            color: #94a3b8;
        }

        .po-title {
            color: #E74C3C;
            font-weight: bold;
            font-size: 18px;
            margin: 0 0 4px 0;
            letter-spacing: 1px;
        }

        .po-meta {
            font-size: 10px;
            color: #94a3b8;
            line-height: 1.2;
        }

        .po-meta div {
            margin-bottom: 1px;
        }

        .address-section {
            width: calc(100% - 60px);
            margin: 0 10px 10px 10px;
            padding: 10px 20px;
        }

        .from-address {
            float: left;
            width: 60%;
        }

        .to-address {
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

        .supplier-name {
            font-weight: bold;
            color: #0f172a;
            margin: 5px 0;
            font-size: 13px;
        }

        .supplier-details {
            color: #475569;
            line-height: 1.4;
            font-size: 11px;
        }

        .supplier-details p {
            margin: 3px 0;
        }

        .items-table {
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

        letter-spacing: 0.5px;
        padding: 10px 8px;
        border: none;
        }

        .items-table th.red-header {
            background-color: #E74C3C !important;
            position: relative;
        }

        .items-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: top;
        }

        .items-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .summary-table {
            float: right;
            width: 40%;
            margin: 8px 12px;
            border-collapse: collapse;
            font-size: 11px;
        }

        .summary-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .summary-table .total-row {
            background-color: #1F2A44;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }

        .notes-section {
            width: calc(100% - 8px);
            margin: 2px 4px;
            padding: 3px 8px;
            background-color: #f8fafc;
            border-left: 3px solid #E74C3C;
            font-size: 9px;
        }

        .signature-section {
            width: calc(100% - 24px);
            margin: 12px 12px 8px 12px;
        }

        .signature-left {
            float: left;
            width: 45%;
            text-align: center;
        }

        .signature-right {
            float: right;
            width: 45%;
            text-align: center;
        }

        .signature-box {
            border: 1px solid #dee2e6;
            height: 80px;
            margin-bottom: 10px;
            background-color: #fff;
        }

        .footer-thank-you {
            position: fixed;
            bottom: 8px;
            left: 0;
            right: 0;
            font-size: 13px;
            color: #334155;
            text-align: center;
            padding: 6px 8px;
            font-weight: bold;
            background-color: #f8fafc;
            border-top: 2px solid #E74C3C;
        }

        .footer-decoration {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 12px;
            background-color: #1F2A44;
        }

        .status-belum-bayar {
            color: #dc2626;
            font-weight: bold;
        }

        .status-sebagian {
            color: #d97706;
            font-weight: bold;
        }

        .status-lunas {
            color: #16a34a;
            font-weight: bold;
        }

        .status-default {
            color: #6b7280;
            font-weight: bold;
        }

        .watermark-bg {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            text-align: center;
            z-index: 1000;
            opacity: 0.05;
            font-size: 60px;
            font-weight: bold;
            color: #1F2A44;
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }

        /* QR Code Styles */
        .qr-signature {
            margin-top: 10px;
            text-align: center;
        }

        .qr-signature .qr-label {
            font-size: 8px;
            color: #666;
            margin-bottom: 3px;
        }

        .qr-signature img {
            border: 1px solid #ddd;
            padding: 2px;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            margin: 5px auto;
            display: block;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
            background: white;
        }

        .qr-code-small {
            width: 60px;
            height: 60px;
            margin: 5px auto;
            display: block;
            border: 1px solid #ddd;
            padding: 2px;
            background: white;
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
            border-top: 1px solid #dee2e6;
            width: 80%;
            margin: 20px auto 10px auto;
        }

        .signature-box {
            border: 1px solid #dee2e6;
            height: 80px;
            margin-bottom: 10px;
            background-color: #fff;
        }
    </style>
</head>

<body>
    <div class="watermark-bg">{{ strtoupper('PT INDO ATSAKA INDUSTRI') }}</div>
    <div class="sheet">
        <div class="main-content">
            <!-- Header Section -->
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
                                style="width: 50px; height: 50px; background-color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15); border: 1px solid rgba(255, 255, 255, 0.1);">
                                <div
                                    style="width: 32px; height: 32px; background-color: #E74C3C; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: white; font-weight: bold; font-size: 14px;">IA</span>
                                </div>
                            </div>
                        @endif
                        <div style="margin-left: 60px;">
                            <p class="company-name">PT INDO ATSAKA INDUSTRI</p>
                            <p class="company-tagline">INDUSTRIAL SUPPLIER</p>
                        </div>
                    </div>
                    <div class="po-info">
                        <div class="po-meta" style="text-align: right;">
                            <div
                                style="font-size: 18px; font-weight: bold; color: #E74C3C; margin-bottom: 4px; letter-spacing: 1px;">
                                PURCHASE ORDER</div>
                            <div>Nomor: <strong>{{ $purchaseOrder->nomor }}</strong> | Tanggal:
                                <strong>{{ \Carbon\Carbon::parse($purchaseOrder->tanggal)->format('d/m/Y') }}</strong>
                            </div>
                            @if ($purchaseOrder->status)
                                <div>Status:
                                    @php
                                        $statusClass = 'status-default';
                                        $statusLabel = ucfirst(str_replace('_', ' ', $purchaseOrder->status));

                                        switch ($purchaseOrder->status) {
                                            case 'draft':
                                                $statusClass = 'status-belum-bayar';
                                                $statusLabel = 'Draft';
                                                break;
                                            case 'diproses':
                                                $statusClass = 'status-sebagian';
                                                $statusLabel = 'Diproses';
                                                break;
                                            case 'selesai':
                                                $statusClass = 'status-lunas';
                                                $statusLabel = 'Selesai';
                                                break;
                                        }
                                    @endphp
                                    <strong class="{{ $statusClass }}">{{ $statusLabel }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purchase Order Details -->
            <div class="address-section clearfix">
                <div class="from-address">
                    <p class="section-title">DARI:</p>
                    <div class="supplier-details">
                        <strong>PT INDO ATSAKA INDUSTRI</strong><br>
                        Jl. Industri Raya No. 123<br>
                        Jakarta Utara 14350, Indonesia<br>
                        Telp: (021) 1234-5678<br>
                        Email: info@indoatsaka.com
                    </div>
                </div>
                <div class="to-address">
                    <p class="section-title">KEPADA YTH:</p>
                    <p class="supplier-name">{{ $purchaseOrder->supplier->nama }}</p>
                    <div class="supplier-details">
                        <p>{{ $purchaseOrder->supplier->alamat ?? '-' }}</p>
                        @if ($purchaseOrder->supplier->telepon)
                            <p>Telp: {{ $purchaseOrder->supplier->telepon }}</p>
                        @endif
                        @if ($purchaseOrder->supplier->email)
                            <p>Email: {{ $purchaseOrder->supplier->email }}</p>
                        @endif
                        @if ($purchaseOrder->supplier->nama_kontak)
                            <p>Kontak: {{ $purchaseOrder->supplier->nama_kontak }}
                                @if ($purchaseOrder->supplier->no_hp)
                                    ({{ $purchaseOrder->supplier->no_hp }})
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            @if ($purchaseOrder->tanggal_pengiriman)
                <div style="border: 2px solid #E74C3C; padding: 10px; margin: 15px 10px; background-color: #fff5f5;">
                    <strong style="color: #E74C3C; font-size: 12px;">Tanggal Pengiriman:</strong>
                    <span style="font-weight: bold; color: #1F2A44;">
                        {{ \Carbon\Carbon::parse($purchaseOrder->tanggal_pengiriman)->format('d/m/Y') }}
                    </span>
                </div>
            @endif

            <!-- Table Section -->
            <div class="table-section">
                <table class="items-table">
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
                        @foreach ($purchaseOrder->details as $detail)
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
                                </td>
                                <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="total-summary clearfix">
                <div class="summary-section">
                    <div class="summary-item clearfix">
                        <span class="label">Subtotal:</span>
                        <span class="amount">Rp {{ number_format($purchaseOrder->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($purchaseOrder->diskon_nominal > 0)
                        <div class="summary-item summary-highlight clearfix">
                            <span class="label">Diskon
                                ({{ number_format($purchaseOrder->diskon_persen, 1) }}%):</span>
                            <span class="amount">-Rp
                                {{ number_format($purchaseOrder->diskon_nominal, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if ($purchaseOrder->ppn > 0)
                        <div class="summary-item clearfix">
                            <span class="label">PPN 11%:</span>
                            <span class="amount">Rp
                                {{ number_format($purchaseOrder->subtotal * ($purchaseOrder->ppn / 100), 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if ($purchaseOrder->ongkos_kirim > 0)
                        <div class="summary-item clearfix">
                            <span class="label">Ongkos Kirim:</span>
                            <span class="amount">Rp
                                {{ number_format($purchaseOrder->ongkos_kirim, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="total-final clearfix">
                        <span class="label">TOTAL:</span>
                        <span class="amount">Rp {{ number_format($purchaseOrder->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Text Terbilang -->
            <div style="margin: 15px 10px; color: #1F2A44; font-size: 11px; font-style: italic;">
                <strong>Terbilang:</strong> {{ ucwords(terbilang((int) $purchaseOrder->total)) }} Rupiah
            </div>

            <!-- Notes and Terms Section -->
            <div style="display: table; width: calc(100% - 20px); margin: 10px 10px;">
                <div style="display: table-cell; width: 60%; vertical-align: top; padding-right: 20px;">
                    @if ($purchaseOrder->catatan)
                        <div
                            style="margin-bottom: 15px; border-left: 3px solid #E74C3C; padding-left: 10px; background-color: #f8fafc;">
                            <strong style="color: #2c3e50; font-size: 10px;">Catatan:</strong>
                            <p style="font-size: 9px; margin: 2px 0;">{{ $purchaseOrder->catatan }}</p>
                        </div>
                    @endif

                    @if ($purchaseOrder->syarat_ketentuan)
                        <div
                            style="margin-bottom: 15px; border-left: 3px solid #E74C3C; padding-left: 10px; background-color: #f8fafc;">
                            <strong style="color: #2c3e50; font-size: 10px;">Syarat & Ketentuan:</strong>
                            <div style="font-size: 9px; margin-top: 5px; white-space: pre-line;">
                                {{ $purchaseOrder->syarat_ketentuan }}</div>
                        </div>
                    @else
                        <div
                            style="margin-bottom: 15px; border-left: 3px solid #E74C3C; padding-left: 10px; background-color: #f8fafc;">
                            <strong style="color: #2c3e50; font-size: 10px;">Syarat & Ketentuan:</strong>
                            <ol style="margin-top: 5px; padding-left: 20px; font-size: 9px;">
                                <li>Barang harus sesuai dengan spesifikasi yang tercantum dalam PO</li>
                                <li>Pembayaran akan dilakukan setelah barang diterima dengan kondisi baik</li>
                                <li>Pengiriman harap disertai dengan surat jalan dan invoice</li>
                            </ol>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Signature Section - Full Width Below -->
            <div style="margin: 20px 10px 10px 10px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <!-- Dibuat oleh -->
                        <td style="width: 50%; text-align: center; vertical-align: bottom; padding: 5px;">
                            <div style="margin-bottom: 10px; font-weight: bold; color: #1F2A44; font-size: 10px;">
                                Dibuat oleh:
                            </div>

                            {{-- WhatsApp QR Code for Creator --}}
                            @if (isset($whatsappQR) && $whatsappQR)
                                <div style="text-align: center; margin-bottom: 10px;">
                                    <div style="font-size: 8px; color: #64748b; margin-bottom: 5px;">Scan untuk
                                        Verifikasi via WhatsApp</div>
                                    <img src="{{ $whatsappQR }}" alt="Creator WhatsApp QR"
                                        style="width: 60px; height: 60px; margin: 0 auto; border: 1px solid #e5e7eb; padding: 3px;">
                                </div>
                            @else
                                <div style="height: 60px; margin-bottom: 10px;"></div>
                            @endif

                            <div style="border-top: 1px solid #1F2A44; width: 60%; margin: 0 auto 8px auto;"></div>
                            <div style="font-weight: bold; color: #1F2A44; font-size: 10px;">
                                {{ $purchaseOrder->user->name ?? 'Purchasing' }}
                            </div>
                            <div style="font-size: 8px; color: #64748b; margin-top: 2px;">Purchasing</div>
                            <div style="font-size: 8px; color: #64748b; margin-top: 2px;">
                                {{ \Carbon\Carbon::parse($purchaseOrder->created_at)->format('d/m/Y H:i') }}
                            </div>
                            <div style="font-size: 7px; color: #94a3b8; margin-top: 2px;">
                                {{ $purchaseOrder->user->email ?? '' }}
                            </div>
                        </td>

                        <!-- Diproses oleh -->
                        <td style="width: 50%; text-align: center; vertical-align: bottom; padding: 5px;">
                            @if ($isProcessed && $processedBy)
                                <div style="margin-bottom: 10px; font-weight: bold; color: #1F2A44; font-size: 10px;">
                                    Diproses oleh:
                                </div>

                                {{-- WhatsApp QR Code for Processor --}}
                                @if (isset($whatsappQRProcessor) && $whatsappQRProcessor)
                                    <div style="text-align: center; margin-bottom: 10px;">
                                        <div style="font-size: 8px; color: #64748b; margin-bottom: 5px;">Scan untuk
                                            Verifikasi via WhatsApp</div>
                                        <img src="{{ $whatsappQRProcessor }}" alt="Processor WhatsApp QR"
                                            style="width: 60px; height: 60px; margin: 0 auto; border: 1px solid #e5e7eb; padding: 3px;">
                                    </div>
                                @else
                                    <div style="height: 60px; margin-bottom: 10px;"></div>
                                @endif

                                <div style="border-top: 1px solid #1F2A44; width: 60%; margin: 0 auto 8px auto;"></div>
                                <div style="font-weight: bold; color: #1F2A44; font-size: 10px;">
                                    {{ $processedBy->name }}
                                </div>
                                <div style="font-size: 8px; color: #64748b; margin-top: 2px;">Manager/Supervisor</div>
                                <div style="font-size: 8px; color: #64748b; margin-top: 2px;">
                                    {{ $processedAt ? $processedAt->format('d/m/Y H:i') : '' }}
                                </div>
                                <div style="font-size: 7px; color: #94a3b8; margin-top: 2px;">
                                    {{ $processedBy->email ?? '' }}
                                </div>
                            @else
                                <div style="margin-bottom: 10px; font-weight: bold; color: #1F2A44; font-size: 10px;">
                                    Disetujui oleh:
                                </div>
                                <div style="height: 60px; margin-bottom: 10px;">
                                    <div
                                        style="border: 1px dashed #cbd5e1; width: 60px; height: 60px; margin: 0 auto; display: flex; align-items: center; justify-content: center; font-size: 9px; color: #94a3b8; background-color: #f8fafc; line-height: 1.2;">
                                        Menunggu<br>Tanda Tangan
                                    </div>
                                </div>
                                <div style="border-top: 1px solid #1F2A44; width: 60%; margin: 0 auto 8px auto;"></div>
                                <div style="font-weight: bold; color: #1F2A44; font-size: 10px;">
                                    Direktur
                                </div>
                                <div style="font-size: 8px; color: #64748b; margin-top: 2px;">PT Indo Atsaka Industri
                                </div>
                                <div style="font-size: 8px; color: #64748b; margin-top: 2px;">Tanggal: ___/___/______
                                </div>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Notes and Terms Section -->
            <div style="display: table; width: calc(100% - 20px); margin: 10px 10px;">
                <div style="display: table-cell; width: 100%; vertical-align: top;">
                    @if ($purchaseOrder->catatan)
                        <div
                            style="margin-bottom: 10px; border-left: 3px solid #E74C3C; padding-left: 10px; background-color: #f8fafc;">
                            <strong style="color: #2c3e50; font-size: 10px;">Catatan:</strong>
                            <p style="font-size: 9px; margin: 2px 0;">{{ $purchaseOrder->catatan }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer with Thank You -->
        <div class="footer-thank-you">Terima kasih atas kepercayaan Anda</div>
        <div class="footer-decoration"></div>
    </div>
</body>

</html>
