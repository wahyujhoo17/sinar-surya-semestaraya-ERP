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
        $accounts = AkunAkuntansi::where('kategori', $category)
            ->where('is_active', true)
            ->where('tipe', 'detail')
            ->orderBy('kode')
            ->get();

        $accountsWithBalance = $accounts->map(function ($account) use ($tanggalAkhir) {
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
                'id' => $account->id,
                'kode' => $account->kode,
                'nama' => $account->nama,
                'balance' => $balance,
                'kategori' => $account->kategori,
                'parent_id' => $account->parent_id,
                'ref_id' => $account->ref_id,
                'ref_type' => $account->ref_type
            ];
        })->filter(function ($account) {
            return $account['balance'] != 0;
        });

        // Get unique parent IDs from accounts with balance
        $parentIds = $accountsWithBalance->pluck('parent_id')->filter()->unique();

        // Fetch parent (header) accounts
        $parentAccounts = AkunAkuntansi::whereIn('id', $parentIds)
            ->where('is_active', true)
            ->get()
            ->map(function ($parent) {
                return [
                    'id' => $parent->id,
                    'kode' => $parent->kode,
                    'nama' => $parent->nama,
                    'balance' => 0, // Will be calculated from children
                    'kategori' => $parent->kategori,
                    'parent_id' => $parent->parent_id,
                    'ref_id' => $parent->ref_id,
                    'ref_type' => $parent->ref_type
                ];
            });

        // Merge detail accounts with their parent accounts
        return $accountsWithBalance->concat($parentAccounts);
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

        // Group accounts by their parent (header accounts)
        // Special handling: accounts with ref_type (RekeningBank, Kas) should be grouped
        $accountsWithParent = [];
        $accountsWithoutParent = [];

        foreach ($accounts as $acc) {
            // Check if this is a reference account (bank/kas) that should be grouped
            $shouldGroup = !empty($acc['parent_id']) &&
                (isset($acc['ref_type']) && in_array($acc['ref_type'], ['App\\Models\\RekeningBank', 'App\\Models\\Kas']));

            // Or if it has parent_id
            $hasParentInList = !empty($acc['parent_id']);

            if ($shouldGroup || $hasParentInList) {
                if (!isset($accountsWithParent[$acc['parent_id']])) {
                    $accountsWithParent[$acc['parent_id']] = [];
                }
                $accountsWithParent[$acc['parent_id']][] = $acc;
            } else {
                $accountsWithoutParent[] = $acc;
            }
        }

        // Build the grouped accounts array
        $processedAccounts = [];

        // First, add accounts without parent that don't have children
        foreach ($accountsWithoutParent as $acc) {
            if (!isset($accountsWithParent[$acc['id']])) {
                // Regular account without children
                $processedAccounts[] = $acc;
            }
        }

        // Then, process all parent-child relationships
        foreach ($accountsWithParent as $parentId => $children) {
            // Find the parent account
            $parentAcc = null;

            foreach ($accountsWithoutParent as $acc) {
                if ($acc['id'] == $parentId) {
                    $parentAcc = $acc;
                    break;
                }
            }

            // If parent not found in accountsWithoutParent, search in all accounts
            if (!$parentAcc) {
                foreach ($accounts as $acc) {
                    if ($acc['id'] == $parentId) {
                        $parentAcc = $acc;
                        break;
                    }
                }
            }

            if ($parentAcc) {
                // This is a header account with children
                $childrenBalance = collect($children)->sum('balance');

                // Check if balance is abnormal
                $isAbnormal = false;
                if ($mainCategory === 'asset' && $childrenBalance < 0) {
                    $isAbnormal = true;
                } elseif (in_array($mainCategory, ['liability', 'equity']) && $childrenBalance < 0) {
                    $isAbnormal = true;
                }

                $processedAccounts[] = [
                    'id' => $parentAcc['id'],
                    'kode' => $parentAcc['kode'],
                    'nama' => $parentAcc['nama'],
                    'balance' => $childrenBalance,
                    'is_header' => true,
                    'is_abnormal' => $isAbnormal
                ];
            }
        }

        $processedCollection = collect($processedAccounts);

        if ($mainCategory === 'asset') {
            $currentAssets = $processedCollection->filter(function ($acc) {
                return preg_match('/^1[- ]?1/', $acc['kode']);
            });

            $fixedAssets = $processedCollection->filter(function ($acc) {
                return preg_match('/^1[- ]?[23]/', $acc['kode']);
            });

            $otherAssets = $processedCollection->filter(function ($acc) use ($currentAssets, $fixedAssets) {
                $isInCurrent = $currentAssets->contains('id', $acc['id']);
                $isInFixed = $fixedAssets->contains('id', $acc['id']);
                return !$isInCurrent && !$isInFixed;
            });

            if ($currentAssets->count() > 0) {
                $grouped['groups'][] = [
                    'name' => 'AKTIVA LANCAR',
                    'accounts' => $currentAssets->values()->all(),
                    'subtotal' => $currentAssets->sum('balance')
                ];
            }

            if ($fixedAssets->count() > 0) {
                $grouped['groups'][] = [
                    'name' => 'AKTIVA TETAP',
                    'accounts' => $fixedAssets->values()->all(),
                    'subtotal' => $fixedAssets->sum('balance')
                ];
            }

            if ($otherAssets->count() > 0) {
                $grouped['groups'][] = [
                    'name' => 'AKTIVA LAINNYA',
                    'accounts' => $otherAssets->values()->all(),
                    'subtotal' => $otherAssets->sum('balance')
                ];
            }

            $grouped['total'] = $processedCollection->sum('balance');
        } elseif ($mainCategory === 'liability') {
            $currentLiabilities = $processedCollection->filter(function ($acc) {
                return preg_match('/^2[- ]?1/', $acc['kode']);
            });

            $longTermLiabilities = $processedCollection->filter(function ($acc) {
                return preg_match('/^2[- ]?2/', $acc['kode']);
            });

            $otherLiabilities = $processedCollection->filter(function ($acc) use ($currentLiabilities, $longTermLiabilities) {
                $isInCurrent = $currentLiabilities->contains('id', $acc['id']);
                $isInLongTerm = $longTermLiabilities->contains('id', $acc['id']);
                return !$isInCurrent && !$isInLongTerm;
            });

            if ($currentLiabilities->count() > 0) {
                $grouped['groups'][] = [
                    'name' => 'KEWAJIBAN JANGKA PENDEK',
                    'accounts' => $currentLiabilities->values()->all(),
                    'subtotal' => $currentLiabilities->sum('balance')
                ];
            }

            if ($longTermLiabilities->count() > 0) {
                $grouped['groups'][] = [
                    'name' => 'KEWAJIBAN JANGKA PANJANG',
                    'accounts' => $longTermLiabilities->values()->all(),
                    'subtotal' => $longTermLiabilities->sum('balance')
                ];
            }

            if ($otherLiabilities->count() > 0) {
                $grouped['groups'][] = [
                    'name' => 'KEWAJIBAN LAINNYA',
                    'accounts' => $otherLiabilities->values()->all(),
                    'subtotal' => $otherLiabilities->sum('balance')
                ];
            }

            $grouped['total'] = $processedCollection->sum('balance');
        } elseif ($mainCategory === 'equity') {
            $grouped['groups'][] = [
                'name' => 'EKUITAS',
                'accounts' => $processedCollection->values()->all(),
                'subtotal' => $processedCollection->sum('balance')
            ];

            $grouped['total'] = $processedCollection->sum('balance');
        }

        return $grouped;
    }
}
