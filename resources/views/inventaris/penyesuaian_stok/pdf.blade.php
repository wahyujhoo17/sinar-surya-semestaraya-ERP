<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Penyesuaian Stok - {{ $penyesuaian->nomor }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            position: relative;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            opacity: 0.1;
            color: #FF0000;
            font-weight: bold;
            z-index: 0;
            text-align: center;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header p {
            font-size: 12px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table.info {
            margin-bottom: 25px;
        }

        table.info td {
            padding: 4px;
            vertical-align: top;
        }

        table.detail {
            border: 1px solid #000;
        }

        table.detail th,
        table.detail td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 10px;
            text-align: left;
        }

        table.detail th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .signatures {
            margin-top: 50px;
        }

        .signatures table {
            width: 100%;
        }

        .signatures td {
            vertical-align: top;
            text-align: center;
            width: 33.33%;
            padding: 5px;
        }

        .signatures .sign-line {
            border-bottom: 1px solid #000;
            margin-top: 50px;
            margin-bottom: 10px;
            width: 80%;
            display: inline-block;
        }

        .summary {
            margin-bottom: 30px;
        }

        .summary table {
            width: 100%;
        }

        .summary td {
            padding: 5px;
        }

        .positive {
            color: green;
        }

        .negative {
            color: red;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px dashed #ccc;
            padding-top: 5px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    @if ($penyesuaian->status == 'draft')
        <div class="watermark">
            {{ strpos($penyesuaian->nomor, 'DRAFT') !== false ? 'DRAFT PREVIEW' : 'DRAFT' }}
        </div>
    @endif
    <div class="header">
        <h1>FORM PENYESUAIAN STOK</h1>
        <p>Sinar Surya Inventory System</p>
    </div>

    <table class="info">
        <tr>
            <td width="120px"><strong>Nomor</strong></td>
            <td width="10px">:</td>
            <td>{{ $penyesuaian->nomor }}</td>
            <td width="120px"><strong>Tanggal</strong></td>
            <td width="10px">:</td>
            <td>{{ \Carbon\Carbon::parse($penyesuaian->tanggal)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td><strong>Gudang</strong></td>
            <td>:</td>
            <td>{{ $penyesuaian->gudang->nama }}</td>
            <td><strong>Dibuat Oleh</strong></td>
            <td>:</td>
            <td>{{ $penyesuaian->user->name }}</td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td>:</td>
            <td>{{ ucfirst($penyesuaian->status) }}</td>
            <td><strong>Tanggal Dibuat</strong></td>
            <td>:</td>
            <td>{{ $penyesuaian->created_at->format('d F Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Catatan</strong></td>
            <td>:</td>
            <td colspan="4">{{ $penyesuaian->catatan ?? '-' }}</td>
        </tr>
    </table>

    <div class="summary">
        <h3>Ringkasan Penyesuaian</h3>
        <table>
            <tr>
                <td width="33.33%"><strong>Total Produk: </strong>{{ $totalItems }} produk</td>
                <td width="33.33%">
                    <strong>Produk Sesuai: </strong>{{ $countNoChange }} produk
                    ({{ $totalItems > 0 ? round(($countNoChange / $totalItems) * 100) : 0 }}%)
                </td>
                <td width="33.33%"></td>
            </tr>
            <tr>
                <td>
                    <strong>Produk Tambah: </strong>{{ $countPositive }} produk
                    @if ($totalPositive > 0)
                        ({{ $totalPositive }} {{ $unitDisplay }})
                    @endif
                </td>
                <td>
                    <strong>Produk Kurang: </strong>{{ $countNegative }} produk
                    @if ($totalNegative > 0)
                        ({{ $totalNegative }} {{ $unitDisplay }})
                    @endif
                </td>
                <td></td>
            </tr>
        </table>
    </div>

    <h3>Detail Produk</h3>
    <table class="detail">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Produk</th>
                <th width="15%">Stok Tercatat</th>
                <th width="15%">Stok Fisik</th>
                <th width="15%">Selisih</th>
                <th width="15%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penyesuaian->details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $detail->produk->kode }} - {{ $detail->produk->nama }}
                    </td>
                    <td>{{ number_format($detail->stok_tercatat, 2) }} {{ $detail->satuan->nama ?? '' }}</td>
                    <td>{{ number_format($detail->stok_fisik, 2) }} {{ $detail->satuan->nama ?? '' }}</td>
                    <td class="{{ $detail->selisih > 0 ? 'positive' : ($detail->selisih < 0 ? 'negative' : '') }}">
                        {{ $detail->selisih > 0 ? '+' : '' }}{{ number_format($detail->selisih, 2) }}
                        {{ $detail->satuan->nama ?? '' }}
                    </td>
                    <td>{{ $detail->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signatures">
        <table>
            <tr>
                <td>
                    <div>Dibuat oleh,</div>
                    <div class="sign-line"></div>
                    <div>{{ $penyesuaian->user->name }}</div>
                    <div><small>{{ $penyesuaian->created_at->format('d-m-Y H:i') }}</small></div>
                </td>
                <td>
                    <div>Diperiksa oleh,</div>
                    <div class="sign-line"></div>
                    <div>_____________________</div>
                    <div><small>Kepala Gudang</small></div>
                </td>
                <td>
                    <div>Disetujui oleh,</div>
                    <div class="sign-line"></div>
                    <div>_____________________</div>
                    <div><small>Manager</small></div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak pada: {{ now()->format('d F Y H:i:s') }} | {{ setting('company_name', 'Sinar Surya') }} ERP System
    </div>
</body>

</html>
