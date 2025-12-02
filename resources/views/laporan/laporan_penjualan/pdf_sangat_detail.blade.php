<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Sangat Detail</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 7px;
            line-height: 1.4;
            color: #2d3748;
            margin: 10px 15px;
        }

        .header {
            margin-bottom: 10px;
            text-align: center;
        }

        .logo-container {
            margin-bottom: 5px;
        }

        .company-logo {
            max-width: 60px;
            max-height: 50px;
            height: auto;
        }

        .company-name {
            font-size: 12px;
            font-weight: bold;
            color: #1e293b;
            margin: 0;
        }

        .report-title {
            font-size: 10px;
            font-weight: bold;
            color: #334155;
            margin: 2px 0;
        }

        .report-period {
            font-size: 7px;
            color: #64748b;
        }

        .print-info {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 6.5px;
            color: #64748b;
        }

        .divider {
            border-top: 2px solid #3b82f6;
            margin: 6px 0;
        }

        .filter-info {
            margin-bottom: 8px;
            background: #f8fafc;
            padding: 5px 6px;
        }

        .filter-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .filter-info td {
            font-size: 6.5px;
            color: #475569;
            padding: 1px 0;
            vertical-align: top;
        }

        .filter-label {
            width: 80px;
            font-weight: 600;
            color: #334155;
        }

        .filter-separator {
            width: 10px;
            text-align: center;
        }

        .summary-row {
            margin-bottom: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 2px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-cell {
            width: 25%;
            padding: 5px 6px;
            background: #fafbfc;
            border-right: 1px solid #e5e7eb;
            text-align: center;
        }

        .summary-cell:last-child {
            border-right: none;
        }

        .summary-label {
            font-size: 6px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .summary-value {
            font-size: 9px;
            font-weight: bold;
            color: #1e293b;
        }

        .so-section {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .so-header {
            background: #dbeafe;
            padding: 6px 8px;
            margin-bottom: 4px;
            border-left: 4px solid #2563eb;
        }

        .so-header table {
            width: 100%;
            border-collapse: collapse;
        }

        .so-number {
            font-size: 8.5px;
            font-weight: bold;
            color: #1e40af;
        }

        .so-customer {
            font-size: 7px;
            color: #334155;
            margin-top: 1px;
        }

        .so-meta {
            font-size: 6.5px;
            color: #64748b;
            margin-top: 1px;
        }

        .so-status-cell {
            text-align: right;
            vertical-align: top;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 6.5px;
            font-weight: 600;
            border-radius: 2px;
        }

        .status-lunas {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .status-sebagian {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .status-belum {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table thead tr {
            background: #f9fafb;
            border-top: 1px solid #d1d5db;
            border-bottom: 1px solid #d1d5db;
        }

        .items-table th {
            font-size: 6.5px;
            font-weight: 600;
            color: #374151 !important;
            text-align: left;
            padding: 4px 4px;
            border-right: 1px solid #e5e7eb;
        }

        .items-table th:last-child {
            border-right: none;
        }

        .items-table th.text-center {
            text-align: center;
        }

        .items-table th.text-right {
            text-align: right;
        }

        .items-table td {
            padding: 3px 4px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 6.5px;
            color: #334155;
        }

        .items-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: 600;
        }

        .grand-summary {
            margin-top: 12px;
            background: #f8fafc;
            padding: 6px 8px;
            border-top: 2px solid #3b82f6;
        }

        .grand-summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .grand-summary-left {
            width: 60%;
            vertical-align: top;
        }

        .grand-summary-right {
            width: 40%;
            vertical-align: top;
        }

        .grand-summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .grand-summary-table td {
            font-size: 7.5px;
            padding: 2px 4px;
        }

        .grand-label {
            color: #475569;
            font-weight: 600;
            width: 120px;
        }

        .grand-value {
            text-align: right;
            font-weight: bold;
            color: #1e293b;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 6.5px;
            color: #94a3b8;
            text-align: center;
            padding: 8px 15px;
            border-top: 1px solid #e2e8f0;
            background: white;
        }

        @page {
            margin-bottom: 40px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        @php
            $logoSrc = null;
            if (isset($company) && $company && $company->logo) {
                $logoPath = public_path('storage/' . $company->logo);
                if (file_exists($logoPath)) {
                    $logoData = base64_encode(file_get_contents($logoPath));
                    $logoMimeType = mime_content_type($logoPath);
                    $logoSrc = 'data:' . $logoMimeType . ';base64,' . $logoData;
                }
            }

            if (!$logoSrc) {
                $defaultLogoPath = public_path('img/logo-sinar-surya.png');
                if (file_exists($defaultLogoPath)) {
                    $logoData = base64_encode(file_get_contents($defaultLogoPath));
                    $logoSrc = 'data:image/png;base64,' . $logoData;
                }
            }
        @endphp

        @if ($logoSrc)
            <div class="logo-container">
                <img src="{{ $logoSrc }}" alt="Logo" class="company-logo">
            </div>
        @endif

        <h1 class="company-name">{{ $company->nama ?? 'SINAR SURYA SEMESTARAYA' }}</h1>
        <h2 class="report-title">LAPORAN PENJUALAN SANGAT DETAIL</h2>
        <p class="report-period">
            Periode: {{ \Carbon\Carbon::parse($filters['tanggal_awal'])->format('d M Y') }} -
            {{ \Carbon\Carbon::parse($filters['tanggal_akhir'])->format('d M Y') }}
        </p>
        <div class="print-info">
            Dicetak: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <div class="divider"></div>

    <!-- Filter Info -->
    <div class="filter-info">
        <table>
            <tr>
                <td class="filter-label">Customer</td>
                <td class="filter-separator">:</td>
                <td>{{ $filters['customer_id'] ? 'Filter diterapkan' : 'Semua Customer' }}</td>
                <td class="filter-label" style="width: 110px;">Status Pembayaran</td>
                <td class="filter-separator">:</td>
                <td>{{ $filters['status_pembayaran'] ? ucfirst(str_replace('_', ' ', $filters['status_pembayaran'])) : 'Semua Status' }}
                </td>
            </tr>
            @if ($filters['search'])
                <tr>
                    <td class="filter-label">Pencarian</td>
                    <td class="filter-separator">:</td>
                    <td colspan="4">{{ $filters['search'] }}</td>
                </tr>
            @endif
        </table>
    </div>

    <!-- Summary -->
    <div class="summary-row">
        <table class="summary-table">
            <tr>
                <td class="summary-cell">
                    <div class="summary-label">Total Penjualan</div>
                    <div class="summary-value">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                </td>
                <td class="summary-cell">
                    <div class="summary-label">Total Dibayar</div>
                    <div class="summary-value">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</div>
                </td>
                <td class="summary-cell">
                    <div class="summary-label">Uang Muka</div>
                    <div class="summary-value">Rp {{ number_format($totalUangMuka, 0, ',', '.') }}</div>
                </td>
                <td class="summary-cell">
                    <div class="summary-label">Sisa Pembayaran</div>
                    <div class="summary-value">Rp {{ number_format($sisaPembayaran, 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Sales Orders with Details -->
    @forelse($dataPenjualan as $so)
        <div class="so-section">
            <!-- SO Header -->
            <div class="so-header">
                <table>
                    <tr>
                        <td style="width: 60%; vertical-align: top;">
                            <div class="so-number">{{ $so->nomor }}</div>
                            <div class="so-customer">
                                <strong>{{ $so->customer->company ?? $so->customer->nama }}</strong>
                                @if ($so->customer && $so->customer->kode)
                                    <span style="color: #94a3b8;"> ({{ $so->customer->kode }})</span>
                                @endif
                            </div>
                            @if ($so->customer && $so->customer->alamat)
                                <div style="font-size: 6px; color: #64748b; margin-top: 1px;">
                                    {{ $so->customer->alamat }}
                                </div>
                            @endif
                            <div class="so-meta">
                                Tanggal: {{ \Carbon\Carbon::parse($so->tanggal)->format('d M Y') }}
                                @if ($so->user)
                                    | Petugas: {{ $so->user->name }}
                                @endif
                            </div>
                        </td>
                        <td class="so-status-cell">
                            @php
                                $statusClass = match ($so->status_pembayaran) {
                                    'lunas' => 'status-lunas',
                                    'sebagian' => 'status-sebagian',
                                    default => 'status-belum',
                                };
                                $statusLabel = match ($so->status_pembayaran) {
                                    'lunas' => 'Lunas',
                                    'sebagian' => 'Dibayar Sebagian',
                                    'belum_bayar' => 'Belum Dibayar',
                                    default => ucfirst($so->status_pembayaran),
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            <div style="font-size: 6px; color: #64748b; margin-top: 2px;">
                                {{ $so->details->count() }} item produk
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 3%;" class="text-center">No</th>
                        <th style="width: 10%;">Kode Produk</th>
                        <th style="width: 20%;">Nama Produk</th>
                        <th style="width: 6%;" class="text-center">Qty</th>
                        <th style="width: 6%;" class="text-center">Satuan</th>
                        <th style="width: 10%;" class="text-right">Harga Satuan</th>
                        <th style="width: 6%;" class="text-right">Disc (%)</th>
                        <th style="width: 10%;" class="text-right">Subtotal</th>
                        <th style="width: 6%;" class="text-right">PPN %</th>
                        <th style="width: 10%;" class="text-right">PPN Rp</th>
                        <th style="width: 13%;" class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse($so->details as $detail)
                        @php
                            $subtotalAfterDisc = ($detail->subtotal ?? 0) - ($detail->diskon_nominal ?? 0);
                            $ppnPercentage = $so->ppn > 0 ? floatval($so->ppn) : 0;
                            $itemPpnNominal = $so->ppn > 0 ? ($ppnPercentage / 100) * $subtotalAfterDisc : 0;
                            $totalWithPpn = $subtotalAfterDisc + $itemPpnNominal;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $detail->produk->kode ?? '-' }}</td>
                            <td>{{ $detail->produk->nama ?? '-' }}</td>
                            <td class="text-center">{{ number_format($detail->quantity, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $detail->produk->satuan->nama ?? '-' }}</td>
                            <td class="text-right">{{ number_format($detail->harga ?? 0, 0, ',', '.') }}</td>
                            <td class="text-right">
                                {{ ($detail->diskon_persen ?? 0) == intval($detail->diskon_persen ?? 0) ? number_format($detail->diskon_persen ?? 0, 0, ',', '.') : number_format($detail->diskon_persen ?? 0, 2, ',', '.') }}
                            </td>
                            <td class="text-right">{{ number_format($detail->subtotal ?? 0, 0, ',', '.') }}</td>
                            <td class="text-right">
                                {{ $ppnPercentage > 0 ? number_format($ppnPercentage, 0) . '%' : '-' }}</td>
                            <td class="text-right">
                                {{ $itemPpnNominal > 0 ? number_format($itemPpnNominal, 0, ',', '.') : '-' }}</td>
                            <td class="text-right font-bold">{{ number_format($totalWithPpn, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center" style="padding: 10px; color: #94a3b8;">
                                Tidak ada detail item
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Summary Section -->
            <div style="margin-top: 8px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 60%;"></td>
                        <td style="width: 40%;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 7px;">
                                @php
                                    $itemSubtotal = $so->details->sum(function ($detail) {
                                        return ($detail->subtotal ?? 0) - ($detail->diskon_nominal ?? 0);
                                    });
                                    $totalPpnNominal = 0;
                                    if ($so->ppn > 0) {
                                        $ppnPercentage = floatval($so->ppn);
                                        foreach ($so->details as $detail) {
                                            $subtotalAfterDisc =
                                                ($detail->subtotal ?? 0) - ($detail->diskon_nominal ?? 0);
                                            $itemPpn = ($ppnPercentage / 100) * $subtotalAfterDisc;
                                            $totalPpnNominal += $itemPpn;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td
                                        style="padding: 2px 5px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b;">
                                        Subtotal Item</td>
                                    <td
                                        style="padding: 2px 5px; border-bottom: 1px solid #e5e7eb; text-align: right; color: #1e293b;">
                                        Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if ($so->ongkos_kirim > 0)
                                    <tr>
                                        <td
                                            style="padding: 2px 5px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b;">
                                            Ongkos Kirim</td>
                                        <td
                                            style="padding: 2px 5px; border-bottom: 1px solid #e5e7eb; text-align: right; color: #1e293b;">
                                            Rp {{ number_format($so->ongkos_kirim, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @if ($so->ppn > 0)
                                    @php
                                        $ppnPercentage = floatval($so->ppn);
                                    @endphp
                                    <tr>
                                        <td
                                            style="padding: 2px 5px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b;">
                                            PPN ({{ number_format($ppnPercentage, 0) }}%)</td>
                                        <td
                                            style="padding: 2px 5px; border-bottom: 1px solid #e5e7eb; text-align: right; color: #1e293b;">
                                            Rp {{ number_format($totalPpnNominal, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr style="border-top: 2px solid #cbd5e1;">
                                    <td style="padding: 3px 5px; font-weight: 700; color: #334155;">Total Penjualan</td>
                                    <td style="padding: 3px 5px; text-align: right; font-weight: 700; color: #1e293b;">
                                        Rp {{ number_format($so->total, 0, ',', '.') }}</td>
                                </tr>
                                @if ($so->total_uang_muka > 0)
                                    <tr>
                                        <td
                                            style="padding: 2px 5px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b;">
                                            Uang Muka</td>
                                        <td
                                            style="padding: 2px 5px; border-bottom: 1px solid #e5e7eb; text-align: right; font-weight: 600; color: #7c3aed;">
                                            Rp {{ number_format($so->total_uang_muka, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td
                                        style="padding: 2px 5px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b;">
                                        Total Dibayar</td>
                                    <td
                                        style="padding: 2px 5px; border-bottom: 1px solid #e5e7eb; text-align: right; font-weight: 600; color: #059669;">
                                        Rp {{ number_format($so->total_bayar, 0, ',', '.') }}</td>
                                </tr>
                                @if ($so->total - $so->total_bayar > 0)
                                    <tr>
                                        <td style="padding: 2px 5px; font-weight: 600; color: #64748b;">Sisa</td>
                                        <td
                                            style="padding: 2px 5px; text-align: right; font-weight: 600; color: #dc2626;">
                                            Rp {{ number_format($so->total - $so->total_bayar, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Payment History -->
            @if ($so->invoices && $so->invoices->count() > 0)
                @php
                    $allPayments = collect();
                    foreach ($so->invoices as $invoice) {
                        if ($invoice->pembayaranPiutang && $invoice->pembayaranPiutang->count() > 0) {
                            $allPayments = $allPayments->merge($invoice->pembayaranPiutang);
                        }
                    }
                @endphp
                @if ($allPayments->count() > 0)
                    <div
                        style="margin-top: 10px; background: #f0f9ff; border-left: 4px solid #0284c7; border: 1px solid #bae6fd;">
                        <div style="background: #f9fafb; padding: 5px 8px; border-bottom: 2px solid #d1d5db;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="width: 60%;">
                                        <span style="font-size: 7.5px; font-weight: 700; color: #374151;">
                                            RIWAYAT PEMBAYARAN
                                        </span>
                                    </td>
                                    <td style="width: 40%; text-align: right;">
                                        <span style="font-size: 6px; color: #64748b; font-weight: 600;">
                                            {{ $allPayments->count() }} transaksi pembayaran
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div style="padding: 6px 8px;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 6.5px;">
                                <thead>
                                    <tr>
                                        <th
                                            style="background: #f9fafb; color: #374151 !important; padding: 3px 4px; text-align: center; border-right: 1px solid #d1d5db; font-weight: 600; width: 5%;">
                                            #
                                        </th>
                                        <th
                                            style="background: #f9fafb; color: #374151 !important; padding: 3px 4px; text-align: left; border-right: 1px solid #d1d5db; font-weight: 600; width: 15%;">
                                            Tanggal
                                        </th>
                                        <th
                                            style="background: #f9fafb; color: #374151 !important; padding: 3px 4px; text-align: left; border-right: 1px solid #d1d5db; font-weight: 600; width: 18%;">
                                            No Invoice
                                        </th>
                                        <th
                                            style="background: #f9fafb; color: #374151 !important; padding: 3px 4px; text-align: left; border-right: 1px solid #d1d5db; font-weight: 600; width: 15%;">
                                            Metode
                                        </th>
                                        <th
                                            style="background: #f9fafb; color: #374151 !important; padding: 3px 4px; text-align: right; border-right: 1px solid #d1d5db; font-weight: 600; width: 20%;">
                                            Jumlah Bayar
                                        </th>
                                        <th
                                            style="background: #f9fafb; color: #374151 !important; padding: 3px 4px; text-align: left; font-weight: 600; width: 27%;">
                                            Keterangan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allPayments as $index => $bayar)
                                        <tr style="background: {{ $index % 2 == 0 ? '#ffffff' : '#f0f9ff' }};">
                                            <td
                                                style="padding: 3px 4px; text-align: center; border-bottom: 1px solid #bae6fd; color: #0369a1; font-weight: 600;">
                                                {{ $index + 1 }}
                                            </td>
                                            <td
                                                style="padding: 3px 4px; border-bottom: 1px solid #bae6fd; color: #1e293b;">
                                                <strong
                                                    style="color: #0f172a;">{{ \Carbon\Carbon::parse($bayar->tanggal)->format('d M Y') }}</strong><br>
                                                <span
                                                    style="font-size: 5.5px; color: #64748b;">{{ \Carbon\Carbon::parse($bayar->tanggal)->format('H:i') }}</span>
                                            </td>
                                            <td style="padding: 3px 4px; border-bottom: 1px solid #bae6fd;">
                                                <strong
                                                    style="color: #0369a1;">{{ $bayar->invoice->nomor ?? '-' }}</strong>
                                            </td>
                                            <td style="padding: 3px 4px; border-bottom: 1px solid #bae6fd;">
                                                <span
                                                    style="display: inline-block; background: {{ $bayar->metode_pembayaran == 'transfer' ? '#dbeafe' : ($bayar->metode_pembayaran == 'cash' ? '#d1fae5' : '#fef3c7') }}; 
                                                    color: {{ $bayar->metode_pembayaran == 'transfer' ? '#1e40af' : ($bayar->metode_pembayaran == 'cash' ? '#065f46' : '#92400e') }}; 
                                                    padding: 2px 5px; font-size: 6px; font-weight: 600; text-transform: uppercase; border: 1px solid {{ $bayar->metode_pembayaran == 'transfer' ? '#93c5fd' : ($bayar->metode_pembayaran == 'cash' ? '#6ee7b7' : '#fcd34d') }};">
                                                    {{ $bayar->metode_pembayaran ?? '-' }}
                                                </span>
                                            </td>
                                            <td
                                                style="padding: 3px 4px; text-align: right; border-bottom: 1px solid #bae6fd;">
                                                <strong style="color: #059669; font-size: 7px;">
                                                    Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}
                                                </strong>
                                            </td>
                                            <td
                                                style="padding: 3px 4px; border-bottom: 1px solid #bae6fd; color: #475569; font-size: 6px;">
                                                {{ $bayar->catatan ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr style="background: #e0f2fe;">
                                        <td colspan="4"
                                            style="padding: 4px 5px; text-align: right; font-weight: 700; color: #0c4a6e; font-size: 7px; border-top: 2px solid #0891b2;">
                                            TOTAL PEMBAYARAN:
                                        </td>
                                        <td
                                            style="padding: 4px 5px; text-align: right; font-weight: 700; color: #047857; font-size: 7.5px; border-top: 2px solid #0891b2;">
                                            Rp {{ number_format($allPayments->sum('jumlah'), 0, ',', '.') }}
                                        </td>
                                        <td style="padding: 4px 5px; border-top: 2px solid #0891b2;"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endif

            <!-- SO Footer with Notes -->
            @if ($so->catatan)
                <div style="margin-top: 8px; padding: 5px; background: #fffbeb; border-left: 3px solid #f59e0b;">
                    <div style="font-size: 7px; font-weight: 700; color: #92400e; margin-bottom: 2px;">Catatan:</div>
                    <div style="font-size: 6px; color: #78350f;">{{ $so->catatan }}</div>
                </div>
            @endif

            <div style="margin-top: 5px; color: #94a3b8; font-size: 6px; text-align: right;">
                Dibuat: {{ \Carbon\Carbon::parse($so->created_at)->format('d M Y H:i') }}
                @if ($so->updated_at != $so->created_at)
                    | Diupdate: {{ \Carbon\Carbon::parse($so->updated_at)->format('d M Y H:i') }}
                @endif
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 30px; color: #94a3b8;">
            Tidak ada data penjualan untuk periode ini
        </div>
    @endforelse

    <!-- Grand Summary -->
    @if ($dataPenjualan->count() > 0)
        <div class="grand-summary">
            <table>
                <tr>
                    <td class="grand-summary-left">
                        <strong style="font-size: 7.5px; color: #475569;">RINGKASAN KESELURUHAN</strong>
                        <div style="font-size: 6.5px; color: #64748b; margin-top: 2px;">
                            Total {{ $dataPenjualan->count() }} transaksi penjualan
                        </div>
                    </td>
                    <td class="grand-summary-right">
                        <table class="grand-summary-table">
                            <tr>
                                <td class="grand-label">Total Penjualan</td>
                                <td class="grand-value">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="grand-label">Uang Muka</td>
                                <td class="grand-value" style="color: #7c3aed;">Rp
                                    {{ number_format($totalUangMuka, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="grand-label">Total Dibayar</td>
                                <td class="grand-value" style="color: #059669;">Rp
                                    {{ number_format($totalDibayar, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="grand-label">Sisa Pembayaran</td>
                                <td class="grand-value"
                                    style="color: {{ $sisaPembayaran > 0 ? '#dc2626' : '#059669' }};">
                                    Rp {{ number_format($sisaPembayaran, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem ERP SemestaPro</p>
        <p>Laporan Penjualan Sangat Detail - {{ now()->format('Y') }}</p>
    </div>
</body>

</html>
