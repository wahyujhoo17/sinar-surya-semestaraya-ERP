<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice - {{ $invoice->nomor }} - PT Hidayah Cahaya Berkah</title>
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

        .invoice-container {
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
            margin-top: 20px;
            margin-bottom: 15px;
            padding-bottom: 10px;
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

        .status-belum-bayar {
            color: #dc2626;
            font-weight: bold;
        }

        .status-sebagian {
            color: #d97706;
            font-weight: bold;
        }

        .status-lunas {
            color: #16a34a;
            font-weight: bold;
        }

        .status-default {
            color: #6b7280;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="watermark-bg">{{ strtoupper('PT HIDAYAH CAHAYA BERKAH') }}</div>
    <div class="invoice-container relative z-10">
        <!-- Header Section -->
        <div style="display: table; width: 100%; margin-bottom: 0px; border-collapse: separate; border-spacing: 0;">
            <div style="display: table-row;">
                <!-- Logo and Company Info -->
                <div style="display: table-cell; vertical-align: middle; width: 65%; padding-right: 8px;">
                    <div style="display: flex; align-items: center; flex-wrap: nowrap; gap: 12px; min-height: 56px;">
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
                                style="height: 48px; width: auto; max-width: 80px; object-fit: contain; border-radius: 4px; flex-shrink: 0; vertical-align: middle; background-color: white; padding: 3px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                        @else
                            <div
                                style="height: 48px; width: 80px; border: 2px dashed var(--hcb-green); display: flex; align-items: center; justify-content: center; font-size: 13px; color: var(--hcb-green); background-color: #f0fdf4; border-radius: 4px; font-weight: 700; flex-shrink: 0;">
                                HCB
                            </div>
                        @endif
                        <span
                            style="font-size: 16px; font-weight: 900; color: var(--hcb-blue); letter-spacing: 0.7px; white-space: nowrap; flex-shrink: 0; line-height: 1; vertical-align: middle;">
                            PT HIDAYAH CAHAYA BERKAH
                        </span>
                    </div>
                </div>

                <!-- Invoice Info -->
                <div style="display: table-cell; vertical-align: middle; width: 35%; text-align: right;">
                    <div
                        style="background-color: #f8fafc; padding: 8px; border-radius: 5px; border-left: 4px solid var(--hcb-orange); min-height: 48px;">
                        <div
                            style="font-size: 16px; font-weight: 700; color: var(--hcb-orange); margin-bottom: 4px; letter-spacing: 1px;">
                            INVOICE
                        </div>
                        <div style="font-size: 10px; line-height: 1.3; color: #374151;">
                            <div style="margin-bottom: 2px;">
                                <span style="font-weight: 600; color: #1f2937;">No:</span>
                                <span style="color: var(--hcb-blue); font-weight: 600;">{{ $invoice->nomor }}</span>
                            </div>
                            @if ($invoice->nomor_so)
                                <div style="margin-bottom: 2px;">
                                    <span style="font-weight: 600; color: #1f2937;">No. SO:</span>
                                    <span
                                        style="color: var(--hcb-blue); font-weight: 600;">{{ $invoice->nomor_so }}</span>
                                </div>
                            @endif
                            <div style="margin-bottom: 2px;">
                                <span style="font-weight: 600; color: #1f2937;">Tanggal:</span>
                                <span
                                    style="color: #374151;">{{ \Carbon\Carbon::parse($invoice->tanggal)->format('d/m/Y') }}</span>
                            </div>
                            @if ($invoice->salesOrder)
                                <div style="margin-bottom: 2px;">
                                    <span style="font-weight: 600; color: #1f2937;">No. Sales Order:</span>
                                    <span
                                        style="color: var(--hcb-blue); font-weight: 600;">{{ $invoice->salesOrder->nomor }}</span>
                                </div>
                            @endif
                            @if ($invoice->status)
                                <div>
                                    <span style="font-weight: 600; color: #1f2937;">Status Pembayaran:</span>
                                    @php
                                        $statusClass = 'status-default';
                                        $statusLabel = ucfirst(str_replace('_', ' ', $invoice->status));

                                        switch ($invoice->status) {
                                            case 'belum_bayar':
                                                $statusClass = 'status-belum-bayar';
                                                $statusLabel = 'Belum Bayar';
                                                break;
                                            case 'sebagian':
                                                $statusClass = 'status-sebagian';
                                                $statusLabel = 'Sebagian';
                                                break;
                                            case 'lunas':
                                                $statusClass = 'status-lunas';
                                                $statusLabel = 'Lunas';
                                                break;
                                        }
                                    @endphp
                                    <span class="{{ $statusClass }}"
                                        style="text-transform: uppercase; background-color: rgba(34, 197, 94, 0.1); padding: 2px 6px; border-radius: 3px; font-size: 9px;">{{ $statusLabel }}</span>
                                </div>
                            @endif
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
                        {{ $invoice->customer->company ?? $invoice->customer->nama }}
                    </div>
                    <div style="font-size: 11px; line-height: 1.4; color: #374151;">
                        {{ $invoice->customer->alamat ?? '-' }}<br>
                        @if ($invoice->customer->telepon)
                            Telp: {{ $invoice->customer->telepon }}<br>
                        @endif
                        @if ($invoice->customer->email)
                            Email: {{ $invoice->customer->email }}<br>
                        @endif
                        @if ($invoice->customer->kontak_person)
                            Kontak: {{ $invoice->customer->kontak_person }}
                            @if ($invoice->customer->no_hp_kontak)
                                ({{ $invoice->customer->no_hp_kontak }})
                            @endif
                        @endif
                        @if ($invoice->alamat_pengiriman)
                            <br><strong>Alamat Pengiriman:</strong><br>
                            {{ $invoice->alamat_pengiriman }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if ($invoice->tanggal_jatuh_tempo)
            <div
                style="background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 0.25rem; padding: 0.75rem; margin-bottom: 1rem; display: flex; align-items: center; font-size: 10px;">
                <svg style="height: 14px; width: 14px; color: #d97706; margin-right: 0.5rem;" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <strong style="color: #92400e; margin-right: 0.5rem;">Jatuh Tempo:</strong>
                <span
                    style="color: #b45309;">{{ \Carbon\Carbon::parse($invoice->tanggal_jatuh_tempo)->format('d/m/Y') }}</span>
            </div>
        @endif

        <!-- Items Table -->
        <div
            style="font-size: 10px; font-weight: 600; color: var(--hcb-blue); margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
            Detail Invoice:</div>
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
                    @foreach ($invoice->details as $detail)
                        <tr>
                            <td style="text-align: center; font-weight: 600;">{{ $no++ }}</td>
                            <td>
                                <div style="font-weight: 500; color: #111827; margin-bottom: 2px;">
                                    {{ $detail->produk->nama ?? 'Produk' }}</div>
                                @if ($detail->deskripsi != $detail->produk->nama)
                                    <div style="color: #6b7280; font-size: 9px; line-height: 1.2;">
                                        {{ $detail->deskripsi }}</div>
                                @endif
                            </td>
                            <td style="text-align: center;">{{ number_format($detail->quantity, 0) }}</td>
                            <td style="text-align: center;">{{ $detail->satuan->nama ?? '-' }}</td>
                            <td style="text-align: right;">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td style="text-align: center; font-size: 9px;">
                                @if ($detail->diskon_persen > 0)
                                    {{ number_format($detail->diskon_persen, 1) }}%
                                @endif
                            </td>
                            <td style="text-align: right; font-weight: 600;">Rp
                                {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Section -->
        @php
            $uangMuka = $invoice->uang_muka_terapkan ?? 0;
            $grandTotal = $invoice->total;
            $totalTagihan = $grandTotal - $uangMuka;
            $nilaiTerbilang = $invoice->total;

            // Jika ada DP dari parameter
            if (isset($dpAmount) && $dpAmount > 0) {
                $dpSubtotal = $dpAmount;
                $dpPPN = 0;
                if ($invoice->ppn > 0) {
                    $ppnRate = $invoice->subtotal > 0 ? $invoice->ppn / $invoice->subtotal : 0;
                    $dpPPN = $dpAmount * $ppnRate;
                }
                $totalDP = $dpSubtotal + $dpPPN;
                $nilaiTerbilang = $totalDP;
            }
        @endphp

        <div class="total-summary clearfix">
            <div class="summary-section">
                @if (isset($dpAmount) && $dpAmount > 0)
                    {{-- Invoice DP --}}
                    <div class="summary-item clearfix">
                        <span class="label">Uang Muka (DP):</span>
                        <span class="amount">Rp {{ number_format($dpSubtotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($dpPPN > 0)
                        <div class="summary-item clearfix">
                            <span class="label">PPN 11%:</span>
                            <span class="amount">Rp {{ number_format($dpPPN, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="total-final clearfix">
                        <span class="label">Total DP:</span>
                        <span class="amount">Rp {{ number_format($totalDP, 0, ',', '.') }}</span>
                    </div>
                @elseif ($uangMuka > 0)
                    {{-- Invoice Pelunasan --}}
                    @php
                        $nilaiTerbilang = $totalTagihan;
                        $sisaSetelahUM = $grandTotal - $uangMuka;
                        $pelunasanBersih = $sisaSetelahUM / 1.11;
                        $ppnPelunasan = $pelunasanBersih * 0.11;
                    @endphp
                    <div class="summary-item clearfix">
                        <span class="label">Jmh.Total:</span>
                        <span class="amount">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-item clearfix">
                        <span class="label">Uang Muka:</span>
                        <span class="amount">(Rp {{ number_format($uangMuka, 0, ',', '.') }})</span>
                    </div>
                    <div style="border-top: 1px solid #ddd; margin: 5px 0;"></div>
                    <div class="summary-item clearfix">
                        <span class="label">Pelunasan:</span>
                        <span class="amount">Rp {{ number_format($pelunasanBersih, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-item clearfix">
                        <span class="label">PPN 11%:</span>
                        <span class="amount">Rp {{ number_format($ppnPelunasan, 0, ',', '.') }}</span>
                    </div>
                    <div style="border-top: 1px solid #ddd; margin: 5px 0;"></div>
                    <div class="total-final clearfix">
                        <span class="label">Total:</span>
                        <span class="amount">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                    </div>
                @else
                    {{-- Invoice Normal --}}
                    <div class="summary-item clearfix">
                        <span class="label">Subtotal:</span>
                        <span class="amount">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($invoice->diskon_nominal > 0)
                        <div class="summary-item summary-highlight clearfix">
                            <span class="label">Diskon ({{ number_format($invoice->diskon_persen, 1) }}%):</span>
                            <span class="amount">-Rp {{ number_format($invoice->diskon_nominal, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if ($invoice->ppn > 0)
                        <div class="summary-item clearfix">
                            <span class="label">PPN 11%:</span>
                            <span class="amount">Rp {{ number_format($invoice->ppn, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if ($invoice->ongkos_kirim > 0)
                        <div class="summary-item clearfix">
                            <span class="label">Ongkos Kirim:</span>
                            <span class="amount">Rp {{ number_format($invoice->ongkos_kirim, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="total-final clearfix">
                        <span class="label">TOTAL:</span>
                        <span class="amount">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>
        </div>

        @if (isset($dpAmount) && $dpAmount > 0)
            {{-- Note untuk DP --}}
            <div style="margin: 10px 10px; padding: 10px; background-color: #f8fafc; border-left: 3px solid #E74C3C;">
                <strong style="color: #2c3e50; font-size: 10px;">Note:</strong>
                <div style="font-size: 9px; margin-top: 5px;">
                    @php
                        $totalPO = $invoice->salesOrder->total ?? 0;
                        $persenDP = $totalPO > 0 ? ($dpAmount / $totalPO) * 100 : 0;
                        $nomorPO = $invoice->salesOrder->nomor_po ?? '-';
                    @endphp
                    Pembayaran DP {{ number_format($persenDP, 2) }}% untuk PO: {{ $nomorPO }}<br>
                    Dengan total PO senilai Rp. {{ number_format($totalPO, 0, ',', '.') }}
                </div>
            </div>
        @endif

        <!-- Clear float untuk memastikan layout yang benar -->
        <div style="clear: both;"></div>

        <!-- Text Terbilang -->
        <div style="margin: 15px 10px; color: #1F2A44; font-size: 11px; font-style: italic;">
            <strong>Terbilang:</strong> {{ ucwords(terbilang((int) $nilaiTerbilang)) }} Rupiah
        </div>

        <!-- Notes and Signature Section -->
        <div style="display: table; width: calc(100% - 20px); margin: 10px 10px;">
            <div style="display: table-cell; width: 60%; vertical-align: top; padding-right: 20px;">
                @if ($invoice->catatan)
                    <div
                        style="margin-bottom: 15px; border-left: 3px solid var(--hcb-orange); padding-left: 10px; background-color: #f8fafc;">
                        <strong style="color: #2c3e50; font-size: 10px;">Catatan:</strong>
                        <p style="font-size: 9px; margin: 2px 0;">{{ $invoice->catatan }}</p>
                    </div>
                @endif

                @if ($invoice->syarat_ketentuan)
                    <div
                        style="margin-bottom: 15px; border-left: 3px solid var(--hcb-green); padding-left: 10px; background-color: #f8fafc;">
                        <strong style="color: #2c3e50; font-size: 10px;">Syarat & Ketentuan:</strong>
                        <div style="font-size: 9px; margin-top: 5px; white-space: pre-line;">
                            {{ $invoice->syarat_ketentuan }}</div>
                    </div>
                @endif

                <div
                    style="margin-bottom: 15px; border-left: 3px solid var(--hcb-blue); padding-left: 10px; background-color: #f8fafc;">
                    <strong style="color: #2c3e50; font-size: 10px;">Informasi Pembayaran:</strong>
                    <div style="font-size: 9px; margin-top: 5px;">
                        Pembayaran Giro, Cek atau Transfer <br>

                        @if (isset($primaryBank) && $primaryBank)
                            Bank: {{ $primaryBank->nama_bank }}<br>
                            No. Rekening: {{ $primaryBank->nomor_rekening }}<br>
                            Atas Nama: {{ $primaryBank->atas_nama }}
                        @elseif(isset($bankAccounts) && $bankAccounts->isNotEmpty())
                            @php $firstBank = $bankAccounts->first(); @endphp
                            Bank: {{ $firstBank->nama_bank }}<br>
                            No. Rekening: {{ $firstBank->nomor_rekening }}<br>
                            Atas Nama: {{ $firstBank->atas_nama }}
                        @else
                            Bank: {{ setting('company_bank_name', 'Mandiri') }}<br>
                            No. Rekening: {{ setting('company_bank_account', '006.000.301.9563') }}<br>
                            Atas Nama: {{ setting('company_name', 'PT. Sinar Surya Semestaraya') }}
                        @endif

                        @if (isset($bankAccounts) && $bankAccounts->count() > 1)
                            <br><strong style="font-size: 8px; color: #666;">Bank Alternatif:</strong><br>
                            @foreach ($bankAccounts as $bank)
                                @if (!$primaryBank || $bank->id != $primaryBank->id)
                                    <span style="font-size: 8px;">{{ $bank->nama_bank }}: {{ $bank->nomor_rekening }}
                                        (a.n. {{ $bank->atas_nama }})
                                    </span><br>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Signature Section -->
            <div style="display: table-cell; width: 40%; vertical-align: top; text-align: center;">
                <div
                    style="margin-top: 30px; margin-bottom: 45px; font-weight: bold; color: var(--hcb-blue); font-size: 10px;">
                    Hormat Kami,
                </div>
                <div style="height: 60px; margin-bottom: 10px;"></div>
                <div style="border-top: 1px solid #cbd5e1; width: 80%; margin: 0 auto 10px auto;"></div>
                <div style="font-weight: bold; color: #1F2A44; font-size: 10px;">
                    {{ $template['direktur_nama'] }}
                </div>
                <div style="font-size: 8px; color: #64748b;">PT Hidayah Cahaya Berkah</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-fixed">
            <div style="font-size: 8px;">Dokumen dicetak digital {{ now()->format('d/m/Y H:i') }} WIB • <span
                    style="font-weight: 600; color: var(--hcb-blue);">SemestaPro</span></div>
        </div>
    </div>
</body>
