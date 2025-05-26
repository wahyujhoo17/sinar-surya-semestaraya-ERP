<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan - {{ $deliveryOrder->nomor }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: white;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            /* A4 width */
            min-height: 297mm - 40mm;
            /* A4 height minus margins */
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
            background: white;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }

        .logo-container {
            width: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 15px;
        }

        .company-info {
            flex: 1;
        }

        .company-logo {
            max-width: 100%;
            height: auto;
            display: block;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            margin-bottom: 8px;
        }

        .company-address {
            font-size: 11px;
            line-height: 1.3;
        }

        .title-section {
            text-align: center;
            margin-bottom: 25px;
        }

        .document-title {
            font-size: 22px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 15px;
            letter-spacing: 1px;
            text-align: center;
        }

        .document-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            font-size: 12px;
            max-width: 85%;
            margin-left: auto;
            margin-right: auto;
        }

        .info-item {
            display: flex;
            align-items: center;
        }

        .info-label {
            font-weight: bold;
            margin-right: 10px;
        }

        .info-value {
            border-bottom: 1px solid #000;
            min-width: 150px;
            padding: 2px 5px;
            display: inline-block;
        }

        .recipient-section {
            margin-bottom: 25px;
        }

        .recipient-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .recipient-box {
            border: 1px solid #000;
            padding: 15px;
            min-height: 100px;
            background: white;
        }

        .recipient-label {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .recipient-sublabel {
            font-size: 10px;
            font-style: italic;
            margin-bottom: 15px;
        }

        .recipient-content {
            min-height: 60px;
            margin-bottom: 10px;
        }

        .greeting {
            margin: 25px 0 20px;
            font-size: 12px;
        }

        .greeting-title {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 11px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
            font-size: 11px;
        }

        .items-table .no-col {
            width: 8%;
            text-align: center;
        }

        .items-table .item-col {
            width: 40%;
        }

        .items-table .catalog-col {
            width: 25%;
        }

        .items-table .qty-col {
            width: 12%;
            text-align: center;
        }

        .items-table .unit-col {
            width: 15%;
            text-align: center;
        }

        .items-table tbody td {
            height: 30px;
            padding: 6px 8px;
            vertical-align: middle;
        }

        .signature-section {
            margin-top: 40px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            page-break-inside: avoid;
        }

        .signature-left {
            flex: 1;
            text-align: center;
        }

        .signature-right {
            flex: 1;
            text-align: center;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }

        .signature-box {
            margin-top: 15px;
        }

        .signature-name {
            border-top: 1px solid #000;
            padding-top: 5px;
            width: 200px;
            margin: 0 auto;
            text-align: center;
        }

        .notes-section {
            margin-top: 30px;
            font-size: 10px;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .notes-content {
            border: 1px solid #000;
            padding: 10px;
            min-height: 50px;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            .container {
                border: 2px solid #000;
                max-width: none;
                width: 100%;
                margin: 0;
                padding: 10mm;
                box-sizing: border-box;
                page-break-after: always;
            }

            .no-print {
                display: none;
            }

            @page {
                size: A4 portrait;
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <div class="logo-container">
                <img src="{{ asset('img/Logo.png') }}" alt="Logo Sinar Surya" class="company-logo">
            </div>
            <div class="company-info">
                <div class="company-name">PT SINAR SURYA SEMESTARAYA</div>
                <div class="company-address">
                    Jl. Condet Raya No. 6 Balekambang - Jakarta Timur 13530<br>
                    Telp. (021) 80876624 - 80876642<br>
                    E-mail : admin@kliksinarsurya.com - sinar.surya@hotmail.com<br>
                    sinarsurya.sr@gmail.com
                </div>
            </div>
        </div>

        <!-- Title Section -->
        <div class="title-section">
            <div class="document-title">SURAT JALAN</div>
        </div>

        <!-- Document Info -->
        <div class="document-info">
            <div class="info-item">
                <span class="info-label">Nomor :</span>
                <span class="info-value">{{ $deliveryOrder->nomor }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal :</span>
                <span class="info-value">{{ date('d F Y', strtotime($deliveryOrder->tanggal)) }}</span>
            </div>
        </div>

        <!-- Recipient Section -->
        <div class="recipient-section">
            <div class="recipient-title">Kepada Yth :</div>
            <div class="recipient-box">
                <div class="recipient-label">Penerima</div>

                <div class="recipient-content">
                    {{ $deliveryOrder->customer->company ?? $deliveryOrder->customer->nama }}<br>
                    {{ $deliveryOrder->alamat_pengiriman }}
                </div>
            </div>
        </div>

        <!-- Greeting -->
        <div class="greeting">
            <div class="greeting-title">Dengan Hormat,</div>
            <div>Mohon diterima dengan baik barang-barang pesanan Bapak/Ibu sbb :</div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="no-col">No.</th>
                    <th class="item-col">Nama Barang</th>
                    <th class="catalog-col">Kode</th>
                    <th class="qty-col">Jumlah</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($deliveryOrder->deliveryOrderDetail as $index => $detail)
                    <tr>
                        <td class="no-col">{{ $index + 1 }}</td>
                        <td>{{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}</td>
                        <td>{{ $detail->produk->kode ?? '-' }}</td>
                        <td>{{ number_format($detail->quantity, 2, ',', '.') }}</td>
                    </tr>
                @endforeach

                <!-- Add empty rows if needed -->
                @php
                    $currentRows = count($deliveryOrder->deliveryOrderDetail);
                    $desiredRows = 8;
                    $emptyRows = max(0, $desiredRows - $currentRows);
                @endphp

                @for ($i = 0; $i < $emptyRows; $i++)
                    <tr>
                        <td class="no-col">{{ $currentRows + $i + 1 }}</td>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                @endfor
            </tbody>
        </table>

        <!-- Notes Section -->
        <div class="notes-section">
            <div class="notes-title">Catatan:</div>
            <div class="notes-content">
                {{ $deliveryOrder->catatan ?? '-' }}
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-left">
                <div class="signature-title">Penerima,</div>
                <div class="signature-box">
                    <div style="height: 80px;"></div>
                    <div class="signature-name">
                        {{ $deliveryOrder->customer->company ?? ($deliveryOrder->customer->nama ?? '____________________') }}
                        <div class="recipient-sublabel">(Nama dan Cap Perusahaan)</div>
                    </div>
                </div>
            </div>
            <div class="signature-right">
                <div class="signature-title">Hormat Kami,</div>
                <div class="signature-box">
                    <div style="height: 80px;"></div>
                    <div class="signature-name">
                        PT Sinar Surya Semestaraya<br>
                        ({{ $deliveryOrder->user->name ?? '____________________' }})
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>

</html>
