<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Absensi::with('karyawan')
            ->join('karyawans', 'absensis.karyawan_id', '=', 'karyawans.id')
            ->select('absensis.*', 'karyawans.nama as nama_karyawan', 'karyawans.departemen');

        // Apply filters
        if (isset($this->filters['tanggal_mulai']) && $this->filters['tanggal_mulai']) {
            $query->whereDate('absensis.tanggal', '>=', $this->filters['tanggal_mulai']);
        }

        if (isset($this->filters['tanggal_akhir']) && $this->filters['tanggal_akhir']) {
            $query->whereDate('absensis.tanggal', '<=', $this->filters['tanggal_akhir']);
        }

        if (isset($this->filters['karyawan_id']) && $this->filters['karyawan_id']) {
            $query->where('absensis.karyawan_id', $this->filters['karyawan_id']);
        }

        if (isset($this->filters['status']) && $this->filters['status']) {
            $query->where('absensis.status', $this->filters['status']);
        }

        if (isset($this->filters['departemen']) && $this->filters['departemen']) {
            $query->where('karyawans.departemen', $this->filters['departemen']);
        }

        return $query->orderBy('absensis.tanggal', 'desc')
            ->orderBy('karyawans.nama')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Karyawan',
            'Departemen',
            'Jam Masuk',
            'Jam Keluar',
            'Status',
            'Keterangan'
        ];
    }

    public function map($absensi): array
    {
        $statusLabels = [
            'hadir' => 'Hadir',
            'terlambat' => 'Terlambat',
            'alpha' => 'Alpha',
            'izin' => 'Izin',
            'cuti' => 'Cuti',
            'dinas_luar' => 'Dinas Luar'
        ];

        return [
            \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y'),
            $absensi->nama_karyawan,
            $absensi->departemen,
            $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '-',
            $absensi->jam_keluar ? \Carbon\Carbon::parse($absensi->jam_keluar)->format('H:i') : '-',
            $statusLabels[$absensi->status] ?? $absensi->status,
            $absensi->keterangan ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
