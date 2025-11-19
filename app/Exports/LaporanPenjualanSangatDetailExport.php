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

class LaporanPenjualanSangatDetailExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
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

        // Query sales_order dengan detail produk
        $query = SalesOrder::query()
            ->with(['details.produk.satuan', 'customer', 'user'])
            ->select(
                'sales_order.*',
                DB::raw('COALESCE(
                    (SELECT SUM(CAST(jumlah AS DECIMAL(15,2))) 
                     FROM pembayaran_piutang 
                     INNER JOIN invoice ON invoice.id = pembayaran_piutang.invoice_id 
                     WHERE invoice.sales_order_id = sales_order.id), 
                    0
                ) as total_bayar')
            )
            ->whereBetween('sales_order.tanggal', [$tanggalAwal, $tanggalAkhir])
            ->where('sales_order.status', '!=', 'draft');

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

        // Hitung total penjualan, total dibayar, uang muka, dan sisa pembayaran
        $totalPenjualan = $dataPenjualan->sum('total');
        $totalDibayar = $dataPenjualan->sum('total_bayar');
        $totalUangMuka = $dataPenjualan->sum('total_uang_muka');
        $sisaPembayaran = $totalPenjualan - $totalDibayar;

        return view('laporan.laporan_penjualan.excel_sangat_detail', [
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
        return 'Laporan Penjualan Detail';
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Set style untuk header
        $headerRow = 5;
        $sheet->getStyle("A{$headerRow}:P{$headerRow}")->applyFromArray([
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

        // Set style untuk judul laporan
        $sheet->getStyle('A1:P3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
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
            'A' => 20, // No SO
            'B' => 12, // Tanggal
            'C' => 25, // Customer
            'D' => 15, // Kode Produk
            'E' => 30, // Nama Produk
            'F' => 10, // Qty
            'G' => 10, // Satuan
            'H' => 15, // Harga Satuan
            'I' => 10, // Disc (%)
            'J' => 18, // Subtotal
            'K' => 18, // Total Item
            'L' => 18, // Total SO
            'M' => 18, // Uang Muka
            'N' => 18, // Dibayar
            'O' => 15, // Status
            'P' => 20, // Petugas
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

                // Set borders for all data
                $sheet->getStyle('A6:P' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Set number format for currency columns
                $sheet->getStyle('H6:H' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('J6:N' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
            },
        ];
    }
}
