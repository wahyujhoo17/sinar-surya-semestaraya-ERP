<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Permintaan Pembelian PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 30px;
            color: #333;
            background-color: #fff;
            position: relative;
        }

        /* Page Border */
        body:before {
            content: '';
            position: absolute;
            top: 10px;
            right: 10px;
            bottom: 10px;
            left: 10px;
            border: 1px solid #0055aa;
            z-index: -2;
        }

        /* Inner Border */
        body:after {
            content: '';
            position: absolute;
            top: 12px;
            right: 12px;
            bottom: 12px;
            left: 12px;
            border: 1px solid #ddd;
            z-index: -2;
        }

        .header {
            padding-bottom: 15px;
            border-bottom: 3px double #0055aa;
            margin-bottom: 25px;
            width: 100%;
            position: relative;

        }

        .logo-container {
            text-align: center;
        }

        .logo {
            width: 100%;
            max-width: 450px;
            height: auto;
            margin: 0 auto;
            display: block;
            filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.2));
        }

        .document-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 30px 0;
            color: #0052cc;
            text-transform: uppercase;
            letter-spacing: 3px;
            position: relative;
            text-shadow: 0 1px 2px rgba(0, 85, 170, 0.2);
        }

        .document-meta {
            background: linear-gradient(to bottom, #fafbff, #f0f5ff);
            border: 1px solid #d0d9e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .document-meta:before {
            content: "";
            position: absolute;
            top: -1px;
            left: -1px;
            right: -1px;
            height: 8px;
            background: linear-gradient(90deg, #1a73e8, #0052cc);
            border-radius: 8px 8px 0 0;
        }

        .document-meta table {
            border: none;
            width: 100%;
        }

        .document-meta td {
            border: none;
            padding: 6px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            color: #0055aa;
            text-transform: uppercase;
            font-size: 11px;
        }

        .value {
            font-weight: 600;
        }

        table.items {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 25px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #c8d0e0;
        }

        table.items th {
            background: linear-gradient(135deg, #1a73e8, #0052cc);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.6);
            padding: 14px 10px;
            text-align: left;
            font-weight: 800;
            border: none;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 1px;
        }

        table.items td {
            border: none;
            border-bottom: 1px solid #e0e6f0;
            padding: 13px 10px;
            vertical-align: middle;
        }

        table.items tr:nth-child(odd) {
            background-color: #f7faff;
        }

        table.items tr:nth-child(even) {
            background-color: #ffffff;
        }

        table.items tr:hover {
            background-color: #e6f0ff;
            transition: background-color 0.3s ease;
        }

        table.items tfoot {
            font-weight: bold;
        }

        table.items tfoot th,
        table.items tfoot td {
            background: linear-gradient(to bottom, #f0f5ff, #e0ebff);
            border-top: 2px solid #1a73e8;
            text-align: right;
            padding: 14px 10px;
            color: #0052cc;
            font-weight: 800;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .notes {
            margin-top: 30px;
            background-color: #f9fbff;
            border-left: 6px solid #1a73e8;
            padding: 18px 22px;
            border-radius: 0 8px 8px 0;
            position: relative;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
        }

        .notes:after {
            content: "✎";
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 18px;
            color: #ccc;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #0055aa;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 11px;
        }

        .footer {
            margin-top: 50px;
            width: 100%;
            page-break-inside: avoid;
            position: relative;
        }

        .footer:before {
            content: '';
            display: block;
            width: 100%;
            height: 1px;
            background: linear-gradient(to right, transparent, #0055aa, transparent);
            margin-bottom: 20px;
        }

        .signature-section {
            width: 100%;
            display: table;
            padding-top: 15px;
        }

        .signature-box {
            float: left;
            width: 30%;
            text-align: center;
            margin-right: 3%;
            padding: 15px 0;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #0055aa;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 11px;
        }

        .signature-line {
            margin-top: 70px;
            border-top: 1px solid #333;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .signature-name {
            margin-top: 8px;
            font-weight: bold;
        }

        .signature-date {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            font-weight: bold;
            letter-spacing: 10px;
            color: rgba(0, 85, 170, 0.03);
            z-index: -1;
            white-space: nowrap;
        }

        .page-number {
            text-align: right;
            font-size: 10px;
            color: #777;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .important-info {
            background-color: rgba(0, 85, 170, 0.05);
            border-radius: 5px;
            padding: 2px 5px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="watermark">SINAR SURYA</div>

    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('img/logo_nama3.png') }}" class="logo" alt="PT Sinar Surya Semestaraya">
        </div>
    </div>

    <div class="document-title">PERMINTAAN PEMBELIAN</div>

    <div class="document-meta">
        <table>
            <tr>
                <td width="15%" class="label">Nomor</td>
                <td width="2%">:</td>
                <td width="33%" class="value"><span class="important-info">{{ $permintaanPembelian->nomor }}</span>
                </td>
                <td width="15%" class="label">Departemen</td>
                <td width="2%">:</td>
                <td class="value">{{ $permintaanPembelian->department->nama }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal</td>
                <td>:</td>
                <td class="value">{{ \Carbon\Carbon::parse($permintaanPembelian->tanggal)->format('d F Y') }}</td>
                <td class="label">Pemohon</td>
                <td>:</td>
                <td class="value">{{ $permintaanPembelian->user->name }}</td>
            </tr>
        </table>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Item</th>
                <th width="30%">Spesifikasi</th>
                <th width="15%">Jumlah</th>
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
                    <td>{{ $detail->quantity }} {{ $detail->satuan->nama }}</td>
                    <td>Rp {{ number_format($detail->harga_estimasi, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->quantity * $detail->harga_estimasi, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">Total Estimasi:</th>
                <th colspan="2">Rp {{ number_format($totalEstimasi, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    @if ($permintaanPembelian->catatan)
        <div class="notes">
            <div class="notes-title">Catatan:</div>
            <div>{{ $permintaanPembelian->catatan }}</div>
        </div>
    @endif

    <div class="footer">
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">Pemohon</div>
                <div class="signature-line"></div>
                <div class="signature-name">{{ $permintaanPembelian->user->name }}</div>
                <div class="signature-date">{{ \Carbon\Carbon::parse($permintaanPembelian->tanggal)->format('d/m/Y') }}
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-title">Disetujui oleh</div>
                <div class="signature-line"></div>
                <div class="signature-name">Manager</div>
                <div class="signature-date">Tanggal: ___/___/______</div>
            </div>
            <div class="signature-box">
                <div class="signature-title">Diketahui oleh</div>
                <div class="signature-line"></div>
                <div class="signature-name">Direktur</div>
                <div class="signature-date">Tanggal: ___/___/______</div>
            </div>
        </div>
    </div>

    <div class="page-number">
        Dokumen ini dibuat oleh sistem ERP PT Sinar Surya Semestaraya | Dicetak pada:
        {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>
</body>

</html>
