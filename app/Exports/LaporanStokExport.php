<?php

namespace App\Exports;

use App\Models\Produk;
use App\Models\StokProduk;
use App\Models\RiwayatStok;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanStokExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithCustomStartCell
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
        $kategoriId = $this->filters['kategori_id'] ?? null;
        $gudangId = $this->filters['gudang_id'] ?? null;
        $search = $this->filters['search'] ?? null;

        // Query stok barang dengan join tabel terkait
        $query = StokProduk::query()
            ->select(
                'stok_produk.id',
                'stok_produk.produk_id',
                'stok_produk.gudang_id',
                'stok_produk.jumlah as stok_akhir',
                'produk.nama as nama_barang',
                'produk.kode as kode_barang',
                'produk.stok_minimum',
                'produk.harga_jual',
                'kategori_produk.nama as kategori',
                'satuan.nama as satuan',
                'gudang.nama as gudang',
                'stok_produk.updated_at as tanggal_update'
            )
            ->join('produk', 'stok_produk.produk_id', '=', 'produk.id')
            ->leftJoin('kategori_produk', 'produk.kategori_id', '=', 'kategori_produk.id')
            ->leftJoin('satuan', 'produk.satuan_id', '=', 'satuan.id')
            ->join('gudang', 'stok_produk.gudang_id', '=', 'gudang.id');

        // Filter berdasarkan kategori produk
        if ($kategoriId) {
            $query->where('produk.kategori_id', $kategoriId);
        }

        // Filter berdasarkan gudang
        if ($gudangId) {
            $query->where('stok_produk.gudang_id', $gudangId);
        }

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('produk.nama', 'like', "%{$search}%")
                    ->orWhere('produk.kode', 'like', "%{$search}%");
            });
        }

        $dataStok = $query->get();

        // Loop setiap item untuk menghitung stok awal, barang masuk, dan barang keluar
        $result = $dataStok->map(function ($item) use ($tanggalAwal, $tanggalAkhir) {
            // Query riwayat untuk mendapatkan pergerakan stok
            $riwayatMasuk = RiwayatStok::where('produk_id', $item->produk_id)
                ->where('gudang_id', $item->gudang_id)
                ->where('jenis', 'masuk')
                ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                ->sum('jumlah_perubahan');

            $riwayatKeluar = RiwayatStok::where('produk_id', $item->produk_id)
                ->where('gudang_id', $item->gudang_id)
                ->where('jenis', 'keluar')
                ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                ->sum('jumlah_perubahan');

            // Hitung stok awal (stok akhir - masuk + keluar)
            $stokAwal = $item->stok_akhir - $riwayatMasuk + abs($riwayatKeluar);

            // Hitung nilai barang (stok akhir * harga)
            $nilaiBarang = $item->stok_akhir * $item->harga_jual;

            // Flag jika di bawah minimum
            $isBelowMinimum = $item->stok_akhir < $item->stok_minimum;

            return [
                'id' => $item->id,
                'produk_id' => $item->produk_id,
                'gudang_id' => $item->gudang_id,
                'kode_barang' => $item->kode_barang,
                'nama_barang' => $item->nama_barang,
                'kategori' => $item->kategori,
                'satuan' => $item->satuan,
                'gudang' => $item->gudang,
                'stok_awal' => $stokAwal,
                'barang_masuk' => $riwayatMasuk,
                'barang_keluar' => abs($riwayatKeluar),
                'stok_akhir' => $item->stok_akhir,
                'nilai_barang' => $nilaiBarang,
                'tanggal_update' => $item->tanggal_update,
                'is_below_minimum' => $isBelowMinimum
            ];
        });

        return view('laporan.laporan_stok.excel', [
            'data' => $result,
            'tanggal_awal' => $tanggalAwal,
            'tanggal_akhir' => $tanggalAkhir,
            'kategori' => $kategoriId ? \App\Models\KategoriProduk::find($kategoriId)->nama : 'Semua Kategori',
            'gudang' => $gudangId ? \App\Models\Gudang::find($gudangId)->nama : 'Semua Gudang',
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Laporan Stok Barang';
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'A1';
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,     // No
            'B' => 15,    // Kode
            'C' => 30,    // Nama Barang
            'D' => 15,    // Kategori
            'E' => 10,    // Satuan
            'F' => 15,    // Gudang
            'G' => 10,    // Stok Awal
            'H' => 10,    // Barang Masuk
            'I' => 10,    // Barang Keluar
            'J' => 10,    // Stok Akhir
            'K' => 15,    // Nilai Barang
            'L' => 15,    // Tanggal Update
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Format judul
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Format subtitle
        $sheet->mergeCells('A2:L2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Format header
        $sheet->getStyle('A4:L4')->getFont()->setBold(true);
        $sheet->getStyle('A4:L4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('DDEBF7');
        $sheet->getStyle('A4:L4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Format angka
        $sheet->getStyle('G5:K100')->getNumberFormat()->setFormatCode('#,##0');

        // Format konditional untuk stok di bawah minimum
        $conditionalStyles = [
            new \PhpOffice\PhpSpreadsheet\Style\Conditional()
        ];
        $conditionalStyles[0]->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_EXPRESSION)
            ->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_EQUAL)
            ->addCondition('TRUE')
            ->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFEB9C');

        // Set borders untuk semua cell data
        $sheet->getStyle('A4:L100')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return [
            'A4:L4' => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}
