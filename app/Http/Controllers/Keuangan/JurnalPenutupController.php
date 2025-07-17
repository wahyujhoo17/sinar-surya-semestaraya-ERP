<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\JurnalUmum;
use App\Models\AkunAkuntansi;
use App\Models\PeriodeAkuntansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class JurnalPenutupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $periode = $request->get('periode_id');
        $tanggalAwal = $request->get('tanggal_awal', Carbon::now()->startOfYear()->format('Y-m-d'));
        $tanggalAkhir = $request->get('tanggal_akhir', Carbon::now()->endOfYear()->format('Y-m-d'));
        $noReferensi = $request->get('no_referensi');
        $status = $request->get('status');
        $sort = $request->get('sort', 'tanggal');
        $direction = $request->get('direction', 'desc');
        $perPage = $request->get('per_page', 15);

        // Base query for closing journals
        $query = JurnalUmum::with(['akun', 'user', 'postedBy'])
            ->where('jenis_jurnal', 'penutup')
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);

        // Apply filters
        if ($periode) {
            // Add periode filter if needed - assuming you have a periode_id field
            // $query->where('periode_id', $periode);
        }

        if ($noReferensi) {
            $query->where('no_referensi', 'like', '%' . $noReferensi . '%');
        }

        if ($status !== null && $status !== '') {
            $query->where('is_posted', $status == 'posted' ? 1 : 0);
        }

        // Get unique no_referensi with grouping
        $subQuery = $query->select('no_referensi')
            ->groupBy('no_referensi')
            ->orderBy($sort == 'tanggal' ? 'tanggal' : ($sort == 'no_referensi' ? 'no_referensi' : 'keterangan'), $direction);

        // Get paginated no_referensi
        $paginatedRefs = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
            ->mergeBindings($subQuery->getQuery())
            ->paginate($perPage, ['no_referensi'], 'page');

        // Get the actual journal data for the paginated references
        $jurnalRefs = $paginatedRefs->pluck('no_referensi');

        $jurnalData = JurnalUmum::with(['akun', 'user', 'postedBy'])
            ->where('jenis_jurnal', 'penutup')
            ->whereIn('no_referensi', $jurnalRefs)
            ->get()
            ->groupBy('no_referensi');

        // Transform the data
        $jurnals = collect();
        foreach ($paginatedRefs as $ref) {
            $group = $jurnalData->get($ref->no_referensi);
            if ($group) {
                $first = $group->first();
                $totalDebit = $group->sum('debit');
                $totalKredit = $group->sum('kredit');

                // Get debit and kredit accounts
                $debitAccounts = $group->where('debit', '>', 0)->map(function ($item) {
                    return (object) [
                        'kode_akun' => $item->akun->kode ?? $item->kode_akun,
                        'nama_akun' => $item->akun->nama ?? 'Akun tidak ditemukan',
                        'jumlah' => $item->debit
                    ];
                });

                $kreditAccounts = $group->where('kredit', '>', 0)->map(function ($item) {
                    return (object) [
                        'kode_akun' => $item->akun->kode ?? $item->kode_akun,
                        'nama_akun' => $item->akun->nama ?? 'Akun tidak ditemukan',
                        'jumlah' => $item->kredit
                    ];
                });

                $jurnals->push((object) [
                    'no_referensi' => $first->no_referensi,
                    'tanggal' => $first->tanggal,
                    'keterangan' => $first->keterangan,
                    'is_posted' => $first->is_posted,
                    'posted_at' => $first->posted_at,
                    'user' => $first->user,
                    'posted_by_user' => $first->postedBy,
                    'count_entries' => $group->count(),
                    'total_debit' => $totalDebit,
                    'total_kredit' => $totalKredit,
                    'is_balanced' => $totalDebit == $totalKredit,
                    'debit_accounts' => $debitAccounts,
                    'kredit_accounts' => $kreditAccounts
                ]);
            }
        }

        // Create a new paginator with our transformed data
        $jurnals = new LengthAwarePaginator(
            $jurnals,
            $paginatedRefs->total(),
            $paginatedRefs->perPage(),
            $paginatedRefs->currentPage(),
            [
                'path' => $request->url(),
                'pageName' => 'page',
            ]
        );

        // Append current query parameters to pagination links
        $jurnals->appends($request->query());

        // Get available periods
        $periods = PeriodeAkuntansi::orderBy('tanggal_mulai', 'desc')->get();

        if ($request->ajax()) {
            return response()->json([
                'table_html' => view('keuangan.jurnal_penutup._table', compact('jurnals'))->render(),
                'pagination_html' => view('keuangan.jurnal_penutup._pagination', compact('jurnals'))->render(),
                'total' => method_exists($jurnals, 'total') ? $jurnals->total() : $jurnals->count(),
                'first_item' => method_exists($jurnals, 'firstItem') ? $jurnals->firstItem() : 1,
                'last_item' => method_exists($jurnals, 'lastItem') ? $jurnals->lastItem() : $jurnals->count(),
                'current_page' => method_exists($jurnals, 'currentPage') ? $jurnals->currentPage() : 1,
                'per_page' => method_exists($jurnals, 'perPage') ? $jurnals->perPage() : 15
            ]);
        }

        return view('keuangan.jurnal_penutup.index', compact(
            'jurnals',
            'periods',
            'periode',
            'tanggalAwal',
            'tanggalAkhir'
        ), [
            'currentPage' => 'Jurnal Penutup',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Keuangan', 'url' => '#'],
                ['name' => 'Jurnal Penutup']
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $mode = $request->get('mode', 'manual'); // manual or auto
        $periode_id = $request->get('periode_id');
        $isAjax = $request->ajax() || $request->get('ajax');

        // Get available periods
        $periods = PeriodeAkuntansi::where('status', 'open')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // Get current period if not specified
        if (!$periode_id && $periods->count() > 0) {
            $currentPeriod = PeriodeAkuntansi::getCurrentPeriode();
            $periode_id = $currentPeriod ? $currentPeriod->id : $periods->first()->id;
        }

        $selectedPeriod = $periode_id ? PeriodeAkuntansi::find($periode_id) : null;

        // Get accounts for manual mode
        $accounts = AkunAkuntansi::where('is_active', true)
            ->where('tipe', 'detail')
            ->orderBy('kode')
            ->get();

        // For auto mode, get summary of accounts to be closed
        $autoClosingData = null;
        if ($mode === 'auto' && $selectedPeriod) {
            $autoClosingData = $this->getAutoClosingData($selectedPeriod);

            // If this is an AJAX request, return JSON data for preview
            if ($isAjax) {
                return $this->getAutoClosingPreviewDataPrivate($selectedPeriod);
            }
        }

        return view('keuangan.jurnal_penutup.create', compact(
            'mode',
            'periods',
            'selectedPeriod',
            'accounts',
            'autoClosingData'
        ), [
            'currentPage' => 'Buat Jurnal Penutup',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Keuangan', 'url' => '#'],
                ['name' => 'Jurnal Penutup', 'url' => route('keuangan.jurnal-penutup.index')],
                [
                    'name' => 'Buat Baru'
                ]
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mode = $request->input('mode', 'manual');

        if ($mode === 'auto') {
            return $this->storeAutoClosing($request);
        } else {
            return $this->storeManualClosing($request);
        }
    }

    /**
     * Store manual closing journal
     */
    private function storeManualClosing(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'periode_id' => 'nullable|exists:periode_akuntansi,id',
            'details' => 'required|array|min:2',
            'details.*.akun_id' => 'required|exists:akun_akuntansi,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.kredit' => 'required|numeric|min:0',
            'details.*.keterangan' => 'nullable|string|max:255',
        ], [
            'details.required' => 'Minimal harus ada 2 entri jurnal',
            'details.min' => 'Minimal harus ada 2 entri jurnal',
        ]);

        // Validate balance
        $totalDebit = collect($request->details)->sum('debit');
        $totalKredit = collect($request->details)->sum('kredit');

        if ($totalDebit != $totalKredit) {
            return back()->withErrors([
                'balance' => 'Total debit (' . number_format($totalDebit) . ') tidak sama dengan total kredit (' . number_format($totalKredit) . ')'
            ])->withInput();
        }

        // Validate that each entry has either debit OR kredit, not both
        foreach ($request->details as $index => $detail) {
            if ($detail['debit'] > 0 && $detail['kredit'] > 0) {
                return back()->withErrors([
                    "details.{$index}" => 'Setiap entri hanya boleh memiliki nilai debit atau kredit, tidak keduanya'
                ])->withInput();
            }
            if ($detail['debit'] == 0 && $detail['kredit'] == 0) {
                return back()->withErrors([
                    "details.{$index}" => 'Setiap entri harus memiliki nilai debit atau kredit'
                ])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            // Generate reference number
            $noReferensi = 'JP-' . date('Ymd') . '-' . str_pad(
                JurnalUmum::where('jenis_jurnal', 'penutup')
                    ->whereDate('created_at', today())
                    ->count() + 1,
                3,
                '0',
                STR_PAD_LEFT
            );

            // Create journal entries
            foreach ($request->details as $detail) {
                JurnalUmum::create([
                    'tanggal' => $request->tanggal,
                    'no_referensi' => $noReferensi,
                    'akun_id' => $detail['akun_id'],
                    'debit' => $detail['debit'],
                    'kredit' => $detail['kredit'],
                    'keterangan' => $detail['keterangan'] ?: $request->keterangan,
                    'jenis_jurnal' => 'penutup',
                    'sumber' => 'manual_closing',
                    'user_id' => Auth::id(),
                    'is_posted' => false,
                ]);
            }

            DB::commit();

            return redirect()->route('keuangan.jurnal-penutup.index')
                ->with('success', 'Jurnal penutup berhasil dibuat dengan nomor referensi: ' . $noReferensi);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Store automatic closing journal
     */
    private function storeAutoClosing(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periode_akuntansi,id',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
        ]);

        $periode = PeriodeAkuntansi::find($request->periode_id);

        // Check if period allows closing
        if (!$periode->allowsPosting()) {
            return back()->withErrors(['periode_id' => 'Periode ini tidak dapat ditutup']);
        }

        DB::beginTransaction();
        try {
            // Get closing data
            $closingData = $this->getAutoClosingData($periode);

            if (empty($closingData['income_accounts']) && empty($closingData['expense_accounts'])) {
                return back()->withErrors(['error' => 'Tidak ada akun pendapatan atau beban untuk ditutup']);
            }

            $noReferensi = 'JP-AUTO-' . date('Ymd') . '-' . str_pad(
                JurnalUmum::where('jenis_jurnal', 'penutup')
                    ->whereDate('created_at', today())
                    ->count() + 1,
                3,
                '0',
                STR_PAD_LEFT
            );

            // Find or create Income Summary account
            $incomeSummaryAccount = $this->getOrCreateIncomeSummaryAccount();

            // Find Retained Earnings account
            $retainedEarningsAccount = $this->getRetainedEarningsAccount();

            // Close income accounts (Debit Income, Credit Income Summary)
            foreach ($closingData['income_accounts'] as $account) {
                if ($account['balance'] > 0) {
                    // Debit Income Account
                    JurnalUmum::create([
                        'tanggal' => $request->tanggal,
                        'no_referensi' => $noReferensi,
                        'akun_id' => $account['id'],
                        'debit' => $account['balance'],
                        'kredit' => 0,
                        'keterangan' => 'Penutupan akun pendapatan: ' . $account['nama'],
                        'jenis_jurnal' => 'penutup',
                        'sumber' => 'auto_closing_income',
                        'user_id' => Auth::id(),
                        'is_posted' => false,
                    ]);
                }
            }

            // Credit Income Summary with total income
            if ($closingData['total_income'] > 0) {
                JurnalUmum::create([
                    'tanggal' => $request->tanggal,
                    'no_referensi' => $noReferensi,
                    'akun_id' => $incomeSummaryAccount->id,
                    'debit' => 0,
                    'kredit' => $closingData['total_income'],
                    'keterangan' => 'Transfer total pendapatan ke income summary',
                    'jenis_jurnal' => 'penutup',
                    'sumber' => 'auto_closing_income_summary',
                    'user_id' => Auth::id(),
                    'is_posted' => false,
                ]);
            }

            // Close expense accounts (Credit Expense, Debit Income Summary)
            foreach ($closingData['expense_accounts'] as $account) {
                if ($account['balance'] > 0) {
                    // Credit Expense Account
                    JurnalUmum::create([
                        'tanggal' => $request->tanggal,
                        'no_referensi' => $noReferensi,
                        'akun_id' => $account['id'],
                        'debit' => 0,
                        'kredit' => $account['balance'],
                        'keterangan' => 'Penutupan akun beban: ' . $account['nama'],
                        'jenis_jurnal' => 'penutup',
                        'sumber' => 'auto_closing_expense',
                        'user_id' => Auth::id(),
                        'is_posted' => false,
                    ]);
                }
            }

            // Debit Income Summary with total expenses
            if ($closingData['total_expenses'] > 0) {
                JurnalUmum::create([
                    'tanggal' => $request->tanggal,
                    'no_referensi' => $noReferensi,
                    'akun_id' => $incomeSummaryAccount->id,
                    'debit' => $closingData['total_expenses'],
                    'kredit' => 0,
                    'keterangan' => 'Transfer total beban ke income summary',
                    'jenis_jurnal' => 'penutup',
                    'sumber' => 'auto_closing_expense_summary',
                    'user_id' => Auth::id(),
                    'is_posted' => false,
                ]);
            }

            // Close Income Summary to Retained Earnings
            $netIncome = $closingData['total_income'] - $closingData['total_expenses'];
            if ($netIncome != 0) {
                if ($netIncome > 0) {
                    // Profit: Debit Income Summary, Credit Retained Earnings
                    JurnalUmum::create([
                        'tanggal' => $request->tanggal,
                        'no_referensi' => $noReferensi,
                        'akun_id' => $incomeSummaryAccount->id,
                        'debit' => $netIncome,
                        'kredit' => 0,
                        'keterangan' => 'Transfer laba bersih ke laba ditahan',
                        'jenis_jurnal' => 'penutup',
                        'sumber' => 'auto_closing_net_income',
                        'user_id' => Auth::id(),
                        'is_posted' => false,
                    ]);

                    JurnalUmum::create([
                        'tanggal' => $request->tanggal,
                        'no_referensi' => $noReferensi,
                        'akun_id' => $retainedEarningsAccount->id,
                        'debit' => 0,
                        'kredit' => $netIncome,
                        'keterangan' => 'Penerimaan laba bersih',
                        'jenis_jurnal' => 'penutup',
                        'sumber' => 'auto_closing_net_income',
                        'user_id' => Auth::id(),
                        'is_posted' => false,
                    ]);
                } else {
                    // Loss: Credit Income Summary, Debit Retained Earnings
                    $netLoss = abs($netIncome);

                    JurnalUmum::create([
                        'tanggal' => $request->tanggal,
                        'no_referensi' => $noReferensi,
                        'akun_id' => $incomeSummaryAccount->id,
                        'debit' => 0,
                        'kredit' => $netLoss,
                        'keterangan' => 'Transfer rugi bersih ke laba ditahan',
                        'jenis_jurnal' => 'penutup',
                        'sumber' => 'auto_closing_net_loss',
                        'user_id' => Auth::id(),
                        'is_posted' => false,
                    ]);

                    JurnalUmum::create([
                        'tanggal' => $request->tanggal,
                        'no_referensi' => $noReferensi,
                        'akun_id' => $retainedEarningsAccount->id,
                        'debit' => $netLoss,
                        'kredit' => 0,
                        'keterangan' => 'Pengurangan akibat rugi bersih',
                        'jenis_jurnal' => 'penutup',
                        'sumber' => 'auto_closing_net_loss',
                        'user_id' => Auth::id(),
                        'is_posted' => false,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('keuangan.jurnal-penutup.index')
                ->with('success', 'Jurnal penutup otomatis berhasil dibuat dengan nomor referensi: ' . $noReferensi .
                    '. Laba bersih: Rp ' . number_format($netIncome));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $no_referensi)
    {
        $entries = JurnalUmum::with(['akun', 'user', 'postedBy', 'periode'])
            ->where('no_referensi', $no_referensi)
            ->where('jenis_jurnal', 'penutup')
            ->orderBy('id')
            ->get();

        if ($entries->isEmpty()) {
            abort(404, 'Jurnal penutup tidak ditemukan');
        }

        // Calculate totals
        $totalDebit = $entries->sum('debit');
        $totalKredit = $entries->sum('kredit');

        // Group entries by source for better display
        $groupedEntries = $entries->groupBy('sumber');

        // Create a mock jurnal penutup object for the view
        $firstEntry = $entries->first();

        // Try to get period from relation, or find by date if missing
        $periode = $firstEntry->periode;
        if (!$periode && $firstEntry->tanggal) {
            $periode = PeriodeAkuntansi::getPeriodeForDate($firstEntry->tanggal);
        }

        $jurnalPenutup = (object) [
            'id' => $firstEntry->id,
            'no_referensi' => $firstEntry->no_referensi,
            'tanggal' => $firstEntry->tanggal,
            'keterangan' => $firstEntry->keterangan,
            'is_posted' => $firstEntry->is_posted,
            'posted_at' => $firstEntry->posted_at,
            'posted_by' => $firstEntry->posted_by,
            'postedBy' => $firstEntry->postedBy,
            'created_at' => $firstEntry->created_at,
            'updated_at' => $firstEntry->updated_at,
            'createdBy' => $firstEntry->user, // User who created the entry
            'details' => $entries,
            'periodeAkuntansi' => $periode, // Use found or related periode
            'total_debit' => $totalDebit,
            'total_kredit' => $totalKredit
        ];

        return view('keuangan.jurnal_penutup.show', compact(
            'entries',
            'totalDebit',
            'totalKredit',
            'groupedEntries',
            'jurnalPenutup'
        ), [
            'currentPage' => 'Detail Jurnal Penutup',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Keuangan', 'url' => '#'],
                ['name' => 'Jurnal Penutup', 'url' => route('keuangan.jurnal-penutup.index')],
                ['name' => $no_referensi]
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $no_referensi)
    {
        $entries = JurnalUmum::with('akun')
            ->where('no_referensi', $no_referensi)
            ->where('jenis_jurnal', 'penutup')
            ->orderBy('id')
            ->get();

        if ($entries->isEmpty()) {
            abort(404, 'Jurnal penutup tidak ditemukan');
        }

        // Check if can be edited
        if ($entries->first()->is_posted) {
            return redirect()->route('keuangan.jurnal-penutup.show', $no_referensi)
                ->with('error', 'Jurnal yang sudah diposting tidak dapat diedit');
        }

        // Get available periods
        $periods = PeriodeAkuntansi::orderBy('tanggal_mulai', 'desc')->get();

        // Simulate journal object for edit form
        $jurnalPenutup = (object) [
            'id' => $entries->first()->id,
            'tanggal' => $entries->first()->tanggal,
            'no_referensi' => $entries->first()->no_referensi,
            'keterangan' => $entries->first()->keterangan,
            'periode_id' => $entries->first()->periode_id,
            'details' => $entries
        ];

        $accounts = AkunAkuntansi::where('is_active', true)
            ->where('tipe', 'detail')
            ->orderBy('kode')
            ->get();

        return view('keuangan.jurnal_penutup.edit', compact(
            'jurnalPenutup',
            'accounts',
            'periods'
        ), [
            'currentPage' => 'Edit Jurnal Penutup',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Keuangan', 'url' => '#'],
                ['name' => 'Jurnal Penutup', 'url' => route('keuangan.jurnal-penutup.index')],
                ['name' => 'Edit ' . $no_referensi]
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $no_referensi)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'details' => 'required|array|min:2',
            'details.*.akun_id' => 'required|exists:akun_akuntansi,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.kredit' => 'required|numeric|min:0',
            'details.*.keterangan' => 'nullable|string|max:255',
        ]);

        // Validate balance
        $totalDebit = collect($request->details)->sum('debit');
        $totalKredit = collect($request->details)->sum('kredit');

        if ($totalDebit != $totalKredit) {
            return back()->withErrors([
                'balance' => 'Total debit (' . number_format($totalDebit) . ') tidak sama dengan total kredit (' . number_format($totalKredit) . ')'
            ])->withInput();
        }

        DB::beginTransaction();
        try {
            // Check if entries can be updated
            $existingEntries = JurnalUmum::where('no_referensi', $no_referensi)
                ->where('jenis_jurnal', 'penutup')
                ->get();

            if ($existingEntries->first()->is_posted) {
                return back()->withErrors(['error' => 'Jurnal yang sudah diposting tidak dapat diedit']);
            }

            // Delete old entries
            JurnalUmum::where('no_referensi', $no_referensi)
                ->where('jenis_jurnal', 'penutup')
                ->delete();

            // Create new entries
            foreach ($request->details as $detail) {
                JurnalUmum::create([
                    'tanggal' => $request->tanggal,
                    'no_referensi' => $no_referensi,
                    'akun_id' => $detail['akun_id'],
                    'debit' => $detail['debit'],
                    'kredit' => $detail['kredit'],
                    'keterangan' => $detail['keterangan'] ?: $request->keterangan,
                    'jenis_jurnal' => 'penutup',
                    'sumber' => 'manual_closing_updated',
                    'user_id' => Auth::id(),
                    'is_posted' => false,
                ]);
            }

            DB::commit();

            return redirect()->route('keuangan.jurnal-penutup.show', $no_referensi)
                ->with('success', 'Jurnal penutup berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $no_referensi)
    {
        DB::beginTransaction();
        try {
            $entries = JurnalUmum::where('no_referensi', $no_referensi)
                ->where('jenis_jurnal', 'penutup')
                ->get();

            if ($entries->isEmpty()) {
                return redirect()->route('keuangan.jurnal-penutup.index')
                    ->with('error', 'Jurnal penutup tidak ditemukan');
            }

            // Check if can be deleted
            if ($entries->first()->is_posted) {
                return redirect()->route('keuangan.jurnal-penutup.index')
                    ->with('error', 'Jurnal yang sudah diposting tidak dapat dihapus');
            }

            // Delete all entries
            JurnalUmum::where('no_referensi', $no_referensi)
                ->where('jenis_jurnal', 'penutup')
                ->delete();

            DB::commit();

            return redirect()->route('keuangan.jurnal-penutup.index')
                ->with('success', 'Jurnal penutup berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('keuangan.jurnal-penutup.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Post/unpost journal entries
     */
    public function togglePost(Request $request, string $no_referensi)
    {
        DB::beginTransaction();
        try {
            $entries = JurnalUmum::where('no_referensi', $no_referensi)
                ->where('jenis_jurnal', 'penutup')
                ->get();

            if ($entries->isEmpty()) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Jurnal tidak ditemukan']);
                }
                return redirect()->back()->withErrors(['error' => 'Jurnal tidak ditemukan']);
            }

            $isPosted = $entries->first()->is_posted;
            $newStatus = !$isPosted;

            foreach ($entries as $entry) {
                if ($newStatus) {
                    $entry->post(Auth::id());
                } else {
                    $entry->unpost();
                }
            }

            DB::commit();

            $message = $newStatus ? 'Jurnal berhasil diposting' : 'Posting jurnal berhasil dibatalkan';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'is_posted' => $newStatus
                ]);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }

            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Get data for automatic closing
     */
    private function getAutoClosingData(PeriodeAkuntansi $periode)
    {
        $startDate = $periode->tanggal_mulai;
        $endDate = $periode->tanggal_akhir;

        // Get income accounts with balances
        $incomeAccounts = $this->getAccountsWithBalances('income', $startDate, $endDate);

        // Get expense accounts with balances
        $expenseAccounts = $this->getAccountsWithBalances('expense', $startDate, $endDate);

        $totalIncome = $incomeAccounts->sum('balance');
        $totalExpenses = $expenseAccounts->sum('balance');
        $netIncome = $totalIncome - $totalExpenses;

        return [
            'periode' => $periode,
            'income_accounts' => $incomeAccounts->toArray(),
            'expense_accounts' => $expenseAccounts->toArray(),
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'net_income' => $netIncome,
        ];
    }

    /**
     * Get accounts with balances for a specific category and period
     */
    private function getAccountsWithBalances($category, $startDate, $endDate)
    {
        $accounts = AkunAkuntansi::where('kategori', $category)
            ->where('is_active', true)
            ->where('tipe', 'detail')
            ->orderBy('kode')
            ->get();

        return $accounts->map(function ($account) use ($startDate, $endDate) {
            $journalEntries = JurnalUmum::where('akun_id', $account->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get();

            $totalDebit = $journalEntries->sum('debit');
            $totalKredit = $journalEntries->sum('kredit');

            // Calculate balance based on account category
            $balance = 0;
            if ($account->kategori === 'income') {
                $balance = $totalKredit - $totalDebit; // Income has credit balance
            } elseif ($account->kategori === 'expense') {
                $balance = $totalDebit - $totalKredit; // Expense has debit balance
            }

            return [
                'id' => $account->id,
                'kode' => $account->kode,
                'nama' => $account->nama,
                'balance' => $balance
            ];
        })->filter(function ($account) {
            return $account['balance'] > 0; // Only accounts with positive balances
        });
    }

    /**
     * Get or create Income Summary account
     */
    private function getOrCreateIncomeSummaryAccount()
    {
        $account = AkunAkuntansi::where('nama', 'LIKE', '%income summary%')
            ->orWhere('nama', 'LIKE', '%ikhtisar laba rugi%')
            ->orWhere('kode', '3300')
            ->first();

        if (!$account) {
            // Find equity parent account
            $equityParent = AkunAkuntansi::where('kategori', 'equity')
                ->where('tipe', 'header')
                ->first();

            $account = AkunAkuntansi::create([
                'kode' => '3300',
                'nama' => 'Ikhtisar Laba Rugi',
                'kategori' => 'equity',
                'tipe' => 'detail',
                'parent_id' => $equityParent ? $equityParent->id : null,
                'is_active' => true
            ]);
        }

        return $account;
    }

    /**
     * Get Retained Earnings account
     */
    private function getRetainedEarningsAccount()
    {
        $account = AkunAkuntansi::where('nama', 'LIKE', '%laba ditahan%')
            ->orWhere('nama', 'LIKE', '%retained earnings%')
            ->orWhere('kode', '3200')
            ->first();

        if (!$account) {
            // Find equity parent account
            $equityParent = AkunAkuntansi::where('kategori', 'equity')
                ->where('tipe', 'header')
                ->first();

            $account = AkunAkuntansi::create([
                'kode' => '3200',
                'nama' => 'Laba Ditahan',
                'kategori' => 'equity',
                'tipe' => 'detail',
                'parent_id' => $equityParent ? $equityParent->id : null,
                'is_active' => true
            ]);
        }

        return $account;
    }

    /**
     * Get data for automatic closing preview (AJAX)
     */
    private function getAutoClosingPreviewDataPrivate(PeriodeAkuntansi $periode)
    {
        try {
            $closingData = $this->getAutoClosingData($periode);

            // Build journal entries for preview
            $entries = [];
            $totalDebit = 0;
            $totalKredit = 0;

            // Get or create required accounts
            $incomeSummaryAccount = $this->getOrCreateIncomeSummaryAccount();
            $retainedEarningsAccount = $this->getRetainedEarningsAccount();

            // 1. Close income accounts (Debit Income, Credit Income Summary)
            foreach ($closingData['income_accounts'] as $account) {
                if ($account['balance'] > 0) {
                    $entries[] = [
                        'akun_code' => $account['kode'],
                        'akun_name' => $account['nama'],
                        'akun_jenis' => 'Pendapatan',
                        'keterangan' => 'Penutupan akun pendapatan: ' . $account['nama'],
                        'debit' => $account['balance'],
                        'kredit' => 0
                    ];
                    $totalDebit += $account['balance'];
                }
            }

            // Credit Income Summary with total income
            if ($closingData['total_income'] > 0) {
                $entries[] = [
                    'akun_code' => $incomeSummaryAccount->kode,
                    'akun_name' => $incomeSummaryAccount->nama,
                    'akun_jenis' => 'Ekuitas',
                    'keterangan' => 'Transfer total pendapatan ke income summary',
                    'debit' => 0,
                    'kredit' => $closingData['total_income']
                ];
                $totalKredit += $closingData['total_income'];
            }

            // 2. Close expense accounts (Credit Expense, Debit Income Summary)
            foreach ($closingData['expense_accounts'] as $account) {
                if ($account['balance'] > 0) {
                    $entries[] = [
                        'akun_code' => $account['kode'],
                        'akun_name' => $account['nama'],
                        'akun_jenis' => 'Beban',
                        'keterangan' => 'Penutupan akun beban: ' . $account['nama'],
                        'debit' => 0,
                        'kredit' => $account['balance']
                    ];
                    $totalKredit += $account['balance'];
                }
            }

            // Debit Income Summary with total expenses
            if ($closingData['total_expenses'] > 0) {
                $entries[] = [
                    'akun_code' => $incomeSummaryAccount->kode,
                    'akun_name' => $incomeSummaryAccount->nama,
                    'akun_jenis' => 'Ekuitas',
                    'keterangan' => 'Transfer total beban ke income summary',
                    'debit' => $closingData['total_expenses'],
                    'kredit' => 0
                ];
                $totalDebit += $closingData['total_expenses'];
            }

            // 3. Close Income Summary to Retained Earnings
            $netIncome = $closingData['total_income'] - $closingData['total_expenses'];
            if ($netIncome != 0) {
                if ($netIncome > 0) {
                    // Profit: Debit Income Summary, Credit Retained Earnings
                    $entries[] = [
                        'akun_code' => $incomeSummaryAccount->kode,
                        'akun_name' => $incomeSummaryAccount->nama,
                        'akun_jenis' => 'Ekuitas',
                        'keterangan' => 'Transfer laba bersih ke laba ditahan',
                        'debit' => $netIncome,
                        'kredit' => 0
                    ];
                    $totalDebit += $netIncome;

                    $entries[] = [
                        'akun_code' => $retainedEarningsAccount->kode,
                        'akun_name' => $retainedEarningsAccount->nama,
                        'akun_jenis' => 'Ekuitas',
                        'keterangan' => 'Penerimaan laba bersih',
                        'debit' => 0,
                        'kredit' => $netIncome
                    ];
                    $totalKredit += $netIncome;
                } else {
                    // Loss: Credit Income Summary, Debit Retained Earnings
                    $netLoss = abs($netIncome);

                    $entries[] = [
                        'akun_code' => $incomeSummaryAccount->kode,
                        'akun_name' => $incomeSummaryAccount->nama,
                        'akun_jenis' => 'Ekuitas',
                        'keterangan' => 'Transfer rugi bersih ke laba ditahan',
                        'debit' => 0,
                        'kredit' => $netLoss
                    ];
                    $totalKredit += $netLoss;

                    $entries[] = [
                        'akun_code' => $retainedEarningsAccount->kode,
                        'akun_name' => $retainedEarningsAccount->nama,
                        'akun_jenis' => 'Ekuitas',
                        'keterangan' => 'Pengurangan akibat rugi bersih',
                        'debit' => $netLoss,
                        'kredit' => 0
                    ];
                    $totalDebit += $netLoss;
                }
            }

            return response()->json([
                'success' => true,
                'entries' => $entries,
                'total_debit' => $totalDebit,
                'total_kredit' => $totalKredit,
                'summary' => [
                    'total_pendapatan' => $closingData['total_income'],
                    'total_beban' => $closingData['total_expenses'],
                    'net_income' => $netIncome
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating preview: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get auto closing preview data via AJAX
     */
    public function getAutoClosingPreviewData(Request $request)
    {
        $periode_id = $request->get('periode_id');

        if (!$periode_id) {
            return response()->json([
                'success' => false,
                'message' => 'Periode ID is required'
            ], 400);
        }

        $periode = PeriodeAkuntansi::find($periode_id);

        if (!$periode) {
            return response()->json([
                'success' => false,
                'message' => 'Periode not found'
            ], 404);
        }

        return $this->getAutoClosingPreviewDataPrivate($periode);
    }
}
