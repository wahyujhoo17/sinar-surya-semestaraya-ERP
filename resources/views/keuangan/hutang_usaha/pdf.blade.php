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
        }

        .data-table td {
            border: 1px solid #ddd;
            padding: 5px;
            font-size: 10px;
        }

        .data-table .text-right {
            text-align: right;
        }

        .data-table tfoot th,
        .data-table tfoot td {
            border-top: 2px solid #000;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN HUTANG USAHA</h1>
        <div class="company-info">
            PT. SINAR SURYA | {{ date('d/m/Y') }}
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
                <th width="5%">No</th>
                <th width="15%">Nomor PO</th>
                <th width="10%">Tanggal</th>
                <th width="25%">Supplier</th>
                <th width="10%" class="text-right">Total PO</th>
                <th width="10%" class="text-right">Total Pembayaran</th>
                <th width="10%" class="text-right">Total Retur</th>
                <th width="10%" class="text-right">Sisa Hutang</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchaseOrders as $index => $po)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $po->nomor }}</td>
                    <td>{{ date('d/m/Y', strtotime($po->tanggal)) }}</td>
                    <td>{{ $po->supplier->nama }}</td>
                    <td class="text-right">{{ number_format($po->total, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($po->total_pembayaran, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($po->total_retur, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($po->sisa_hutang, 0, ',', '.') }}</td>
                    <td>
                        @if ($po->status_pembayaran == 'belum_bayar')
                            Belum Bayar
                        @elseif($po->status_pembayaran == 'sebagian')
                            Sebagian
                        @else
                            Lunas
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data hutang usaha</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7" class="text-right">Total Sisa Hutang:</th>
                <th class="text-right">{{ number_format($totalSisaHutang, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i:s') }}
    </div>
</body>

</html>
