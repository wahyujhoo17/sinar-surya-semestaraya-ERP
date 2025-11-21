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

        // Query purchase_order dengan detail produk, penerimaan, dan pembayaran
        $query = PurchaseOrder::query()
            ->with([
                'details.produk.satuan',
                'supplier',
                'user',
                'penerimaan.gudang',
                'pembayaran' => function ($query) {
                    $query->orderBy('tanggal', 'asc');
                }
            ])
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

        // Transform data to ensure proper numeric formatting
        $dataPembelian = $dataPembelian->map(function ($item) {
            $item->total = (float) $item->total;
            $item->total_bayar = (float) $item->total_bayar;

            // Ensure detail prices are also properly formatted
            if ($item->details) {
                $item->details = $item->details->map(function ($detail) {
                    $detail->harga = (float) $detail->harga;
                    $detail->diskon_persen = (float) $detail->diskon_persen;
                    $detail->quantity = (float) $detail->quantity;
                    $detail->subtotal = (float) $detail->subtotal;
                    return $detail;
                });
            }

            return $item;
        });

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
        $sheet->getStyle("A{$headerRow}:P{$headerRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F2937'],
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
            'K' => 10, // PPN (%)
            'L' => 18, // PPN (Rp)
            'M' => 18, // Total PO
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
                            'color' => ['rgb' => 'D1D5DB'],
                        ],
                    ],
                ]);

                // Set number format for currency columns (H, J, L, M, N) to show numbers with thousand separators
                $sheet->getStyle('H6:H' . $highestRow)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                $sheet->getStyle('J6:J' . $highestRow)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');
                $sheet->getStyle('L6:N' . $highestRow)->getNumberFormat()->setFormatCode('#,##0_);(#,##0)');

                // Set percentage format for discount and PPN percentage columns (I, K)
                $sheet->getStyle('I6:I' . $highestRow)->getNumberFormat()->setFormatCode('0.00%');
                $sheet->getStyle('K6:K' . $highestRow)->getNumberFormat()->setFormatCode('0.00%');
            },
        ];
    }
}
