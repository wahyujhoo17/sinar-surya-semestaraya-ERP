<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembelian - {{ $pembelian->nomor_faktur }}</title>
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

        .info-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }

        .info-box h3 {
            margin-top: 0;
            margin-bottom: 5px;
            font-size: 14px;
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

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .col {
            flex: 0 0 50%;
            max-width: 50%;
            padding-right: 15px;
            padding-left: 15px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>DETAIL PEMBELIAN #{{ $pembelian->nomor_faktur }}</h1>
        <p>Tanggal Pembelian: {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d M Y') }}</p>
    </div>

    <div class="row">
        <div class="col">
            <div class="info-box">
                <h3>Informasi Supplier</h3>
                <p><strong>Kode:</strong> {{ $pembelian->supplier->kode }}</p>
                <p><strong>Nama:</strong> {{ $pembelian->supplier->nama }}</p>
                <p><strong>Alamat:</strong> {{ $pembelian->supplier->alamat }}</p>
                <p><strong>Telepon:</strong> {{ $pembelian->supplier->telepon }}</p>
            </div>
        </div>
        <div class="col">
            <div class="info-box">
                <h3>Informasi Pembayaran</h3>
                <p><strong>Status:</strong>
                    @if ($pembelian->status == 'lunas')
                        <span class="status status-lunas">Lunas</span>
                    @elseif($pembelian->status == 'sebagian')
                        <span class="status status-sebagian">Dibayar Sebagian</span>
                    @else
                        <span class="status status-belum">Belum Dibayar</span>
                    @endif
                </p>
                <p><strong>Total Pembelian:</strong> Rp {{ number_format($pembelian->total, 0, ',', '.') }}</p>
                <p><strong>Total Dibayar:</strong> Rp {{ number_format($pembelian->total_bayar, 0, ',', '.') }}</p>
                <p><strong>Sisa Pembayaran:</strong> Rp
                    {{ number_format($pembelian->total - $pembelian->total_bayar, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <h3>Detail Item Pembelian</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Kode</th>
                <th width="30%">Nama Produk</th>
                <th width="10%">Jumlah</th>
                <th width="10%">Satuan</th>
                <th width="15%">Harga</th>
                <th width="5%">Diskon</th>
                <th width="15%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembelian->details as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->produk->kode }}</td>
                    <td>{{ $item->produk->nama }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->produk->satuan->nama }}</td>
                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>{{ $item->diskon_persen ?? 0 }}%</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data item pembelian</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" style="text-align: right;"><strong>Total:</strong></td>
                <td><strong>Rp {{ number_format($pembelian->total, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="info-box">
        <h3>Keterangan</h3>
        <p>{{ $pembelian->keterangan ?? 'Tidak ada keterangan' }}</p>
    </div>

    <div class="info-box">
        <h3>Informasi Tambahan</h3>
        <p><strong>Dibuat oleh:</strong> {{ $pembelian->user->name ?? 'N/A' }}</p>
        <p><strong>Tanggal dibuat:</strong> {{ \Carbon\Carbon::parse($pembelian->created_at)->format('d M Y H:i') }}
        </p>
        <p><strong>Terakhir diupdate:</strong> {{ \Carbon\Carbon::parse($pembelian->updated_at)->format('d M Y H:i') }}
        </p>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak dari sistem ERP Sinar Surya pada {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>

</html>
