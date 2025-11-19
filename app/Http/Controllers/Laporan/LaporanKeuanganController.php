<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AkunAkuntansi;
use App\Models\JurnalUmum;
use App\Models\Kas;
use App\Models\RekeningBank;
use App\Models\TransaksiKas;
use App\Models\TransaksiBank;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LaporanKeuanganController extends Controller
{
    /**
     * Menampilkan halaman laporan keuangan
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tanggalAwal = now()->startOfMonth();
        $tanggalAkhir = now()->endOfMonth();

        // Get all accounts for filter
        $akunList = AkunAkuntansi::where('is_active', true)
            ->orderBy('kode')
            ->get();

        // Breadcrumbs
        $breadcrumbs = [
            ['name' => 'Dashboard', 'link' => route('dashboard')],
            ['name' => 'Laporan', 'link' => '#'],
            ['name' => 'Laporan Keuangan', 'link' => '#'],
        ];

        $currentPage = 'laporan-keuangan';

        return view('laporan.laporan_keuangan.index', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'akunList',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Generate Enhanced Balance Sheet Report
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBalanceSheet(Request $request)
    {
        try {
            $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay();

            // Get assets (Aset)
            $assets = $this->getAccountBalance('asset', $tanggalAkhir);

            // Get liabilities (Kewajiban)
            $liabilities = $this->getAccountBalance('liability', $tanggalAkhir);

            // Get equity (Ekuitas)
            $equity = $this->getAccountBalance('equity', $tanggalAkhir);

            // Calculate totals
            $totalAssets = $assets->sum('balance');
            $totalLiabilities = $liabilities->sum('balance');
            $totalEquity = $equity->sum('balance');

            // === CHART DATA ===
            $chartData = [
                'assets_composition' => [
                    'labels' => $assets->pluck('nama_akun')->toArray(),
                    'datasets' => [
                        [
                            'data' => $assets->pluck('balance')->toArray(),
                            'backgroundColor' => ['#10B981', '#3B82F6', '#8B5CF6', '#F59E0B', '#EF4444'],
                            'borderWidth' => 1
                        ]
                    ]
                ],
                'liabilities_equity' => [
                    'labels' => ['Kewajiban', 'Ekuitas'],
                    'datasets' => [
                        [
                            'data' => [$totalLiabilities, $totalEquity],
                            'backgroundColor' => ['#EF4444', '#10B981'],
                            'borderWidth' => 1
                        ]
                    ]
                ],
                'financial_position' => [
                    'labels' => array_merge(
                        $assets->pluck('nama_akun')->toArray(),
                        $liabilities->pluck('nama_akun')->toArray(),
                        $equity->pluck('nama_akun')->toArray()
                    ),
                    'datasets' => [
                        [
                            'data' => array_merge(
                                $assets->pluck('balance')->toArray(),
                                $liabilities->pluck('balance')->map(function ($value) {
                                    return -$value;
                                })->toArray(),
                                $equity->pluck('balance')->toArray()
                            ),
                            'backgroundColor' => array_merge(
                                array_fill(0, $assets->count(), '#10B981'),
                                array_fill(0, $liabilities->count(), '#EF4444'),
                                array_fill(0, $equity->count(), '#3B82F6')
                            ),
                            'borderWidth' => 1
                        ]
                    ]
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'assets' => $assets,
                    'liabilities' => $liabilities,
                    'equity' => $equity,
                    'totals' => [
                        'total_assets' => $totalAssets,
                        'total_liabilities' => $totalLiabilities,
                        'total_equity' => $totalEquity,
                        'total_liab_equity' => $totalLiabilities + $totalEquity
                    ],
                    'chart_data' => $chartData
                ],
                'period' => [
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in Enhanced Balance Sheet: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat memuat neraca',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate Enhanced Income Statement Report
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIncomeStatement(Request $request)
    {
        try {
            $tanggalAwal = Carbon::parse($request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
            $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay();

            // === REVENUE SECTION ===
            // 1. Sales Revenue from Sales Orders - HANYA yang sudah ada invoice
            $salesRevenue = DB::table('sales_order')
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('invoice')
                        ->whereColumn('invoice.sales_order_id', 'sales_order.id')
                        ->whereNotNull('invoice.nomor');
                })
                ->whereNotNull('total')
                ->where('total', '>', 0)
                ->sum('total');

            // 2. Get ALL income from journal entries (proper accrual accounting)
            $allIncome = $this->getAccountBalanceForPeriod('income', $tanggalAwal, $tanggalAkhir);

            // Log income accounts for debugging
            Log::info('Income Accounts from Journal', [
                'period' => [$tanggalAwal->format('Y-m-d'), $tanggalAkhir->format('Y-m-d')],
                'accounts' => $allIncome->map(function ($acc) {
                    return [
                        'kode' => $acc['kode'],
                        'nama' => $acc['nama'],
                        'balance' => $acc['balance']
                    ];
                })->toArray(),
                'total' => $allIncome->sum('balance')
            ]);

            // 3. Separate main sales vs other income
            $mainSalesAccounts = $allIncome->filter(function ($account) {
                $nama = strtolower($account['nama']);
                $kode = $account['kode'];
                // Account codes 4-1xxx or names containing 'penjualan'/'sales'
                return preg_match('/^4[- ]?1/', $kode) ||
                    str_contains($nama, 'penjualan') ||
                    str_contains($nama, 'sales') ||
                    str_contains($nama, 'revenue from sales');
            });

            $otherIncome = $allIncome->filter(function ($account) use ($mainSalesAccounts) {
                return !$mainSalesAccounts->contains('id', $account['id']);
            });

            // Use journal entry as source of truth if exists, otherwise use SO total
            $totalSalesFromJournal = $mainSalesAccounts->sum('balance');
            $finalSalesRevenue = $totalSalesFromJournal > 0 ? $totalSalesFromJournal : $salesRevenue;

            $totalOtherIncome = $otherIncome->sum('balance');
            $totalRevenue = $finalSalesRevenue + $totalOtherIncome;

            // === COST OF GOODS SOLD ===
            // Get ONLY COGS from journal entries (proper accounting)
            $cogsAccounts = AkunAkuntansi::where('kategori', 'expense')
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->where('nama', 'LIKE', '%harga pokok%')
                        ->orWhere('nama', 'LIKE', '%cost of goods%')
                        ->orWhere('nama', 'LIKE', '%cogs%')
                        ->orWhere('nama', 'LIKE', '%hpp%')
                        ->orWhere('kode', 'LIKE', '51%') // Standard COGS account codes
                        ->orWhere('kode', 'LIKE', '5-1%')
                        ->orWhere('kode', 'LIKE', '5100%')
                        ->orWhere('kode', 'LIKE', '5110%')
                        ->orWhere('kode', 'LIKE', '5120%')
                        ->orWhere('kode', 'LIKE', '5130%');
                })
                ->pluck('id');

            $totalCogs = 0;
            if ($cogsAccounts->isNotEmpty()) {
                $totalCogs = JurnalUmum::whereIn('akun_id', $cogsAccounts)
                    ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                    ->sum(DB::raw('debit - kredit'));
            }

            // Log COGS calculation
            Log::info('COGS Calculation', [
                'period' => [$tanggalAwal->format('Y-m-d'), $tanggalAkhir->format('Y-m-d')],
                'cogs_accounts_count' => $cogsAccounts->count(),
                'total_cogs' => $totalCogs
            ]);

            // === GROSS PROFIT ===
            $grossProfit = $totalRevenue - $totalCogs;

            // === OPERATING EXPENSES ===
            // Get all expense accounts from journal entries (excluding COGS)
            $allExpenseAccounts = $this->getAccountBalanceForPeriod('expense', $tanggalAwal, $tanggalAkhir);

            // Filter out COGS accounts and ensure only active operational expense accounts
            $operationalExpenseAccounts = $allExpenseAccounts->filter(function ($expense) use ($cogsAccounts) {
                // Exclude COGS accounts
                $isNotCogs = !$cogsAccounts->contains($expense['id']);

                // Additional filtering for operational expenses based on account code patterns
                $kode = $expense['kode'];
                $isOperationalByCode = preg_match('/^(5[2-9]|6[1-9]|5-[2-9]|6-[1-9])/', $kode); // Starting with 52-59 or 61-69

                // Include if not COGS and matches operational patterns or has balance
                return $isNotCogs && ($isOperationalByCode || $expense['balance'] > 0);
            });

            // Log expense accounts for debugging
            Log::info('Operating Expenses Accounts', [
                'period' => [$tanggalAwal->format('Y-m-d'), $tanggalAkhir->format('Y-m-d')],
                'total_expense_accounts' => $allExpenseAccounts->count(),
                'cogs_accounts' => $cogsAccounts->count(),
                'operational_accounts' => $operationalExpenseAccounts->count(),
                'accounts' => $operationalExpenseAccounts->map(function ($acc) {
                    return [
                        'kode' => $acc['kode'],
                        'nama' => $acc['nama'],
                        'balance' => $acc['balance']
                    ];
                })->toArray()
            ]);

            // 3. Enhanced categorization based on account code and name patterns with comprehensive filtering
            $salaryRelatedAccounts = $operationalExpenseAccounts->filter(function ($expense) {
                $nama = strtolower($expense['nama']);
                $kode = $expense['kode'];

                // Enhanced account code pattern matching for salary/payroll
                $salaryCodePattern = preg_match('/^(51|5211|611|621|6211)/', $kode);

                // Comprehensive name pattern matching including variations
                $salaryNamePattern = str_contains($nama, 'gaji') ||
                    str_contains($nama, 'salary') ||
                    str_contains($nama, 'upah') ||
                    str_contains($nama, 'tunjangan') ||
                    str_contains($nama, 'bonus karyawan') ||
                    str_contains($nama, 'insentif') ||
                    str_contains($nama, 'lembur') ||
                    str_contains($nama, 'overtime') ||
                    str_contains($nama, 'thr') ||
                    str_contains($nama, 'hari raya') ||
                    str_contains($nama, 'pesangon') ||
                    str_contains($nama, 'severance') ||
                    str_contains($nama, 'bpjs') ||
                    str_contains($nama, 'jamsostek') ||
                    str_contains($nama, 'kesehatan karyawan') ||
                    str_contains($nama, 'asuransi karyawan') ||
                    str_contains($nama, 'employee') ||
                    str_contains($nama, 'pegawai') ||
                    str_contains($nama, 'staff') ||
                    str_contains($nama, 'payroll');

                return $salaryCodePattern || $salaryNamePattern;
            });

            // Priority-based filtering to avoid conflicts
            // First, identify utility accounts with comprehensive patterns
            $utilityAccounts = $operationalExpenseAccounts->filter(function ($expense) {
                $nama = strtolower($expense['nama']);
                $kode = $expense['kode'];

                // Comprehensive utility name pattern matching (highest priority)
                $utilityNamePattern = str_contains($nama, 'listrik') ||
                    str_contains($nama, 'air') ||
                    str_contains($nama, 'telepon') ||
                    str_contains($nama, 'internet') ||
                    str_contains($nama, 'utilities') ||
                    str_contains($nama, 'utility') ||
                    str_contains($nama, 'utilitas') ||
                    str_contains($nama, 'pln') ||
                    str_contains($nama, 'pdam') ||
                    str_contains($nama, 'wifi') ||
                    str_contains($nama, 'provider') ||
                    str_contains($nama, 'gas') ||
                    str_contains($nama, 'token') ||
                    str_contains($nama, 'pulsa') ||
                    str_contains($nama, 'electricity') ||
                    str_contains($nama, 'water') ||
                    str_contains($nama, 'telephone') ||
                    str_contains($nama, 'phone') ||
                    str_contains($nama, 'communication') ||
                    str_contains($nama, 'komunikasi') ||
                    str_contains($nama, 'tagihan');

                // Enhanced account code pattern matching for utilities
                $utilityCodePattern = preg_match('/^(5213|5214|5215|612|6121|6122)/', $kode);

                // Special handling for 5212: utility if name suggests it
                $is5212Utility = ($kode === '5212' && $utilityNamePattern);

                return $utilityNamePattern || $utilityCodePattern || $is5212Utility;
            });

            // Get IDs of utility accounts to exclude from other categories
            $utilityAccountIds = $utilityAccounts->pluck('id')->toArray();

            $rentAccounts = $operationalExpenseAccounts->filter(function ($expense) use ($utilityAccountIds) {
                // Skip if already categorized as utility
                if (in_array($expense['id'], $utilityAccountIds)) {
                    return false;
                }

                $nama = strtolower($expense['nama']);
                $kode = $expense['kode'];

                // Comprehensive rent/lease name pattern matching
                $rentNamePattern = str_contains($nama, 'sewa') ||
                    str_contains($nama, 'rent') ||
                    str_contains($nama, 'rental') ||
                    str_contains($nama, 'kontrak') ||
                    str_contains($nama, 'lease') ||
                    str_contains($nama, 'gedung') ||
                    str_contains($nama, 'ruang') ||
                    str_contains($nama, 'kantor') ||
                    str_contains($nama, 'building') ||
                    str_contains($nama, 'office') ||
                    str_contains($nama, 'space') ||
                    str_contains($nama, 'property') ||
                    str_contains($nama, 'properti') ||
                    str_contains($nama, 'tempat') ||
                    str_contains($nama, 'lokasi');

                // Enhanced account code pattern matching for rent/lease (excluding conflicting codes)
                $rentCodePattern = preg_match('/^(53|5212|613|6131|6132)/', $kode);

                return $rentNamePattern || $rentCodePattern;
            });

            $adminAccounts = $operationalExpenseAccounts->filter(function ($expense) {
                $nama = strtolower($expense['nama']);
                $kode = $expense['kode'];

                // Enhanced account code pattern matching for administrative
                $adminCodePattern = preg_match('/^(54|5214|614|6141|6142)/', $kode);

                // Comprehensive administrative name pattern matching
                $adminNamePattern = str_contains($nama, 'administrasi') ||
                    str_contains($nama, 'admin') ||
                    str_contains($nama, 'atk') ||
                    str_contains($nama, 'supplies') ||
                    str_contains($nama, 'alat tulis') ||
                    str_contains($nama, 'perlengkapan') ||
                    str_contains($nama, 'fotokopi') ||
                    str_contains($nama, 'printing') ||
                    str_contains($nama, 'materai') ||
                    str_contains($nama, 'dokumentasi') ||
                    str_contains($nama, 'perizinan') ||
                    str_contains($nama, 'notaris') ||
                    str_contains($nama, 'stationery') ||
                    str_contains($nama, 'office supplies') ||
                    str_contains($nama, 'office equipment') ||
                    str_contains($nama, 'equipment') ||
                    str_contains($nama, 'peralatan') ||
                    str_contains($nama, 'kertas') ||
                    str_contains($nama, 'tinta') ||
                    str_contains($nama, 'cartridge') ||
                    str_contains($nama, 'license') ||
                    str_contains($nama, 'lisensi');

                return $adminCodePattern || $adminNamePattern;
            });

            $transportAccounts = $operationalExpenseAccounts->filter(function ($expense) {
                $nama = strtolower($expense['nama']);
                $kode = $expense['kode'];

                // Enhanced account code pattern matching for transportation
                $transportCodePattern = preg_match('/^(55|5215|615|6151|6152)/', $kode);

                // Comprehensive transportation name pattern matching
                $transportNamePattern = str_contains($nama, 'transport') ||
                    str_contains($nama, 'bensin') ||
                    str_contains($nama, 'bbm') ||
                    str_contains($nama, 'solar') ||
                    str_contains($nama, 'perjalanan') ||
                    str_contains($nama, 'parkir') ||
                    str_contains($nama, 'tol') ||
                    str_contains($nama, 'taxi') ||
                    str_contains($nama, 'ojek') ||
                    str_contains($nama, 'dinas') ||
                    str_contains($nama, 'kendaraan') ||
                    str_contains($nama, 'mobil') ||
                    str_contains($nama, 'motor') ||
                    str_contains($nama, 'fuel') ||
                    str_contains($nama, 'gasoline') ||
                    str_contains($nama, 'diesel') ||
                    str_contains($nama, 'travel') ||
                    str_contains($nama, 'trip') ||
                    str_contains($nama, 'vehicle') ||
                    str_contains($nama, 'shipping') ||
                    str_contains($nama, 'delivery') ||
                    str_contains($nama, 'pengiriman') ||
                    str_contains($nama, 'ekspedisi');

                return $transportCodePattern || $transportNamePattern;
            });

            $maintenanceAccounts = $operationalExpenseAccounts->filter(function ($expense) {
                $nama = strtolower($expense['nama']);
                $kode = $expense['kode'];

                // Enhanced account code pattern matching for maintenance
                $maintenanceCodePattern = preg_match('/^(56|5216|616|6161|6162)/', $kode);

                // Comprehensive maintenance name pattern matching
                $maintenanceNamePattern = str_contains($nama, 'maintenance') ||
                    str_contains($nama, 'pemeliharaan') ||
                    str_contains($nama, 'perbaikan') ||
                    str_contains($nama, 'service') ||
                    str_contains($nama, 'reparasi') ||
                    str_contains($nama, 'perawatan') ||
                    str_contains($nama, 'servis') ||
                    str_contains($nama, 'sparepart') ||
                    str_contains($nama, 'onderdil') ||
                    str_contains($nama, 'repair') ||
                    str_contains($nama, 'fix') ||
                    str_contains($nama, 'spare part') ||
                    str_contains($nama, 'parts') ||
                    str_contains($nama, 'cleaning') ||
                    str_contains($nama, 'pembersihan') ||
                    str_contains($nama, 'renovasi') ||
                    str_contains($nama, 'renovation') ||
                    str_contains($nama, 'upgrade') ||
                    str_contains($nama, 'improvement');

                return $maintenanceCodePattern || $maintenanceNamePattern;
            });

            $marketingAccounts = $operationalExpenseAccounts->filter(function ($expense) {
                $nama = strtolower($expense['nama']);
                $kode = $expense['kode'];

                // Enhanced account code pattern matching for marketing
                $marketingCodePattern = preg_match('/^(57|5217|617|6171|6172)/', $kode);

                // Comprehensive marketing name pattern matching
                $marketingNamePattern = str_contains($nama, 'marketing') ||
                    str_contains($nama, 'promosi') ||
                    str_contains($nama, 'iklan') ||
                    str_contains($nama, 'advertising') ||
                    str_contains($nama, 'brosur') ||
                    str_contains($nama, 'banner') ||
                    str_contains($nama, 'spanduk') ||
                    str_contains($nama, 'katalog') ||
                    str_contains($nama, 'publikasi') ||
                    str_contains($nama, 'event') ||
                    str_contains($nama, 'pameran') ||
                    str_contains($nama, 'exhibition') ||
                    str_contains($nama, 'seminar') ||
                    str_contains($nama, 'workshop') ||
                    str_contains($nama, 'campaign') ||
                    str_contains($nama, 'kampanye') ||
                    str_contains($nama, 'sales promotion') ||
                    str_contains($nama, 'branding') ||
                    str_contains($nama, 'media') ||
                    str_contains($nama, 'digital marketing') ||
                    str_contains($nama, 'social media') ||
                    str_contains($nama, 'website') ||
                    str_contains($nama, 'seo') ||
                    str_contains($nama, 'google ads');

                return $marketingCodePattern || $marketingNamePattern;
            });

            $professionalAccounts = $operationalExpenseAccounts->filter(function ($expense) {
                $nama = strtolower($expense['nama']);
                $kode = $expense['kode'];

                // Enhanced account code pattern matching for professional services
                $professionalCodePattern = preg_match('/^(58|5218|618|6181|6182)/', $kode);

                // Comprehensive professional services name pattern matching
                $professionalNamePattern = str_contains($nama, 'konsultan') ||
                    str_contains($nama, 'auditor') ||
                    str_contains($nama, 'lawyer') ||
                    str_contains($nama, 'legal') ||
                    str_contains($nama, 'hukum') ||
                    str_contains($nama, 'akuntan') ||
                    str_contains($nama, 'pajak') ||
                    str_contains($nama, 'profesional') ||
                    str_contains($nama, 'jasa') ||
                    str_contains($nama, 'consultant') ||
                    str_contains($nama, 'professional') ||
                    str_contains($nama, 'services') ||
                    str_contains($nama, 'accounting') ||
                    str_contains($nama, 'tax') ||
                    str_contains($nama, 'audit') ||
                    str_contains($nama, 'advisory') ||
                    str_contains($nama, 'consulting') ||
                    str_contains($nama, 'expert') ||
                    str_contains($nama, 'specialist') ||
                    str_contains($nama, 'spesialis') ||
                    str_contains($nama, 'training') ||
                    str_contains($nama, 'pelatihan') ||
                    str_contains($nama, 'certification') ||
                    str_contains($nama, 'sertifikasi');

                return $professionalCodePattern || $professionalNamePattern;
            });

            $insuranceAccounts = $operationalExpenseAccounts->filter(function ($expense) {
                $nama = strtolower($expense['nama']);
                $kode = $expense['kode'];

                // Enhanced account code pattern matching for insurance
                $insuranceCodePattern = preg_match('/^(59|5219|619|6191|6192)/', $kode);

                // Comprehensive insurance name pattern matching
                $insuranceNamePattern = str_contains($nama, 'asuransi') ||
                    str_contains($nama, 'insurance') ||
                    str_contains($nama, 'premi') ||
                    str_contains($nama, 'perlindungan') ||
                    str_contains($nama, 'premium') ||
                    str_contains($nama, 'coverage') ||
                    str_contains($nama, 'policy') ||
                    str_contains($nama, 'polis') ||
                    str_contains($nama, 'protection') ||
                    str_contains($nama, 'guarantee') ||
                    str_contains($nama, 'jaminan') ||
                    str_contains($nama, 'takaful') ||
                    str_contains($nama, 'life insurance') ||
                    str_contains($nama, 'health insurance') ||
                    str_contains($nama, 'fire insurance') ||
                    str_contains($nama, 'vehicle insurance');

                return $insuranceCodePattern || $insuranceNamePattern;
            });

            // Other operational expenses (remaining accounts that don't fit in specific categories)
            $otherExpenses = $operationalExpenseAccounts->filter(function ($expense) use (
                $salaryRelatedAccounts,
                $utilityAccounts,
                $rentAccounts,
                $adminAccounts,
                $transportAccounts,
                $maintenanceAccounts,
                $marketingAccounts,
                $professionalAccounts,
                $insuranceAccounts
            ) {
                return !$salaryRelatedAccounts->contains('id', $expense['id']) &&
                    !$utilityAccounts->contains('id', $expense['id']) &&
                    !$rentAccounts->contains('id', $expense['id']) &&
                    !$adminAccounts->contains('id', $expense['id']) &&
                    !$transportAccounts->contains('id', $expense['id']) &&
                    !$maintenanceAccounts->contains('id', $expense['id']) &&
                    !$marketingAccounts->contains('id', $expense['id']) &&
                    !$professionalAccounts->contains('id', $expense['id']) &&
                    !$insuranceAccounts->contains('id', $expense['id']);
            });

            // Calculate totals for each category with validation
            $totalSalaryFromJournal = $salaryRelatedAccounts->sum('balance');
            $totalUtilities = $utilityAccounts->sum('balance');
            $totalRent = $rentAccounts->sum('balance');
            $totalAdmin = $adminAccounts->sum('balance');
            $totalTransport = $transportAccounts->sum('balance');
            $totalMaintenance = $maintenanceAccounts->sum('balance');
            $totalMarketing = $marketingAccounts->sum('balance');
            $totalProfessional = $professionalAccounts->sum('balance');
            $totalInsurance = $insuranceAccounts->sum('balance');
            $totalOtherExpenses = $otherExpenses->sum('balance');

            // Log expense categorization for monitoring
            Log::info('Enhanced Operating Expenses Categorization', [
                'period' => [$tanggalAwal->format('Y-m-d'), $tanggalAkhir->format('Y-m-d')],
                'salary_from_payroll' => $salaryExpenses,
                'salary_from_journal' => $totalSalaryFromJournal,
                'utilities' => $totalUtilities,
                'rent' => $totalRent,
                'admin' => $totalAdmin,
                'transport' => $totalTransport,
                'maintenance' => $totalMaintenance,
                'marketing' => $totalMarketing,
                'professional' => $totalProfessional,
                'insurance' => $totalInsurance,
                'other' => $totalOtherExpenses,
                'account_counts' => [
                    'salary_accounts' => $salaryRelatedAccounts->count(),
                    'utility_accounts' => $utilityAccounts->count(),
                    'rent_accounts' => $rentAccounts->count(),
                    'admin_accounts' => $adminAccounts->count(),
                    'transport_accounts' => $transportAccounts->count(),
                    'maintenance_accounts' => $maintenanceAccounts->count(),
                    'marketing_accounts' => $marketingAccounts->count(),
                    'professional_accounts' => $professionalAccounts->count(),
                    'insurance_accounts' => $insuranceAccounts->count(),
                    'other_accounts' => $otherExpenses->count()
                ]
            ]);

            // Total operating expenses (all from journal - no double counting with payroll system)
            $totalOperatingExpenses = $totalSalaryFromJournal + $totalUtilities +
                $totalRent + $totalAdmin + $totalTransport + $totalMaintenance +
                $totalMarketing + $totalProfessional + $totalInsurance + $totalOtherExpenses;

            // Validation: Log if there's potential double counting
            $totalExpenseAccountsProcessed = $salaryRelatedAccounts->count() + $utilityAccounts->count() +
                $rentAccounts->count() + $adminAccounts->count() +
                $transportAccounts->count() + $maintenanceAccounts->count() +
                $marketingAccounts->count() + $professionalAccounts->count() +
                $insuranceAccounts->count() + $otherExpenses->count();

            Log::info('Operating Expenses Summary', [
                'total_accounts_processed' => $totalExpenseAccountsProcessed,
                'total_operational_accounts' => $operationalExpenseAccounts->count(),
                'total_operating_expenses' => $totalOperatingExpenses,
                'salary_from_journal' => $totalSalaryFromJournal
            ]);

            // === NET INCOME ===
            $operatingIncome = $grossProfit - $totalOperatingExpenses;
            $netIncome = $operatingIncome; // Assuming no non-operating items for now

            // === DYNAMIC CHART LABELS ===
            // Create dynamic labels based on actual expense categories with data
            $expenseCategories = [];
            $expenseData = [];
            $expenseColors = ['#EF4444', '#F59E0B', '#8B5CF6', '#EC4899', '#10B981', '#6366F1', '#14B8A6', '#F97316', '#84CC16', '#06B6D4', '#6B7280'];
            $colorIndex = 0;

            // Add categories that have data
            if ($totalCogs > 0) {
                $expenseCategories[] = 'HPP';
                $expenseData[] = $totalCogs;
            }

            if ($totalSalaryFromJournal > 0) {
                $expenseCategories[] = 'Gaji';
                $expenseData[] = $totalSalaryFromJournal;
            }

            if ($totalUtilities > 0) {
                $expenseCategories[] = 'Utilitas';
                $expenseData[] = $totalUtilities;
            }

            if ($totalRent > 0) {
                $expenseCategories[] = 'Sewa';
                $expenseData[] = $totalRent;
            }

            if ($totalAdmin > 0) {
                $expenseCategories[] = 'Admin';
                $expenseData[] = $totalAdmin;
            }

            if ($totalTransport > 0) {
                $expenseCategories[] = 'Transport';
                $expenseData[] = $totalTransport;
            }

            if ($totalMaintenance > 0) {
                $expenseCategories[] = 'Maintenance';
                $expenseData[] = $totalMaintenance;
            }

            if ($totalMarketing > 0) {
                $expenseCategories[] = 'Marketing';
                $expenseData[] = $totalMarketing;
            }

            if ($totalProfessional > 0) {
                $expenseCategories[] = 'Profesional';
                $expenseData[] = $totalProfessional;
            }

            if ($totalInsurance > 0) {
                $expenseCategories[] = 'Asuransi';
                $expenseData[] = $totalInsurance;
            }

            if ($totalOtherExpenses > 0) {
                $expenseCategories[] = 'Lainnya';
                $expenseData[] = $totalOtherExpenses;
            }

            // === CHART DATA ===
            $chartData = [
                'revenue_breakdown' => [
                    'labels' => ['Penjualan', 'Pendapatan Lain'],
                    'datasets' => [
                        [
                            'data' => [$salesRevenue, $totalOtherIncome],
                            'backgroundColor' => ['#10B981', '#3B82F6'],
                            'borderWidth' => 1
                        ]
                    ]
                ],
                'expense_breakdown' => [
                    'labels' => $expenseCategories,
                    'datasets' => [
                        [
                            'data' => $expenseData,
                            'backgroundColor' => array_slice($expenseColors, 0, count($expenseData)),
                            'borderWidth' => 1
                        ]
                    ]
                ],
                'profitability' => [
                    'labels' => ['Total Pendapatan', 'Total Pengeluaran', 'Laba Bersih'],
                    'datasets' => [
                        [
                            'data' => [$totalRevenue, $totalCogs + $totalOperatingExpenses, $netIncome],
                            'backgroundColor' => ['#10B981', '#EF4444', $netIncome >= 0 ? '#10B981' : '#EF4444'],
                            'borderWidth' => 1
                        ]
                    ]
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'revenue' => [
                        'sales_revenue' => $finalSalesRevenue,
                        'sales_accounts' => $mainSalesAccounts->values(),
                        'other_income' => $otherIncome->values(),
                        'total_revenue' => $totalRevenue
                    ],
                    'cogs' => [
                        'cogs_accounts' => $cogsAccounts,
                        'total_cogs' => $totalCogs
                    ],
                    'operating_expenses' => [
                        'salary_from_journal' => $salaryRelatedAccounts->values(),
                        'salary_from_journal_total' => $totalSalaryFromJournal,
                        'utility_expenses' => $utilityAccounts->values(),
                        'utility_expenses_total' => $totalUtilities,
                        'rent_expenses' => $rentAccounts->values(),
                        'rent_expenses_total' => $totalRent,
                        'admin_expenses' => $adminAccounts->values(),
                        'admin_expenses_total' => $totalAdmin,
                        'transport_expenses' => $transportAccounts->values(),
                        'transport_expenses_total' => $totalTransport,
                        'maintenance_expenses' => $maintenanceAccounts->values(),
                        'maintenance_expenses_total' => $totalMaintenance,
                        'marketing_expenses' => $marketingAccounts->values(),
                        'marketing_expenses_total' => $totalMarketing,
                        'professional_expenses' => $professionalAccounts->values(),
                        'professional_expenses_total' => $totalProfessional,
                        'insurance_expenses' => $insuranceAccounts->values(),
                        'insurance_expenses_total' => $totalInsurance,
                        'other_expenses' => $otherExpenses->values(),
                        'other_expenses_total' => $totalOtherExpenses,
                        'total_operating_expenses' => $totalOperatingExpenses
                    ],
                    'totals' => [
                        'total_revenue' => $totalRevenue,
                        'total_cogs' => $totalCogs,
                        'gross_profit' => $grossProfit,
                        'total_operating_expenses' => $totalOperatingExpenses,
                        'operating_income' => $operatingIncome,
                        'net_income' => $netIncome
                    ],
                    'chart_data' => $chartData
                ],
                'period' => [
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in Enhanced Income Statement: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat memuat laporan laba rugi',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate Enhanced Cash Flow Report
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCashFlow(Request $request)
    {
        try {
            $tanggalAwal = Carbon::parse($request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
            $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay();

            // Get cash transactions from Kas
            $kasTransactions = TransaksiKas::with('kas')
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->selectRaw('
                    kas_id,
                    SUM(CASE WHEN jenis = "masuk" THEN jumlah ELSE 0 END) as total_masuk,
                    SUM(CASE WHEN jenis = "keluar" THEN jumlah ELSE 0 END) as total_keluar
                ')
                ->groupBy('kas_id')
                ->get();

            // Get bank transactions from RekeningBank
            $bankTransactions = TransaksiBank::with('rekening')
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->selectRaw('
                    rekening_id,
                    SUM(CASE WHEN jenis = "masuk" THEN jumlah ELSE 0 END) as total_masuk,
                    SUM(CASE WHEN jenis = "keluar" THEN jumlah ELSE 0 END) as total_keluar
                ')
                ->groupBy('rekening_id')
                ->get();

            // Get opening balances
            $kasOpeningBalances = $this->getKasOpeningBalances($tanggalAwal);
            $bankOpeningBalances = $this->getBankOpeningBalances($tanggalAwal);

            // Calculate totals
            $totalKasMasuk = $kasTransactions->sum('total_masuk');
            $totalKasKeluar = $kasTransactions->sum('total_keluar');
            $totalBankMasuk = $bankTransactions->sum('total_masuk');
            $totalBankKeluar = $bankTransactions->sum('total_keluar');

            $totalMasuk = $totalKasMasuk + $totalBankMasuk;
            $totalKeluar = $totalKasKeluar + $totalBankKeluar;
            $netCashFlow = $totalMasuk - $totalKeluar;

            // === MONTHLY CASH FLOW TREND ===
            $monthlyData = [];
            $startDate = $tanggalAwal->copy()->startOfMonth();
            $endDate = $tanggalAkhir->copy()->endOfMonth();

            while ($startDate <= $endDate) {
                $monthStart = $startDate->copy()->startOfMonth();
                $monthEnd = $startDate->copy()->endOfMonth();

                $monthlyKasIn = TransaksiKas::where('jenis', 'masuk')
                    ->whereBetween('tanggal', [$monthStart, $monthEnd])
                    ->sum('jumlah');

                $monthlyKasOut = TransaksiKas::where('jenis', 'keluar')
                    ->whereBetween('tanggal', [$monthStart, $monthEnd])
                    ->sum('jumlah');

                $monthlyBankIn = TransaksiBank::where('jenis', 'masuk')
                    ->whereBetween('tanggal', [$monthStart, $monthEnd])
                    ->sum('jumlah');

                $monthlyBankOut = TransaksiBank::where('jenis', 'keluar')
                    ->whereBetween('tanggal', [$monthStart, $monthEnd])
                    ->sum('jumlah');

                $monthlyData[] = [
                    'month' => $startDate->format('M Y'),
                    'cash_in' => $monthlyKasIn + $monthlyBankIn,
                    'cash_out' => $monthlyKasOut + $monthlyBankOut,
                    'net_flow' => ($monthlyKasIn + $monthlyBankIn) - ($monthlyKasOut + $monthlyBankOut)
                ];

                $startDate->addMonth();
            }

            // === CHART DATA ===
            $chartData = [
                'cash_flow_summary' => [
                    'labels' => ['Kas Masuk', 'Kas Keluar', 'Arus Kas Bersih'],
                    'datasets' => [
                        [
                            'data' => [$totalMasuk, $totalKeluar, abs($netCashFlow)],
                            'backgroundColor' => ['#10B981', '#EF4444', $netCashFlow >= 0 ? '#10B981' : '#EF4444'],
                            'borderWidth' => 1
                        ]
                    ]
                ],
                'cash_vs_bank' => [
                    'labels' => ['Kas', 'Bank'],
                    'datasets' => [
                        [
                            'label' => 'Masuk',
                            'data' => [$totalKasMasuk, $totalBankMasuk],
                            'backgroundColor' => '#10B981'
                        ],
                        [
                            'label' => 'Keluar',
                            'data' => [$totalKasKeluar, $totalBankKeluar],
                            'backgroundColor' => '#EF4444'
                        ]
                    ]
                ],
                'monthly_trend' => [
                    'labels' => array_column($monthlyData, 'month'),
                    'datasets' => [
                        [
                            'label' => 'Kas Masuk',
                            'data' => array_column($monthlyData, 'cash_in'),
                            'borderColor' => '#10B981',
                            'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                            'fill' => true
                        ],
                        [
                            'label' => 'Kas Keluar',
                            'data' => array_column($monthlyData, 'cash_out'),
                            'borderColor' => '#EF4444',
                            'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                            'fill' => true
                        ]
                    ]
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'kas_transactions' => $kasTransactions,
                    'bank_transactions' => $bankTransactions,
                    'opening_balances' => [
                        'kas' => $kasOpeningBalances,
                        'bank' => $bankOpeningBalances
                    ],
                    'monthly_data' => $monthlyData,
                    'totals' => [
                        'total_kas_masuk' => $totalKasMasuk,
                        'total_kas_keluar' => $totalKasKeluar,
                        'total_bank_masuk' => $totalBankMasuk,
                        'total_bank_keluar' => $totalBankKeluar,
                        'total_masuk' => $totalMasuk,
                        'total_keluar' => $totalKeluar,
                        'net_cash_flow' => $netCashFlow
                    ],
                    'chart_data' => $chartData
                ],
                'period' => [
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in Enhanced Cash Flow: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat memuat arus kas',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export laporan keuangan ke Excel
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel(Request $request)
    {
        $reportType = $request->input('report_type', 'balance_sheet');
        $fileDate = now()->format('Ymd_His');
        $fileName = "laporan_keuangan_{$reportType}_{$fileDate}.xlsx";

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\LaporanKeuanganExport($request->all()),
            $fileName
        );
    }

    /**
     * Export laporan keuangan ke PDF
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $reportType = $request->input('report_type', 'balance_sheet');
        $tanggalAwal = Carbon::parse($request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
        $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay();

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
                    'total_assets' => $assets->sum('balance'),
                    'total_liabilities' => $liabilities->sum('balance'),
                    'total_equity' => $equity->sum('balance')
                ];
                break;

            case 'income_statement':
                $income = $this->getAccountBalanceForPeriod('income', $tanggalAwal, $tanggalAkhir);
                $expenses = $this->getAccountBalanceForPeriod('expense', $tanggalAwal, $tanggalAkhir);

                $data = [
                    'income' => $income,
                    'expenses' => $expenses,
                    'total_income' => $income->sum('balance'),
                    'total_expenses' => $expenses->sum('balance'),
                    'net_income' => $income->sum('balance') - $expenses->sum('balance')
                ];
                break;

            case 'cash_flow':
                // Cash flow data preparation
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
                    'total_kas_masuk' => $kasTransactions->sum('total_masuk'),
                    'total_kas_keluar' => $kasTransactions->sum('total_keluar'),
                    'total_bank_masuk' => $bankTransactions->sum('total_masuk'),
                    'total_bank_keluar' => $bankTransactions->sum('total_keluar'),
                    'total_masuk' => $kasTransactions->sum('total_masuk') + $bankTransactions->sum('total_masuk'),
                    'total_keluar' => $kasTransactions->sum('total_keluar') + $bankTransactions->sum('total_keluar')
                ];
                break;
        }

        $filters = [
            'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
            'tanggal_akhir' => $tanggalAkhir->format('Y-m-d'),
            'report_type' => $reportType
        ];

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.laporan_keuangan.pdf', [
            'data' => $data,
            'filters' => $filters,
            'reportType' => $reportType
        ]);

        // Format tanggal untuk nama file
        $fileDate = now()->format('Ymd_His');
        $fileName = "laporan_keuangan_{$reportType}_{$fileDate}.pdf";

        return $pdf->download($fileName);
    }

    /**
     * Validate and test the accuracy of operating expense data
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateOperatingExpenses(Request $request)
    {
        try {
            $tanggalAwal = Carbon::parse($request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
            $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay();

            // Get all expense accounts
            $allExpenseAccounts = $this->getAccountBalanceForPeriod('expense', $tanggalAwal, $tanggalAkhir);

            // Get COGS accounts
            $cogsAccounts = AkunAkuntansi::where('kategori', 'expense')
                ->where(function ($q) {
                    $q->where('nama', 'LIKE', '%harga pokok%')
                        ->orWhere('nama', 'LIKE', '%cost of goods%')
                        ->orWhere('nama', 'LIKE', '%cogs%')
                        ->orWhere('nama', 'LIKE', '%hpp%')
                        ->orWhere('kode', 'LIKE', '51%');
                })
                ->where('is_active', true)
                ->pluck('id');

            // Filter operational expenses
            $operationalExpenseAccounts = $allExpenseAccounts->filter(function ($expense) use ($cogsAccounts) {
                $isNotCogs = !$cogsAccounts->contains($expense['id']);
                $kode = $expense['kode'];
                $isOperationalByCode = preg_match('/^(5[2-9]|6[1-9])/', $kode);
                return $isNotCogs && ($isOperationalByCode || $expense['balance'] > 0);
            });

            // Validate categorization
            $validationResults = [
                'total_expense_accounts' => $allExpenseAccounts->count(),
                'cogs_accounts_count' => $cogsAccounts->count(),
                'operational_accounts_count' => $operationalExpenseAccounts->count(),
                'total_expense_amount' => $allExpenseAccounts->sum('balance'),
                'total_operational_amount' => $operationalExpenseAccounts->sum('balance'),
                'uncategorized_accounts' => [],
                'categorization_accuracy' => 0
            ];

            // Check for uncategorized accounts
            $uncategorizedAccounts = $operationalExpenseAccounts->filter(function ($expense) {
                $nama = strtolower($expense['nama']);
                $kode = $expense['kode'];

                // Check if account matches any category pattern
                $matchesAnyCategory =
                    preg_match('/^(51|5211|611|621|6211)/', $kode) || // Salary
                    preg_match('/^(52|5213|612|6121|6122)/', $kode) || // Utilities
                    preg_match('/^(53|5212|613|6131|6132)/', $kode) || // Rent
                    preg_match('/^(54|5214|614|6141|6142)/', $kode) || // Admin
                    preg_match('/^(55|5215|615|6151|6152)/', $kode) || // Transport
                    preg_match('/^(56|5216|616|6161|6162)/', $kode) || // Maintenance
                    preg_match('/^(57|5217|617|6171|6172)/', $kode) || // Marketing
                    preg_match('/^(58|5218|618|6181|6182)/', $kode) || // Professional
                    preg_match('/^(59|5219|619|6191|6192)/', $kode) || // Insurance
                    str_contains($nama, 'gaji') || str_contains($nama, 'salary') ||
                    str_contains($nama, 'listrik') || str_contains($nama, 'air') ||
                    str_contains($nama, 'sewa') || str_contains($nama, 'rent') ||
                    str_contains($nama, 'administrasi') || str_contains($nama, 'admin') ||
                    str_contains($nama, 'transport') || str_contains($nama, 'bensin') ||
                    str_contains($nama, 'maintenance') || str_contains($nama, 'pemeliharaan') ||
                    str_contains($nama, 'marketing') || str_contains($nama, 'promosi') ||
                    str_contains($nama, 'konsultan') || str_contains($nama, 'jasa') ||
                    str_contains($nama, 'asuransi') || str_contains($nama, 'insurance');

                return !$matchesAnyCategory;
            });

            $validationResults['uncategorized_accounts'] = $uncategorizedAccounts->map(function ($account) {
                return [
                    'id' => $account['id'],
                    'kode' => $account['kode'],
                    'nama' => $account['nama'],
                    'balance' => $account['balance']
                ];
            })->values();

            $validationResults['categorization_accuracy'] = $operationalExpenseAccounts->count() > 0
                ? (($operationalExpenseAccounts->count() - $uncategorizedAccounts->count()) / $operationalExpenseAccounts->count()) * 100
                : 100;

            return response()->json([
                'success' => true,
                'validation_results' => $validationResults,
                'period' => [
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in Operating Expenses Validation: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat validasi beban operasional',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed breakdown of specific expense category
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExpenseCategoryDetail(Request $request)
    {
        try {
            $category = $request->input('category', 'salary');
            $tanggalAwal = Carbon::parse($request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
            $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', now()->format('Y-m-d')))->endOfDay();

            // Get all expense accounts
            $allExpenseAccounts = $this->getAccountBalanceForPeriod('expense', $tanggalAwal, $tanggalAkhir);

            // Filter based on category
            $categoryAccounts = collect();

            switch ($category) {
                case 'salary':
                    $categoryAccounts = $allExpenseAccounts->filter(function ($expense) {
                        $nama = strtolower($expense['nama']);
                        $kode = $expense['kode'];
                        $salaryCodePattern = preg_match('/^(51|5211|611|621|6211)/', $kode);
                        $salaryNamePattern = str_contains($nama, 'gaji') || str_contains($nama, 'salary') ||
                            str_contains($nama, 'upah') || str_contains($nama, 'tunjangan') ||
                            str_contains($nama, 'payroll') || str_contains($nama, 'employee');
                        return $salaryCodePattern || $salaryNamePattern;
                    });
                    break;

                case 'utilities':
                    $categoryAccounts = $allExpenseAccounts->filter(function ($expense) {
                        $nama = strtolower($expense['nama']);
                        $kode = $expense['kode'];
                        $utilityCodePattern = preg_match('/^(52|5213|612|6121|6122)/', $kode);
                        $utilityNamePattern = str_contains($nama, 'listrik') || str_contains($nama, 'air') ||
                            str_contains($nama, 'telepon') || str_contains($nama, 'internet') ||
                            str_contains($nama, 'utilities') || str_contains($nama, 'pln');
                        return $utilityCodePattern || $utilityNamePattern;
                    });
                    break;

                    // Add more categories as needed
            }

            // Get journal entries for detailed view
            $detailedData = $categoryAccounts->map(function ($account) use ($tanggalAwal, $tanggalAkhir) {
                $journalEntries = JurnalUmum::where('akun_id', $account['id'])
                    ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                    ->with('akun')
                    ->orderBy('tanggal', 'desc')
                    ->get();

                return [
                    'account' => $account,
                    'journal_entries' => $journalEntries->map(function ($entry) {
                        return [
                            'tanggal' => $entry->tanggal,
                            'no_referensi' => $entry->no_referensi,
                            'keterangan' => $entry->keterangan,
                            'debit' => $entry->debit,
                            'kredit' => $entry->kredit
                        ];
                    })
                ];
            });

            return response()->json([
                'success' => true,
                'category' => $category,
                'total_accounts' => $categoryAccounts->count(),
                'total_amount' => $categoryAccounts->sum('balance'),
                'detailed_data' => $detailedData,
                'period' => [
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in Expense Category Detail: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat memuat detail kategori beban',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get account balance for specific category up to specific date
     *
     * @param string $category
     * @param Carbon $tanggalAkhir
     * @return \Illuminate\Support\Collection
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
                'id' => $account->id,
                'kode' => $account->kode,
                'nama' => $account->nama,
                'nama_akun' => $account->nama, // For compatibility with chart data
                'kategori' => $account->kategori,
                'debit' => $totalDebit,
                'kredit' => $totalKredit,
                'balance' => $balance
            ];
        })->filter(function ($account) {
            return $account['balance'] != 0; // Only show accounts with balance
        });
    }

    /**
     * Get account balance for specific category within period with enhanced accuracy
     *
     * @param string $category Account category (income, expense, asset, liability, equity)
     * @param Carbon $tanggalAwal Start date 
     * @param Carbon $tanggalAkhir End date
     * @return \Illuminate\Support\Collection
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
            // Get journal entries for this account within the period
            $journalEntries = JurnalUmum::where('akun_id', $account->id)
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->get();

            $totalDebit = $journalEntries->sum('debit');
            $totalKredit = $journalEntries->sum('kredit');

            // Calculate balance based on account category using proper accounting principles
            $balance = 0;
            if ($account->kategori == 'income') {
                // Income accounts have credit balances (kredit minus debit)
                $balance = $totalKredit - $totalDebit;
            } elseif ($account->kategori == 'expense') {
                // Expense accounts have debit balances (debit minus kredit)
                $balance = $totalDebit - $totalKredit;
            } elseif ($account->kategori == 'asset') {
                // Asset accounts have debit balances
                $balance = $totalDebit - $totalKredit;
            } elseif (in_array($account->kategori, ['liability', 'equity'])) {
                // Liability and equity accounts have credit balances
                $balance = $totalKredit - $totalDebit;
            }

            return [
                'id' => $account->id,
                'kode' => $account->kode,
                'nama' => $account->nama,
                'kategori' => $account->kategori,
                'debit' => $totalDebit,
                'kredit' => $totalKredit,
                'balance' => $balance,
                'journal_count' => $journalEntries->count() // Track number of transactions
            ];
        })->filter(function ($account) {
            // Only show accounts with non-zero balance or transaction activity
            return abs($account['balance']) > 0.01 || $account['journal_count'] > 0;
        });
    }

    /**
     * Get opening balances for Kas accounts
     *
     * @param Carbon $tanggalAwal
     * @return \Illuminate\Support\Collection
     */
    private function getKasOpeningBalances($tanggalAwal)
    {
        return Kas::where('is_aktif', true)->get()->map(function ($kas) use ($tanggalAwal) {
            // Calculate opening balance by getting all transactions before start date
            $totalMasuk = TransaksiKas::where('kas_id', $kas->id)
                ->where('jenis', 'masuk')
                ->where('tanggal', '<', $tanggalAwal)
                ->sum('jumlah');

            $totalKeluar = TransaksiKas::where('kas_id', $kas->id)
                ->where('jenis', 'keluar')
                ->where('tanggal', '<', $tanggalAwal)
                ->sum('jumlah');

            return [
                'id' => $kas->id,
                'nama' => $kas->nama,
                'opening_balance' => $totalMasuk - $totalKeluar
            ];
        });
    }

    /**
     * Get opening balances for Bank accounts
     *
     * @param Carbon $tanggalAwal
     * @return \Illuminate\Support\Collection
     */
    private function getBankOpeningBalances($tanggalAwal)
    {
        return RekeningBank::where('is_aktif', true)->get()->map(function ($rekening) use ($tanggalAwal) {
            // Calculate opening balance by getting all transactions before start date
            $totalMasuk = TransaksiBank::where('rekening_id', $rekening->id)
                ->where('jenis', 'masuk')
                ->where('tanggal', '<', $tanggalAwal)
                ->sum('jumlah');

            $totalKeluar = TransaksiBank::where('rekening_id', $rekening->id)
                ->where('jenis', 'keluar')
                ->where('tanggal', '<', $tanggalAwal)
                ->sum('jumlah');

            return [
                'id' => $rekening->id,
                'nama' => $rekening->nama_bank . ' - ' . $rekening->nomor_rekening,
                'opening_balance' => $totalMasuk - $totalKeluar
            ];
        });
    }
}