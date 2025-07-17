<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\AkunAkuntansi;
use App\Models\JurnalUmum;
use App\Models\Kas;
use App\Models\PeriodeAkuntansi;
use App\Models\RekeningBank;
use App\Models\TransaksiKas;
use App\Models\TransaksiBank;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class JurnalUmumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        // Mendapatkan semua data jurnal umum yang diurutkan berdasarkan tanggal terbaru
        $query = JurnalUmum::with(['akun', 'user', 'periode'])
            ->where('jenis_jurnal', 'umum');

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
                ->first();
            if ($fullJournal) {
                // Add the grouped keterangan to the journal object
                $fullJournal->keterangan = $distinctJournal->keterangan;
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
            $tableHtml = view('keuangan.jurnal_umum._table', compact('jurnals'))->render();
            $paginationHtml = $jurnals->appends(request()->query())->links('vendor.pagination.tailwind')->toHtml();

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
            ['url' => null, 'label' => 'Jurnal Umum'],
        ];

        $currentPage = 'jurnal-umum';

        return view('keuangan.jurnal_umum.index', compact('jurnals', 'akuns', 'periodes', 'breadcrumbs', 'currentPage', 'sortField', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $akuns = AkunAkuntansi::where('is_active', 1)
            ->orderBy('kode', 'asc')
            ->get();

        $periodes = PeriodeAkuntansi::orderBy('tanggal_mulai', 'desc')->get();

        $breadcrumbs = [
            ['url' => route('dashboard'), 'label' => 'Dashboard'],
            ['url' => '#', 'label' => 'Keuangan'],
            ['url' => route('keuangan.jurnal-umum.index'), 'label' => 'Jurnal Umum'],
            ['url' => null, 'label' => 'Tambah Jurnal'],
        ];

        $currentPage = 'jurnal-umum';

        return view('keuangan.jurnal_umum.create', compact('akuns', 'periodes', 'breadcrumbs', 'currentPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'no_referensi' => 'required|string|max:50',
            'keterangan' => 'required|string|max:255',
            'jurnal_items' => 'required|array|min:2',
            'jurnal_items.*.akun_id' => 'required|exists:akun_akuntansi,id',
            'jurnal_items.*.debit' => 'nullable|numeric|min:0',
            'jurnal_items.*.kredit' => 'nullable|numeric|min:0',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validasi: dalam satu baris harus ada debit atau kredit, tidak boleh kosong keduanya atau diisi keduanya
        foreach ($request->jurnal_items as $index => $item) {
            $debit = isset($item['debit']) && is_numeric($item['debit']) ? floatval($item['debit']) : 0;
            $kredit = isset($item['kredit']) && is_numeric($item['kredit']) ? floatval($item['kredit']) : 0;

            if (($debit <= 0 && $kredit <= 0) || ($debit > 0 && $kredit > 0)) {
                return redirect()->back()
                    ->withErrors(['jurnal_items.' . $index => 'Setiap baris harus diisi salah satu: debit atau kredit (tidak boleh kosong keduanya atau diisi keduanya).'])
                    ->withInput();
            }
        }

        // Validasi total debit dan kredit harus sama
        $totalDebit = 0;
        $totalKredit = 0;

        foreach ($request->jurnal_items as $item) {
            $totalDebit += (float) ($item['debit'] ?? 0);
            $totalKredit += (float) ($item['kredit'] ?? 0);
        }

        if (abs($totalDebit - $totalKredit) > 0.01) { // Menggunakan toleransi 0.01 untuk mengatasi floating point errors
            return redirect()->back()
                ->withErrors(['jurnal_items' => 'Total debit dan kredit harus sama.'])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Simpan semua item jurnal TANPA mengubah saldo (akan diubah saat posting)
            foreach ($request->jurnal_items as $item) {
                // Buat entri jurnal
                $jurnal = JurnalUmum::create([
                    'tanggal' => $request->tanggal,
                    'no_referensi' => $request->no_referensi,
                    'keterangan' => $request->keterangan,
                    'akun_id' => $item['akun_id'],
                    'debit' => $item['debit'] ?? 0,
                    'kredit' => $item['kredit'] ?? 0,
                    'jenis_jurnal' => 'umum',
                    'user_id' => Auth::id(),
                    'is_posted' => false, // Jurnal manual dimulai sebagai draft
                ]);
            }

            DB::commit();

            return redirect()->route('keuangan.jurnal-umum.index')
                ->with('success', 'Jurnal berhasil disimpan sebagai draft. Post jurnal untuk menerapkan perubahan saldo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Karena jurnal biasanya berisi banyak entri dengan no_referensi yang sama,
        // kita akan menampilkan semua entri dengan no_referensi yang sama
        $jurnal = JurnalUmum::where('jenis_jurnal', 'umum')->findOrFail($id);
        $relatedJurnals = JurnalUmum::where('no_referensi', $jurnal->no_referensi)
            ->where('tanggal', $jurnal->tanggal)
            ->where('jenis_jurnal', 'umum')
            ->with(['akun', 'user'])
            ->get();

        $breadcrumbs = [
            ['url' => route('dashboard'), 'label' => 'Dashboard'],
            ['url' => '#', 'label' => 'Keuangan'],
            ['url' => route('keuangan.jurnal-umum.index'), 'label' => 'Jurnal Umum'],
            ['url' => null, 'label' => 'Detail Jurnal'],
        ];

        $currentPage = 'jurnal-umum';

        return view('keuangan.jurnal_umum.show', compact('jurnal', 'relatedJurnals', 'breadcrumbs', 'currentPage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Mendapatkan jurnal yang akan diedit dan semua entri terkait
        $jurnal = JurnalUmum::where('jenis_jurnal', 'umum')->findOrFail($id);
        $jurnalItems = JurnalUmum::where('no_referensi', $jurnal->no_referensi)
            ->where('tanggal', $jurnal->tanggal)
            ->where('jenis_jurnal', 'umum')
            ->with('akun')
            ->get();

        $akuns = AkunAkuntansi::where('is_active', 1)
            ->orderBy('kode', 'asc')
            ->get();

        $breadcrumbs = [
            ['url' => route('dashboard'), 'label' => 'Dashboard'],
            ['url' => '#', 'label' => 'Keuangan'],
            ['url' => route('keuangan.jurnal-umum.index'), 'label' => 'Jurnal Umum'],
            ['url' => null, 'label' => 'Edit Jurnal'],
        ];

        $currentPage = 'jurnal-umum';

        return view('keuangan.jurnal_umum.edit', compact('jurnal', 'jurnalItems', 'akuns', 'breadcrumbs', 'currentPage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'no_referensi' => 'required|string|max:50',
            'keterangan' => 'required|string|max:255',
            'jurnal_items' => 'required|array|min:2',
            'jurnal_items.*.id' => 'nullable|exists:jurnal_umum,id',
            'jurnal_items.*.akun_id' => 'required|exists:akun_akuntansi,id',
            'jurnal_items.*.debit' => 'nullable|numeric|min:0',
            'jurnal_items.*.kredit' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validasi: dalam satu baris harus ada debit atau kredit, tidak boleh kosong keduanya atau diisi keduanya
        foreach ($request->jurnal_items as $index => $item) {
            $debit = isset($item['debit']) && is_numeric($item['debit']) ? floatval($item['debit']) : 0;
            $kredit = isset($item['kredit']) && is_numeric($item['kredit']) ? floatval($item['kredit']) : 0;

            if (($debit <= 0 && $kredit <= 0) || ($debit > 0 && $kredit > 0)) {
                return redirect()->back()
                    ->withErrors(['jurnal_items.' . $index => 'Setiap baris harus diisi salah satu: debit atau kredit (tidak boleh kosong keduanya atau diisi keduanya).'])
                    ->withInput();
            }
        }

        // Validasi total debit dan kredit harus sama
        $totalDebit = 0;
        $totalKredit = 0;

        foreach ($request->jurnal_items as $item) {
            $totalDebit += (float) ($item['debit'] ?? 0);
            $totalKredit += (float) ($item['kredit'] ?? 0);
        }

        if (abs($totalDebit - $totalKredit) > 0.01) {
            return redirect()->back()
                ->withErrors(['jurnal_items' => 'Total debit dan kredit harus sama.'])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Dapatkan jurnal yang diedit (hanya jurnal umum)
            $jurnal = JurnalUmum::where('jenis_jurnal', 'umum')->findOrFail($id);
            $oldReferensi = $jurnal->no_referensi;
            $oldTanggal = $jurnal->tanggal;

            // Hapus transaksi kas dan bank terkait dengan jurnal ini
            TransaksiKas::where('related_type', JurnalUmum::class)
                ->where(function ($query) use ($oldReferensi, $oldTanggal) {
                    $query->whereIn('related_id', function ($subquery) use ($oldReferensi, $oldTanggal) {
                        $subquery->select('id')
                            ->from('jurnal_umum')
                            ->where('no_referensi', $oldReferensi)
                            ->where('tanggal', $oldTanggal);
                    });
                })->delete();

            TransaksiBank::where('related_type', JurnalUmum::class)
                ->where(function ($query) use ($oldReferensi, $oldTanggal) {
                    $query->whereIn('related_id', function ($subquery) use ($oldReferensi, $oldTanggal) {
                        $subquery->select('id')
                            ->from('jurnal_umum')
                            ->where('no_referensi', $oldReferensi)
                            ->where('tanggal', $oldTanggal);
                    });
                })->delete();

            // Dapatkan semua akun terkait kas dan bank yang terpengaruh oleh jurnal lama
            $akunIds = JurnalUmum::where('no_referensi', $oldReferensi)
                ->where('tanggal', $oldTanggal)
                ->where('jenis_jurnal', 'umum')
                ->pluck('akun_id');

            $kasIds = AkunAkuntansi::whereIn('id', $akunIds)
                ->where('ref_type', 'App\Models\Kas')
                ->pluck('ref_id');

            $rekeningIds = AkunAkuntansi::whereIn('id', $akunIds)
                ->where('ref_type', 'App\Models\RekeningBank')
                ->pluck('ref_id');

            // Reset saldo kas yang terpengaruh oleh jurnal lama
            if ($kasIds->count() > 0) {
                // Refresh saldo kas dari transaksi yang tersisa
                foreach ($kasIds as $kasId) {
                    $kas = Kas::find($kasId);
                    if ($kas) {
                        $totalMasuk = TransaksiKas::where('kas_id', $kasId)
                            ->where('jenis', 'masuk')
                            ->sum('jumlah');

                        $totalKeluar = TransaksiKas::where('kas_id', $kasId)
                            ->where('jenis', 'keluar')
                            ->sum('jumlah');

                        $kas->saldo = $totalMasuk - $totalKeluar;
                        $kas->save();
                    }
                }
            }

            // Reset saldo rekening yang terpengaruh oleh jurnal lama
            if ($rekeningIds->count() > 0) {
                // Refresh saldo rekening dari transaksi yang tersisa
                foreach ($rekeningIds as $rekeningId) {
                    $rekening = RekeningBank::find($rekeningId);
                    if ($rekening) {
                        $totalMasuk = TransaksiBank::where('rekening_id', $rekeningId)
                            ->where('jenis', 'masuk')
                            ->sum('jumlah');

                        $totalKeluar = TransaksiBank::where('rekening_id', $rekeningId)
                            ->where('jenis', 'keluar')
                            ->sum('jumlah');

                        $rekening->saldo = $totalMasuk - $totalKeluar;
                        $rekening->save();
                    }
                }
            }

            // Hapus semua entri jurnal terkait
            JurnalUmum::where('no_referensi', $oldReferensi)
                ->where('tanggal', $oldTanggal)
                ->where('jenis_jurnal', 'umum')
                ->delete();

            // Array untuk menyimpan akun-akun kas dan rekening yang perlu diupdate
            $kasToUpdate = [];
            $rekeningToUpdate = [];

            // Buat ulang semua entri jurnal
            foreach ($request->jurnal_items as $item) {
                // Buat entri jurnal baru
                $newJurnal = JurnalUmum::create([
                    'tanggal' => $request->tanggal,
                    'no_referensi' => $request->no_referensi,
                    'keterangan' => $request->keterangan,
                    'akun_id' => $item['akun_id'],
                    'debit' => $item['debit'] ?? 0,
                    'kredit' => $item['kredit'] ?? 0,
                    'jenis_jurnal' => 'umum',
                    'user_id' => Auth::id(),
                ]);

                // Periksa apakah akun ini terkait dengan Kas atau RekeningBank
                $akun = AkunAkuntansi::with('reference')->find($item['akun_id']);

                if ($akun && $akun->ref_type) {
                    $debit = (float)($item['debit'] ?? 0);
                    $kredit = (float)($item['kredit'] ?? 0);

                    if ($akun->ref_type === 'App\Models\Kas') {
                        // Untuk akun Kas, kita perlu membalik logika
                        // Nilai perubahan positif jika debit > kredit (masuk/penambahan)
                        // Nilai perubahan negatif jika kredit > debit (keluar/pengurangan)
                        $nilaiPerubahan = $debit - $kredit;
                        Log::info('Kas - ID: ' . $akun->ref_id . ', debit: ' . $debit . ', kredit: ' . $kredit . ', nilai perubahan: ' . $nilaiPerubahan);

                        if (!isset($kasToUpdate[$akun->ref_id])) {
                            $kasToUpdate[$akun->ref_id] = 0;
                        }
                        $kasToUpdate[$akun->ref_id] += $nilaiPerubahan;
                    } elseif ($akun->ref_type === 'App\Models\RekeningBank') {
                        // Untuk akun Rekening Bank, kita perlu membalik logika
                        // Nilai perubahan positif jika debit > kredit (masuk/penambahan)
                        // Nilai perubahan negatif jika kredit > debit (keluar/pengurangan)

                        // Tambahkan log untuk debugging
                        Log::info('Rekening Bank - ref_id: ' . $akun->ref_id . ', debit: ' . $debit . ', kredit: ' . $kredit);

                        $nilaiPerubahan = $debit - $kredit;
                        Log::info('Nilai Perubahan: ' . $nilaiPerubahan);

                        if (!isset($rekeningToUpdate[$akun->ref_id])) {
                            $rekeningToUpdate[$akun->ref_id] = 0;
                        }
                        $rekeningToUpdate[$akun->ref_id] += $nilaiPerubahan;
                    }
                }
            }

            // Update saldo kas
            foreach ($kasToUpdate as $kasId => $nilaiPerubahan) {
                $kas = Kas::find($kasId);
                if ($kas) {
                    // Log saldo sebelum update
                    Log::info('Update - Kas - ID: ' . $kasId . ', Saldo Sebelum: ' . $kas->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                    $kas->saldo += $nilaiPerubahan;
                    $kas->save();

                    // Log saldo setelah update
                    Log::info('Update - Kas - ID: ' . $kasId . ', Saldo Setelah: ' . $kas->saldo);

                    // Buat transaksi kas untuk mencatat perubahan
                    if ($nilaiPerubahan != 0) {
                        TransaksiKas::create([
                            'tanggal' => $request->tanggal,
                            'kas_id' => $kasId,
                            'jenis' => $nilaiPerubahan > 0 ? 'masuk' : 'keluar',
                            'jumlah' => abs($nilaiPerubahan),
                            'keterangan' => $request->keterangan,
                            'no_bukti' => $request->no_referensi,
                            'related_id' => $newJurnal->id,
                            'related_type' => JurnalUmum::class,
                            'user_id' => Auth::id()
                        ]);
                    }
                }
            }

            // Update saldo rekening bank
            foreach ($rekeningToUpdate as $rekeningId => $nilaiPerubahan) {
                $rekening = RekeningBank::find($rekeningId);
                if ($rekening) {
                    // Log saldo sebelum update
                    Log::info('Update - RekeningBank - ID: ' . $rekeningId . ', Saldo Sebelum: ' . $rekening->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                    $rekening->saldo += $nilaiPerubahan;
                    $rekening->save();

                    // Log saldo setelah update
                    Log::info('Update - RekeningBank - ID: ' . $rekeningId . ', Saldo Setelah: ' . $rekening->saldo);

                    // Buat transaksi bank untuk mencatat perubahan
                    if ($nilaiPerubahan != 0) {
                        TransaksiBank::create([
                            'tanggal' => $request->tanggal,
                            'rekening_id' => $rekeningId,
                            'jenis' => $nilaiPerubahan > 0 ? 'masuk' : 'keluar',
                            'jumlah' => abs($nilaiPerubahan),
                            'keterangan' => $request->keterangan,
                            'no_referensi' => $request->no_referensi,
                            'related_id' => $newJurnal->id,
                            'related_type' => JurnalUmum::class,
                            'user_id' => Auth::id()
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('keuangan.jurnal-umum.index')
                ->with('success', 'Jurnal berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Dapatkan jurnal yang akan dihapus
            $jurnal = JurnalUmum::where('jenis_jurnal', 'umum')->findOrFail($id);
            $referensi = $jurnal->no_referensi;
            $tanggal = $jurnal->tanggal;

            // Cek apakah jurnal sudah diposting, jika ya maka unpost dulu
            if ($jurnal->is_posted) {
                // Unpost jurnal terlebih dahulu untuk membalik saldo
                $this->unpostJournal($referensi);
            }

            // Hapus transaksi kas dan bank terkait dengan jurnal ini
            TransaksiKas::where('related_type', JurnalUmum::class)
                ->where(function ($query) use ($referensi, $tanggal) {
                    $query->whereIn('related_id', function ($subquery) use ($referensi, $tanggal) {
                        $subquery->select('id')
                            ->from('jurnal_umum')
                            ->where('no_referensi', $referensi)
                            ->where('tanggal', $tanggal);
                    });
                })->delete();

            TransaksiBank::where('related_type', JurnalUmum::class)
                ->where(function ($query) use ($referensi, $tanggal) {
                    $query->whereIn('related_id', function ($subquery) use ($referensi, $tanggal) {
                        $subquery->select('id')
                            ->from('jurnal_umum')
                            ->where('no_referensi', $referensi)
                            ->where('tanggal', $tanggal);
                    });
                })->delete();

            // Hapus semua entri jurnal terkait
            JurnalUmum::where('no_referensi', $referensi)
                ->where('tanggal', $tanggal)
                ->where('jenis_jurnal', 'umum')
                ->delete();

            DB::commit();

            return redirect()->route('keuangan.jurnal-umum.index')
                ->with('success', 'Jurnal berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper method to reverse balance changes for kas/bank accounts only
     * Used internally by destroy() method when deleting posted journals
     */
    private function unpostJournal($noReferensi)
    {
        // Get all entries for this reference
        $entries = JurnalUmum::where('no_referensi', $noReferensi)->get();

        if ($entries->isEmpty()) {
            return;
        }

        // Array untuk menyimpan akun-akun kas dan rekening yang perlu diupdate (reverse)
        $kasToUpdate = [];
        $rekeningToUpdate = [];

        // Process each entry to calculate balance changes (reverse)
        foreach ($entries as $entry) {
            // Periksa apakah akun ini terkait dengan Kas atau RekeningBank
            $akun = AkunAkuntansi::with('reference')->find($entry->akun_id);

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
                Log::info('Internal Unpost - Kas - ID: ' . $kasId . ', Saldo Sebelum: ' . $kas->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                $kas->saldo += $nilaiPerubahan;
                $kas->save();

                Log::info('Internal Unpost - Kas - ID: ' . $kasId . ', Saldo Setelah: ' . $kas->saldo);
            }
        }

        // Update saldo rekening bank (reverse)
        foreach ($rekeningToUpdate as $rekeningId => $nilaiPerubahan) {
            $rekening = RekeningBank::find($rekeningId);
            if ($rekening) {
                Log::info('Internal Unpost - RekeningBank - ID: ' . $rekeningId . ', Saldo Sebelum: ' . $rekening->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                $rekening->saldo += $nilaiPerubahan;
                $rekening->save();

                Log::info('Internal Unpost - RekeningBank - ID: ' . $rekeningId . ', Saldo Setelah: ' . $rekening->saldo);
            }
        }
    }

    /**
     * Export jurnal umum to Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $akunId = $request->get('akun_id');
            $noReferensi = $request->get('no_referensi');

            // Validate date range if provided
            if ($startDate && $endDate && $startDate > $endDate) {
                return redirect()->back()
                    ->withErrors(['error' => 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.']);
            }

            // Generate filename
            $filename = 'jurnal_umum';
            if ($startDate && $endDate) {
                $filename .= '_' . str_replace('-', '', $startDate) . '_' . str_replace('-', '', $endDate);
            }
            if ($akunId) {
                $akun = \App\Models\AkunAkuntansi::find($akunId);
                if ($akun) {
                    $filename .= '_' . $akun->kode;
                }
            }
            $filename .= '.xlsx';

            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\JurnalUmumExport($startDate, $endDate, $akunId, $noReferensi),
                $filename
            );
        } catch (\Exception $e) {
            Log::error('Error exporting jurnal umum: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat export: ' . $e->getMessage()]);
        }
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
            // Get all entries for this reference (only jurnal umum)
            $entries = JurnalUmum::where('no_referensi', $noReferensi)
                ->where('jenis_jurnal', 'umum')
                ->get();

            if ($entries->isEmpty()) {
                return redirect()->back()->withErrors(['error' => 'Jurnal tidak ditemukan']);
            }

            // Check if already posted
            if ($entries->first()->is_posted) {
                return redirect()->back()->withErrors(['error' => 'Jurnal sudah diposting sebelumnya']);
            }

            // Array untuk menyimpan akun-akun kas dan rekening yang perlu diupdate
            $kasToUpdate = [];
            $rekeningToUpdate = [];

            // Process each entry to calculate balance changes
            foreach ($entries as $entry) {
                // Periksa apakah akun ini terkait dengan Kas atau RekeningBank
                $akun = AkunAkuntansi::with('reference')->find($entry->akun_id);

                if ($akun && $akun->ref_type) {
                    $debit = (float)$entry->debit;
                    $kredit = (float)$entry->kredit;

                    if ($akun->ref_type === 'App\Models\Kas') {
                        // Untuk akun Kas, kita perlu membalik logika
                        // Nilai perubahan positif jika debit > kredit (masuk/penambahan)
                        // Nilai perubahan negatif jika kredit > debit (keluar/pengurangan)
                        $nilaiPerubahan = $debit - $kredit;

                        if (!isset($kasToUpdate[$akun->ref_id])) {
                            $kasToUpdate[$akun->ref_id] = 0;
                        }
                        $kasToUpdate[$akun->ref_id] += $nilaiPerubahan;
                    } elseif ($akun->ref_type === 'App\Models\RekeningBank') {
                        // Untuk akun Rekening Bank, kita perlu membalik logika
                        // Nilai perubahan positif jika debit > kredit (masuk/penambahan)
                        // Nilai perubahan negatif jika kredit > debit (keluar/pengurangan)
                        $nilaiPerubahan = $debit - $kredit;

                        if (!isset($rekeningToUpdate[$akun->ref_id])) {
                            $rekeningToUpdate[$akun->ref_id] = 0;
                        }
                        $rekeningToUpdate[$akun->ref_id] += $nilaiPerubahan;
                    }
                }
            }

            // Update saldo kas
            foreach ($kasToUpdate as $kasId => $nilaiPerubahan) {
                $kas = Kas::find($kasId);
                if ($kas) {
                    // Log saldo sebelum update
                    Log::info('Post - Kas - ID: ' . $kasId . ', Saldo Sebelum: ' . $kas->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                    $kas->saldo += $nilaiPerubahan;
                    $kas->save();

                    // Log saldo setelah update
                    Log::info('Post - Kas - ID: ' . $kasId . ', Saldo Setelah: ' . $kas->saldo);

                    // Buat transaksi kas untuk mencatat perubahan
                    if ($nilaiPerubahan != 0) {
                        $firstEntry = $entries->first();
                        TransaksiKas::create([
                            'tanggal' => $firstEntry->tanggal,
                            'kas_id' => $kasId,
                            'jenis' => $nilaiPerubahan > 0 ? 'masuk' : 'keluar',
                            'jumlah' => abs($nilaiPerubahan),
                            'keterangan' => $firstEntry->keterangan,
                            'no_bukti' => $firstEntry->no_referensi,
                            'related_id' => $firstEntry->id,
                            'related_type' => JurnalUmum::class,
                            'user_id' => Auth::id()
                        ]);
                    }
                }
            }

            // Update saldo rekening bank
            foreach ($rekeningToUpdate as $rekeningId => $nilaiPerubahan) {
                $rekening = RekeningBank::find($rekeningId);
                if ($rekening) {
                    // Log saldo sebelum update
                    Log::info('Post - RekeningBank - ID: ' . $rekeningId . ', Saldo Sebelum: ' . $rekening->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                    $rekening->saldo += $nilaiPerubahan;
                    $rekening->save();

                    // Log saldo setelah update
                    Log::info('Post - RekeningBank - ID: ' . $rekeningId . ', Saldo Setelah: ' . $rekening->saldo);

                    // Buat transaksi bank untuk mencatat perubahan
                    if ($nilaiPerubahan != 0) {
                        $firstEntry = $entries->first();
                        TransaksiBank::create([
                            'tanggal' => $firstEntry->tanggal,
                            'rekening_id' => $rekeningId,
                            'jenis' => $nilaiPerubahan > 0 ? 'masuk' : 'keluar',
                            'jumlah' => abs($nilaiPerubahan),
                            'keterangan' => $firstEntry->keterangan,
                            'no_referensi' => $firstEntry->no_referensi,
                            'related_id' => $firstEntry->id,
                            'related_type' => JurnalUmum::class,
                            'user_id' => Auth::id()
                        ]);
                    }
                }
            }

            // Post all entries with this reference
            JurnalUmum::where('no_referensi', $noReferensi)
                ->update([
                    'is_posted' => true,
                    'posted_at' => now(),
                    'posted_by' => Auth::id()
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'Jurnal berhasil diposting dan saldo kas/bank telah diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error posting journal: ' . $e->getMessage());
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
            // Get all entries for this reference (only jurnal umum)
            $entries = JurnalUmum::where('no_referensi', $noReferensi)
                ->where('jenis_jurnal', 'umum')
                ->get();

            if ($entries->isEmpty()) {
                return redirect()->back()->withErrors(['error' => 'Jurnal tidak ditemukan']);
            }

            // Check if not posted
            if (!$entries->first()->is_posted) {
                return redirect()->back()->withErrors(['error' => 'Jurnal belum diposting']);
            }

            // Array untuk menyimpan akun-akun kas dan rekening yang perlu diupdate (reverse)
            $kasToUpdate = [];
            $rekeningToUpdate = [];

            // Process each entry to calculate balance changes (reverse)
            foreach ($entries as $entry) {
                // Periksa apakah akun ini terkait dengan Kas atau RekeningBank
                $akun = AkunAkuntansi::with('reference')->find($entry->akun_id);

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
                    // Log saldo sebelum update
                    Log::info('Unpost - Kas - ID: ' . $kasId . ', Saldo Sebelum: ' . $kas->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                    $kas->saldo += $nilaiPerubahan;
                    $kas->save();

                    // Log saldo setelah update
                    Log::info('Unpost - Kas - ID: ' . $kasId . ', Saldo Setelah: ' . $kas->saldo);
                }
            }

            // Update saldo rekening bank (reverse)
            foreach ($rekeningToUpdate as $rekeningId => $nilaiPerubahan) {
                $rekening = RekeningBank::find($rekeningId);
                if ($rekening) {
                    // Log saldo sebelum update
                    Log::info('Unpost - RekeningBank - ID: ' . $rekeningId . ', Saldo Sebelum: ' . $rekening->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                    $rekening->saldo += $nilaiPerubahan;
                    $rekening->save();

                    // Log saldo setelah update
                    Log::info('Unpost - RekeningBank - ID: ' . $rekeningId . ', Saldo Setelah: ' . $rekening->saldo);
                }
            }

            // Delete related transactions
            TransaksiKas::where('no_bukti', $noReferensi)->delete();

            TransaksiBank::where('no_referensi', $noReferensi)->delete();

            // Unpost all entries with this reference
            JurnalUmum::where('no_referensi', $noReferensi)
                ->update([
                    'is_posted' => false,
                    'posted_at' => null,
                    'posted_by' => null
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'Jurnal berhasil dibatalkan postingnya dan saldo kas/bank telah dikembalikan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error unposting journal: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Gagal membatalkan posting jurnal: ' . $e->getMessage()]);
        }
    }
}
