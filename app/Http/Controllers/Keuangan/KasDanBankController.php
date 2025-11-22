<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use App\Models\RekeningBank;
use App\Models\TransaksiKas;
use App\Models\TransaksiBank;
use App\Models\AkunAkuntansi;
use App\Models\AccountingConfiguration;
use App\Services\JournalEntryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KasDanBankController extends Controller
{
    /**
     * Menampilkan halaman manajemen kas dan bank
     */
    public function index()
    {
        // Mengambil data kas dan rekening bank yang aktif
        $kasAll = Kas::where('is_aktif', true)->get();
        $rekeningAll = RekeningBank::where('is_aktif', true)
            ->where('is_perusahaan', true)
            ->get();

        // Hitung total saldo kas dan rekening
        $totalKas = $kasAll->sum('saldo');
        $totalRekening = $rekeningAll->sum('saldo');

        // Data transaksi terbaru (5 transaksi terakhir)
        $transaksiKas = TransaksiKas::with('kas', 'user')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        $transaksiBank = TransaksiBank::with('rekening', 'user')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // Data project untuk tab manajemen project
        $projects = \App\Models\Project::with(['transaksi'])
            ->aktif()
            ->get()
            ->map(function ($project) {
                $project->total_alokasi = $project->transaksi()
                    ->where('jenis', 'alokasi')
                    ->sum('nominal');
                $project->total_penggunaan = $project->transaksi()
                    ->where('jenis', 'penggunaan')
                    ->sum('nominal');
                $project->total_pengembalian = $project->transaksi()
                    ->where('jenis', 'pengembalian')
                    ->sum('nominal');

                // Hitung saldo project (saldo aktual dari transaksi)
                // Formula: Alokasi - Penggunaan - Pengembalian
                // Pengembalian mengurangi saldo project karena dana kembali ke kas/bank
                $saldoCalculated = $project->total_alokasi - $project->total_penggunaan - $project->total_pengembalian;

                // Set saldo untuk display
                $project->saldo_display = $saldoCalculated;

                // Hitung persentase penggunaan budget (gunakan total alokasi sebagai basis)
                if ($project->total_alokasi > 0) {
                    $percentage = ($project->total_penggunaan / $project->total_alokasi) * 100;
                    $project->persentase_penggunaan = round($percentage, 2);

                    // Debug logging for troubleshooting
                    \Log::info('Project calculation debug', [
                        'project_name' => $project->nama,
                        'total_alokasi' => $project->total_alokasi,
                        'total_penggunaan' => $project->total_penggunaan,
                        'raw_percentage' => $percentage,
                        'rounded_percentage' => $project->persentase_penggunaan
                    ]);
                } else {
                    $project->persentase_penggunaan = 0;
                }

                return $project;
            });

        // Statistik project
        $totalBudget = $projects->sum('budget');
        $totalAlokasi = $projects->sum('total_alokasi');
        $sisaBudget = $totalBudget - $totalAlokasi;

        // Breadcrumbs untuk navigasi
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Keuangan', 'url' => '#'],
            ['name' => 'Kas & Bank', 'url' => null]
        ];

        return view('keuangan.kas_dan_bank.index', compact(
            'kasAll',
            'rekeningAll',
            'totalKas',
            'totalRekening',
            'transaksiKas',
            'transaksiBank',
            'projects',
            'totalBudget',
            'totalAlokasi',
            'sisaBudget',
            'breadcrumbs'
        ))->with('currentPage', 'Kas & Bank');
    }

    /**
     * Menampilkan detail kas
     */
    public function detailKas($id, Request $request)
    {
        $kas = Kas::findOrFail($id);

        // Start query builder for filters
        $baseQuery = TransaksiKas::where('kas_id', $id)->orderBy('created_at', 'desc');

        // Apply filters based on request parameters - clone the base query for reuse
        $query = clone $baseQuery;

        // Filter by transaction type (masuk/keluar/all)
        if ($request->has('jenis') && $request->jenis != 'all') {
            $query->where('jenis', $request->jenis);
            $baseQuery->where('jenis', $request->jenis);
        }

        // Filter by time period
        if ($request->has('periode') && $request->periode != 'all') {
            $days = (int) $request->periode;
            $query->where('tanggal', '>=', now()->subDays($days));
            $baseQuery->where('tanggal', '>=', now()->subDays($days));
        }

        // Filter by custom date range if provided
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
            $query->where('tanggal', '>=', $request->tanggal_mulai);
            $baseQuery->where('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->where('tanggal', '<=', $request->tanggal_akhir);
            $baseQuery->where('tanggal', '<=', $request->tanggal_akhir);
        }

        // Filter by amount range
        if ($request->has('nominal_min') && $request->nominal_min) {
            $query->where('jumlah', '>=', $request->nominal_min);
            $baseQuery->where('jumlah', '>=', $request->nominal_min);
        }

        if ($request->has('nominal_max') && $request->nominal_max) {
            $query->where('jumlah', '<=', $request->nominal_max);
            $baseQuery->where('jumlah', '<=', $request->nominal_max);
        }

        // Filter by description (keterangan)
        if ($request->has('keterangan') && $request->keterangan) {
            $query->where('keterangan', 'like', '%' . $request->keterangan . '%');
            $baseQuery->where('keterangan', 'like', '%' . $request->keterangan . '%');
        }

        // Calculate totals based on the filtered data
        $totalMasuk = (clone $baseQuery)->where('jenis', 'masuk')->sum('jumlah');
        $totalKeluar = (clone $baseQuery)->where('jenis', 'keluar')->sum('jumlah');

        // Handle export functionality
        if ($request->has('export') && $request->export == 'excel') {
            return $this->exportKasTransaksi($kas, $query->get(), $totalMasuk, $totalKeluar);
        }

        // Order and paginate results
        $transaksi = $query->orderBy('tanggal', 'desc')
            ->paginate(10);

        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Keuangan', 'url' => '#'],
            ['name' => 'Kas & Bank', 'url' => route('keuangan.kas-dan-bank.index')],
            ['name' => 'Detail Kas: ' . $kas->nama, 'url' => null]
        ];

        return view('keuangan.kas_dan_bank.detail_kas', compact(
            'kas',
            'transaksi',
            'totalMasuk',
            'totalKeluar',
            'breadcrumbs'
        ))->with('currentPage', 'Detail Kas');
    }

    /**
     * Export kas transaksi to Excel
     */
    private function exportKasTransaksi($kas, $transaksi, $totalMasuk = 0, $totalKeluar = 0)
    {
        // For now, return a simple response
        // In a real implementation, you would use Laravel Excel or a similar package
        return response()->json([
            'success' => true,
            'message' => 'Export functionality would be implemented here',
            'data' => [
                'kas' => $kas->nama,
                'count' => $transaksi->count(),
                'totalMasuk' => $totalMasuk,
                'totalKeluar' => $totalKeluar,
                'netChange' => $totalMasuk - $totalKeluar
            ]
        ]);

        // Example with Laravel Excel (you need to implement the export class):
        // return Excel::download(new TransaksiKasExport($transaksi, $totalMasuk, $totalKeluar), 'transaksi-' . $kas->nama . '.xlsx');
    }

    /**
     * Menampilkan detail rekening bank
     */
    public function detailRekening($id, Request $request)
    {
        $rekening = RekeningBank::findOrFail($id);

        // Start query builder for filters
        $baseQuery = TransaksiBank::where('rekening_id', $id)->orderBy('created_at', 'desc');

        // Apply filters based on request parameters - clone the base query for reuse
        $query = clone $baseQuery;

        // Filter by transaction type (masuk/keluar/all)
        if ($request->has('jenis') && $request->jenis != 'all') {
            $query->where('jenis', $request->jenis);
            $baseQuery->where('jenis', $request->jenis);
        }

        // Filter by time period
        if ($request->has('periode') && $request->periode != 'all') {
            $days = (int) $request->periode;
            $query->where('tanggal', '>=', now()->subDays($days));
            $baseQuery->where('tanggal', '>=', now()->subDays($days));
        }

        // Filter by custom date range if provided
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
            $query->where('tanggal', '>=', $request->tanggal_mulai);
            $baseQuery->where('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->where('tanggal', '<=', $request->tanggal_akhir);
            $baseQuery->where('tanggal', '<=', $request->tanggal_akhir);
        }

        // Filter by amount range
        if ($request->has('nominal_min') && $request->nominal_min) {
            $query->where('jumlah', '>=', $request->nominal_min);
            $baseQuery->where('jumlah', '>=', $request->nominal_min);
        }

        if ($request->has('nominal_max') && $request->nominal_max) {
            $query->where('jumlah', '<=', $request->nominal_max);
            $baseQuery->where('jumlah', '<=', $request->nominal_max);
        }

        // Filter by description (keterangan)
        if ($request->has('keterangan') && $request->keterangan) {
            $query->where('keterangan', 'like', '%' . $request->keterangan . '%');
            $baseQuery->where('keterangan', 'like', '%' . $request->keterangan . '%');
        }

        // Calculate totals based on the filtered data
        $totalMasuk = (clone $baseQuery)->where('jenis', 'masuk')->sum('jumlah');
        $totalKeluar = (clone $baseQuery)->where('jenis', 'keluar')->sum('jumlah');

        // Handle export functionality
        if ($request->has('export') && $request->export == 'excel') {
            return $this->exportRekeningTransaksi($rekening, $query->get(), $totalMasuk, $totalKeluar);
        }

        // Order and paginate results
        $transaksi = $query->orderBy('tanggal', 'desc')
            ->paginate(10);

        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Keuangan', 'url' => '#'],
            ['name' => 'Kas & Bank', 'url' => route('keuangan.kas-dan-bank.index')],
            ['name' => 'Detail Rekening: ' . $rekening->nama_bank, 'url' => null]
        ];

        return view('keuangan.kas_dan_bank.detail_rekening', compact(
            'rekening',
            'transaksi',
            'totalMasuk',
            'totalKeluar',
            'breadcrumbs'
        ))->with('currentPage', 'Detail Rekening');
    }

    /**
     * Export rekening transaksi to Excel
     */
    private function exportRekeningTransaksi($rekening, $transaksi, $totalMasuk = 0, $totalKeluar = 0)
    {
        // For now, return a simple response
        // In a real implementation, you would use Laravel Excel or a similar package
        return response()->json([
            'success' => true,
            'message' => 'Export functionality would be implemented here',
            'data' => [
                'rekening' => $rekening->nama_bank . ' - ' . $rekening->nomor_rekening,
                'count' => $transaksi->count(),
                'totalMasuk' => $totalMasuk,
                'totalKeluar' => $totalKeluar,
                'netChange' => $totalMasuk - $totalKeluar
            ]
        ]);

        // Example with Laravel Excel (you need to implement the export class):
        // return Excel::download(new TransaksiRekeningExport($transaksi, $totalMasuk, $totalKeluar), 'transaksi-' . $rekening->nama_bank . '.xlsx');
    }

    /**
     * Menyimpan kas baru
     */
    public function storeKas(Request $request)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'saldo' => 'required|numeric|min:0',
            'is_aktif' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Buat kas baru
            $kas = Kas::create([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'saldo' => $request->saldo,
                'is_aktif' => $request->is_aktif ? true : false,
            ]);

            // Auto-create akun COA untuk kas ini
            $akunKas = $this->createAkunKas($kas);

            // Jika saldo awal > 0, buat jurnal pembukaan
            if ($request->saldo > 0) {
                // Buat transaksi kas masuk untuk riwayat
                TransaksiKas::create([
                    'kas_id' => $kas->id,
                    'tanggal' => now(),
                    'keterangan' => 'Saldo awal kas ' . $kas->nama,
                    'jumlah' => $request->saldo,
                    'jenis' => 'masuk',
                    'user_id' => Auth::id(),
                ]);

                // Buat jurnal pembukaan (Debit: Kas, Kredit: Modal)
                $journalService = app(JournalEntryService::class);

                // PRIORITAS 1: Ambil dari Accounting Configuration (Kalibrasi)
                $modalConfig = AccountingConfiguration::where('transaction_type', 'saldo_awal')
                    ->where('account_key', 'modal_pemilik')
                    ->first();

                $akunModal = $modalConfig && $modalConfig->akun_id
                    ? AkunAkuntansi::find($modalConfig->akun_id)
                    : null;

                // PRIORITAS 2: Jika tidak dikonfigurasi, cari akun Modal/Ekuitas
                if (!$akunModal) {
                    $akunModal = AkunAkuntansi::whereIn('kategori', ['equity', 3])
                        ->where('nama', 'LIKE', '%Modal%')
                        ->where('tipe', 'detail')
                        ->first();
                }

                // PRIORITAS 3: Cari akun Ekuitas lainnya
                if (!$akunModal) {
                    $akunModal = AkunAkuntansi::whereIn('kategori', ['equity', 3])
                        ->where('tipe', 'detail')
                        ->first();
                }

                // PRIORITAS 4: Buat akun Modal default
                if (!$akunModal) {
                    $akunModal = $this->createDefaultModalAccount();
                }

                if ($akunKas && $akunModal) {
                    $entries = [
                        [
                            'akun_id' => $akunKas->id,  // Debit: Kas
                            'debit' => $request->saldo,
                            'kredit' => 0,
                        ],
                        [
                            'akun_id' => $akunModal->id,  // Kredit: Modal
                            'debit' => 0,
                            'kredit' => $request->saldo,
                        ]
                    ];

                    $noReferensi = 'SALDO-AWAL-KAS-' . $kas->id . '-' . now()->format('YmdHis');
                    $keterangan = 'Jurnal pembukaan saldo awal kas: ' . $kas->nama;

                    $journalService->createJournalEntries(
                        $entries,
                        $noReferensi,
                        $keterangan,
                        now()->format('Y-m-d'),
                        $kas
                    );
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kas berhasil ditambahkan',
                'data' => $kas
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update data kas
     */
    public function updateKas(Request $request, $id)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_aktif' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Update kas
            $kas = Kas::findOrFail($id);
            $kas->update([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'is_aktif' => $request->is_aktif ? true : false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kas berhasil diperbarui',
                'data' => $kas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus kas
     */
    public function destroyKas($id)
    {
        try {
            $kas = Kas::findOrFail($id);

            // Cek apakah kas memiliki transaksi
            $hasTransactions = TransaksiKas::where('kas_id', $id)->exists();

            if ($hasTransactions) {
                // Jika ada transaksi, nonaktifkan saja
                $kas->update(['is_aktif' => false]);
                $message = 'Kas dinonaktifkan karena memiliki riwayat transaksi';
            } else {
                // Jika tidak ada transaksi, hapus permanen
                $kas->delete();
                $message = 'Kas berhasil dihapus';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyimpan rekening bank baru
     */
    public function storeRekening(Request $request)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:255',
            'cabang' => 'nullable|string|max:255',
            'saldo' => 'required|numeric|min:0',
            'is_aktif' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Buat rekening baru
            $rekening = RekeningBank::create([
                'nama_bank' => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
                'atas_nama' => $request->atas_nama,
                'cabang' => $request->cabang,
                'saldo' => $request->saldo,
                'is_aktif' => $request->is_aktif ? true : false,
                'is_perusahaan' => true, // Selalu set true karena ini rekening perusahaan
            ]);

            // Auto-create akun COA untuk rekening ini
            $akunBank = $this->createAkunBank($rekening);

            // Jika saldo awal > 0, buat jurnal pembukaan
            if ($request->saldo > 0) {
                // Buat transaksi bank masuk untuk riwayat
                TransaksiBank::create([
                    'rekening_id' => $rekening->id,
                    'tanggal' => now(),
                    'keterangan' => 'Saldo awal rekening ' . $rekening->nama_bank . ' - ' . $rekening->nomor_rekening,
                    'jumlah' => $request->saldo,
                    'jenis' => 'masuk',
                    'user_id' => Auth::id(),
                ]);

                // Buat jurnal pembukaan (Debit: Bank, Kredit: Modal)
                $journalService = app(JournalEntryService::class);

                // PRIORITAS 1: Ambil dari Accounting Configuration (Kalibrasi)
                $modalConfig = AccountingConfiguration::where('transaction_type', 'saldo_awal')
                    ->where('account_key', 'modal_pemilik')
                    ->first();

                $akunModal = $modalConfig && $modalConfig->akun_id
                    ? AkunAkuntansi::find($modalConfig->akun_id)
                    : null;

                // PRIORITAS 2: Jika tidak dikonfigurasi, cari akun Modal/Ekuitas
                if (!$akunModal) {
                    $akunModal = AkunAkuntansi::whereIn('kategori', ['equity', 3])
                        ->where('nama', 'LIKE', '%Modal%')
                        ->where('tipe', 'detail')
                        ->first();
                }

                // PRIORITAS 3: Cari akun Ekuitas lainnya
                if (!$akunModal) {
                    $akunModal = AkunAkuntansi::whereIn('kategori', ['equity', 3])
                        ->where('tipe', 'detail')
                        ->first();
                }

                // PRIORITAS 4: Buat akun Modal default
                if (!$akunModal) {
                    $akunModal = $this->createDefaultModalAccount();
                }

                if ($akunBank && $akunModal) {
                    $entries = [
                        [
                            'akun_id' => $akunBank->id,  // Debit: Bank
                            'debit' => $request->saldo,
                            'kredit' => 0,
                        ],
                        [
                            'akun_id' => $akunModal->id,  // Kredit: Modal
                            'debit' => 0,
                            'kredit' => $request->saldo,
                        ]
                    ];

                    $noReferensi = 'SALDO-AWAL-BANK-' . $rekening->id . '-' . now()->format('YmdHis');
                    $keterangan = 'Jurnal pembukaan saldo awal rekening: ' . $rekening->nama_bank . ' - ' . $rekening->nomor_rekening;

                    $journalService->createJournalEntries(
                        $entries,
                        $noReferensi,
                        $keterangan,
                        now()->format('Y-m-d'),
                        $rekening
                    );
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rekening bank berhasil ditambahkan',
                'data' => $rekening
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update data rekening bank
     */
    public function updateRekening(Request $request, $id)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:255',
            'cabang' => 'nullable|string|max:255',
            'is_aktif' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Update rekening bank
            $rekening = RekeningBank::findOrFail($id);
            $rekening->update([
                'nama_bank' => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
                'atas_nama' => $request->atas_nama,
                'cabang' => $request->cabang,
                'is_aktif' => $request->is_aktif ? true : false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rekening bank berhasil diperbarui',
                'data' => $rekening
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus rekening bank
     */
    public function destroyRekening($id)
    {
        try {
            $rekening = RekeningBank::findOrFail($id);

            // Cek apakah rekening memiliki transaksi
            $hasTransactions = TransaksiBank::where('rekening_id', $id)->exists();

            if ($hasTransactions) {
                // Jika ada transaksi, nonaktifkan saja
                $rekening->update(['is_aktif' => false]);
                $message = 'Rekening dinonaktifkan karena memiliki riwayat transaksi';
            } else {
                // Jika tidak ada transaksi, hapus permanen
                $rekening->delete();
                $message = 'Rekening bank berhasil dihapus';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get the latest transactions for a cash account
     * 
     * @param int $id The kas (cash account) ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKasTransaksi($id)
    {
        try {
            $kas = Kas::findOrFail($id);

            // Get last 5 transactions
            $transaksi = TransaksiKas::where('kas_id', $id)
                ->orderBy('tanggal', 'desc')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $transaksi->map(function ($trx) {
                    return [
                        'id' => $trx->id,
                        'tanggal' => $trx->tanggal,
                        'jenis' => $trx->jenis,
                        'jumlah' => $trx->jumlah,
                        'keterangan' => $trx->keterangan,
                        'no_bukti' => $trx->no_bukti,
                        'created_at' => $trx->created_at
                    ];
                }),
                'account' => [
                    'id' => $kas->id,
                    'nama' => $kas->nama,
                    'deskripsi' => $kas->deskripsi,
                    'saldo' => $kas->saldo,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get the latest transactions for a bank account
     * 
     * @param int $id The rekening (bank account) ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRekeningTransaksi($id)
    {
        try {
            $rekening = RekeningBank::findOrFail($id);

            // Get last 5 transactions
            $transaksi = TransaksiBank::where('rekening_id', $id)
                ->orderBy('tanggal', 'desc')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $transaksi->map(function ($trx) {
                    return [
                        'id' => $trx->id,
                        'tanggal' => $trx->tanggal,
                        'jenis' => $trx->jenis,
                        'jumlah' => $trx->jumlah,
                        'keterangan' => $trx->keterangan,
                        'no_referensi' => $trx->no_referensi,
                        'created_at' => $trx->created_at
                    ];
                }),
                'account' => [
                    'id' => $rekening->id,
                    'nama_bank' => $rekening->nama_bank,
                    'nomor_rekening' => $rekening->nomor_rekening,
                    'atas_nama' => $rekening->atas_nama,
                    'cabang' => $rekening->cabang,
                    'saldo' => $rekening->saldo,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }





    /**
     * Menyimpan transaksi baru (kas atau bank)
     */
    public function storeTransaksi(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'jenis' => 'required|in:masuk,keluar',
            'account_type' => 'required|in:kas,bank',
            'account_id' => 'required|integer',
            'contra_account_id' => 'required|integer|exists:akun_akuntansi,id',
            'jumlah' => 'required|numeric|min:0.01',
            'keterangan' => 'required|string|max:500',
            'no_referensi' => 'nullable|string|max:100',
        ], [
            'tanggal.required' => 'Tanggal transaksi harus diisi',
            'jenis.required' => 'Jenis transaksi harus dipilih',
            'jenis.in' => 'Jenis transaksi tidak valid',
            'account_type.required' => 'Tipe akun harus dipilih',
            'account_type.in' => 'Tipe akun tidak valid',
            'account_id.required' => 'Akun harus dipilih',
            'contra_account_id.required' => 'Akun lawan transaksi harus dipilih',
            'contra_account_id.exists' => 'Akun lawan transaksi tidak valid',
            'jumlah.required' => 'Jumlah transaksi harus diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah minimal adalah 0.01',
            'keterangan.required' => 'Keterangan transaksi harus diisi',
            'keterangan.max' => 'Keterangan maksimal 500 karakter',
            'no_referensi.max' => 'No referensi maksimal 100 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Validasi akun berdasarkan tipe
            if ($request->account_type === 'kas') {
                $account = Kas::where('id', $request->account_id)
                    ->where('is_aktif', true)
                    ->first();

                if (!$account) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kas tidak ditemukan atau tidak aktif'
                    ], 404);
                }

                // Cek saldo untuk transaksi keluar
                if ($request->jenis === 'keluar' && $account->saldo < $request->jumlah) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Saldo kas tidak mencukupi. Saldo tersedia: Rp ' . number_format($account->saldo, 0, ',', '.')
                    ], 400);
                }
            } else {
                $account = RekeningBank::where('id', $request->account_id)
                    ->where('is_aktif', true)
                    ->where('is_perusahaan', true)
                    ->first();

                if (!$account) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Rekening bank tidak ditemukan atau tidak aktif'
                    ], 404);
                }

                // Cek saldo untuk transaksi keluar
                if ($request->jenis === 'keluar' && $account->saldo < $request->jumlah) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Saldo rekening tidak mencukupi. Saldo tersedia: Rp ' . number_format($account->saldo, 0, ',', '.')
                    ], 400);
                }
            }

            // Validasi akun lawan transaksi
            $contraAccount = AkunAkuntansi::where('id', $request->contra_account_id)
                ->where('is_active', true)
                ->first();

            if (!$contraAccount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun lawan transaksi tidak valid'
                ], 404);
            }

            // Cek apakah ini adalah transfer antar kas/bank
            $isTransfer = false;
            $targetAccount = null;

            if ($contraAccount->ref_type === 'App\Models\Kas' && $contraAccount->ref_id) {
                $targetAccount = Kas::find($contraAccount->ref_id);
                $isTransfer = true;
            } elseif ($contraAccount->ref_type === 'App\Models\RekeningBank' && $contraAccount->ref_id) {
                $targetAccount = RekeningBank::find($contraAccount->ref_id);
                $isTransfer = true;
            }

            // Generate nomor referensi jika tidak ada
            $noReferensi = $request->no_referensi;
            if (empty($noReferensi)) {
                $prefix = $request->account_type === 'kas' ? 'KAS' : 'BANK';
                $prefix .= $request->jenis === 'masuk' ? 'IN' : 'OUT';
                $noReferensi = $prefix . '-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }

            // Buat transaksi
            if ($request->account_type === 'kas') {
                $transaksi = TransaksiKas::create([
                    'kas_id' => $account->id,
                    'tanggal' => $request->tanggal,
                    'jenis' => $request->jenis,
                    'jumlah' => $request->jumlah,
                    'keterangan' => $request->keterangan,
                    'no_referensi' => $noReferensi,
                    'user_id' => Auth::id(),
                    'no_bukti' => $noReferensi,
                ]);

                // Update saldo kas
                if ($request->jenis === 'masuk') {
                    $account->increment('saldo', $request->jumlah);
                } else {
                    $account->decrement('saldo', $request->jumlah);
                }

                // Jika ini adalah transfer, update saldo target account
                if ($isTransfer && $targetAccount && $request->jenis === 'keluar') {
                    // Transfer dari kas ke kas/bank lain
                    $targetAccount->increment('saldo', $request->jumlah);

                    // Buat transaksi masuk di target account juga
                    if ($contraAccount->ref_type === 'App\Models\Kas') {
                        TransaksiKas::create([
                            'kas_id' => $targetAccount->id,
                            'tanggal' => $request->tanggal,
                            'jenis' => 'masuk',
                            'jumlah' => $request->jumlah,
                            'keterangan' => 'Transfer dari ' . $account->nama . ' - ' . $request->keterangan,
                            'no_referensi' => $noReferensi . '-IN',
                            'user_id' => Auth::id(),
                            'no_bukti' => $noReferensi,
                        ]);
                    } else {
                        TransaksiBank::create([
                            'rekening_id' => $targetAccount->id,
                            'tanggal' => $request->tanggal,
                            'jenis' => 'masuk',
                            'jumlah' => $request->jumlah,
                            'keterangan' => 'Transfer dari ' . $account->nama . ' - ' . $request->keterangan,
                            'no_referensi' => $noReferensi . '-IN',
                            'user_id' => Auth::id(),
                            'no_bukti' => $noReferensi,
                        ]);
                    }
                }
            } else {
                $transaksi = TransaksiBank::create([
                    'rekening_id' => $account->id,
                    'tanggal' => $request->tanggal,
                    'jenis' => $request->jenis,
                    'jumlah' => $request->jumlah,
                    'keterangan' => $request->keterangan,
                    'no_referensi' => $noReferensi,
                    'user_id' => Auth::id(),
                ]);

                // Update saldo rekening
                if ($request->jenis === 'masuk') {
                    $account->increment('saldo', $request->jumlah);
                } else {
                    $account->decrement('saldo', $request->jumlah);
                }

                // Jika ini adalah transfer, update saldo target account
                if ($isTransfer && $targetAccount && $request->jenis === 'keluar') {
                    // Transfer dari bank ke kas/bank lain
                    $targetAccount->increment('saldo', $request->jumlah);

                    // Buat transaksi masuk di target account juga
                    if ($contraAccount->ref_type === 'App\Models\Kas') {
                        TransaksiKas::create([
                            'kas_id' => $targetAccount->id,
                            'tanggal' => $request->tanggal,
                            'jenis' => 'masuk',
                            'jumlah' => $request->jumlah,
                            'keterangan' => 'Transfer dari ' . $account->nama_bank . ' - ' . $account->nomor_rekening . ' - ' . $request->keterangan,
                            'no_referensi' => $noReferensi . '-IN',
                            'user_id' => Auth::id(),
                            'no_bukti' => $noReferensi,
                        ]);
                    } else {
                        TransaksiBank::create([
                            'rekening_id' => $targetAccount->id,
                            'tanggal' => $request->tanggal,
                            'jenis' => 'masuk',
                            'jumlah' => $request->jumlah,
                            'keterangan' => 'Transfer dari ' . $account->nama_bank . ' - ' . $account->nomor_rekening . ' - ' . $request->keterangan,
                            'no_referensi' => $noReferensi . '-IN',
                            'user_id' => Auth::id(),
                            'no_bukti' => $noReferensi,
                        ]);
                    }
                }
            }

            // Dapatkan akun akuntansi untuk kas/bank
            $kasAccountRecord = AkunAkuntansi::where('ref_type', get_class($account))
                ->where('ref_id', $account->id)
                ->first();

            if (!$kasAccountRecord) {
                // Jika belum ada akun akuntansi untuk kas/bank ini, buat otomatis
                $kasAccountRecord = AkunAkuntansi::create([
                    'kode' => ($request->account_type === 'kas' ? '1110' : '1120') . str_pad($account->id, 3, '0', STR_PAD_LEFT),
                    'nama' => $request->account_type === 'kas' ? $account->nama : $account->nama_bank . ' - ' . $account->nomor_rekening,
                    'kategori' => 'asset',
                    'tipe' => 'current',
                    'is_active' => true,
                    'ref_type' => get_class($account),
                    'ref_id' => $account->id,
                ]);
            }

            // Buat entri jurnal otomatis
            $journalService = new JournalEntryService();

            $journalEntries = [];
            if ($request->jenis === 'masuk') {
                // Kas/Bank: Debit (penambahan aset)
                $journalEntries[] = [
                    'akun_id' => $kasAccountRecord->id,
                    'debit' => $request->jumlah,
                    'kredit' => 0,
                ];

                // Akun lawan: Kredit
                $journalEntries[] = [
                    'akun_id' => $contraAccount->id,
                    'debit' => 0,
                    'kredit' => $request->jumlah,
                ];
            } else {
                // Akun lawan: Debit
                $journalEntries[] = [
                    'akun_id' => $contraAccount->id,
                    'debit' => $request->jumlah,
                    'kredit' => 0,
                ];

                // Kas/Bank: Kredit (pengurangan aset)
                $journalEntries[] = [
                    'akun_id' => $kasAccountRecord->id,
                    'debit' => 0,
                    'kredit' => $request->jumlah,
                ];
            }

            $journalCreated = $journalService->createJournalEntries(
                $journalEntries,
                $noReferensi,
                $request->keterangan,
                $request->tanggal,
                $transaksi
            );

            if (!$journalCreated) {
                throw new \Exception('Gagal membuat entri jurnal');
            }

            DB::commit();

            // Tentukan pesan sukses berdasarkan jenis transaksi
            $successMessage = 'Transaksi berhasil disimpan dan jurnal otomatis telah dibuat';
            if ($isTransfer && $targetAccount) {
                $targetName = $contraAccount->ref_type === 'App\Models\Kas' ? $targetAccount->nama : $targetAccount->nama_bank;
                $successMessage = 'Transfer berhasil dilakukan ke ' . $targetName . ' dan jurnal otomatis telah dibuat';
            }

            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'data' => [
                    'transaksi_id' => $transaksi->id,
                    'no_referensi' => $noReferensi,
                    'saldo_baru' => $account->fresh()->saldo,
                    'is_transfer' => $isTransfer,
                    'target_saldo' => $isTransfer && $targetAccount ? $targetAccount->fresh()->saldo : null,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get chart of accounts for contra account selection
     */
    public function getChartOfAccounts()
    {
        try {
            $accounts = AkunAkuntansi::where('is_active', true)
                ->orderBy('kode')
                ->select('id', 'kode', 'nama', 'tipe', 'kategori')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $accounts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat chart of accounts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get active kas accounts for transaction modal
     */
    public function getActiveKas()
    {
        try {
            $kasAccounts = Kas::where('is_aktif', true)
                ->orderBy('nama')
                ->select('id', 'nama', 'saldo', 'deskripsi')
                ->get()
                ->map(function ($kas) {
                    return [
                        'id' => $kas->id,
                        'nama' => $kas->nama,
                        'saldo' => $kas->saldo,
                        'deskripsi' => $kas->deskripsi,
                        'display_name' => $kas->nama . ($kas->deskripsi ? ' - ' . $kas->deskripsi : '') . ' (Saldo: Rp ' . number_format($kas->saldo, 0, ',', '.') . ')'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $kasAccounts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data kas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get active bank accounts for transaction modal
     */
    public function getActiveRekeningBank()
    {
        try {
            $bankAccounts = RekeningBank::where('is_aktif', true)
                ->where('is_perusahaan', true)
                ->orderBy('nama_bank')
                ->select('id', 'nama_bank', 'nomor_rekening', 'atas_nama', 'saldo', 'cabang')
                ->get()
                ->map(function ($rekening) {
                    return [
                        'id' => $rekening->id,
                        'nama_bank' => $rekening->nama_bank,
                        'nomor_rekening' => $rekening->nomor_rekening,
                        'atas_nama' => $rekening->atas_nama,
                        'saldo' => $rekening->saldo,
                        'cabang' => $rekening->cabang,
                        'display_name' => $rekening->nama_bank . ' - ' . $rekening->nomor_rekening . ' (' . $rekening->atas_nama . ')' . ($rekening->cabang ? ' - ' . $rekening->cabang : '') . ' - Saldo: Rp ' . number_format($rekening->saldo, 0, ',', '.')
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $bankAccounts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data rekening bank: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper: Create akun COA untuk Kas
     */
    private function createAkunKas($kas)
    {
        // Cari parent akun "Kas dan Bank" atau "Kas" 
        $parentAkun = AkunAkuntansi::whereIn('kategori', ['asset', 1])
            ->where('tipe', 'header')
            ->where(function ($query) {
                $query->where('nama', 'LIKE', '%Kas dan Bank%')
                    ->orWhere('nama', 'LIKE', '%Kas & Bank%')
                    ->orWhere('kode', 'LIKE', '11%');
            })
            ->first();

        // Jika tidak ada parent, buat parent default
        if (!$parentAkun) {
            $parentAkun = AkunAkuntansi::create([
                'kode' => '1100',
                'nama' => 'KAS DAN BANK',
                'kategori' => 'asset',
                'tipe' => 'header',
                'is_active' => true,
            ]);
        }

        // Generate kode akun untuk kas ini
        $lastKas = AkunAkuntansi::where('ref_type', 'App\\Models\\Kas')
            ->where('kode', 'LIKE', '1101%')
            ->orderBy('kode', 'desc')
            ->first();

        $newKode = $lastKas ? (intval($lastKas->kode) + 1) : 110101;

        // Buat akun COA untuk kas ini
        $akunKas = AkunAkuntansi::create([
            'kode' => (string) $newKode,
            'nama' => 'KAS - ' . strtoupper($kas->nama),
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $parentAkun->id,
            'ref_type' => 'App\\Models\\Kas',
            'ref_id' => $kas->id,
            'is_active' => true,
        ]);

        return $akunKas;
    }

    /**
     * Helper: Create akun COA untuk Rekening Bank
     */
    private function createAkunBank($rekening)
    {
        // Cari parent akun "Kas dan Bank" atau "Bank"
        $parentAkun = AkunAkuntansi::whereIn('kategori', ['asset', 1])
            ->where('tipe', 'header')
            ->where(function ($query) {
                $query->where('nama', 'LIKE', '%Kas dan Bank%')
                    ->orWhere('nama', 'LIKE', '%Kas & Bank%')
                    ->orWhere('nama', 'LIKE', '%Bank%')
                    ->orWhere('kode', 'LIKE', '11%');
            })
            ->first();

        // Jika tidak ada parent, buat parent default
        if (!$parentAkun) {
            $parentAkun = AkunAkuntansi::create([
                'kode' => '1100',
                'nama' => 'KAS DAN BANK',
                'kategori' => 'asset',
                'tipe' => 'header',
                'is_active' => true,
            ]);
        }

        // Generate kode akun untuk bank ini
        $lastBank = AkunAkuntansi::where('ref_type', 'App\\Models\\RekeningBank')
            ->where('kode', 'LIKE', '1105%')
            ->orderBy('kode', 'desc')
            ->first();

        $newKode = $lastBank ? (intval($lastBank->kode) + 1) : 110501;

        // Buat akun COA untuk rekening ini
        $akunBank = AkunAkuntansi::create([
            'kode' => (string) $newKode,
            'nama' => 'BANK - ' . strtoupper($rekening->nama_bank) . ' - ' . $rekening->nomor_rekening,
            'kategori' => 'asset',
            'tipe' => 'detail',
            'parent_id' => $parentAkun->id,
            'ref_type' => 'App\\Models\\RekeningBank',
            'ref_id' => $rekening->id,
            'is_active' => true,
        ]);

        return $akunBank;
    }

    /**
     * Helper: Create akun Modal default jika belum ada
     */
    private function createDefaultModalAccount()
    {
        // Cari parent akun "Ekuitas" atau "Modal"
        $parentAkun = AkunAkuntansi::whereIn('kategori', ['equity', 3])
            ->where('tipe', 'header')
            ->where(function ($query) {
                $query->where('nama', 'LIKE', '%Ekuitas%')
                    ->orWhere('nama', 'LIKE', '%Modal%')
                    ->orWhere('kode', 'LIKE', '3%');
            })
            ->first();

        // Jika tidak ada parent, buat parent default
        if (!$parentAkun) {
            $parentAkun = AkunAkuntansi::create([
                'kode' => '3000',
                'nama' => 'EKUITAS',
                'kategori' => 'equity',
                'tipe' => 'header',
                'is_active' => true,
            ]);
        }

        // Buat akun Modal
        $akunModal = AkunAkuntansi::create([
            'kode' => '3100',
            'nama' => 'MODAL PEMILIK',
            'kategori' => 'equity',
            'tipe' => 'detail',
            'parent_id' => $parentAkun->id,
            'is_active' => true,
        ]);

        return $akunModal;
    }
}
