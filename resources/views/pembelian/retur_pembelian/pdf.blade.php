<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retur Pembelian - {{ $returPembelian->nomor }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 11pt;
            color: #333;
        }

        .container {
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        .header {
            border-bottom: 2px solid #555;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header img {
            max-height: 60px;
        }

        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
        }

        .doc-title {
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
        }

        .doc-number {
            text-align: center;
            margin: -15px 0 20px;
            font-size: 14px;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-item {
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            margin-left: 10px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        th {
            background-color: #f3f4f6;
            text-align: left;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            font-size: 10pt;
        }

        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 30%;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #555;
            height: 40px;
            margin-bottom: 5px;
        }

        .notes {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 20px 0;
            background-color: #f9fafb;
        }

        .text-center {
            text-align: center;
        }

        .qr-box {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 80px;
            height: 80px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            @if ($companyLogo)
                <img src="{{ $companyLogo }}" alt="Company Logo">
            @endif
            <h1>{{ $company->nama }}</h1>
            <p>{{ $company->alamat }}</p>
            <p>{{ $company->telepon }} | {{ $company->email }}</p>
        </div>

        <div class="doc-title">RETUR PEMBELIAN</div>
        <div class="doc-number">{{ $returPembelian->nomor }}</div>

        <div class="info-section">
            <div class="info-item">
                <span class="info-label">Status:</span>
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
                <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal Retur:</span>
                {{ \Carbon\Carbon::parse($returPembelian->tanggal)->format('d/m/Y') }}
            </div>
            <div class="info-item">
                <span class="info-label">Supplier:</span>
                {{ $returPembelian->supplier->nama }}
            </div>
            <div class="info-item">
                <span class="info-label">No. Purchase Order:</span>
                {{ $returPembelian->purchaseOrder->nomor }}
                ({{ \Carbon\Carbon::parse($returPembelian->purchaseOrder->tanggal)->format('d/m/Y') }})
            </div>
            <div class="info-item">
                <span class="info-label">Gudang:</span>
                {{ $returPembelian->gudang->nama }}
            </div>
        </div>

        @if ($returPembelian->catatan)
            <div class="notes">
                <strong>Catatan:</strong><br>
                {{ $returPembelian->catatan }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Produk</th>
                    <th>Kuantitas</th>
                    <th>Satuan</th>
                    <th>Alasan</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($returPembelian->returPembelianDetails as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->produk->kode }}</td>
                        <td>{{ $detail->produk->nama }}</td>
                        <td>{{ number_format($detail->quantity, 2, ',', '.') }}</td>
                        <td>{{ $detail->satuan->nama }}</td>
                        <td>{{ $detail->alasan }}</td>
                        <td>{{ $detail->keterangan ?: '-' }}</td>
                    </tr>
                @endforeach

                @if (count($returPembelian->returPembelianDetails) == 0)
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada detail barang untuk retur pembelian ini.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div>Dibuat oleh,</div>
                <div><strong>{{ $returPembelian->createdBy->name }}</strong></div>
            </div>

            <div class="signature-box">
                <div class="signature-line"></div>
                <div>Diketahui oleh,</div>
                <div><strong>Kepala Gudang</strong></div>
            </div>

            <div class="signature-box">
                <div class="signature-line"></div>
                <div>Disetujui oleh,</div>
                <div><strong>Purchasing Manager</strong></div>
            </div>
        </div>

        <div class="footer">
            <p>Dokumen ini dicetak pada {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
            <p>Retur Pembelian {{ $returPembelian->nomor }} - {{ $returPembelian->supplier->nama }}</p>
        </div>

        @if ($qrCode)
            <div class="qr-box">
                <img src="{{ $qrCode }}" alt="QR Code" style="width: 100%;">
            </div>
        @endif
    </div>
</body>

</html>
