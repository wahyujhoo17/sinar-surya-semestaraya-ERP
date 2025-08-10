<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice {{ $invoice->nomor }} - PT Indo Atsaka Industri</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Watermark background */
        .watermark-bg {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            text-align: center;
            z-index: 0;
            opacity: 0.07;
            font-size: 70px;
            font-weight: bold;
            color: #dc2626;
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }

        .page-break {
            page-break-after: always;
        }

        /* Simple table-based layout for better printing support */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #dc2626;
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            vertical-align: top;
            padding: 5px;
            width: 50%;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #1d4ed8;
            padding: 6px;
            text-align: left;
        }

        .items-table th {
            background-color: #dbeafe;
            color: #2c3e50;
        }

        .section-title {
            background-color: #dbeafe;
            padding: 5px;
            font-weight: bold;
            border-left: 3px solid #dc2626;
            color: #2c3e50;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-table {
            border-collapse: collapse;
            width: 40%;
            margin-left: 60%;
            margin-bottom: 15px;
        }

        .summary-table td {
            padding: 5px;
        }

        .total-row {
            font-weight: bold;
            border-top: 1px solid #dc2626;
            color: #2c3e50;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .signature-table td {
            width: 50%;
            vertical-align: bottom;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #1d4ed8;
            width: 80%;
            margin: 50px auto 10px auto;
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
            background-color: #DBEAFE;
            color: #1D4ED8;
        }

        .text-amount {
            text-transform: uppercase;
            font-style: italic;
            font-size: 11px;
            margin-top: 5px;
            color: #dc2626;
        }

        .footer {
            text-align: center;
            margin-top: 45px;
            border-top: 1.5px solid #dbeafe;
            padding-top: 22px;
            background-color: #f9fafb;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.03);
        }

        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            margin: 0;
            box-shadow: none;
            background: #f9fafb;
        }

        .footer-text {
            font-size: 9.5px;
            color: #6b7280;
            margin-top: 15px;
            padding-bottom: 12px;
        }

        /* Print-specific styling */
        @page {
            size: A4;
            margin: 1cm;
        }
    </style>
</head>

