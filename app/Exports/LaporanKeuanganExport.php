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

                // Group accounts by their parent (header)
                $assetsGrouped = $this->groupBalanceSheetAccounts($assets, 'asset');
                $liabilitiesGrouped = $this->groupBalanceSheetAccounts($liabilities, 'liability');
                $equityGrouped = $this->groupBalanceSheetAccounts($equity, 'equity');

                $data = [
                    'assets' => $assets,
                    'liabilities' => $liabilities,
                    'equity' => $equity,
                    'assets_grouped' => $assetsGrouped,
                    'liabilities_grouped' => $liabilitiesGrouped,
                    'equity_grouped' => $equityGrouped,
                    'totals' => [
                        'total_assets' => $assetsGrouped['total'],
                        'total_liabilities' => $liabilitiesGrouped['total'],
                        'total_equity' => $equityGrouped['total']
                    ]
                ];
                break;

            case 'income_statement':
                // Get the same data structure as the controller
                $request = new \Illuminate\Http\Request();
                $request->merge([
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d')
                ]);

                // Create temporary controller instance to reuse the logic
                $controller = new \App\Http\Controllers\Laporan\LaporanKeuanganController();
                $incomeStatementResponse = $controller->getIncomeStatement($request);
                $incomeStatementData = json_decode($incomeStatementResponse->getContent(), true);

                if ($incomeStatementData['success']) {
                    $data = $incomeStatementData['data'];
                } else {
                    // Fallback if there's an error
                    $data = [
                        'revenue' => [],
                        'cogs' => [],
                        'operating_expenses' => [],
                        'totals' => [
                            'total_revenue' => 0,
                            'total_cogs' => 0,
                            'gross_profit' => 0,
                            'total_operating_expenses' => 0,
                            'operating_income' => 0,
                            'net_income' => 0
                        ]
                    ];
                }
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
        // Get all active detail accounts for this category
        $accounts = AkunAkuntansi::where('kategori', $category)
            ->where('is_active', true)
            ->where('tipe', 'detail')
            ->orderBy('kode')
            ->get();

        // Calculate balance for each account from posted journal entries
        $accountsWithBalance = $accounts->map(function ($account) use ($tanggalAkhir) {
            // Sum debit and credit from posted journal entries up to specified date
            $totalDebit = JurnalUmum::where('akun_id', $account->id)
                ->where('tanggal', '<=', $tanggalAkhir)
                ->where('is_posted', true) // Only posted entries
                ->sum('debit');

            $totalKredit = JurnalUmum::where('akun_id', $account->id)
                ->where('tanggal', '<=', $tanggalAkhir)
                ->where('is_posted', true) // Only posted entries
                ->sum('kredit');

            // Calculate balance based on account category (normal balance)
            if (in_array($account->kategori, ['asset', 'expense'])) {
                // Normal debit balance
                $balance = $totalDebit - $totalKredit;
                $isAbnormal = $balance < -1; // Allow rounding errors, only flag if < -1
            } else {
                // Normal credit balance (liability, equity, income)
                $balance = $totalKredit - $totalDebit;
                $isAbnormal = $balance < -1; // Allow rounding errors, only flag if < -1
            }

            // Treat very small balances (< 1 rupiah) as zero
            if (abs($balance) < 1) {
                $balance = 0;
                $isAbnormal = false;
            }

            return [
                'id' => $account->id,
                'kode' => $account->kode,
                'kode_akun' => $account->kode, // For compatibility
                'nama' => $account->nama,
                'nama_akun' => $account->nama, // For compatibility with chart data
                'kategori' => $account->kategori,
                'parent_id' => $account->parent_id,
                'ref_id' => $account->ref_id,
                'ref_type' => $account->ref_type,
                'debit' => $totalDebit,
                'kredit' => $totalKredit,
                'balance' => $balance,
                'is_abnormal' => $isAbnormal
            ];
        })->filter(function ($account) {
            return $account['balance'] != 0; // Only show accounts with balance
        });

        // Return only detail accounts with balance
        // Parent balances will be calculated in buildFlatHierarchy()
        return $accountsWithBalance;
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
                'id' => $account->id,
                'kode' => $account->kode,
                'nama' => $account->nama,
                'balance' => $balance,
                'kategori' => $account->kategori,
                'parent_id' => $account->parent_id
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

    /**
     * Group balance sheet accounts by parent (header)
     * Same logic as in LaporanKeuanganController
     *
     * @param \Illuminate\Support\Collection $accounts
     * @param string $mainCategory (asset, liability, equity)
     * @return array
     */
    private function groupBalanceSheetAccounts($accounts, $mainCategory)
    {
        $grouped = [
            'groups' => [],
            'total' => 0
        ];

        // Convert collection to array if needed
        if ($accounts instanceof \Illuminate\Support\Collection) {
            $accountsArray = $accounts->toArray();
        } else {
            $accountsArray = $accounts;
        }

        // Jika tidak ada akun dengan balance, return kosong
        if (empty($accountsArray)) {
            return $grouped;
        }

        // Get all unique parent IDs to find headers
        $allParentIds = array_unique(array_filter(array_column($accountsArray, 'parent_id')));

        if (empty($allParentIds)) {
            return $grouped;
        }

        // Get all parent/header accounts in this category
        $allParents = AkunAkuntansi::whereIn('id', $allParentIds)
            ->where('is_active', true)
            ->orderBy('kode')
            ->get()
            ->keyBy('id');

        // Find top-level headers (Level 2 - directly under main category like Aset/Kewajiban/Ekuitas)
        $topLevelHeaders = [];
        foreach ($allParents as $parent) {
            // Check if parent's parent is the main category (null or has no parent)
            if (empty($parent->parent_id)) {
                continue; // This is the root (Aset/Kewajiban/Ekuitas itself)
            }

            $grandParent = AkunAkuntansi::find($parent->parent_id);
            if (!$grandParent || empty($grandParent->parent_id)) {
                // This parent is Level 2 (directly under main category)
                $topLevelHeaders[$parent->id] = $parent;
            }
        }

        // Build hierarchy for each top-level header
        foreach ($topLevelHeaders as $header) {
            $items = $this->buildFlatHierarchy(
                $header,
                $accountsArray,
                $allParents,
                0
            );

            if (!empty($items)) {
                // Calculate total for this top-level group
                $subtotal = array_sum(array_column($items, 'balance'));

                if ($subtotal != 0) {
                    $grouped['groups'][] = [
                        'name' => $header->nama,
                        'kode' => $header->kode,
                        'subtotal' => $subtotal,
                        'accounts' => $items
                    ];
                    $grouped['total'] += $subtotal;
                }
            }
        }

        return $grouped;
    }

    /**
     * Build flat hierarchy with indentation levels
     */
    private function buildFlatHierarchy($header, $detailAccounts, $allParents, $level)
    {
        $result = [];

        // Find direct children (sub-headers and detail accounts)
        $directSubHeaders = [];
        $directDetailAccounts = [];

        foreach ($allParents as $subHeader) {
            if ($subHeader->parent_id == $header->id) {
                $directSubHeaders[] = $subHeader;
            }
        }

        foreach ($detailAccounts as $account) {
            if ($account['parent_id'] == $header->id) {
                $directDetailAccounts[] = $account;
            }
        }

        // Check if ALL direct detail children are Kas/Bank
        $allChildrenAreKasBank = false;
        if (!empty($directDetailAccounts) && empty($directSubHeaders)) {
            $allChildrenAreKasBank = true;
            foreach ($directDetailAccounts as $account) {
                if (
                    empty($account['ref_type']) ||
                    !in_array($account['ref_type'], ['App\Models\Kas', 'App\Models\RekeningBank'])
                ) {
                    $allChildrenAreKasBank = false;
                    break;
                }
            }
        }

        // Process sub-headers first (recursively)
        foreach ($directSubHeaders as $subHeader) {
            $subItems = $this->buildFlatHierarchy(
                $subHeader,
                $detailAccounts,
                $allParents,
                $level + 1
            );

            if (!empty($subItems)) {
                // Add sub-header as a separator/label
                $subtotal = array_sum(array_column($subItems, 'balance'));

                $result[] = [
                    'id' => $subHeader->id,
                    'kode_akun' => $subHeader->kode,
                    'kode' => $subHeader->kode,
                    'nama' => $subHeader->nama,
                    'balance' => $subtotal,
                    'is_header' => true,
                    'hide_details' => false,
                    'is_abnormal' => false,
                    'level' => $level
                ];

                // Add all sub-items
                foreach ($subItems as $item) {
                    $result[] = $item;
                }
            }
        }

        // Add direct detail accounts
        foreach ($directDetailAccounts as $account) {
            $result[] = [
                'id' => $account['id'],
                'kode_akun' => $account['kode'],
                'kode' => $account['kode'],
                'nama' => $account['nama'],
                'balance' => $account['balance'],
                'is_header' => false,
                'hide_details' => $allChildrenAreKasBank, // Mark for hiding if all are Kas/Bank
                'is_abnormal' => $account['is_abnormal'] ?? false,
                'level' => $level
            ];
        }

        return $result;
    }
}
