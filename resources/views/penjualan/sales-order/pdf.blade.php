<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sales Order {{ $salesOrder->nomor }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .page-break {
            page-break-after: always;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px 10px;
        }

        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
        }

        .company-info {
            text-align: left;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 11px;
        }

        .document-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
        }

        .document-info {
            margin-bottom: 15px;
        }

        .row {
            display: flex;
            margin-bottom: 15px;
        }

        .col-6 {
            width: 50%;
        }

        .label {
            font-weight: bold;
            margin-right: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals {
            width: 40%;
            margin-left: auto;
            margin-bottom: 30px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .total-label {
            font-weight: bold;
        }

        .grand-total {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #ddd;
            padding-top: 5px;
            margin-top: 5px;
        }

        .notes {
            margin-bottom: 30px;
        }

        .terms {
            margin-bottom: 30px;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 60px;
            margin-bottom: 5px;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-belum_bayar,
        .status-belum_dikirim {
            background-color: #FEE2E2;
            color: #B91C1C;
        }

        .status-sebagian {
            background-color: #FEF3C7;
            color: #D97706;
        }

        .status-lunas,
        .status-dikirim {
            background-color: #D1FAE5;
            color: #059669;
        }

        .info-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 15px;
        }

        .info-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 12px;
        }

        .text-amount {
            text-transform: uppercase;
            font-style: italic;
            font-size: 11px;
            margin-top: 5px;
        }

        .page-number {
            position: absolute;
            bottom: 20px;
            right: 20px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <div class="company-info">
                    <div class="company-name">PT. SINAR SURYA</div>
                    <div class="company-address">
                        Jl. Raya Sukabumi KM 5, Ciawi, Bogor<br>
                        Telepon: (021) 12345678<br>
                        Email: info@sinarsurya.com
                    </div>
                </div>
                <div class="document-info" style="text-align: right;">
                    <div style="font-size: 14px;"><strong>SALES ORDER</strong></div>
                    <div style="margin-top: 5px;"><strong>Nomor:</strong> {{ $salesOrder->nomor }}</div>
                    <div><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($salesOrder->tanggal)->format('d/m/Y') }}
                    </div>
                    @if ($salesOrder->nomor_po)
                        <div><strong>Nomor PO Customer:</strong> {{ $salesOrder->nomor_po }}</div>
                    @endif
                    @if ($salesOrder->quotation)
                        <div><strong>Berdasarkan Quotation:</strong> {{ $salesOrder->quotation->nomor }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="document-title">SALES ORDER</div>

        <div class="row">
            <div class="col-6">
                <div class="info-box">
                    <div class="info-title">Customer:</div>
                    <div>{{ $salesOrder->customer->nama }}</div>
                    @if ($salesOrder->customer->company)
                        <div>{{ $salesOrder->customer->company }}</div>
                    @endif
                    <div>{{ $salesOrder->customer->alamat_utama ?? '-' }}</div>
                    <div>Telepon: {{ $salesOrder->customer->telepon ?? '-' }}</div>
                    <div>Email: {{ $salesOrder->customer->email ?? '-' }}</div>
                </div>
            </div>
            <div class="col-6">
                <div class="info-box">
                    <div class="info-title">Alamat Pengiriman:</div>
                    <div>{{ $salesOrder->alamat_pengiriman ?? ($salesOrder->customer->alamat_utama ?? '-') }}</div>
                    <div>&nbsp;</div>
                    <div class="info-title" style="margin-top: 10px;">Status:</div>
                    <div>
                        <span class="status-badge status-{{ $salesOrder->status_pembayaran }}">
                            @if ($salesOrder->status_pembayaran == 'belum_bayar')
                                Belum Bayar
                            @elseif ($salesOrder->status_pembayaran == 'sebagian')
                                Sebagian
                            @elseif ($salesOrder->status_pembayaran == 'lunas')
                                Lunas
                            @endif
                        </span>
                        &nbsp;
                        <span class="status-badge status-{{ $salesOrder->status_pengiriman }}">
                            @if ($salesOrder->status_pengiriman == 'belum_dikirim')
                                Belum Dikirim
                            @elseif ($salesOrder->status_pengiriman == 'sebagian')
                                Sebagian
                            @elseif ($salesOrder->status_pengiriman == 'dikirim')
                                Dikirim
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 35%;">Produk</th>
                    <th style="width: 10%;">Kuantitas</th>
                    <th style="width: 10%;">Satuan</th>
                    <th style="width: 15%;">Harga</th>
                    <th style="width: 10%;">Diskon (%)</th>
                    <th style="width: 15%;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($salesOrder->details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <div><strong>{{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}</strong></div>
                            <div style="font-size: 10px;">{{ $detail->produk->kode ?? '' }}</div>
                            @if ($detail->deskripsi)
                                <div style="font-size: 10px; margin-top: 3px;">{{ $detail->deskripsi }}</div>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($detail->quantity, 2, ',', '.') }}</td>
                        <td class="text-center">{{ $detail->satuan->nama ?? '' }}</td>
                        <td class="text-right">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if ($detail->diskon_persen > 0)
                                {{ number_format($detail->diskon_persen, 2, ',', '.') }}%
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="display: flex; justify-content: space-between;">
            <div style="width: 60%;">
                @php
                    // Convert number to words in Indonesian
                    function terbilang($number)
                    {
                        $number = abs($number);
                        $words = [
                            '',
                            'satu',
                            'dua',
                            'tiga',
                            'empat',
                            'lima',
                            'enam',
                            'tujuh',
                            'delapan',
                            'sembilan',
                            'sepuluh',
                            'sebelas',
                        ];

                        if ($number < 12) {
                            return $words[$number];
                        } elseif ($number < 20) {
                            return $words[$number - 10] . ' belas';
                        } elseif ($number < 100) {
                            return $words[floor($number / 10)] . ' puluh ' . $words[$number % 10];
                        } elseif ($number < 200) {
                            return 'seratus ' . terbilang($number - 100);
                        } elseif ($number < 1000) {
                            return $words[floor($number / 100)] . ' ratus ' . terbilang($number % 100);
                        } elseif ($number < 2000) {
                            return 'seribu ' . terbilang($number - 1000);
                        } elseif ($number < 1000000) {
                            return terbilang(floor($number / 1000)) . ' ribu ' . terbilang($number % 1000);
                        } elseif ($number < 1000000000) {
                            return terbilang(floor($number / 1000000)) . ' juta ' . terbilang($number % 1000000);
                        } elseif ($number < 1000000000000) {
                            return terbilang(floor($number / 1000000000)) .
                                ' milyar ' .
                                terbilang($number % 1000000000);
                        } elseif ($number < 1000000000000000) {
                            return terbilang(floor($number / 1000000000000)) .
                                ' trilyun ' .
                                terbilang($number % 1000000000000);
                        } else {
                            return 'Angka terlalu besar';
                        }
                    }
                @endphp

                <div class="notes">
                    @if ($salesOrder->catatan)
                        <div class="section-title">Catatan:</div>
                        <div>{{ $salesOrder->catatan }}</div>
                    @endif
                </div>
                <div class="text-amount">
                    Terbilang: {{ ucwords(terbilang((int) $salesOrder->total)) }} Rupiah
                </div>
            </div>
            <div class="totals">
                <div class="total-row">
                    <div class="total-label">Subtotal:</div>
                    <div>{{ number_format($salesOrder->subtotal, 0, ',', '.') }}</div>
                </div>
                @if ($salesOrder->diskon_nominal > 0)
                    <div class="total-row">
                        <div class="total-label">Diskon:</div>
                        <div>{{ number_format($salesOrder->diskon_nominal, 0, ',', '.') }}</div>
                    </div>
                @endif
                @if ($salesOrder->ppn > 0)
                    <div class="total-row">
                        <div class="total-label">PPN ({{ $salesOrder->ppn }}%):</div>
                        <div>
                            {{ number_format(($salesOrder->subtotal - $salesOrder->diskon_nominal) * ($salesOrder->ppn / 100), 0, ',', '.') }}
                        </div>
                    </div>
                @endif
                <div class="total-row grand-total">
                    <div class="total-label">TOTAL:</div>
                    <div>{{ number_format($salesOrder->total, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        @if ($salesOrder->syarat_ketentuan)
            <div class="terms">
                <div class="section-title">Syarat dan Ketentuan:</div>
                <div style="white-space: pre-line;">{{ $salesOrder->syarat_ketentuan }}</div>
            </div>
        @endif

        <div class="signatures">
            <div class="signature-box">
                <div>Disiapkan Oleh,</div>
                <div class="signature-line"></div>
                <div>{{ $salesOrder->user->name ?? 'Admin' }}</div>
            </div>
            <div class="signature-box">
                <div>Disetujui Oleh,</div>
                <div class="signature-line"></div>
                <div>{{ $salesOrder->customer->nama }}</div>
            </div>
        </div>

        <div class="page-number">Halaman 1 dari 1</div>
    </div>
</body>

</html>
