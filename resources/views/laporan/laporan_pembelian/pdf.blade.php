<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0 0 5px;
        }

        .header p {
            font-size: 12px;
            margin: 0;
        }

        .meta-info {
            margin-bottom: 20px;
        }

        .meta-info table {
            width: 100%;
        }

        .meta-info table td {
            padding: 3px 0;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 11px;
        }

        table.data-table th {
            background-color: #f2f2f2;
            text-align: left;
            font-weight: bold;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .summary {
            margin-top: 20px;
        }

        .summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary table th,
        .summary table td {
            padding: 5px;
            text-align: right;
        }

        .summary table th {
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }

        .status {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
            text-align: center;
            display: inline-block;
            min-width: 60px;
        }

        .status-lunas {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-sebagian {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-belum {
            background-color: #fee2e2;
            color: #b91c1c;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN PEMBELIAN</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($filters['tanggal_awal'])->format('d M Y') }} -
            {{ \Carbon\Carbon::parse($filters['tanggal_akhir'])->format('d M Y') }}</p>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td width="15%"><strong>Tanggal Cetak</strong></td>
                <td width="5%">:</td>
                <td>{{ now()->format('d/m/Y H:i:s') }}</td>
                <td width="15%"><strong>Total Pembelian</strong></td>
                <td width="5%">:</td>
                <td>Rp {{ number_format($totalPembelian, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Filter</strong></td>
                <td>:</td>
                <td>{{ $filters['search'] ? $filters['search'] : 'Semua' }}</td>
                <td><strong>Total Dibayar</strong></td>
                <td>:</td>
                <td>Rp {{ number_format($totalDibayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Status</strong></td>
                <td>:</td>
                <td>{{ $filters['status_pembayaran'] ? ucfirst($filters['status_pembayaran']) : 'Semua' }}</td>
                <td><strong>Sisa Pembayaran</strong></td>
                <td>:</td>
                <td>Rp {{ number_format($sisaPembayaran, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Nomor Faktur</th>
                <th width="10%">Tanggal</th>
                <th width="20%">Supplier</th>
                <th width="10%">Status</th>
                <th width="12%">Total</th>
                <th width="12%">Dibayar</th>
                <th width="12%">Sisa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataPembelian as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nomor_faktur }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $item->supplier_nama }}</td>
                    <td>
                        @if ($item->status == 'lunas')
                            <div class="status status-lunas">Lunas</div>
                        @elseif($item->status == 'sebagian')
                            <div class="status status-sebagian">Sebagian</div>
                        @else
                            <div class="status status-belum">Belum Bayar</div>
                        @endif
                    </td>
                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->total - $item->total_bayar, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data pembelian</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <th width="20%">Total Pembelian:</th>
                <td>Rp {{ number_format($totalPembelian, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Dibayar:</th>
                <td>Rp {{ number_format($totalDibayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Sisa Pembayaran:</th>
                <td>Rp {{ number_format($sisaPembayaran, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini dicetak dari sistem ERP Sinar Surya pada {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>

</html>
