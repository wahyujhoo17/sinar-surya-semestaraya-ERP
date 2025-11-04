<?php

namespace App\Exports;

use App\Models\AkunAkuntansi;
use App\Models\JurnalUmum;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BukuBesarMultipleAccountsExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths, ShouldAutoSize
{
    protected $akunIds;
    protected $tanggalAwal;
    protected $tanggalAkhir;
    protected $includeDrafts;
    protected $rowCount = 0;

    public function __construct($akunIds, $tanggalAwal, $tanggalAkhir, $includeDrafts = false)
    {
        $this->akunIds = $akunIds;
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->includeDrafts = $includeDrafts;
    }

    public function collection()
    {
        $data = collect();

        // Add header information
        $data->push(['PT. SINAR SURYA SEMESTARAYA']);
        $data->push(['BUKU BESAR - MULTIPLE ACCOUNTS']);
        $data->push(['Periode: ' . date('d/m/Y', strtotime($this->tanggalAwal)) . ' s/d ' . date('d/m/Y', strtotime($this->tanggalAkhir))]);
        $data->push(['Filter: ' . ($this->includeDrafts ? 'Termasuk Draft' : 'Hanya Posted')]);
        $data->push([]); // Empty row

        foreach ($this->akunIds as $akunId) {
            $akun = AkunAkuntansi::find($akunId);
            if (!$akun) continue;

            // Account header
            $data->push([]);
            $data->push(['AKUN: ' . $akun->kode . ' - ' . $akun->nama]);
            $data->push(['Kategori: ' . strtoupper($akun->kategori)]);

            // Get opening balance
            $openingDebit = JurnalUmum::where('akun_id', $akunId)
                ->where('tanggal', '<', $this->tanggalAwal);
            if (!$this->includeDrafts) {
                $openingDebit->where('is_posted', true);
            }
            $openingDebit = $openingDebit->sum('debit');

            $openingKredit = JurnalUmum::where('akun_id', $akunId)
                ->where('tanggal', '<', $this->tanggalAwal);
            if (!$this->includeDrafts) {
                $openingKredit->where('is_posted', true);
            }
            $openingKredit = $openingKredit->sum('kredit');

            $openingBalance = $this->calculateBalance($akun->kategori, $openingDebit, $openingKredit);

            // Get transactions
            $transaksi = JurnalUmum::where('akun_id', $akunId)
                ->whereBetween('tanggal', [$this->tanggalAwal, $this->tanggalAkhir]);
            if (!$this->includeDrafts) {
                $transaksi->where('is_posted', true);
            }
            $transaksi = $transaksi->orderBy('tanggal')
                ->orderBy('created_at')
                ->get();

            // Opening balance row
            $data->push([
                'Tanggal',
                'No. Referensi',
                'Keterangan',
                'Debit',
                'Kredit',
                'Saldo'
            ]);

            $data->push([
                '',
                '',
                'Saldo Awal',
                '',
                '',
                $openingBalance
            ]);

            // Transaction rows
            $runningBalance = $openingBalance;
            foreach ($transaksi as $trx) {
                $trxBalance = $this->calculateBalance($akun->kategori, $trx->debit, $trx->kredit);
                $runningBalance += $trxBalance;

                $data->push([
                    date('d/m/Y', strtotime($trx->tanggal)),
                    $trx->no_referensi ?? '-',
                    $trx->keterangan,
                    $trx->debit > 0 ? $trx->debit : '',
                    $trx->kredit > 0 ? $trx->kredit : '',
                    $runningBalance
                ]);
            }

            // Summary row
            $periodDebit = $transaksi->sum('debit');
            $periodKredit = $transaksi->sum('kredit');

            $data->push([]);
            $data->push([
                '',
                '',
                'Total Periode',
                $periodDebit,
                $periodKredit,
                ''
            ]);
            $data->push([
                '',
                '',
                'Saldo Akhir',
                '',
                '',
                $runningBalance
            ]);
            $data->push([
                '',
                '',
                'Total Transaksi: ' . $transaksi->count(),
                '',
                '',
                ''
            ]);
            $data->push([]); // Separator
        }

        $this->rowCount = $data->count();
        return $data;
    }

    public function headings(): array
    {
        return []; // Headings included in collection
    }

    public function title(): string
    {
        return 'Buku Besar Multiple';
    }

    private function calculateBalance($kategori, $debit, $kredit)
    {
        if (in_array($kategori, ['asset', 'expense'])) {
            return $debit - $kredit;
        }
        return $kredit - $debit;
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            3 => [
                'font' => ['size' => 10],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            4 => [
                'font' => ['size' => 10],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];

        // Find and style table headers
        for ($row = 1; $row <= $this->rowCount; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();

            // Account header rows
            if (strpos($cellValue, 'AKUN:') === 0) {
                $styles[$row] = [
                    'font' => ['bold' => true, 'size' => 12],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E3F2FD'],
                    ],
                ];
            }

            // Table header rows
            if ($cellValue === 'Tanggal') {
                $styles[$row] = [
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4472C4'],
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ];
            }
        }

        // Amount columns (D, E, F) - right align
        $styles['D:F'] = [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            'numberFormat' => ['formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1],
        ];

        return $styles;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,  // Tanggal
            'B' => 15,  // No. Referensi
            'C' => 45,  // Keterangan
            'D' => 18,  // Debit
            'E' => 18,  // Kredit
            'F' => 18,  // Saldo
        ];
    }
}
