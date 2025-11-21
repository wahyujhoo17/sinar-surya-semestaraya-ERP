<?php

namespace App\Exports;

use App\Models\SalesOrder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanSimpleExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
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
        $customerId = $this->filters['customer_id'] ?? null;
        $statusPembayaran = $this->filters['status_pembayaran'] ?? null;
        $search = $this->filters['search'] ?? null;

        // Query sales_order dengan invoice dan PO info
        $query = SalesOrder::query()
            ->with(['customer', 'invoices'])
            ->select(
                'sales_order.*',
                DB::raw('COALESCE(
                    (SELECT SUM(CAST(jumlah AS DECIMAL(15,2))) 
                     FROM pembayaran_piutang 
                     INNER JOIN invoice ON invoice.id = pembayaran_piutang.invoice_id 
                     WHERE invoice.sales_order_id = sales_order.id), 
                    0
                ) as total_bayar'),
                DB::raw('COALESCE(
                    (SELECT SUM(i.uang_muka_terapkan) FROM invoice i 
                     WHERE i.sales_order_id = sales_order.id), 
                    0
                ) as total_uang_muka'),
                DB::raw('(SELECT GROUP_CONCAT(DISTINCT i.nomor SEPARATOR ", ") 
                         FROM invoice i 
                         WHERE i.sales_order_id = sales_order.id) as nomor_invoice')
            )
            ->whereBetween('sales_order.tanggal', [$tanggalAwal, $tanggalAkhir]);

        // Filter berdasarkan customer
        if ($customerId) {
            $query->where('sales_order.customer_id', $customerId);
        }

        // Filter berdasarkan status pembayaran
        if ($statusPembayaran) {
            $query->where('sales_order.status_pembayaran', $statusPembayaran);
        }

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('sales_order.nomor', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $dataPenjualan = $query->orderBy('sales_order.tanggal', 'desc')->get();

        // Hitung total
        $totalPenjualan = $dataPenjualan->sum('total');
        $totalDibayar = $dataPenjualan->sum('total_bayar');
        $totalUangMuka = $dataPenjualan->sum('total_uang_muka');
        $sisaPembayaran = $totalPenjualan - $totalDibayar;

        return view('laporan.laporan_penjualan.excel_simple', [
            'dataPenjualan' => $dataPenjualan,
            'filters' => $this->filters,
            'totalPenjualan' => $totalPenjualan,
            'totalDibayar' => $totalDibayar,
            'totalUangMuka' => $totalUangMuka,
            'sisaPembayaran' => $sisaPembayaran
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Laporan Penjualan Ringkas';
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Set style untuk header
        $sheet->getStyle('A5:J5')->applyFromArray([
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
        $sheet->getStyle('A6:J' . ($sheet->getHighestRow()))->applyFromArray([
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
        foreach (range('A', 'J') as $col) {
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
            'B' => 12, // Tanggal
            'C' => 15, // No SO
            'D' => 20, // No Inv
            'E' => 15, // No PO
            'F' => 25, // Customer
            'G' => 20, // Total Penjualan
            'H' => 20, // Total Dibayar
            'I' => 20, // Uang Muka
            'J' => 20, // Sisa Pembayaran
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
                $sheet->getStyle('G6:G' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('H6:H' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('I6:I' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('J6:J' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');

                // Bold and background for total row
                if ($highestRow > 6) {
                    $sheet->getStyle('A' . $highestRow . ':J' . $highestRow)->applyFromArray([
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
