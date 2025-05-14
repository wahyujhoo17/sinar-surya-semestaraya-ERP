<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hutang Usaha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
            padding: 0;
        }

        .company-info {
            font-size: 11px;
            margin-top: 5px;
        }

        .filter-info {
            font-size: 11px;
            margin-top: 5px;
            margin-bottom: 10px;
            padding: 5px;
            background-color: #f8f8f8;
            border: 1px solid #eee;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
        }

        .data-table td {
            border: 1px solid #ddd;
            padding: 5px;
            font-size: 10px;
        }

        .data-table .text-right {
            text-align: right;
        }

        .data-table .text-center {
            text-align: center;
        }

        .data-table tfoot th,
        .data-table tfoot td {
            border-top: 2px solid #000;
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 9px;
            color: #fff;
        }

        .status-lunas {
            background-color: #28a745;
        }

        .status-sebagian {
            background-color: #ffc107;
            color: #000;
        }

        .status-belum {
            background-color: #dc3545;
        }

        .summary-box {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
        }

        .summary-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 12px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .summary-label {
            font-size: 10px;
        }

        .summary-value {
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN HUTANG USAHA</h1>
        <div class="company-info">
            PT. SINAR SURYA SEMESTARAYA | {{ date('d/m/Y') }}
        </div>
    </div>

    <div class="filter-info">
        <strong>Filter:</strong>
        @if ($supplier)
            Supplier: {{ $supplier->nama }} |
        @endif
        @if ($startDate && $endDate)
            Periode: {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}
        @elseif($startDate)
            Dari Tanggal: {{ date('d/m/Y', strtotime($startDate)) }}
        @elseif($endDate)
            Sampai Tanggal: {{ date('d/m/Y', strtotime($endDate)) }}
        @else
            Semua Periode
        @endif
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Nomor PO</th>
                <th width="10%">Tanggal</th>
                <th width="25%">Supplier</th>
                <th width="10%" class="text-right">Total PO</th>
                <th width="10%" class="text-right">Total Pembayaran</th>
                <th width="10%" class="text-right">Total Retur</th>
                <th width="10%" class="text-right">Sisa Hutang</th>
                <th width="10%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPO = 0;
                $totalPembayaran = 0;
                $totalRetur = 0;
            @endphp

            @forelse($purchaseOrders as $index => $po)
                @if ($po->status != 'dibatalkan')
                    @php
                        $totalPO += $po->total;
                        $totalPembayaran += $po->total_pembayaran;
                        $totalRetur += $po->total_retur;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $po->nomor }}</td>
                        <td>{{ date('d/m/Y', strtotime($po->tanggal)) }}</td>
                        <td>{{ $po->supplier->nama }}</td>
                        <td class="text-right">{{ number_format($po->total, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($po->total_pembayaran, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($po->total_retur, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($po->sisa_hutang, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if ($po->status_pembayaran == 'belum_bayar')
                                <span class="status-badge status-belum">Belum Bayar</span>
                            @elseif($po->status_pembayaran == 'sebagian')
                                <span class="status-badge status-sebagian">Sebagian</span>
                            @else
                                <span class="status-badge status-lunas">Lunas</span>
                            @endif
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data hutang usaha</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total:</th>
                <th class="text-right">{{ number_format($totalPO, 0, ',', '.') }}</th>
                <th class="text-right">{{ number_format($totalPembayaran, 0, ',', '.') }}</th>
                <th class="text-right">{{ number_format($totalRetur, 0, ',', '.') }}</th>
                <th class="text-right">{{ number_format($totalSisaHutang, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div class="summary-box">
        <div class="summary-title">Ringkasan Hutang</div>
        <div class="summary-item">
            <span class="summary-label">Total Purchase Order:</span>
            <span class="summary-value">Rp {{ number_format($totalPO, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Pembayaran:</span>
            <span class="summary-value">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Retur:</span>
            <span class="summary-value">Rp {{ number_format($totalRetur, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Sisa Hutang:</span>
            <span class="summary-value">Rp {{ number_format($totalSisaHutang, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i:s') }} | PT. SINAR SURYA SEMESTARAYA
    </div>
</body>

</html>
