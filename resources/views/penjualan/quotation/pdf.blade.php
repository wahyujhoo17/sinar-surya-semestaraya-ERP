<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quotation - {{ $quotation->nomor }}</title>
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

        /* Bundle styling */
        .bundle-header {
            background-color: #f8fafc !important;
            border-left: 3px solid #4a6fa5;
        }

        .bundle-item {
            background-color: #fefefe !important;
            font-size: 11px;
        }

        .bundle-item td {
            border-top: none !important;
            padding: 3px 6px !important;
        }

        .bundle-connector {
            color: #666;
            font-weight: bold;
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
    <div class="watermark-bg">{{ strtoupper(setting('company_name', 'SINAR SURYA SEMESTARAYA')) }}</div>
    <!-- Header Section -->
    <table class="header-table">
        <tr style="margin-bottom: 10px;">
            <td style="width: 50%; vertical-align: middle;">
                @php
                    $logoPath = public_path('img/logo_nama3.png');
                    $logoExists = file_exists($logoPath);
                    $logoBase64 = '';
                    if ($logoExists) {
                        $logoData = file_get_contents($logoPath);
                        $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
                    }
                @endphp

                @if ($logoExists && $logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Sinar Surya Logo"
                        style="height: 50px; max-width: 200px; object-fit: contain;">
                @else
                    <div
                        style="height: 50px; width: 200px; border: 1px dashed #4a6fa5; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #4a6fa5; background-color: #f0f4f8;">
                        PT SINAR SURYA SEMESTARAYA
                    </div>
                @endif
            </td>
            <td style="width: 50%; text-align: right; vertical-align: middle;">
                <h2 style="color: #4a6fa5; margin: 0 0 5px 0;">QUOTATION</h2>
                <div>
                    <strong>Nomor:</strong> {{ $quotation->nomor }}<br>
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($quotation->tanggal)->format('d/m/Y') }}<br>
                    <strong>Status:</strong> <span
                        style="text-transform: uppercase; color: #3498db;">{{ $quotation->status }}</span>
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
            @php
                $displayIndex = 1;
                $processedBundles = [];
            @endphp

            @foreach ($quotation->details as $index => $detail)
                @if ($detail->bundle_id && !in_array($detail->bundle_id, $processedBundles))
                    @php
                        $processedBundles[] = $detail->bundle_id;
                        // Find bundle header (the main bundle item)
                        $bundleHeader = $quotation->details
                            ->where('bundle_id', $detail->bundle_id)
                            ->where('is_bundle_item', '!=', true)
                            ->first();
                        // Find all bundle items
                        $bundleItems = $quotation->details
                            ->where('bundle_id', $detail->bundle_id)
                            ->where('is_bundle_item', true);

                        // Use first item if no clear header found
                        if (!$bundleHeader) {
                            $bundleHeader = $quotation->details->where('bundle_id', $detail->bundle_id)->first();
                        }
                    @endphp

                    {{-- Bundle Header --}}
                    <tr class="bundle-header">
                        <td class="text-center">{{ $displayIndex++ }}</td>
                        <td>
                            <strong>PAKET:
                                @if ($bundleHeader->bundle && $bundleHeader->bundle->nama)
                                    {{ $bundleHeader->bundle->nama }}
                                @elseif (str_contains($bundleHeader->deskripsi ?? '', 'Bundle:'))
                                    {{ str_replace('Bundle: ', '', $bundleHeader->deskripsi) }}
                                @else
                                    Paket Bundle #{{ $detail->bundle_id }}
                                @endif
                            </strong>
                            @if ($bundleHeader->bundle && $bundleHeader->bundle->kode)
                                <div style="font-size: 10px; color: #666;">Kode: {{ $bundleHeader->bundle->kode }}
                                </div>
                            @endif

                            {{-- Bundle Items Details in same row --}}
                            <div style="margin-top: 5px; padding: 5px; background-color: #f9f9f9; border-radius: 3px;">
                                <div style="font-size: 10px; color: #555; font-weight: bold;">Isi Paket:</div>
                                @foreach ($bundleItems as $bundleItem)
                                    <div style="font-size: 10px; color: #666; margin-left: 10px;">
                                        •
                                        @if ($bundleItem->produk && $bundleItem->produk->nama)
                                            {{ $bundleItem->produk->nama }}
                                        @elseif ($bundleItem->deskripsi)
                                            {{ preg_replace('/^└─\s*/', '', preg_replace('/\s*\(dari bundle.*\)$/', '', $bundleItem->deskripsi)) }}
                                        @else
                                            Item Bundle
                                        @endif
                                        (@if (floor($bundleItem->quantity) == $bundleItem->quantity)
                                            {{ number_format($bundleItem->quantity, 0, ',', '.') }}@else{{ number_format($bundleItem->quantity, 2, ',', '.') }}
                                        @endif
                                        @if ($bundleItem->satuan && $bundleItem->satuan->nama)
                                            {{ $bundleItem->satuan->nama }}
                                        @else
                                            pcs
                                        @endif)
                                        @if ($bundleItem->produk && $bundleItem->produk->kode)
                                            - {{ $bundleItem->produk->kode }}
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="text-center">
                            @if (floor($bundleHeader->quantity) == $bundleHeader->quantity)
                                {{ number_format($bundleHeader->quantity, 0, ',', '.') }}
                            @else
                                {{ number_format($bundleHeader->quantity, 2, ',', '.') }}
                            @endif
                        </td>
                        <td class="text-center">Paket</td>
                        <td class="text-right">Rp
                            {{ number_format($bundleHeader->bundle->harga_bundle ?? 0, 0, ',', '.') }}</td>
                        <td class="text-center">-</td>
                        <td class="text-right">Rp
                            {{ number_format(($bundleHeader->bundle->harga_bundle ?? 0) * $bundleHeader->quantity, 0, ',', '.') }}
                        </td>
                    </tr>
                @elseif (!$detail->bundle_id)
                    {{-- Regular Product (not part of any bundle) --}}
                    <tr>
                        <td class="text-center">{{ $displayIndex++ }}</td>
                        <td>
                            <strong>
                                @if ($detail->produk && $detail->produk->nama)
                                    {{ $detail->produk->nama }}
                                @elseif ($detail->deskripsi)
                                    {{ $detail->deskripsi }}
                                @else
                                    Produk tidak ditemukan
                                @endif
                            </strong>
                            @if ($detail->produk && $detail->produk->kode)
                                <div style="font-size: 10px;">{{ $detail->produk->kode }}</div>
                            @endif
                            @if ($detail->deskripsi && $detail->produk && $detail->produk->nama != $detail->deskripsi)
                                <div style="font-size: 10px; margin-top: 3px;">{{ $detail->deskripsi }}</div>
                            @endif
                        </td>
                        <td class="text-center">
                            @if (floor($detail->quantity) == $detail->quantity)
                                {{ number_format($detail->quantity, 0, ',', '.') }}
                            @else
                                {{ number_format($detail->quantity, 2, ',', '.') }}
                            @endif
                        </td>
                        <td class="text-center">{{ $detail->satuan->nama ?? 'pcs' }}</td>
                        <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if ($detail->diskon_persen > 0)
                                {{ number_format($detail->diskon_persen, 1, ',', '.') }}%
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endif
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
            style="margin-bottom: 15px; border-left: 3px solid #4a6fa5; padding-left: 10px; background-color: #f8fafc;">
            <strong style="color: #2c3e50;">Catatan:</strong>
            <p>{{ $quotation->catatan }}</p>
        </div>
    @endif

    <!-- Terms and Conditions -->
    <div style="margin-bottom: 15px; border-left: 3px solid #4a6fa5; padding-left: 10px; background-color: #f8fafc;">
        <strong style="color: #2c3e50;">Syarat & Ketentuan:</strong>
        @if ($quotation->syarat_ketentuan)
            <div style="margin-top: 5px; white-space: pre-line;">{!! nl2br(e($quotation->syarat_ketentuan)) !!}</div>
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
        <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 15px;">
            <img src="{{ public_path('img/atsaka.webp') }}" alt="Atsaka Logo" style="height: 55px; margin: 0 30px;">
            <img src="{{ public_path('img/polylab.webp') }}" alt="Polylab Logo" style="height: 35px; margin: 0 25px;">
            <img src="{{ public_path('img/sumbunesia.webp') }}" alt="Sumbunesia Logo"
                style="height: 55px; margin: 0 30px;">
        </div>
        <div class="footer-text">
            <p>Dokumen ini dicetak secara digital pada {{ now()->format('d M Y, H:i') }} WIB |
                {{ setting('company_name', 'PT. SINAR SURYA SEMESTARAYA') }}</p>
        </div>
    </div>
</body>

</html>
