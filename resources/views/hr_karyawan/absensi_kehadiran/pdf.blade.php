<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Absensi Karyawan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header h2 {
            margin: 5px 0 0 0;
            font-size: 14px;
            font-weight: normal;
            color: #666;
        }

        .info {
            margin-bottom: 20px;
        }

        .info p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            color: white;
        }

        .badge-hadir {
            background-color: #10b981;
        }

        .badge-terlambat {
            background-color: #f59e0b;
        }

        .badge-alpha {
            background-color: #ef4444;
        }

        .badge-izin {
            background-color: #3b82f6;
        }

        .badge-cuti {
            background-color: #8b5cf6;
        }

        .badge-dinas {
            background-color: #6366f1;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>PT SINAR SURYA SEMESTARAYA</h1>
        <h2>LAPORAN ABSENSI KARYAWAN</h2>
    </div>

    <div class="info">
        <p><strong>Tanggal Cetak:</strong> {{ date('d/m/Y H:i:s') }}</p>
        <p><strong>Total Data:</strong> {{ $absensis->count() }} record</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 20%;">Nama Karyawan</th>
                <th style="width: 15%;">Departemen</th>
                <th style="width: 10%;">Jam Masuk</th>
                <th style="width: 10%;">Jam Keluar</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 18%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absensis as $index => $absensi)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $absensi->nama_karyawan }}</td>
                    <td>{{ $absensi->departemen }}</td>
                    <td class="text-center">
                        {{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '-' }}
                    </td>
                    <td class="text-center">
                        {{ $absensi->jam_keluar ? \Carbon\Carbon::parse($absensi->jam_keluar)->format('H:i') : '-' }}
                    </td>
                    <td class="text-center">
                        @php
                            $statusLabels = [
                                'hadir' => 'Hadir',
                                'terlambat' => 'Terlambat',
                                'alpha' => 'Alpha',
                                'izin' => 'Izin',
                                'cuti' => 'Cuti',
                                'dinas_luar' => 'Dinas Luar',
                            ];
                            $statusClasses = [
                                'hadir' => 'badge-hadir',
                                'terlambat' => 'badge-terlambat',
                                'alpha' => 'badge-alpha',
                                'izin' => 'badge-izin',
                                'cuti' => 'badge-cuti',
                                'dinas_luar' => 'badge-dinas',
                            ];
                        @endphp
                        <span class="badge {{ $statusClasses[$absensi->status] ?? '' }}">
                            {{ $statusLabels[$absensi->status] ?? $absensi->status }}
                        </span>
                    </td>
                    <td>{{ $absensi->keterangan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px; color: #666;">
                        Tidak ada data absensi
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem ERP PT Sinar Surya Semestaraya</p>
    </div>
</body>

</html>
