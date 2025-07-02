<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Penyesuaian Stok - {{ $penyesuaian->nomor }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            position: relative;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            opacity: 0.1;
            color: #FF0000;
            font-weight: bold;
            z-index: 0;
            text-align: center;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header p {
            font-size: 12px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table.info {
            margin-bottom: 25px;
        }

        table.info td {
            padding: 4px;
            vertical-align: top;
        }

        table.detail {
            border: 1px solid #000;
        }

        table.detail th,
        table.detail td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 10px;
            text-align: left;
        }

        table.detail th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .signatures {
            margin-top: 50px;
        }

        .signatures table {
            width: 100%;
        }

        .signatures td {
            vertical-align: top;
            text-align: center;
            width: 33.33%;
            padding: 5px;
        }

        .signatures .sign-line {
            border-bottom: 1px solid #000;
            margin-top: 20px;
            margin-bottom: 10px;
            width: 80%;
            display: inline-block;
        }

        .signatures div {
            margin: 5px 0;
        }

        .signatures small {
            font-size: 10px;
            color: #666;
        }

        .qr-signature {
            margin-top: 10px;
            text-align: center;
        }

        .qr-signature .qr-label {
            font-size: 8px;
            color: #666;
            margin-bottom: 3px;
        }

        .qr-signature img {
            border: 1px solid #ddd;
            padding: 2px;
        }

        .status-box {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            page-break-inside: avoid;
        }

        .status-processed {
            background-color: #f0f9ff;
            border: 1px solid #0ea5e9;
            color: #0369a1;
        }

        .status-draft {
            background-color: #fffbeb;
            border: 1px solid #f59e0b;
            color: #d97706;
        }

        .status-box h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
        }

        .status-box p {
            margin: 0;
            font-size: 11px;
        }

        .qr-signature {
            display: inline-block;
            text-align: center;
            margin: 5px;
        }

        .qr-signature .qr-label {
            font-size: 9px;
            color: #666;
            margin-bottom: 3px;
        }

        .document-qr {
            position: absolute;
            top: 20px;
            right: 20px;
            text-align: center;
        }

        .document-qr img {
            max-width: 100px;
            max-height: 100px;
        }

        .document-qr .qr-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }

        .summary {
            margin-bottom: 30px;
        }

        .summary table {
            width: 100%;
        }

        .summary td {
            padding: 5px;
        }

        .positive {
            color: green;
        }

        .negative {
            color: red;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            margin: 5px auto;
            display: block;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
            background: white;
        }

        .qr-code-small {
            width: 60px;
            height: 60px;
            margin: 5px auto;
            display: block;
            border: 1px solid #ddd;
            padding: 2px;
            background: white;
        }

        .header-with-qr {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .header-content {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .header-qr {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 120px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px dashed #ccc;
            padding-top: 5px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    @if ($penyesuaian->status == 'draft')
        <div class="watermark">
            {{ strpos($penyesuaian->nomor, 'DRAFT') !== false ? 'DRAFT PREVIEW' : 'DRAFT' }}
        </div>
    @endif

    <div class="header-with-qr">
        <div class="header-content">
            <h1>FORM PENYESUAIAN STOK</h1>
            <p>{{ setting('company_name', 'Sinar Surya') }} Inventory System</p>
        </div>

        {{-- Document QR Code in Header --}}
        @if (isset($qrCodes['document_qr']) && $qrCodes['document_qr'])
            <div class="header-qr">
                <img src="{{ $qrCodes['document_qr'] }}" alt="Document QR Code" class="qr-code">
                <div style="font-size: 8px; text-align: center; margin-top: 5px;">
                    <strong>Scan untuk Verifikasi</strong><br>
                    Dokumen Digital
                </div>
            </div>
        @endif
    </div>

    <table class="info">
        <tr>
            <td width="120px"><strong>Nomor</strong></td>
            <td width="10px">:</td>
            <td>{{ $penyesuaian->nomor }}</td>
            <td width="120px"><strong>Tanggal</strong></td>
            <td width="10px">:</td>
            <td>{{ \Carbon\Carbon::parse($penyesuaian->tanggal)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td><strong>Gudang</strong></td>
            <td>:</td>
            <td>{{ $penyesuaian->gudang->nama }}</td>
            <td><strong>Dibuat Oleh</strong></td>
            <td>:</td>
            <td>{{ $penyesuaian->user->name }}</td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td>:</td>
            <td>{{ ucfirst($penyesuaian->status) }}</td>
            <td><strong>Tanggal Dibuat</strong></td>
            <td>:</td>
            <td>{{ $penyesuaian->created_at->format('d F Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Catatan</strong></td>
            <td>:</td>
            <td colspan="4">{{ $penyesuaian->catatan ?? '-' }}</td>
        </tr>
    </table>

    <div class="summary">
        <h3>Ringkasan Penyesuaian</h3>
        <table>
            <tr>
                <td width="33.33%"><strong>Total Produk: </strong>{{ $totalItems }} produk</td>
                <td width="33.33%">
                    <strong>Produk Sesuai: </strong>{{ $countNoChange }} produk
                    ({{ $totalItems > 0 ? round(($countNoChange / $totalItems) * 100) : 0 }}%)
                </td>
                <td width="33.33%"></td>
            </tr>
            <tr>
                <td>
                    <strong>Produk Tambah: </strong>{{ $countPositive }} produk
                    @if ($totalPositive > 0)
                        ({{ $totalPositive }} {{ $unitDisplay }})
                    @endif
                </td>
                <td>
                    <strong>Produk Kurang: </strong>{{ $countNegative }} produk
                    @if ($totalNegative > 0)
                        ({{ $totalNegative }} {{ $unitDisplay }})
                    @endif
                </td>
                <td></td>
            </tr>
        </table>
    </div>

    <h3>Detail Produk</h3>
    <table class="detail">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Produk</th>
                <th width="15%">Stok Tercatat</th>
                <th width="15%">Stok Fisik</th>
                <th width="15%">Selisih</th>
                <th width="15%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penyesuaian->details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $detail->produk->kode }} - {{ $detail->produk->nama }}
                    </td>
                    <td>{{ number_format($detail->stok_tercatat, 2) }} {{ $detail->satuan->nama ?? '' }}</td>
                    <td>{{ number_format($detail->stok_fisik, 2) }} {{ $detail->satuan->nama ?? '' }}</td>
                    <td class="{{ $detail->selisih > 0 ? 'positive' : ($detail->selisih < 0 ? 'negative' : '') }}">
                        {{ $detail->selisih > 0 ? '+' : '' }}{{ number_format($detail->selisih, 2) }}
                        {{ $detail->satuan->nama ?? '' }}
                    </td>
                    <td>{{ $detail->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signatures">
        <table>
            <tr>
                <td>
                    <div><strong>Dibuat oleh:</strong></div>


                    {{-- QR Code for Creator --}}
                    @if (isset($qrCodes['created_qr']) && $qrCodes['created_qr'])
                        <div class="qr-signature">
                            <div class="qr-label">Tanda Tangan Digital</div>
                            <img src="{{ $qrCodes['created_qr'] }}" alt="Creator QR Code" class="qr-code-small">
                        </div>
                    @endif
                    <div class="sign-line"></div>
                    <div><strong>{{ $createdBy->name }}</strong></div>
                    <div><small>{{ $penyesuaian->created_at->format('d-m-Y H:i') }}</small></div>
                    <div><small>{{ $createdBy->email }}</small></div>
                </td>

                @if ($isProcessed && $processedBy)
                    <td>
                        <div><strong>Diproses oleh:</strong></div>


                        {{-- QR Code for Processor --}}
                        @if (isset($qrCodes['processed_qr']) && $qrCodes['processed_qr'])
                            <div class="qr-signature">
                                <div class="qr-label">Tanda Tangan Digital</div>
                                <img src="{{ $qrCodes['processed_qr'] }}" alt="Processor QR Code"
                                    class="qr-code-small">
                            </div>
                        @endif
                        <div class="sign-line"></div>
                        <div><strong>{{ $processedBy->name }}</strong></div>
                        <div><small>{{ $processedAt ? $processedAt->format('d-m-Y H:i') : '-' }}</small></div>
                        <div><small>{{ $processedBy->email }}</small></div>
                    </td>
                @else
                    <td>
                        <div><strong>Disetujui oleh:</strong></div>
                        <div class="sign-line"></div>

                        <div class="qr-signature">
                            <div class="qr-label">Menunggu Tanda Tangan</div>
                            <div
                                style="width: 60px; height: 60px; border: 1px dashed #ccc; display: inline-block; line-height: 60px; font-size: 10px; color: #999;">
                                QR</div>
                        </div>

                        <div>_____________________</div>
                        <div>
                            <small>{{ $penyesuaian->status === 'draft' ? 'Menunggu Persetujuan' : 'Manager/Supervisor' }}</small>
                        </div>
                    </td>
                @endif


            </tr>
        </table>
    </div>

    @if ($isProcessed)
        <div class="status-box status-processed">
            <h4><strong>Status: TELAH DIPROSES</strong></h4>
            <p>
                Penyesuaian stok ini telah diproses dan stok sistem telah disesuaikan dengan stok fisik pada
                {{ $processedAt ? $processedAt->format('d F Y \p\u\k\u\l H:i') : $penyesuaian->updated_at->format('d F Y \p\u\k\u\l H:i') }}
                oleh {{ $processedBy ? $processedBy->name : 'Sistem' }}.
            </p>
        </div>
    @elseif ($penyesuaian->status === 'draft')
        <div class="status-box status-draft">
            <h4><strong>Status: DRAFT</strong></h4>
            <p>
                Dokumen ini masih dalam status draft dan belum diproses. Penyesuaian stok belum diterapkan ke sistem.
            </p>
        </div>
    @endif

    <div class="footer">
        Dicetak pada: {{ now()->format('d F Y H:i:s') }} | {{ setting('company_name', 'Sinar Surya') }} ERP System
    </div>
</body>

</html>
