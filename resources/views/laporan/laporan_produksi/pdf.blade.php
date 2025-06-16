<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Produksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ddd;
        }

        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 12px;
        }

        .meta-info {
            margin-bottom: 20px;
        }

        .meta-info table {
            width: 100%;
        }

        .meta-info td {
            padding: 3px 0;
        }

        .meta-info .label {
            font-weight: bold;
            width: 150px;
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
            text-align: left;
        }

        table.data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .total-row {
            font-weight: bold;
            background-color: #f2f2f2 !important;
        }

        .status {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-selesai {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-proses {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-batal {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .status-menunggu {
            background-color: #fef3c7;
            color: #92400e;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .page-break {
            page-break-after: always;
        }

        .summary {
            margin-bottom: 20px;
        }

        .summary-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 5px;
        }

        .summary-table .label {
            font-weight: bold;
            width: 150px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN PRODUKSI</h1>
        <p>PT SINAR SURYA</p>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td class="label">Periode</td>
                <td>: {{ \Carbon\Carbon::parse($filters['tanggal_awal'])->format('d M Y') }} -
                    {{ \Carbon\Carbon::parse($filters['tanggal_akhir'])->format('d M Y') }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Cetak</td>
                <td>: {{ now()->format('d M Y H:i') }}</td>
            </tr>
            @if (!empty($filters['search']))
                <tr>
                    <td class="label">Filter Pencarian</td>
                    <td>: {{ $filters['search'] }}</td>
                </tr>
            @endif
        </table>
    </div>

    <div class="summary">
        <div class="summary-title">Ringkasan Produksi</div>
        <table class="summary-table">
            <tr>
                <td class="label">Total Produksi</td>
                <td>: {{ number_format($totalProduksi, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Total Data</td>
                <td>: {{ $dataProduksi->count() }} data</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Nomor</th>
                <th width="12%">Tanggal</th>
                <th width="25%">Produk</th>
                <th width="10%">Jumlah</th>
                <th width="13%">Status</th>
                <th width="20%">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @if ($dataProduksi->count() > 0)
                @foreach ($dataProduksi as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->nomor }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                        <td>
                            {{ $item->produk_nama }}<br>
                            <small style="color: #666;">{{ $item->produk_kode }}</small>
                        </td>
                        <td class="text-right">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td>
                            @if ($item->status == 'selesai')
                                <span class="status status-selesai">Selesai</span>
                            @elseif($item->status == 'proses')
                                <span class="status status-proses">Proses</span>
                            @elseif($item->status == 'batal')
                                <span class="status status-batal">Dibatalkan</span>
                            @elseif($item->status == 'menunggu')
                                <span class="status status-menunggu">Menunggu</span>
                            @endif
                        </td>
                        <td>{{ $item->nama_petugas ?? '-' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4">Total</td>
                <td class="text-right">{{ number_format($totalProduksi, 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dokumen ini dihasilkan oleh Sistem ERP PT Sinar Surya pada {{ now()->format('d M Y H:i:s') }}</p>
    </div>
</body>

</html>
