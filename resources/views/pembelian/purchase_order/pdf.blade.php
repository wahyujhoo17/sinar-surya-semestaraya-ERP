<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Purchase Order - {{ $purchaseOrder->nomor }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: black;
            margin: 0;
            padding: 0;
        }

        /* Simple table-based layout for better printing support */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #1f5da0;
            margin-bottom: 25px;
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
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }

        .items-table th {
            background-color: #f0f0f0;
        }

        .section-title {
            background-color: #f0f0f0;
            padding: 5px;
            font-weight: bold;
            border-left: 3px solid #1f5da0;
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
            border-top: 1px solid #333;
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
            border-top: 1px solid #333;
            width: 80%;
            margin: 50px auto 10px auto;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 30px;
        }

        /* Simple utility classes */
        .text-right {
            text-align: right;
        }

        /* Print-specific styling */
        @page {
            size: A4;
            margin: 1cm;
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td style="width: 50%; vertical-align: middle;">
                <img src="{{ public_path('img/logo_nama3.png') }}" alt="Sinar Surya Logo"
                    onerror="this.src='{{ public_path('img/logo-default.png') }}';" style="height: 60px;">
            </td>
            <td style="width: 50%; text-align: right; vertical-align: middle;">
                <h2 style="color: #1f5da0; margin: 0 0 5px 0;">PURCHASE ORDER</h2>
                <div>
                    <strong>Nomor:</strong> {{ $purchaseOrder->nomor }}<br>
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($purchaseOrder->tanggal)->format('d/m/Y') }}<br>
                    <strong>Status:</strong> <span
                        style="text-transform: uppercase;">{{ $purchaseOrder->status }}</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Company and Supplier Info Section -->
    <table class="info-table">
        <tr>
            <td>
                <div class="section-title">Info Perusahaan</div>
                <div style="padding: 5px;">
                    <strong>PT. SINAR SURYA</strong><br>
                    Jl. Condet Raya No. 6 Balekambang<br>
                    Jakarta Timur 13530<br>
                    Telp. (021) 80876624 - 80876642<br>
                    E-mail: admin@kliksinarsurya.com<br>
                    sinar.surya@hotmail.com<br>
                    sinarsurya.sr@gmail.com
                </div>
            </td>
            <td>
                <div class="section-title">Supplier</div>
                <div style="padding: 5px;">
                    <strong>{{ $purchaseOrder->supplier->nama }}</strong><br>
                    {{ $purchaseOrder->supplier->alamat ?? '-' }}<br>
                    @if ($purchaseOrder->supplier->telepon)
                        Telp: {{ $purchaseOrder->supplier->telepon }}<br>
                    @endif
                    @if ($purchaseOrder->supplier->email)
                        Email: {{ $purchaseOrder->supplier->email }}<br>
                    @endif
                    @if ($purchaseOrder->supplier->nama_kontak)
                        Kontak: {{ $purchaseOrder->supplier->nama_kontak }}
                        @if ($purchaseOrder->supplier->no_hp)
                            ({{ $purchaseOrder->supplier->no_hp }})
                        @endif
                        <br>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Shipping Info if available -->
    @if ($purchaseOrder->alamat_pengiriman)
        <div style="border: 1px dashed #999; padding: 8px; margin-bottom: 15px; background-color: #f9f9f9;">
            <strong>Alamat Pengiriman:</strong> {{ $purchaseOrder->alamat_pengiriman }}
            @if ($purchaseOrder->tanggal_pengiriman)
                <br><strong>Tanggal Pengiriman:</strong>
                {{ \Carbon\Carbon::parse($purchaseOrder->tanggal_pengiriman)->format('d/m/Y') }}
            @endif
        </div>
    @endif

    <!-- Purchase Request reference if available -->
    @if ($purchaseOrder->purchaseRequest)
        <div style="margin-bottom: 15px;">
            <strong>Berdasarkan Permintaan Pembelian:</strong> {{ $purchaseOrder->purchaseRequest->nomor }}
        </div>
    @endif

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
            @php $no = 1; @endphp
            @foreach ($purchaseOrder->details as $detail)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>
                        <strong>{{ $detail->nama_item ?? ($detail->produk->nama ?? 'Produk') }}</strong>
                        @if ($detail->deskripsi)
                            <br><span style="font-size: 10px;">{{ $detail->deskripsi }}</span>
                        @endif
                    </td>
                    <td>{{ number_format($detail->quantity, 0) }}</td>
                    <td>{{ $detail->satuan->nama ?? '-' }}</td>
                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td>
                        @if ($detail->diskon_persen > 0)
                            {{ number_format($detail->diskon_persen, 1) }}%
                        @endif
                        @if ($detail->diskon_nominal > 0)
                            Rp {{ number_format($detail->diskon_nominal, 0, ',', '.') }}
                        @endif
                    </td>
                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary Table -->
    <table class="summary-table">
        <tr>
            <td>Subtotal</td>
            <td class="text-right">Rp {{ number_format($purchaseOrder->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if ($purchaseOrder->diskon_nominal > 0)
            <tr>
                <td>Diskon ({{ number_format($purchaseOrder->diskon_persen, 1) }}%)</td>
                <td class="text-right">Rp {{ number_format($purchaseOrder->diskon_nominal, 0, ',', '.') }}</td>
            </tr>
        @endif
        @if ($purchaseOrder->ppn > 0)
            <tr>
                <td>PPN ({{ $purchaseOrder->ppn }}%)</td>
                <td class="text-right">Rp
                    {{ number_format($purchaseOrder->subtotal * ($purchaseOrder->ppn / 100), 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr class="total-row">
            <td><strong>Total</strong></td>
            <td class="text-right"><strong>Rp {{ number_format($purchaseOrder->total, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <!-- Notes Section -->
    @if ($purchaseOrder->catatan)
        <div style="margin-bottom: 15px;">
            <strong>Catatan:</strong>
            <p>{{ $purchaseOrder->catatan }}</p>
        </div>
    @endif

    <!-- Terms and Conditions -->
    <div style="margin-bottom: 15px;">
        <strong>Syarat & Ketentuan:</strong>
        @if ($purchaseOrder->syarat_ketentuan)
            <div style="margin-top: 5px;">{{ $purchaseOrder->syarat_ketentuan }}</div>
        @else
            <ol style="margin-top: 5px; padding-left: 20px;">
                <li>Barang harus sesuai dengan spesifikasi yang tercantum dalam PO</li>
                <li>Pembayaran akan dilakukan setelah barang diterima dengan kondisi baik</li>
                <li>Pengiriman harap disertai dengan surat jalan dan invoice</li>
            </ol>
        @endif
    </div>

    <!-- Signatures -->
    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-line"></div>
                <div><strong>{{ $purchaseOrder->user->name ?? 'Purchasing' }}</strong></div>
                <div>Purchasing</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div><strong>Mengetahui</strong></div>
                <div>Direktur</div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara digital pada {{ now()->format('d M Y, H:i') }} WIB</p>
    </div>
</body>

</html>
