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
            margin: 50px auto 10px auto;
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
    <!-- Header Section -->
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
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($returPembelian->tanggal)->format('d/m/Y') }}<br>
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

    <!-- Signatures -->
    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-line"></div>
                <div><strong
                        style="color: #2c3e50;">{{ $returPembelian->user->name ?? ($returPembelian->user->nama ?? 'Purchasing') }}</strong>
                </div>
                <div style="color: #7f8c8d;">Dibuat Oleh</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div><strong style="color: #2c3e50;">Kepala Gudang</strong></div>
                <div style="color: #7f8c8d;">Diketahui Oleh</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div><strong style="color: #2c3e50;">Purchasing Manager</strong></div>
                <div style="color: #7f8c8d;">Disetujui Oleh</div>
            </td>
        </tr>
    </table>

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
