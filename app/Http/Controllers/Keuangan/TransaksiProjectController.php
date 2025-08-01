<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\TransaksiProject;
use App\Models\Project;
use App\Models\Kas;
use App\Models\RekeningBank;
use App\Models\TransaksiKas;
use App\Models\TransaksiBank;
use App\Models\JurnalUmum;
use App\Services\JournalEntryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiProjectController extends Controller
{
    protected $journalService;

    public function __construct(JournalEntryService $journalService)
    {
        $this->journalService = $journalService;
    }
    public function index(Request $request)
    {
        $query = TransaksiProject::with(['project', 'user', 'kas', 'rekeningBank']);

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->has('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->has('tanggal_mulai') && $request->has('tanggal_selesai')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        $transaksi = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $transaksi
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'tanggal' => 'required|date',
            'jenis' => 'required|in:alokasi,penggunaan,pengembalian',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'required|string',
            'no_bukti' => 'nullable|string|max:255',
            'sumber_dana_type' => 'required_if:jenis,alokasi,pengembalian|in:kas,bank',
            'kas_id' => 'required_if:sumber_dana_type,kas|nullable|exists:kas,id',
            'rekening_bank_id' => 'required_if:sumber_dana_type,bank|nullable|exists:rekening_bank,id',
            'kategori_penggunaan' => 'required_if:jenis,penggunaan|nullable|string|max:255',
        ], [
            'project_id.required' => 'Project harus dipilih',
            'project_id.exists' => 'Project tidak valid',
            'tanggal.required' => 'Tanggal harus diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'jenis.required' => 'Jenis transaksi harus dipilih',
            'jenis.in' => 'Jenis transaksi tidak valid',
            'jumlah.required' => 'Jumlah harus diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah minimal 0',
            'keterangan.required' => 'Keterangan harus diisi',
            'sumber_dana_type.required_if' => 'Sumber dana harus dipilih untuk transaksi alokasi/pengembalian',
            'kas_id.required_if' => 'Kas harus dipilih jika sumber dana adalah kas',
            'kas_id.exists' => 'Kas tidak valid',
            'rekening_bank_id.required_if' => 'Rekening bank harus dipilih jika sumber dana adalah bank',
            'rekening_bank_id.exists' => 'Rekening bank tidak valid',
            'kategori_penggunaan.required_if' => 'Kategori penggunaan harus dipilih untuk transaksi penggunaan',
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

            // Get project for validation
            $project = Project::findOrFail($request->project_id);

            // Validasi saldo untuk transaksi penggunaan
            if ($request->jenis === 'penggunaan') {
                // Hitung saldo project saat ini
                $totalAlokasi = $project->transaksi()->where('jenis', 'alokasi')->sum('nominal');
                $totalPenggunaan = $project->transaksi()->where('jenis', 'penggunaan')->sum('nominal');
                $totalPengembalian = $project->transaksi()->where('jenis', 'pengembalian')->sum('nominal');
                $saldoProject = $totalAlokasi - $totalPenggunaan + $totalPengembalian;

                if ($saldoProject < $request->jumlah) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Saldo project tidak mencukupi. Saldo tersedia: Rp ' . number_format($saldoProject, 0, ',', '.')
                    ], 422);
                }
            }

            // Validasi saldo kas/bank untuk transaksi alokasi
            if ($request->jenis === 'alokasi') {
                if ($request->sumber_dana_type === 'kas') {
                    $kas = Kas::findOrFail($request->kas_id);
                    if ($kas->saldo < $request->jumlah) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Saldo kas tidak mencukupi. Saldo tersedia: Rp ' . number_format($kas->saldo, 0, ',', '.')
                        ], 422);
                    }
                } else {
                    $rekening = RekeningBank::findOrFail($request->rekening_bank_id);
                    if ($rekening->saldo < $request->jumlah) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Saldo rekening bank tidak mencukupi. Saldo tersedia: Rp ' . number_format($rekening->saldo, 0, ',', '.')
                        ], 422);
                    }
                }
            }

            // Generate nomor bukti jika kosong
            $noBukti = $request->no_bukti;
            if (empty($noBukti)) {
                $prefix = 'TRP-' . strtoupper(substr($request->jenis, 0, 3));
                $noBukti = $prefix . '-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }

            $transaksi = TransaksiProject::create([
                'project_id' => $request->project_id,
                'tanggal' => $request->tanggal,
                'jenis' => $request->jenis,
                'nominal' => $request->jumlah,
                'keterangan' => $request->keterangan,
                'no_bukti' => $noBukti,
                'sumber_dana_type' => $request->sumber_dana_type,
                'sumber_kas_id' => $request->kas_id,
                'sumber_bank_id' => $request->rekening_bank_id,
                'kategori_penggunaan' => $request->kategori_penggunaan,
                'created_by' => Auth::id()
            ]);

            // Buat transaksi kas/bank terkait
            $this->createRelatedTransaction($transaksi, $request);

            // Buat jurnal entry
            $this->createJournalEntry($transaksi, $request);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi project berhasil dibuat',
                'data' => $transaksi->load(['project', 'user', 'kas', 'rekeningBank'])
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi project: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $transaksi = TransaksiProject::with(['project', 'user', 'kas', 'rekeningBank'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $transaksi
        ]);
    }

    public function update(Request $request, $id)
    {
        $transaksi = TransaksiProject::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'no_bukti' => 'nullable|string|max:255',
            'kategori_penggunaan' => 'nullable|string|max:255',
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

            $transaksi->update([
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'no_bukti' => $request->no_bukti,
                'kategori_penggunaan' => $request->kategori_penggunaan,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi project berhasil diupdate',
                'data' => $transaksi->load(['project', 'user', 'kas', 'rekeningBank'])
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate transaksi project: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $transaksi = TransaksiProject::findOrFail($id);
            $transaksi->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi project berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus transaksi project: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProjects()
    {
        $projects = Project::select('id', 'kode', 'nama', 'saldo', 'budget', 'status')
            ->aktif()
            ->orderBy('nama')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    public function getKasAccounts()
    {
        $kas = Kas::select('id', 'nama', 'saldo')
            ->where('is_aktif', true)
            ->orderBy('nama')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $kas
        ]);
    }

    public function getBankAccounts()
    {
        $rekening = RekeningBank::select('id', 'nama_bank', 'nomor_rekening', 'saldo')
            ->where('is_aktif', true)
            ->orderBy('nama_bank')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rekening
        ]);
    }

    public function getProjectSummary($projectId)
    {
        $project = Project::with(['transaksi' => function ($query) {
            $query->orderBy('tanggal', 'desc');
        }])->findOrFail($projectId);

        $summary = [
            'total_alokasi' => $project->transaksi()->where('jenis', 'alokasi')->sum('nominal'),
            'total_penggunaan' => $project->transaksi()->where('jenis', 'penggunaan')->sum('nominal'),
            'total_pengembalian' => $project->transaksi()->where('jenis', 'pengembalian')->sum('nominal'),
            'saldo_aktual' => $project->saldo,
            'recent_transactions' => $project->transaksi()->with(['user', 'kas', 'rekeningBank'])->take(10)->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }

    /**
     * Membuat transaksi kas/bank terkait transaksi project
     */
    private function createRelatedTransaction($transaksiProject, $request)
    {
        // Hanya untuk transaksi alokasi dan pengembalian yang mempengaruhi kas/bank
        if (in_array($request->jenis, ['alokasi', 'pengembalian'])) {

            if ($request->sumber_dana_type === 'kas' && $request->kas_id) {
                // Buat transaksi kas
                $jenis = $request->jenis === 'alokasi' ? 'keluar' : 'masuk';

                TransaksiKas::create([
                    'kas_id' => $request->kas_id,
                    'tanggal' => $request->tanggal,
                    'jenis' => $jenis,
                    'jumlah' => $request->jumlah,
                    'keterangan' => "Transaksi Project ({$request->jenis}): {$request->keterangan}",
                    'no_bukti' => $transaksiProject->no_bukti,
                    'user_id' => Auth::id(),
                ]);

                // Update saldo kas
                $kas = Kas::find($request->kas_id);
                if ($jenis === 'keluar') {
                    $kas->decrement('saldo', $request->jumlah);
                } else {
                    $kas->increment('saldo', $request->jumlah);
                }
            } elseif ($request->sumber_dana_type === 'bank' && $request->rekening_bank_id) {
                // Buat transaksi bank
                $jenis = $request->jenis === 'alokasi' ? 'keluar' : 'masuk';

                TransaksiBank::create([
                    'rekening_id' => $request->rekening_bank_id,
                    'tanggal' => $request->tanggal,
                    'jenis' => $jenis,
                    'jumlah' => $request->jumlah,
                    'keterangan' => "Transaksi Project ({$request->jenis}): {$request->keterangan}",
                    'no_referensi' => $transaksiProject->no_bukti,
                    'user_id' => Auth::id(),
                ]);

                // Update saldo rekening bank
                $rekening = RekeningBank::find($request->rekening_bank_id);
                if ($jenis === 'keluar') {
                    $rekening->decrement('saldo', $request->jumlah);
                } else {
                    $rekening->increment('saldo', $request->jumlah);
                }
            }
        }
    }

    /**
     * Membuat jurnal entry untuk transaksi project
     */
    private function createJournalEntry($transaksiProject, $request)
    {
        $project = Project::find($request->project_id);
        $description = "Transaksi Project {$project->kode}: {$request->keterangan}";

        // Akun yang akan digunakan berdasarkan data yang tersedia
        $akunProject = 37; // ID 37 - Project dalam Pengerjaan (baru dibuat)
        $akunKas = 19; // ID 19 - KAS 1
        $akunBank = 22; // ID 22 - Rekening Mandiri (atau 23 untuk BRI)
        $akunBiayaProject = 32; // ID 32 - Beban Lainnya

        // Cari akun bank yang sesuai dengan rekening_bank_id
        if ($request->rekening_bank_id && $request->sumber_dana_type === 'bank') {
            $rekening = RekeningBank::find($request->rekening_bank_id);
            if ($rekening) {
                // Cari akun berdasarkan nama bank
                if (strpos(strtolower($rekening->nama_bank), 'mandiri') !== false) {
                    $akunBank = 22; // Rekening Mandiri
                } else {
                    $akunBank = 23; // Rekening BRI (default)
                }
            }
        }

        switch ($request->jenis) {
            case 'alokasi':
                // Alokasi dana ke project
                if ($request->sumber_dana_type === 'kas') {
                    // Dr. Project WIP, Cr. Kas
                    $this->journalService->createJournalEntries([
                        [
                            'akun_id' => $akunProject,
                            'debit' => $request->jumlah,
                            'kredit' => 0
                        ],
                        [
                            'akun_id' => $akunKas,
                            'debit' => 0,
                            'kredit' => $request->jumlah
                        ]
                    ], $request->no_bukti ?: 'PROJ-' . $transaksiProject->id, $description, $request->tanggal, $transaksiProject);
                } else {
                    // Dr. Project WIP, Cr. Bank
                    $this->journalService->createJournalEntries([
                        [
                            'akun_id' => $akunProject,
                            'debit' => $request->jumlah,
                            'kredit' => 0
                        ],
                        [
                            'akun_id' => $akunBank,
                            'debit' => 0,
                            'kredit' => $request->jumlah
                        ]
                    ], $request->no_bukti ?: 'PROJ-' . $transaksiProject->id, $description, $request->tanggal, $transaksiProject);
                }
                break;

            case 'penggunaan':
                // Penggunaan dana project untuk biaya
                $this->journalService->createJournalEntries([
                    [
                        'akun_id' => $akunBiayaProject,
                        'debit' => $request->jumlah,
                        'kredit' => 0
                    ],
                    [
                        'akun_id' => $akunProject,
                        'debit' => 0,
                        'kredit' => $request->jumlah
                    ]
                ], $request->no_bukti ?: 'PROJ-' . $transaksiProject->id, $description, $request->tanggal, $transaksiProject);
                break;

            case 'pengembalian':
                // Pengembalian dana dari project
                if ($request->sumber_dana_type === 'kas') {
                    // Dr. Kas, Cr. Project WIP
                    $this->journalService->createJournalEntries([
                        [
                            'akun_id' => $akunKas,
                            'debit' => $request->jumlah,
                            'kredit' => 0
                        ],
                        [
                            'akun_id' => $akunProject,
                            'debit' => 0,
                            'kredit' => $request->jumlah
                        ]
                    ], $request->no_bukti ?: 'PROJ-' . $transaksiProject->id, $description, $request->tanggal, $transaksiProject);
                } else {
                    // Dr. Bank, Cr. Project WIP
                    $this->journalService->createJournalEntries([
                        [
                            'akun_id' => $akunBank,
                            'debit' => $request->jumlah,
                            'kredit' => 0
                        ],
                        [
                            'akun_id' => $akunProject,
                            'debit' => 0,
                            'kredit' => $request->jumlah
                        ]
                    ], $request->no_bukti ?: 'PROJ-' . $transaksiProject->id, $description, $request->tanggal, $transaksiProject);
                }
                break;
        }
    }

    /**
     * Generate PDF report for project transactions
     */
    public function generatePDF(Project $project)
    {
        try {
            // Get all transactions for the project
            $transaksi = TransaksiProject::with(['project', 'user', 'kas', 'rekeningBank'])
                ->where('project_id', $project->id)
                ->orderBy('tanggal', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            // Calculate summary data
            $totalAlokasi = $transaksi->where('jenis', 'alokasi')->sum('nominal');
            $totalPenggunaan = $transaksi->where('jenis', 'penggunaan')->sum('nominal');
            $totalPengembalian = $transaksi->where('jenis', 'pengembalian')->sum('nominal');
            $saldoTersisa = $totalAlokasi - $totalPenggunaan + $totalPengembalian;

            $data = [
                'project' => $project,
                'transaksi' => $transaksi,
                'summary' => [
                    'total_alokasi' => $totalAlokasi,
                    'total_penggunaan' => $totalPenggunaan,
                    'total_pengembalian' => $totalPengembalian,
                    'saldo_tersisa' => $saldoTersisa,
                ],
                'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
                'dicetak_oleh' => Auth::user()->name
            ];

            $pdf = Pdf::loadView('keuangan.transaksi-project.pdf', $data);
            $pdf->setPaper('A4', 'portrait');

            $filename = 'Laporan_Transaksi_Project_' . str_replace(' ', '_', $project->nama) . '_' . now()->format('Y-m-d') . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menggenerate PDF: ' . $e->getMessage());
        }
    }
}
