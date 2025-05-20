<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Hutang Usaha - {{ $po->nomor }}</title>
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

        .info-section {
            margin-bottom: 20px;
        }

        .info-section table {
            width: 100%;
        }

        .info-section th {
            text-align: left;
            width: 30%;
            vertical-align: top;
            padding: 2px 5px 2px 0;
        }

        .info-section td {
            padding: 2px 0;
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
            font-size: 11px;
        }

        .data-table .text-right {
            text-align: right;
        }

        .summary-table {
            width: 300px;
            float: right;
            margin-bottom: 20px;
        }

        .summary-table th {
            text-align: right;
            padding: 3px 10px 3px 0;
        }

        .summary-table td {
            text-align: right;
            padding: 3px 0;
        }

        .page-break {
            page-break-after: always;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>DETAIL HUTANG USAHA</h1>
        <div class="company-info">
            {{ $po->supplier->nama }} | Nomor PO: {{ $po->nomor }} | Tanggal:
            {{ date('d/m/Y', strtotime($po->tanggal)) }}
        </div>
    </div>

    <div class="info-section" style="display: flex; justify-content: space-between;">
        <div style="width: 48%;">
            <table>
                <tr>
                    <th>Nomor PO</th>
                    <td>: {{ $po->nomor }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>: {{ date('d/m/Y', strtotime($po->tanggal)) }}</td>
                </tr>
                <tr>
                    <th>Supplier</th>
                    <td>: {{ $po->supplier->nama }}</td>
                </tr>
                <tr>
                    <th>Status Pembayaran</th>
                    <td>:
                        @if ($po->status_pembayaran == 'belum_bayar')
                            Belum Bayar
                        @elseif($po->status_pembayaran == 'sebagian')
                            Sebagian
                        @elseif($po->status_pembayaran == 'kelebihan_bayar')
                            Kelebihan Bayar
                        @else
                            Lunas
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div style="width: 48%;">
            <table>
                <tr>
                    <th>Total PO</th>
                    <td>: Rp {{ number_format($po->total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Total Pembayaran</th>
                    <td>: Rp {{ number_format($totalPayments, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Total Retur</th>
                    <td>: Rp {{ number_format($totalReturValue, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Sisa Hutang</th>
                    <td class="font-bold">: Rp {{ number_format($sisaHutang, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <h4 style="margin-bottom: 5px;">Detail Barang</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Kode</th>
                <th width="30%">Nama Barang</th>
                <th width="10%">Qty</th>
                <th width="10%">Satuan</th>
                <th width="15%">Harga</th>
                <th width="10%">Diskon</th>
                <th width="15%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($po->details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->produk->kode ?? '-' }}</td>
                    <td>{{ $detail->produk->nama ?? '-' }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ $detail->satuan->nama ?? '-' }}</td>
                    <td class="text-right">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detail->diskon, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="clearfix">
        <table class="summary-table">
            <tr>
                <th>Subtotal:</th>
                <td>Rp {{ number_format($po->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Diskon:</th>
                <td>Rp {{ number_format($po->diskon_nominal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>PPN:</th>
                <td>Rp {{ number_format($po->ppn, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Ongkos Kirim:</th>
                <td>{{ $po->ongkos_kirim > 0 ? 'Rp ' . number_format($po->ongkos_kirim, 0, ',', '.') : '-' }}</td>
            </tr>
            <tr>
                <th class="font-bold">Total:</th>
                <td class="font-bold">Rp {{ number_format($po->total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div style="clear: both;"></div>

    <div style="display: flex; justify-content: space-between;">
        <div style="width: 48%;">
            <h4 style="margin-bottom: 5px;">Riwayat Pembayaran</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Tanggal</th>
                        <th width="30%">Nomor</th>
                        <th width="20%">Metode</th>
                        <th width="25%">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $index => $payment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ date('d/m/Y', strtotime($payment->tanggal)) }}</td>
                            <td>{{ $payment->nomor }}</td>
                            <td>{{ $payment->metode_pembayaran }}</td>
                            <td class="text-right">{{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">Belum ada pembayaran</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" style="text-align: right;">Total:</th>
                        <th class="text-right">{{ number_format($totalPayments, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div style="width: 48%;">
            <h4 style="margin-bottom: 5px;">Riwayat Retur</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Tanggal</th>
                        <th width="30%">Nomor</th>
                        <th width="40%">Nilai Retur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $index => $return)
                        @php
                            $nilaiRetur = 0;
                            $poDetails = $return->purchaseOrder->details;

                            foreach ($return->details as $returDetail) {
                                // Find matching PO detail for this product
                                $matchingPoDetail = $poDetails->where('produk_id', $returDetail->produk_id)->first();

                                if ($matchingPoDetail) {
                                    $nilaiRetur += $matchingPoDetail->harga * $returDetail->quantity;
                                }
                            }
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ date('d/m/Y', strtotime($return->tanggal)) }}</td>
                            <td>{{ $return->nomor }}</td>
                            <td class="text-right">{{ number_format($nilaiRetur, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">Belum ada retur</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" style="text-align: right;">Total:</th>
                        <th class="text-right">{{ number_format($totalReturValue, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i:s') }}
    </div>
</body>

</html>
