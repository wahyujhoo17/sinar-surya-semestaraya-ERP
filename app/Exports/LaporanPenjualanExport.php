<?php

namespace App\Exports;

use App\Models\SalesOrder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithCustomStartCell, WithEvents
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

        // Query sales_order dengan detail produk dan invoice info
        $query = SalesOrder::query()
            ->with(['details.produk.satuan', 'customer', 'user', 'invoices'])
            ->select(
                'sales_order.*',
                DB::raw('COALESCE(
                    (SELECT SUM(pp.jumlah) FROM pembayaran_piutang pp 
                     JOIN invoice i ON pp.invoice_id = i.id 
                     WHERE i.sales_order_id = sales_order.id), 
                    0
                ) as total_bayar'),
                DB::raw('COALESCE(
                    (SELECT SUM(rp.total) FROM retur_penjualan rp 
                     WHERE rp.sales_order_id = sales_order.id), 
                    0
                ) as total_retur'),
                DB::raw('COALESCE(
                    (SELECT SUM(i.uang_muka_terapkan) FROM invoice i 
                     WHERE i.sales_order_id = sales_order.id), 
                    0
                ) as total_uang_muka'),
                DB::raw('(SELECT GROUP_CONCAT(DISTINCT i.nomor SEPARATOR ", ")
                         FROM invoice i
                         WHERE i.sales_order_id = sales_order.id) as nomor_invoice'),
                'sales_order.catatan as keterangan',
                'sales_order.created_at',
                'sales_order.updated_at',
                DB::raw('COALESCE(NULLIF(TRIM(customer.company), ""), NULLIF(TRIM(customer.nama), ""), customer.kode, CONCAT("Customer #", customer.id)) as customer_nama'),
                'customer.kode as customer_kode',
                'users.name as nama_petugas'
            )
            ->join('customer', 'sales_order.customer_id', '=', 'customer.id')
            ->leftJoin('users', 'sales_order.user_id', '=', 'users.id')
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
                    ->orWhere('customer.nama', 'like', "%{$search}%");
            });
        }

        $dataPenjualan = $query->orderBy('sales_order.tanggal', 'desc')->get();

        // Hitung total penjualan, total dibayar, dan sisa pembayaran
        $totalPenjualan = $dataPenjualan->sum('total');
        $totalDibayar = $dataPenjualan->sum('total_bayar');
        $sisaPembayaran = $totalPenjualan - $totalDibayar;

        return view('laporan.laporan_penjualan.excel', [
            'dataPenjualan' => $dataPenjualan,
            'filters' => $this->filters,
            'totalPenjualan' => $totalPenjualan,
            'totalDibayar' => $totalDibayar,
            'sisaPembayaran' => $sisaPembayaran
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Laporan Penjualan';
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Set style untuk header
        $sheet->getStyle('A5:R5')->applyFromArray([
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
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set style untuk data
        $sheet->getStyle('A6:R' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
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

        // Set auto size untuk semua kolom
        foreach (range('A', 'R') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // No Faktur
            'B' => 12, // Tanggal
            'C' => 15, // No Inv
            'D' => 15, // No PO
            'E' => 25, // Customer
            'F' => 15, // Kode Produk
            'G' => 30, // Nama Produk
            'H' => 10, // Qty
            'I' => 10, // Satuan
            'J' => 15, // Harga Satuan
            'K' => 10, // Disc (%)
            'L' => 18, // Subtotal
            'M' => 10, // PPN (%)
            'N' => 15, // PPN Nominal
            'O' => 18, // Total SO
            'P' => 18, // Dibayar
            'Q' => 15, // Status
            'R' => 20, // Petugas
        ];
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'A5';
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

                // Set text format for all currency and number columns to prevent percentage issue
                // Columns: J (Harga), L (Subtotal), N (PPN Nominal), O (Total SO), P (Dibayar)
                $sheet->getStyle('J6:J' . $highestRow)->getNumberFormat()->setFormatCode('@');
                $sheet->getStyle('L6:L' . $highestRow)->getNumberFormat()->setFormatCode('@');
                $sheet->getStyle('N6:P' . $highestRow)->getNumberFormat()->setFormatCode('@');

                // Set text format for percentage columns too
                $sheet->getStyle('K6:K' . $highestRow)->getNumberFormat()->setFormatCode('@');
                $sheet->getStyle('M6:M' . $highestRow)->getNumberFormat()->setFormatCode('@');
            },
        ];
    }
}
