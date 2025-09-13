<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Permintaan Barang - {{ $permintaanBarang->nomor }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            position: relative;
        }

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

        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header p {
            font-size: 12px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .info-table {
            width: 100%;
            border: none;
            margin-bottom: 20px;
        }

        .info-table td {
            border: none;
            padding: 3px 0;
        }

        .info-table .label {
            width: 120px;
            font-weight: bold;
        }

        .summary-section {
            margin-top: 30px;
            position: relative;
            z-index: 1;
        }

        .summary-box {
            border: 1px solid #000;
            padding: 15px;
            margin-bottom: 20px;
        }

        .summary-title {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 8px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 8px 12px;
            border-bottom: 1px dotted #ccc;
        }

        .summary-table td:first-child {
            font-weight: bold;
            width: 40%;
        }

        .summary-table td:last-child {
            text-align: right;
            font-weight: bold;
        }

        .summary-table tr:last-child td {
            border-bottom: none;
        }

        .signature-section {
            margin-top: 40px;
            position: relative;
            z-index: 1;
        }

        .signature-table {
            width: 100%;
            border: none;
        }

        .signature-table td {
            border: none;
            padding: 10px;
            text-align: center;
            vertical-align: top;
            width: 50%;
        }

        .signature-box {
            border: 1px solid #000;
            min-height: 80px;
            padding: 10px;
            text-align: center;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .signature-name {
            margin-top: 50px;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 10px;
            text-align: center;
            padding: 10px;
            background-color: white;
            z-index: 1;
        }

        .page-break {
            page-break-after: always;
        }

        .qr-code {
            text-align: center;
            margin: 10px 0;
        }

        .status-badge {
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    @if ($permintaanBarang->status == 'draft')
        <div class="watermark">DRAFT</div>
    @elseif($permintaanBarang->status == 'dibatalkan')
        <div class="watermark">DIBATALKAN</div>
    @endif

    <div class="header">
        <h1>PERMINTAAN BARANG</h1>
        <p>Nomor: {{ $permintaanBarang->nomor }}</p>
        <p>
            Status: <span class="status-badge">{{ strtoupper($permintaanBarang->status) }}</span>
        </p>
    </div>

    <!-- Informasi Permintaan -->
    <table class="info-table">
        <tr>
            <td class="label">Tanggal:</td>
            <td>{{ \Carbon\Carbon::parse($permintaanBarang->tanggal)->format('d F Y') }}</td>
            <td class="label" style="padding-left: 20px;">Gudang:</td>
            <td>{{ $permintaanBarang->gudang->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Customer:</td>
            <td>{{ $permintaanBarang->customer->nama ?? '-' }}</td>
            <td class="label" style="padding-left: 20px;">Sales Order:</td>
            <td>{{ $permintaanBarang->salesOrder->nomor ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Dibuat oleh:</td>
            <td>{{ $createdBy->name ?? '-' }}</td>
            <td class="label" style="padding-left: 20px;">Tanggal Dibuat:</td>
            <td>{{ \Carbon\Carbon::parse($permintaanBarang->created_at)->format('d F Y H:i') }}</td>
        </tr>
        @if ($processedBy)
            <tr>
                <td class="label">Diproses oleh:</td>
                <td>{{ $processedBy->name ?? '-' }}</td>
                <td class="label" style="padding-left: 20px;">Tanggal Diproses:</td>
                <td>{{ $processedAt ? \Carbon\Carbon::parse($processedAt)->format('d F Y H:i') : '-' }}</td>
            </tr>
        @endif
    </table>

    <!-- Detail Permintaan Barang -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Kode Produk</th>
                <th style="width: 35%;">Nama Produk</th>
                <th style="width: 10%;">Jumlah</th>
                <th style="width: 8%;">Satuan</th>
                <th style="width: 12%;">Jumlah Tersedia</th>
                <th style="width: 15%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permintaanBarang->details as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $detail->produk->kode ?? '-' }}</td>
                    <td>{{ $detail->produk->nama ?? '-' }}</td>
                    <td class="text-right">{{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $detail->satuan->nama ?? '-' }}</td>
                    <td class="text-right">{{ number_format($detail->jumlah_tersedia, 0, ',', '.') }}</td>
                    <td>{{ $detail->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($permintaanBarang->catatan)
        <!-- Catatan -->
        <div style="margin-top: 20px; padding: 10px; border: 1px solid #000; position: relative; z-index: 1;">
            <strong>Catatan:</strong><br>
            {{ $permintaanBarang->catatan }}
        </div>
    @endif

    <!-- Ringkasan -->
    <div class="summary-section">
        <div class="summary-box">
            <div class="summary-title">RINGKASAN PERMINTAAN BARANG</div>
            <table class="summary-table">
                <tr>
                    <td>Total Jenis Item</td>
                    <td>{{ $totalItems }} item</td>
                </tr>
                <tr>
                    <td>Total Jumlah Diminta</td>
                    <td>{{ number_format($permintaanBarang->details->sum('jumlah'), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Jumlah Tersedia</td>
                    <td>{{ number_format($permintaanBarang->details->sum('jumlah_tersedia'), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Status Permintaan</td>
                    <td>{{ ucfirst($permintaanBarang->status) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Tanda Tangan -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-box">
                        <div class="signature-title">Dibuat Oleh</div>
                        <div class="signature-name">{{ $createdBy->name ?? '.......................' }}</div>
                    </div>
                </td>
                <td>
                    <div class="signature-box">
                        <div class="signature-title">Disetujui Oleh</div>
                        <div class="signature-name">.......................</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>


    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis pada {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }}</p>
        <p>{{ config('app.name') }} - Sistem ERP</p>
    </div>
</body>

</html>
