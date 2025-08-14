<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Surat Jalan - {{ $deliveryOrder->nomor }} - PT Hidayah Cahaya Berkah</title>
    <style>
        :root {
            --hcb-blue: #002147;
            --hcb-green: #27ae60;
            --hcb-orange: #FF6E00;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background-color: #ffffff;
            font-size: 11px;
            line-height: 1.3;
            color: #1f2937;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .container {
            max-width: 100%;
            margin: 0;
            background-color: #ffffff;
            padding: 8mm 5mm;
            min-height: 100vh;
            position: relative;
            padding-bottom: 120px;
        }

        @page {
            size: 165mm 212mm;
            margin: 8mm 5mm;
        }

        @media print {
            body {
                background-color: #fff;
                font-size: 11px;
            }

            .container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                max-width: none;
                min-height: 100vh;
            }

            .footer-fixed {
                position: fixed;
                bottom: 5mm;
                left: 5mm;
                right: 5mm;
            }

            .watermark-bg {
                display: block !important;
                z-index: 1000 !important;
                position: fixed !important;
                opacity: 0.07 !important;
                pointer-events: none;
            }
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .custom-table th,
        .custom-table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        .custom-table th {
            background-color: var(--hcb-blue);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            font-size: 10px;
            padding: 10px 8px;
        }

        .custom-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .footer-fixed {
            position: fixed;
            bottom: 5mm;
            left: 5mm;
            right: 5mm;
            text-align: center;
            padding: 10px 0;
            border-top: 1px solid #e5e7eb;
            background-color: #f8fafc;
        }

        .signature-section {
            width: 100%;
            position: fixed;
            left: 0;
            bottom: 100px;
            padding: 0 12px;
            font-size: 10px;
            background: #fff;
            z-index: 10;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.04);
        }

        .signature-row {
            width: 95%;
            display: table;
            table-layout: fixed;
            margin-top: 15px;
        }

        .signature-item {
            display: table-cell;
            width: 45%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }

        .signature-line {
            height: 40px;
            border-bottom: 1px solid #cbd5e1;
            margin-bottom: 8px;
        }

        .signature-label {
            color: #334155;
            font-size: 10px;
            font-weight: bold;
        }

        .footer-thank-you {
            position: fixed;
            bottom: 30px;
            left: 0;
            right: 0;
            font-size: 13px;
            color: #334155;
            text-align: center;
            padding: 15px 20px;
            font-weight: bold;
            background-color: #f8fafc;
            border-top: 2px solid var(--hcb-orange);
        }

        .footer-decoration {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 25px;
            background-color: var(--hcb-blue);
        }

        .watermark-bg {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            text-align: center;
            z-index: 0;
            opacity: 0.05;
            font-size: 50px;
            font-weight: bold;
            color: var(--hcb-blue);
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }

        .decorative-line {
            height: 3px;
            background: linear-gradient(to right, var(--hcb-blue) 0%, var(--hcb-green) 50%, var(--hcb-orange) 100%);
            margin: 15px 0;
            border-radius: 2px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="watermark-bg">{{ strtoupper('PT HIDAYAH CAHAYA BERKAH') }}</div>
        <!-- Header Section -->
        <div style="display: table; width: 100%; margin-bottom: 0px; border-collapse: separate; border-spacing: 0;">
            <div style="display: table-row;">
                <!-- Logo and Company Info -->
                <div style="display: table-cell; vertical-align: middle; width: 65%; padding-right: 8px;">
                    <div style="display: flex; align-items: center; flex-wrap: nowrap; gap: 8px; min-height: 48px;">
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
                            <img src="{{ $logoBase64 }}" alt="HCB Logo"
                                style="height: 38px; width: auto; max-width: 60px; object-fit: contain; border-radius: 4px; flex-shrink: 0; vertical-align: middle; background-color: white; padding: 3px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                        @else
                            <div
                                style="height: 38px; width: 60px; border: 2px dashed var(--hcb-green); display: flex; align-items: center; justify-content: center; font-size: 11px; color: var(--hcb-green); background-color: #f0fdf4; border-radius: 4px; font-weight: 600; flex-shrink: 0;">
                                HCB</div>
                        @endif
                        <span
                            style="font-size: 13px; font-weight: 800; color: var(--hcb-blue); letter-spacing: 0.5px; white-space: nowrap; flex-shrink: 0; line-height: 1; vertical-align: middle;">PT
                            HIDAYAH CAHAYA BERKAH</span>
                    </div>
                </div>
                <!-- Surat Jalan Info -->
                <div style="display: table-cell; vertical-align: middle; width: 35%; text-align: right;">
                    <div
                        style="background-color: #f8fafc; padding: 8px; border-radius: 5px; border-left: 4px solid var(--hcb-orange); min-height: 48px;">
                        <div
                            style="font-size: 16px; font-weight: 700; color: var(--hcb-orange); margin-bottom: 4px; letter-spacing: 1px;">
                            SURAT JALAN</div>
                        <div style="font-size: 10px; line-height: 1.3; color: #374151;">
                            <div style="margin-bottom: 2px;"><span style="font-weight: 600; color: #1f2937;">No:</span>
                                <span
                                    style="color: var(--hcb-blue); font-weight: 600;">{{ $deliveryOrder->nomor }}</span>
                            </div>
                            <div style="margin-bottom: 2px;"><span
                                    style="font-weight: 600; color: #1f2937;">Tanggal:</span> <span
                                    style="color: #374151;">{{ \Carbon\Carbon::parse($deliveryOrder->tanggal)->format('d/m/Y') }}</span>
                            </div>
                            @if ($deliveryOrder->salesOrder)
                                <div style="margin-bottom: 2px;"><span style="font-weight: 600; color: #1f2937;">SO:
                                    </span> <span
                                        style="color: var(--hcb-blue); font-weight: 600;">{{ $deliveryOrder->salesOrder->nomor }}</span>
                                </div>
                            @endif
                            <div><span style="font-weight: 600; color: #1f2937;">Status:</span><span
                                    style="color: var(--hcb-green); font-weight: 700; text-transform: uppercase; background-color: #f0fdf4; padding: 2px 6px; border-radius: 3px; font-size: 9px;">{{ $deliveryOrder->status }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="decorative-line"></div>
        <!-- Company and Customer Info Section -->
        <div style="display: table; width: 100%; margin-bottom: 20px; border-collapse: separate; border-spacing: 0;">
            <div style="display: table-row;">
                <!-- DARI Section -->
                <div style="display: table-cell; vertical-align: top; width: 48%; padding-right: 30px;">
                    <div
                        style="font-size: 12px; font-weight: 700; color: var(--hcb-blue) ; letter-spacing: 1px; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 2px solid var(--hcb-blue);">
                        Dari:</div>
                    <div style="font-weight: 700; color: var(--hcb-blue); font-size: 13px; margin-bottom: 5px;">PT
                        HIDAYAH CAHAYA BERKAH</div>
                    <div style="font-size: 11px; line-height: 1.4; color: #374151;">Jl. Raya Keberkahan No. 789, Komplek
                        Islami<br>Jakarta Selatan 12560, Indonesia<br>Telp: (021) 7890-1234 | Fax: (021)
                        7890-1235</div>
                </div>
                <!-- DIKIRIM KEPADA Section -->
                <div style="display: table-cell; vertical-align: top; width: 52%;">
                    <div
                        style="font-size: 12px; font-weight: 700; color: var(--hcb-orange); letter-spacing: 1px; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 2px solid var(--hcb-orange);">
                        Dikirim Kepada:</div>
                    <div style="font-weight: 700; color: var(--hcb-blue); font-size: 13px; margin-bottom: 5px;">
                        {{ $deliveryOrder->customer->company ?? $deliveryOrder->customer->nama }}</div>
                    <div style="font-size: 11px; line-height: 1.4; color: #374151;">
                        {{ $deliveryOrder->alamat_pengiriman ?? ($deliveryOrder->customer->alamat ?? '-') }}<br>
                        @if ($deliveryOrder->customer->telepon)
                            Telp: {{ $deliveryOrder->customer->telepon }}
                            <br>
                        @endif
                        @if ($deliveryOrder->customer->email)
                            Email: {{ $deliveryOrder->customer->email }}<br>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Items Table -->
        <div
            style="font-size: 10px; font-weight: 600; color: var(--hcb-blue); margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
            Detail Pengiriman:</div>
        <div
            style="overflow-x: auto; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); border-radius: 0.25rem; margin-bottom: 1rem;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 8%; text-align: center;">No</th>
                        <th style="width: 40%;">Produk</th>
                        <th style="width: 8%; text-align: center;">Qty</th>
                        <th style="width: 10%; text-align: center;">Satuan</th>
                        <th style="width: 15%; text-align: right;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse ($deliveryOrder->details as $detail)
                        <tr>
                            <td style="text-align: center; font-weight: 600;">{{ $no++ }}</td>
                            <td>
                                <div style="font-weight: 500; color: #111827; margin-bottom: 2px;">
                                    {{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}</div>
                                @if ($detail->produk->deskripsi)
                                    <div style="color: #6b7280; font-size: 9px; line-height: 1.2;">
                                        {{ $detail->produk->deskripsi }}</div>
                                @endif
                            </td>
                            <td style="text-align: center;">{{ number_format($detail->quantity, 0) }}</td>
                            <td style="text-align: center;">{{ $detail->produk->satuan->nama ?? '-' }}</td>
                            <td style="text-align: right;">{{ $detail->keterangan ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada item untuk dikirim
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Signature Section -->
        <div class="signature-section clearfix">
            <div class="signature-row">
                <div class="signature-item">
                    <div class="signature-line"></div>
                    <p class="signature-label">{{ $deliveryOrder->user->name ?? 'Sales' }}</p>
                    <p style="font-size: 8px; margin: 2px 0; color: #64748b;">Sales Representative</p>
                </div>
                <div class="signature-item">
                    <div class="signature-line"></div>
                    <p style="font-size: 8px; margin: 2px 0; color: #64748b;">Penerima Barang</p>
                    <div style="height:18px; border-bottom:1px dashed #cbd5e1; margin:6px 0 2px 0;"></div>
                    <p style="font-size:8px; color:#64748b; margin:0;">(Nama penerima)</p>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div class="footer-thank-you"
            style="position: fixed; bottom: 30px; left: 0; right: 0; font-size: 13px; color: #334155; text-align: center; padding: 15px 20px; font-weight: bold; background-color: #f8fafc; border-top: 2px solid var(--hcb-orange);">
            Terima kasih atas kepercayaan Anda
        </div>
        <div class="footer-decoration"
            style="position: fixed; bottom: 0; left: 0; right: 0; height: 25px; background-color: var(--hcb-blue);">
        </div>
    </div>
</body>

</html>
