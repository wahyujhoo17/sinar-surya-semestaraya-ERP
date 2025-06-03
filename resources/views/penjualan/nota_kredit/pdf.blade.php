<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Nota Kredit - {{ $notaKredit->nomor }}</title>
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
                <h2 style="color: #4a6fa5; margin: 0 0 5px 0;">NOTA KREDIT</h2>
                <div>
                    <strong>Nomor:</strong> {{ $notaKredit->nomor }}<br>
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($notaKredit->tanggal)->format('d/m/Y') }}<br>
                    <strong>Status:</strong>
                    @php
                        $statusClass = '';
                        $statusLabel = ucfirst($notaKredit->status);

                        switch ($notaKredit->status) {
                            case 'draft':
                                $statusClass = 'status-draft';
                                break;
                            case 'diproses':
                                $statusClass = 'status-diproses';
                                break;
                            case 'selesai':
                                $statusClass = 'status-selesai';
                                break;
                        }
                    @endphp
                    <span class="status-label {{ $statusClass }}">{{ $statusLabel }}</span>
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
                    <strong>PT SINAR SURYA SEMESTARAYA</strong><br>
                    Jl. Industri No. 123, Kawasan Industri Pulogadung, Jakarta Timur<br>
                    Telp: (021) 4567-8901<br>
                    E-mail: info@sinarsurya.com
                </div>
            </td>
            <td>
                <div class="section-title">Info Customer</div>
                <div style="padding: 5px;">
                    <strong>{{ $notaKredit->customer->nama ?? $notaKredit->customer->company }}</strong><br>
                    {{ $notaKredit->customer->alamat ?? '-' }}<br>
                    @if ($notaKredit->customer->telepon)
                        Telp: {{ $notaKredit->customer->telepon }}<br>
                    @endif
                    @if ($notaKredit->customer->email)
                        Email: {{ $notaKredit->customer->email }}<br>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Reference Info -->
    <div class="notes-box">
        <strong>Referensi Retur:</strong> {{ $notaKredit->returPenjualan->nomor }}
        ({{ \Carbon\Carbon::parse($notaKredit->returPenjualan->tanggal)->format('d/m/Y') }})
    </div>

    @if ($notaKredit->salesOrder)
        <div class="notes-box">
            <strong>Sales Order:</strong> {{ $notaKredit->salesOrder->nomor }}
        </div>
    @endif

    @if ($notaKredit->catatan)
        <div class="notes-box">
            <strong>Catatan:</strong> {{ $notaKredit->catatan }}
        </div>
    @endif

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode</th>
                <th width="35%">Produk</th>
                <th width="10%">Qty</th>
                <th width="10%">Satuan</th>
                <th width="12%">Harga</th>
                <th width="13%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $details = $notaKredit->details;
                $detailCount = count($details);
            @endphp

            @if ($detailCount > 0)
                @foreach ($details as $index => $detail)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $detail->produk->kode ?? 'N/A' }}</td>
                        <td>
                            <strong>{{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}</strong>
                        </td>
                        <td class="text-right">{{ number_format($detail->quantity, 2, ',', '.') }}</td>
                        <td>{{ $detail->satuan->nama ?? 'N/A' }}</td>
                        <td class="text-right">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center">Tidak ada detail barang untuk nota kredit ini.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Total and Terbilang -->
    <div style="width: 60%; float: right; margin-top: 15px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px; font-weight: bold; text-align: right; width: 60%;">Total:</td>
                <td style="padding: 8px; text-align: right; width: 40%; font-weight: bold;">
                    Rp {{ number_format($notaKredit->total, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <div style="clear: both;"></div>

    <div class="notes-box" style="margin-top: 15px;">
        <strong>Terbilang:</strong> <em>{{ ucwords(terbilang($notaKredit->total)) }} Rupiah</em>
    </div>

    <!-- Signatures -->
    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-line"></div>
                <div><strong style="color: #2c3e50;">{{ $notaKredit->user->name ?? 'Admin' }}</strong></div>
                <div style="color: #7f8c8d;">Dibuat Oleh</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div><strong style="color: #2c3e50;">Kepala Bagian Keuangan</strong></div>
                <div style="color: #7f8c8d;">Diperiksa Oleh</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div><strong style="color: #2c3e50;">{{ $notaKredit->customer->nama }}</strong></div>
                <div style="color: #7f8c8d;">Diterima Oleh</div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara digital pada {{ now()->format('d M Y, H:i') }} WIB</p>
        <p>Nota Kredit {{ $notaKredit->nomor }} - {{ $notaKredit->customer->nama }}</p>
        <div style="font-size: 9px; color: #aaa; margin-top: 5px;">
            © {{ date('Y') }} PT. SINAR SURYA SEMESTARAYA. All rights reserved.
        </div>
    </div>
</body>

</html>
