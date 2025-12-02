<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penjualan - {{ $penjualan->nomor_faktur }}</title>
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
            position: relative;
        }

        .logo-container {
            margin-bottom: 10px;
        }

        .company-logo {
            max-width: 80px;
            max-height: 60px;
            height: auto;
        }

        .header h1 {
            font-size: 18px;
            margin: 0 0 5px;
        }

        .header p {
            font-size: 12px;
            margin: 0;
        }

        .print-info {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 10px;
            color: #777;
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
        @php
            $logoSrc = null;
            if (isset($company) && $company && $company->logo) {
                $logoPath = public_path('storage/' . $company->logo);
                if (file_exists($logoPath)) {
                    $logoData = base64_encode(file_get_contents($logoPath));
                    $logoMimeType = mime_content_type($logoPath);
                    $logoSrc = 'data:' . $logoMimeType . ';base64,' . $logoData;
                }
            }

            if (!$logoSrc) {
                $defaultLogoPath = public_path('img/SemestaPro.PNG');
                if (file_exists($defaultLogoPath)) {
                    $logoData = base64_encode(file_get_contents($defaultLogoPath));
                    $logoSrc = 'data:image/png;base64,' . $logoData;
                }
            }
        @endphp

        @if ($logoSrc)
            <div class="logo-container">
                <img src="{{ $logoSrc }}" alt="Logo" class="company-logo">
            </div>
        @endif

        <h1>{{ $company->nama ?? 'SINAR SURYA SEMESTARAYA' }}</h1>
        <p>DETAIL PENJUALAN #{{ $penjualan->nomor_faktur }}</p>
        <p>Tanggal Penjualan: {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d M Y') }}</p>
        <div class="print-info">
            Dicetak: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="info-box">
                <h3>Informasi Customer</h3>
                <p><strong>Kode:</strong> {{ $penjualan->customer->kode }}</p>
                <p><strong>Nama:</strong> {{ $penjualan->customer->nama }}</p>
                <p><strong>Alamat:</strong> {{ $penjualan->customer->alamat }}</p>
                <p><strong>Telepon:</strong> {{ $penjualan->customer->telepon }}</p>
            </div>
        </div>
        <div class="col">
            <div class="info-box">
                <h3>Informasi Pembayaran</h3>
                <p><strong>Status:</strong>
                    @if ($penjualan->status == 'lunas')
                        <span class="status status-lunas">Lunas</span>
                    @elseif($penjualan->status == 'sebagian')
                        <span class="status status-sebagian">Dibayar Sebagian</span>
                    @else
                        <span class="status status-belum">Belum Dibayar</span>
                    @endif
                </p>
                <p><strong>Total Penjualan:</strong> Rp {{ number_format($penjualan->total, 0, ',', '.') }}</p>
                <p><strong>Total Dibayar:</strong> Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</p>
                <p><strong>Sisa Pembayaran:</strong> Rp
                    {{ number_format($penjualan->total - $penjualan->total_bayar, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <h3>Detail Item Penjualan</h3>
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
            @forelse($penjualan->details as $index => $item)
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
                    <td colspan="8" style="text-align: center;">Tidak ada data item penjualan</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" style="text-align: right;"><strong>Total:</strong></td>
                <td><strong>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="info-box">
        <h3>Keterangan</h3>
        <p>{{ $penjualan->keterangan ?? 'Tidak ada keterangan' }}</p>
    </div>

    @if (count($penjualan->returPenjualan) > 0)
        <div class="info-box">
            <h3>Data Retur Penjualan</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Retur</th>
                        <th>Tanggal</th>
                        <th>Tipe Retur</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualan->returPenjualan as $index => $retur)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $retur->nomor }}</td>
                            <td>{{ \Carbon\Carbon::parse($retur->tanggal)->format('d/m/Y') }}</td>
                            <td>
                                @if ($retur->tipe_retur == 'pengembalian_dana')
                                    Pengembalian Dana
                                @elseif($retur->tipe_retur == 'tukar_barang')
                                    Tukar Barang
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $retur->tipe_retur)) }}
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusText = [
                                        'draft' => 'Draft',
                                        'menunggu_persetujuan' => 'Menunggu Persetujuan',
                                        'disetujui' => 'Disetujui',
                                        'ditolak' => 'Ditolak',
                                        'diproses' => 'Diproses',
                                        'menunggu_pengiriman' => 'Menunggu Pengiriman',
                                        'menunggu_barang_pengganti' => 'Menunggu Barang Pengganti',
                                        'selesai' => 'Selesai',
                                    ];

                                    $status = $retur->status ?? 'draft';
                                    $text = $statusText[$status] ?? ucfirst(str_replace('_', ' ', $status));
                                @endphp
                                {{ $text }}
                            </td>
                            <td>Rp {{ number_format($retur->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align: right;"><strong>Total Retur:</strong></td>
                        <td><strong>Rp
                                {{ number_format($penjualan->returPenjualan->sum('total'), 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @foreach ($penjualan->returPenjualan as $indexRetur => $retur)
            @if (count($retur->details) > 0)
                <div class="info-box">
                    <h3>Detail Item Retur #{{ $retur->nomor }}</h3>
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($retur->tanggal)->format('d/m/Y') }} |
                        <strong>Status:</strong> {{ $statusText[$retur->status ?? 'draft'] }}
                    </p>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Quantity</th>
                                <th>Alasan</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($retur->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $detail->produk->nama ?? 'Produk tidak tersedia' }}<br>
                                        <small>{{ $detail->produk->kode ?? '-' }}
                                            @if ($detail->produk && $detail->produk->satuan)
                                                | Satuan: {{ $detail->produk->satuan->nama ?? '-' }}
                                            @endif
                                        </small>
                                    </td>
                                    <td>{{ $detail->quantity }}
                                        @if ($detail->satuan)
                                            {{ $detail->satuan->nama ?? '' }}
                                        @endif
                                    </td>
                                    <td>{{ $detail->alasan ?? '-' }}</td>
                                    <td>{{ $detail->keterangan ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (!empty($retur->catatan))
                        <p><strong>Catatan Retur:</strong> {{ $retur->catatan }}</p>
                    @endif
                </div>
            @endif
        @endforeach
    @endif

    <div class="info-box">
        <h3>Informasi Tambahan</h3>
        <p><strong>Dibuat oleh:</strong> {{ $penjualan->user->name ?? 'N/A' }}</p>
        <p><strong>Tanggal dibuat:</strong> {{ \Carbon\Carbon::parse($penjualan->created_at)->format('d M Y H:i') }}
        </p>
        <p><strong>Terakhir diupdate:</strong> {{ \Carbon\Carbon::parse($penjualan->updated_at)->format('d M Y H:i') }}
        </p>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak dari sistem ERP Sinar Surya pada {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>

</html>
