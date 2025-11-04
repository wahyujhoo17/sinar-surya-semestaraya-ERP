<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Piutang Usaha</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            font-size: 9px;
            color: #333;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 3px 0;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 4px 3px;
            text-align: left;
            word-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 8px;
        }

        /* Column width controls */
        th:nth-child(1),
        td:nth-child(1) {
            width: 3%;
        }

        /* No */
        th:nth-child(2),
        td:nth-child(2) {
            width: 10%;
        }

        /* Nomor Invoice */
        th:nth-child(3),
        td:nth-child(3) {
            width: 8%;
        }

        /* Tanggal Invoice */
        th:nth-child(4),
        td:nth-child(4) {
            width: 10%;
        }

        /* Nomor SO */
        th:nth-child(5),
        td:nth-child(5) {
            width: 15%;
        }

        /* Customer */
        th:nth-child(6),
        td:nth-child(6) {
            width: 11%;
        }

        /* Total Invoice */
        th:nth-child(7),
        td:nth-child(7) {
            width: 11%;
        }

        /* Total Pembayaran */
        th:nth-child(8),
        td:nth-child(8) {
            width: 11%;
        }

        /* Sisa Piutang */
        th:nth-child(9),
        td:nth-child(9) {
            width: 8%;
        }

        /* Jatuh Tempo */
        th:nth-child(10),
        td:nth-child(10) {
            width: 8%;
        }

        /* Status */

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            text-align: right;
            margin-top: 20px;
            font-size: 9px;
        }

        .summary {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .summary p {
            margin: 3px 0;
            font-size: 10px;
        }

        .summary strong {
            font-size: 11px;
        }

        .sorting-info {
            margin: 8px 0;
            font-style: italic;
            font-size: 9px;
            color: #666;
        }

        tfoot th {
            font-size: 8px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Laporan Piutang Usaha</h1>
            @if (request('customer_id'))
                @php
                    $customer = \App\Models\Customer::find(request('customer_id'));
                @endphp
                <p>Customer: {{ $customer ? $customer->nama : 'Semua Customer' }}</p>
            @else
                <p>Customer: Semua Customer</p>
            @endif
            @if (request('start_date') && request('end_date'))
                <p>Periode: {{ date('d/m/Y', strtotime(request('start_date'))) }} -
                    {{ date('d/m/Y', strtotime(request('end_date'))) }}</p>
            @elseif(request('start_date'))
                <p>Mulai Tanggal: {{ date('d/m/Y', strtotime(request('start_date'))) }}</p>
            @elseif(request('end_date'))
                <p>Sampai Tanggal: {{ date('d/m/Y', strtotime(request('end_date'))) }}</p>
            @endif
            <p>Dicetak pada: {{ $tanggalCetak }}</p>
        </div>

        <div class="sorting-info">
            Diurutkan berdasarkan: {{ ucfirst(str_replace('_', ' ', $sortColumn)) }}
            ({{ $sortDirection === 'asc' ? 'Naik' : 'Turun' }})
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Invoice</th>
                    <th>Tanggal Invoice</th>
                    <th>Nomor SO</th>
                    <th>Customer</th>
                    <th class="text-right">Total Invoice</th>
                    <th class="text-right">Total Pembayaran</th>
                    <th class="text-right">Sisa Piutang</th>
                    <th class="text-center">Jatuh Tempo</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($invoices as $index => $invoice)
                    @php
                        $totalPayments = $invoice->pembayaranPiutang->sum('jumlah');
                        $sisaPiutang = $invoice->sisa_piutang; // Use accessor that includes nota kredit

                        // Status display logic for the invoice
                        $isOverdue =
                            $invoice->jatuh_tempo &&
                            \Carbon\Carbon::parse($invoice->jatuh_tempo)
                                ->startOfDay()
                                ->lt(\Carbon\Carbon::today()->startOfDay());

                        if ($sisaPiutang <= 0 && $invoice->status === 'lunas') {
                            $statusDisplay = 'Lunas';
                        } elseif ($isOverdue && $invoice->status !== 'lunas') {
                            $statusDisplay = 'Jatuh Tempo';
                        } elseif ($invoice->status === 'sebagian') {
                            $statusDisplay = 'Lunas Sebagian';
                        } elseif ($invoice->status === 'belum_bayar') {
                            $statusDisplay = 'Belum Bayar';
                        } elseif ($sisaPiutang <= 0) {
                            $statusDisplay = 'Lunas';
                        } else {
                            $statusDisplay = ucfirst(str_replace('_', ' ', $invoice->status ?? 'Tidak Diketahui'));
                        }
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $invoice->nomor }}</td>
                        <td>{{ date('d/m/Y', strtotime($invoice->tanggal)) }}</td>
                        <td>{{ $invoice->salesOrder ? $invoice->salesOrder->nomor : '-' }}</td>
                        <td>{{ $invoice->customer->company ?? ($invoice->customer->nama ?? 'N/A') }}</td>
                        </td>
                        <td class="text-right">{{ number_format($invoice->total, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($totalPayments, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($sisaPiutang, 0, ',', '.') }}</td>
                        <td class="text-center">
                            {{ $invoice->jatuh_tempo ? date('d/m/Y', strtotime($invoice->jatuh_tempo)) : '-' }}
                        </td>
                        <td class="text-center">{{ $statusDisplay }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data piutang usaha.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7" class="text-right">Total Sisa Piutang:</th>
                    <th class="text-right">{{ number_format($totalPiutangPdf, 0, ',', '.') }}</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>

        <div class="summary">
            <p>Total Invoice: <strong>{{ $invoices->count() }}</strong></p>
            <p>Total Keseluruhan Sisa Piutang: <strong style="color: {{ $totalPiutangPdf > 0 ? 'red' : 'green' }};">Rp
                    {{ number_format($totalPiutangPdf, 0, ',', '.') }}</strong></p>
        </div>

    </div>
</body>

</html>
