<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Purchase Order PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 30px;
            color: #333;
            background-color: #fff;
            position: relative;
        }

        /* Page Border */
        body:before {
            content: '';
            position: absolute;
            top: 10px;
            right: 10px;
            bottom: 10px;
            left: 10px;
            border: 1px solid #0055aa;
            z-index: -2;
        }

        /* Inner Border */
        body:after {
            content: '';
            position: absolute;
            top: 12px;
            right: 12px;
            bottom: 12px;
            left: 12px;
            border: 1px solid #ddd;
            z-index: -2;
        }

        .header {
            padding-bottom: 15px;
            border-bottom: 3px double #0055aa;
            margin-bottom: 25px;
            width: 100%;
            position: relative;
        }

        .logo-container {
            text-align: center;
        }

        .logo {
            width: 100%;
            max-width: 450px;
            height: auto;
            margin: 0 auto;
            display: block;
            filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.2));
        }

        .document-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 30px 0;
            color: #0052cc;
            text-transform: uppercase;
            letter-spacing: 3px;
            position: relative;
            text-shadow: 0 1px 2px rgba(0, 85, 170, 0.2);
        }

        .document-meta {
            background: linear-gradient(to bottom, #fafbff, #f0f5ff);
            border: 1px solid #d0d9e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .document-meta:before {
            content: "";
            position: absolute;
            top: -1px;
            left: -1px;
            right: -1px;
            height: 8px;
            background: linear-gradient(90deg, #1a73e8, #0052cc);
            border-radius: 8px 8px 0 0;
        }

        .document-meta table {
            border: none;
            width: 100%;
        }

        .document-meta td {
            border: none;
            padding: 6px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            color: #0055aa;
            text-transform: uppercase;
            font-size: 11px;
        }

        .value {
            font-weight: 600;
        }

        table.items {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 25px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #c8d0e0;
        }

        table.items th {
            background: linear-gradient(135deg, #1a73e8, #0052cc);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.6);
            padding: 14px 10px;
            text-align: left;
            font-weight: 800;
            border: none;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 1px;
        }

        table.items td {
            border: none;
            border-bottom: 1px solid #e0e6f0;
            padding: 13px 10px;
            vertical-align: middle;
        }

        table.items tr:nth-child(odd) {
            background-color: #f7faff;
        }

        table.items tr:nth-child(even) {
            background-color: #ffffff;
        }

        table.items tr:hover {
            background-color: #e6f0ff;
            transition: background-color 0.3s ease;
        }

        table.items tfoot {
            font-weight: bold;
        }

        table.items tfoot th,
        table.items tfoot td {
            background: linear-gradient(to bottom, #f0f5ff, #e0ebff);
            border-top: 2px solid #1a73e8;
            text-align: right;
            padding: 14px 10px;
            color: #0052cc;
            font-weight: 800;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .notes {
            margin-top: 30px;
            background-color: #f9fbff;
            border-left: 6px solid #1a73e8;
            padding: 18px 22px;
            border-radius: 0 8px 8px 0;
            position: relative;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
        }

        .notes:after {
            content: "✎";
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 18px;
            color: #ccc;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #0055aa;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 11px;
        }

        .terms {
            margin-top: 20px;
            background-color: #f5f7fa;
            border-top: 3px solid #d8e0ed;
            padding: 18px 22px;
            position: relative;
        }

        .terms-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #0055aa;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 11px;
        }

        .summary-box {
            margin-top: 30px;
            background: linear-gradient(to bottom, #fafcff, #f0f5ff);
            border: 1px solid #d0d9e6;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
            float: right;
            width: 35%;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e6f0;
        }

        .summary-row.total {
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
            padding-top: 12px;
            color: #0052cc;
        }

        .footer {
            margin-top: 50px;
            width: 100%;
            page-break-inside: avoid;
            position: relative;
            clear: both;
        }

        .footer:before {
            content: '';
            display: block;
            width: 100%;
            height: 1px;
            background: linear-gradient(to right, transparent, #0055aa, transparent);
            margin-bottom: 20px;
        }

        .signature-section {
            width: 100%;
            display: table;
            padding-top: 15px;
        }

        .signature-box {
            float: left;
            width: 30%;
            text-align: center;
            margin-right: 3%;
            padding: 15px 0;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #0055aa;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 11px;
        }

        .signature-line {
            margin-top: 70px;
            border-top: 1px solid #333;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .signature-name {
            margin-top: 8px;
            font-weight: bold;
        }

        .signature-date {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            font-weight: bold;
            letter-spacing: 10px;
            color: rgba(0, 85, 170, 0.03);
            z-index: -1;
            white-space: nowrap;
        }

        .page-number {
            text-align: right;
            font-size: 10px;
            color: #777;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .important-info {
            background-color: rgba(0, 85, 170, 0.05);
            border-radius: 5px;
            padding: 2px 5px;
            display: inline-block;
        }

        .supplier-info {
            margin-top: 15px;
            background-color: #f9fbff;
            border: 1px solid #d0d9e6;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .supplier-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #0055aa;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 11px;
        }

        .supplier-data-table {
            width: 100%;
            border: none;
        }

        .supplier-data-table td {
            padding: 3px 5px;
            vertical-align: top;
            border: none;
        }

        .supplier-data-table .supplier-label {
            font-weight: bold;
            color: #555;
            width: 80px;
        }

        .supplier-data-table .supplier-value {
            color: #333;
        }

        .supplier-name {
            font-weight: bold;
            font-size: 14px;
            color: #0055aa;
            margin-bottom: 8px;
        }

        .supplier-contact {
            margin-top: 5px;
            padding-top: 5px;
            border-top: 1px dashed #d0d9e6;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-draft {
            background-color: #f0f0f0;
            color: #666;
        }

        .status-diproses {
            background-color: #e3f2fd;
            color: #0277bd;
        }

        .status-dikirim {
            background-color: #fff8e1;
            color: #ff8f00;
        }

        .status-selesai {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-dibatalkan {
            background-color: #ffebee;
            color: #c62828;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
            text-align: right;
        }

        .sku-info {
            font-size: 10px;
            color: #555;
            margin-top: 3px;
            display: block;
        }

        .item-name {
            font-weight: bold;
            color: #333;
            display: block;
        }
    </style>
</head>

<body>
    <div class="watermark">SINAR SURYA</div>

    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('img/logo_nama3.png') }}" class="logo" alt="PT Sinar Surya Semestaraya">
        </div>
    </div>

    <div class="document-title">PURCHASE ORDER</div>

    <div class="document-meta">
        <table>
            <tr>
                <td width="15%" class="label">Nomor PO</td>
                <td width="2%">:</td>
                <td width="33%" class="value">
                    <span class="important-info">{{ $purchaseOrder->nomor }}</span>
                </td>
                <td width="15%" class="label">Status</td>
                <td width="2%">:</td>
                <td class="value">
                    <span class="status-badge status-{{ $purchaseOrder->status }}">
                        @if ($purchaseOrder->status == 'draft')
                            Draft
                        @elseif ($purchaseOrder->status == 'diproses')
                            Diproses
                        @elseif ($purchaseOrder->status == 'dikirim')
                            Dikirim
                        @elseif ($purchaseOrder->status == 'selesai')
                            Selesai
                        @elseif ($purchaseOrder->status == 'dibatalkan')
                            Dibatalkan
                        @endif
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Tanggal PO</td>
                <td>:</td>
                <td class="value">{{ \Carbon\Carbon::parse($purchaseOrder->tanggal)->format('d F Y') }}</td>
                <td class="label">Pembuat PO</td>
                <td>:</td>
                <td class="value">{{ $purchaseOrder->user->name }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Pengiriman</td>
                <td>:</td>
                <td class="value">
                    {{ $purchaseOrder->tanggal_pengiriman ? \Carbon\Carbon::parse($purchaseOrder->tanggal_pengiriman)->format('d F Y') : '-' }}
                </td>
                <td class="label">PR Ref.</td>
                <td>:</td>
                <td class="value">
                    {{ $purchaseOrder->purchaseRequest ? $purchaseOrder->purchaseRequest->nomor : '-' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="supplier-info">
        <div class="supplier-title">Supplier Information</div>
        <div class="supplier-name">{{ $purchaseOrder->supplier->nama }}</div>

        <table class="supplier-data-table">
            <tr>
                <td class="supplier-label">Alamat</td>
                <td class="supplier-value">{{ $purchaseOrder->supplier->alamat }}</td>
            </tr>
            <tr>
                <td class="supplier-label">Telepon</td>
                <td class="supplier-value">{{ $purchaseOrder->supplier->telp ?? '-' }}</td>
            </tr>
            <tr>
                <td class="supplier-label">Email</td>
                <td class="supplier-value">{{ $purchaseOrder->supplier->email ?? '-' }}</td>
            </tr>
            <tr>
                <td class="supplier-label">Contact</td>
                <td class="supplier-value">{{ $purchaseOrder->supplier->kontak ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Item</th>
                <th width="20%">Deskripsi</th>
                <th width="8%">Qty</th>
                <th width="7%">Satuan</th>
                <th width="10%">Harga</th>
                <th width="8%">Diskon</th>
                <th width="15%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchaseOrder->details as $i => $detail)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        @php
                            // Ensure the item name is displayed correctly
                            $itemName = $detail->nama_item;
                            if (empty(trim($itemName)) && $detail->produk) {
                                $itemName = $detail->produk->nama;
                            }
                        @endphp
                        <span class="item-name">{{ $itemName }}</span>
                        @if ($detail->produk && $detail->produk->kode)
                            <span class="sku-info">{{ $detail->produk->kode }}</span>
                        @endif
                    </td>
                    <td>{{ $detail->deskripsi ?? '-' }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ $detail->satuan->nama }}</td>
                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td>
                        @if ($detail->diskon_nominal > 0)
                            {{ $detail->diskon_persen }}% <br>
                            <small>(Rp {{ number_format($detail->diskon_nominal, 0, ',', '.') }})</small>
                        @else
                            -
                        @endif
                    </td>
                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="clearfix">
        <div class="summary-box">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($purchaseOrder->subtotal, 0, ',', '.') }}</span>
            </div>
            @if ($purchaseOrder->diskon_nominal > 0)
                <div class="summary-row">
                    <span>Diskon ({{ $purchaseOrder->diskon_persen }}%):</span>
                    <span>Rp {{ number_format($purchaseOrder->diskon_nominal, 0, ',', '.') }}</span>
                </div>
            @endif
            @if ($purchaseOrder->ppn > 0)
                <div class="summary-row">
                    <span>PPN ({{ $purchaseOrder->ppn }}%):</span>
                    <span>Rp
                        {{ number_format(($purchaseOrder->subtotal - $purchaseOrder->diskon_nominal) * ($purchaseOrder->ppn / 100), 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="summary-row total">
                <span>Total:</span>
                <span>Rp {{ number_format($purchaseOrder->total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    @if ($purchaseOrder->alamat_pengiriman)
        <div class="notes" style="clear: both; margin-top: 200px;">
            <div class="notes-title">Alamat Pengiriman:</div>
            <div>{{ $purchaseOrder->alamat_pengiriman }}</div>
        </div>
    @endif

    @if ($purchaseOrder->catatan)
        <div class="notes">
            <div class="notes-title">Catatan:</div>
            <div>{{ $purchaseOrder->catatan }}</div>
        </div>
    @endif

    @if ($purchaseOrder->syarat_ketentuan)
        <div class="terms">
            <div class="terms-title">Syarat & Ketentuan:</div>
            <div>{!! nl2br(e($purchaseOrder->syarat_ketentuan)) !!}</div>
        </div>
    @endif

    <div class="footer">
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">Prepared By</div>
                <div class="signature-line"></div>
                <div class="signature-name">{{ $purchaseOrder->user->name }}</div>
                <div class="signature-date">{{ \Carbon\Carbon::parse($purchaseOrder->tanggal)->format('d/m/Y') }}</div>
            </div>
            <div class="signature-box">
                <div class="signature-title">Approved By</div>
                <div class="signature-line"></div>
                <div class="signature-name">Manager Purchasing</div>
                <div class="signature-date">Tanggal: ___/___/______</div>
            </div>
            <div class="signature-box">
                <div class="signature-title">Supplier Confirmation</div>
                <div class="signature-line"></div>
                <div class="signature-name">{{ $purchaseOrder->supplier->nama }}</div>
                <div class="signature-date">Tanggal: ___/___/______</div>
            </div>
        </div>
    </div>

    <div class="page-number">
        Dokumen ini dibuat oleh sistem ERP PT Sinar Surya Semestaraya | Dicetak pada:
        {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>
</body>

</html>
