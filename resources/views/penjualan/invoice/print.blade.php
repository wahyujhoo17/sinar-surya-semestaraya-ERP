<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice {{ $invoice->nomor }}</title>
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
            color: #4a6fa5;
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
            border-bottom: 2px solid #4a6fa5;
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
            border: 1px solid #b8c4d6;
            padding: 6px;
            text-align: left;
        }

        .items-table th {
            background-color: #e8f0fa;
            color: #2c3e50;
        }

        .section-title {
            background-color: #e8f0fa;
            padding: 5px;
            font-weight: bold;
            border-left: 3px solid #4a6fa5;
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
            border-top: 1px solid #4a6fa5;
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
            border-top: 1px solid #b8c4d6;
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
            background-color: #D1FAE5;
            color: #059669;
        }

        .text-amount {
            text-transform: uppercase;
            font-style: italic;
            font-size: 11px;
            margin-top: 5px;
            color: #4a6fa5;
        }

        .footer {
            text-align: center;
            margin-top: 45px;
            border-top: 1.5px solid #e0e6ed;
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
    <div class="watermark-bg">{{ strtoupper(setting('company_name', 'SINAR SURYA SEMESTARAYA')) }}</div>

    <!-- Header Section -->
    <table class="header-table">
        <tr style="margin-bottom: 10px;">
            <td style="width: 50%; vertical-align: middle;">
                <img src="{{ public_path('img/logo_nama3.png') }}" alt="Sinar Surya Logo"
                    onerror="this.src='{{ public_path('img/logo-default.png') }}';" style="height: 60px;">
            </td>
            <td style="width: 50%; text-align: right; vertical-align: middle;">
                <h2 style="color: #4a6fa5; margin: 0 0 5px 0;">INVOICE #{{ $invoice->nomor }}</h2>
                <div>
                    {{-- <strong>Nomor:</strong> {{ $invoice->nomor }}<br> --}}
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($invoice->tanggal)->format('d/m/Y') }}<br>
                    <strong>Jatuh Tempo:</strong>
                    {{ \Carbon\Carbon::parse($invoice->jatuh_tempo)->format('d/m/Y') }}<br>
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
                    <strong>{{ setting('company_name', 'PT. SINAR SURYA SEMESTARAYA') }}</strong><br>
                    {{ setting('company_address', 'Jl. Condet Raya No. 6 Balekambang') }}<br>
                    {{ setting('company_city', 'Jakarta Timur') }} {{ setting('company_postal_code', '13530') }}<br>
                    Telp. {{ setting('company_phone', '(021) 80876624 - 80876642') }}<br>
                    E-mail: {{ setting('company_email', 'admin@kliksinarsurya.com') }}<br>
                    @if (setting('company_email_2'))
                        {{ setting('company_email_2') }}<br>
                    @endif
                    @if (setting('company_email_3'))
                        {{ setting('company_email_3') }}
                    @endif
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
                    <td>PPN ({{ setting('tax_percentage', 11) }}%)</td>
                    <td class="text-right">Rp {{ number_format($invoice->ppn, 0, ',', '.') }}</td>
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
                    style="margin-bottom: 15px; border-left: 3px solid #4a6fa5; padding-left: 10px; background-color: #f8fafc;">
                    <strong style="color: #2c3e50;">Catatan:</strong>
                    <p>{{ $invoice->catatan }}</p>
                </div>
            @endif

            @if ($invoice->syarat_ketentuan)
                <div
                    style="margin-bottom: 15px; border-left: 3px solid #4a6fa5; padding-left: 10px; background-color: #f8fafc;">
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
    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-line"></div>
                <div><strong style="color: #2c3e50;">{{ $invoice->user->name ?? 'Admin' }}</strong></div>
                <div style="color: #7f8c8d;">{{ setting('company_name', 'PT. SINAR SURYA SEMESTARAYA') }}</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div><strong
                        style="color: #2c3e50;">{{ $invoice->customer->nama ?? $invoice->customer->company }}</strong>
                </div>
                <div style="color: #7f8c8d;">Customer</div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-text">
            <p>Dokumen ini dicetak secara digital pada {{ now()->format('d M Y, H:i') }} WIB |
                {{ setting('company_name', 'PT. SINAR SURYA SEMESTARAYA') }}</p>
        </div>
    </div>

</body>

</html>
