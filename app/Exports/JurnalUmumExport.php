<?php

namespace App\Exports;

use App\Models\JurnalUmum;
use App\Models\AkunAkuntansi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class JurnalUmumExport implements FromView, WithTitle, ShouldAutoSize, WithStyles, WithColumnWidths
{
    protected $startDate;
    protected $endDate;
    protected $akunId;
    protected $noReferensi;

    public function __construct($startDate = null, $endDate = null, $akunId = null, $noReferensi = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->akunId = $akunId;
        $this->noReferensi = $noReferensi;
    }

    public function view(): View
    {
        // Get filtered data
        $query = JurnalUmum::with(['akun', 'user'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }

        if ($this->akunId) {
            $query->where('akun_id', $this->akunId);
        }

        if ($this->noReferensi) {
            $query->where('no_referensi', 'like', '%' . $this->noReferensi . '%');
        }

        $jurnals = $query->get();

        // Calculate totals
        $totalDebit = $jurnals->sum('debit');
        $totalKredit = $jurnals->sum('kredit');

        // Get selected account info if any
        $selectedAkun = null;
        if ($this->akunId) {
            $selectedAkun = AkunAkuntansi::find($this->akunId);
        }

        return view('exports.jurnal_umum', [
            'jurnals' => $jurnals,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'selectedAkun' => $selectedAkun,
            'noReferensi' => $this->noReferensi
        ]);
    }

    public function title(): string
    {
        return 'Jurnal Umum';
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Find the header row by looking for "Tanggal" in column A
        $headerRow = null;
        for ($row = 1; $row <= $lastRow; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();
            if ($cellValue === 'Tanggal') {
                $headerRow = $row;
                break;
            }
        }

        $styles = [
            // Header company
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            // Header report
            2 => [
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            // Filter info rows (3-5 potential)
            3 => [
                'font' => [
                    'size' => 10,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            4 => [
                'font' => [
                    'size' => 10,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            5 => [
                'font' => [
                    'size' => 10,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];

        // Apply header row styling if found
        if ($headerRow) {
            $styles[$headerRow] = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ];

            // Apply data rows styling (from header row + 1 to last row)
            $dataStartRow = $headerRow + 1;
            $styles['A' . $dataStartRow . ':I' . $lastRow] = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ];
        }

        // Amount columns (F and G) - right align
        $styles['F:G'] = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
        ];

        return $styles;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,  // Tanggal
            'B' => 15,  // No. Referensi
            'C' => 12,  // Kode Akun
            'D' => 35,  // Nama Akun
            'E' => 40,  // Keterangan
            'F' => 18,  // Debit
            'G' => 18,  // Kredit
            'H' => 15,  // Dibuat Oleh
            'I' => 12,  // Tanggal Input
        ];
    }
}
