<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quotation - {{ $quotation->nomor }} - PT Hidayah Cahaya Berkah</title>
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
            color: #27ae60;
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }

        /* Simple table-based layout for better printing support */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #27ae60;
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
            background-color: #e8f5e8;
            color: #2c3e50;
        }

        .section-title {
            background-color: #e8f5e8;
            padding: 5px;
            font-weight: bold;
            border-left: 3px solid #27ae60;
            color: #2c3e50;
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
            border-top: 1px solid #27ae60;
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

        .footer {
            text-align: center;
            margin-top: 45px;
            border-top: 1.5px solid #e0e6ed;
            padding-top: 22px;
            background-color: #f9fafb;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.03);
        }

        .footer-text {
            font-size: 9.5px;
            color: #6b7280;
            margin-top: 15px;
            padding-bottom: 12px;
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
    <div class="watermark-bg">{{ strtoupper('PT HIDAYAH CAHAYA BERKAH') }}</div>
    <!-- Header Section -->
    <table class="header-table">
        <tr style="margin-bottom: 10px;">
            <td style="width: 50%; vertical-align: middle;">
                @php
                    $logoPath = public_path('img/LogoHCB-0.jpeg');
                    $logoExists = file_exists($logoPath);
                    $logoBase64 = '';
                    if ($logoExists) {
                        $logoData = file_get_contents($logoPath);
                        $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
                    }
                @endphp

                @if ($logoExists && $logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Hidayah Cahaya Berkah Logo"
                        style="height: 50px; max-width: 200px; object-fit: contain;">
                @else
                    <div
                        style="height: 50px; width: 200px; border: 1px dashed #16a085; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #16a085; background-color: #f0fff4;">
                        PT HIDAYAH CAHAYA BERKAH
                    </div>
                @endif
            </td>
            <td style="width: 50%; text-align: right; vertical-align: middle;">
                <h2 style="color: #27ae60; margin: 0 0 5px 0;">QUOTATION</h2>
                <div>
                    <strong>Nomor:</strong> {{ $quotation->nomor }}<br>
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($quotation->tanggal)->format('d/m/Y') }}<br>
                    <strong>Status:</strong> <span
                        style="text-transform: uppercase; color: #27ae60;">{{ $quotation->status }}</span>
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
                    <strong>PT HIDAYAH CAHAYA BERKAH</strong><br>
                    Jl. Raya Bekasi No. 88, Cakung<br>
                    Jakarta Timur 13910, Indonesia<br>
                    Telp. (021) 4608-7890 - (021) 4608-7891<br>
                    E-mail: info@hidayahcahaya.com<br>
                    sales@hidayahcahaya.com<br>
                    Website: www.hidayahcahayaberkah.com
                </div>
            </td>
            <td>
                <div class="section-title">Customer</div>
                <div style="padding: 5px;">
                    <strong>{{ $quotation->customer->company ?? $quotation->customer->nama }}</strong><br>
                    {{ $quotation->customer->alamat ?? '-' }}<br>
                    @if ($quotation->customer->telepon)
                        Telp: {{ $quotation->customer->telepon }}<br>
                    @endif
                    @if ($quotation->customer->email)
                        Email: {{ $quotation->customer->email }}<br>
                    @endif
                    @if ($quotation->customer->kontak_person)
                        Kontak: {{ $quotation->customer->kontak_person }}
                        @if ($quotation->customer->no_hp_kontak)
                            ({{ $quotation->customer->no_hp_kontak }})
                        @endif
                        <br>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    @if ($quotation->periode_start && $quotation->periode_end)
        <div style="border: 1px dashed #b8c4d6; padding: 8px; margin-bottom: 15px; background-color: #f8fafc;">
            <strong>Periode Penawaran:</strong>
            {{ \Carbon\Carbon::parse($quotation->periode_start)->format('d/m/Y') }} s/d
            {{ \Carbon\Carbon::parse($quotation->periode_end)->format('d/m/Y') }}
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
            @foreach ($quotation->details as $detail)
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
            <td class="text-right">Rp {{ number_format($quotation->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if ($quotation->diskon_nominal > 0)
            <tr>
                <td>Diskon ({{ number_format($quotation->diskon_persen, 1) }}%)</td>
                <td class="text-right">Rp {{ number_format($quotation->diskon_nominal, 0, ',', '.') }}</td>
            </tr>
        @endif
        @if ($quotation->ppn > 0)
            <tr>
                <td>PPN ({{ $quotation->ppn }}%)</td>
                <td class="text-right">Rp
                    {{ number_format($quotation->subtotal * ($quotation->ppn / 100), 0, ',', '.') }}</td>
            </tr>
        @endif
        @if ($quotation->ongkos_kirim > 0)
            <tr>
                <td>Ongkos Kirim</td>
                <td class="text-right">Rp {{ number_format($quotation->ongkos_kirim, 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr class="total-row">
            <td><strong>Total</strong></td>
            <td class="text-right"><strong>Rp {{ number_format($quotation->total, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <!-- Notes Section -->
    @if ($quotation->catatan)
        <div
            style="margin-bottom: 15px; border-left: 3px solid #27ae60; padding-left: 10px; background-color: #f8fafc;">
            <strong style="color: #2c3e50;">Catatan:</strong>
            <p>{{ $quotation->catatan }}</p>
        </div>
    @endif

    <!-- Terms and Conditions -->
    <div style="margin-bottom: 15px; border-left: 3px solid #27ae60; padding-left: 10px; background-color: #f8fafc;">
        <strong style="color: #2c3e50;">Syarat & Ketentuan:</strong>
        @if ($quotation->syarat_ketentuan)
            <div style="margin-top: 5px;">{{ $quotation->syarat_ketentuan }}</div>
        @else
            <ol style="margin-top: 5px; padding-left: 20px;">
                <li>Penawaran ini berlaku selama periode yang tertera di atas</li>
                <li>Harga belum termasuk pajak dan ongkos kirim, kecuali disebutkan secara eksplisit</li>
                <li>Pembayaran dilakukan sesuai kesepakatan kedua belah pihak</li>
                <li>Pengiriman dilakukan setelah pembayaran diterima (kecuali untuk pelanggan dengan terms tertentu)
                </li>
            </ol>
        @endif
    </div>

    <!-- Signatures -->
    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-line"></div>
                <div><strong style="color: #2c3e50;">{{ $quotation->user->name ?? 'Sales' }}</strong></div>
                <div style="color: #7f8c8d;">Sales</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div><strong style="color: #2c3e50;">Mengetahui</strong></div>
                <div style="color: #7f8c8d;">Direktur</div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-text">
            <p>Dokumen ini dicetak secara digital pada {{ now()->format('d M Y, H:i') }} WIB |
                PT HIDAYAH CAHAYA BERKAH</p>
        </div>
    </div>
</body>

</html>
