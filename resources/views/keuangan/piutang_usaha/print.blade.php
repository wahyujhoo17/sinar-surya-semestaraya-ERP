<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Piutang Usaha - {{ $so->nomor }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .header h1 {
            font-size: 22px;
            color: #222;
            margin: 0 0 5px 0;
        }

        .header p {
            font-size: 14px;
            color: #555;
            margin: 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f7f7f7;
            font-weight: bold;
            color: #444;
        }

        .info-table td:first-child {
            width: 35%;
            font-weight: 500;
            color: #555;
        }

        .item-table th {
            text-align: center;
        }

        .item-table td {
            text-align: right;
        }

        .item-table td:nth-child(2) {
            /* Product Name */
            text-align: left;
        }

        .item-table td:nth-child(3) {
            /* Quantity */
            text-align: center;
        }

        .totals-table td:first-child {
            font-weight: bold;
            text-align: right;
        }

        .totals-table td:last-child {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-belum_bayar {
            background-color: #ffebee;
            /* Light Red */
            color: #c62828;
            /* Dark Red */
        }

        .status-sebagian {
            background-color: #fff3e0;
            /* Light Orange */
            color: #ef6c00;
            /* Dark Orange */
        }

        .status-lunas {
            background-color: #e8f5e9;
            /* Light Green */
            color: #2e7d32;
            /* Dark Green */
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Detail Piutang Usaha</h1>
            <p>Sales Order: {{ $so->nomor }}</p>
            <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
        </div>

        <div class="section-title">Informasi Sales Order & Keuangan</div>
        <table class="info-table">
            <tr>
                <td>Nomor SO</td>
                <td>{{ $so->nomor }}</td>
            </tr>
            <tr>
                <td>Tanggal SO</td>
                <td>{{ date('d F Y', strtotime($so->tanggal)) }}</td>
            </tr>
            <tr>
                <td>Customer</td>
                <td>{{ $so->customer->nama }}</td>
            </tr>
            <tr>
                <td>Nomor Invoice</td>
                <td>{{ $so->invoice ? $so->invoice->nomor : '-' }}</td>
            </tr>
            <tr>
                <td>Tanggal Invoice</td>
                <td>{{ $so->invoice ? date('d F Y', strtotime($so->invoice->tanggal)) : '-' }}</td>
            </tr>
            <tr>
                <td>Jatuh Tempo</td>
                <td>{{ $so->invoice && $so->invoice->tanggal_jatuh_tempo ? date('d F Y', strtotime($so->invoice->tanggal_jatuh_tempo)) : '-' }}
                </td>
            </tr>
            <tr>
                <td>Dibuat Oleh</td>
                <td>{{ $so->user->name }}</td>
            </tr>
            <tr>
                <td>Total SO</td>
                <td class="text-right">Rp {{ number_format($so->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Pembayaran</td>
                <td class="text-right" style="color: green;">Rp {{ number_format($totalPayments, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Retur</td>
                <td class="text-right" style="color: orange;">Rp {{ number_format($totalReturValue, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td><strong>Sisa Piutang</strong></td>
                <td class="text-right" style="font-weight: bold; color: {{ $sisaPiutang > 0 ? 'red' : 'green' }};">
                    <strong>Rp {{ number_format($sisaPiutang, 0, ',', '.') }}</strong>
                </td>
            </tr>
            <tr>
                <td>Status Pembayaran</td>
                <td><span
                        class="status-badge status-{{ $so->status_pembayaran }}">{{ ucwords(str_replace('_', ' ', $so->status_pembayaran)) }}</span>
                </td>
            </tr>
        </table>

        <div class="section-title">Detail Produk Sales Order</div>
        <table class="item-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($so->details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $detail->produk->nama }}</td>
                        <td class="text-center">{{ $detail->quantity }} {{ $detail->produk->satuan->nama }}</td>
                        <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="totals-table">
                    <td colspan="4">Total SO</td>
                    <td>Rp {{ number_format($so->total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        @if ($payments->count() > 0)
            <div class="section-title">Riwayat Pembayaran</div>
            <table class="item-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nomor Pembayaran</th>
                        <th>Metode</th>
                        <th class="text-right">Jumlah</th>
                        <th>Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        <tr>
                            <td class="text-center">{{ date('d/m/Y', strtotime($payment->tanggal)) }}</td>
                            <td class="text-center">{{ $payment->nomor }}</td>
                            <td class="text-center">{{ $payment->akunAkuntansi->nama ?? 'N/A' }}</td>
                            <td>Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $payment->user->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="totals-table">
                        <td colspan="3">Total Pembayaran</td>
                        <td style="color: green;">Rp {{ number_format($totalPayments, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        @endif

        @if ($returns->count() > 0)
            <div class="section-title">Riwayat Retur Penjualan</div>
            <table class="item-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nomor Retur</th>
                        <th class="text-right">Total Nilai Retur</th>
                        <th>Status</th>
                        <th>Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($returns as $return)
                        @php
                            $currentReturValue = 0;
                            $soDetails = $return->salesOrder->details;
                            foreach ($return->details as $returDetail) {
                                $matchingSoDetail = $soDetails->where('produk_id', $returDetail->produk_id)->first();
                                if ($matchingSoDetail) {
                                    $currentReturValue += $matchingSoDetail->harga * $returDetail->quantity;
                                }
                            }
                        @endphp
                        <tr>
                            <td class="text-center">{{ date('d/m/Y', strtotime($return->tanggal)) }}</td>
                            <td class="text-center">{{ $return->nomor }}</td>
                            <td>Rp {{ number_format($currentReturValue, 0, ',', '.') }}</td>
                            <td class="text-center"><span
                                    class="status-badge status-{{ $return->status === 'selesai' ? 'lunas' : 'sebagian' }}">{{ ucfirst($return->status) }}</span>
                            </td>
                            <td class="text-center">{{ $return->user->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="totals-table">
                        <td colspan="2">Total Nilai Retur</td>
                        <td style="color: orange;">Rp {{ number_format($totalReturValue, 0, ',', '.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        @endif

        <div class="footer">
            <p>Ini adalah dokumen yang dicetak secara otomatis oleh sistem ERP Sinar Surya.</p>
        </div>
    </div>
</body>

</html>
