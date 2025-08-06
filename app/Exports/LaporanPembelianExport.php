<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Facades\DB;

class LaporanPembelianExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithCustomStartCell, WithEvents
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

        // Query purchase_order dengan join tabel terkait
        $query = PurchaseOrder::query()
            ->select(
                'purchase_order.id',
                'purchase_order.nomor as nomor_faktur',
                'purchase_order.tanggal',
                'purchase_order.supplier_id',
                'purchase_order.status_pembayaran as status',
                'purchase_order.total',
                DB::raw('COALESCE(
                    (SELECT SUM(CAST(jumlah AS DECIMAL(15,2))) FROM pembayaran_hutang WHERE purchase_order_id = purchase_order.id), 
                    0
                ) as total_bayar'),
                'purchase_order.catatan as keterangan',
                'purchase_order.created_at',
                'purchase_order.updated_at',
                'supplier.nama as supplier_nama',
                'supplier.kode as supplier_kode',
                'users.name as nama_petugas'
            )
            ->join('supplier', 'purchase_order.supplier_id', '=', 'supplier.id')
            ->leftJoin('users', 'purchase_order.user_id', '=', 'users.id')
            ->whereBetween('purchase_order.tanggal', [$tanggalAwal, $tanggalAkhir]);

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
                    ->orWhere('supplier.nama', 'like', "%{$search}%");
            });
        }

        $dataPembelian = $query->orderBy('purchase_order.tanggal', 'desc')->get();

        // Transform data to ensure proper numeric formatting
        $dataPembelian = $dataPembelian->map(function ($item) {
            $item->total = (float) $item->total;
            $item->total_bayar = (float) $item->total_bayar;
            return $item;
        });

        // Hitung total pembelian, total dibayar, dan sisa pembayaran
        $totalPembelian = $dataPembelian->sum('total');
        $totalDibayar = $dataPembelian->sum('total_bayar');
        $sisaPembayaran = $totalPembelian - $totalDibayar;

        return view('laporan.laporan_pembelian.excel', [
            'dataPembelian' => $dataPembelian,
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
        return 'Laporan Pembelian';
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

        // Set auto size untuk semua kolom
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
            'B' => 20, // Nomor Faktur
            'C' => 15, // Tanggal
            'D' => 25, // Supplier
            'E' => 15, // Status
            'F' => 20, // Total
            'G' => 20, // Total Bayar
            'H' => 20, // Sisa
            'I' => 25, // Petugas
            'J' => 25, // Keterangan
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

                // Set number format for currency columns (F, G, H) to show numbers with thousand separators
                $sheet->getStyle('F6:F' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('G6:G' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('H6:H' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');

                // Also apply to total row
                $sheet->getStyle('F' . $highestRow . ':H' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
            },
        ];
    }
}
