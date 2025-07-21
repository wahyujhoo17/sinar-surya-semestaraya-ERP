<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KaryawanTemplateExport implements FromArray, WithHeadings, WithStyles, WithEvents
{
    /**
     * @return array
     */
    public function array(): array
    {
        return [
            [
                'K001',
                'John Doe',
                'john.doe@example.com',
                '081234567890',
                'IT',
                'Software Developer',
                '01/01/2024',
                '8000000',
                'aktif',
                'Jl. Contoh No. 123, Jakarta',
                '15/06/1990',
                'laki-laki',
                'menikah',
                '1234567890123456',
                'karyawan'
            ],
            [
                'K002',
                'Jane Smith',
                'jane.smith@example.com',
                '081234567891',
                'HR',
                'HR Manager',
                '15/02/2024',
                '12000000',
                'aktif',
                'Jl. Sample No. 456, Bandung',
                '20/08/1988',
                'perempuan',
                'belum_menikah',
                '1234567890123457',
                'manager'
            ]
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIP',
            'Nama Lengkap',
            'Email',
            'No. Telepon',
            'Department',
            'Jabatan',
            'Tanggal Masuk (DD/MM/YYYY)',
            'Gaji Pokok',
            'Status (aktif/nonaktif/cuti/keluar)',
            'Alamat',
            'Tanggal Lahir (DD/MM/YYYY)',
            'Jenis Kelamin (laki-laki/perempuan)',
            'Status Pernikahan (menikah/belum_menikah)',
            'No. KTP',
            'Role User (admin/manager/karyawan)'
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
            // Style example rows
            2 => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'E8F4FD'],
                ],
            ],
            3 => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'E8F4FD'],
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
                foreach (range('A', 'O') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Add borders to all cells with data
                $sheet->getStyle('A1:O3')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Add instructions
                $sheet->setCellValue('A5', 'PETUNJUK PENGISIAN:');
                $sheet->getStyle('A5')->getFont()->setBold(true);

                $instructions = [
                    'A6' => '1. NIP harus unik dan tidak boleh kosong',
                    'A7' => '2. Email harus format yang valid dan unik',
                    'A8' => '3. Department dan Jabatan harus sudah ada di sistem',
                    'A9' => '4. Tanggal harus format DD/MM/YYYY',
                    'A10' => '5. Gaji Pokok harus berupa angka (tanpa titik/koma)',
                    'A11' => '6. Status: aktif, nonaktif, cuti, atau keluar',
                    'A12' => '7. Jenis Kelamin: laki-laki atau perempuan',
                    'A13' => '8. Status Pernikahan: menikah atau belum_menikah',
                    'A14' => '9. Role User: admin, manager, atau karyawan',
                    'A15' => '10. Hapus baris contoh sebelum import'
                ];

                foreach ($instructions as $cell => $text) {
                    $sheet->setCellValue($cell, $text);
                    $sheet->getStyle($cell)->getFont()->setSize(10);
                }
            },
        ];
    }
}
