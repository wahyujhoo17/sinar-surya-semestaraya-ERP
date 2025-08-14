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
            margin: 4mm 2mm;
        }

        .main-content {
            min-height: calc(100vh - 30px);
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
            padding: 4px 8px;
            margin: 0 6px 4px 6px;
            position: relative;
            box-sizing: border-box;
        }

        .header-content {
            width: 100%;
        }

        .company-info {
            float: left;
            width: 65%;
            margin-top: 4%;
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
            max-height: 32px;
            max-width: 32px;
            background-color: white;
            border-radius: 50%;
            padding: 5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .company-name {
            font-weight: bold;
            font-size: 13px;
            margin: 2px 0 1px 0;
            color: white;
        }

        .company-tagline {
            font-size: 11px;
            margin: 0;
            opacity: 0.9;
            color: #94a3b8;
        }

        .po-title {
            color: #E74C3C;
            font-weight: bold;
            font-size: 22px;
            margin: 0 0 8px 0;
            letter-spacing: 1.5px;
        }

        .po-details {
            font-size: 11px;
            line-height: 1.4;
        }

        .address-section {
            width: calc(100% - 24px);
            padding: 6px 8px;
            margin: 0 4px 4px 4px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
        }

        .from-address {
            float: left;
            width: 48%;
        }

        .to-address {
            float: right;
            width: 48%;
        }

        .address-title {
            background-color: #1F2A44;
            color: white;
            padding: 4px 8px;
            margin: -8px -12px 6px -12px;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        .items-table {
            width: calc(100% - 24px);
            margin: 4px 12px;
            border-collapse: collapse;
            font-size: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .items-table th {
            background-color: #1F2A44;
            color: white;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
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
                                style="width: 80px; height: 80px; background-color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2); border: 2px solid rgba(255, 255, 255, 0.1);">
                                <div
                                    style="width: 50px; height: 50px; background-color: #E74C3C; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: white; font-weight: bold; font-size: 18px;">IA</span>
                                </div>
                            </div>
                        @endif
                        <div style="margin-left: 54px;">
                            <p class="company-name">PT INDO ATSAKA INDUSTRI</p>
                            <p class="company-tagline">INDUSTRIAL SUPPLIER</p>
                        </div>
                    </div>
                    <div class="po-info">
                        <div class="po-title" style="white-space: nowrap;">PURCHASE ORDER</div>
                        <div class="po-details">
                            <strong>No:</strong> {{ $purchaseOrder->nomor }}<br>
                            <strong>Tanggal:</strong>
                            {{ \Carbon\Carbon::parse($purchaseOrder->tanggal)->format('d/m/Y') }}<br>
                            <strong>Status:</strong>
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
                            <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Section -->
            <div class="invoice-details clearfix"
                style="width: calc(100% - 60px); margin: 0 10px 5px 10px; padding: 15px 20px;">
                <div class="payment-method" style="float: left; width: 35%;">
                    <p class="section-title"
                        style="color: #E74C3C; font-weight: bold; margin-bottom: 5px; font-size: 12px; border-bottom: 1px solid #E74C3C; padding-bottom: 2px;">
                        DARI : </p>
                    <div class="customer-details" style="color: #475569; line-height: 1.4; font-size: 11px;">
                        <strong>PT INDO ATSAKA INDUSTRI</strong><br>
                        Jl. Industri Raya No. 123<br>
                        Jakarta Utara 14350, Indonesia<br>
                        Telp: (021) 1234-5678<br>
                        Email: info@indoatsaka.com
                    </div>
                </div>
                <div class="invoice-to" style="float: right; width: 60%;">
                    <p class="section-title"
                        style="color: #E74C3C; font-weight: bold; margin-bottom: 5px; font-size: 12px; border-bottom: 1px solid #E74C3C; padding-bottom: 2px;">
                        KEPADA :</p>
                    <p class="customer-name" style="font-weight: bold; color: #0f172a; margin: 5px 0; font-size: 13px;">
                        {{ $purchaseOrder->supplier->nama }}</p>
                    <div class="customer-details"
                        style="color: #475569; line-height: 1.1; font-size: 11px; margin-bottom: 0;">
                        <p style="margin: 0 0 2px 0;">{{ $purchaseOrder->supplier->alamat ?? '-' }}</p>
                        @if ($purchaseOrder->supplier->telepon)
                            <p style="margin: 0 0 2px 0;">Telp: {{ $purchaseOrder->supplier->telepon }}</p>
                        @endif
                        @if ($purchaseOrder->supplier->email)
                            <p style="margin: 0 0 2px 0;">Email: {{ $purchaseOrder->supplier->email }}</p>
                        @endif
                        @if ($purchaseOrder->supplier->nama_kontak)
                            <p style="margin: 0 0 2px 0;">Kontak: {{ $purchaseOrder->supplier->nama_kontak }}
                                @if ($purchaseOrder->supplier->no_hp)
                                    ({{ $purchaseOrder->supplier->no_hp }})
                                @endif
                            </p>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Shipping Info if available -->
            @if ($purchaseOrder->alamat_pengiriman)
                <div
                    style="width: calc(100% - 60px); margin: 0 30px 15px 30px; padding: 10px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-left: 4px solid #fdcb6e;">
                    <strong>Alamat Pengiriman:</strong> {{ $purchaseOrder->alamat_pengiriman }}
                    @if ($purchaseOrder->tanggal_pengiriman)
                        <br><strong>Tanggal Pengiriman:</strong>
                        {{ \Carbon\Carbon::parse($purchaseOrder->tanggal_pengiriman)->format('d/m/Y') }}
                    @endif
                </div>
            @endif

            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%; text-align: center;">No</th>
                        <th class="red-header" style="width: 35%;">Produk</th>
                        <th style="width: 8%; text-align: center;">Qty</th>
                        <th style="width: 10%; text-align: center;">Satuan</th>
                        <th style="width: 15%; text-align: right;">Harga</th>
                        <th style="width: 10%; text-align: center;">Diskon</th>
                        <th style="width: 17%; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($purchaseOrder->details as $detail)
                        <tr>
                            <td style="text-align: center; font-weight: bold;">{{ $no++ }}</td>
                            <td>
                                <div style="font-weight: 500; color: #1F2A44;">
                                    {{ $detail->nama_item ?? ($detail->produk->nama ?? 'Produk') }}
                                </div>
                            </td>
                            <td style="text-align: center;">{{ number_format($detail->quantity, 0) }}</td>
                            <td style="text-align: center;">{{ $detail->satuan->nama ?? '-' }}</td>
                            <td style="text-align: right;">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td style="text-align: center; font-size: 9px;">
                                @if ($detail->diskon_persen > 0)
                                    {{ number_format($detail->diskon_persen, 1) }}%
                                @endif
                                @if ($detail->diskon_nominal > 0)
                                    <br>Rp {{ number_format($detail->diskon_nominal, 0, ',', '.') }}
                                @endif
                            </td>
                            <td style="text-align: right; font-weight: bold;">Rp
                                {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Summary Section -->
            <div class="total-summary clearfix"
                style="width: calc(100% - 60px); margin: 20px 30px; padding: 0; page-break-inside: avoid;">
                <div class="summary-section" style="float: right; width: 40%; padding: 0;">
                    <div class="summary-item clearfix"
                        style="width: 100%; margin-bottom: 6px; font-size: 11px; clear: both; box-sizing: border-box; padding: 2px 0; display: table; table-layout: fixed;">
                        <span class="label"
                            style="display: table-cell; width: 65%; line-height: 1.4; color: #475569; font-weight: normal; vertical-align: top;">Subtotal:</span>
                        <span class="amount"
                            style="display: table-cell; font-weight: bold; text-align: right; width: 35%; line-height: 1.4; color: #0f172a; vertical-align: top;">Rp
                            {{ number_format($purchaseOrder->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($purchaseOrder->diskon_nominal > 0)
                        <div class="summary-item summary-highlight clearfix"
                            style="background-color: rgba(239, 68, 68, 0.1); padding: 4px 6px; margin: 2px -6px; border-radius: 3px; width: 100%; margin-bottom: 6px; font-size: 11px; clear: both; box-sizing: border-box; display: table; table-layout: fixed;">
                            <span class="label"
                                style="display: table-cell; width: 65%; line-height: 1.4; color: #dc2626; font-weight: 500; vertical-align: top;">Diskon
                                ({{ number_format($purchaseOrder->diskon_persen, 1) }}%):</span>
                            <span class="amount"
                                style="display: table-cell; font-weight: bold; text-align: right; width: 35%; line-height: 1.4; color: #dc2626; vertical-align: top;">-Rp
                                {{ number_format($purchaseOrder->diskon_nominal, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if ($purchaseOrder->ppn > 0)
                        <div class="summary-item clearfix"
                            style="width: 100%; margin-bottom: 6px; font-size: 11px; clear: both; box-sizing: border-box; padding: 2px 0; display: table; table-layout: fixed;">
                            <span class="label"
                                style="display: table-cell; width: 65%; line-height: 1.4; color: #475569; font-weight: normal; vertical-align: top;">PPN
                                ({{ $purchaseOrder->ppn }}%):</span>
                            <span class="amount"
                                style="display: table-cell; font-weight: bold; text-align: right; width: 35%; line-height: 1.4; color: #0f172a; vertical-align: top;">Rp
                                {{ number_format($purchaseOrder->subtotal * ($purchaseOrder->ppn / 100), 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if ($purchaseOrder->ongkos_kirim > 0)
                        <div class="summary-item clearfix"
                            style="width: 100%; margin-bottom: 6px; font-size: 11px; clear: both; box-sizing: border-box; padding: 2px 0; display: table; table-layout: fixed;">
                            <span class="label"
                                style="display: table-cell; width: 65%; line-height: 1.4; color: #475569; font-weight: normal; vertical-align: top;">Ongkos
                                Kirim:</span>
                            <span class="amount"
                                style="display: table-cell; font-weight: bold; text-align: right; width: 35%; line-height: 1.4; color: #0f172a; vertical-align: top;">Rp
                                {{ number_format($purchaseOrder->ongkos_kirim, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="total-final clearfix"
                        style="width: 100%; margin-top: 15px; padding: 12px 0; border-top: 2px solid #E74C3C; font-size: 14px; font-weight: bold; color: #1F2A44; clear: both; display: table; table-layout: fixed; background: none;">
                        <span class="label"
                            style="display: table-cell; width: 65%; vertical-align: top; color: #1F2A44; font-weight: 500;">TOTAL:</span>
                        <span class="amount"
                            style="display: table-cell; text-align: right; width: 35%; color: #E74C3C; vertical-align: top; font-weight: bold;">Rp
                            {{ number_format($purchaseOrder->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div style="clear: both;"></div>

            <!-- Notes Section -->
            <div class="notes-section">
                @if ($purchaseOrder->catatan)
                    <strong style="color: #2c3e50; font-size: 10px;">Catatan:</strong>
                    <p style="font-size: 9px; margin: 2px 0;">{{ $purchaseOrder->catatan }}</p>
                @endif
                <strong style="color: #2c3e50; font-size: 10px;">Syarat & Ketentuan:</strong>
                @if ($purchaseOrder->syarat_ketentuan)
                    <div style="font-size: 9px; margin-top: 5px; white-space: pre-line;">
                        {{ $purchaseOrder->syarat_ketentuan }}</div>
                @else
                    <ol style="margin-top: 5px; padding-left: 20px;">
                        <li>Barang harus sesuai dengan spesifikasi yang tercantum dalam PO</li>
                        <li>Pembayaran akan dilakukan setelah barang diterima dengan kondisi baik</li>
                        <li>Pengiriman harap disertai dengan surat jalan dan invoice</li>
                    </ol>
                @endif
            </div>

            <!-- Signature Section with QR Codes -->
            <table class="signature-table">
                <tr>
                    <td>
                        <div style="margin-bottom: 10px; font-weight: bold; color: #1F2A44;">Dibuat oleh:</div>

                        {{-- QR Code for Creator --}}
                        @if (isset($qrCodes['created_qr']) && $qrCodes['created_qr'])
                            <div class="qr-signature">
                                <div class="qr-label">Tanda Tangan Digital</div>
                                <img src="{{ $qrCodes['created_qr'] }}" alt="Creator QR Code" class="qr-code-small">
                            </div>
                        @endif

                        <div class="signature-line"></div>
                        <div style="font-weight: bold; color: #1F2A44;">
                            {{ $purchaseOrder->user->name ?? 'Purchasing Manager' }}</div>
                        <div style="font-size: 10px; color: #6c757d;">PT Indo Atsaka Industri</div>
                        <div style="font-size:10px;">
                            {{ \Carbon\Carbon::parse($purchaseOrder->created_at)->format('d/m/Y H:i') }}
                        </div>
                        <div style="font-size:10px; color: #666;">
                            {{ $purchaseOrder->user->email ?? '' }}
                        </div>
                    </td>

                    @if ($isProcessed && $processedBy)
                        <td>
                            <div style="margin-bottom: 10px; font-weight: bold; color: #1F2A44;">Diproses oleh:</div>

                            {{-- QR Code for Processor --}}
                            @if (isset($qrCodes['processed_qr']) && $qrCodes['processed_qr'])
                                <div class="qr-signature">
                                    <div class="qr-label">Tanda Tangan Digital</div>
                                    <img src="{{ $qrCodes['processed_qr'] }}" alt="Processor QR Code"
                                        class="qr-code-small">
                                </div>
                            @endif

                            <div class="signature-line"></div>
                            <div style="font-weight: bold; color: #1F2A44;">{{ $processedBy->name }}</div>
                            <div style="font-size: 10px; color: #6c757d;">Manager/Supervisor</div>
                            <div style="font-size:10px;">
                                {{ $processedAt ? $processedAt->format('d/m/Y H:i') : '' }}
                            </div>
                            <div style="font-size:10px; color: #666;">
                                {{ $processedBy->email ?? '' }}
                            </div>
                        </td>
                    @else
                        <td>
                            <div style="margin-bottom: 10px; font-weight: bold; color: #1F2A44;">Menyetujui:</div>

                            <div class="qr-signature">
                                <div class="qr-label">Menunggu Tanda Tangan</div>
                                <div
                                    style="width: 60px; height: 60px; border: 1px dashed #ccc; display: inline-block; line-height: 60px; font-size: 10px; color: #999;">
                                    QR
                                </div>
                            </div>

                            <div class="signature-line"></div>
                            <div style="font-weight: bold; color: #1F2A44;">Menunggu Persetujuan</div>
                            <div style="font-size: 10px; color: #6c757d;">PT Indo Atsaka Industri</div>
                            <div style="font-size:10px;">Tanggal: ___/___/______</div>
                        </td>
                    @endif
                </tr>
            </table>

            {{-- Status Box --}}
            @if (isset($isProcessed) && $isProcessed)
                <div
                    style="margin-top: 20px; padding: 10px; border-radius: 5px; background-color: #f0f9ff; border: 1px solid #0ea5e9; color: #0369a1; page-break-inside: avoid;">
                    <h4 style="margin: 0 0 5px 0; font-size: 14px;"><strong>Status: TELAH DIPROSES</strong></h4>
                    <p style="margin: 0; font-size: 11px;">
                        Purchase Order ini telah diproses pada
                        {{ isset($processedAt) && $processedAt ? $processedAt->format('d F Y \p\u\k\u\l H:i') : $purchaseOrder->updated_at->format('d F Y \p\u\k\u\l H:i') }}
                        oleh {{ isset($processedBy) && $processedBy ? $processedBy->name : 'Sistem' }}.
                    </p>
                </div>
            @elseif ($purchaseOrder->status === 'draft')
                <div
                    style="margin-top: 12px; padding: 6px 12px; border-radius: 3px; border: 1px solid #f59e0b; color: #d97706; font-size: 11px; background: none; page-break-inside: avoid;">
                    <strong>Status: DRAFT</strong> &mdash; Dokumen ini masih dalam status draft dan belum diproses.
                </div>
            @endif

            <!-- Footer -->
            <div class="footer-thank-you">Terima kasih atas kepercayaan Anda</div>
            <div class="footer-decoration"></div>
        </div>
    </div>
</body>

</html>
