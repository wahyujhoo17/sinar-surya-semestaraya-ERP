<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
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
            font-weight: bold;
            margin: 0;
            padding: 0;
        }

        .header p {
            font-size: 14px;
            margin: 5px 0 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }

        td {
            padding: 6px 8px;
            font-size: 11px;
        }

        .status-lunas {
            background-color: #d1fae5;
            color: #047857;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }

        .status-sebagian {
            background-color: #fef3c7;
            color: #92400e;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }

        .status-belum {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }

        .status-kelebihan {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }

        tfoot td {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .info-box {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }

        .info-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .info-content {
            margin-bottom: 3px;
        }

        .text-muted {
            color: #6b7280;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN PENJUALAN</h1>
        <p>Periode:
            {{ \Carbon\Carbon::parse($filters['tanggal_awal'] ?? now()->startOfMonth()->format('Y-m-d'))->format('d/m/Y') }}
            -
            {{ \Carbon\Carbon::parse($filters['tanggal_akhir'] ?? now()->format('Y-m-d'))->format('d/m/Y') }}</p>
    </div>

    <div class="info-box">
        <div class="info-title">Filter yang digunakan:</div>
        <div class="info-content">
            <strong>Tanggal:</strong>
            {{ \Carbon\Carbon::parse($filters['tanggal_awal'] ?? now()->startOfMonth()->format('Y-m-d'))->format('d/m/Y') }}
            -
            {{ \Carbon\Carbon::parse($filters['tanggal_akhir'] ?? now()->format('Y-m-d'))->format('d/m/Y') }}
        </div>
        @if (!empty($filters['customer_id']))
            <div class="info-content">
                <strong>Customer:</strong>
                {{ \App\Models\Customer::find($filters['customer_id'])->nama ?? 'Tidak diketahui' }}
                ({{ \App\Models\Customer::find($filters['customer_id'])->kode ?? '-' }})
            </div>
        @endif
        @if (!empty($filters['status_pembayaran']))
            <div class="info-content">
                <strong>Status Pembayaran:</strong>
                @if ($filters['status_pembayaran'] == 'lunas')
                    LUNAS
                @elseif($filters['status_pembayaran'] == 'sebagian')
                    DIBAYAR SEBAGIAN
                @elseif($filters['status_pembayaran'] == 'belum_bayar')
                    BELUM DIBAYAR
                @elseif($filters['status_pembayaran'] == 'kelebihan_bayar')
                    KELEBIHAN BAYAR
                @endif
            </div>
        @endif
        @if (!empty($filters['search']))
            <div class="info-content">
                <strong>Pencarian:</strong> "{{ $filters['search'] }}"
            </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="12%">Nomor Faktur</th>
                <th width="10%">Tanggal</th>
                <th width="15%">Customer</th>
                <th width="8%">Status</th>
                <th width="10%">Total</th>
                <th width="10%">Total Bayar</th>
                <th width="10%">Retur</th>
                <th width="10%">Sisa</th>
                <th width="12%">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @if (count($dataPenjualan) > 0)
                @foreach ($dataPenjualan as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nomor_faktur }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td>
                            {{ $item->customer_nama }}
                            <div class="text-muted">{{ $item->customer_kode }}</div>
                        </td>
                        <td>
                            @if ($item->status == 'lunas')
                                <span class="status-lunas">LUNAS</span>
                            @elseif($item->status == 'sebagian')
                                <span class="status-sebagian">SEBAGIAN</span>
                            @elseif($item->status == 'belum_bayar')
                                <span class="status-belum">BELUM BAYAR</span>
                            @elseif($item->status == 'kelebihan_bayar')
                                <span class="status-kelebihan">KELEBIHAN</span>
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($item->total, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item->total_retur ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">
                            {{ number_format($item->total - $item->total_bayar - ($item->total_retur ?? 0), 0, ',', '.') }}
                        </td>
                        <td>{{ $item->nama_petugas }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" style="text-align: center;">Tidak ada data penjualan</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">TOTAL:</td>
                <td class="text-right">{{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalDibayar, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($dataPenjualan->sum('total_retur') ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">
                    {{ number_format($sisaPembayaran - ($dataPenjualan->sum('total_retur') ?? 0), 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 30px; font-size: 10px; color: #6b7280; text-align: center;">
        Laporan dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>

</html>
