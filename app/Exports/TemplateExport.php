<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class TemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Contoh data untuk memudahkan user
        return [
            [
                '', // Kode - kosong, akan auto-generate
                'Contoh Nama Produk', // Nama
                'Kimia', // Jenis (boleh kosong)
                'SKU001', // SKU
                'Bahan Kimia', // Kategori
                'ABC Brand', // Merek
                'Sub A', // Sub Kategori
                'PCS', // Satuan
                '10x20 cm', // Ukuran
                'Plastik', // Tipe Material
                'Grade A', // Kualitas
                '50000', // Harga Beli
                '75000', // Harga Jual
                '10', // Stok Minimum
                'Aktif', // Status
                'Gudang Utama', // Gudang (optional)
                '100', // Qty (optional)
            ],
            [
                '', // Baris kedua kosong untuk user isi
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '', // Gudang
                '', // Qty
            ]
        ];
    }

    public function headings(): array
    {
        return [
            [
                'Kode',
                'Nama',
                'Jenis',
                'SKU',
                'Kategori',
                'Merek',
                'Sub Kategori',
                'Satuan',
                'Ukuran',
                'Tipe Material',
                'Kualitas',
                'Harga Beli',
                'Harga Jual',
                'Stok Minimum',
                'Status',
                'Gudang',
                'Qty'
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Tambahkan note di atas header
        $sheet->insertNewRowBefore(1, 3);

        $sheet->setCellValue('A1', 'TEMPLATE IMPORT PRODUK');
        $sheet->setCellValue('A2', 'Petunjuk: Nama wajib diisi. Kode kosong = auto-generate. Kategori/Satuan akan dibuat otomatis jika belum ada. Jenis kosong = tidak dibuat. Gudang & Qty opsional untuk langsung input stok. Status: Aktif/Nonaktif');

        // Merge cells untuk petunjuk
        $sheet->mergeCells('A1:Q1');
        $sheet->mergeCells('A2:Q2');

        // Style judul
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Style petunjuk
        $sheet->getStyle('A2')->getFont()->setSize(10)->setItalic(true);
        $sheet->getStyle('A2')->getAlignment()->setWrapText(true);
        $sheet->getRowDimension(2)->setRowHeight(40);

        // Style untuk header kolom (baris 4 setelah insert)
        $sheet->getStyle('A4:Q4')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        // Style untuk contoh data (baris 5)
        $sheet->getStyle('A5:Q5')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FEF3C7']
            ],
            'font' => [
                'italic' => true,
                'color' => ['rgb' => '92400E']
            ]
        ]);

        // Border untuk data area
        $sheet->getStyle('A4:Q6')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // Kode
            'B' => 35, // Nama
            'C' => 15, // Jenis
            'D' => 15, // SKU
            'E' => 20, // Kategori
            'F' => 20, // Merek
            'G' => 20, // Sub Kategori
            'H' => 15, // Satuan
            'I' => 15, // Ukuran
            'J' => 20, // Tipe Material
            'K' => 15, // Kualitas
            'L' => 15, // Harga Beli
            'M' => 15, // Harga Jual
            'N' => 15, // Stok Minimum
            'O' => 12, // Status
            'P' => 20, // Gudang
            'Q' => 12, // Qty
        ];
    }
}
