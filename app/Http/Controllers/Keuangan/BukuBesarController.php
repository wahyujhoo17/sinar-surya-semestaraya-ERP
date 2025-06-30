<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\AkunAkuntansi;
use App\Models\JurnalUmum;
use App\Exports\BukuBesarExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $tanggalAwal = $request->get('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->get('tanggal_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Get all active accounts
        $akuns = AkunAkuntansi::where('is_active', true)
            ->orderBy('kode')
            ->get();

        $bukuBesarData = null;
        $selectedAkun = null;
        $allAccountsData = null;

        if ($akunId) {
            // Single account view
            $selectedAkun = AkunAkuntansi::findOrFail($akunId);
            $bukuBesarData = $this->getBukuBesarData($akunId, $tanggalAwal, $tanggalAkhir);
        } else {
            // All accounts view - show accounts with balances
            $allAccountsData = $this->getAllAccountsWithBalances($tanggalAkhir);
        }

        return view('keuangan.buku_besar.index', compact(
            'akuns',
            'selectedAkun',
            'bukuBesarData',
            'allAccountsData',
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
    private function getBukuBesarData($akunId, $tanggalAwal, $tanggalAkhir)
    {
        $akun = AkunAkuntansi::findOrFail($akunId);

        // Get opening balance (before start date)
        $openingDebit = JurnalUmum::where('akun_id', $akunId)
            ->where('tanggal', '<', $tanggalAwal)
            ->sum('debit');

        $openingKredit = JurnalUmum::where('akun_id', $akunId)
            ->where('tanggal', '<', $tanggalAwal)
            ->sum('kredit');

        // Calculate opening balance based on account category
        $openingBalance = $this->calculateBalance($akun->kategori, $openingDebit, $openingKredit);

        // Get transactions for the period
        $transaksi = JurnalUmum::where('akun_id', $akunId)
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
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
    private function getAllAccountsWithBalances($tanggalAkhir)
    {
        $accounts = AkunAkuntansi::where('is_active', true)
            ->where('tipe', 'detail') // Only detail accounts have transactions
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
            $totalDebit = JurnalUmum::where('akun_id', $account->id)
                ->where('tanggal', '<=', $tanggalAkhir)
                ->sum('debit');

            $totalKredit = JurnalUmum::where('akun_id', $account->id)
                ->where('tanggal', '<=', $tanggalAkhir)
                ->sum('kredit');

            $balance = $this->calculateBalance($account->kategori, $totalDebit, $totalKredit);

            // Only include accounts with transactions or non-zero balance
            if ($totalDebit > 0 || $totalKredit > 0 || $balance != 0) {
                $accountsWithBalances[] = [
                    'account' => $account,
                    'total_debit' => $totalDebit,
                    'total_kredit' => $totalKredit,
                    'balance' => $balance,
                    'formatted_balance' => $this->formatCurrency($balance),
                    'balance_type' => $balance >= 0 ? 'positive' : 'negative'
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
            $tanggalAwal = $request->get('tanggal_awal', now()->startOfMonth()->format('Y-m-d'));
            $tanggalAkhir = $request->get('tanggal_akhir', now()->endOfMonth()->format('Y-m-d'));

            // Validate date range
            if ($tanggalAwal > $tanggalAkhir) {
                return redirect()->back()
                    ->withErrors(['error' => 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.']);
            }

            if ($akunId) {
                // Export satu akun (pakai export lama dengan Excel format)
                $akun = \App\Models\AkunAkuntansi::find($akunId);
                if (!$akun) {
                    return redirect()->back()
                        ->withErrors(['error' => 'Akun tidak ditemukan.']);
                }
                $filename = 'buku_besar_' . $akun->kode . '_' . str_replace('-', '', $tanggalAwal) . '_' . str_replace('-', '', $tanggalAkhir) . '.xlsx';
                return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BukuBesarExport($akunId, $tanggalAwal, $tanggalAkhir), $filename);
            } else {
                // Export seluruh akun dengan format Excel yang bagus
                $filename = 'buku_besar_semua_akun_' . str_replace('-', '', $tanggalAwal) . '_' . str_replace('-', '', $tanggalAkhir) . '.xlsx';
                return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BukuBesarAllAccountsExport($tanggalAwal, $tanggalAkhir), $filename);
            }
        } catch (\Exception $e) {
            \Log::error('Error exporting buku besar: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat export: ' . $e->getMessage()]);
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
            ->where('tanggal', '<=', $tanggal)
            ->sum('debit');

        $totalKredit = JurnalUmum::where('akun_id', $akunId)
            ->where('tanggal', '<=', $tanggal)
            ->sum('kredit');

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