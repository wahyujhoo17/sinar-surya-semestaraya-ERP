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

class LaporanPembelianSangatDetailExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
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

        // Query purchase_order dengan detail produk
        $query = PurchaseOrder::query()
            ->with(['details.produk.satuan', 'supplier', 'user'])
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

        // Hitung total pembelian, total dibayar, dan sisa pembayaran
        $totalPembelian = $dataPembelian->sum('total');
        $totalDibayar = $dataPembelian->sum('total_bayar');
        $sisaPembayaran = $totalPembelian - $totalDibayar;

        return view('laporan.laporan_pembelian.excel_sangat_detail', [
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
        return 'Laporan Pembelian Detail';
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Set style untuk header
        $headerRow = 5;
        $sheet->getStyle("A{$headerRow}:O{$headerRow}")->applyFromArray([
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
        $sheet->getStyle('A1:O3')->applyFromArray([
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
            'A' => 20, // No Faktur
            'B' => 12, // Tanggal
            'C' => 25, // Supplier
            'D' => 15, // Kode Produk
            'E' => 30, // Nama Produk
            'F' => 10, // Qty
            'G' => 10, // Satuan
            'H' => 15, // Harga Satuan
            'I' => 10, // Disc (%)
            'J' => 18, // Subtotal
            'K' => 18, // Total Item
            'L' => 18, // Total PO
            'M' => 18, // Dibayar
            'N' => 15, // Status
            'O' => 20, // Petugas
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
                $sheet->getStyle('A6:O' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Set number format for currency columns
                $sheet->getStyle('H6:H' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('J6:M' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
            },
        ];
    }
}
