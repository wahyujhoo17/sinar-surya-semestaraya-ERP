<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quotation - {{ $quotation->nomor }} - PT Hidayah Cahaya Berkah</title>
    <style>
        /* Custom colors for PT Hidayah Cahaya Berkah */
        :root {
            --hcb-blue: #002147;
            --hcb-green: #27ae60;
            --hcb-orange: #FF6E00;
        }

        .text-hcb-blue {
            color: var(--hcb-blue);
        }

        .bg-hcb-blue {
            background-color: var(--hcb-blue);
        }

        .text-hcb-green {
            color: var(--hcb-green);
        }

        .bg-hcb-green {
            background-color: var(--hcb-green);
        }

        .text-hcb-orange {
            color: var(--hcb-orange);
        }

        .border-hcb-orange {
            border-color: var(--hcb-orange);
        }

        .border-hcb-green {
            border-color: var(--hcb-green);
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

        .quotation-container {
            max-width: 100%;
            margin: 0;
            background-color: #ffffff;
            padding: 8mm 5mm;
            min-height: 100vh;
            position: relative;
            padding-bottom: 120px;
        }

        @page {
            size: A4;
            margin: 8mm 5mm;
        }

        /* Print styles for A4 */
        @media print {
            body {
                background-color: #fff;
                font-size: 11px;
            }

            .quotation-container {
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

            .no-print {
                display: none;
            }

            .watermark-bg {
                display: block !important;
                z-index: 1000 !important;
                position: fixed !important;
                opacity: 0.07 !important;
                pointer-events: none;
            }
        }

        /* Custom responsive table */
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

        /* Compact spacing utilities */
        .compact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .info-card {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 4px;
            border-left: 3px solid;
            font-size: 11px;
        }

        .summary-card {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
            font-size: 11px;
        }

        /* Footer */
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

        /* Signature positioning */
        .signature-section {
            margin-top: 20px;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        @media print {
            .signature-section {
                position: absolute;
                bottom: 80px;
                left: 0;
                right: 0;
                margin-bottom: 0;
            }
        }

        .qr-signature {
            text-align: center;
            margin: 5px 0;
        }

        .qr-label {
            font-size: 8px;
            color: #64748b;
            margin-bottom: 3px;
        }

        .qr-code-small {
            width: 60px;
            height: 60px;
            border: 1px solid #e5e7eb;
            padding: 3px;
            margin-bottom: 5px;
        }

        /* Watermark background */
        .watermark-bg {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            text-align: center;
            z-index: 1000;
            opacity: 0.07;
            font-size: 50px;
            font-weight: bold;
            color: var(--hcb-green);
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }

        /* Total Summary */
        .total-summary {
            width: calc(100% - 60px);
            margin: 20px 30px;
            padding: 0;
            page-break-inside: avoid;
        }

        .summary-section {
            float: right;
            width: 50%;
            padding: 0;
            min-width: 300px;
        }

        .summary-item {
            width: 100%;
            margin-bottom: 6px;
            font-size: 11px;
            clear: both;
            box-sizing: border-box;
            padding: 2px 0;
            display: table;
            table-layout: fixed;
        }

        .summary-item .label {
            display: table-cell;
            width: 55%;
            line-height: 1.4;
            color: #4b5563 !important;
            font-weight: normal;
            vertical-align: top;
        }

        .summary-item .amount {
            display: table-cell;
            font-weight: bold;
            text-align: right;
            width: 45%;
            line-height: 1.4;
            color: #111827 !important;
            vertical-align: top;
            white-space: nowrap;
        }

        .summary-highlight {
            background-color: rgba(239, 68, 68, 0.1);
            padding: 4px 6px;
            margin: 2px -6px;
            border-radius: 3px;
        }

        .summary-highlight .label {
            color: #dc2626 !important;
            font-weight: 500;
        }

        .summary-highlight .amount {
            color: #dc2626 !important;
            font-weight: bold;
        }

        /* Total at bottom */
        .total-final {
            width: 100%;
            margin-top: 15px;
            padding: 12px 0;
            border-top: 2px solid var(--hcb-blue);
            font-size: 14px;
            font-weight: bold;
            color: var(--hcb-blue);
            clear: both;
            display: table;
            table-layout: fixed;
        }

        .total-final .label {
            display: table-cell;
            width: 55%;
            vertical-align: top;
        }

        .total-final .amount {
            display: table-cell;
            text-align: right;
            width: 45%;
            color: var(--hcb-orange);
            font-weight: bold;
            vertical-align: top;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="watermark-bg">{{ strtoupper('PT HIDAYAH CAHAYA BERKAH') }}</div>

    <div class="quotation-container relative z-10">
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
                                HCB
                            </div>
                        @endif
                        <span
                            style="font-size: 13px; font-weight: 800; color: var(--hcb-blue); letter-spacing: 0.5px; white-space: nowrap; flex-shrink: 0; line-height: 1; vertical-align: middle;">
                            PT HIDAYAH CAHAYA BERKAH
                        </span>
                    </div>
                </div>

                <!-- Quotation Info -->
                <div style="display: table-cell; vertical-align: middle; width: 35%; text-align: right;">
                    <div
                        style="background-color: #f8fafc; padding: 8px; border-radius: 5px; border-left: 4px solid var(--hcb-orange); min-height: 48px;">
                        <div
                            style="font-size: 16px; font-weight: 700; color: var(--hcb-orange); margin-bottom: 4px; letter-spacing: 1px;">
                            QUOTATION
                        </div>
                        <div style="font-size: 10px; line-height: 1.3; color: #374151;">
                            <div style="margin-bottom: 2px;">
                                <span style="font-weight: 600; color: #1f2937;">No:</span>
                                <span style="color: var(--hcb-blue); font-weight: 600;">{{ $quotation->nomor }}</span>
                            </div>
                            <div style="margin-bottom: 2px;">
                                <span style="font-weight: 600; color: #1f2937;">Tanggal:</span>
                                <span
                                    style="color: #374151;">{{ \Carbon\Carbon::parse($quotation->tanggal)->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span style="font-weight: 600; color: #1f2937;">Status:</span>
                                <span
                                    style="color: var(--hcb-green); font-weight: 700; text-transform: uppercase; background-color: #f0fdf4; padding: 2px 6px; border-radius: 3px; font-size: 9px;">{{ $quotation->status }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            style="height: 3px; background: linear-gradient(to right, var(--hcb-blue) 0%, var(--hcb-green) 50%, var(--hcb-orange) 100%); margin: 15px 0; border-radius: 2px;">
        </div>

        <!-- Company and Customer Info Section -->
        <div style="display: table; width: 100%; margin-bottom: 20px; border-collapse: separate; border-spacing: 0;">
            <div style="display: table-row;">
                <!-- DARI Section -->
                <div style="display: table-cell; vertical-align: top; width: 48%; padding-right: 30px;">
                    <div
                        style="font-size: 12px; font-weight: 700; color: var(--hcb-blue); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 2px solid var(--hcb-blue);">
                        DARI:
                    </div>
                    <div style="font-weight: 700; color: var(--hcb-blue); font-size: 13px; margin-bottom: 5px;">
                        PT HIDAYAH CAHAYA BERKAH
                    </div>
                    <div style="font-size: 11px; line-height: 1.4; color: #374151;">
                        Jl. Raya Bekasi No. 88, Cakung<br>
                        Jakarta Timur 13910, Indonesia<br>
                        Telp: (021) 4608-7890 - (021) 4608-7891<br>
                    </div>
                </div>
                <!-- UNTUK Section -->
                <div style="display: table-cell; vertical-align: top; width: 52%;">
                    <div
                        style="font-size: 12px; font-weight: 700; color: var(--hcb-orange); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 2px solid var(--hcb-orange);">
                        UNTUK:
                    </div>
                    <div style="font-weight: 700; color: var(--hcb-blue); font-size: 13px; margin-bottom: 5px;">
                        {{ $quotation->customer->company ?? $quotation->customer->nama }}
                    </div>
                    <div style="font-size: 11px; line-height: 1.4; color: #374151;">
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
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if ($quotation->periode_start && $quotation->periode_end)
            <div
                style="background-color: #dbeafe; border: 1px solid #93c5fd; border-radius: 0.25rem; padding: 0.75rem; margin-bottom: 1rem; display: flex; align-items: center; font-size: 10px;">
                <svg style="height: 14px; width: 14px; color: #3b82f6; margin-right: 0.5rem;" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <strong style="color: #1d4ed8; margin-right: 0.5rem;">Periode Penawaran:</strong>
                <span style="color: #2563eb;">
                    {{ \Carbon\Carbon::parse($quotation->periode_start)->format('d/m/Y') }} s/d
                    {{ \Carbon\Carbon::parse($quotation->periode_end)->format('d/m/Y') }}
                </span>
            </div>
        @endif

        <!-- Items Table -->
        <div
            style="font-size: 10px; font-weight: 600; color: var(--hcb-blue); margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
            Detail Penawaran:
        </div>
        <div
            style="overflow-x: auto; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); border-radius: 0.25rem; margin-bottom: 1rem;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 8%; text-align: center;">No</th>
                        <th style="width: 40%;">Produk</th>
                        <th style="width: 8%; text-align: center;">Qty</th>
                        <th style="width: 10%; text-align: center;">Satuan</th>
                        <th style="width: 15%; text-align: right;">Harga</th>
                        <th style="width: 10%; text-align: center;">Diskon</th>
                        <th style="width: 19%; text-align: right;">Total</th>
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
                                    $bundleHeader = $quotation->details
                                        ->where('bundle_id', $detail->bundle_id)
                                        ->first();
                                }
                            @endphp

                            {{-- Bundle Header --}}
                            <tr style="background-color: #f8fafc; border-left: 3px solid #1a2e05;">
                                <td style="text-align: center; font-weight: 600;">{{ $displayIndex++ }}</td>
                                <td>
                                    <div style="font-weight: 600; color: #1a2e05; margin-bottom: 2px;">
                                        PAKET:
                                        @if ($bundleHeader->bundle && $bundleHeader->bundle->nama)
                                            {{ $bundleHeader->bundle->nama }}
                                        @elseif (str_contains($bundleHeader->deskripsi ?? '', 'Bundle:'))
                                            {{ str_replace('Bundle: ', '', $bundleHeader->deskripsi) }}
                                        @else
                                            Paket Bundle #{{ $detail->bundle_id }}
                                        @endif
                                    </div>
                                    @if ($bundleHeader->bundle && $bundleHeader->bundle->kode)
                                        <div style="font-size: 10px; color: #666;">Kode:
                                            {{ $bundleHeader->bundle->kode }}</div>
                                    @endif

                                    {{-- Bundle Items Details in same row --}}
                                    <div
                                        style="margin-top: 5px; padding: 5px; background-color: #f9f9f9; border-radius: 3px;">
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
                                <td style="text-align: center;">
                                    @if (floor($bundleHeader->quantity) == $bundleHeader->quantity)
                                        {{ number_format($bundleHeader->quantity, 0, ',', '.') }}
                                    @else
                                        {{ number_format($bundleHeader->quantity, 2, ',', '.') }}
                                    @endif
                                </td>
                                <td style="text-align: center;">Paket</td>
                                <td style="text-align: right;">
                                    {{ number_format($bundleHeader->bundle->harga_bundle ?? 0, 0, ',', '.') }}
                                </td>
                                <td style="text-align: center; font-size: 9px;">-</td>
                                <td style="text-align: right; font-weight: 600;">Rp
                                    {{ number_format(($bundleHeader->bundle->harga_bundle ?? 0) * $bundleHeader->quantity, 0, ',', '.') }}
                                </td>
                            </tr>
                        @elseif (!$detail->bundle_id)
                            {{-- Regular Product (not part of any bundle) --}}
                            <tr>
                                <td style="text-align: center; font-weight: 600;">{{ $displayIndex++ }}</td>
                                <td>
                                    <div style="font-weight: 500; color: #111827; margin-bottom: 2px;">
                                        @if ($detail->produk && $detail->produk->nama)
                                            {{ $detail->produk->nama }}
                                        @elseif ($detail->deskripsi)
                                            {{ $detail->deskripsi }}
                                        @else
                                            Produk tidak ditemukan
                                        @endif
                                    </div>
                                    @if ($detail->produk && $detail->produk->kode)
                                        <div style="font-size: 10px;">{{ $detail->produk->kode }}</div>
                                    @endif
                                    @if ($detail->deskripsi && $detail->produk && $detail->produk->nama != $detail->deskripsi)
                                        <div style="color: #6b7280; font-size: 9px; line-height: 1.2;">
                                            {{ $detail->deskripsi }}</div>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if (floor($detail->quantity) == $detail->quantity)
                                        {{ number_format($detail->quantity, 0, ',', '.') }}
                                    @else
                                        {{ number_format($detail->quantity, 2, ',', '.') }}
                                    @endif
                                </td>
                                <td style="text-align: center;">{{ $detail->satuan->nama ?? 'pcs' }}</td>
                                <td style="text-align: right;">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td style="text-align: center; font-size: 9px;">
                                    @if ($detail->diskon_persen > 0)
                                        {{ number_format($detail->diskon_persen, 1, ',', '.') }}%
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="text-align: right; font-weight: 600;">Rp
                                    {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Section -->
        <div class="total-summary clearfix">
            <div class="summary-section">
                <div class="summary-item clearfix">
                    <span class="label">Subtotal:</span>
                    <span class="amount">Rp {{ number_format($quotation->subtotal, 0, ',', '.') }}</span>
                </div>
                @if ($quotation->diskon_nominal > 0)
                    <div class="summary-item summary-highlight clearfix">
                        <span class="label">Diskon ({{ number_format($quotation->diskon_persen, 1) }}%):</span>
                        <span class="amount">-Rp {{ number_format($quotation->diskon_nominal, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if ($quotation->ppn > 0)
                    <div class="summary-item clearfix">
                        <span class="label">PPN ({{ $quotation->ppn }}%):</span>
                        <span class="amount">Rp
                            {{ number_format($quotation->subtotal * ($quotation->ppn / 100), 0, ',', '.') }}</span>
                    </div>
                @endif
                @if ($quotation->ongkos_kirim > 0)
                    <div class="summary-item clearfix">
                        <span class="label">Ongkos Kirim:</span>
                        <span class="amount">Rp {{ number_format($quotation->ongkos_kirim, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="total-final clearfix">
                    <span class="label">TOTAL:</span>
                    <span class="amount">Rp {{ number_format($quotation->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Clear float untuk memastikan layout yang benar -->
        <div style="clear: both;"></div>

        <!-- Notes Section -->
        @if ($quotation->catatan)
            <div
                style="margin: 15px 0 1rem 0; background-color: #fef3c7; border-left: 3px solid #f59e0b; padding: 0.75rem; border-radius: 0 0.25rem 0.25rem 0; clear: both;">
                <div style="display: flex; align-items: flex-start;">
                    <svg style="height: 14px; width: 14px; color: #d97706; margin-right: 0.5rem; margin-top: 1px; flex-shrink: 0;"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <div>
                        <div style="font-weight: 600; color: #92400e; font-size: 10px; margin-bottom: 0.25rem;">
                            Catatan:
                        </div>
                        <div style="color: #b45309; font-size: 10px; line-height: 1.4;">{{ $quotation->catatan }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Terms and Conditions -->
        <div
            style="margin-bottom: 30px; margin-top: {{ $quotation->catatan ? '25px' : '40px' }}; page-break-inside: avoid; clear: both;">
            <div
                style="font-weight: 600; font-size: 11px; color: var(--hcb-blue); margin-bottom: 8px; padding: 4px 0; border-bottom: 1px solid #e5e7eb;">
                SYARAT & KETENTUAN
            </div>
            <div style="padding: 5px 0;">
                @if ($quotation->syarat_ketentuan)
                    <div style="color: #374151; font-size: 9px; line-height: 1.4; white-space: pre-line;">
                        {!! nl2br(e($quotation->syarat_ketentuan)) !!}
                    </div>
                @else
                    <div style="color: #374151; font-size: 9px; line-height: 1.4;">
                        <div style="margin-bottom: 3px;">• Penawaran berlaku sesuai periode yang tertera</div>
                        <div style="margin-bottom: 3px;">• Harga belum termasuk pajak dan ongkos kirim</div>
                        <div style="margin-bottom: 3px;">• Pembayaran sesuai kesepakatan kedua belah pihak</div>
                        <div style="margin-bottom: 3px;">• Pengiriman setelah pembayaran diterima</div>
                        <div>• Barang yang sudah dibeli tidak dapat dikembalikan</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Signatures -->
        <div class="signature-section"
            style="display: table; width: 100%; border-collapse: separate; border-spacing: 0; page-break-inside: avoid;">
            <div style="display: table-row;">
                <div style="display: table-cell; vertical-align: top; text-align: center;">
                    {{-- WhatsApp QR Code for Creator --}}
                    @if (isset($whatsappQR) && $whatsappQR)
                        <div class="qr-signature">
                            <div class="qr-label">Scan untuk Verifikasi via WhatsApp</div>
                            <img src="{{ $whatsappQR }}" alt="WhatsApp Verification QR Code"
                                class="qr-code-small">
                        </div>
                    @else
                        <div style="height: 60px; margin: 5px 0;"></div>
                    @endif

                    <div style="border-bottom: 1px solid var(--hcb-blue); margin: 5px auto 8px auto; width: 200px;">
                    </div>
                    <div style="font-weight: 700; color: var(--hcb-blue); font-size: 11px; margin-bottom: 4px;">
                        {{ $quotation->user->name ?? 'Sales Representative' }}
                    </div>
                    <div style="font-size: 10px; color: #6b7280;">Sales Representative</div>
                    <div style="font-size: 8px; color: #9ca3af; margin-top: 3px;">
                        {{ \Carbon\Carbon::parse($quotation->created_at)->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-fixed">
            <div style="font-size: 8px;">
                Dokumen dicetak digital {{ now()->format('d/m/Y H:i') }} WIB • <span
                    style="font-weight: 600; color: var(--hcb-blue);">SemestaPro</span>
            </div>
        </div>
    </div>
</body>

</html>
