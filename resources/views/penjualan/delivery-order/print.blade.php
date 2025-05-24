<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Order - {{ $deliveryOrder->nomor }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 10px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }

        .info-section {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .info-block {
            width: 48%;
        }

        .info-block h3 {
            margin: 0 0 5px;
            font-size: 14px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
        }

        .info-item {
            margin-bottom: 8px;
        }

        .info-item span {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th,
        table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-block {
            width: 30%;
            text-align: center;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            padding-top: 5px;
        }

        @media print {
            body {
                padding: 0;
                font-size: 12px;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>DELIVERY ORDER</h1>
            <p>{{ $deliveryOrder->nomor }}</p>
        </div>

        <div class="info-section">
            <div class="info-block">
                <h3>Pengirim</h3>
                <div class="info-item">
                    <span>Gudang:</span> {{ $deliveryOrder->gudang->nama }}
                </div>
                <div class="info-item">
                    <span>Alamat:</span> {{ $deliveryOrder->gudang->alamat ?? '-' }}
                </div>
                <div class="info-item">
                    <span>Tanggal:</span> {{ date('d F Y', strtotime($deliveryOrder->tanggal)) }}
                </div>
                <div class="info-item">
                    <span>Sales Order:</span> {{ $deliveryOrder->salesOrder->nomor ?? '-' }}
                </div>
            </div>
            <div class="info-block">
                <h3>Penerima</h3>
                <div class="info-item">
                    <span>Customer:</span> {{ $deliveryOrder->customer->company ?? $deliveryOrder->customer->nama }}
                </div>
                <div class="info-item">
                    <span>Alamat Pengiriman:</span> {{ $deliveryOrder->alamat_pengiriman }}
                </div>
                @if ($deliveryOrder->status == 'diterima')
                    <div class="info-item">
                        <span>Diterima oleh:</span> {{ $deliveryOrder->nama_penerima }}
                    </div>
                    <div class="info-item">
                        <span>Tanggal diterima:</span> {{ date('d F Y', strtotime($deliveryOrder->tanggal_diterima)) }}
                    </div>
                @endif
            </div>
        </div>

        <h3>Daftar Produk</h3>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Kode</th>
                    <th width="40%">Produk</th>
                    <th width="15%">Kuantitas</th>
                    <th width="10%">Satuan</th>
                    <th width="15%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($deliveryOrder->deliveryOrderDetail as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->produk->kode ?? '-' }}</td>
                        <td>{{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}</td>
                        <td>{{ number_format($detail->quantity, 2, ',', '.') }}</td>
                        <td>{{ $detail->produk->satuan->nama ?? '-' }}</td>
                        <td>{{ $detail->keterangan ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($deliveryOrder->catatan)
            <div style="margin-top: 20px;">
                <h3>Catatan</h3>
                <p>{{ $deliveryOrder->catatan }}</p>
            </div>
        @endif

        <div class="footer">
            <div class="signature-block">
                <p>Disiapkan oleh:</p>
                <div class="signature-line">
                    {{ $deliveryOrder->user->name ?? 'Admin' }}
                </div>
            </div>
            <div class="signature-block">
                <p>Pengirim:</p>
                <div class="signature-line">
                    __________________
                </div>
            </div>
            <div class="signature-block">
                <p>Penerima:</p>
                <div class="signature-line">
                    {{ $deliveryOrder->nama_penerima ?? '________________' }}
                </div>
            </div>
        </div>

        <div class="no-print" style="margin-top: 30px; text-align: center;">
            <button onclick="window.print()"
                style="padding: 10px 20px; cursor: pointer; background-color: #4CAF50; color: white; border: none; border-radius: 4px;">
                Cetak
            </button>
            <button onclick="window.close()"
                style="padding: 10px 20px; margin-left: 10px; cursor: pointer; background-color: #f44336; color: white; border: none; border-radius: 4px;">
                Tutup
            </button>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                // window.print();
            }, 500);
        };
    </script>
</body>

</html>
