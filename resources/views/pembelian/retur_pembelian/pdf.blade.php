<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Retur Pembelian - {{ $returPembelian->nomor }}</title>
    <!-- Performance optimizations for PDF rendering -->
    <meta name="dompdf.enable_php" content="false">
    <meta name="dompdf.enable_javascript" content="false">
    <meta name="dompdf.enable_remote" content="false">
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

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .signature-table td {
            width: 33.33%;
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

        .notes-box {
            border: 1px dashed #b8c4d6;
            padding: 8px;
            margin-bottom: 15px;
            background-color: #f8fafc;
        }

        .status-label {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }

        .status-draft {
            background-color: #e5e7eb;
            color: #374151;
        }

        .status-diproses {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-selesai {
            background-color: #d1fae5;
            color: #065f46;
        }

        .text-center {
            text-align: center;
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

        .header-with-qr {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .header-content {
            display: table-cell;
            vertical-align: middle;
            width: 100%;
        }

        .header-qr {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
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

        .text-right {
            text-align: right;
        }

        /* Print-specific styling */
        @page {
            size: A4;
            margin: 1cm;
        }
    </style>
</head>

<body>
    <div class="watermark-bg">SINAR SURYA SEMESTARAYA</div>

    <!-- Header Section with QR Code -->
    <div class="header-with-qr">
        <div class="header-content">
            <table class="header-table">
                <tr style="margin-bottom: 10px;">
                    <td style="width: 50%; vertical-align: middle;">
                        @php
                            $logoPath = public_path('img/logo_nama3.png');
                            $defaultLogoPath = public_path('img/logo-default.png');
                            $logoSrc = file_exists($logoPath) ? $logoPath : $defaultLogoPath;
                        @endphp
                        <img src="{{ $logoSrc }}" alt="Sinar Surya Logo" style="height: 60px;">
                    </td>
                    <td style="width: 50%; text-align: right; vertical-align: middle;">
                        <h2 style="color: #4a6fa5; margin: 0 0 5px 0;">RETUR PEMBELIAN</h2>
                        <div>
                            <strong>Nomor:</strong> {{ $returPembelian->nomor }}<br>
                            <strong>Tanggal:</strong>
                            {{ \Carbon\Carbon::parse($returPembelian->tanggal)->format('d/m/Y') }}<br>
                            <strong>Status:</strong>
                            @php
                                $statusClass = '';
                                $statusLabel = '';

                                switch ($returPembelian->status) {
                                    case 'draft':
                                        $statusClass = 'status-draft';
                                        $statusLabel = 'Draft';
                                        break;
                                    case 'diproses':
                                        $statusClass = 'status-diproses';
                                        $statusLabel = 'Diproses';
                                        break;
                                    case 'selesai':
                                        $statusClass = 'status-selesai';
                                        $statusLabel = 'Selesai';
                                        break;
                                }
                            @endphp
                            <span class="status-label {{ $statusClass }}">{{ $statusLabel }}</span>
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
                    <strong>{{ $company->nama }}</strong><br>
                    {{ $company->alamat }}<br>
                    @if ($company->telepon)
                        Telp. {{ $company->telepon }}<br>
                    @endif
                    @if ($company->email)
                        E-mail: {{ $company->email }}
                    @endif
                </div>
            </td>
            <td>
                <div class="section-title">Info Supplier</div>
                <div style="padding: 5px;">
                    <strong>{{ $returPembelian->supplier->nama }}</strong><br>
                    {{ $returPembelian->supplier->alamat ?? '-' }}<br>
                    @if ($returPembelian->supplier->telepon)
                        Telp: {{ $returPembelian->supplier->telepon }}<br>
                    @endif
                    @if ($returPembelian->supplier->email)
                        Email: {{ $returPembelian->supplier->email }}<br>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Reference PO Info -->
    <div class="notes-box">
        <strong>Purchase Order:</strong> {{ $returPembelian->purchaseOrder->nomor }}
        ({{ \Carbon\Carbon::parse($returPembelian->purchaseOrder->tanggal)->format('d/m/Y') }})
    </div>

    <!-- Tipe Retur Info -->
    <div class="notes-box">
        <strong>Tipe Retur:</strong>
        <span
            style="color: {{ $returPembelian->tipe_retur === 'pengembalian_dana' ? '#c2410c' : '#7e22ce' }}; font-weight: bold;">
            {{ $returPembelian->tipe_retur === 'pengembalian_dana' ? 'Pengembalian Dana' : 'Tukar Barang' }}
        </span>
    </div>

    @if ($returPembelian->catatan)
        <div class="notes-box">
            <strong>Catatan:</strong> {{ $returPembelian->catatan }}
        </div>
    @endif

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Kode</th>
                <th width="25%">Produk</th>
                <th width="10%">Qty</th>
                <th width="10%">Satuan</th>
                <th width="20%">Alasan</th>
                <th width="20%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $details = $returPembelian->details;
                $detailCount = count($details);
            @endphp

            @if ($detailCount > 0)
                @foreach ($details as $detail)
                    @php
                        $produkKode = $detail->produk->kode ?? '-';
                        $produkNama = $detail->produk->nama ?? 'Produk';
                        $satuanNama = $detail->satuan->nama ?? '-';
                        $formattedQty = number_format($detail->quantity, 2, ',', '.');
                        $keterangan = $detail->keterangan ?: '-';
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $produkKode }}</td>
                        <td>
                            <strong>{{ $produkNama }}</strong>
                        </td>
                        <td>{{ $formattedQty }}</td>
                        <td>{{ $satuanNama }}</td>
                        <td>{{ $detail->alasan }}</td>
                        <td>{{ $keterangan }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center">Tidak ada detail barang untuk retur pembelian ini.</td>
                </tr>
            @endif
        </tbody>
    </table>

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
                <div><strong
                        style="color: #2c3e50;">{{ $returPembelian->user->name ?? ($returPembelian->user->nama ?? 'Purchasing') }}</strong>
                </div>
                <div style="color: #7f8c8d;">Purchasing</div>
                <div style="font-size:10px;">
                    {{ \Carbon\Carbon::parse($returPembelian->created_at)->format('d/m/Y H:i') }}
                </div>
                <div style="font-size:10px; color: #666;">
                    {{ $returPembelian->user->email ?? '' }}
                </div>
            </td>

            @if ($isApproved && $approvedBy)
                <td>
                    <div><strong style="color: #2c3e50;">Disetujui oleh:</strong></div>

                    {{-- QR Code for Approver --}}
                    @if (isset($qrCodes['processed_qr']) && $qrCodes['processed_qr'])
                        <div class="qr-signature">
                            <div class="qr-label">Tanda Tangan Digital</div>
                            <img src="{{ $qrCodes['processed_qr'] }}" alt="Approver QR Code" class="qr-code-small">
                        </div>
                    @endif

                    <div class="signature-line"></div>
                    <div><strong style="color: #2c3e50;">{{ $approvedBy->name }}</strong></div>
                    <div style="color: #7f8c8d;">Manager/Supervisor</div>
                    <div style="font-size:10px;">
                        {{ $approvedAt ? $approvedAt->format('d/m/Y H:i') : '' }}
                    </div>
                    <div style="font-size:10px; color: #666;">
                        {{ $approvedBy->email ?? '' }}
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
    @if ($isApproved)
        <div class="status-box status-processed">
            <h4><strong>Status: TELAH DISETUJUI</strong></h4>
            <p>
                Retur Pembelian ini telah disetujui pada
                {{ $approvedAt ? $approvedAt->format('d F Y \p\u\k\u\l H:i') : $returPembelian->updated_at->format('d F Y \p\u\k\u\l H:i') }}
                oleh {{ $approvedBy ? $approvedBy->name : 'Sistem' }}.
            </p>
        </div>
    @elseif ($returPembelian->status === 'draft')
        <div class="status-box status-draft">
            <h4><strong>Status: DRAFT</strong></h4>
            <p>
                Dokumen ini masih dalam status draft dan belum disetujui.
            </p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara digital pada {{ now()->format('d M Y, H:i') }} WIB</p>
        <p>Retur Pembelian {{ $returPembelian->nomor }} - {{ $returPembelian->supplier->nama }}</p>
        <div style="font-size: 9px; color: #aaa; margin-top: 5px;">
            © {{ date('Y') }} PT. SINAR SURYA SEMESTARAYA. All rights reserved.
        </div>
    </div>

    <script type="application/javascript">
        // Basic inline script to improve PDF layout rendering
        window.onload = function() {
            document.body.classList.add('pdf-ready');
        }
    </script>
</body>

</html>
