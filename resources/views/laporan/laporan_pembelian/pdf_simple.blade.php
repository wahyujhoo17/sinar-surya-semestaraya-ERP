<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pembelian Ringkas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            line-height: 1.5;
            color: #2d3748;
            margin: 15px 20px;
        }

        .header {
            margin-bottom: 12px;
        }

        .header table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 70px;
            vertical-align: middle;
            padding-right: 10px;
        }

        .logo {
            width: 60px;
            height: auto;
        }

        .header-text {
            vertical-align: middle;
        }

        .company-name {
            font-size: 13px;
            font-weight: bold;
            color: #1e293b;
            margin: 0 0 2px 0;
        }

        .report-title {
            font-size: 11px;
            font-weight: bold;
            color: #334155;
            margin: 0;
        }

        .report-period {
            font-size: 7.5px;
            color: #64748b;
            margin: 2px 0 0 0;
        }

        .print-info {
            text-align: right;
            vertical-align: middle;
        }

        .print-date {
            font-size: 7px;
            color: #64748b;
        }

        .divider {
            border-top: 2px solid #3b82f6;
            margin: 8px 0;
        }

        .filter-info {
            margin-bottom: 10px;
            background: #f8fafc;
            padding: 6px 8px;
        }

        .filter-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .filter-info td {
            font-size: 7px;
            color: #475569;
            padding: 1px 0;
            vertical-align: top;
        }

        .filter-label {
            width: 90px;
            font-weight: 600;
            color: #334155;
        }

        .filter-separator {
            width: 10px;
            text-align: center;
        }

        .summary-cards {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .summary-card {
            display: table-cell;
            width: 33.33%;
            padding: 8px;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            text-align: center;
        }

        .summary-card:first-child {
            border-top-left-radius: 3px;
            border-bottom-left-radius: 3px;
        }

        .summary-card:last-child {
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
        }

        .summary-label {
            font-size: 7px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .summary-value {
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table.data-table thead tr {
            background: #f9fafb;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        table.data-table th {
            font-size: 7px;
            font-weight: 600;
            color: #475569;
            text-align: left;
            padding: 4px 5px;
        }

        table.data-table td {
            padding: 4px 5px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 7.5px;
            color: #334155;
        }

        table.data-table tbody tr:nth-child(even) {
            background: #fafbfc;
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

        .total-row {
            background: #dbeafe !important;
            font-weight: bold;
        }

        .grand-summary {
            margin-top: 15px;
            background: #f8fafc;
            padding: 8px 10px;
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
            font-size: 8.5px;
            padding: 3px 5px;
        }

        .grand-label {
            color: #475569;
            font-weight: 600;
            width: 140px;
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
            font-size: 7px;
            color: #94a3b8;
            text-align: center;
            padding: 8px 20px;
            border-top: 1px solid #e2e8f0;
            background: white;
        }

        @page {
            margin-bottom: 40px;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: 600;
        }

        .status-lunas {
            background: #d1fae5;
            color: #065f46;
        }

        .status-sebagian {
            background: #fed7aa;
            color: #92400e;
        }

        .status-belum {
            background: #fecaca;
            color: #991b1b;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td class="logo-cell">
                    @php
                        $logoPath = public_path('img/SemestaPro.PNG');
                        $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
                    @endphp
                    @if ($logoData)
                        <img src="data:image/png;base64,{{ $logoData }}" alt="Logo" class="logo">
                    @endif
                </td>
                <td class="header-text">
                    <h1 class="company-name">SINAR SURYA SEMESTARAYA</h1>
                    <h2 class="report-title">LAPORAN PEMBELIAN RINGKAS</h2>
                    <p class="report-period">
                        Periode: {{ \Carbon\Carbon::parse($filters['tanggal_awal'])->format('d M Y') }} -
                        {{ \Carbon\Carbon::parse($filters['tanggal_akhir'])->format('d M Y') }}
                    </p>
                </td>
                <td class="print-info">
                    <div class="print-date">
                        Dicetak: {{ now()->format('d/m/Y H:i') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <!-- Filter Info -->
    <div class="filter-info">
        <table>
            <tr>
                <td class="filter-label">Supplier</td>
                <td class="filter-separator">:</td>
                <td>{{ $filters['supplier_id'] ? 'Filter diterapkan' : 'Semua Supplier' }}</td>
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

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="summary-label">Total Pembelian</div>
            <div class="summary-value">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Total Dibayar</div>
            <div class="summary-value">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Sisa Pembayaran</div>
            <div class="summary-value">Rp {{ number_format($sisaPembayaran, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Data Table - Ringkas Per Transaksi -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 12%;">No Faktur</th>
                <th style="width: 8%;" class="text-center">Tanggal</th>
                <th style="width: 20%;">Supplier</th>
                <th style="width: 14%;" class="text-right">Total Pembelian</th>
                <th style="width: 14%;" class="text-right">Total Dibayar</th>
                <th style="width: 14%;" class="text-right">Sisa Pembayaran</th>
                <th style="width: 14%;">Pembuat</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($dataPembelian as $po)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td><strong>{{ $po->nomor }}</strong></td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($po->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $po->supplier->nama ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($po->total, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($po->total_bayar, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($po->total - $po->total_bayar, 0, ',', '.') }}</td>
                    <td>{{ $po->user->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px; color: #94a3b8;">
                        Tidak ada data pembelian untuk periode ini
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if ($dataPembelian->count() > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="4" class="text-right font-bold">TOTAL</td>
                    <td class="text-right font-bold">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</td>
                    <td class="text-right font-bold">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</td>
                    <td class="text-right font-bold">Rp {{ number_format($sisaPembayaran, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem ERP SemestaPro</p>
        <p>Laporan Pembelian Ringkas - {{ now()->format('Y') }}</p>
    </div>
</body>

</html>
