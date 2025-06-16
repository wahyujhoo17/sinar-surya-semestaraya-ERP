<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class LaporanProduksiExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithCustomStartCell
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
        $search = $this->filters['search'] ?? null;

        // Query produksi dengan join tabel terkait
        $query = DB::table('work_order')
            ->select(
                'work_order.id',
                'work_order.nomor',
                'work_order.tanggal',
                'work_order.quantity as jumlah',
                'work_order.status',
                'work_order.catatan',
                'produk.nama as produk_nama',
                'produk.kode as produk_kode',
                'users.name as nama_petugas'
            )
            ->join('produk', 'work_order.produk_id', '=', 'produk.id')
            ->leftJoin('users', 'work_order.user_id', '=', 'users.id')
            ->whereBetween('work_order.tanggal', [$tanggalAwal, $tanggalAkhir]);

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('work_order.nomor', 'like', "%{$search}%")
                    ->orWhere('produk.nama', 'like', "%{$search}%")
                    ->orWhere('produk.kode', 'like', "%{$search}%");
            });
        }

        $dataProduksi = $query->orderBy('work_order.tanggal', 'desc')->get();

        // Hitung total produksi
        $totalProduksi = $dataProduksi->sum('jumlah');

        return view('laporan.laporan_produksi.excel', [
            'dataProduksi' => $dataProduksi,
            'filters' => $this->filters,
            'totalProduksi' => $totalProduksi
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Laporan Produksi';
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Set style untuk judul laporan
        $sheet->getStyle('A1:G3')->applyFromArray([
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
        $sheet->getStyle('A4:G4')->applyFromArray([
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
        $sheet->getStyle('A5:G' . ($sheet->getHighestRow()))->applyFromArray([
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
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Style untuk total row
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A' . ($lastRow) . ':G' . ($lastRow))->applyFromArray([
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
            'B' => 20, // Nomor 
            'C' => 15, // Tanggal
            'D' => 25, // Produk
            'E' => 15, // Jumlah
            'F' => 20, // Status
            'G' => 25, // Petugas
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
