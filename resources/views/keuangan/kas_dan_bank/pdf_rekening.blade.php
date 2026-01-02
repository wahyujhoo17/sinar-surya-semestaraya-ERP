<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Rekening Bank - {{ $rekening->nama_bank }}</title>
    <style>
        @page {
            margin: 20mm 22mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.5;
            color: #2d3748;
            padding: 15px 20px;
        }

        /* Header Styling */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px 10px;
            border-bottom: 3px solid #2d3748;
            background-color: #f7fafc;
        }

        .header h1 {
            font-size: 16px;
            margin-bottom: 8px;
            text-transform: uppercase;
            color: #1a202c;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .header h2 {
            font-size: 13px;
            color: #4a5568;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .header .subtitle {
            font-size: 9px;
            color: #718096;
            margin-top: 5px;
        }

        /* Info Section */
        .info-section {
            margin-bottom: 15px;
            background-color: #f7fafc;
            padding: 10px;
            border: 1px solid #e2e8f0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 8px;
            font-size: 9px;
        }

        .info-table td:first-child {
            width: 140px;
            font-weight: bold;
            color: #2d3748;
        }

        .info-table td:nth-child(2) {
            width: 10px;
            text-align: center;
        }

        .info-table td:last-child {
            color: #4a5568;
        }

        /* Summary Section */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .summary-table td {
            width: 33.33%;
            padding: 12px 8px;
            text-align: center;
            border: 2px solid #e2e8f0;
        }

        .summary-table td.income-box {
            background-color: #f0fdf4;
            border-color: #86efac;
        }

        .summary-table td.expense-box {
            background-color: #fef2f2;
            border-color: #fca5a5;
        }

        .summary-table td.net-box {
            background-color: #eff6ff;
            border-color: #93c5fd;
        }

        .summary-label {
            display: block;
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .summary-amount {
            display: block;
            font-size: 12px;
            font-weight: bold;
        }

        .amount-income {
            color: #15803d;
        }

        .amount-expense {
            color: #dc2626;
        }

        .amount-net {
            color: #1e40af;
        }

        /* Filter Info */
        .filter-info {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 10px 12px;
            margin-bottom: 15px;
            font-size: 9px;
        }

        .filter-info strong {
            color: #92400e;
            font-weight: bold;
        }

        /* Transaction Table */
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .transaction-table thead {
            background-color: #2d3748;
            color: white;
        }

        .transaction-table th {
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #2d3748;
            font-size: 9px;
            text-transform: uppercase;
        }

        .transaction-table td {
            padding: 6px;
            border: 1px solid #cbd5e0;
            font-size: 9px;
        }

        .transaction-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .transaction-table tbody tr:nth-child(even) {
            background-color: #f7fafc;
        }

        .transaction-table tfoot tr {
            background-color: #e2e8f0;
            font-weight: bold;
        }

        .transaction-table tfoot td {
            padding: 8px 6px;
            border: 2px solid #cbd5e0;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Badge Styles */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 2px;
        }

        .badge-income {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #86efac;
        }

        .badge-expense {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        /* Footer */
        .footer {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 2px solid #e2e8f0;
            font-size: 8px;
            color: #718096;
            text-align: center;
        }

        .footer p {
            margin: 3px 0;
        }

        /* No Data Message */
        .no-data {
            text-align: center;
            padding: 50px 20px;
            color: #9ca3af;
            background-color: #f9fafb;
            border: 2px dashed #d1d5db;
            margin: 20px 0;
        }

        .no-data p {
            font-size: 11px;
            font-style: italic;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #1a202c;
            margin: 15px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #e2e8f0;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>Laporan Transaksi Rekening Bank</h1>
        <h2>{{ $rekening->nama_bank }}</h2>
        <p class="subtitle">
            No. Rekening: {{ $rekening->nomor_rekening }}
            @if ($rekening->nama_pemilik)
                | a.n. {{ $rekening->nama_pemilik }}
            @endif
        </p>
    </div>

    <!-- Info Section -->
    <div class="info-section">
        <table class="info-table">
            <tr>
                <td>Tanggal Cetak</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Saldo Saat Ini</td>
                <td>:</td>
                <td style="font-weight: bold; color: #1a202c;">Rp {{ number_format($rekening->saldo, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Status Rekening</td>
                <td>:</td>
                <td>
                    <span style="font-weight: bold; color: {{ $rekening->is_aktif ? '#15803d' : '#dc2626' }};">
                        {{ $rekening->is_aktif ? 'Aktif' : 'Non-aktif' }}
                    </span>
                </td>
            </tr>
            @if ($rekening->cabang)
                <tr>
                    <td>Cabang</td>
                    <td>:</td>
                    <td>{{ $rekening->cabang }}</td>
                </tr>
            @endif
        </table>
    </div>

    <!-- Filter Info -->
    @if ($filterInfo['jenis'] != 'all' || $filterInfo['tanggal_mulai'] || $filterInfo['tanggal_akhir'])
        <div class="filter-info">
            <strong>&#9432; Filter Diterapkan:</strong>
            @if ($filterInfo['jenis'] != 'all')
                Jenis: <strong>{{ $filterInfo['jenis'] == 'masuk' ? 'Transaksi Masuk' : 'Transaksi Keluar' }}</strong>
            @endif
            @if ($filterInfo['tanggal_mulai'] && $filterInfo['tanggal_akhir'])
                {{ $filterInfo['jenis'] != 'all' ? ' | ' : '' }}Periode:
                <strong>{{ \Carbon\Carbon::parse($filterInfo['tanggal_mulai'])->format('d/m/Y') }} -
                    {{ \Carbon\Carbon::parse($filterInfo['tanggal_akhir'])->format('d/m/Y') }}</strong>
            @elseif($filterInfo['periode'] && $filterInfo['periode'] != 'all')
                {{ $filterInfo['jenis'] != 'all' ? ' | ' : '' }}Periode: <strong>{{ $filterInfo['periode'] }} Hari
                    Terakhir</strong>
            @endif
        </div>
    @endif

    <!-- Summary Section -->
    <table class="summary-table">
        <tr>
            <td class="income-box">
                <span class="summary-label">Total Masuk</span>
                <span class="summary-amount amount-income">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</span>
            </td>
            <td class="expense-box">
                <span class="summary-label">Total Keluar</span>
                <span class="summary-amount amount-expense">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span>
            </td>
            <td class="net-box">
                <span class="summary-label">Perubahan Bersih</span>
                <span class="summary-amount amount-net">
                    {{ $totalMasuk - $totalKeluar >= 0 ? '+' : '-' }} Rp
                    {{ number_format(abs($totalMasuk - $totalKeluar), 0, ',', '.') }}
                </span>
            </td>
        </tr>
    </table>

    <!-- Transaction List -->
    <div class="section-title">Rincian Transaksi</div>

    @if ($transaksi->isEmpty())
        <div class="no-data">
            <p>&#9888; Tidak ada transaksi ditemukan berdasarkan filter yang diterapkan.</p>
        </div>
    @else
        <table class="transaction-table">
            <thead>
                <tr>
                    <th width="10%">Tanggal</th>
                    <th width="13%">No. Bukti</th>
                    <th width="32%">Keterangan</th>
                    <th width="15%">Penerima/Pengirim</th>
                    <th width="10%" class="text-center">Jenis</th>
                    <th width="20%" class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi as $trx)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}</td>
                        <td style="font-weight: 600;">{{ $trx->no_bukti }}</td>
                        <td>{{ $trx->keterangan }}</td>
                        <td>{{ $trx->nama_penerima ?: '-' }}</td>
                        <td class="text-center">
                            <span class="badge {{ $trx->jenis == 'masuk' ? 'badge-income' : 'badge-expense' }}">
                                {{ $trx->jenis == 'masuk' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </td>
                        <td class="text-right"
                            style="color: {{ $trx->jenis == 'masuk' ? '#15803d' : '#dc2626' }}; font-weight: bold;">
                            {{ $trx->jenis == 'masuk' ? '+' : '-' }} Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right" style="font-weight: bold;">JUMLAH TRANSAKSI:</td>
                    <td class="text-right" style="font-weight: bold;">{{ $transaksi->count() }} Transaksi</td>
                </tr>
            </tfoot>
        </table>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p style="font-weight: bold;">{{ config('app.name', 'ERP System') }}</p>
        <p>Dokumen ini dicetak secara otomatis oleh sistem</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i:s') }} WIB</p>
    </div>
</body>

</html>
