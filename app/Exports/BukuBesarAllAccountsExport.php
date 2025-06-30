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

class BukuBesarAllAccountsExport implements FromView, WithTitle, ShouldAutoSize, WithStyles, WithColumnWidths
{
    protected $tanggalAwal;
    protected $tanggalAkhir;

    public function __construct($tanggalAwal, $tanggalAkhir)
    {
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
    }

    public function view(): View
    {
        // Get all active accounts with transactions
        $accounts = AkunAkuntansi::where('is_active', true)
            ->where('tipe', 'detail')
            ->orderBy('kode')
            ->get();

        $accountsWithBalances = [];
        $totalsByCategory = [
            'asset' => 0,
            'liability' => 0,
            'equity' => 0,
            'income' => 0,
            'expense' => 0,
            'other' => 0
        ];

        foreach ($accounts as $account) {
            // Get period transactions
            $periodDebit = JurnalUmum::where('akun_id', $account->id)
                ->whereBetween('tanggal', [$this->tanggalAwal, $this->tanggalAkhir])
                ->sum('debit');

            $periodKredit = JurnalUmum::where('akun_id', $account->id)
                ->whereBetween('tanggal', [$this->tanggalAwal, $this->tanggalAkhir])
                ->sum('kredit');

            // Get opening balance (before start date)
            $openingDebit = JurnalUmum::where('akun_id', $account->id)
                ->where('tanggal', '<', $this->tanggalAwal)
                ->sum('debit');

            $openingKredit = JurnalUmum::where('akun_id', $account->id)
                ->where('tanggal', '<', $this->tanggalAwal)
                ->sum('kredit');

            // Calculate balances
            $kategori = $account->kategori;
            $openingBalance = $this->calculateBalance($kategori, $openingDebit, $openingKredit);
            $periodMutation = $this->calculateBalance($kategori, $periodDebit, $periodKredit);
            $endingBalance = $openingBalance + $periodMutation;

            // Total debit and kredit (all time up to end date)
            $totalDebit = JurnalUmum::where('akun_id', $account->id)
                ->where('tanggal', '<=', $this->tanggalAkhir)
                ->sum('debit');

            $totalKredit = JurnalUmum::where('akun_id', $account->id)
                ->where('tanggal', '<=', $this->tanggalAkhir)
                ->sum('kredit');

            // Only include accounts with transactions or non-zero balance
            if ($totalDebit > 0 || $totalKredit > 0 || $endingBalance != 0) {
                $accountsWithBalances[] = [
                    'account' => $account,
                    'opening_balance' => $openingBalance,
                    'period_debit' => $periodDebit,
                    'period_kredit' => $periodKredit,
                    'period_mutation' => $periodMutation,
                    'ending_balance' => $endingBalance,
                    'total_debit' => $totalDebit,
                    'total_kredit' => $totalKredit,
                ];

                // Add to category totals
                if (isset($totalsByCategory[$account->kategori])) {
                    $totalsByCategory[$account->kategori] += $endingBalance;
                } else {
                    $totalsByCategory['other'] += $endingBalance;
                }
            }
        }

        return view('exports.buku_besar_all_accounts', [
            'accounts' => $accountsWithBalances,
            'totalsByCategory' => $totalsByCategory,
            'tanggalAwal' => $this->tanggalAwal,
            'tanggalAkhir' => $this->tanggalAkhir,
            'grandTotal' => array_sum($totalsByCategory)
        ]);
    }

    public function title(): string
    {
        return 'Buku Besar - Semua Akun';
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

        // Find the detail table header row by looking for "Kode" in column A
        $detailHeaderRow = null;
        for ($row = 1; $row <= $lastRow; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();
            if ($cellValue === 'Kode') {
                $detailHeaderRow = $row;
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
            // Filter info
            3 => [
                'font' => [
                    'size' => 10,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];

        // Find and style summary headers (RINGKASAN PER KATEGORI)
        for ($row = 4; $row <= $lastRow; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();
            if (strpos($cellValue, 'RINGKASAN') !== false) {
                $styles[$row] = [
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E3F2FD'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                // Style the category headers row (next row after RINGKASAN)
                $styles[$row + 1] = [
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
                break;
            }
        }

        // Apply detail table header styling if found
        if ($detailHeaderRow) {
            $styles[$detailHeaderRow] = [
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

            // Apply data rows styling (from detail header + 1 to last row)
            $dataStartRow = $detailHeaderRow + 1;
            $styles['A' . $dataStartRow . ':I' . $lastRow] = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ];
        }

        // Amount columns (D,E,F,G,H) - right align
        $styles['D:H'] = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
        ];

        return $styles;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,  // Kode Akun
            'B' => 35,  // Nama Akun  
            'C' => 12,  // Kategori
            'D' => 18,  // Saldo Awal
            'E' => 18,  // Debit Periode
            'F' => 18,  // Kredit Periode
            'G' => 18,  // Mutasi
            'H' => 18,  // Saldo Akhir
            'I' => 10,  // Status
        ];
    }
}
