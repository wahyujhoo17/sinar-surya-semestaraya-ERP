<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class LaporanPembelianSimpleExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
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
        $tanggalAwal = $this->filters['tanggal_awal'] ?? now()->startOfMonth()->format('Y-m-d');
        $tanggalAkhir = $this->filters['tanggal_akhir'] ?? now()->format('Y-m-d');
        $supplierId = $this->filters['supplier_id'] ?? null;
        $statusPembayaran = $this->filters['status_pembayaran'] ?? null;
        $search = $this->filters['search'] ?? null;

        // Query purchase_order
        $query = PurchaseOrder::query()
            ->with(['supplier'])
            ->select(
                'purchase_order.*',
                DB::raw('COALESCE(
                    (SELECT SUM(CAST(jumlah AS DECIMAL(15,2))) FROM pembayaran_hutang WHERE purchase_order_id = purchase_order.id), 
                    0
                ) as total_bayar')
            )
            ->whereBetween('purchase_order.tanggal', [$tanggalAwal, $tanggalAkhir])
            ->where('purchase_order.status', '!=', 'draft');

        // Filter berdasarkan supplier
        if ($supplierId) {
            $query->where('purchase_order.supplier_id', $supplierId);
        }

        // Filter berdasarkan status pembayaran
        if ($statusPembayaran) {
            $query->where('purchase_order.status_pembayaran', $statusPembayaran);
        }

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('purchase_order.nomor', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $dataPembelian = $query->orderBy('purchase_order.tanggal', 'desc')->get();

        // Group data per supplier untuk ringkasan
        $groupedData = $dataPembelian->groupBy('supplier_id')->map(function ($items) {
            $totalPembelian = $items->sum('total');
            $totalDibayar = $items->sum('total_bayar');
            return [
                'supplier' => $items->first()->supplier,
                'total_transaksi' => $items->count(),
                'total_pembelian' => $totalPembelian,
                'total_dibayar' => $totalDibayar,
                'sisa_pembayaran' => $totalPembelian - $totalDibayar,
            ];
        });

        // Hitung total
        $totalPembelian = $dataPembelian->sum('total');
        $totalDibayar = $dataPembelian->sum('total_bayar');
        $sisaPembayaran = $totalPembelian - $totalDibayar;

        return view('laporan.laporan_pembelian.excel_simple', [
            'groupedData' => $groupedData,
            'filters' => $this->filters,
            'totalPembelian' => $totalPembelian,
            'totalDibayar' => $totalDibayar,
            'sisaPembayaran' => $sisaPembayaran
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Laporan Pembelian Ringkas';
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Set style untuk header
        $sheet->getStyle('A5:F5')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set style untuk data
        $sheet->getStyle('A6:F' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set style untuk judul laporan
        $sheet->getStyle('A1:A3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Auto size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $sheet;
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,  // No
            'B' => 30, // Supplier
            'C' => 15, // Jumlah Transaksi
            'D' => 20, // Total Pembelian
            'E' => 20, // Total Dibayar
            'F' => 20, // Sisa
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

                // Set number format for currency columns
                $sheet->getStyle('D6:D' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('E6:E' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('F6:F' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');

                // Bold and background for total row
                if ($highestRow > 6) {
                    $sheet->getStyle('A' . $highestRow . ':F' . $highestRow)->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'DBEAFE'],
                        ],
                    ]);
                }
            },
        ];
    }
}
