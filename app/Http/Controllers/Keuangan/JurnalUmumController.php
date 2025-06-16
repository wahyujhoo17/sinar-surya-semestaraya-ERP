<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\AkunAkuntansi;
use App\Models\JurnalUmum;
use App\Models\Kas;
use App\Models\RekeningBank;
use App\Models\TransaksiKas;
use App\Models\TransaksiBank;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\LOG;

class JurnalUmumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mendapatkan semua data jurnal umum yang diurutkan berdasarkan tanggal terbaru
        $query = JurnalUmum::with(['akun', 'user'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc');

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

        $jurnals = $query->paginate(15);
        $akuns = AkunAkuntansi::where('is_active', 1)
            ->orderBy('kode', 'asc')
            ->get();

        // Set breadcrumbs
        $breadcrumbs = [
            ['url' => route('dashboard'), 'label' => 'Dashboard'],
            ['url' => '#', 'label' => 'Keuangan'],
            ['url' => null, 'label' => 'Jurnal Umum'],
        ];

        $currentPage = 'jurnal-umum';

        return view('keuangan.jurnal_umum.index', compact('jurnals', 'akuns', 'breadcrumbs', 'currentPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $akuns = AkunAkuntansi::where('is_active', 1)
            ->orderBy('kode', 'asc')
            ->get();

        $breadcrumbs = [
            ['url' => route('dashboard'), 'label' => 'Dashboard'],
            ['url' => '#', 'label' => 'Keuangan'],
            ['url' => route('keuangan.jurnal-umum.index'), 'label' => 'Jurnal Umum'],
            ['url' => null, 'label' => 'Tambah Jurnal'],
        ];

        $currentPage = 'jurnal-umum';

        return view('keuangan.jurnal_umum.create', compact('akuns', 'breadcrumbs', 'currentPage'));
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

            // Array untuk menyimpan akun-akun kas dan rekening yang perlu diupdate
            $kasToUpdate = [];
            $rekeningToUpdate = [];

            // Simpan semua item jurnal
            foreach ($request->jurnal_items as $item) {
                // Buat entri jurnal
                $jurnal = JurnalUmum::create([
                    'tanggal' => $request->tanggal,
                    'no_referensi' => $request->no_referensi,
                    'keterangan' => $request->keterangan,
                    'akun_id' => $item['akun_id'],
                    'debit' => $item['debit'] ?? 0,
                    'kredit' => $item['kredit'] ?? 0,
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
                    Log::info('Store - Kas - ID: ' . $kasId . ', Saldo Sebelum: ' . $kas->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                    $kas->saldo += $nilaiPerubahan;
                    $kas->save();

                    // Log saldo setelah update
                    Log::info('Store - Kas - ID: ' . $kasId . ', Saldo Setelah: ' . $kas->saldo);

                    // Buat transaksi kas untuk mencatat perubahan
                    if ($nilaiPerubahan != 0) {
                        TransaksiKas::create([
                            'tanggal' => $request->tanggal,
                            'kas_id' => $kasId,
                            'jenis' => $nilaiPerubahan > 0 ? 'masuk' : 'keluar',
                            'jumlah' => abs($nilaiPerubahan),
                            'keterangan' => $request->keterangan,
                            'no_bukti' => $request->no_referensi,
                            'related_id' => $jurnal->id,
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
                    Log::info('RekeningBank - ID: ' . $rekeningId . ', Saldo Sebelum: ' . $rekening->saldo . ', Nilai Perubahan: ' . $nilaiPerubahan);

                    $rekening->saldo += $nilaiPerubahan;
                    $rekening->save();

                    // Log saldo setelah update
                    Log::info('RekeningBank - ID: ' . $rekeningId . ', Saldo Setelah: ' . $rekening->saldo);

                    // Buat transaksi bank untuk mencatat perubahan
                    if ($nilaiPerubahan != 0) {
                        TransaksiBank::create([
                            'tanggal' => $request->tanggal,
                            'rekening_id' => $rekeningId,
                            'jenis' => $nilaiPerubahan > 0 ? 'masuk' : 'keluar',
                            'jumlah' => abs($nilaiPerubahan),
                            'keterangan' => $request->keterangan,
                            'no_referensi' => $request->no_referensi,
                            'related_id' => $jurnal->id,
                            'related_type' => JurnalUmum::class,
                            'user_id' => Auth::id()
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('keuangan.jurnal-umum.index')
                ->with('success', 'Jurnal berhasil disimpan.');
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
        $jurnal = JurnalUmum::findOrFail($id);
        $relatedJurnals = JurnalUmum::where('no_referensi', $jurnal->no_referensi)
            ->where('tanggal', $jurnal->tanggal)
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
        $jurnal = JurnalUmum::findOrFail($id);
        $jurnalItems = JurnalUmum::where('no_referensi', $jurnal->no_referensi)
            ->where('tanggal', $jurnal->tanggal)
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

            // Dapatkan jurnal yang diedit
            $jurnal = JurnalUmum::findOrFail($id);
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
                ->pluck('akun_id');

            $kasIds = AkunAkuntansi::whereIn('id', $akunIds)
                ->where('ref_type', 'App\\Models\\Kas')
                ->pluck('ref_id');

            $rekeningIds = AkunAkuntansi::whereIn('id', $akunIds)
                ->where('ref_type', 'App\\Models\\RekeningBank')
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
                    'user_id' => Auth::id(),
                ]);

                // Periksa apakah akun ini terkait dengan Kas atau RekeningBank
                $akun = AkunAkuntansi::with('reference')->find($item['akun_id']);

                if ($akun && $akun->ref_type) {
                    $debit = (float)($item['debit'] ?? 0);
                    $kredit = (float)($item['kredit'] ?? 0);

                    if ($akun->ref_type === 'App\\Models\\Kas') {
                        // Untuk akun Kas, kita perlu membalik logika
                        // Nilai perubahan positif jika debit > kredit (masuk/penambahan)
                        // Nilai perubahan negatif jika kredit > debit (keluar/pengurangan)
                        $nilaiPerubahan = $debit - $kredit;
                        Log::info('Kas - ID: ' . $akun->ref_id . ', debit: ' . $debit . ', kredit: ' . $kredit . ', nilai perubahan: ' . $nilaiPerubahan);

                        if (!isset($kasToUpdate[$akun->ref_id])) {
                            $kasToUpdate[$akun->ref_id] = 0;
                        }
                        $kasToUpdate[$akun->ref_id] += $nilaiPerubahan;
                    } elseif ($akun->ref_type === 'App\\Models\\RekeningBank') {
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
            $jurnal = JurnalUmum::findOrFail($id);
            $referensi = $jurnal->no_referensi;
            $tanggal = $jurnal->tanggal;

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

            // Dapatkan semua akun terkait kas dan bank yang terpengaruh oleh jurnal
            $akunIds = JurnalUmum::where('no_referensi', $referensi)
                ->where('tanggal', $tanggal)
                ->pluck('akun_id');

            $kasIds = AkunAkuntansi::whereIn('id', $akunIds)
                ->where('ref_type', 'App\\Models\\Kas')
                ->pluck('ref_id');

            $rekeningIds = AkunAkuntansi::whereIn('id', $akunIds)
                ->where('ref_type', 'App\\Models\\RekeningBank')
                ->pluck('ref_id');

            // Hapus semua entri jurnal terkait
            JurnalUmum::where('no_referensi', $referensi)
                ->where('tanggal', $tanggal)
                ->delete();

            // Perbarui saldo kas yang terpengaruh
            if ($kasIds->count() > 0) {
                foreach ($kasIds as $kasId) {
                    $kas = Kas::find($kasId);
                    if ($kas) {
                        // Log saldo sebelum update
                        Log::info('Destroy - Kas - ID: ' . $kasId . ', Saldo Sebelum: ' . $kas->saldo);

                        $totalMasuk = TransaksiKas::where('kas_id', $kasId)
                            ->where('jenis', 'masuk')
                            ->sum('jumlah');

                        $totalKeluar = TransaksiKas::where('kas_id', $kasId)
                            ->where('jenis', 'keluar')
                            ->sum('jumlah');

                        $kas->saldo = $totalMasuk - $totalKeluar;
                        $kas->save();

                        // Log saldo setelah update
                        Log::info('Destroy - Kas - ID: ' . $kasId . ', Saldo Setelah: ' . $kas->saldo);
                    }
                }
            }

            // Perbarui saldo rekening yang terpengaruh
            if ($rekeningIds->count() > 0) {
                foreach ($rekeningIds as $rekeningId) {
                    $rekening = RekeningBank::find($rekeningId);
                    if ($rekening) {
                        // Log saldo sebelum update
                        Log::info('Destroy - RekeningBank - ID: ' . $rekeningId . ', Saldo Sebelum: ' . $rekening->saldo);

                        $totalMasuk = TransaksiBank::where('rekening_id', $rekeningId)
                            ->where('jenis', 'masuk')
                            ->sum('jumlah');

                        $totalKeluar = TransaksiBank::where('rekening_id', $rekeningId)
                            ->where('jenis', 'keluar')
                            ->sum('jumlah');

                        $rekening->saldo = $totalMasuk - $totalKeluar;
                        $rekening->save();

                        // Log saldo setelah update
                        Log::info('Destroy - RekeningBank - ID: ' . $rekeningId . ', Saldo Setelah: ' . $rekening->saldo);
                    }
                }
            }

            DB::commit();

            return redirect()->route('keuangan.jurnal-umum.index')
                ->with('success', 'Jurnal berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
