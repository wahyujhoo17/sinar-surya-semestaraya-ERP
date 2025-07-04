<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Cuti & Izin</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .header h2 {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
            font-weight: normal;
        }

        .company-info {
            margin-bottom: 10px;
        }

        .export-info {
            text-align: right;
            margin-bottom: 20px;
            font-size: 9px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }

        table td {
            font-size: 8px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .status-diajukan {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-disetujui {
            background-color: #d1edff;
            color: #004085;
        }

        .status-ditolak {
            background-color: #f8d7da;
            color: #721c24;
        }

        .jenis-cuti {
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }

        @page {
            margin: 15mm;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-info">
            <h1>PT SINAR SURYA SEMESTARAYA</h1>
            <h2>Laporan Data Cuti & Izin Karyawan</h2>
        </div>
    </div>

    <div class="export-info">
        <strong>Dicetak pada:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}<br>
        <strong>Total Data:</strong> {{ $cuti->count() }} pengajuan cuti
    </div>

    @if ($cuti->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 3%;">No</th>
                    <th style="width: 8%;">NIP</th>
                    <th style="width: 15%;">Nama Karyawan</th>
                    <th style="width: 10%;">Department</th>
                    <th style="width: 10%;">Jabatan</th>
                    <th style="width: 8%;">Jenis Cuti</th>
                    <th style="width: 8%;">Tgl Mulai</th>
                    <th style="width: 8%;">Tgl Selesai</th>
                    <th style="width: 5%;">Hari</th>
                    <th style="width: 15%;">Keterangan</th>
                    <th style="width: 7%;">Status</th>
                    <th style="width: 10%;">Disetujui Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cuti as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->karyawan->nip ?? '-' }}</td>
                        <td>{{ $item->karyawan->nama_lengkap ?? '-' }}</td>
                        <td>{{ $item->karyawan->department->nama ?? '-' }}</td>
                        <td>{{ $item->karyawan->jabatan->nama ?? '-' }}</td>
                        <td class="jenis-cuti">{{ ucfirst($item->jenis_cuti) }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                        </td>
                        <td class="text-center">{{ $item->jumlah_hari }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                        <td class="text-center">
                            <span class="status status-{{ $item->status }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>{{ $item->approver->name ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada data cuti yang ditemukan.</p>
        </div>
    @endif

    <div class="footer">
        <p>Laporan ini digenerate otomatis oleh sistem ERP PT Sinar Surya Semestaraya</p>
        <p>{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
    </div>
</body>

</html>
