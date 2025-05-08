<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Permintaan Pembelian - {{ $permintaanPembelian->nomor }}</title>
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
            width: 33%;
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

        .text-right {
            text-align: right;
        }

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
        <tr>
            <td style="width: 50%; vertical-align: middle;">
                <img src="{{ public_path('img/logo_nama3.png') }}" alt="Sinar Surya Logo"
                    onerror="this.src='{{ public_path('img/logo-default.png') }}';" style="height: 60px;">
            </td>
            <td style="width: 50%; text-align: right; vertical-align: middle;">
                <h2 style="color: #4a6fa5; margin: 0 0 5px 0;">PERMINTAAN PEMBELIAN</h2>
                <div>
                    <strong>Nomor:</strong> {{ $permintaanPembelian->nomor }}<br>
                    <strong>Tanggal:</strong>
                    {{ \Carbon\Carbon::parse($permintaanPembelian->tanggal)->format('d/m/Y') }}<br>
                    <strong>Status:</strong> <span
                        style="text-transform: uppercase; color: #3498db;">{{ $permintaanPembelian->status ?? '-' }}</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Company and Pemohon Info Section -->
    <table class="info-table">
        <tr>
            <td>
                <div class="section-title">Info Perusahaan</div>
                <div style="padding: 5px;">
                    <strong>PT. SINAR SURYA SEMESTARAYA</strong><br>
                    Jl. Condet Raya No. 6 Balekambang<br>
                    Jakarta Timur 13530<br>
                    Telp. (021) 80876624 - 80876642<br>
                    E-mail: admin@kliksinarsurya.com<br>
                    sinar.surya@hotmail.com<br>
                    sinarsurya.sr@gmail.com
                </div>
            </td>
            <td>
                <div class="section-title">Info Permintaan</div>
                <div style="padding: 5px;">
                    <strong>Departemen:</strong> {{ $permintaanPembelian->department->nama ?? '-' }}<br>
                    <strong>Pemohon:</strong> {{ $permintaanPembelian->user->name ?? '-' }}<br>
                    <strong>Tanggal Permintaan:</strong>
                    {{ \Carbon\Carbon::parse($permintaanPembelian->tanggal)->format('d/m/Y') }}<br>
                </div>
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Item</th>
                <th width="25%">Spesifikasi</th>
                <th width="10%">Jumlah</th>
                <th width="15%">Harga Est.</th>
                <th width="15%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permintaanPembelian->details as $i => $detail)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detail->nama_item }}</td>
                    <td>
                        @if ($detail->produk)
                            {{ $detail->produk->ukuran ? 'Ukuran: ' . $detail->produk->ukuran . '; ' : '' }}
                            {{ $detail->produk->merek ? 'Merek: ' . $detail->produk->merek . '; ' : '' }}
                            {{ $detail->deskripsi ? 'Catatan: ' . $detail->deskripsi : '' }}
                        @else
                            {{ $detail->deskripsi ?? '-' }}
                        @endif
                    </td>
                    <td>{{ $detail->quantity }} {{ $detail->satuan->nama ?? '-' }}</td>
                    <td>Rp {{ number_format($detail->harga_estimasi, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->quantity * $detail->harga_estimasi, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary Table -->
    <table class="summary-table">
        <tr class="total-row">
            <td><strong>Total Estimasi</strong></td>
            <td class="text-right"><strong>Rp {{ number_format($totalEstimasi, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <!-- Notes Section -->
    @if ($permintaanPembelian->catatan)
        <div
            style="margin-bottom: 15px; border-left: 3px solid #4a6fa5; padding-left: 10px; background-color: #f8fafc;">
            <strong style="color: #2c3e50;">Catatan:</strong>
            <p>{{ $permintaanPembelian->catatan }}</p>
        </div>
    @endif

    <!-- Signatures -->
    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-line"></div>
                <div><strong style="color: #2c3e50;">{{ $permintaanPembelian->user->name ?? 'Pemohon' }}</strong></div>
                <div style="color: #7f8c8d;">Pemohon</div>
                <div style="font-size:10px;">
                    {{ \Carbon\Carbon::parse($permintaanPembelian->tanggal)->format('d/m/Y') }}</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div><strong style="color: #2c3e50;">Disetujui oleh</strong></div>
                <div style="color: #7f8c8d;">Manager</div>
                <div style="font-size:10px;">Tanggal: ___/___/______</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div><strong style="color: #2c3e50;">Diketahui oleh</strong></div>
                <div style="color: #7f8c8d;">Direktur</div>
                <div style="font-size:10px;">Tanggal: ___/___/______</div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dibuat oleh sistem ERP PT Sinar Surya Semestaraya | Dicetak pada:
            {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>
</body>

</html>
