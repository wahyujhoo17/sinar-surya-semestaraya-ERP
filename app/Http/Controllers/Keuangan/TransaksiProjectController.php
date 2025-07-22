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
            'nominal' => 'required|numeric|min:0', // Changed from 'jumlah' to 'nominal'
            'keterangan' => 'required|string',
            'no_bukti' => 'nullable|string|max:255',
            'sumber_dana_type' => 'required|in:kas,bank',
            'sumber_kas_id' => 'required_if:sumber_dana_type,kas|nullable|exists:kas,id', // Changed from 'kas_id'
            'sumber_bank_id' => 'required_if:sumber_dana_type,bank|nullable|exists:rekening_bank,id', // Changed from 'rekening_bank_id'
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

            // Validasi saldo untuk transaksi penggunaan
            if ($request->jenis === 'penggunaan') {
                $project = Project::findOrFail($request->project_id);
                if ($project->saldo < $request->nominal) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Saldo project tidak mencukupi'
                    ], 422);
                }
            }

            // Validasi saldo kas/bank untuk transaksi alokasi
            if ($request->jenis === 'alokasi') {
                if ($request->sumber_dana_type === 'kas') {
                    $kas = Kas::findOrFail($request->sumber_kas_id);
                    if ($kas->saldo < $request->nominal) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Saldo kas tidak mencukupi'
                        ], 422);
                    }
                } else {
                    $rekening = RekeningBank::findOrFail($request->sumber_bank_id);
                    if ($rekening->saldo < $request->nominal) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Saldo rekening bank tidak mencukupi'
                        ], 422);
                    }
                }
            }

            $transaksi = TransaksiProject::create([
                'project_id' => $request->project_id,
                'tanggal' => $request->tanggal,
                'jenis' => $request->jenis,
                'nominal' => $request->nominal, // Changed from 'jumlah'
                'keterangan' => $request->keterangan,
                'no_bukti' => $request->no_bukti,
                'sumber_dana_type' => $request->sumber_dana_type,
                'sumber_kas_id' => $request->sumber_kas_id, // Changed from 'kas_id'
                'sumber_bank_id' => $request->sumber_bank_id, // Changed from 'rekening_bank_id'
                'kategori_penggunaan' => $request->kategori_penggunaan,
                'created_by' => Auth::id() // Changed from 'user_id'
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

            if ($request->sumber_dana_type === 'kas' && $request->sumber_kas_id) {
                // Buat transaksi kas
                $jenis = $request->jenis === 'alokasi' ? 'keluar' : 'masuk';

                TransaksiKas::create([
                    'kas_id' => $request->sumber_kas_id,
                    'tanggal' => $request->tanggal,
                    'jenis' => $jenis,
                    'jumlah' => $request->nominal, // Changed from 'jumlah'
                    'keterangan' => "Transaksi Project: {$request->keterangan}",
                    'no_bukti' => $request->no_bukti,
                    'user_id' => Auth::id(),
                    'related_type' => 'App\\Models\\TransaksiProject',
                    'related_id' => $transaksiProject->id
                ]);

                // Update saldo kas
                $kas = Kas::find($request->sumber_kas_id);
                if ($jenis === 'keluar') {
                    $kas->decrement('saldo', $request->nominal);
                } else {
                    $kas->increment('saldo', $request->nominal);
                }
            } elseif ($request->sumber_dana_type === 'bank' && $request->sumber_bank_id) {
                // Buat transaksi bank
                $jenis = $request->jenis === 'alokasi' ? 'keluar' : 'masuk';

                TransaksiBank::create([
                    'rekening_bank_id' => $request->sumber_bank_id,
                    'tanggal' => $request->tanggal,
                    'jenis' => $jenis,
                    'jumlah' => $request->nominal, // Changed from 'jumlah'
                    'keterangan' => "Transaksi Project: {$request->keterangan}",
                    'no_bukti' => $request->no_bukti,
                    'user_id' => Auth::id(),
                    'related_type' => 'App\\Models\\TransaksiProject',
                    'related_id' => $transaksiProject->id
                ]);

                // Update saldo rekening bank
                $rekening = RekeningBank::find($request->sumber_bank_id);
                if ($jenis === 'keluar') {
                    $rekening->decrement('saldo', $request->nominal);
                } else {
                    $rekening->increment('saldo', $request->nominal);
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
                            'debit' => $request->nominal,
                            'kredit' => 0
                        ],
                        [
                            'akun_id' => $akunKas,
                            'debit' => 0,
                            'kredit' => $request->nominal
                        ]
                    ], $request->no_bukti ?: 'PROJ-' . $transaksiProject->id, $description, $request->tanggal, $transaksiProject);
                } else {
                    // Dr. Project WIP, Cr. Bank
                    $this->journalService->createJournalEntries([
                        [
                            'akun_id' => $akunProject,
                            'debit' => $request->nominal,
                            'kredit' => 0
                        ],
                        [
                            'akun_id' => $akunBank,
                            'debit' => 0,
                            'kredit' => $request->nominal
                        ]
                    ], $request->no_bukti ?: 'PROJ-' . $transaksiProject->id, $description, $request->tanggal, $transaksiProject);
                }
                break;

            case 'penggunaan':
                // Penggunaan dana project untuk biaya
                $this->journalService->createJournalEntries([
                    [
                        'akun_id' => $akunBiayaProject,
                        'debit' => $request->nominal,
                        'kredit' => 0
                    ],
                    [
                        'akun_id' => $akunProject,
                        'debit' => 0,
                        'kredit' => $request->nominal
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
                            'debit' => $request->nominal,
                            'kredit' => 0
                        ],
                        [
                            'akun_id' => $akunProject,
                            'debit' => 0,
                            'kredit' => $request->nominal
                        ]
                    ], $request->no_bukti ?: 'PROJ-' . $transaksiProject->id, $description, $request->tanggal, $transaksiProject);
                } else {
                    // Dr. Bank, Cr. Project WIP
                    $this->journalService->createJournalEntries([
                        [
                            'akun_id' => $akunBank,
                            'debit' => $request->nominal,
                            'kredit' => 0
                        ],
                        [
                            'akun_id' => $akunProject,
                            'debit' => 0,
                            'kredit' => $request->nominal
                        ]
                    ], $request->no_bukti ?: 'PROJ-' . $transaksiProject->id, $description, $request->tanggal, $transaksiProject);
                }
                break;
        }
    }
}
