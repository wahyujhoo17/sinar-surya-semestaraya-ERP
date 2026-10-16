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

class BOMExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $search = $this->filters['search_query'] ?? ($this->filters['search'] ?? null);
        $produkId = $this->filters['produk_id'] ?? null;
        $status = $this->filters['status'] ?? null;
        $startDate = $this->filters['start_date'] ?? null;
        $endDate = $this->filters['end_date'] ?? null;
        $costStatus = $this->filters['cost_status'] ?? null;

        $query = BillOfMaterial::with([
            'produk.kategori',
            'produk.satuan',
            'details.komponen.kategori',
            'details.komponen.satuan',
            'details.satuan'
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('bill_of_materials.nama', 'like', "%{$search}%")
                    ->orWhere('bill_of_materials.kode', 'like', "%{$search}%")
                    ->orWhere('bill_of_materials.deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('produk', function ($qProduk) use ($search) {
                        $qProduk->where('nama', 'like', "%{$search}%")
                            ->orWhere('kode', 'like', "%{$search}%");
                    });
            });
        }

        if ($produkId) {
            $query->where('bill_of_materials.produk_id', $produkId);
        }

        if ($status !== '' && $status !== null) {
            $query->where('bill_of_materials.is_active', $status == '1');
        }

        if ($startDate) {
            $query->whereDate('bill_of_materials.created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('bill_of_materials.created_at', '<=', $endDate);
        }

        if ($costStatus) {
            if ($costStatus === 'ready') {
                $query->whereHas('details.komponen', function ($q) {
                    $q->where('harga_beli', '>', 0);
                });
            } elseif ($costStatus === 'no_cost') {
                $query->whereHas('details')
                    ->whereDoesntHave('details.komponen', function ($q) {
                        $q->where('harga_beli', '>', 0);
                    });
            } elseif ($costStatus === 'empty') {
                $query->doesntHave('details');
            }
        }

        $boms = $query->orderBy('bill_of_materials.kode', 'asc')->get();

        // Pre-fetch creators from log_aktivitas in one fast batch query
        $logs = \Illuminate\Support\Facades\DB::table('log_aktivitas')
            ->where('detail', 'like', '%"bom_id":%')
            ->join('users', 'log_aktivitas.user_id', '=', 'users.id')
            ->orderBy('log_aktivitas.created_at', 'asc')
            ->select('log_aktivitas.detail', 'users.name')
            ->get();

        $bomCreators = [];
        foreach ($logs as $l) {
            $detail = json_decode($l->detail, true);
            if (isset($detail['bom_id']) && !isset($bomCreators[$detail['bom_id']])) {
                $bomCreators[$detail['bom_id']] = $l->name;
            }
        }

        // Calculate costs per BOM
        $totalMaterialCost = 0;
        $totalOverheadCost = 0;
        $grandTotalCost = 0;
        $totalComponentsCount = 0;

        foreach ($boms as $bom) {
            $bom->creator_name = $bomCreators[$bom->id] ?? $bom->creator_name;

            $matCost = 0;
            foreach ($bom->details as $detail) {
                $qty = (float) $detail->quantity;
                $hargaBeli = (float) ($detail->komponen->harga_beli ?? 0);
                $subtotal = $qty * $hargaBeli;
                $detail->calculated_subtotal = $subtotal;
                $matCost += $subtotal;
                $totalComponentsCount++;
            }
            $ovhCost = (float) ($bom->overhead_cost ?? 0);
            $totCost = $matCost + $ovhCost;

            $bom->calculated_material_cost = $matCost;
            $bom->calculated_overhead_cost = $ovhCost;
            $bom->calculated_total_cost = $totCost;
            $bom->component_count = $bom->details->count();

            $totalMaterialCost += $matCost;
            $totalOverheadCost += $ovhCost;
            $grandTotalCost += $totCost;
        }

        return view('produksi.BOM.excel', [
            'boms' => $boms,
            'filters' => $this->filters,
            'totalBOM' => $boms->count(),
            'activeBOM' => $boms->where('is_active', true)->count(),
            'inactiveBOM' => $boms->where('is_active', false)->count(),
            'totalComponentsCount' => $totalComponentsCount,
            'totalMaterialCost' => $totalMaterialCost,
            'totalOverheadCost' => $totalOverheadCost,
            'grandTotalCost' => $grandTotalCost,
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Daftar Detail BOM';
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Header columns (Row 5: A5:V5)
        $sheet->getStyle('A5:V5')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E3A8A'], // Navy
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
                'wrapText' => true,
            ],
        ]);

        $highestRow = $sheet->getHighestRow();

        // Data rows borders and alignment
        if ($highestRow >= 6) {
            $sheet->getStyle('A6:V' . $highestRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'D1D5DB'],
                    ],
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                ],
            ]);
        }

        // Title styling (Row 1-3)
        $sheet->getStyle('A1:V3')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
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
            'A' => 6,  // No BOM
            'B' => 24, // Kode BOM
            'C' => 30, // Nama BOM
            'D' => 8,  // Versi
            'E' => 12, // Status BOM
            'F' => 28, // Produk Jadi
            'G' => 16, // Kategori Produk
            'H' => 10, // Satuan Produk
            'I' => 8,  // No Komp
            'J' => 18, // Kode Komponen
            'K' => 32, // Nama Komponen
            'L' => 16, // Kategori Komponen
            'M' => 12, // Qty Komponen
            'N' => 10, // Satuan Komponen
            'O' => 18, // Harga Beli Satuan (Rp)
            'P' => 20, // Subtotal Bahan (Rp)
            'Q' => 22, // Catatan Komponen
            'R' => 18, // Biaya Overhead (Rp)
            'S' => 22, // Total HPP BOM (Rp)
            'T' => 15, // Tanggal Dibuat
            'U' => 22, // Pembuat BOM
            'V' => 25, // Keterangan BOM
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

                if ($highestRow >= 6) {
                    // Number formatting for currency: O (Harga Beli), P (Subtotal), R (Overhead), S (Total HPP)
                    $sheet->getStyle('O6:P' . $highestRow)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');

                    $sheet->getStyle('R6:S' . $highestRow)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');

                    // Quantity formatting
                    $sheet->getStyle('M6:M' . $highestRow)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.00');

                    // Center alignment
                    $sheet->getStyle('A6:A' . $highestRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $sheet->getStyle('D6:E' . $highestRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $sheet->getStyle('H6:I' . $highestRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $sheet->getStyle('N6:N' . $highestRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $sheet->getStyle('T6:T' . $highestRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }
}
