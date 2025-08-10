<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sales Order - {{ $salesOrder->nomor }} - PT Indo Atsaka Industri</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Watermark background - simplified */
        .watermark-bg {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            text-align: center;
            z-index: 0;
            opacity: 0.05;
            font-size: 60px;
            font-weight: bold;
            color: #2E86AB;
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
            user-select: none;
        }

        /* Simple table-based layout for better printing support */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #2E86AB;
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
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
        }

        .items-table th {
            background-color: #f0f8ff;
            color: #1565C0;
            font-weight: bold;
        }

        .section-title {
            background-color: #f0f8ff;
            padding: 4px;
            font-weight: bold;
            border-left: 2px solid #2E86AB;
            color: #1565C0;
        }

        .summary-table {
            border-collapse: collapse;
            width: 40%;
            margin-left: 60%;
            margin-bottom: 15px;
        }

        .summary-table td {
            padding: 3px;
            border-bottom: 1px solid #eee;
        }

        .total-row {
            font-weight: bold;
            border-top: 1px solid #D32F2F;
            background-color: #ffe8e8;
            color: #C62828;
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
            border-top: 1px solid #2E86AB;
            width: 70%;
            margin: 30px auto 8px auto;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            border-top: 1px solid #2E86AB;
            padding-top: 15px;
            background-color: #f8fbff;
        }

        .footer-text {
            font-size: 9px;
            color: #1565C0;
            margin-top: 10px;
            padding-bottom: 8px;
        }

        /* Simple utility classes */
        .text-right {
            text-align: right;
        }

        /* Print optimization */
        @page {
            size: A4;
            margin: 0.8cm;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }

            .watermark-bg {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="watermark-bg">{{ strtoupper('PT INDO ATSAKA INDUSTRI') }}</div>
    <!-- Header Section -->
    <table class="header-table">
        <tr style="margin-bottom: 10px;">
            <td style="width: 50%; vertical-align: middle;">
                @php
                    $logoPath = public_path('img/PTIndoatsakaindustri-2.jpeg');
                    $logoExists = file_exists($logoPath);
                    $logoBase64 = '';
                    if ($logoExists) {
                        $logoData = file_get_contents($logoPath);
                        $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
                    }
                @endphp

                @if ($logoExists && $logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Indo Atsaka Logo"
                        style="height: 50px; max-width: 200px; object-fit: contain;">
                @else
                    <div
                        style="height: 50px; width: 200px; border: 1px dashed #2E86AB; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #2E86AB; background-color: #f0f8ff;">
                        PT INDO ATSAKA INDUSTRI
                    </div>
                @endif
            </td>
            <td style="width: 50%; text-align: right; vertical-align: middle;">
                <h2 style="color: #2E86AB; margin: 0 0 5px 0;">SALES ORDER</h2>
                <div>
                    <strong>Nomor:</strong> {{ $salesOrder->nomor }}<br>
                    @if ($salesOrder->nomor_po)
                        <strong>No. PO:</strong> {{ $salesOrder->nomor_po }}<br>
                    @endif
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($salesOrder->tanggal)->format('d/m/Y') }}<br>
                    <strong>Status Pembayaran:</strong> <span
                        style="text-transform: uppercase; color: #D32F2F;">{{ $salesOrder->status_pembayaran }}</span><br>
                    <strong>Status Pengiriman:</strong> <span
                        style="text-transform: uppercase; color: #D32F2F;">{{ $salesOrder->status_pengiriman }}</span>
                    <p></p>
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
                    Jl. Raya Condet No. 15A, Balekambang<br>
                    Jakarta Timur 13530, Indonesia<br>
                    Telp. (021) 8087-6625 - (021) 8087-6626<br>
                    E-mail: info@indoatsaka.com<br>
                    sales@indoatsaka.com<br>
                    Website: www.indoatsaka.com
                </div>
            </td>
            <td>
                <div class="section-title">Customer</div>
                <div style="padding: 5px;">
                    <strong>{{ $salesOrder->customer->company ?? $salesOrder->customer->nama }}</strong><br>
                    {{ $salesOrder->customer->alamat ?? '-' }}<br>
                    @if ($salesOrder->customer->telepon)
                        Telp: {{ $salesOrder->customer->telepon }}<br>
                    @endif
                    @if ($salesOrder->customer->email)
                        Email: {{ $salesOrder->customer->email }}<br>
                    @endif
                    @if ($salesOrder->customer->kontak_person)
                        Kontak: {{ $salesOrder->customer->kontak_person }}
                        @if ($salesOrder->customer->no_hp_kontak)
                            ({{ $salesOrder->customer->no_hp_kontak }})
                        @endif
                        <br>
                    @endif
                    @if ($salesOrder->alamat_pengiriman)
                        <br><strong>Alamat Pengiriman:</strong><br>
                        {{ $salesOrder->alamat_pengiriman }}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    @if ($salesOrder->tanggal_kirim)
        <div style="border: 1px dashed #2E86AB; padding: 6px; margin-bottom: 12px; background-color: #f8fbff;">
            <strong style="color: #1565C0;">Tanggal Pengiriman:</strong>
            {{ \Carbon\Carbon::parse($salesOrder->tanggal_kirim)->format('d/m/Y') }}
        </div>
    @endif

    @if ($salesOrder->quotation_id)
        <div style="border: 1px dashed #2E86AB; padding: 6px; margin-bottom: 12px; background-color: #f8fbff;">
            <strong style="color: #1565C0;">Referensi Quotation:</strong>
            {{ $salesOrder->quotation->nomor ?? '-' }}
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
            @foreach ($salesOrder->details as $detail)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>
                        <strong>{{ $detail->produk->nama ?? 'Produk' }}</strong>
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
            <td class="text-right">Rp {{ number_format($salesOrder->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if ($salesOrder->diskon_nominal > 0)
            <tr>
                <td>Diskon ({{ number_format($salesOrder->diskon_persen, 1) }}%)</td>
                <td class="text-right">Rp {{ number_format($salesOrder->diskon_nominal, 0, ',', '.') }}</td>
            </tr>
        @endif
        @if ($salesOrder->ppn > 0)
            <tr>
                <td>PPN ({{ $salesOrder->ppn }}%)</td>
                <td class="text-right">Rp
                    {{ number_format($salesOrder->subtotal * ($salesOrder->ppn / 100), 0, ',', '.') }}</td>
            </tr>
        @endif
        @if ($salesOrder->ongkos_kirim > 0)
            <tr>
                <td>Ongkos Kirim</td>
                <td class="text-right">Rp {{ number_format($salesOrder->ongkos_kirim, 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr class="total-row">
            <td><strong>Total</strong></td>
            <td class="text-right"><strong>Rp {{ number_format($salesOrder->total, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <!-- Notes Section -->
    @if ($salesOrder->catatan)
        <div style="margin-bottom: 12px; border-left: 2px solid #2E86AB; padding-left: 8px; background-color: #f8fbff;">
            <strong style="color: #1565C0;">Catatan:</strong>
            <p style="margin: 3px 0;">{{ $salesOrder->catatan }}</p>
        </div>
    @endif

    <!-- Terms and Conditions -->
    <div style="margin-bottom: 12px; border-left: 2px solid #2E86AB; padding-left: 8px; background-color: #f8fbff;">
        <strong style="color: #1565C0;">Syarat & Ketentuan:</strong>
        @if ($salesOrder->syarat_ketentuan)
            <div style="margin-top: 3px;">{{ $salesOrder->syarat_ketentuan }}</div>
        @else
            <ol style="margin: 3px 0; padding-left: 18px; font-size: 11px;">
                <li>Sales Order ini berlaku sesuai tanggal yang tertera</li>
                <li>Harga belum termasuk pajak dan ongkos kirim, kecuali disebutkan secara eksplisit</li>
                <li>Pembayaran dilakukan sesuai kesepakatan kedua belah pihak</li>
                <li>Pengiriman dilakukan sesuai jadwal yang disepakati</li>
            </ol>
        @endif
    </div>

    @if ($salesOrder->terms_pembayaran)
        <div style="margin-bottom: 12px; border-left: 2px solid #2E86AB; padding-left: 8px; background-color: #f8fbff;">
            <strong style="color: #1565C0;">Terms Pembayaran:</strong>
            <div style="margin-top: 3px;">
                {{ $salesOrder->terms_pembayaran }}
                @if ($salesOrder->terms_pembayaran_hari)
                    ({{ $salesOrder->terms_pembayaran_hari }} hari)
                @endif
            </div>
        </div>
    @endif

    <!-- Signatures -->
    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-line"></div>
                <div><strong style="color: #1565C0;">{{ $salesOrder->user->name ?? 'Sales' }}</strong></div>
                <div style="color: #2E86AB;">Sales</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div><strong style="color: #1565C0;">Mengetahui</strong></div>
                <div style="color: #2E86AB;">Direktur</div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-text">
            <p>Dokumen ini dicetak secara digital pada {{ now()->format('d M Y, H:i') }} WIB |
                PT INDO ATSAKA INDUSTRI</p>
        </div>
    </div>
</body>

</html>
