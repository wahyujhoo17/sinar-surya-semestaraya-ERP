<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\JurnalUmum;
use App\Models\AkunAkuntansi;
use App\Models\PeriodeAkuntansi;
use App\Models\Kas;
use App\Models\RekeningBank;
use App\Models\TransaksiKas;
use App\Models\TransaksiBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class JurnalPenyesuaianController extends Controller
{
    public function index(Request $request)
    {
        // Get sort parameters
        $sortField = $request->get('sort', 'tanggal');
        $sortDirection = $request->get('direction', 'desc');

        // Validate sort field
        $allowedSortFields = ['tanggal', 'no_referensi', 'keterangan'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'tanggal';
        }

        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        // Mendapatkan semua data jurnal penyesuaian yang diurutkan berdasarkan tanggal terbaru
        $query = JurnalUmum::with(['akun', 'user', 'periode'])
            ->where('jenis_jurnal', 'penyesuaian');

        // Filter berdasarkan periode
        if ($request->has('periode_id') && $request->periode_id) {
            $query->where('periode_id', $request->periode_id);
        }

        // Filter berdasarkan tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        // Filter berdasarkan akun
        if ($request->has('akun_id') && $request->akun_id) {
            $query->where('akun_id', $request->akun_id);
        }

        // Filter berdasarkan nomor referensi
        if ($request->has('no_referensi') && $request->no_referensi) {
            $query->where('no_referensi', 'like', '%' . $request->no_referensi . '%');
        }

        // Filter berdasarkan status posting
        if ($request->has('status_posting') && $request->status_posting !== '') {
            if ($request->status_posting === 'posted') {
                $query->where('is_posted', true);
            } elseif ($request->status_posting === 'draft') {
                $query->where('is_posted', false);
            }
        }

        // Get unique journal transactions by grouping by no_referensi and tanggal only
        $groupedQuery = $query->select('tanggal', 'no_referensi')
            ->selectRaw('MIN(id) as min_id')
            ->selectRaw('MAX(keterangan) as keterangan')  // Take any keterangan from the group
            ->selectRaw('MAX(is_posted) as is_posted')  // Take posting status
            ->groupBy('tanggal', 'no_referensi');

        // Apply sorting to the grouped query
        if ($sortField === 'tanggal') {
            $groupedQuery->orderBy('tanggal', $sortDirection)->orderBy('min_id', $sortDirection);
        } elseif ($sortField === 'no_referensi') {
            $groupedQuery->orderBy('no_referensi', $sortDirection)->orderBy('tanggal', 'desc');
        } elseif ($sortField === 'keterangan') {
            $groupedQuery->orderBy('keterangan', $sortDirection)->orderBy('tanggal', 'desc');
        }

        // Paginate the grouped results
        $distinctJournals = $groupedQuery->paginate(15);

        // Now get the full jurnal data for each group
        $jurnals = collect();
        foreach ($distinctJournals as $distinctJournal) {
            $fullJournal = JurnalUmum::with(['akun', 'user'])
                ->where('tanggal', $distinctJournal->tanggal)
                ->where('no_referensi', $distinctJournal->no_referensi)
                ->where('jenis_jurnal', 'penyesuaian')
                ->first();
            if ($fullJournal) {
                // Add the grouped keterangan and posting status to the journal object
                $fullJournal->keterangan = $distinctJournal->keterangan;
                $fullJournal->is_posted = $distinctJournal->is_posted;

                // Calculate jumlah entri and total
                $entries = JurnalUmum::where('tanggal', $distinctJournal->tanggal)
                    ->where('no_referensi', $distinctJournal->no_referensi)
                    ->where('jenis_jurnal', 'penyesuaian')
                    ->get();

                $fullJournal->jumlah_entri = $entries->count();
                $fullJournal->total_debit = $entries->sum('debit');

                $jurnals->push($fullJournal);
            }
        }

        // Convert the collection to a paginator to maintain pagination functionality
        $jurnals = new \Illuminate\Pagination\LengthAwarePaginator(
            $jurnals,
            $distinctJournals->total(),
            $distinctJournals->perPage(),
            $distinctJournals->currentPage(),
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );
        $jurnals->appends(request()->query());

        $akuns = AkunAkuntansi::where('is_active', 1)
            ->orderBy('kode', 'asc')
            ->get();

        $periodes = PeriodeAkuntansi::orderBy('tanggal_mulai', 'desc')->get();

        // Handle AJAX requests - only respond with JSON if it's a genuine AJAX request
        // Check for X-Requested-With header AND Accept header to ensure it's a real AJAX call
        if ($request->ajax() && $request->wantsJson()) {
            $tableHtml = view('keuangan.jurnal_penyesuaian._table', compact('jurnals'))->render();

            // Always render pagination container with showing-info, even without pagination links
            $paginationHtml = view('keuangan.jurnal_penyesuaian._pagination', compact('jurnals'))->render();

            return response()->json([
                'table_html' => $tableHtml,
                'pagination_html' => $paginationHtml,
                'total' => $jurnals->total(),
                'first_item' => $jurnals->firstItem() ?? 0,
                'last_item' => $jurnals->lastItem() ?? 0,
            ]);
        }

        // Set breadcrumbs
        $breadcrumbs = [
            ['url' => route('dashboard'), 'label' => 'Dashboard'],
            ['url' => '#', 'label' => 'Keuangan'],
            ['url' => null, 'label' => 'Jurnal Penyesuaian'],
        ];

        $currentPage = 'jurnal-penyesuaian';

        return view('keuangan.jurnal_penyesuaian.index', compact('jurnals', 'akuns', 'periodes', 'breadcrumbs', 'currentPage', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        $akuns = AkunAkuntansi::orderBy('kode')->get();
        $periodes = PeriodeAkuntansi::orderBy('tanggal_mulai', 'desc')->get();
        $nextRefNumber = $this->generateNextReferenceNumber();

        $breadcrumbs = [
            ['label' => 'Keuangan', 'url' => '#'],
            ['label' => 'Jurnal Penyesuaian', 'url' => route('keuangan.jurnal-penyesuaian.index')],
            ['label' => 'Buat Baru']
        ];

        return view('keuangan.jurnal_penyesuaian.create', compact(
            'akuns',
            'periodes',
            'nextRefNumber',
            'breadcrumbs'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'no_referensi' => 'required|string|max:50|unique:jurnal_umum,no_referensi',
            'keterangan' => 'required|string|max:255',
            'entries' => 'required|array|min:2',
            'entries.*.akun_id' => 'required|exists:akun_akuntansi,id',
            'entries.*.debit' => 'required|numeric|min:0',
            'entries.*.kredit' => 'required|numeric|min:0',
            'entries.*.keterangan' => 'nullable|string|max:255',
        ], [
            'entries.min' => 'Minimal harus ada 2 entri jurnal',
            'no_referensi.unique' => 'Nomor referensi sudah digunakan',
        ]);

        // Validasi balance debit dan kredit
        $totalDebit = collect($request->entries)->sum('debit');
        $totalKredit = collect($request->entries)->sum('kredit');

        if ($totalDebit != $totalKredit) {
            return back()->withErrors([
                'balance' => 'Total debit (' . number_format($totalDebit) . ') tidak sama dengan total kredit (' . number_format($totalKredit) . ')'
            ])->withInput();
        }

        // Validasi tidak boleh ada entri dengan debit dan kredit keduanya 0 atau keduanya terisi
        foreach ($request->entries as $index => $entry) {
            if (($entry['debit'] > 0 && $entry['kredit'] > 0) ||
                ($entry['debit'] == 0 && $entry['kredit'] == 0)
            ) {
                return back()->withErrors([
                    "entries.{$index}" => 'Setiap entri harus memiliki nilai debit atau kredit saja, tidak boleh keduanya atau tidak ada sama sekali'
                ])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            // Simpan jurnal entries TANPA mengubah saldo (akan diubah saat posting)
            foreach ($request->entries as $entry) {
                $jurnalEntry = JurnalUmum::create([
                    'tanggal' => $request->tanggal,
                    'no_referensi' => $request->no_referensi,
                    'akun_id' => $entry['akun_id'],
                    'debit' => $entry['debit'],
                    'kredit' => $entry['kredit'],
                    'keterangan' => $entry['keterangan'] ?: $request->keterangan,
                    'user_id' => Auth::id(),
                    'jenis_jurnal' => 'penyesuaian',
                    'ref_type' => 'manual_adjustment',
                    'ref_id' => null,
                    'is_posted' => false, // Jurnal penyesuaian manual dimulai sebagai draft
                ]);
            }

            DB::commit();

            return redirect()->route('keuangan.jurnal-penyesuaian.index')
                ->with('success', 'Jurnal penyesuaian berhasil dibuat sebagai draft. Post jurnal untuk menerapkan perubahan saldo.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating jurnal penyesuaian: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal menyimpan jurnal: ' . $e->getMessage()])->withInput();
        }
    }

    public function show($no_referensi)
    {
        $entries = JurnalUmum::with('akun', 'user')
            ->where('no_referensi', $no_referensi)
            ->where('jenis_jurnal', 'penyesuaian')
            ->orderBy('id')
            ->get();

        if ($entries->isEmpty()) {
            abort(404, 'Jurnal penyesuaian tidak ditemukan');
        }

        $totalDebit = $entries->sum('debit');
        $totalKredit = $entries->sum('kredit');

        $breadcrumbs = [
            ['label' => 'Keuangan', 'url' => '#'],
            ['label' => 'Jurnal Penyesuaian', 'url' => route('keuangan.jurnal-penyesuaian.index')],
            ['label' => 'Detail']
        ];

        return view('keuangan.jurnal_penyesuaian.show', compact(
            'entries',
            'totalDebit',
            'totalKredit',
            'breadcrumbs'
        ));
    }

    public function edit($no_referensi)
    {
        $entries = JurnalUmum::with('akun')
            ->where('no_referensi', $no_referensi)
            ->where('jenis_jurnal', 'penyesuaian')
            ->orderBy('id')
            ->get();

        if ($entries->isEmpty()) {
            abort(404, 'Jurnal penyesuaian tidak ditemukan');
        }

        // Simulasi objek jurnal untuk keperluan edit form
        $jurnal = (object) [
            'tanggal_jurnal' => $entries->first()->tanggal,
            'no_referensi' => $entries->first()->no_referensi,
            'keterangan' => $entries->first()->keterangan,
            'details' => $entries
        ];

        $akunList = AkunAkuntansi::orderBy('kode')->get();

        $breadcrumbs = [
            ['label' => 'Keuangan', 'url' => '#'],
            ['label' => 'Jurnal Penyesuaian', 'url' => route('keuangan.jurnal-penyesuaian.index')],
            ['label' => 'Edit']
        ];

        return view('keuangan.jurnal_penyesuaian.edit', compact(
            'jurnal',
            'akunList',
            'breadcrumbs'
        ));
    }

    public function update(Request $request, $no_referensi)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'entries' => 'required|array|min:2',
            'entries.*.akun_id' => 'required|exists:akun_akuntansi,id',
            'entries.*.debit' => 'required|numeric|min:0',
            'entries.*.kredit' => 'required|numeric|min:0',
            'entries.*.keterangan' => 'nullable|string|max:255',
        ]);

        // Validasi balance
        $totalDebit = collect($request->entries)->sum('debit');
        $totalKredit = collect($request->entries)->sum('kredit');

        if ($totalDebit != $totalKredit) {
            return back()->withErrors([
                'balance' => 'Total debit (' . number_format($totalDebit) . ') tidak sama dengan total kredit (' . number_format($totalKredit) . ')'
            ])->withInput();
        }

        DB::beginTransaction();
        try {
            // Hapus entri lama
            JurnalUmum::where('no_referensi', $no_referensi)
                ->where('jenis_jurnal', 'penyesuaian')
                ->delete();

            // Buat entri baru
            foreach ($request->entries as $entry) {
                JurnalUmum::create([
                    'tanggal' => $request->tanggal,
                    'no_referensi' => $no_referensi,
                    'akun_id' => $entry['akun_id'],
                    'debit' => $entry['debit'],
                    'kredit' => $entry['kredit'],
                    'keterangan' => $entry['keterangan'] ?: $request->keterangan,
                    'user_id' => Auth::id(),
                    'jenis_jurnal' => 'penyesuaian',
                    'ref_type' => 'manual_adjustment',
                    'ref_id' => null,
                    'is_posted' => false, // Jurnal penyesuaian manual dimulai sebagai draft
                ]);
            }

            DB::commit();

            return redirect()->route('keuangan.jurnal-penyesuaian.index')
                ->with('success', 'Jurnal penyesuaian berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui jurnal: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($no_referensi)
    {
        try {
            DB::beginTransaction();

            // Dapatkan jurnal yang akan dihapus
            $entries = JurnalUmum::where('jenis_jurnal', 'penyesuaian')
                ->where('no_referensi', $no_referensi)
                ->get();

            if ($entries->isEmpty()) {
                throw new \Exception('Jurnal penyesuaian tidak ditemukan');
            }

            $jurnal = $entries->first();
            $tanggal = $jurnal->tanggal;

            // Cek apakah jurnal sudah diposting, jika ya maka unpost dulu
            if ($jurnal->is_posted) {
                // Unpost jurnal terlebih dahulu untuk membalik saldo
                $this->unpostJournal($no_referensi);
            }

            // Hapus transaksi kas dan bank terkait dengan jurnal ini
            TransaksiKas::where('related_type', 'App\Models\JurnalPenyesuaian')
                ->where(function ($query) use ($no_referensi, $tanggal) {
                    $query->where('no_bukti', $no_referensi);
                })->delete();

            TransaksiBank::where('related_type', 'App\Models\JurnalPenyesuaian')
                ->where(function ($query) use ($no_referensi, $tanggal) {
                    $query->where('no_referensi', $no_referensi);
                })->delete();

            // Hapus semua entri jurnal terkait
            JurnalUmum::where('no_referensi', $no_referensi)
                ->where('tanggal', $tanggal)
                ->where('jenis_jurnal', 'penyesuaian')
                ->delete();

            DB::commit();

            return redirect()->route('keuangan.jurnal-penyesuaian.index')
                ->with('success', 'Jurnal penyesuaian berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting jurnal penyesuaian: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper method to unpost journal without returning response
     */
    private function unpostJournal($noReferensi)
    {
        // Get all entries for this reference
        $entries = JurnalUmum::where('no_referensi', $noReferensi)
            ->where('jenis_jurnal', 'penyesuaian')
            ->get();

        if ($entries->isEmpty()) {
            return;
        }

        // Arrays untuk menyimpan perubahan saldo (reverse)
        $kasToUpdate = [];
        $rekeningToUpdate = [];

        // Process each entry to calculate balance changes (reverse)
        foreach ($entries as $entry) {
            // Periksa apakah akun ini terkait dengan Kas atau RekeningBank
            $akun = AkunAkuntansi::find($entry->akun_id);

            if ($akun && $akun->ref_type) {
                $debit = (float)$entry->debit;
                $kredit = (float)$entry->kredit;

                if ($akun->ref_type === 'App\Models\Kas') {
                    // Reverse logic: subtract what was previously added
                    $nilaiPerubahan = $kredit - $debit; // Opposite of what was done during posting

                    if (!isset($kasToUpdate[$akun->ref_id])) {
                        $kasToUpdate[$akun->ref_id] = 0;
                    }
                    $kasToUpdate[$akun->ref_id] += $nilaiPerubahan;
                } elseif ($akun->ref_type === 'App\Models\RekeningBank') {
                    // Reverse logic: subtract what was previously added
                    $nilaiPerubahan = $kredit - $debit; // Opposite of what was done during posting

                    if (!isset($rekeningToUpdate[$akun->ref_id])) {
                        $rekeningToUpdate[$akun->ref_id] = 0;
                    }
                    $rekeningToUpdate[$akun->ref_id] += $nilaiPerubahan;
                }
            }
        }

        // Update saldo kas (reverse)
        foreach ($kasToUpdate as $kasId => $nilaiPerubahan) {
            $kas = Kas::find($kasId);
            if ($kas) {
                Log::info('Destroy Unpost JurnalPenyesuaian - Kas - ID: ' . $kasId . ', Saldo Sebelum: ' . $kas->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                $kas->saldo += $nilaiPerubahan;
                $kas->save();

                Log::info('Destroy Unpost JurnalPenyesuaian - Kas - ID: ' . $kasId . ', Saldo Setelah: ' . $kas->saldo);
            }
        }

        // Update saldo rekening bank (reverse)
        foreach ($rekeningToUpdate as $rekeningId => $nilaiPerubahan) {
            $rekening = RekeningBank::find($rekeningId);
            if ($rekening) {
                Log::info('Destroy Unpost JurnalPenyesuaian - RekeningBank - ID: ' . $rekeningId . ', Saldo Sebelum: ' . $rekening->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                $rekening->saldo += $nilaiPerubahan;
                $rekening->save();

                Log::info('Destroy Unpost JurnalPenyesuaian - RekeningBank - ID: ' . $rekeningId . ', Saldo Setelah: ' . $rekening->saldo);
            }
        }
    }

    private function generateNextReferenceNumber()
    {
        $currentYear = date('Y');
        $currentMonth = date('m');

        $lastNumber = JurnalUmum::where('jenis_jurnal', 'penyesuaian')
            ->where('no_referensi', 'like', "ADJ-{$currentYear}{$currentMonth}%")
            ->max('no_referensi');

        if ($lastNumber) {
            $lastSequence = (int) substr($lastNumber, -4);
            $newSequence = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSequence = '0001';
        }

        return "ADJ-{$currentYear}{$currentMonth}{$newSequence}";
    }

    /**
     * Post the specified journal entries
     */
    public function post(Request $request)
    {
        $noReferensi = $request->input('no_referensi');

        if (!$noReferensi) {
            return redirect()->back()->withErrors(['error' => 'Nomor referensi tidak ditemukan']);
        }

        DB::beginTransaction();
        try {
            // Get all entries for this reference
            $entries = JurnalUmum::where('no_referensi', $noReferensi)
                ->where('jenis_jurnal', 'penyesuaian')
                ->get();

            if ($entries->isEmpty()) {
                return redirect()->back()->withErrors(['error' => 'Jurnal penyesuaian tidak ditemukan']);
            }

            // Check if already posted
            if ($entries->first()->is_posted) {
                return redirect()->back()->withErrors(['error' => 'Jurnal penyesuaian sudah diposting sebelumnya']);
            }

            // Arrays untuk menyimpan perubahan saldo
            $kasToUpdate = [];
            $rekeningToUpdate = [];

            // Process each entry to calculate balance changes
            foreach ($entries as $entry) {
                // Periksa apakah akun ini terkait dengan Kas atau RekeningBank
                $akun = AkunAkuntansi::find($entry->akun_id);

                if ($akun && $akun->ref_type) {
                    $debit = (float)$entry->debit;
                    $kredit = (float)$entry->kredit;

                    if ($akun->ref_type === 'App\Models\Kas') {
                        // Untuk akun Kas: debit menambah saldo, kredit mengurangi saldo
                        $nilaiPerubahan = $debit - $kredit;

                        if (!isset($kasToUpdate[$akun->ref_id])) {
                            $kasToUpdate[$akun->ref_id] = 0;
                        }
                        $kasToUpdate[$akun->ref_id] += $nilaiPerubahan;
                    } elseif ($akun->ref_type === 'App\Models\RekeningBank') {
                        // Untuk akun Rekening Bank: debit menambah saldo, kredit mengurangi saldo
                        $nilaiPerubahan = $debit - $kredit;

                        if (!isset($rekeningToUpdate[$akun->ref_id])) {
                            $rekeningToUpdate[$akun->ref_id] = 0;
                        }
                        $rekeningToUpdate[$akun->ref_id] += $nilaiPerubahan;
                    }
                }
            }

            // Update saldo kas dan buat transaksi kas
            foreach ($kasToUpdate as $kasId => $nilaiPerubahan) {
                $kas = Kas::find($kasId);
                if ($kas && $nilaiPerubahan != 0) {
                    Log::info('Post JurnalPenyesuaian - Kas - ID: ' . $kasId . ', Saldo Sebelum: ' . $kas->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                    $kas->saldo += $nilaiPerubahan;
                    $kas->save();

                    Log::info('Post JurnalPenyesuaian - Kas - ID: ' . $kasId . ', Saldo Setelah: ' . $kas->saldo);

                    // Buat transaksi kas untuk mencatat perubahan
                    $firstEntry = $entries->first();
                    TransaksiKas::create([
                        'tanggal' => $firstEntry->tanggal,
                        'kas_id' => $kasId,
                        'jenis' => $nilaiPerubahan > 0 ? 'masuk' : 'keluar',
                        'jumlah' => abs($nilaiPerubahan),
                        'keterangan' => 'Jurnal Penyesuaian: ' . $firstEntry->keterangan,
                        'no_bukti' => $firstEntry->no_referensi,
                        'related_id' => null,
                        'related_type' => 'App\Models\JurnalPenyesuaian',
                        'user_id' => Auth::id()
                    ]);
                }
            }

            // Update saldo rekening bank dan buat transaksi bank
            foreach ($rekeningToUpdate as $rekeningId => $nilaiPerubahan) {
                $rekening = RekeningBank::find($rekeningId);
                if ($rekening && $nilaiPerubahan != 0) {
                    Log::info('Post JurnalPenyesuaian - RekeningBank - ID: ' . $rekeningId . ', Saldo Sebelum: ' . $rekening->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                    $rekening->saldo += $nilaiPerubahan;
                    $rekening->save();

                    Log::info('Post JurnalPenyesuaian - RekeningBank - ID: ' . $rekeningId . ', Saldo Setelah: ' . $rekening->saldo);

                    // Buat transaksi bank untuk mencatat perubahan
                    $firstEntry = $entries->first();
                    TransaksiBank::create([
                        'tanggal' => $firstEntry->tanggal,
                        'rekening_id' => $rekeningId,
                        'jenis' => $nilaiPerubahan > 0 ? 'masuk' : 'keluar',
                        'jumlah' => abs($nilaiPerubahan),
                        'keterangan' => 'Jurnal Penyesuaian: ' . $firstEntry->keterangan,
                        'no_referensi' => $firstEntry->no_referensi,
                        'related_id' => null,
                        'related_type' => 'App\Models\JurnalPenyesuaian',
                        'user_id' => Auth::id()
                    ]);
                }
            }

            // Post all entries with this reference
            JurnalUmum::where('no_referensi', $noReferensi)
                ->where('jenis_jurnal', 'penyesuaian')
                ->update([
                    'is_posted' => true,
                    'posted_at' => now(),
                    'posted_by' => Auth::id()
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'Jurnal penyesuaian berhasil diposting dan saldo kas/bank telah diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error posting journal penyesuaian: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Gagal memposting jurnal: ' . $e->getMessage()]);
        }
    }

    /**
     * Unpost the specified journal entries
     */
    public function unpost(Request $request)
    {
        $noReferensi = $request->input('no_referensi');

        if (!$noReferensi) {
            return redirect()->back()->withErrors(['error' => 'Nomor referensi tidak ditemukan']);
        }

        DB::beginTransaction();
        try {
            // Get all entries for this reference
            $entries = JurnalUmum::where('no_referensi', $noReferensi)
                ->where('jenis_jurnal', 'penyesuaian')
                ->get();

            if ($entries->isEmpty()) {
                return redirect()->back()->withErrors(['error' => 'Jurnal penyesuaian tidak ditemukan']);
            }

            // Check if not posted
            if (!$entries->first()->is_posted) {
                return redirect()->back()->withErrors(['error' => 'Jurnal penyesuaian belum diposting']);
            }

            // Arrays untuk menyimpan perubahan saldo (reverse)
            $kasToUpdate = [];
            $rekeningToUpdate = [];

            // Process each entry to calculate balance changes (reverse)
            foreach ($entries as $entry) {
                // Periksa apakah akun ini terkait dengan Kas atau RekeningBank
                $akun = AkunAkuntansi::find($entry->akun_id);

                if ($akun && $akun->ref_type) {
                    $debit = (float)$entry->debit;
                    $kredit = (float)$entry->kredit;

                    if ($akun->ref_type === 'App\Models\Kas') {
                        // Reverse logic: subtract what was previously added
                        $nilaiPerubahan = $kredit - $debit; // Opposite of what was done during posting

                        if (!isset($kasToUpdate[$akun->ref_id])) {
                            $kasToUpdate[$akun->ref_id] = 0;
                        }
                        $kasToUpdate[$akun->ref_id] += $nilaiPerubahan;
                    } elseif ($akun->ref_type === 'App\Models\RekeningBank') {
                        // Reverse logic: subtract what was previously added
                        $nilaiPerubahan = $kredit - $debit; // Opposite of what was done during posting

                        if (!isset($rekeningToUpdate[$akun->ref_id])) {
                            $rekeningToUpdate[$akun->ref_id] = 0;
                        }
                        $rekeningToUpdate[$akun->ref_id] += $nilaiPerubahan;
                    }
                }
            }

            // Update saldo kas (reverse)
            foreach ($kasToUpdate as $kasId => $nilaiPerubahan) {
                $kas = Kas::find($kasId);
                if ($kas) {
                    Log::info('Unpost JurnalPenyesuaian - Kas - ID: ' . $kasId . ', Saldo Sebelum: ' . $kas->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                    $kas->saldo += $nilaiPerubahan;
                    $kas->save();

                    Log::info('Unpost JurnalPenyesuaian - Kas - ID: ' . $kasId . ', Saldo Setelah: ' . $kas->saldo);
                }
            }

            // Update saldo rekening bank (reverse)
            foreach ($rekeningToUpdate as $rekeningId => $nilaiPerubahan) {
                $rekening = RekeningBank::find($rekeningId);
                if ($rekening) {
                    Log::info('Unpost JurnalPenyesuaian - RekeningBank - ID: ' . $rekeningId . ', Saldo Sebelum: ' . $rekening->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                    $rekening->saldo += $nilaiPerubahan;
                    $rekening->save();

                    Log::info('Unpost JurnalPenyesuaian - RekeningBank - ID: ' . $rekeningId . ', Saldo Setelah: ' . $rekening->saldo);
                }
            }

            // Delete related transactions
            TransaksiKas::where('no_bukti', $noReferensi)
                ->where('related_type', 'App\Models\JurnalPenyesuaian')
                ->delete();

            TransaksiBank::where('no_referensi', $noReferensi)
                ->where('related_type', 'App\Models\JurnalPenyesuaian')
                ->delete();

            // Unpost all entries with this reference
            JurnalUmum::where('no_referensi', $noReferensi)
                ->where('jenis_jurnal', 'penyesuaian')
                ->update([
                    'is_posted' => false,
                    'posted_at' => null,
                    'posted_by' => null
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'Jurnal penyesuaian berhasil dibatalkan postingnya dan saldo kas/bank telah dikembalikan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error unposting journal penyesuaian: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Gagal membatalkan posting jurnal: ' . $e->getMessage()]);
        }
    }
}
