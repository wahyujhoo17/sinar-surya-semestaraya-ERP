<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Retur Penjualan - {{ $returPenjualan->nomor }}</title>
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

        .header-table td {
            vertical-align: top;
            padding: 10px;
        }

        .section-title {
            font-weight: bold;
            color: #4a6fa5;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .info-table th {
            font-weight: bold;
            color: #2c3e50;
            width: 20%;
            text-align: left;
            padding: 5px 0;
            vertical-align: top;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            border: 1px solid #ddd;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .items-table th {
            background-color: #4a6fa5;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .items-table td {
            font-size: 11px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .status-label {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-draft {
            background-color: #fbbf24;
            color: #92400e;
        }

        .status-diproses {
            background-color: #60a5fa;
            color: #1e40af;
        }

        .status-selesai {
            background-color: #34d399;
            color: #065f46;
        }

        .notes-box {
            border: 1px dashed #b8c4d6;
            padding: 8px;
            margin-bottom: 15px;
            background-color: #f8fafc;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .signature-table td {
            text-align: center;
            vertical-align: top;
            padding: 10px;
            width: 33.33%;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin: 50px auto 10px auto;
            width: 150px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            margin-top: 30px;
            page-break-inside: avoid;
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
                <h2 style="color: #4a6fa5; margin: 0 0 5px 0;">RETUR PENJUALAN</h2>
                <div>
                    <strong>Nomor:</strong> {{ $returPenjualan->nomor }}<br>
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($returPenjualan->tanggal)->format('d/m/Y') }}<br>
                    <strong>Status:</strong>
                    @php
                        $statusClass = '';
                        $statusLabel = '';

                        switch ($returPenjualan->status) {
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

    <!-- Company and Customer Info Section -->
    <table class="info-table">
        <tr>
            <td style="width: 50%; padding-right: 15px;">
                <div class="section-title">{{ setting('company_name', 'PT. SINAR SURYA SEMESTARAYA') }}</div>
                <div style="padding: 5px;">
                    {{ setting('company_address', 'Jl. Marsekal Surya Darma No. 8 Ruko') }}<br>
                    {{ setting('company_city', 'Neglasari - Tangerang - Banten') }}<br>
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
            <td style="width: 50%; padding-left: 15px;">
                <div class="section-title">Dari Pelanggan</div>
                <div style="padding: 5px;">
                    <strong>{{ $returPenjualan->customer->nama ?? ($returPenjualan->salesOrder->customer->nama ?? 'Customer') }}</strong><br>
                    @if (isset($returPenjualan->customer->company) && $returPenjualan->customer->company)
                        {{ $returPenjualan->customer->company }}<br>
                    @elseif(isset($returPenjualan->salesOrder->customer->company) && $returPenjualan->salesOrder->customer->company)
                        {{ $returPenjualan->salesOrder->customer->company }}<br>
                    @endif
                    {{ $returPenjualan->customer->alamat_utama ?? ($returPenjualan->salesOrder->customer->alamat_utama ?? '-') }}<br>
                    @if (isset($returPenjualan->customer->telepon) && $returPenjualan->customer->telepon)
                        Telp: {{ $returPenjualan->customer->telepon }}<br>
                    @elseif(isset($returPenjualan->salesOrder->customer->telepon) && $returPenjualan->salesOrder->customer->telepon)
                        Telp: {{ $returPenjualan->salesOrder->customer->telepon }}<br>
                    @endif
                    @if (isset($returPenjualan->customer->email) && $returPenjualan->customer->email)
                        Email: {{ $returPenjualan->customer->email }}<br>
                    @elseif(isset($returPenjualan->salesOrder->customer->email) && $returPenjualan->salesOrder->customer->email)
                        Email: {{ $returPenjualan->salesOrder->customer->email }}<br>
                    @endif
                    @if (isset($returPenjualan->customer->nama_kontak) && $returPenjualan->customer->nama_kontak)
                        Kontak: {{ $returPenjualan->customer->nama_kontak }}
                        @if (isset($returPenjualan->customer->no_hp) && $returPenjualan->customer->no_hp)
                            ({{ $returPenjualan->customer->no_hp }})
                        @endif
                    @elseif(isset($returPenjualan->salesOrder->customer->nama_kontak) && $returPenjualan->salesOrder->customer->nama_kontak)
                        Kontak: {{ $returPenjualan->salesOrder->customer->nama_kontak }}
                        @if (isset($returPenjualan->salesOrder->customer->no_hp) && $returPenjualan->salesOrder->customer->no_hp)
                            ({{ $returPenjualan->salesOrder->customer->no_hp }})
                        @endif
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Reference Sales Order Info -->
    <div class="notes-box">
        <strong>Sales Order:</strong> {{ $returPenjualan->salesOrder->nomor ?? '-' }}
        @if (isset($returPenjualan->salesOrder->tanggal))
            ({{ \Carbon\Carbon::parse($returPenjualan->salesOrder->tanggal)->format('d/m/Y') }})
        @endif
    </div>

    <!-- Return Type Info -->
    <div class="notes-box">
        <strong>Tipe Retur:</strong>
        <span
            style="color: {{ $returPenjualan->tipe_retur === 'pengembalian_dana' ? '#c2410c' : '#7e22ce' }}; font-weight: bold;">
            {{ $returPenjualan->tipe_retur === 'pengembalian_dana' ? 'Pengembalian Dana' : 'Tukar Barang' }}
        </span>
    </div>

    <!-- Return Reason -->
    @if ($returPenjualan->alasan)
        <div class="notes-box">
            <strong>Alasan Retur:</strong>
            @switch($returPenjualan->alasan)
                @case('barang_rusak')
                    Barang Rusak
                @break

                @case('barang_cacat')
                    Barang Cacat
                @break

                @case('tidak_sesuai_pesanan')
                    Tidak Sesuai Pesanan
                @break

                @case('kelebihan_pengiriman')
                    Kelebihan Pengiriman
                @break

                @case('lainnya')
                    Lainnya
                @break

                @default
                    {{ $returPenjualan->alasan }}
            @endswitch
        </div>
    @endif

    @if ($returPenjualan->catatan)
        <div class="notes-box">
            <strong>Catatan:</strong> {{ $returPenjualan->catatan }}
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
                <th width="15%">Harga Satuan</th>
                <th width="15%">Subtotal</th>
                <th width="10%">Alasan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $details = $returPenjualan->details;
                $detailCount = count($details);
                $totalRetur = 0;
            @endphp

            @if ($detailCount > 0)
                @foreach ($details as $detail)
                    @php
                        $produkKode = $detail->produk->kode ?? '-';
                        $produkNama = $detail->produk->nama ?? 'Produk';
                        $satuanNama = $detail->satuan->nama ?? '-';
                        $formattedQty = number_format($detail->quantity_retur, 2, ',', '.');
                        $hargaSatuan = $detail->harga_satuan ?? 0;
                        $subtotal = $detail->quantity_retur * $hargaSatuan;
                        $totalRetur += $subtotal;
                        $alasan = $detail->alasan ?? '-';
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $produkKode }}</td>
                        <td>
                            <strong>{{ $produkNama }}</strong>
                            @if ($detail->keterangan)
                                <br><small>{{ $detail->keterangan }}</small>
                            @endif
                        </td>
                        <td class="text-right">{{ $formattedQty }}</td>
                        <td class="text-center">{{ $satuanNama }}</td>
                        <td class="text-right">Rp {{ number_format($hargaSatuan, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $alasan }}</td>
                    </tr>
                @endforeach

                <!-- Total Row -->
                <tr style="background-color: #f8fafc; font-weight: bold;">
                    <td colspan="6" class="text-right"><strong>Total Retur:</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($totalRetur, 0, ',', '.') }}</strong></td>
                    <td></td>
                </tr>
            @else
                <tr>
                    <td colspan="8" class="text-center">Tidak ada detail barang untuk retur penjualan ini.</td>
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
                        style="color: #2c3e50;">{{ $returPenjualan->user->name ?? ($returPenjualan->user->nama ?? 'Admin') }}</strong>
                </div>
                <div style="color: #7f8c8d;">Dibuat Oleh</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div><strong
                        style="color: #2c3e50;">{{ $returPenjualan->customer->nama ?? ($returPenjualan->salesOrder->customer->nama ?? 'Customer') }}</strong>
                </div>
                <div style="color: #7f8c8d;">Pelanggan</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div><strong style="color: #2c3e50;">Manager Penjualan</strong></div>
                <div style="color: #7f8c8d;">Disetujui Oleh</div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara digital pada {{ now()->format('d M Y, H:i') }} WIB</p>
        <p>Retur Penjualan {{ $returPenjualan->nomor }} -
            {{ $returPenjualan->customer->nama ?? ($returPenjualan->salesOrder->customer->nama ?? 'Customer') }}</p>
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
