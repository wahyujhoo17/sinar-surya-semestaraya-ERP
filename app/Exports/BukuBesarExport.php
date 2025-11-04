<?php

namespace App\Exports;

use App\Models\AkunAkuntansi;
use App\Models\JurnalUmum;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class BukuBesarExport implements FromView, WithTitle, ShouldAutoSize, WithStyles, WithColumnWidths
{
    protected $akunId;
    protected $tanggalAwal;
    protected $tanggalAkhir;
    protected $includeDrafts;

    public function __construct($akunId, $tanggalAwal, $tanggalAkhir, $includeDrafts = false)
    {
        $this->akunId = $akunId;
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->includeDrafts = $includeDrafts;
    }

    public function view(): View
    {
        $bukuBesarData = $this->getBukuBesarData();

        return view('exports.buku_besar_single_account', [
            'bukuBesarData' => $bukuBesarData,
            'tanggalAwal' => $this->tanggalAwal,
            'tanggalAkhir' => $this->tanggalAkhir,
            'includeDrafts' => $this->includeDrafts
        ]);
    }

    public function title(): string
    {
        $akun = AkunAkuntansi::find($this->akunId);
        return 'Buku Besar - ' . ($akun ? $akun->kode : 'Unknown');
    }

    private function getBukuBesarData()
    {
        $akun = AkunAkuntansi::findOrFail($this->akunId);

        // Get opening balance (before start date)
        $openingDebit = JurnalUmum::where('akun_id', $this->akunId)
            ->where('tanggal', '<', $this->tanggalAwal);

        if (!$this->includeDrafts) {
            $openingDebit->where('is_posted', true);
        }
        $openingDebit = $openingDebit->sum('debit');

        $openingKredit = JurnalUmum::where('akun_id', $this->akunId)
            ->where('tanggal', '<', $this->tanggalAwal);

        if (!$this->includeDrafts) {
            $openingKredit->where('is_posted', true);
        }
        $openingKredit = $openingKredit->sum('kredit');

        // Calculate opening balance based on account category
        $openingBalance = $this->calculateBalance($akun->kategori, $openingDebit, $openingKredit);

        // Get transactions for the period
        $transaksi = JurnalUmum::where('akun_id', $this->akunId)
            ->whereBetween('tanggal', [$this->tanggalAwal, $this->tanggalAkhir]);

        if (!$this->includeDrafts) {
            $transaksi->where('is_posted', true);
        }
        $transaksi = $transaksi->orderBy('tanggal')
            ->orderBy('created_at')
            ->get();

        // Calculate running balance
        $runningBalance = $openingBalance;
        $transaksiWithBalance = [];

        foreach ($transaksi as $trx) {
            $trxBalance = $this->calculateBalance($akun->kategori, $trx->debit, $trx->kredit);
            $runningBalance += $trxBalance;

            $transaksiWithBalance[] = [
                'transaksi' => $trx,
                'saldo' => $runningBalance
            ];
        }

        // Calculate period totals
        $periodDebit = $transaksi->sum('debit');
        $periodKredit = $transaksi->sum('kredit');
        $endingBalance = $runningBalance;

        return [
            'akun' => $akun,
            'opening_balance' => $openingBalance,
            'transaksi' => $transaksiWithBalance,
            'period_debit' => $periodDebit,
            'period_kredit' => $periodKredit,
            'ending_balance' => $endingBalance,
            'total_transaksi' => $transaksi->count()
        ];
    }

    private function calculateBalance($kategori, $debit, $kredit)
    {
        // For asset and expense accounts: debit increases balance
        if (in_array($kategori, ['asset', 'expense'])) {
            return $debit - $kredit;
        }

        // For liability, equity, and income accounts: credit increases balance
        return $kredit - $debit;
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Find the table header row by looking for "Tanggal" in column A
        $headerRow = null;
        for ($row = 1; $row <= $lastRow; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();
            if ($cellValue === 'Tanggal') {
                $headerRow = $row;
                break;
            }
        }

        $styles = [
            // Header company
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            // Header report
            2 => [
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];

        // Apply header row styling if found
        if ($headerRow) {
            $styles[$headerRow] = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ];

            // Apply data rows styling
            $dataStartRow = $headerRow + 1;
            $styles['A' . $dataStartRow . ':F' . $lastRow] = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ];
        }

        // Amount columns (D, E, F) - right align
        $styles['D:F'] = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
        ];

        return $styles;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,  // Tanggal
            'B' => 15,  // No. Referensi
            'C' => 40,  // Keterangan
            'D' => 18,  // Debit
            'E' => 18,  // Kredit
            'F' => 18,  // Saldo
        ];
    }
}
