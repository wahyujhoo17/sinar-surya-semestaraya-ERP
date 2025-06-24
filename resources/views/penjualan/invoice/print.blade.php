<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice - {{ $invoice->nomor }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            color: #333;
        }

        .page-break {
            page-break-after: always;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }

        .header {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .header-left {
            float: left;
            width: 40%;
        }

        .header-right {
            float: right;
            width: 40%;
            text-align: right;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-info {
            margin-bottom: 5px;
            line-height: 1.5;
        }

        .invoice-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
            padding: 5px 0;
            border-bottom: 2px solid #333;
            border-top: 2px solid #333;
        }

        .invoice-info {
            margin-bottom: 20px;
        }

        .customer-info {
            margin-bottom: 20px;
            width: 100%;
        }

        .info-row {
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            width: 100px;
            display: inline-block;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .totals {
            width: 40%;
            float: right;
            margin-top: 10px;
        }

        .total-row {
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
        }

        .total-label {
            font-weight: bold;
            text-align: right;
        }

        .total-value {
            text-align: right;
            width: 100px;
        }

        .grand-total {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #ddd;
            padding-top: 5px;
            margin-top: 5px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #777;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #333;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .notes {
            margin-top: 30px;
            border-top: 1px dashed #ddd;
            padding-top: 10px;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .terms {
            margin-top: 20px;
            border-top: 1px dashed #ddd;
            padding-top: 10px;
        }

        .terms-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .payment-info {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
        }

        .payment-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        @media print {
            body {
                font-size: 11pt;
            }

            .container {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header clearfix">
            <div class="header-left">
                <div class="company-name">{{ setting('company_name', 'PT Sinar Surya Semestaraya') }}</div>
                <div class="company-info">
                    {{ setting('company_address', 'Jl. Condet Raya No. 6 Balekambang') }}<br>
                    {{ setting('company_city', 'Jakarta Timur') }}, {{ setting('company_postal_code', '13530') }}<br>
                    Telp: {{ setting('company_phone', '(021) 80876624 - 80876642') }}<br>
                    Email: {{ setting('company_email', 'admin@kliksinarsurya.com') }}
                </div>
            </div>
            <div class="header-right">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-info">
                    <div class="info-row">
                        <span class="info-label">No. Invoice:</span>
                        <span>{{ $invoice->nomor }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal:</span>
                        <span>{{ date('d/m/Y', strtotime($invoice->tanggal)) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jatuh Tempo:</span>
                        <span>{{ date('d/m/Y', strtotime($invoice->jatuh_tempo)) }}</span>
                    </div>
                    @if ($invoice->salesOrder)
                        <div class="info-row">
                            <span class="info-label">No. Sales Order:</span>
                            <span>{{ $invoice->salesOrder->nomor }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="customer-info clearfix">
            <div style="float: left; width: 50%;">
                <h3 style="margin-top: 0; margin-bottom: 5px;">Kepada:</h3>
                <div style="font-weight: bold; margin-bottom: 3px;">{{ $invoice->customer->company }}</div>
                <div>{{ $invoice->customer->nama }}</div>
                <div>{{ $invoice->customer->alamat }}</div>
                <div>{{ $invoice->customer->telepon }}</div>
                <div>{{ $invoice->customer->email }}</div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="40%">Deskripsi</th>
                    <th width="10%">Kuantitas</th>
                    <th width="10%">Satuan</th>
                    <th width="15%">Harga</th>
                    <th width="10%">Diskon</th>
                    <th width="15%">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($invoice->details as $detail)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>
                            {{ $detail->produk->nama }}<br>
                            <small>{{ $detail->deskripsi }}</small>
                        </td>
                        <td>{{ number_format($detail->kuantitas, 2, ',', '.') }}</td>
                        <td>{{ $detail->satuan }}</td>
                        <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="text-right">
                            @if ($detail->diskon > 0)
                                {{ number_format($detail->diskon, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="clearfix">
            <div style="float: left; width: 50%;">
                @if ($invoice->catatan)
                    <div class="notes">
                        <div class="notes-title">Catatan:</div>
                        <div>{{ $invoice->catatan }}</div>
                    </div>
                @endif

                @if ($invoice->syarat_ketentuan)
                    <div class="terms">
                        <div class="terms-title">Syarat & Ketentuan:</div>
                        <div>{{ $invoice->syarat_ketentuan }}</div>
                    </div>
                @endif

                <div class="payment-info">
                    <div class="payment-title">Informasi Pembayaran:</div>
                    <div>Bank: {{ $invoice->user->company->nama_bank }}</div>
                    <div>No. Rekening: {{ $invoice->user->company->no_rekening }}</div>
                    <div>Atas Nama: {{ $invoice->user->company->nama_rekening }}</div>
                </div>
            </div>

            <div class="totals">
                <div class="total-row">
                    <div class="total-label">Subtotal:</div>
                    <div class="total-value">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</div>
                </div>

                @if ($invoice->diskon_nominal > 0)
                    <div class="total-row">
                        <div class="total-label">Diskon:</div>
                        <div class="total-value">Rp {{ number_format($invoice->diskon_nominal, 0, ',', '.') }}</div>
                    </div>
                @endif

                @if ($invoice->ppn > 0)
                    <div class="total-row">
                        <div class="total-label">PPN ({{ setting('tax_percentage', 11) }}%):</div>
                        <div class="total-value">Rp {{ number_format($invoice->ppn, 0, ',', '.') }}</div>
                    </div>
                @endif

                <div class="total-row grand-total">
                    <div class="total-label">Total:</div>
                    <div class="total-value">Rp {{ number_format($invoice->total, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="signatures clearfix">
            <div class="signature-box">
                <div>Diterbitkan oleh,</div>
                <div class="signature-line"></div>
                <div>{{ $invoice->user->name }}</div>
                <div>{{ $invoice->user->company->nama }}</div>
            </div>
            <div class="signature-box">
                <div>Diterima oleh,</div>
                <div class="signature-line"></div>
                <div>{{ $invoice->customer->nama }}</div>
                <div>{{ $invoice->customer->company }}</div>
            </div>
        </div>

        <div class="footer">
            <p>Invoice ini sah dan diproses oleh komputer. Silakan hubungi kami jika Anda membutuhkan bantuan.</p>
        </div>
    </div>
</body>

</html>
