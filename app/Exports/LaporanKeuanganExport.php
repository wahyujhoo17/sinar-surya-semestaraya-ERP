<?php

namespace App\Exports;

use App\Models\AkunAkuntansi;
use App\Models\JurnalUmum;
use App\Models\Kas;
use App\Models\RekeningBank;
use App\Models\TransaksiKas;
use App\Models\TransaksiBank;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanKeuanganExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithCustomStartCell
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
        $reportType = $this->filters['report_type'] ?? 'balance_sheet';
        $tanggalAwal = Carbon::parse($this->filters['tanggal_awal'] ?? now()->startOfMonth()->format('Y-m-d'))->startOfDay();
        $tanggalAkhir = Carbon::parse($this->filters['tanggal_akhir'] ?? now()->format('Y-m-d'))->endOfDay();

        $data = [];

        switch ($reportType) {
            case 'balance_sheet':
                $assets = $this->getAccountBalance('asset', $tanggalAkhir);
                $liabilities = $this->getAccountBalance('liability', $tanggalAkhir);
                $equity = $this->getAccountBalance('equity', $tanggalAkhir);

                $data = [
                    'assets' => $assets,
                    'liabilities' => $liabilities,
                    'equity' => $equity,
                    'totals' => [
                        'total_assets' => $assets->sum('balance'),
                        'total_liabilities' => $liabilities->sum('balance'),
                        'total_equity' => $equity->sum('balance')
                    ]
                ];
                break;

            case 'income_statement':
                $income = $this->getAccountBalanceForPeriod('income', $tanggalAwal, $tanggalAkhir);
                $expenses = $this->getAccountBalanceForPeriod('expense', $tanggalAwal, $tanggalAkhir);

                $data = [
                    'income' => $income,
                    'expenses' => $expenses,
                    'totals' => [
                        'total_income' => $income->sum('balance'),
                        'total_expenses' => $expenses->sum('balance'),
                        'net_income' => $income->sum('balance') - $expenses->sum('balance')
                    ]
                ];
                break;

            case 'cash_flow':
                // Get cash transactions
                $kasTransactions = TransaksiKas::with('kas')
                    ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                    ->selectRaw('
                        kas_id,
                        SUM(CASE WHEN jenis = "masuk" THEN jumlah ELSE 0 END) as total_masuk,
                        SUM(CASE WHEN jenis = "keluar" THEN jumlah ELSE 0 END) as total_keluar
                    ')
                    ->groupBy('kas_id')
                    ->get();

                $bankTransactions = TransaksiBank::with('rekening')
                    ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                    ->selectRaw('
                        rekening_id,
                        SUM(CASE WHEN jenis = "masuk" THEN jumlah ELSE 0 END) as total_masuk,
                        SUM(CASE WHEN jenis = "keluar" THEN jumlah ELSE 0 END) as total_keluar
                    ')
                    ->groupBy('rekening_id')
                    ->get();

                $data = [
                    'kas_transactions' => $kasTransactions,
                    'bank_transactions' => $bankTransactions,
                    'totals' => [
                        'total_kas_masuk' => $kasTransactions->sum('total_masuk'),
                        'total_kas_keluar' => $kasTransactions->sum('total_keluar'),
                        'total_bank_masuk' => $bankTransactions->sum('total_masuk'),
                        'total_bank_keluar' => $bankTransactions->sum('total_keluar'),
                        'total_masuk' => $kasTransactions->sum('total_masuk') + $bankTransactions->sum('total_masuk'),
                        'total_keluar' => $kasTransactions->sum('total_keluar') + $bankTransactions->sum('total_keluar')
                    ]
                ];
                break;
        }

        return view('laporan.laporan_keuangan.excel', [
            'data' => $data,
            'filters' => $this->filters,
            'reportType' => $reportType,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir
        ]);
    }

    /**
     * Get account balance for specific category up to specific date
     */
    private function getAccountBalance($category, $tanggalAkhir)
    {
        $accounts = AkunAkuntansi::where('kategori', $category)
            ->where('is_active', true)
            ->where('tipe', 'detail')
            ->orderBy('kode')
            ->get();

        return $accounts->map(function ($account) use ($tanggalAkhir) {
            $totalDebit = JurnalUmum::where('akun_id', $account->id)
                ->where('tanggal', '<=', $tanggalAkhir)
                ->sum('debit');

            $totalKredit = JurnalUmum::where('akun_id', $account->id)
                ->where('tanggal', '<=', $tanggalAkhir)
                ->sum('kredit');

            // Calculate balance based on account category
            if (in_array($account->kategori, ['asset', 'expense'])) {
                $balance = $totalDebit - $totalKredit;
            } else {
                $balance = $totalKredit - $totalDebit;
            }

            return [
                'kode' => $account->kode,
                'nama' => $account->nama,
                'balance' => $balance,
                'kategori' => $account->kategori
            ];
        })->filter(function ($account) {
            return $account['balance'] != 0;
        });
    }

    /**
     * Get account balance for specific category within period
     */
    private function getAccountBalanceForPeriod($category, $tanggalAwal, $tanggalAkhir)
    {
        // Get all active accounts in category that have journal entries in the period
        // Include both detail and header accounts that have actual transactions
        $accountsWithJournals = JurnalUmum::select('akun_id')
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->whereHas('akun', function ($query) use ($category) {
                $query->where('kategori', $category)
                    ->where('is_active', true);
            })
            ->distinct()
            ->pluck('akun_id');

        $accounts = AkunAkuntansi::whereIn('id', $accountsWithJournals)
            ->where('kategori', $category)
            ->where('is_active', true)
            ->orderBy('kode')
            ->get();

        return $accounts->map(function ($account) use ($tanggalAwal, $tanggalAkhir) {
            $totalDebit = JurnalUmum::where('akun_id', $account->id)
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->sum('debit');

            $totalKredit = JurnalUmum::where('akun_id', $account->id)
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->sum('kredit');

            // For income statement
            if ($account->kategori == 'income') {
                $balance = $totalKredit - $totalDebit;
            } else {
                $balance = $totalDebit - $totalKredit;
            }

            return [
                'kode' => $account->kode,
                'nama' => $account->nama,
                'balance' => $balance,
                'kategori' => $account->kategori
            ];
        })->filter(function ($account) {
            return $account['balance'] != 0;
        });
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $reportType = $this->filters['report_type'] ?? 'balance_sheet';
        $titles = [
            'balance_sheet' => 'Neraca',
            'income_statement' => 'Laba Rugi',
            'cash_flow' => 'Arus Kas'
        ];

        return $titles[$reportType] ?? 'Laporan Keuangan';
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
            'A' => 15,
            'B' => 40,
            'C' => 20,
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true]],
            'A:C' => ['alignment' => ['horizontal' => 'left']],
            'C:C' => ['alignment' => ['horizontal' => 'right']],
        ];
    }
}
