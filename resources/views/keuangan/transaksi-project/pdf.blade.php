<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Project - {{ $project->nama }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0 5px 0;
        }

        .project-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #2563eb;
        }

        .project-info h3 {
            margin: 0 0 10px 0;
            color: #2563eb;
            font-size: 14px;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            width: 150px;
            font-weight: bold;
            padding: 3px 10px 3px 0;
        }

        .info-value {
            display: table-cell;
            padding: 3px 0;
        }

        .summary-cards {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }

        .summary-row {
            display: table-row;
        }

        .summary-card {
            display: table-cell;
            width: 25%;
            padding: 10px;
            margin: 0 5px;
            border-radius: 6px;
            text-align: center;
        }

        .summary-card.alokasi {
            background-color: #dbeafe;
            border: 1px solid #93c5fd;
        }

        .summary-card.penggunaan {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
        }

        .summary-card.pengembalian {
            background-color: #dcfce7;
            border: 1px solid #86efac;
        }

        .summary-card.saldo {
            background-color: #f3e8ff;
            border: 1px solid #c4b5fd;
        }

        .summary-label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 5px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .summary-amount {
            font-size: 13px;
            font-weight: bold;
        }

        .summary-card.alokasi .summary-amount {
            color: #1d4ed8;
        }

        .summary-card.penggunaan .summary-amount {
            color: #dc2626;
        }

        .summary-card.pengembalian .summary-amount {
            color: #16a34a;
        }

        .summary-card.saldo .summary-amount {
            color: #7c3aed;
        }

        .table-container {
            margin-top: 25px;
        }

        .table-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #1f2937;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }

        th {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            color: #374151;
        }

        td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            font-size: 11px;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tr:hover {
            background-color: #f3f4f6;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge.alokasi {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge.penggunaan {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .badge.pengembalian {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .amount-positive {
            color: #16a34a;
            font-weight: bold;
        }

        .amount-negative {
            color: #dc2626;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            font-size: 10px;
            color: #6b7280;
        }

        .footer-grid {
            display: table;
            width: 100%;
        }

        .footer-row {
            display: table-row;
        }

        .footer-cell {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-aktif {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .status-selesai {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-ditunda {
            background-color: #fef3c7;
            color: #d97706;
        }

        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
            font-style: italic;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header">
        <div class="company-name">ERP SINAR SURYA</div>
        <div class="report-title">LAPORAN TRANSAKSI PROJECT</div>
        <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
            Dicetak pada: {{ $tanggal_cetak }} oleh {{ $dicetak_oleh }}
        </div>
    </div>

    {{-- Project Information --}}
    <div class="project-info">
        <h3>INFORMASI PROJECT</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nama Project:</div>
                <div class="info-value">{{ $project->nama }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kode Project:</div>
                <div class="info-value">{{ $project->kode }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    <span class="status-badge status-{{ $project->status }}">
                        {{ ucfirst($project->status) }}
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Budget Awal:</div>
                <div class="info-value">Rp {{ number_format($project->budget, 0, ',', '.') }}</div>
            </div>
            @if ($project->tanggal_mulai)
                <div class="info-row">
                    <div class="info-label">Tanggal Mulai:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d/m/Y') }}</div>
                </div>
            @endif
            @if ($project->tanggal_selesai)
                <div class="info-row">
                    <div class="info-label">Tanggal Selesai:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($project->tanggal_selesai)->format('d/m/Y') }}
                    </div>
                </div>
            @endif
            @if ($project->deskripsi)
                <div class="info-row">
                    <div class="info-label">Deskripsi:</div>
                    <div class="info-value">{{ $project->deskripsi }}</div>
                </div>
            @endif
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="summary-cards">
        <div class="summary-row">
            <div class="summary-card alokasi">
                <div class="summary-label">Total Alokasi</div>
                <div class="summary-amount">Rp {{ number_format($summary['total_alokasi'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-card penggunaan">
                <div class="summary-label">Total Penggunaan</div>
                <div class="summary-amount">Rp {{ number_format($summary['total_penggunaan'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-card pengembalian">
                <div class="summary-label">Total Pengembalian</div>
                <div class="summary-amount">Rp {{ number_format($summary['total_pengembalian'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-card saldo">
                <div class="summary-label">Saldo Tersisa</div>
                <div class="summary-amount">Rp {{ number_format($summary['saldo_tersisa'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Transaction Table --}}
    <div class="table-container">
        <div class="table-title">DETAIL TRANSAKSI KEUANGAN</div>

        @if ($transaksi->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%;">No</th>
                        <th style="width: 12%;">Tanggal</th>
                        <th style="width: 12%;">Jenis</th>
                        <th style="width: 25%;">Keterangan</th>
                        <th style="width: 18%;">Sumber Dana</th>
                        <th style="width: 15%;">Jumlah</th>
                        <th style="width: 10%;">User</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi as $index => $trx)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge {{ $trx->jenis }}">{{ $trx->jenis }}</span>
                            </td>
                            <td>{{ $trx->keterangan }}</td>
                            <td>
                                @if ($trx->kas)
                                    <strong>Kas:</strong> {{ $trx->kas->nama }}
                                @elseif($trx->rekeningBank)
                                    <strong>Bank:</strong> {{ $trx->rekeningBank->nama_bank }}<br>
                                    <small>{{ $trx->rekeningBank->nomor_rekening }}</small>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-right">
                                <span
                                    class="{{ $trx->jenis === 'penggunaan' ? 'amount-negative' : 'amount-positive' }}">
                                    Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>{{ $trx->user->name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Summary Table --}}
            <div style="margin-top: 30px;">
                <div class="table-title">RINGKASAN KEUANGAN</div>
                <table style="width: 60%; margin-left: auto;">
                    <tbody>
                        <tr>
                            <td style="font-weight: bold; background-color: #f9fafb;">Total Alokasi</td>
                            <td class="text-right amount-positive">Rp
                                {{ number_format($summary['total_alokasi'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; background-color: #f9fafb;">Total Penggunaan</td>
                            <td class="text-right amount-negative">Rp
                                {{ number_format($summary['total_penggunaan'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; background-color: #f9fafb;">Total Pengembalian</td>
                            <td class="text-right amount-positive">Rp
                                {{ number_format($summary['total_pengembalian'], 0, ',', '.') }}</td>
                        </tr>
                        <tr style="border-top: 2px solid #374151;">
                            <td style="font-weight: bold; background-color: #e5e7eb; font-size: 13px;">Saldo Tersisa
                            </td>
                            <td class="text-right"
                                style="font-weight: bold; background-color: #e5e7eb; font-size: 13px; color: {{ $summary['saldo_tersisa'] >= 0 ? '#16a34a' : '#dc2626' }};">
                                Rp {{ number_format($summary['saldo_tersisa'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Progress Information --}}
            @php
                $progressPercentage =
                    $summary['total_alokasi'] > 0
                        ? ($summary['total_penggunaan'] / $summary['total_alokasi']) * 100
                        : 0;
            @endphp
            <div style="margin-top: 30px; background-color: #f8f9fa; padding: 15px; border-radius: 8px;">
                <div class="table-title">ANALISIS PENGGUNAAN DANA</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Persentase Penggunaan:</div>
                        <div class="info-value">
                            <strong
                                style="color: {{ $progressPercentage > 80 ? '#dc2626' : ($progressPercentage > 60 ? '#d97706' : '#16a34a') }};">
                                {{ number_format($progressPercentage, 1) }}%
                            </strong>
                            ({{ number_format($summary['total_penggunaan'], 0, ',', '.') }} dari
                            {{ number_format($summary['total_alokasi'], 0, ',', '.') }})
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status Anggaran:</div>
                        <div class="info-value">
                            @if ($progressPercentage > 100)
                                <span style="color: #dc2626; font-weight: bold;">OVER BUDGET</span>
                            @elseif($progressPercentage > 80)
                                <span style="color: #d97706; font-weight: bold;">HAMPIR HABIS</span>
                            @elseif($progressPercentage > 60)
                                <span style="color: #eab308; font-weight: bold;">PERLU PERHATIAN</span>
                            @else
                                <span style="color: #16a34a; font-weight: bold;">AMAN</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Total Transaksi:</div>
                        <div class="info-value"><strong>{{ $transaksi->count() }}</strong> transaksi</div>
                    </div>
                </div>
            </div>
        @else
            <div class="no-data">
                <strong>Tidak ada transaksi keuangan untuk project ini.</strong><br>
                Transaksi akan muncul setelah ada aktivitas keuangan pada project.
            </div>
        @endif
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-grid">
            <div class="footer-row">
                <div class="footer-cell">
                    <strong>Catatan:</strong><br>
                    • Laporan ini dibuat secara otomatis oleh sistem ERP<br>
                    • Data akurat pada waktu pencetakan<br>
                    • Untuk informasi lebih lanjut, hubungi bagian keuangan
                </div>
                <div class="footer-cell" style="text-align: right;">
                    <strong>SemestaPro</strong><br>
                    Sistem Manajemen Keuangan<br>
                    {{ config('app.url') }}
                </div>
            </div>
        </div>
    </div>
</body>

</html>