<body>
    <div class="watermark-bg">PT INDO ATSAKA INDUSTRI</div>

    <!-- Header Section -->
    <table class="header-table">
        <tr style="margin-bottom: 10px;">
            <td style="width: 50%; vertical-align: middle;">
                @if ($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo Atsaka" style="height: 60px;">
                @endif
            </td>
            <td style="width: 50%; text-align: right; vertical-align: middle;">
                <h2 style="color: #dc2626; margin: 0 0 5px 0;">INVOICE #{{ $invoice->nomor }}</h2>
                <div>
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($invoice->tanggal)->format('d/m/Y') }}<br>
                    <strong>Jatuh Tempo:</strong>
                    {{ \Carbon\Carbon::parse($invoice->jatuh_tempo)->format('d/m/Y') }}<br>
                    @if ($invoice->salesOrder && $invoice->salesOrder->nomor_po)
                        <strong>No. PO:</strong> {{ $invoice->salesOrder->nomor_po }}
                    @endif
                    @if ($invoice->salesOrder)
                        <strong>No. Sales Order:</strong> {{ $invoice->salesOrder->nomor }}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Company and Customer Info Section -->
    <table class="info-table">
        <tr>
            <td>
                <div class="section-title">Info Perusahaan</div>
                <div style="padding: 5px;">
                    <strong>PT INDO ATSAKA INDUSTRI</strong><br>
                    Jl. Industri Raya No. 88, Kawasan Industri<br>
                    Bekasi 17520<br>
                    Telp: (021) 8888-9999 | Fax: (021) 8888-1111<br>
                    Email: info@indoatsaka.co.id
                </div>
            </td>
            <td>
                <div class="section-title">Customer</div>
                <div style="padding: 5px;">
                    <strong>{{ $invoice->customer->company ?? $invoice->customer->nama }}</strong><br>
                    {{ $invoice->customer->alamat ?? '-' }}<br>
                    @if ($invoice->customer->telepon)
                        Telp: {{ $invoice->customer->telepon }}<br>
                    @endif
                    @if ($invoice->customer->email)
                        Email: {{ $invoice->customer->email }}<br>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Produk</th>
                <th width="10%">Qty</th>
                <th width="10%">Satuan</th>
                <th width="15%">Harga</th>
                <th width="10%">Diskon</th>
                <th width="20%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->details as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}</strong>
                        <div style="font-size: 10px;">{{ $detail->produk->kode ?? '' }}</div>
                        @if ($detail->deskripsi)
                            <div style="font-size: 10px; margin-top: 3px;">{{ $detail->deskripsi }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($detail->quantity, 2, ',', '.') }}</td>
                    <td class="text-center">{{ $detail->satuan->nama ?? ($detail->produk->satuan->nama ?? '') }}</td>
                    <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if ($detail->diskon_nominal > 0)
                            Rp {{ number_format($detail->diskon_nominal, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="display: flex; justify-content: space-between;">
        <div class="text-amount">
            <strong>Terbilang:</strong> {{ ucwords(terbilang((int) $invoice->total)) }} Rupiah
        </div>
        <!-- Summary Table -->
        <table class="summary-table">
            <tr>
                <td>Subtotal</td>
                <td class="text-right">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if ($invoice->diskon_nominal > 0)
                <tr>
                    <td>Diskon</td>
                    <td class="text-right">Rp {{ number_format($invoice->diskon_nominal, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if ($invoice->ppn > 0)
                <tr>
                    <td>PPN (11%)</td>
                    <td class="text-right">Rp {{ number_format($invoice->ppn, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if ($invoice->ongkos_kirim > 0)
                <tr>
                    <td>Ongkos Kirim</td>
                    <td class="text-right">Rp {{ number_format($invoice->ongkos_kirim, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td><strong>Total</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($invoice->total, 0, ',', '.') }}</strong></td>
            </tr>
        </table>

        <div style="width: 60%;">
            @if ($invoice->catatan)
                <div
                    style="margin-bottom: 15px; border-left: 3px solid #dc2626; padding-left: 10px; background-color: #f8fafc;">
                    <strong style="color: #2c3e50;">Catatan:</strong>
                    <p>{{ $invoice->catatan }}</p>
                </div>
            @endif

            @if ($invoice->syarat_ketentuan)
                <div
                    style="margin-bottom: 15px; border-left: 3px solid #dc2626; padding-left: 10px; background-color: #f8fafc;">
                    <strong style="color: #2c3e50;">Syarat & Ketentuan:</strong>
                    <div style="margin-top: 5px; white-space: pre-line;">{{ $invoice->syarat_ketentuan }}</div>
                </div>
            @endif

            <div
                style="margin-bottom: 15px; border-left: 3px solid #4a6fa5; padding-left: 10px; background-color: #f8fafc;">
                <strong style="color: #2c3e50;">Informasi Pembayaran:</strong>
                <div style="margin-top: 5px;">
                    Pembayaran Giro, Cek atau Transfer <br>
                    Bank: {{ setting('company_bank_name', 'Mandiri') }}<br>
                    No. Rekening: {{ setting('company_bank_account', '006.000.301.9563') }}<br>
                    Atas Nama: {{ setting('company_name', 'PT. Sinar Surya Semestaraya') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Signatures -->
    <div style="width: 100%; margin-top: 30px;">
        <div style="width: 40%; float: right; text-align: center;">
            <div style="margin-bottom: 35px; font-weight: bold; color: #2c3e50;">Hormat Kami,</div>
            <div class="signature-line" style="margin: 50px auto 10px auto; width: 80%;"></div>
            <div><strong style="color: #2c3e50;">{{ $invoice->user->name ?? 'Management' }}</strong></div>
        </div>
        <div style="clear: both;"></div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-text">
            <p>Dokumen ini dicetak secara digital pada {{ $currentDate }} {{ $currentTime }} WIB |
                PT Indo Atsaka Industri</p>
        </div>
    </div>

</body>

</html>
