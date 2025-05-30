<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Nota Kredit - {{ $notaKredit->nomor }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .company-address {
            font-size: 12px;
            margin: 5px 0;
        }

        .document-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 15px 0;
            text-decoration: underline;
        }

        .info-section {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-section:after {
            content: "";
            display: table;
            clear: both;
        }

        .info-left {
            float: left;
            width: 48%;
        }

        .info-right {
            float: right;
            width: 48%;
            text-align: right;
        }

        .info-row {
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        .table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .table .number {
            text-align: right;
        }

        .total-section {
            margin-top: 20px;
            text-align: right;
        }

        .total-row {
            margin-bottom: 5px;
        }

        .total-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }

        .total-value {
            display: inline-block;
            width: 150px;
            text-align: right;
        }

        .signature-section {
            margin-top: 40px;
        }

        .signature-row {
            width: 100%;
        }

        .signature-row:after {
            content: "";
            display: table;
            clear: both;
        }

        .signature-box {
            float: left;
            width: 30%;
            text-align: center;
        }

        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #333;
            width: 80%;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 class="company-name">PT SINAR SURYA</h1>
        <p class="company-address">Jl. Industri No. 123, Kawasan Industri Pulogadung, Jakarta Timur</p>
        <p class="company-address">Telp: (021) 4567-8901 | Email: info@sinarsurya.com</p>
    </div>

    <h2 class="document-title">NOTA KREDIT</h2>

    <div class="info-section">
        <div class="info-left">
            <div class="info-row">
                <span class="info-label">Kepada:</span>
                <span>{{ $notaKredit->customer->nama }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Alamat:</span>
                <span>{{ $notaKredit->customer->alamat }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Telepon:</span>
                <span>{{ $notaKredit->customer->telepon }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ref. Retur:</span>
                <span>{{ $notaKredit->returPenjualan->nomor }}</span>
            </div>
        </div>
        <div class="info-right">
            <div class="info-row">
                <span class="info-label">No. Nota Kredit:</span>
                <span>{{ $notaKredit->nomor }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal:</span>
                <span>{{ \Carbon\Carbon::parse($notaKredit->tanggal)->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Sales Order:</span>
                <span>{{ $notaKredit->salesOrder->nomor ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span>{{ ucfirst($notaKredit->status) }}</span>
            </div>
        </div>
    </div>

    <table class="table">
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
            @foreach ($notaKredit->details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->produk->kode ?? 'N/A' }}</td>
                    <td>{{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}</td>
                    <td class="number">{{ number_format($detail->quantity, 2) }}</td>
                    <td>{{ $detail->satuan->nama ?? 'N/A' }}</td>
                    <td class="number">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td class="number">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <span class="total-label">Total:</span>
            <span class="total-value">Rp {{ number_format($notaKredit->total, 0, ',', '.') }}</span>
        </div>
        <div class="total-row">
            <span class="total-label">Terbilang:</span>
            <span>{{ ucwords(terbilang($notaKredit->total)) }} Rupiah</span>
        </div>
    </div>

    <div class="info-section" style="margin-top: 20px;">
        <div class="info-left">
            <div class="info-row">
                <span class="info-label">Catatan:</span>
                <span>{{ $notaKredit->catatan ?? 'Tidak ada catatan' }}</span>
            </div>
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-row">
            <div class="signature-box">
                <p>Dibuat oleh,</p>
                <div class="signature-line"></div>
                <p>{{ $notaKredit->user->name ?? 'Admin' }}</p>
            </div>
            <div class="signature-box">
                <p>Diperiksa oleh,</p>
                <div class="signature-line"></div>
                <p>Kepala Bagian Keuangan</p>
            </div>
            <div class="signature-box">
                <p>Diterima oleh,</p>
                <div class="signature-line"></div>
                <p>{{ $notaKredit->customer->nama }}</p>
            </div>
        </div>
    </div>
</body>

</html>
