<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Piutang Usaha</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            text-align: right;
            margin-top: 30px;
            font-size: 12px;
        }

        .summary {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .summary p {
            margin: 5px 0;
            font-size: 14px;
        }

        .summary strong {
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Laporan Piutang Usaha</h1>
            @if ($customer)
                <p>Customer: {{ $customer->nama }}</p>
            @endif
            @if ($startDate && $endDate)
                <p>Periode: {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}</p>
            @elseif($startDate)
                <p>Mulai Tanggal: {{ date('d/m/Y', strtotime($startDate)) }}</p>
            @elseif($endDate)
                <p>Sampai Tanggal: {{ date('d/m/Y', strtotime($endDate)) }}</p>
            @endif
            <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor SO</th>
                    <th>Nomor Invoice</th>
                    <th>Tanggal SO</th>
                    <th>Customer</th>
                    <th class="text-right">Total SO</th>
                    <th class="text-right">Total Pembayaran</th>
                    <th class="text-right">Total Retur</th>
                    <th class="text-right">Sisa Piutang</th>
                    <th class="text-center">Jatuh Tempo</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($salesOrders as $index => $so)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $so->nomor }}</td>
                        <td>{{ $so->invoice ? $so->invoice->nomor : '-' }}</td>
                        <td>{{ date('d/m/Y', strtotime($so->tanggal)) }}</td>
                        <td>{{ $so->customer->nama }}</td>
                        <td class="text-right">{{ number_format($so->total, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($so->total_pembayaran, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($so->total_retur, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($so->sisa_piutang, 0, ',', '.') }}</td>
                        <td class="text-center">
                            {{ $so->invoice && $so->invoice->tanggal_jatuh_tempo ? date('d/m/Y', strtotime($so->invoice->tanggal_jatuh_tempo)) : '-' }}
                        </td>
                        <td class="text-center">{{ ucwords(str_replace('_', ' ', $so->status_pembayaran)) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">Tidak ada data piutang usaha.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="8" class="text-right">Total Sisa Piutang:</th>
                    <th class="text-right">{{ number_format($totalSisaPiutang, 0, ',', '.') }}</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>

        <div class="summary">
            <p>Total Sales Order: <strong>{{ $salesOrders->count() }}</strong></p>
            <p>Total Keseluruhan Sisa Piutang: <strong style="color: {{ $totalSisaPiutang > 0 ? 'red' : 'green' }};">Rp
                    {{ number_format($totalSisaPiutang, 0, ',', '.') }}</strong></p>
        </div>

    </div>
</body>

</html>
