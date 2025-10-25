<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sales Order - {{ $salesOrder->nomor }} - PT Hidayah Cahaya Berkah</title>
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

        .quotation-container {
            max-width: 100%;
            margin: 0;
            background-color: #ffffff;
            padding: 8mm 5mm;
            min-height: 100vh;
            position: relative;
            padding-bottom: 60px;
        }

        @page {
            size: A4;
            margin: 8mm 5mm;
        }

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

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
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

        .total-summary {
            width: calc(100% - 60px);
            margin: 10px 30px;
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

        .signature-section {
            margin-top: 10px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            page-break-inside: avoid;
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

                <!-- Sales Order Info -->
                <div style="display: table-cell; vertical-align: middle; width: 35%; text-align: right;">
                    <div
                        style="background-color: #f8fafc; padding: 8px; border-radius: 5px; border-left: 4px solid var(--hcb-orange); min-height: 48px;">
                        <div
                            style="font-size: 16px; font-weight: 700; color: var(--hcb-orange); margin-bottom: 4px; letter-spacing: 1px;">
                            SALES ORDER
                        </div>
                        <div style="font-size: 10px; line-height: 1.3; color: #374151;">
                            <div style="margin-bottom: 2px;">
                                <span style="font-weight: 600; color: #1f2937;">No:</span>
                                <span style="color: var(--hcb-blue); font-weight: 600;">{{ $salesOrder->nomor }}</span>
                            </div>
                            @if ($salesOrder->nomor_po)
                                <div style="margin-bottom: 2px;">
                                    <span style="font-weight: 600; color: #1f2937;">No. PO:</span>
                                    <span
                                        style="color: var(--hcb-blue); font-weight: 600;">{{ $salesOrder->nomor_po }}</span>
                                </div>
                            @endif
                            <div style="margin-bottom: 2px;">
                                <span style="font-weight: 600; color: #1f2937;">Tanggal:</span>
                                <span
                                    style="color: #374151;">{{ \Carbon\Carbon::parse($salesOrder->tanggal)->format('d/m/Y') }}</span>
                            </div>
                            <div style="margin-bottom: 2px;">
                                <span style="font-weight: 600; color: #1f2937;">Status Pembayaran:</span>
                                <span
                                    style="color: var(--hcb-green); font-weight: 700; text-transform: uppercase; background-color: #f0fdf4; padding: 2px 6px; border-radius: 3px; font-size: 9px;">{{ $salesOrder->status_pembayaran }}</span>
                            </div>
                            <div>
                                <span style="font-weight: 600; color: #1f2937;">Status Pengiriman:</span>
                                <span
                                    style="color: var(--hcb-orange); font-weight: 700; text-transform: uppercase; background-color: #fff7ed; padding: 2px 6px; border-radius: 3px; font-size: 9px;">{{ $salesOrder->status_pengiriman }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            style="height: 3px; background: linear-gradient(to right, var(--hcb-blue) 0%, var(--hcb-green) 50%, var(--hcb-orange) 100%); margin: 8px 0; border-radius: 2px;">
        </div>

        <!-- Company and Customer Info Section -->
        <div style="display: table; width: 100%; margin-bottom: 12px; border-collapse: separate; border-spacing: 0;">
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
                        {{ $salesOrder->customer->company ?? $salesOrder->customer->nama }}
                    </div>
                    <div style="font-size: 10px; line-height: 1.35; color: #374151;">
                        {{ $salesOrder->customer->alamat ?? '-' }}
                        @if ($salesOrder->alamat_pengiriman && $salesOrder->alamat_pengiriman != $salesOrder->customer->alamat)
                            <br><strong style="color: var(--hcb-orange);">Kirim:</strong>
                            {{ $salesOrder->alamat_pengiriman }}
                        @endif
                        <br>
                        @if ($salesOrder->customer->telepon)
                            Telp: {{ $salesOrder->customer->telepon }}
                        @endif
                        @if ($salesOrder->customer->email)
                            @if ($salesOrder->customer->telepon)
                                |
                            @endif Email: {{ $salesOrder->customer->email }}
                        @endif
                        @if ($salesOrder->customer->kontak_person)
                            <br>Kontak: {{ $salesOrder->customer->kontak_person }}@if ($salesOrder->customer->no_hp_kontak)
                                ({{ $salesOrder->customer->no_hp_kontak }})
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div
            style="font-size: 10px; font-weight: 600; color: var(--hcb-blue); margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
            Detail Pesanan:</div>
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
                    @php $no = 1; @endphp
                    @php
                        $no = 1;
                        $processedBundles = [];
                    @endphp
                    @foreach ($salesOrder->details as $detail)
                        @if ($detail->bundle_id && !in_array($detail->bundle_id, $processedBundles))
                            @php
                                $processedBundles[] = $detail->bundle_id;
                                $bundleHeader = $salesOrder->details
                                    ->where('bundle_id', $detail->bundle_id)
                                    ->where('is_bundle_item', '!=', true)
                                    ->first();
                                $bundleItems = $salesOrder->details
                                    ->where('bundle_id', $detail->bundle_id)
                                    ->where('is_bundle_item', true);
                                if (!$bundleHeader) {
                                    $bundleHeader = $salesOrder->details
                                        ->where('bundle_id', $detail->bundle_id)
                                        ->first();
                                }
                            @endphp
                            <tr>
                                <td style="text-align: center; font-weight: 600;">{{ $no++ }}</td>
                                <td>
                                    <div style="font-weight: 700; color: #002147; margin-bottom: 2px;">PAKET:
                                        {{ $bundleHeader->bundle->nama ?? ($bundleHeader->deskripsi ?? 'Paket Bundle #' . $detail->bundle_id) }}
                                    </div>
                                    @if ($bundleHeader->bundle && $bundleHeader->bundle->kode)
                                        <div style="font-size: 9px; color: #666;">Kode:
                                            {{ $bundleHeader->bundle->kode }}</div>
                                    @endif
                                    <div
                                        style="margin-top: 5px; padding: 5px; background-color: #f3f4f6; border-radius: 3px;">
                                        <div style="font-size: 9px; color: #555; font-weight: bold;">Isi Paket:</div>
                                        @foreach ($bundleItems as $bundleItem)
                                            <div style="font-size: 9px; color: #666; margin-left: 10px;">
                                                •
                                                @if ($bundleItem->produk && $bundleItem->produk->nama)
                                                    {{ $bundleItem->produk->nama }}
                                                @elseif ($bundleItem->deskripsi)
                                                    {{ preg_replace('/^└─\s*/', '', preg_replace('/\s*\(dari bundle.*\)$/', '', $bundleItem->deskripsi)) }}
                                                @else
                                                    Item Bundle
                                                @endif
                                                (@if (floor($bundleItem->quantity) == $bundleItem->quantity)
                                                    {{ number_format($bundleItem->quantity, 0) }}@else{{ number_format($bundleItem->quantity, 2) }}
                                                @endif
                                                {{ $bundleItem->satuan->nama ?? 'pcs' }})
                                                @if ($bundleItem->produk && $bundleItem->produk->kode)
                                                    - {{ $bundleItem->produk->kode }}
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    @if (floor($bundleHeader->quantity) == $bundleHeader->quantity)
                                        {{ number_format($bundleHeader->quantity, 0) }}
                                    @else
                                        {{ number_format($bundleHeader->quantity, 2) }}
                                    @endif
                                </td>
                                <td style="text-align: center;">Paket</td>
                                <td style="text-align: right;">{{ number_format($bundleHeader->harga, 0, ',', '.') }}
                                </td>
                                <td style="text-align: center; font-size: 9px;">
                                    @if ($bundleHeader->diskon_persen > 0)
                                        {{ number_format($bundleHeader->diskon_persen, 1) }}%
                                    @endif
                                </td>
                                <td style="text-align: right; font-weight: 600;">Rp
                                    {{ number_format($bundleHeader->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @elseif (!$detail->bundle_id)
                            <tr>
                                <td style="text-align: center; font-weight: 600;">{{ $no++ }}</td>
                                <td>
                                    <div style="font-weight: 500; color: #111827; margin-bottom: 2px;">
                                        {{ $detail->produk->nama ?? 'Produk' }}</div>
                                    @if ($detail->deskripsi)
                                        <div style="color: #6b7280; font-size: 9px; line-height: 1.2;">
                                            {{ $detail->deskripsi }}</div>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if (floor($detail->quantity) == $detail->quantity)
                                        {{ number_format($detail->quantity, 0) }}
                                    @else
                                        {{ number_format($detail->quantity, 2) }}
                                    @endif
                                </td>
                                <td style="text-align: center;">{{ $detail->satuan->nama ?? '-' }}</td>
                                <td style="text-align: right;">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td style="text-align: center; font-size: 9px;">
                                    @if ($detail->diskon_persen > 0)
                                        {{ number_format($detail->diskon_persen, 1) }}%
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
                    <span class="amount">Rp {{ number_format($salesOrder->subtotal, 0, ',', '.') }}</span>
                </div>
                @if ($salesOrder->diskon_nominal > 0)
                    <div class="summary-item summary-highlight clearfix">
                        <span class="label">Diskon ({{ number_format($salesOrder->diskon_persen, 1) }}%):</span>
                        <span class="amount">-Rp {{ number_format($salesOrder->diskon_nominal, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if ($salesOrder->ppn > 0)
                    <div class="summary-item clearfix">
                        <span class="label">PPN ({{ $salesOrder->ppn }}%):</span>
                        <span class="amount">Rp
                            {{ number_format($salesOrder->subtotal * ($salesOrder->ppn / 100), 0, ',', '.') }}</span>
                    </div>
                @endif
                @if ($salesOrder->ongkos_kirim > 0)
                    <div class="summary-item clearfix">
                        <span class="label">Ongkos Kirim:</span>
                        <span class="amount">Rp {{ number_format($salesOrder->ongkos_kirim, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="total-final clearfix">
                    <span class="label">TOTAL:</span>
                    <span class="amount">Rp {{ number_format($salesOrder->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Clear float untuk memastikan layout yang benar -->
        <div style="clear: both;"></div>

        <!-- Notes Section -->
        @if ($salesOrder->catatan)
            <div
                style="margin: 8px 0 0.5rem 0; background-color: #fef3c7; border-left: 3px solid #f59e0b; padding: 0.5rem; border-radius: 0 0.25rem 0.25rem 0; clear: both;">
                <div style="display: flex; align-items: flex-start;">
                    <svg style="height: 14px; width: 14px; color: #d97706; margin-right: 0.5rem; margin-top: 1px; flex-shrink: 0;"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <div>
                        <div style="font-weight: 600; color: #92400e; font-size: 10px; margin-bottom: 0.25rem;">
                            Catatan:</div>
                        <div style="color: #b45309; font-size: 10px; line-height: 1.4;">{{ $salesOrder->catatan }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($salesOrder->terms_pembayaran)
            <div
                style="margin-bottom: 0.5rem; background-color: #f0fff4; border-left: 3px solid var(--hcb-green); padding: 0.5rem; border-radius: 0 0.25rem 0.25rem 0; clear: both;">
                <div style="font-weight: 600; color: var(--hcb-green); font-size: 10px; margin-bottom: 0.25rem;">Terms
                    Pembayaran:</div>
                <div style="color: #27ae60; font-size: 10px; line-height: 1.4;">
                    {{ $salesOrder->terms_pembayaran }}
                    @if ($salesOrder->terms_pembayaran_hari)
                        ({{ $salesOrder->terms_pembayaran_hari }} hari)
                    @endif
                </div>
            </div>
        @endif

        <!-- Signatures -->
        <div class="signature-section"
            style="clear: both; margin-top: {{ $salesOrder->catatan || $salesOrder->terms_pembayaran ? '5px' : '3px' }};">
            <div style="display: table-row;">
                <div
                    style="display: table-cell; vertical-align: top; width: 50%; padding-right: 30px; text-align: center;">
                    <div style="margin-bottom: 6px; font-weight: bold; color: var(--hcb-blue); font-size: 10px;">
                        Dibuat oleh:</div>

                    {{-- WhatsApp QR Code for Creator --}}
                    @if (isset($whatsappQR) && $whatsappQR)
                        <div style="text-align: center; margin-bottom: 6px;">
                            <div style="font-size: 8px; color: #64748b; margin-bottom: 3px;">Scan untuk Verifikasi via
                                WhatsApp</div>
                            <img src="{{ $whatsappQR }}" alt="WhatsApp Verification QR Code"
                                style="width: 60px; height: 60px; border: 1px solid #e5e7eb; padding: 3px;">
                        </div>
                    @else
                        <div style="height: 60px; margin-bottom: 6px;"></div>
                    @endif

                    <div
                        style="border-bottom: 1px solid var(--hcb-blue); margin-bottom: 6px; width: 150px; margin-left: auto; margin-right: auto;">
                    </div>
                    <div style="font-weight: 700; color: var(--hcb-blue); font-size: 11px; margin-bottom: 3px;">
                        {{ $salesOrder->user->name ?? 'Sales Representative' }}</div>
                    <div style="font-size: 10px; color: #6b7280;">Sales Representative</div>
                    <div style="font-size: 8px; color: #94a3b8; margin-top: 1px;">
                        {{ \Carbon\Carbon::parse($salesOrder->created_at)->format('d/m/Y H:i') }}
                    </div>
                </div>
                <div style="display: table-cell; vertical-align: top; width: 50%; text-align: center;">
                    <div style="margin-bottom: 6px; font-weight: bold; color: var(--hcb-blue); font-size: 10px;">
                        Customer:</div>
                    <div style="height: 60px; margin-bottom: 6px;"></div>
                    <div
                        style="border-bottom: 1px solid var(--hcb-blue); margin-bottom: 6px; width: 150px; margin-left: auto; margin-right: auto;">
                    </div>
                    <div style="font-weight: 700; color: var(--hcb-blue); font-size: 11px; margin-bottom: 3px;">
                        {{ $salesOrder->customer->nama ?? $salesOrder->customer->company }}</div>
                    <div style="font-size: 10px; color: #6b7280;">Tanda Tangan & Stempel</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-fixed">
            <div style="font-size: 8px;">Dokumen dicetak digital {{ now()->format('d/m/Y H:i') }} WIB • <span
                    style="font-weight: 600; color: var(--hcb-blue);">SemestaPro</span></div>
        </div>
    </div>
</body>

</html>
