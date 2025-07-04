<?php

namespace App\Exports;

use App\Models\Cuti;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CutiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Cuti::with(['karyawan.department', 'karyawan.jabatan', 'approver']);

        // Apply filters
        if (isset($this->filters['search']) && !empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('karyawan', function ($sub) use ($search) {
                    $sub->where('nama_lengkap', 'like', '%' . $search . '%')
                        ->orWhere('nip', 'like', '%' . $search . '%');
                })
                    ->orWhere('jenis_cuti', 'like', '%' . $search . '%')
                    ->orWhere('keterangan', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        if (isset($this->filters['status']) && !empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (isset($this->filters['jenis_cuti']) && !empty($this->filters['jenis_cuti'])) {
            $query->where('jenis_cuti', $this->filters['jenis_cuti']);
        }

        if (isset($this->filters['karyawan_id']) && !empty($this->filters['karyawan_id'])) {
            $query->where('karyawan_id', $this->filters['karyawan_id']);
        }

        if (isset($this->filters['department_id']) && !empty($this->filters['department_id'])) {
            $query->whereHas('karyawan', function ($q) {
                $q->where('department_id', $this->filters['department_id']);
            });
        }

        if (isset($this->filters['tanggal_mulai']) && !empty($this->filters['tanggal_mulai'])) {
            $query->where('tanggal_mulai', '>=', $this->filters['tanggal_mulai']);
        }

        if (isset($this->filters['tanggal_selesai']) && !empty($this->filters['tanggal_selesai'])) {
            $query->where('tanggal_selesai', '<=', $this->filters['tanggal_selesai']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'NIP',
            'Nama Karyawan',
            'Department',
            'Jabatan',
            'Jenis Cuti',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Jumlah Hari',
            'Keterangan',
            'Status',
            'Disetujui Oleh',
            'Catatan Persetujuan',
            'Tanggal Pengajuan'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        static $no = 1;

        return [
            $no++,
            $row->karyawan->nip ?? '-',
            $row->karyawan->nama_lengkap ?? '-',
            $row->karyawan->department->nama ?? '-',
            $row->karyawan->jabatan->nama ?? '-',
            ucfirst($row->jenis_cuti),
            \Carbon\Carbon::parse($row->tanggal_mulai)->format('d/m/Y'),
            \Carbon\Carbon::parse($row->tanggal_selesai)->format('d/m/Y'),
            $row->jumlah_hari,
            $row->keterangan ?? '-',
            ucfirst($row->status),
            $row->approver->name ?? '-',
            $row->catatan_persetujuan ?? '-',
            \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],

            // Style all other rows
            'A:N' => [
                'font' => ['size' => 10],
                'alignment' => ['vertical' => 'center'],
            ],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 15,  // NIP
            'C' => 25,  // Nama
            'D' => 20,  // Department
            'E' => 20,  // Jabatan
            'F' => 15,  // Jenis Cuti
            'G' => 15,  // Tanggal Mulai
            'H' => 15,  // Tanggal Selesai
            'I' => 12,  // Jumlah Hari
            'J' => 30,  // Keterangan
            'K' => 15,  // Status
            'L' => 20,  // Disetujui Oleh
            'M' => 30,  // Catatan
            'N' => 20,  // Tanggal Pengajuan
        ];
    }
}
