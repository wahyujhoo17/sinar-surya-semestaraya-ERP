<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Purchase Order - {{ $purchaseOrder->nomor }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
            margin: 20px auto 10px auto;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            margin-top: 30px;
        }

        /* Simple utility classes */
        .text-right {
            text-align: right;
        }

        /* Print-specific styling */
        @page {
            size: A4;
            margin: 1cm;
        }

        /* QR Code Styles */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            opacity: 0.1;
            color: #FF0000;
            font-weight: bold;
            z-index: 0;
            text-align: center;
            width: 100%;
        }

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

        .header-with-qr {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .header-content {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .header-qr {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 120px;
        }

        .status-box {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            page-break-inside: avoid;
        }

        .status-processed {
            background-color: #f0f9ff;
            border: 1px solid #0ea5e9;
            color: #0369a1;
        }

        .status-draft {
            background-color: #fffbeb;
            border: 1px solid #f59e0b;
            color: #d97706;
        }

        .status-box h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
        }

        .status-box p {
            margin: 0;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="watermark-bg">{{ strtoupper(setting('company_name', 'SINAR SURYA SEMESTARAYA')) }}</div>

    <!-- Header Section with QR Code -->
    <div class="header-with-qr">
        <div class="header-content">
            <table class="header-table">
                <tr style="margin-bottom: 10px;">
                    <td style="width: 50%; vertical-align: middle;">
                        <img src="{{ public_path('img/logo_nama3.png') }}" alt="Sinar Surya Logo"
                            onerror="this.src='{{ public_path('img/logo-default.png') }}';" style="height: 60px;">
                    </td>
                    <td style="width: 50%; text-align: right; vertical-align: middle;">
                        <h2 style="color: #4a6fa5; margin: 0 0 5px 0;">PURCHASE ORDER</h2>
                        <div>
                            <strong>Nomor:</strong> {{ $purchaseOrder->nomor }}<br>
                            <strong>Tanggal:</strong>
                            {{ \Carbon\Carbon::parse($purchaseOrder->tanggal)->format('d/m/Y') }}<br>
                            <strong>Status:</strong> <span
                                style="text-transform: uppercase; color: #3498db;">{{ $purchaseOrder->status ?? '-' }}</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

    </div>

    <!-- Company and Supplier Info Section -->
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
                <div class="section-title">Order Ke</div>
                <div style="padding: 5px;">
                    <strong>{{ $purchaseOrder->supplier->nama }}</strong><br>
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
                        <br>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Shipping Info if available -->
    @if ($purchaseOrder->alamat_pengiriman)
        <div style="border: 1px dashed #b8c4d6; padding: 8px; margin-bottom: 15px; background-color: #f8fafc;">
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
            @php $no = 1; @endphp
            @foreach ($purchaseOrder->details as $detail)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>
                        <strong>{{ $detail->nama_item ?? ($detail->produk->nama ?? 'Produk') }}</strong>
                        @if ($detail->deskripsi)
                            <br><span style="font-size: 10px;">{{ $detail->deskripsi }}</span>
                        @endif
                    </td>
                    <td>{{ number_format($detail->quantity, 0) }}</td>
                    <td>{{ $detail->satuan->nama ?? '-' }}</td>
                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td>
                        @if ($detail->diskon_persen > 0)
                            {{ number_format($detail->diskon_persen, 1) }}%
                        @endif
                        @if ($detail->diskon_nominal > 0)
                            Rp {{ number_format($detail->diskon_nominal, 0, ',', '.') }}
                        @endif
                    </td>
                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary Table -->
    <table class="summary-table">
        <tr>
            <td>Subtotal</td>
            <td class="text-right">Rp {{ number_format($purchaseOrder->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if ($purchaseOrder->diskon_nominal > 0)
            <tr>
                <td>Diskon ({{ number_format($purchaseOrder->diskon_persen, 1) }}%)</td>
                <td class="text-right">Rp {{ number_format($purchaseOrder->diskon_nominal, 0, ',', '.') }}</td>
            </tr>
        @endif
        @if ($purchaseOrder->ppn > 0)
            <tr>
                <td>PPN ({{ $purchaseOrder->ppn }}%)</td>
                <td class="text-right">Rp
                    {{ number_format($purchaseOrder->subtotal * ($purchaseOrder->ppn / 100), 0, ',', '.') }}</td>
            </tr>
        @endif
        @if ($purchaseOrder->ongkos_kirim > 0)
            <tr>
                <td>Ongkos Kirim</td>
                <td class="text-right">Rp {{ number_format($purchaseOrder->ongkos_kirim, 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr class="total-row">
            <td><strong>Total</strong></td>
            <td class="text-right"><strong>Rp {{ number_format($purchaseOrder->total, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <!-- Notes Section -->
    @if ($purchaseOrder->catatan)
        <div
            style="margin-bottom: 15px; border-left: 3px solid #4a6fa5; padding-left: 10px; background-color: #f8fafc;">
            <strong style="color: #2c3e50;">Catatan:</strong>
            <p>{{ $purchaseOrder->catatan }}</p>
        </div>
    @endif

    <!-- Terms and Conditions -->
    <div style="margin-bottom: 15px; border-left: 3px solid #4a6fa5; padding-left: 10px; background-color: #f8fafc;">
        <strong style="color: #2c3e50;">Syarat & Ketentuan:</strong>
        @if ($purchaseOrder->syarat_ketentuan)
            <div style="margin-top: 5px;">{{ $purchaseOrder->syarat_ketentuan }}</div>
        @else
            <ol style="margin-top: 5px; padding-left: 20px;">
                <li>Barang harus sesuai dengan spesifikasi yang tercantum dalam PO</li>
                <li>Pembayaran akan dilakukan setelah barang diterima dengan kondisi baik</li>
                <li>Pengiriman harap disertai dengan surat jalan dan invoice</li>
            </ol>
        @endif
    </div>

    <!-- Signatures with QR Codes -->
    <table class="signature-table">
        <tr>
            <td>
                <div><strong style="color: #2c3e50;">Dibuat oleh:</strong></div>

                {{-- QR Code for Creator --}}
                @if (isset($qrCodes['created_qr']) && $qrCodes['created_qr'])
                    <div class="qr-signature">
                        <div class="qr-label">Tanda Tangan Digital</div>
                        <img src="{{ $qrCodes['created_qr'] }}" alt="Creator QR Code" class="qr-code-small">
                    </div>
                @endif

                <div class="signature-line"></div>
                <div><strong style="color: #2c3e50;">{{ $purchaseOrder->user->name ?? 'Purchasing' }}</strong></div>
                <div style="color: #7f8c8d;">Purchasing</div>
                <div style="font-size:10px;">
                    {{ \Carbon\Carbon::parse($purchaseOrder->created_at)->format('d/m/Y H:i') }}
                </div>
                <div style="font-size:10px; color: #666;">
                    {{ $purchaseOrder->user->email ?? '' }}
                </div>
            </td>

            @if ($isProcessed && $processedBy)
                <td>
                    <div><strong style="color: #2c3e50;">Diproses oleh:</strong></div>

                    {{-- QR Code for Processor --}}
                    @if (isset($qrCodes['processed_qr']) && $qrCodes['processed_qr'])
                        <div class="qr-signature">
                            <div class="qr-label">Tanda Tangan Digital</div>
                            <img src="{{ $qrCodes['processed_qr'] }}" alt="Processor QR Code" class="qr-code-small">
                        </div>
                    @endif

                    <div class="signature-line"></div>
                    <div><strong style="color: #2c3e50;">{{ $processedBy->name }}</strong></div>
                    <div style="color: #7f8c8d;">Manager/Supervisor</div>
                    <div style="font-size:10px;">
                        {{ $processedAt ? $processedAt->format('d/m/Y H:i') : '' }}
                    </div>
                    <div style="font-size:10px; color: #666;">
                        {{ $processedBy->email ?? '' }}
                    </div>
                </td>
            @else
                <td>
                    <div><strong style="color: #2c3e50;">Disetujui oleh:</strong></div>
                    <div class="signature-line"></div>

                    <div class="qr-signature">
                        <div class="qr-label">Menunggu Tanda Tangan</div>
                        <div
                            style="width: 60px; height: 60px; border: 1px dashed #ccc; display: inline-block; line-height: 60px; font-size: 10px; color: #999;">
                            QR
                        </div>
                    </div>

                    <div><strong style="color: #2c3e50;">Menunggu Persetujuan</strong></div>
                    <div style="color: #7f8c8d;">Manager/Direktur</div>
                    <div style="font-size:10px;">Tanggal: ___/___/______</div>
                </td>
            @endif
        </tr>
    </table>

    {{-- Status Box --}}
    @if ($isProcessed)
        <div class="status-box status-processed">
            <h4><strong>Status: TELAH DIPROSES</strong></h4>
            <p>
                Purchase Order ini telah diproses pada
                {{ $processedAt ? $processedAt->format('d F Y \p\u\k\u\l H:i') : $purchaseOrder->updated_at->format('d F Y \p\u\k\u\l H:i') }}
                oleh {{ $processedBy ? $processedBy->name : 'Sistem' }}.
            </p>
        </div>
    @elseif ($purchaseOrder->status === 'draft')
        <div class="status-box status-draft">
            <h4><strong>Status: DRAFT</strong></h4>
            <p>
                Dokumen ini masih dalam status draft dan belum diproses.
            </p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }} |
            {{ setting('company_name', 'PT Sinar Surya Semestaraya') }} ERP System</p>
    </div>
</body>

</html>
