<?php

namespace App\Exports;

use App\Models\BillOfMaterial;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BOMDetailExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $bomId;

    public function __construct(string|int $bomId)
    {
        $this->bomId = $bomId;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $bom = BillOfMaterial::with([
            'produk.kategori',
            'produk.satuan',
            'details.komponen.satuan',
            'details.satuan'
        ])->findOrFail($this->bomId);

        $totalMaterialCost = 0;
        foreach ($bom->details as $detail) {
            $qty = (float) $detail->quantity;
            $hargaBeli = (float) ($detail->komponen->harga_beli ?? 0);
            $subtotal = $qty * $hargaBeli;
            $detail->calculated_subtotal = $subtotal;
            $totalMaterialCost += $subtotal;
        }

        $overheadCost = (float) ($bom->overhead_cost ?? 0);
        $grandTotalCost = $totalMaterialCost + $overheadCost;

        return view('produksi.BOM.excel_detail', [
            'bom' => $bom,
            'totalMaterialCost' => $totalMaterialCost,
            'overheadCost' => $overheadCost,
            'grandTotalCost' => $grandTotalCost,
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Detail BOM';
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Table component header (Row 11)
        $sheet->getStyle('A11:H11')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E3A8A'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        return $sheet;
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,  // No
            'B' => 18, // Kode Komponen
            'C' => 35, // Nama Bahan Baku / Komponen
            'D' => 12, // Qty
            'E' => 10, // Satuan
            'F' => 18, // Harga Satuan (Rp)
            'G' => 20, // Subtotal (Rp)
            'H' => 25, // Catatan
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
                $highestRow = $sheet->getHighestRow();

                // Format currency
                if ($highestRow >= 12) {
                    $sheet->getStyle('F12:G' . $highestRow)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');

                    $sheet->getStyle('A12:A' . $highestRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $sheet->getStyle('D12:E' . $highestRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }
}
