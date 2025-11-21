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

class LaporanPenjualanSangatDetailExport implements FromView, WithTitle, WithStyles, WithEvents
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

        // Query sales_order dengan detail produk, payment history, dan delivery orders
        $query = SalesOrder::query()
            ->with([
                'details.produk.satuan',
                'customer',
                'user',
                'invoices.pembayaranPiutang' => function ($query) {
                    $query->orderBy('tanggal', 'asc');
                },
                'deliveryOrders' => function ($query) {
                    $query->orderBy('tanggal', 'asc');
                }
            ])
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
        return 'Laporan Penjualan Sangat Detail';
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Set style untuk header
        $headerRow = 5;
        $sheet->getStyle("A{$headerRow}:Q{$headerRow}")->applyFromArray([
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
        $sheet->getStyle('A1:Q3')->applyFromArray([
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
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Set borders for all data
                $sheet->getStyle('A6:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Set number format for currency columns (I, K, L, M, N, O)
                // But apply text format to prevent percentage issue
                $currencyColumns = ['I', 'K', 'L', 'M', 'N', 'O'];
                foreach ($currencyColumns as $col) {
                    if ($sheet->cellExists($col . '6')) {
                        // Apply text format to all cells in these columns first
                        $sheet->getStyle($col . '6:' . $col . $highestRow)->getNumberFormat()->setFormatCode('@');
                    }
                }

                // Auto-size columns for better readability
                foreach (range('A', $highestColumn) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Set minimum widths for certain columns
                $minWidths = [
                    'A' => 8,  // No
                    'B' => 15, // No SO
                    'C' => 12, // Tanggal
                    'D' => 25, // Customer
                    'E' => 15, // Kode Produk
                    'F' => 30, // Nama Produk
                    'G' => 10, // Qty
                    'H' => 10, // Satuan
                    'I' => 15, // Harga Satuan
                    'J' => 10, // Disc (%)
                    'K' => 18, // Subtotal
                    'L' => 18, // Total Item
                    'M' => 18, // Total SO
                    'N' => 18, // Uang Muka
                    'O' => 18, // Dibayar
                    'P' => 15, // Status
                    'Q' => 20, // Petugas
                ];

                foreach ($minWidths as $column => $width) {
                    if ($sheet->getColumnDimension($column)->getWidth() < $width) {
                        $sheet->getColumnDimension($column)->setWidth($width);
                    }
                }
            },
        ];
    }
}
