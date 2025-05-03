<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use App\Models\KategoriProduk;
use App\Models\Satuan;

class TemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Contoh data
        return [
            [
                'PRD001', // Kode
                'Nama Produk', // Nama
                'Kimia', // jenis
                'SKU123', // SKU
                'Kategori 1', // Kategori
                'Merek A', // Merek
                'Sub Kategori A', // Sub Kategori
                'PCS', // Satuan
                '10x20', // Ukuran
                'Besi', // Tipe Material
                'Grade A', // Kualitas
                '10000', // Harga Beli
                '15000', // Harga Jual
                '5', // Stok Minimum
                'Aktif', // Status
            ]
        ];
    }

    public function headings(): array
    {
        // Get available kategoris and satuans
        $kategoris = KategoriProduk::pluck('nama')->implode(', ');
        $satuans = Satuan::pluck('nama')->implode(', ');

        return [
            ['TEMPLATE IMPORT PRODUK'],
            ['Petunjuk Pengisian:'],
            ['- Kode: Wajib diisi, maksimal 50 karakter, harus unik'],
            ['- Nama: Wajib diisi, maksimal 255 karakter'],
            ['- SKU: Opsional, maksimal 100 karakter'],
            ["- Kategori: Wajib diisi, pilihan: {$kategoris}"],
            ['- Merek: Opsional, maksimal 100 karakter'],
            ['- Sub Kategori: Opsional, maksimal 100 karakter'],
            ["- Satuan: Wajib diisi, pilihan: {$satuans}"],
            ['- Ukuran: Opsional, maksimal 50 karakter'],
            ['- Tipe Material: Opsional, maksimal 100 karakter'],
            ['- Kualitas: Opsional, maksimal 100 karakter'],
            ['- Harga Beli: Wajib diisi, angka positif'],
            ['- Harga Jual: Wajib diisi, angka positif'],
            ['- Stok Minimum: Opsional, angka positif'],
            ['- Status: Wajib diisi, pilihan: Aktif atau Nonaktif'],
            [],
            [
                'Kode*',
                'Nama*',
                'Janis',
                'SKU',
                'Kategori*',
                'Merek',
                'Sub Kategori',
                'Satuan*',
                'Ukuran',
                'Tipe Material',
                'Kualitas',
                'Harga Beli*',
                'Harga Jual*',
                'Stok Minimum',
                'Status*'
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk judul
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Style untuk petunjuk
        $sheet->getStyle('A2:A16')->getFont()->setSize(11);

        // Style untuk header kolom
        $sheet->getStyle('A18:N18')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5']
            ]
        ]);

        // Style untuk contoh data
        $sheet->getStyle('A19:N19')->applyFromArray([
            'font' => [
                'italic' => true,
                'color' => ['rgb' => '666666']
            ]
        ]);

        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // Kode
            'B' => 30, // Nama
            'C' => 15, // SKU
            'D' => 20, // Kategori
            'E' => 15, // Merek
            'F' => 15, // Sub Kategori
            'G' => 15, // Satuan
            'H' => 15, // Ukuran
            'I' => 15, // Tipe Material
            'J' => 15, // Kualitas
            'K' => 15, // Harga Beli
            'L' => 15, // Harga Jual
            'M' => 15, // Stok Minimum
            'N' => 15, // Status
        ];
    }
}
