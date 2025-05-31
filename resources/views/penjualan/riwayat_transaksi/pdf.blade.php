<!DOCTYPE html>
<html>

<head>
    <title>Riwayat Transaksi Penjualan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0;
        }

        .header p {
            font-size: 13px;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th {
            background-color: #f2f2f2;
            padding: 8px;
            font-weight: bold;
            text-align: left;
            border: 1px solid #ddd;
        }

        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .footer {
            font-size: 10px;
            text-align: center;
            margin-top: 30px;
            color: #666;
        }

        .info-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Riwayat Transaksi Penjualan</h1>
        <p>{{ config('app.name', 'ERP System') }}</p>
        <p>Periode: {{ $periode }}</p>
    </div>

    <div class="info-box">
        <p><strong>Filter yang digunakan:</strong></p>
        <p>
            @if ($search)
                <strong>Pencarian:</strong> {{ $search }} <br>
            @endif

            @if ($jenis)
                <strong>Jenis Transaksi:</strong>
                @if ($jenis == 'quotation')
                    Quotation
                @elseif($jenis == 'sales_order')
                    Sales Order
                @elseif($jenis == 'delivery_order')
                    Delivery Order
                @elseif($jenis == 'invoice')
                    Invoice
                @elseif($jenis == 'retur')
                    Retur Penjualan
                @elseif($jenis == 'nota_kredit')
                    Nota Kredit
                @endif
                <br>
            @endif

            @if ($status)
                <strong>Status:</strong>
                @if ($status == 'draft')
                    Draft
                @elseif($status == 'diproses')
                    Diproses
                @elseif($status == 'selesai')
                    Selesai
                @elseif($status == 'dibatalkan')
                    Dibatalkan
                @elseif($status == 'belum_bayar')
                    Belum Bayar
                @elseif($status == 'sebagian')
                    Bayar Sebagian
                @elseif($status == 'lunas')
                    Lunas
                @endif
            @endif
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>No. Dokumen</th>
                <th>Jenis Transaksi</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($transactions as $transaksi)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaksi['tanggal'])->format('d/m/Y') }}</td>
                    <td>{{ $transaksi['nomor'] }}</td>
                    <td>
                        @if ($transaksi['jenis'] == 'quotation')
                            Quotation
                        @elseif($transaksi['jenis'] == 'sales_order')
                            Sales Order
                        @elseif($transaksi['jenis'] == 'delivery_order')
                            Delivery Order
                        @elseif($transaksi['jenis'] == 'invoice')
                            Invoice
                        @elseif($transaksi['jenis'] == 'retur')
                            Retur Penjualan
                        @elseif($transaksi['jenis'] == 'nota_kredit')
                            Nota Kredit
                        @endif
                    </td>
                    <td>{{ $transaksi['customer_nama'] }}</td>
                    <td>{{ number_format($transaksi['total'], 0, ',', '.') }}</td>
                    <td>
                        @if ($transaksi['status'] == 'draft')
                            Draft
                        @elseif($transaksi['status'] == 'diproses')
                            Diproses
                        @elseif($transaksi['status'] == 'selesai')
                            Selesai
                        @elseif($transaksi['status'] == 'dibatalkan')
                            Dibatalkan
                        @elseif($transaksi['status'] == 'belum_bayar')
                            Belum Bayar
                        @elseif($transaksi['status'] == 'sebagian')
                            Bayar Sebagian
                        @elseif($transaksi['status'] == 'lunas')
                            Lunas
                        @endif
                    </td>
                    <td>{{ $transaksi['user_nama'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>
