<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Purchase Order - {{ $purchaseOrder->nomor }} - PT Hidayah Cahaya Berkah</title>
    <style>
        :root {
            --hcb-blue: #002147;
            --hcb-green: #27ae60;
            --hcb-orange: #FF6E00;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background-color: #ffffff;
            font-size: 10px;
            line-height: 1.2;
            color: #1f2937;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .po-container {
            max-width: 100%;
            margin: 0;
            background-color: #ffffff;
            padding: 8mm 7mm;
            min-height: 100vh;
            position: relative;
            padding-bottom: 60px;
        }

        @page {
            size: A4;
            margin: 8mm 7mm;
        }

        .watermark-bg {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            text-align: center;
            z-index: 1000;
            opacity: 0.07;
            font-size: 36px;
            font-weight: bold;
            color: var(--hcb-green);
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 9px;
        }

        .custom-table th,
        .custom-table td {
            border: 1px solid #d1d5db;
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }

        .custom-table th {
            background-color: var(--hcb-blue);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            font-size: 9px;
            padding: 6px 4px;
        }

        .custom-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .total-summary {
            width: calc(100% - 24px);
            margin: 8px 12px;
            padding: 0;
            page-break-inside: avoid;
        }

        .summary-section {
            float: right;
            width: 50%;
            padding: 0;
            min-width: 220px;
        }

        .summary-item {
            width: 100%;
            margin-bottom: 2px;
            font-size: 10px;
            clear: both;
            box-sizing: border-box;
            padding: 1px 0;
            display: table;
            table-layout: fixed;
        }

        .summary-item .label {
            display: table-cell;
            width: 55%;
            line-height: 1.4;
            color: #4b5563 !important;
            font-weight: normal;
            vertical-align: top;
        }

        .summary-item .amount {
            display: table-cell;
            font-weight: bold;
            text-align: right;
            width: 45%;
            line-height: 1.4;
            color: #111827 !important;
            vertical-align: top;
            white-space: nowrap;
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
            margin-top: 8px;
            padding: 6px 0;
            border-top: 2px solid var(--hcb-blue);
            font-size: 12px;
            font-weight: bold;
            color: var(--hcb-blue);
            clear: both;
            display: table;
            table-layout: fixed;
        }

        .total-final .label {
            display: table-cell;
            width: 55%;
            vertical-align: top;
        }

        .total-final .amount {
            display: table-cell;
            text-align: right;
            width: 45%;
            color: var(--hcb-orange);
            font-weight: bold;
            vertical-align: top;
            white-space: nowrap;
        }

        .signature-section {
            margin-top: 10px;
            margin-bottom: 6px;
            padding-bottom: 4px;
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            page-break-inside: avoid;
        }

        .footer-fixed {
            position: fixed;
            bottom: 2mm;
            left: 2mm;
            right: 2mm;
            text-align: center;
            padding: 4px 0;
            border-top: 1px solid #e5e7eb;
            background-color: #f8fafc;
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
            margin-top: 10px;
        }

        .signature-table td {
            width: 50%;
            vertical-align: bottom;
            text-align: center;
        }

        .signature-line-hcb {
            border-top: 1px solid #cbd5e1;
            width: 80%;
            margin: 10px auto 6px auto;
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
    </style>
</head>

<body>
    <div class="watermark-bg">{{ strtoupper('PT HIDAYAH CAHAYA BERKAH') }}</div>
    <div class="po-container relative z-10">
        <!-- Header Section -->
        <div style="display: table; width: 100%; margin-bottom: 8px; border-collapse: separate; border-spacing: 0;">
            <div style="display: table-row;">
                <!-- Logo and Company Info -->
                <div style="display: table-cell; vertical-align: middle; width: 65%; padding-right: 8px;">
                    <div style="display: flex; align-items: center; flex-wrap: nowrap; gap: 12px; min-height: 56px;">
                        @php
                            $logoPath = public_path('img/LogoHCB-0.jpeg');
                            $logoExists = file_exists($logoPath);
                            $logoBase64 = '';
                            if ($logoExists) {
                                $logoData = file_get_contents($logoPath);
                                $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
                            }
                        @endphp

                        @if ($logoExists && $logoBase64)
                            <img src="{{ $logoBase64 }}" alt="HCB Logo"
                                style="height: 48px; width: auto; max-width: 80px; object-fit: contain; border-radius: 4px; flex-shrink: 0; vertical-align: middle; background-color: white; padding: 3px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                        @else
                            <div
                                style="height: 48px; width: 80px; border: 2px dashed var(--hcb-green); display: flex; align-items: center; justify-content: center; font-size: 13px; color: var(--hcb-green); background-color: #f0fdf4; border-radius: 4px; font-weight: 700; flex-shrink: 0;">
                                HCB
                            </div>
                        @endif
                        <span
                            style="font-size: 16px; font-weight: 900; color: var(--hcb-blue); letter-spacing: 0.7px; white-space: nowrap; flex-shrink: 0; line-height: 1; vertical-align: middle;">
                            PT HIDAYAH CAHAYA BERKAH
                        </span>
                    </div>
                </div>

                <!-- Purchase Order Info -->
                <div style="display: table-cell; vertical-align: middle; width: 35%; text-align: right;">
                    <div
                        style="background-color: #f8fafc; padding: 8px; border-radius: 5px; border-left: 4px solid var(--hcb-orange); min-height: 48px;">
                        <div
                            style="font-size: 16px; font-weight: 700; color: var(--hcb-orange); margin-bottom: 4px; letter-spacing: 1px;">
                            PURCHASE ORDER
                        </div>
                        <div style="font-size: 10px; line-height: 1.3; color: #374151;">
                            <div style="margin-bottom: 2px;">
                                <span style="font-weight: 600; color: #1f2937;">No:</span>
                                <span
                                    style="color: var(--hcb-blue); font-weight: 600;">{{ $purchaseOrder->nomor }}</span>
                            </div>
                            <div style="margin-bottom: 2px;">
                                <span style="font-weight: 600; color: #1f2937;">Tanggal:</span>
                                <span
                                    style="color: #374151;">{{ \Carbon\Carbon::parse($purchaseOrder->tanggal)->format('d/m/Y') }}</span>
                            </div>
                            @if ($purchaseOrder->status)
                                <div>
                                    <span style="font-weight: 600; color: #1f2937;">Status:</span>
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
                                    <span class="{{ $statusClass }}"
                                        style="text-transform: uppercase; background-color: rgba(34, 197, 94, 0.1); padding: 2px 6px; border-radius: 3px; font-size: 9px;">{{ $statusLabel }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            style="height: 3px; background: linear-gradient(to right, var(--hcb-blue) 0%, var(--hcb-green) 50%, var(--hcb-orange) 100%); margin: 15px 0; border-radius: 2px;">
        </div>

        <!-- Company and Supplier Info Section -->
        <div style="display: table; width: 100%; margin-bottom: 20px; border-collapse: separate; border-spacing: 0;">
            <div style="display: table-row;">
                <!-- DARI Section -->
                <div style="display: table-cell; vertical-align: top; width: 48%; padding-right: 30px;">
                    <div
                        style="font-size: 12px; font-weight: 700; color: var(--hcb-blue); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 2px solid var(--hcb-blue);">
                        DARI:
                    </div>
                    <div style="font-weight: 700; color: var(--hcb-blue); font-size: 13px; margin-bottom: 5px;">
                        PT HIDAYAH CAHAYA BERKAH
                    </div>
                    <div style="font-size: 11px; line-height: 1.4; color: #374151;">
                        Jl. Raya Bekasi No. 88, Cakung<br>
                        Jakarta Timur 13910, Indonesia<br>
                        Telp: (021) 4608-7890 - (021) 4608-7891<br>
                        Email: info@hidayahcahayaberkah.com
                    </div>
                </div>
                <!-- KEPADA Section -->
                <div style="display: table-cell; vertical-align: top; width: 52%;">
                    <div
                        style="font-size: 12px; font-weight: 700; color: var(--hcb-orange); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 2px solid var(--hcb-orange);">
                        KEPADA:
                    </div>
                    <div style="font-weight: 700; color: var(--hcb-blue); font-size: 13px; margin-bottom: 5px;">
                        {{ $purchaseOrder->supplier->nama }}
                    </div>
                    <div style="font-size: 11px; line-height: 1.4; color: #374151;">
                        {{ $purchaseOrder->supplier->alamat ?? '-' }}<br>
                        @if ($purchaseOrder->supplier->telepon)
                            Telp: {{ $purchaseOrder->supplier->telepon }}<br>
                        @endif
                        @if ($purchaseOrder->supplier->email)
                            Email: {{ $purchaseOrder->supplier->email }}<br>
                        @endif
                        @if ($purchaseOrder->supplier->nama_kontak)
                            Kontak: {{ $purchaseOrder->supplier->nama_kontak }}
                            @if ($purchaseOrder->supplier->no_hp)
                                ({{ $purchaseOrder->supplier->no_hp }})
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if ($purchaseOrder->tanggal_pengiriman)
            <div
                style="background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 0.25rem; padding: 0.75rem; margin-bottom: 1rem; display: flex; align-items: center; font-size: 10px;">
                <svg style="height: 14px; width: 14px; color: #d97706; margin-right: 0.5rem;" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <strong style="color: #92400e; margin-right: 0.5rem;">Tanggal Pengiriman:</strong>
                <span
                    style="color: #b45309;">{{ \Carbon\Carbon::parse($purchaseOrder->tanggal_pengiriman)->format('d/m/Y') }}</span>
            </div>
        @endif

        <!-- Items Table -->
        <div
            style="font-size: 10px; font-weight: bold; color: var(--hcb-blue); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">
            Detail Purchase Order:</div>
        <div
            style="overflow-x: auto; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); border-radius: 0.25rem; margin-bottom: 0.5rem;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 8%; text-align: center;">No</th>
                        <th style="width: 40%;">Produk</th>
                        <th style="width: 8%; text-align: center;">Qty</th>
                        <th style="width: 10%; text-align: center;">Satuan</th>
                        <th style="width: 15%; text-align: right;">Harga</th>
                        <th style="width: 10%; text-align: center;">Diskon</th>
                        <th style="width: 19%; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($purchaseOrder->details as $detail)
                        <tr>
                            <td style="text-align: center; font-weight: 600;">{{ $no++ }}</td>
                            <td>
                                <div style="font-weight: 500; color: #111827;">
                                    {{ $detail->nama_item ?? ($detail->produk->nama ?? 'Produk') }}</div>
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
                            <td style="text-align: right; font-weight: 600;">Rp
                                {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Section -->
        <div class="total-summary clearfix">
            <div class="summary-section">
                <div class="summary-item clearfix">
                    <span class="label">Subtotal:</span>
                    <span class="amount">Rp {{ number_format($purchaseOrder->subtotal, 0, ',', '.') }}</span>
                </div>
                @if ($purchaseOrder->diskon_nominal > 0)
                    <div class="summary-item summary-highlight clearfix">
                        <span class="label">Diskon ({{ number_format($purchaseOrder->diskon_persen, 1) }}%):</span>
                        <span class="amount">-Rp
                            {{ number_format($purchaseOrder->diskon_nominal, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if ($purchaseOrder->ppn > 0)
                    <div class="summary-item clearfix">
                        <span class="label">PPN ({{ $purchaseOrder->ppn }}%):</span>
                        <span class="amount">Rp
                            {{ number_format($purchaseOrder->subtotal * ($purchaseOrder->ppn / 100), 0, ',', '.') }}</span>
                    </div>
                @endif
                @if ($purchaseOrder->ongkos_kirim > 0)
                    <div class="summary-item clearfix">
                        <span class="label">Ongkos Kirim:</span>
                        <span class="amount">Rp {{ number_format($purchaseOrder->ongkos_kirim, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="total-final clearfix">
                    <span class="label">TOTAL:</span>
                    <span class="amount">Rp {{ number_format($purchaseOrder->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Clear float untuk memastikan layout yang benar -->
        <div style="clear: both;"></div>

        <!-- Notes and Signature Section -->
        <div style="display: block; width: calc(100% - 8px); margin: 4px 4px;">
            <div style="width: 100%; margin-bottom: 6px;">
                @if ($purchaseOrder->catatan)
                    <div
                        style="margin-bottom: 6px; border-left: 3px solid var(--hcb-orange); padding-left: 6px; background-color: #f8fafc;">
                        <strong style="color: #2c3e50; font-size: 10px;">Catatan:</strong>
                        <p style="font-size: 9px; margin: 2px 0;">{{ $purchaseOrder->catatan }}</p>
                    </div>
                @endif
                @if ($purchaseOrder->syarat_ketentuan)
                    <div
                        style="margin-bottom: 6px; border-left: 3px solid var(--hcb-green); padding-left: 6px; background-color: #f8fafc;">
                        <strong style="color: #2c3e50; font-size: 10px;">Syarat & Ketentuan:</strong>
                        <div style="font-size: 9px; margin-top: 5px; white-space: pre-line;">
                            {{ $purchaseOrder->syarat_ketentuan }}</div>
                    </div>
                @else
                    <div
                        style="margin-bottom: 6px; border-left: 3px solid var(--hcb-green); padding-left: 6px; background-color: #f8fafc;">
                        <strong style="color: #2c3e50; font-size: 10px;">Syarat & Ketentuan:</strong>
                        <ol style="margin-top: 5px; padding-left: 20px;">
                            <li>Barang harus sesuai dengan spesifikasi yang tercantum dalam PO</li>
                            <li>Pembayaran akan dilakukan setelah barang diterima dengan kondisi baik</li>
                            <li>Pengiriman harap disertai dengan surat jalan dan invoice</li>
                        </ol>
                    </div>
                @endif
            </div>
            <!-- Signature Section with QR Codes -->
            <table class="signature-table" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <tr>
                    <td style="width: 45%; text-align: center; vertical-align: bottom;">
                        <div style="margin-bottom: 10px; font-weight: bold; color: var(--hcb-blue); font-size: 10px;">
                            Dibuat oleh:</div>
                        @if (isset($whatsappQR) && $whatsappQR)
                            <div class="qr-signature">
                                <div class="qr-label">Scan untuk Verifikasi via WhatsApp</div>
                                <img src="{{ $whatsappQR }}" alt="WhatsApp Verification QR Code"
                                    class="qr-code-small">
                            </div>
                        @endif
                        <div class="signature-line-hcb"></div>
                        <div style="font-weight: bold; color: #1F2A44; font-size: 10px;">
                            {{ $purchaseOrder->user->name ?? 'Purchasing Manager' }}</div>
                        <div style="font-size: 8px; color: #64748b;">PT Hidayah Cahaya Berkah</div>
                        <div style="font-size: 8px; color: #64748b;">
                            {{ \Carbon\Carbon::parse($purchaseOrder->created_at)->format('d/m/Y H:i') }}</div>
                        <div style="font-size: 8px; color: #64748b;">{{ $purchaseOrder->user->email ?? '' }}</div>
                    </td>
                    <td style="width: 45%; text-align: center; vertical-align: bottom;">
                        @if (isset($isProcessed) && $isProcessed && isset($processedBy) && $processedBy)
                            <div
                                style="margin-bottom: 10px; font-weight: bold; color: var(--hcb-blue); font-size: 10px;">
                                Diproses oleh:</div>
                            @if (isset($whatsappQRProcessor) && $whatsappQRProcessor)
                                <div class="qr-signature">
                                    <div class="qr-label">Scan untuk Verifikasi via WhatsApp</div>
                                    <img src="{{ $whatsappQRProcessor }}" alt="Processor WhatsApp QR Code"
                                        class="qr-code-small">
                                </div>
                            @endif
                            <div class="signature-line-hcb"></div>
                            <div style="font-weight: bold; color: #1F2A44; font-size: 10px;">{{ $processedBy->name }}
                            </div>
                            <div style="font-size: 8px; color: #64748b;">Manager/Supervisor</div>
                            <div style="font-size: 8px; color: #64748b;">
                                {{ isset($processedAt) && $processedAt ? $processedAt->format('d/m/Y H:i') : '' }}
                            </div>
                            <div style="font-size: 8px; color: #64748b;">{{ $processedBy->email ?? '' }}</div>
                        @else
                            <div
                                style="margin-bottom: 10px; font-weight: bold; color: var(--hcb-blue); font-size: 10px;">
                                Menyetujui:</div>
                            <div class="qr-signature">
                                <div class="qr-label">Menunggu Tanda Tangan</div>
                                <div
                                    style="width: 60px; height: 60px; border: 1px dashed #ccc; display: inline-block; line-height: 60px; font-size: 10px; color: #999;">
                                    QR</div>
                            </div>
                            <div class="signature-line-hcb"></div>
                            <div style="font-weight: bold; color: #1F2A44; font-size: 10px;">Direktur</div>
                            <div style="font-size: 8px; color: #64748b;">PT Hidayah Cahaya Berkah</div>
                            <div style="font-size: 8px; color: #64748b;">Tanggal: ___/___/______</div>
                        @endif
                    </td>
                </tr>
            </table>

            {{-- Status Box --}}
            @if (isset($isProcessed) && $isProcessed)
                <div
                    style="margin-top: 8px; padding: 4px 8px; border-radius: 3px; background-color: #f0f9ff; border: 1px solid var(--hcb-blue); color: var(--hcb-blue); font-size: 10px; page-break-inside: avoid;">
                    <strong>Status: TELAH DIPROSES</strong> &mdash; Purchase Order ini telah diproses pada
                    {{ isset($processedAt) && $processedAt ? $processedAt->format('d/m/Y H:i') : $purchaseOrder->updated_at->format('d/m/Y H:i') }}
                    oleh {{ isset($processedBy) && $processedBy ? $processedBy->name : 'Sistem' }}.
                </div>
            @elseif ($purchaseOrder->status === 'draft')
                <div
                    style="margin-top: 8px; padding: 4px 8px; border-radius: 3px; background-color: #fff7ed; border: 1px solid var(--hcb-orange); color: var(--hcb-orange); font-size: 9px; page-break-inside: avoid;">
                    <strong>Status: DRAFT</strong> &mdash; Dokumen ini masih dalam status draft dan belum diproses.
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer-fixed">
            <div style="font-size: 8px;">Dokumen dicetak digital {{ now()->format('d/m/Y H:i') }} WIB • <span
                    style="font-weight: 600; color: var(--hcb-blue);">SemestaPro</span></div>
        </div>
    </div>
</body>

</html>
