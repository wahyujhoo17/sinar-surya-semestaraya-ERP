<?php

namespace App\Exports;

use App\Models\SalesOrder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithCustomStartCell
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

        // Query sales_order dengan join tabel terkait
        $query = SalesOrder::query()
            ->select(
                'sales_order.id',
                'sales_order.nomor as nomor_faktur',
                'sales_order.tanggal',
                'sales_order.customer_id',
                'sales_order.status_pembayaran as status',
                'sales_order.total',
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
                'sales_order.catatan as keterangan',
                'sales_order.created_at',
                'sales_order.updated_at',
                'customer.nama as customer_nama',
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
        // Set style untuk judul laporan
        $sheet->getStyle('A1:K3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set style untuk header
        $sheet->getStyle('A4:K4')->applyFromArray([
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
        $sheet->getStyle('A5:K' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set auto size untuk semua kolom
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Style untuk total row
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A' . ($lastRow) . ':K' . ($lastRow))->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F3F4F6'],
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                ],
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
            'A' => 5,  // No
            'B' => 20, // Nomor Faktur
            'C' => 15, // Tanggal
            'D' => 25, // Customer
            'E' => 15, // Status
            'F' => 20, // Total
            'G' => 20, // Total Bayar
            'H' => 20, // Retur
            'I' => 20, // Sisa
            'J' => 25, // Petugas
            'K' => 25, // Keterangan
        ];
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'A1';
    }
}
