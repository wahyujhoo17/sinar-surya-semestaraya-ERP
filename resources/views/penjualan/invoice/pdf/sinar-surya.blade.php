<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice {{ $invoice->nomor }} - PT Sinar Surya Semestaraya</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px; /* Reduced font size */
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 5px 20px 5px 5px; /* Shifted left by reducing left padding */
            background-color: white;
        }

        @page {
            size: A4; /* Or keep 165x212mm if that was required, let's keep A4 or continuous form size */
            margin: 10mm 15mm 10mm 5mm; /* Shifted left */
        }

        .text-blue { color: #1E40AF; }
        .text-bold { font-weight: bold; }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            margin-bottom: 20px;
        }

        .header-table td {
            vertical-align: top;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #1E40AF;
        }

        .company-address {
            font-size: 10px;
            color: #1E40AF;
        }

        .row-table {
            margin-bottom: 10px;
        }

        .row-table td {
            vertical-align: top;
        }

        .col-left {
            width: 50%;
            padding-right: 20px;
        }

        .col-right {
            width: 50%;
            padding-left: 20px;
        }

        .customer-title {
            color: #1E40AF;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .customer-box {
            border: 1px solid #1E40AF;
            padding: 8px;
            min-height: 80px;
        }

        .invoice-title {
            color: #1E40AF;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            text-align: left;
        }

        .invoice-no {
            color: #1E40AF;
            font-weight: bold;
            margin-bottom: 5px;
            text-align: left;
        }

        .invoice-no span {
            color: #000;
        }

        .invoice-box {
            border: 1px solid #1E40AF;
            padding: 8px;
        }

        .invoice-box table {
            width: 100%;
        }

        .invoice-box td {
            padding: 2px 0;
        }

        .items-table {
            margin-top: 15px;
            margin-bottom: 10px;
            border: 1px solid #1E40AF;
        }

        .items-table th {
            border: 1px solid #1E40AF;
            color: #1E40AF;
            padding: 5px;
            text-align: left;
        }

        .items-table th.text-center { text-align: center; }
        .items-table th.text-right { text-align: right; }

        .items-table td {
            border-left: 1px solid #1E40AF;
            border-right: 1px solid #1E40AF;
            padding: 5px;
            vertical-align: top;
        }

        .items-table .bottom-border td {
            border-bottom: 1px solid #1E40AF;
        }

        .terbilang-box {
            border: 1px solid #000;
            padding: 5px;
            margin-bottom: 10px;
            font-style: italic;
            display: inline-block;
            min-width: 80%;
        }

        .perhatian-title {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .bank-info {
            color: #1E40AF;
            margin-top: 5px;
        }

        .summary-table td {
            padding: 3px 5px;
        }

        .summary-line {
            border-bottom: 1px solid #000;
        }

        .signature-table {
            margin-top: 30px;
        }

        .signature-table td {
            width: 50%;
            vertical-align: bottom;
        }

        .sig-name {
            margin-top: 60px;
            text-decoration: underline;
            font-weight: bold;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-lunas { background-color: #10B981; color: white; }
        .status-belum-bayar { background-color: #EF4444; color: white; }
        .status-sebagian { background-color: #F59E0B; color: white; }
    </style>
</head>

<body>
    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 80px;">
                @if ($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo" style="max-width: 70px;">
                @endif
            </td>
            <td>
                <div class="company-name">PT. SINAR SURYA SEMESTARAYA</div>
                <div class="company-address">
                    Jl. Condet Raya No. 6 Balekambang - Jakarta Timur 13530<br>
                    Telp. (021) 80876624 - 80876642<br>
                    E-mail : admin@kliksinarsurya.com - sinar.surya@hotmail.com<br>
                    sinarsurya.sr@gmail.com
                </div>
            </td>
        </tr>
    </table>

    <!-- Row 1: Kepada Yth & INVOICE -->
    <table class="row-table">
        <tr>
            <td class="col-left">
                <div class="customer-title">Kepada Yth,</div>
                <div class="customer-box">
                    <strong>{{ $invoice->customer->company ?? $invoice->customer->nama }}</strong><br>
                    @if ($invoice->customer->alamat)
                        {{ $invoice->customer->alamat }}<br>
                    @endif
                    @if ($invoice->customer->telepon)
                        Telp: {{ $invoice->customer->telepon }}<br>
                    @endif
                </div>
            </td>
            <td class="col-right">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-no">No. : <span>{{ $invoice->nomor }}</span></div>
                <div class="invoice-box">
                    <table>
                        <tr>
                            <td style="width: 40%;">Nomor PO</td>
                            <td style="width: 5%;">:</td>
                            <td>{{ $invoice->salesOrder ? $invoice->salesOrder->nomor : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Nomor SJ</td>
                            <td>:</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Pembayaran</td>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->tanggal)->diffInDays(\Carbon\Carbon::parse($invoice->jatuh_tempo)) }} Hari</td>
                        </tr>
                        <tr>
                            <td>Sales</td>
                            <td>:</td>
                            <td>{{ $invoice->user->name }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No.</th>
                <th style="width: 15%;">Kode Barang</th>
                <th style="width: 30%;">Nama Barang</th>
                <th class="text-center" style="width: 10%;">Jumlah Barang</th>
                <th class="text-right" style="width: 15%;">Harga</th>
                <th class="text-right" style="width: 10%;">Disc</th>
                <th class="text-right" style="width: 15%;">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @if ($invoice->details->isEmpty())
                <tr class="bottom-border">
                    <td colspan="7" class="text-center" style="padding: 20px;">
                        <em>Tidak ada data item</em>
                    </td>
                </tr>
            @else
                @php $no = 1; @endphp
                @foreach ($invoice->details as $index => $detail)
                    <tr class="{{ $loop->last ? 'bottom-border' : '' }}">
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $detail->produk->kode ?? '-' }}</td>
                        <td>
                            {{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}
                            @if ($detail->deskripsi)
                                <br><span style="font-size: 8px;">{{ $detail->deskripsi }}</span>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($detail->quantity, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $detail->diskon > 0 ? number_format($detail->diskon, 0, ',', '.') : '0' }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <!-- Bottom Section: Terbilang & Totals -->
    <table class="row-table">
        <tr>
            <td style="width: 60%; padding-right: 15px;">
                @php
                    function terbilang($angka) {
                        $angka = abs($angka);
                        $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
                        $terbilang = "";
                        if ($angka < 12) {
                            $terbilang = " " . $baca[$angka];
                        } else if ($angka < 20) {
                            $terbilang = terbilang($angka - 10) . " Belas";
                        } else if ($angka < 100) {
                            $terbilang = terbilang($angka / 10) . " Puluh" . terbilang($angka % 10);
                        } else if ($angka < 200) {
                            $terbilang = " Seratus" . terbilang($angka - 100);
                        } else if ($angka < 1000) {
                            $terbilang = terbilang($angka / 100) . " Ratus" . terbilang($angka % 100);
                        } else if ($angka < 2000) {
                            $terbilang = " Seribu" . terbilang($angka - 1000);
                        } else if ($angka < 1000000) {
                            $terbilang = terbilang($angka / 1000) . " Ribu" . terbilang($angka % 1000);
                        } else if ($angka < 1000000000) {
                            $terbilang = terbilang($angka / 1000000) . " Juta" . terbilang($angka % 1000000);
                        } else if ($angka < 1000000000000) {
                            $terbilang = terbilang($angka / 1000000000) . " Milyar" . terbilang(fmod($angka, 1000000000));
                        } else if ($angka < 1000000000000000) {
                            $terbilang = terbilang($angka / 1000000000000) . " Trilyun" . terbilang(fmod($angka, 1000000000000));
                        }
                        return $terbilang;
                    }
                    $terbilang_text = trim(terbilang($invoice->total)) . " Rupiah";
                @endphp
                <div class="terbilang-box">
                    {{ $terbilang_text }}
                </div>
                
                <div class="perhatian-title">PERHATIAN:</div>
                <div>Mohon Dicantumkan NO. {{ $invoice->nomor }} Pada Berita Transfer.</div>
                
                <div class="bank-info">
                    Pembayaran Giro, Cek atau transfer Melalui Bank<br>
                    Mandiri : Cab. Jakarta Jatinegara No Rek : 006.000.301.9563<br>
                    Atas Nama PT. Sinar Surya Semestaraya
                </div>
            </td>
            <td style="width: 40%;">
                <table class="summary-table" style="width: 100%;">
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-right">{{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @if ($invoice->diskon_nominal > 0)
                        <tr>
                            <td>Diskon</td>
                            <td class="text-right">- {{ number_format($invoice->diskon_nominal, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if ($invoice->ongkos_kirim > 0)
                        <tr>
                            <td>Ongkos Kirim</td>
                            <td class="text-right">{{ number_format($invoice->ongkos_kirim, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if ($invoice->ppn > 0)
                        <tr>
                            <td class="summary-line">PPN {{ setting('tax_percentage', 11) }}%</td>
                            <td class="summary-line text-right">{{ number_format($invoice->ppn, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td class="summary-line"></td>
                            <td class="summary-line"></td>
                        </tr>
                    @endif
                    <tr>
                        <td class="text-bold">Total</td>
                        <td class="text-bold text-right">{{ number_format($invoice->total, 0, ',', '.') }}</td>
                    </tr>
                    
                    @if ($invoice->uang_muka_terapkan > 0 || $invoice->pembayaranPiutang->sum('jumlah') > 0)
                        <tr>
                            <td colspan="2" style="padding-top: 10px;"><strong>Pembayaran:</strong></td>
                        </tr>
                        @if ($invoice->uang_muka_terapkan > 0)
                            <tr>
                                <td>Uang Muka</td>
                                <td class="text-right">- {{ number_format($invoice->uang_muka_terapkan, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        @if ($invoice->pembayaranPiutang->sum('jumlah') > 0)
                            <tr>
                                <td class="summary-line">Pembayaran</td>
                                <td class="summary-line text-right">- {{ number_format($invoice->pembayaranPiutang->sum('jumlah'), 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="text-bold">Sisa Tagihan</td>
                            <td class="text-bold text-right">{{ number_format($invoice->sisa_piutang, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <!-- Signatures -->
    <table class="signature-table">
        <tr>
            <td class="text-center" style="width: 50%;">
                <div class="text-blue">Hormat Kami,</div>
                <div class="sig-name">{{ $invoice->user->name ?? 'Arief Rahman Hamid' }}</div>
            </td>
            <td class="text-center" style="width: 50%;">
                <div>Jakarta, {{ \Carbon\Carbon::parse($invoice->tanggal)->format('d/m/Y') }}</div>
                <div class="sig-name" style="text-decoration: none; border-bottom: 1px solid #000; width: 60%; margin: 60px auto 0;"></div>
            </td>
        </tr>
    </table>

</body>
</html>
