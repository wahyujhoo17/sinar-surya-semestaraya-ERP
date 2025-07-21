<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KaryawanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
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
        $query = Karyawan::with(['department', 'jabatan', 'user.roles']);

        // Apply filters
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nip', 'like', '%' . $search . '%')
                    ->orWhere('nama_lengkap', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhereHas('department', function ($q) use ($search) {
                        $q->where('nama', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('jabatan', function ($q) use ($search) {
                        $q->where('nama', 'like', '%' . $search . '%');
                    });
            });
        }

        if (!empty($this->filters['department_id'])) {
            $query->where('department_id', $this->filters['department_id']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['tanggal_masuk'])) {
            $query->where('tanggal_masuk', $this->filters['tanggal_masuk']);
        }

        return $query->orderBy('nama_lengkap')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'NIP',
            'Nama Lengkap',
            'Email',
            'No. Telepon',
            'Department',
            'Jabatan',
            'Tanggal Masuk',
            'Gaji Pokok',
            'Status',
            'Alamat',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Status Pernikahan',
            'No. KTP',
            'Role User',
            'Tanggal Dibuat',
            'Terakhir Diupdate'
        ];
    }

    /**
     * @param mixed $karyawan
     * @return array
     */
    public function map($karyawan): array
    {
        static $no = 1;

        // Helper function to format date safely
        $formatDate = function ($date, $format = 'd/m/Y') {
            if (!$date) return '';

            if (is_string($date)) {
                try {
                    return \Carbon\Carbon::parse($date)->format($format);
                } catch (\Exception $e) {
                    return $date; // Return original string if parsing fails
                }
            }

            if ($date instanceof \Carbon\Carbon) {
                return $date->format($format);
            }

            return '';
        };

        return [
            $no++,
            $karyawan->nip,
            $karyawan->nama_lengkap,
            $karyawan->email,
            $karyawan->no_telepon,
            $karyawan->department ? $karyawan->department->nama : '',
            $karyawan->jabatan ? $karyawan->jabatan->nama : '',
            $formatDate($karyawan->tanggal_masuk),
            $karyawan->gaji_pokok ? 'Rp ' . number_format($karyawan->gaji_pokok, 0, ',', '.') : 'Rp 0',
            ucfirst($karyawan->status ?? ''),
            $karyawan->alamat,
            $formatDate($karyawan->tanggal_lahir),
            $karyawan->jenis_kelamin ? ucfirst($karyawan->jenis_kelamin) : '',
            $karyawan->status_pernikahan ? ucfirst($karyawan->status_pernikahan) : '',
            $karyawan->no_ktp,
            $karyawan->user && $karyawan->user->roles->isNotEmpty() ? $karyawan->user->roles->first()->nama : '',
            $formatDate($karyawan->created_at, 'd/m/Y H:i'),
            $formatDate($karyawan->updated_at, 'd/m/Y H:i')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as header
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '366092'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Auto-size columns
                foreach (range('A', 'R') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Add borders to all cells with data
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Center align for certain columns
                $sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // No
                $sheet->getStyle('H:H')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Tanggal Masuk
                $sheet->getStyle('I:I')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // Gaji Pokok
                $sheet->getStyle('J:J')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Status
                $sheet->getStyle('L:L')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Tanggal Lahir
                $sheet->getStyle('M:M')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Jenis Kelamin
                $sheet->getStyle('Q:R')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Tanggal dibuat & update
            },
        ];
    }
}
