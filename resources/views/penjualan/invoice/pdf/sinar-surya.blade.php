<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice {{ $invoice->nomor }} - PT Sinar Surya Semestaraya</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 10px;
            background-color: white;
        }

        /* Page setup for 165x212mm */
        @page {
            size: 165mm 212mm;
            margin: 10mm;
        }

        /* Watermark background */
        .watermark-bg {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            text-align: center;
            z-index: 0;
            opacity: 0.05;
            font-size: 48px;
            font-weight: bold;
            color: #1E40AF;
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
        }

        /* Header styles */
        .header {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 2px solid #1E40AF;
            padding-bottom: 10px;
        }

        .header-row {
            display: table;
            width: 100%;
        }

        .header-left {
            display: table-cell;
            width: 75%;
            vertical-align: top;
        }

        .header-right {
            display: table-cell;
            width: 25%;
            vertical-align: top;
            text-align: right;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #1E40AF;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 9px;
            color: #666;
            line-height: 1.2;
        }

        .logo {
            max-width: 60px;
            max-height: 60px;
        }

        /* Document title */
        .document-title {
            text-align: center;
            margin: 15px 0;
            padding: 8px;
            background: linear-gradient(135deg, #1E40AF, #3B82F6);
            color: white;
            font-size: 14px;
            font-weight: bold;
            border-radius: 5px;
        }

        /* Invoice info section */
        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .invoice-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }

        .invoice-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-left: 10px;
        }

        .info-box {
            border: 1px solid #ddd;
            padding: 8px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
            border-radius: 3px;
        }

        .info-title {
            font-weight: bold;
            color: #1E40AF;
            margin-bottom: 5px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }

        .info-content {
            font-size: 10px;
        }

        /* Items table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            border: 1px solid #ddd;
        }

        .items-table th {
            background: linear-gradient(135deg, #1E40AF, #3B82F6);
            color: white;
            font-weight: bold;
            padding: 6px 4px;
            text-align: center;
            font-size: 9px;
            border: 1px solid #1E40AF;
        }

        .items-table td {
            padding: 5px 4px;
            border: 1px solid #ddd;
            font-size: 9px;
            vertical-align: top;
        }

        .items-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .items-table tbody tr:hover {
            background-color: #e3f2fd;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        /* Summary section */
        .summary-section {
            display: table;
            width: 100%;
            margin-top: 15px;
        }

        .summary-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }

        .summary-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-left: 10px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 4px 8px;
            border-bottom: 1px solid #eee;
            font-size: 10px;
        }

        .summary-table .label {
            text-align: left;
            color: #666;
        }

        .summary-table .value {
            text-align: right;
            font-weight: bold;
        }

        .total-row {
            background-color: #1E40AF;
            color: white;
            font-weight: bold;
        }

        /* Notes section */
        .notes-section {
            margin-top: 15px;
            border: 1px solid #ddd;
            padding: 8px;
            background-color: #f8f9fa;
            border-radius: 3px;
        }

        .notes-title {
            font-weight: bold;
            color: #1E40AF;
            margin-bottom: 5px;
        }

        .notes-content {
            font-size: 9px;
            line-height: 1.4;
            min-height: 30px;
        }

        /* Signature section */
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 20px;
        }

        .signature-left {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding-right: 10px;
        }

        .signature-right {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding-left: 10px;
        }

        .signature-box {
            border: 1px solid #ddd;
            padding: 8px;
            background-color: #f8f9fa;
            border-radius: 3px;
            min-height: 60px;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #1E40AF;
        }

        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #333;
            padding-top: 3px;
            font-size: 9px;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }

        /* Utility classes */
        .font-bold {
            font-weight: bold;
        }

        .text-primary {
            color: #1E40AF;
        }

        .mb-5 {
            margin-bottom: 5px;
        }

        .no-wrap {
            white-space: nowrap;
        }

        /* Status badge */
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-lunas {
            background-color: #10B981;
            color: white;
        }

        .status-belum-bayar {
            background-color: #EF4444;
            color: white;
        }

        .status-sebagian {
            background-color: #F59E0B;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Watermark Background -->
    <div class="watermark-bg">
        PT SINAR SURYA<br>SEMESTARAYA
    </div>

    <div class="content-wrapper">
        <!-- Header -->
        <div class="header">
            <div class="header-row">
                <div class="header-left">
                    <div class="company-name">PT SINAR SURYA SEMESTARAYA</div>
                    <div class="company-details">
                        Jl. Industri Raya No. 88, Kawasan Industri<br>
                        Cakung, Jakarta Timur 13910<br>
                        Telp: (021) 4604-5678 | Fax: (021) 4604-5679<br>
                        Email: info@sinarsurya.co.id
                    </div>
                </div>
                <div class="header-right">
                    @if ($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Logo" class="logo">
                    @endif
                </div>
            </div>
        </div>

        <!-- Document Title -->
        <div class="document-title">
            INVOICE / FAKTUR PENJUALAN
        </div>

        <!-- Invoice Information -->
        <div class="invoice-info">
            <div class="invoice-left">
                <!-- Customer Information -->
                <div class="info-box">
                    <div class="info-title">BILL TO / KEPADA:</div>
                    <div class="info-content">
                        <strong>{{ $invoice->customer->company ?? $invoice->customer->nama }}</strong><br>
                        @if ($invoice->customer->alamat)
                            {{ $invoice->customer->alamat }}<br>
                        @endif
                        @if ($invoice->customer->telepon)
                            Telp: {{ $invoice->customer->telepon }}<br>
                        @endif
                        @if ($invoice->customer->email)
                            Email: {{ $invoice->customer->email }}
                        @endif
                    </div>
                </div>

                <!-- Sales Order Reference -->
                @if ($invoice->salesOrder)
                    <div class="info-box">
                        <div class="info-title">REFERENSI SALES ORDER:</div>
                        <div class="info-content">
                            <strong>{{ $invoice->salesOrder->nomor }}</strong><br>
                            Tanggal SO: {{ \Carbon\Carbon::parse($invoice->salesOrder->tanggal)->format('d/m/Y') }}
                        </div>
                    </div>
                @endif
            </div>

            <div class="invoice-right">
                <!-- Invoice Details -->
                <div class="info-box">
                    <div class="info-title">DETAIL INVOICE:</div>
                    <div class="info-content">
                        <strong>Nomor: {{ $invoice->nomor }}</strong><br>
                        Tanggal: {{ \Carbon\Carbon::parse($invoice->tanggal)->format('d/m/Y') }}<br>
                        Jatuh Tempo: {{ \Carbon\Carbon::parse($invoice->jatuh_tempo)->format('d/m/Y') }}<br>
                        Status: <span
                            class="status-badge status-{{ str_replace(['_', ' '], '-', strtolower($invoice->status)) }}">
                            @if ($invoice->status === 'belum_bayar')
                                Belum Bayar
                            @elseif($invoice->status === 'sebagian')
                                Bayar Sebagian
                            @elseif($invoice->status === 'lunas')
                                Lunas
                            @else
                                {{ ucfirst($invoice->status) }}
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Print Information -->
                <div class="info-box">
                    <div class="info-title">INFORMASI CETAK:</div>
                    <div class="info-content">
                        Dicetak: {{ $currentDate }}<br>
                        Jam: {{ $currentTime }}<br>
                        Oleh: {{ $invoice->user->name }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">Kode</th>
                    <th style="width: 28%;">Nama Produk</th>
                    <th style="width: 8%;">Qty</th>
                    <th style="width: 8%;">Satuan</th>
                    <th style="width: 13%;">Harga</th>
                    <th style="width: 10%;">Diskon</th>
                    <th style="width: 16%;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @if ($invoice->details->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 20px;">
                            <em>Tidak ada data item</em>
                        </td>
                    </tr>
                @else
                    @php $no = 1; @endphp
                    @foreach ($invoice->details as $detail)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td class="text-left">{{ $detail->produk->kode ?? '-' }}</td>
                            <td class="text-left">
                                <strong>{{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}</strong>
                                @if ($detail->deskripsi)
                                    <br><em style="font-size: 8px; color: #666;">{{ $detail->deskripsi }}</em>
                                @endif
                            </td>
                            <td class="text-right">{{ number_format($detail->quantity, 2, ',', '.') }}</td>
                            <td class="text-center">{{ $detail->satuan->nama ?? '-' }}</td>
                            <td class="text-right">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td class="text-right">
                                {{ $detail->diskon > 0 ? number_format($detail->diskon, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-right"><strong>{{ number_format($detail->subtotal, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-left">
                <!-- Notes -->
                <div class="notes-section">
                    <div class="notes-title">CATATAN:</div>
                    <div class="notes-content">
                        {{ $invoice->catatan ?? 'Tidak ada catatan khusus.' }}
                    </div>
                </div>
            </div>

            <div class="summary-right">
                <!-- Financial Summary -->
                <table class="summary-table">
                    <tr>
                        <td class="label">Subtotal:</td>
                        <td class="value">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @if ($invoice->diskon_nominal > 0)
                        <tr>
                            <td class="label">
                                Diskon
                                @if ($invoice->diskon_persen > 0)
                                    ({{ number_format($invoice->diskon_persen, 1, ',', '.') }}%)
                                @endif
                                :
                            </td>
                            <td class="value" style="color: #EF4444;">- Rp
                                {{ number_format($invoice->diskon_nominal, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if ($invoice->ongkos_kirim > 0)
                        <tr>
                            <td class="label">Ongkos Kirim:</td>
                            <td class="value">Rp {{ number_format($invoice->ongkos_kirim, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if ($invoice->ppn > 0)
                        <tr>
                            <td class="label">PPN (11%):</td>
                            <td class="value">Rp {{ number_format($invoice->ppn, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td class="label">TOTAL:</td>
                        <td class="value">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                    </tr>
                    @if ($invoice->uang_muka_terapkan > 0 || $invoice->kredit_terapkan > 0 || $invoice->pembayaranPiutang->sum('jumlah') > 0)
                        <tr>
                            <td colspan="2" style="padding: 8px 0; font-size: 8px; color: #666;">
                                <strong>RINCIAN PEMBAYARAN:</strong>
                            </td>
                        </tr>
                        @if ($invoice->uang_muka_terapkan > 0)
                            <tr>
                                <td class="label">Uang Muka:</td>
                                <td class="value" style="color: #3B82F6;">- Rp
                                    {{ number_format($invoice->uang_muka_terapkan, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        @if ($invoice->kredit_terapkan > 0)
                            <tr>
                                <td class="label">Kredit:</td>
                                <td class="value" style="color: #8B5CF6;">- Rp
                                    {{ number_format($invoice->kredit_terapkan, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        @if ($invoice->pembayaranPiutang->sum('jumlah') > 0)
                            <tr>
                                <td class="label">Pembayaran:</td>
                                <td class="value" style="color: #10B981;">- Rp
                                    {{ number_format($invoice->pembayaranPiutang->sum('jumlah'), 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr style="background-color: #F3F4F6;">
                            <td class="label"><strong>SISA TAGIHAN:</strong></td>
                            <td class="value"
                                style="color: {{ $invoice->sisa_piutang > 0 ? '#EF4444' : '#10B981' }};">
                                <strong>Rp {{ number_format($invoice->sisa_piutang, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-left">
                <div class="signature-box">
                    <div class="signature-title">PENERIMA</div>
                    <div class="signature-line">
                        {{ $invoice->customer->kontak_person ?? '(..........................)' }}
                    </div>
                </div>
            </div>
            <div class="signature-right">
                <div class="signature-box">
                    <div class="signature-title">HORMAT KAMI</div>
                    <div class="signature-line">
                        {{ $invoice->user->name ?? '(..........................)' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <strong>PT Sinar Surya Semestaraya</strong> |
            Invoice: {{ $invoice->nomor }} |
            Halaman 1 dari 1 |
            Dicetak: {{ $currentDate }} {{ $currentTime }}
        </div>
    </div>
</body>

</html>
