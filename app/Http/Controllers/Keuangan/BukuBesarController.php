<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\AkunAkuntansi;
use App\Models\JurnalUmum;
use App\Models\PeriodeAkuntansi;
use App\Exports\BukuBesarExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class BukuBesarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $akunId = $request->get('akun_id');
        $akunIds = $request->get('akun_ids', []);
        $periodeId = $request->get('periode_id');
        $tanggalAwal = $request->get('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->get('tanggal_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Get all active accounts
        $akuns = AkunAkuntansi::where('is_active', true)
            ->orderBy('kode')
            ->get();

        // Get all periods
        $periodes = PeriodeAkuntansi::orderBy('tanggal_mulai', 'desc')->get();

        $bukuBesarData = null;
        $selectedAkun = null;
        $selectedPeriode = null;
        $allAccountsData = null;
        $multipleAccountsData = [];

        if ($periodeId) {
            $selectedPeriode = PeriodeAkuntansi::findOrFail($periodeId);
            $tanggalAwal = $selectedPeriode->tanggal_mulai->format('Y-m-d');
            $tanggalAkhir = $selectedPeriode->tanggal_akhir->format('Y-m-d');
        }

        // Handle AJAX request for single account detail
        if ($request->get('ajax') == '1' && $akunId) {
            $bukuBesarData = $this->getBukuBesarData($request, $akunId, $tanggalAwal, $tanggalAkhir, $periodeId);
            $html = view('keuangan.buku_besar._transaction_detail', compact('bukuBesarData', 'tanggalAwal', 'tanggalAkhir'))->render();
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }

        // Handle multiple accounts selection
        if (!empty($akunIds) && is_array($akunIds)) {
            foreach ($akunIds as $id) {
                $accountData = $this->getBukuBesarData($request, $id, $tanggalAwal, $tanggalAkhir, $periodeId);
                $multipleAccountsData[] = $accountData;
            }
        } elseif ($akunId) {
            // Single account view (legacy support)
            $selectedAkun = AkunAkuntansi::findOrFail($akunId);
            $bukuBesarData = $this->getBukuBesarData($request, $akunId, $tanggalAwal, $tanggalAkhir, $periodeId);
        } else {
            // All accounts view - show accounts with balances
            $allAccountsData = $this->getAllAccountsWithBalances($request, $tanggalAwal, $tanggalAkhir, $periodeId);
        }

        return view('keuangan.buku_besar.index', compact(
            'akuns',
            'periodes',
            'selectedAkun',
            'selectedPeriode',
            'bukuBesarData',
            'allAccountsData',
            'multipleAccountsData',
            'tanggalAwal',
            'tanggalAkhir'
        ), [
            'currentPage' => 'Buku Besar',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Keuangan', 'url' => '#'],
                ['name' => 'Buku Besar']
            ]
        ]);
    }

    /**
     * Get buku besar data for specific account and date range
     */
    private function getBukuBesarData(Request $request, $akunId, $tanggalAwal, $tanggalAkhir, $periodeId = null)
    {
        $akun = AkunAkuntansi::findOrFail($akunId);

        // Build base query
        $baseQuery = JurnalUmum::where('akun_id', $akunId);

        // Filter for posted journals (with option to include drafts for debugging)
        $includeDrafts = $request->get('include_drafts', '0') == '1'; // Default false - only posted journals

        if (!$includeDrafts) {
            $baseQuery->where('is_posted', true); // Only posted journals
        }

        if ($periodeId) {
            $baseQuery->where('periode_id', $periodeId);
        }

        // Get opening balance (before start date)
        $openingQuery = clone $baseQuery;
        $openingDebit = $openingQuery->where('tanggal', '<', $tanggalAwal)->sum('debit');

        $openingQuery = clone $baseQuery;
        $openingKredit = $openingQuery->where('tanggal', '<', $tanggalAwal)->sum('kredit');

        // Calculate opening balance based on account category
        $openingBalance = $this->calculateBalance($akun->kategori, $openingDebit, $openingKredit);

        // Get transactions for the period
        $transaksiQuery = clone $baseQuery;
        $transaksi = $transaksiQuery->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('tanggal')
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

    /**
     * Calculate balance based on account category
     */
    private function calculateBalance($kategori, $debit, $kredit)
    {
        // For asset and expense accounts: debit increases balance
        if (in_array($kategori, ['asset', 'expense'])) {
            return $debit - $kredit;
        }

        // For liability, equity, and income accounts: credit increases balance
        return $kredit - $debit;
    }

    /**
     * Get all accounts with their balances
     */
    private function getAllAccountsWithBalances(Request $request, $tanggalAwal, $tanggalAkhir, $periodeId = null)
    {
        $accounts = AkunAkuntansi::where('is_active', true)
            // Include both detail and header accounts that have transactions
            ->whereIn('tipe', ['detail', 'header'])
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
            $query = JurnalUmum::where('akun_id', $account->id)
                ->where('tanggal', '<=', $tanggalAkhir);

            // Filter for posted journals (with option to include drafts for debugging)
            $includeDrafts = $request->get('include_drafts', '0') == '1';
            if (!$includeDrafts) {
                $query->where('is_posted', true); // Only posted journals
            }

            if ($periodeId) {
                $query->where('periode_id', $periodeId);
            }

            $totalDebit = $query->sum('debit');

            $query = JurnalUmum::where('akun_id', $account->id)
                ->where('tanggal', '<=', $tanggalAkhir);

            // Filter for posted journals (with option to include drafts for debugging)
            $includeDrafts = $request->get('include_drafts', '0') == '1';
            if (!$includeDrafts) {
                $query->where('is_posted', true); // Only posted journals
            }

            if ($periodeId) {
                $query->where('periode_id', $periodeId);
            }

            $totalKredit = $query->sum('kredit');

            $balance = $this->calculateBalance($account->kategori, $totalDebit, $totalKredit);

            // Count total transactions for this account WITHIN the period (not until end date)
            $transactionCount = JurnalUmum::where('akun_id', $account->id)
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);

            if (!$includeDrafts) {
                $transactionCount->where('is_posted', true);
            }

            if ($periodeId) {
                $transactionCount->where('periode_id', $periodeId);
            }

            $transactionCount = $transactionCount->count();

            // Only include accounts with transactions or non-zero balance
            if ($totalDebit > 0 || $totalKredit > 0 || $balance != 0) {
                $accountsWithBalances[] = [
                    'account' => $account,
                    'total_debit' => $totalDebit,
                    'total_kredit' => $totalKredit,
                    'balance' => $balance,
                    'formatted_balance' => $this->formatCurrency($balance),
                    'balance_type' => $balance >= 0 ? 'positive' : 'negative',
                    'transaction_count' => $transactionCount
                ];

                // Add to category totals
                if (isset($totalsByCategory[$account->kategori])) {
                    $totalsByCategory[$account->kategori] += $balance;
                } else {
                    $totalsByCategory['other'] += $balance;
                }
            }
        }

        // Sort by account code
        usort($accountsWithBalances, function ($a, $b) {
            return strcmp($a['account']->kode, $b['account']->kode);
        });

        return [
            'accounts' => $accountsWithBalances,
            'totals_by_category' => $totalsByCategory,
            'grand_total' => array_sum($totalsByCategory)
        ];
    }

    /**
     * Format currency for display
     */
    private function formatCurrency($amount)
    {
        $absAmount = abs($amount);
        $formatted = 'Rp ' . number_format($absAmount, 0, ',', '.');
        return $amount < 0 ? '(' . $formatted . ')' : $formatted;
    }

    /**
     * Export buku besar to Excel
     */
    public function export(Request $request)
    {
        $akunId = $request->get('akun_id');
        $tanggalAwal = $request->get('tanggal_awal');
        $tanggalAkhir = $request->get('tanggal_akhir');

        if (!$akunId) {
            return redirect()->back()->with('error', 'Pilih akun terlebih dahulu');
        }

        $akun = AkunAkuntansi::find($akunId);
        $filename = 'buku_besar_' . $akun->kode . '_' . str_replace('-', '', $tanggalAwal) . '_' . str_replace('-', '', $tanggalAkhir) . '.xlsx';

        return Excel::download(new BukuBesarExport($akunId, $tanggalAwal, $tanggalAkhir), $filename);
    }

    /**
     * Export buku besar ke Excel/CSV (all accounts or single account)
     */
    public function exportExcel(Request $request)
    {
        try {
            $akunId = $request->get('akun_id');
            $akunIds = $request->get('akun_ids', []);
            $periodeId = $request->get('periode_id');
            $tanggalAwal = $request->get('tanggal_awal', now()->startOfMonth()->format('Y-m-d'));
            $tanggalAkhir = $request->get('tanggal_akhir', now()->endOfMonth()->format('Y-m-d'));
            $includeDrafts = $request->get('include_drafts', '0') == '1';

            // If periode is selected, use periode dates
            if ($periodeId) {
                $periode = PeriodeAkuntansi::findOrFail($periodeId);
                $tanggalAwal = $periode->tanggal_mulai->format('Y-m-d');
                $tanggalAkhir = $periode->tanggal_akhir->format('Y-m-d');
            }

            // Validate date range
            if ($tanggalAwal > $tanggalAkhir) {
                return redirect()->back()
                    ->withErrors(['error' => 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.']);
            }

            // Handle multiple accounts filter
            if (!empty($akunIds) && is_array($akunIds)) {
                // Export multiple selected accounts with detailed transactions
                $filename = 'buku_besar_multiple_accounts_' . str_replace('-', '', $tanggalAwal) . '_' . str_replace('-', '', $tanggalAkhir) . '.xlsx';
                return \Maatwebsite\Excel\Facades\Excel::download(
                    new \App\Exports\BukuBesarMultipleAccountsExport($akunIds, $tanggalAwal, $tanggalAkhir, $includeDrafts),
                    $filename
                );
            } elseif ($akunId) {
                // Export single account with detailed transactions
                $akun = \App\Models\AkunAkuntansi::find($akunId);
                if (!$akun) {
                    return redirect()->back()
                        ->withErrors(['error' => 'Akun tidak ditemukan.']);
                }
                $filename = 'buku_besar_' . $akun->kode . '_' . str_replace('-', '', $tanggalAwal) . '_' . str_replace('-', '', $tanggalAkhir) . '.xlsx';
                return \Maatwebsite\Excel\Facades\Excel::download(
                    new \App\Exports\BukuBesarExport($akunId, $tanggalAwal, $tanggalAkhir, $includeDrafts),
                    $filename
                );
            } else {
                // Export all accounts with detailed transactions
                $filename = 'buku_besar_semua_akun_' . str_replace('-', '', $tanggalAwal) . '_' . str_replace('-', '', $tanggalAkhir) . '.xlsx';
                return \Maatwebsite\Excel\Facades\Excel::download(
                    new \App\Exports\BukuBesarAllAccountsExport($tanggalAwal, $tanggalAkhir, $includeDrafts),
                    $filename
                );
            }
        } catch (\Exception $e) {
            Log::error('Error exporting buku besar: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat export: ' . $e->getMessage()]);
        }
    }

    /**
     * Export buku besar ke PDF
     */
    public function exportPdf(Request $request)
    {
        try {
            // Increase execution time for large reports
            set_time_limit(120);
            ini_set('memory_limit', '512M');

            $akunId = $request->get('akun_id');
            $akunIds = $request->get('akun_ids', []);
            $periodeId = $request->get('periode_id');
            $tanggalAwal = $request->get('tanggal_awal', now()->startOfMonth()->format('Y-m-d'));
            $tanggalAkhir = $request->get('tanggal_akhir', now()->endOfMonth()->format('Y-m-d'));
            $includeDrafts = $request->get('include_drafts', '0') == '1';

            // If periode is selected, use periode dates
            $selectedPeriode = null;
            if ($periodeId) {
                $selectedPeriode = PeriodeAkuntansi::findOrFail($periodeId);
                $tanggalAwal = $selectedPeriode->tanggal_mulai->format('Y-m-d');
                $tanggalAkhir = $selectedPeriode->tanggal_akhir->format('Y-m-d');
            }

            // Validate date range
            if ($tanggalAwal > $tanggalAkhir) {
                return redirect()->back()
                    ->withErrors(['error' => 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.']);
            }

            $bukuBesarData = [];
            $reportType = '';

            // Handle multiple accounts filter
            if (!empty($akunIds) && is_array($akunIds)) {
                // Export multiple selected accounts with detailed transactions
                foreach ($akunIds as $id) {
                    $accountData = $this->getBukuBesarData($request, $id, $tanggalAwal, $tanggalAkhir, $periodeId);
                    $bukuBesarData[] = $accountData;
                }
                $reportType = 'multiple';
                $filename = 'buku_besar_multiple_' . str_replace('-', '', $tanggalAwal) . '_' . str_replace('-', '', $tanggalAkhir) . '.pdf';
            } elseif ($akunId) {
                // Export single account with detailed transactions
                $accountData = $this->getBukuBesarData($request, $akunId, $tanggalAwal, $tanggalAkhir, $periodeId);
                $bukuBesarData = [$accountData];
                $reportType = 'single';
                $akun = AkunAkuntansi::find($akunId);
                $filename = 'buku_besar_' . $akun->kode . '_' . str_replace('-', '', $tanggalAwal) . '_' . str_replace('-', '', $tanggalAkhir) . '.pdf';
            } else {
                // Export all accounts with detailed transactions
                $allAccountsWithBalances = $this->getAllAccountsWithBalances($request, $tanggalAwal, $tanggalAkhir, $periodeId);

                // Get detailed transactions for each account
                foreach ($allAccountsWithBalances['accounts'] as $item) {
                    $accountData = $this->getBukuBesarData($request, $item['account']->id, $tanggalAwal, $tanggalAkhir, $periodeId);
                    $bukuBesarData[] = $accountData;
                }

                $reportType = 'all';
                $filename = 'buku_besar_semua_akun_' . str_replace('-', '', $tanggalAwal) . '_' . str_replace('-', '', $tanggalAkhir) . '.pdf';
            }

            // Generate PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('keuangan.buku_besar.pdf', [
                'bukuBesarData' => $bukuBesarData,
                'allAccountsData' => $allAccountsData ?? null,
                'reportType' => $reportType,
                'tanggalAwal' => $tanggalAwal,
                'tanggalAkhir' => $tanggalAkhir,
                'selectedPeriode' => $selectedPeriode,
                'includeDrafts' => $includeDrafts
            ]);

            // Set paper size to A4 landscape for better table view
            $pdf->setPaper('a4', 'landscape');

            // Set options optimized for speed
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultFont' => 'Arial',
                'isFontSubsettingEnabled' => false,
                'dpi' => 96,
                'debugPng' => false,
                'debugKeepTemp' => false,
                'debugCss' => false,
                'enable_php' => true,
                'chroot' => public_path(),
            ]);

            return $pdf->stream($filename);
        } catch (\Exception $e) {
            Log::error('Error exporting buku besar PDF: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat export PDF: ' . $e->getMessage()]);
        }
    }

    /**
     * API endpoint to get account balance
     */
    public function getAccountBalance(Request $request)
    {
        $akunId = $request->get('akun_id');
        $tanggal = $request->get('tanggal', Carbon::now()->format('Y-m-d'));

        if (!$akunId) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ID diperlukan'
            ], 400);
        }

        $akun = AkunAkuntansi::find($akunId);
        if (!$akun) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak ditemukan'
            ], 404);
        }

        $totalDebit = JurnalUmum::where('akun_id', $akunId)
            ->where('tanggal', '<=', $tanggal);

        // TEMPORARY FIX: Include draft journals with warning
        $includeDrafts = request()->get('include_drafts', true);
        if (!$includeDrafts) {
            $totalDebit->where('is_posted', true); // Only posted journals
        }
        $totalDebit = $totalDebit->sum('debit');

        $totalKredit = JurnalUmum::where('akun_id', $akunId)
            ->where('tanggal', '<=', $tanggal);

        if (!$includeDrafts) {
            $totalKredit->where('is_posted', true); // Only posted journals
        }
        $totalKredit = $totalKredit->sum('kredit');

        $balance = $this->calculateBalance($akun->kategori, $totalDebit, $totalKredit);

        return response()->json([
            'success' => true,
            'data' => [
                'akun' => $akun,
                'total_debit' => $totalDebit,
                'total_kredit' => $totalKredit,
                'saldo' => $balance
            ]
        ]);
    }
}
