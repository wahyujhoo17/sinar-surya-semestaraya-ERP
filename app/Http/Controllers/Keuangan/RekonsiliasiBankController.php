<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\RekeningBank;
use App\Models\TransaksiBank;
use App\Models\RekonsiliasiBankHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RekonsiliasiBankController extends Controller
{
    /**
     * Menampilkan halaman rekonsiliasi bank
     */
    public function index()
    {
        // Ambil daftar rekening bank aktif
        $rekeningBanks = RekeningBank::where('is_aktif', true)
            ->where('is_perusahaan', true)
            ->get()
            ->map(function ($rekening) {
                return [
                    'id' => $rekening->id,
                    'value' => $rekening->id,
                    'label' => $rekening->nama_bank . ' - ' . $rekening->nomor_rekening . ' (' . $rekening->atas_nama . ')'
                ];
            });

        // Breadcrumbs untuk navigasi
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Keuangan', 'url' => '#'],
            ['name' => 'Rekonsiliasi Bank', 'url' => null]
        ];

        $currentPage = 'Rekonsiliasi Bank';

        return view('keuangan.rekonsiliasi.index', compact('breadcrumbs', 'currentPage', 'rekeningBanks'));
    }

    /**
     * Mengambil data transaksi ERP untuk rekening tertentu
     */
    public function getErpTransactions(Request $request)
    {
        try {
            $rekeningId = $request->rekening_id;
            $periode = $request->periode; // Format: YYYY-MM

            if (!$rekeningId || !$periode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter tidak lengkap'
                ], 400);
            }

            // Parse periode
            $startDate = Carbon::createFromFormat('Y-m', $periode)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $periode)->endOfMonth();

            // Get rekening information
            $rekening = RekeningBank::findOrFail($rekeningId);

            // Ambil transaksi ERP untuk periode tersebut
            $transaksi = TransaksiBank::where('rekening_id', $rekeningId)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->orderBy('tanggal', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'date' => $item->tanggal,
                        'description' => $item->keterangan,
                        'amount' => $item->jumlah,
                        'type' => $item->jenis === 'masuk' ? 'credit' : 'debit',
                        'reference' => $item->no_referensi ?? 'REF' . $item->id,
                        'matched' => false
                    ];
                });

            // Calculate balances
            $saldoAwal = $rekening->saldo_awal ?? 0;
            $totalMasuk = $transaksi->where('type', 'credit')->sum('amount');
            $totalKeluar = $transaksi->where('type', 'debit')->sum('amount');
            $saldoAkhir = $saldoAwal + $totalMasuk - $totalKeluar;

            return response()->json([
                'success' => true,
                'data' => [
                    'rekening' => [
                        'id' => $rekening->id,
                        'nama_bank' => $rekening->nama_bank,
                        'nomor_rekening' => $rekening->nomor_rekening,
                        'nama_pemilik' => $rekening->nama_pemilik
                    ],
                    'balance' => [
                        'opening' => $saldoAwal,
                        'closing' => $saldoAkhir,
                        'total_credit' => $totalMasuk,
                        'total_debit' => $totalKeluar
                    ],
                    'transactions' => $transaksi,
                    'periode' => [
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d'),
                        'period' => $periode
                    ]
                ],
                'message' => 'Data transaksi ERP berhasil dimuat'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getErpTransactions: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil data saldo rekening
     */
    public function getRekeningBalance(Request $request)
    {
        $rekeningId = $request->rekening_id;

        if (!$rekeningId) {
            return response()->json(['error' => 'Rekening ID tidak ditemukan'], 400);
        }

        $rekening = RekeningBank::find($rekeningId);

        if (!$rekening) {
            return response()->json(['error' => 'Rekening tidak ditemukan'], 404);
        }

        return response()->json([
            'saldo' => $rekening->saldo,
            'nama_bank' => $rekening->nama_bank,
            'nomor_rekening' => $rekening->nomor_rekening
        ]);
    }

    /**
     * Menyimpan hasil rekonsiliasi
     */
    public function saveReconciliation(Request $request)
    {
        $request->validate([
            'rekening_id' => 'required|exists:rekening_bank,id',
            'periode' => 'required|string',
            'tahun' => 'required|integer',
            'bulan' => 'required|string',
            'erp_balance' => 'required|numeric',
            'bank_balance' => 'required|numeric',
            'difference' => 'required|numeric',
            'status' => 'required|string|in:balanced,pending',
            'matched_transactions' => 'sometimes|array',
            'matched_transactions.*.erp_transaction_id' => 'required_with:matched_transactions|integer',
            'matched_transactions.*.bank_transaction_id' => 'nullable|string',
            'matched_transactions.*.amount' => 'required_with:matched_transactions|numeric',
            'unmatched_erp' => 'sometimes|array',
            'unmatched_bank' => 'sometimes|array',
            'summary' => 'required|array',
            'reconciled_by' => 'required|string',
            'file_uploaded' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Generate unique reconciliation ID
            $reconciliationId = 'REC-' . date('Ymd') . '-' . str_pad(
                RekonsiliasiBankHistory::whereDate('created_at', today())->count() + 1,
                3,
                '0',
                STR_PAD_LEFT
            );

            // Parse periode data
            if (str_contains($request->periode, '-')) {
                // If periode contains "-", it's in YYYY-MM format, split it
                [$year, $month] = explode('-', $request->periode);
                $periodeYear = (int) $year;
                $periodeMonth = str_pad($month, 2, '0', STR_PAD_LEFT);
            } else {
                // If periode is just month number, use request tahun
                $periodeYear = $request->tahun;
                $periodeMonth = str_pad($request->periode, 2, '0', STR_PAD_LEFT);
            }

            // Parse periode to date format
            $periodeDate = Carbon::createFromFormat('Y-m', $periodeYear . '-' . $periodeMonth)->startOfMonth();

            // Process matched transactions for better data structure
            $processedMatchedTransactions = collect($request->matched_transactions ?? [])->map(function ($match) {
                return [
                    'erp_transaction_id' => $match['erp_transaction_id'],
                    'bank_transaction_id' => $match['bank_transaction_id'] ?? null,
                    'bank_transaction_ref' => $match['bank_transaction_ref'] ?? null,
                    'amount' => $match['amount'],
                    'erp_description' => $match['erp_description'] ?? '',
                    'bank_description' => $match['bank_description'] ?? '',
                    'match_type' => $match['match_type'] ?? 'auto',
                    'match_confidence' => $match['match_confidence'] ?? 'high',
                    'match_date' => $match['match_date'] ?? now()->toISOString()
                ];
            })->toArray();

            // Simpan ke history rekonsiliasi dengan data lengkap
            $reconciliation = RekonsiliasiBankHistory::create([
                'reconciliation_id' => $reconciliationId,
                'rekening_bank_id' => $request->rekening_id,
                'periode' => $periodeDate->format('Y-m-d'),
                'tahun' => $periodeYear,
                'bulan' => $periodeMonth,
                'erp_balance' => $request->erp_balance,
                'bank_balance' => $request->bank_balance,
                'difference' => $request->difference,
                'status' => $request->status,
                'matched_transactions' => $processedMatchedTransactions,
                'unmatched_erp_transactions' => $request->unmatched_erp ?? [],
                'unmatched_bank_transactions' => $request->unmatched_bank ?? [],
                'summary_statistics' => $request->summary ?? [],
                'reconciled_by' => $request->reconciled_by,
                'reconciled_at' => now(),
                'file_uploaded' => $request->file_uploaded,
                'notes' => $request->notes ?? null,
                'created_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log untuk debugging
            Log::info('Rekonsiliasi Bank Disimpan:', [
                'id' => $reconciliation->id,
                'reconciliation_id' => $reconciliationId,
                'rekening_id' => $request->rekening_id,
                'periode' => $periodeDate->format('Y-m'),
                'status' => $request->status,
                'difference' => $request->difference,
                'matched_count' => count($processedMatchedTransactions),
                'unmatched_erp_count' => count($request->unmatched_erp ?? []),
                'unmatched_bank_count' => count($request->unmatched_bank ?? []),
                'matched_transactions_sample' => array_slice($processedMatchedTransactions, 0, 3),
                'unmatched_erp_sample' => array_slice($request->unmatched_erp ?? [], 0, 3),
                'unmatched_bank_sample' => array_slice($request->unmatched_bank ?? [], 0, 3)
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rekonsiliasi berhasil disimpan',
                'reconciliation_id' => $reconciliationId,
                'status' => $request->status,
                'data' => [
                    'id' => $reconciliation->id,
                    'reconciliation_id' => $reconciliationId,
                    'periode' => $periodeDate->format('Y-m'),
                    'status' => $request->status,
                    'difference' => $request->difference,
                    'matched_count' => count($processedMatchedTransactions),
                    'unmatched_erp_count' => count($request->unmatched_erp ?? []),
                    'unmatched_bank_count' => count($request->unmatched_bank ?? []),
                    'created_at' => $reconciliation->created_at->format('Y-m-d H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error saving reconciliation:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan rekonsiliasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil riwayat rekonsiliasi
     */
    public function getReconciliationHistory(Request $request)
    {
        try {
            $query = RekonsiliasiBankHistory::with(['rekeningBank', 'user'])
                ->orderBy('created_at', 'desc');

            // Filter berdasarkan rekening
            if ($request->rekening_id) {
                $query->where('rekening_bank_id', $request->rekening_id);
            }

            // Filter berdasarkan periode
            if ($request->periode) {
                $query->where('bulan', $request->periode);
            }

            // Filter berdasarkan status
            if ($request->status) {
                $query->where('status', $request->status);
            }

            $reconciliations = $query->paginate($request->per_page ?? 15);

            // Transformasi data untuk frontend
            $reconciliations->getCollection()->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'reconciliation_id' => $item->reconciliation_id,
                    'rekening_bank' => $item->rekeningBank ? [
                        'nama_bank' => $item->rekeningBank->nama_bank,
                        'nomor_rekening' => $item->rekeningBank->nomor_rekening,
                    ] : null,
                    'periode' => $item->tahun . '-' . str_pad($item->bulan, 2, '0', STR_PAD_LEFT),
                    'erp_balance' => $item->erp_balance,
                    'bank_balance' => $item->bank_balance,
                    'difference' => $item->bank_balance - $item->erp_balance,
                    'status' => $item->status,
                    'summary' => $item->summary_statistics,
                    'reconciled_by' => $item->reconciled_by,
                    'reconciled_at' => $item->reconciled_at,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $reconciliations->items(),
                'pagination' => [
                    'current_page' => $reconciliations->currentPage(),
                    'last_page' => $reconciliations->lastPage(),
                    'per_page' => $reconciliations->perPage(),
                    'total' => $reconciliations->total(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting reconciliation history:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat riwayat rekonsiliasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail rekonsiliasi
     */
    public function show($id)
    {
        $reconciliation = RekonsiliasiBankHistory::with(['rekeningBank', 'user'])->findOrFail($id);

        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Keuangan', 'url' => '#'],
            ['name' => 'Rekonsiliasi Bank', 'url' => route('keuangan.rekonsiliasi.index')],
            ['name' => 'Detail', 'url' => null]
        ];

        return view('keuangan.rekonsiliasi.detail', compact('reconciliation', 'breadcrumbs'));
    }

    /**
     * Auto matching transaksi berdasarkan jumlah, tanggal, dan referensi
     */
    public function autoMatch(Request $request)
    {
        $erpTransactions = $request->erp_transactions ?? [];
        $bankTransactions = $request->bank_transactions ?? [];

        $matched = [];

        foreach ($erpTransactions as $erpIndex => $erpTx) {
            foreach ($bankTransactions as $bankIndex => $bankTx) {
                // Skip jika sudah matched
                if (isset($matched['erp'][$erpIndex]) || isset($matched['bank'][$bankIndex])) {
                    continue;
                }

                // Cek kecocokan berdasarkan jumlah, tanggal, dan tipe
                if (
                    $erpTx['amount'] == $bankTx['amount'] &&
                    $erpTx['date'] == $bankTx['date'] &&
                    $erpTx['type'] == $bankTx['type']
                ) {

                    $matched['erp'][$erpIndex] = true;
                    $matched['bank'][$bankIndex] = true;
                    break;
                }
            }
        }

        return response()->json([
            'success' => true,
            'matched' => $matched,
            'message' => 'Auto matching completed'
        ]);
    }

    /**
     * Upload dan proses bank statement
     */
    public function processStatement(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,xlsx,csv|max:10240', // Max 10MB
            'rekening_id' => 'required|exists:rekening_banks,id'
        ]);

        try {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('bank_statements', $fileName, 'public');

            // TODO: Implement file parsing logic based on file type
            // For now, return sample data
            $sampleTransactions = [
                [
                    'id' => 'bank_1',
                    'date' => '2024-01-15',
                    'description' => 'TRANSFER GAJI',
                    'amount' => 5000000,
                    'type' => 'debit',
                    'reference' => 'TRF001',
                    'matched' => false
                ],
                [
                    'id' => 'bank_2',
                    'date' => '2024-01-14',
                    'description' => 'INCOMING TRANSFER',
                    'amount' => 2500000,
                    'type' => 'credit',
                    'reference' => 'INC001',
                    'matched' => false
                ]
            ];

            return response()->json([
                'success' => true,
                'message' => 'Bank statement processed successfully',
                'transactions' => $sampleTransactions,
                'file_path' => $filePath
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process bank statement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil daftar rekonsiliasi dengan pagination dan filter
     */
    public function getReconciliationList(Request $request)
    {
        $query = RekonsiliasiBankHistory::with(['rekeningBank', 'user', 'approver']);

        // Filter berdasarkan rekening
        if ($request->rekening_id) {
            $query->where('rekening_bank_id', $request->rekening_id);
        }

        // Filter berdasarkan status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan periode
        if ($request->periode_start && $request->periode_end) {
            $query->whereBetween('periode', [$request->periode_start, $request->periode_end]);
        }

        // Sort dan pagination
        $reconciliations = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $reconciliations->map(function ($item) {
                return [
                    'id' => $item->id,
                    'reconciliation_id' => $item->reconciliation_id,
                    'rekening' => [
                        'id' => $item->rekeningBank->id,
                        'nama_bank' => $item->rekeningBank->nama_bank,
                        'nomor_rekening' => $item->rekeningBank->nomor_rekening,
                        'atas_nama' => $item->rekeningBank->atas_nama
                    ],
                    'periode' => Carbon::parse($item->periode)->format('F Y'),
                    'periode_raw' => $item->periode,
                    'erp_balance' => $item->erp_balance,
                    'bank_balance' => $item->bank_balance,
                    'difference' => $item->difference,
                    'status' => $item->status,
                    'status_badge' => $this->getStatusBadge($item->status),
                    'is_balanced' => $item->difference == 0,
                    'unmatched_count' => $item->unmatched_count,
                    'reconciled_by' => $item->reconciled_by,
                    'reconciled_at' => $item->reconciled_at ? $item->reconciled_at->format('Y-m-d H:i:s') : null,
                    'created_by' => $item->user->name ?? 'System',
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                    'approved_by' => $item->approver->name ?? null,
                    'approved_at' => $item->approved_at ? $item->approved_at->format('Y-m-d H:i:s') : null,
                    'file_uploaded' => $item->file_uploaded,
                    'notes' => $item->notes
                ];
            }),
            'pagination' => [
                'current_page' => $reconciliations->currentPage(),
                'last_page' => $reconciliations->lastPage(),
                'per_page' => $reconciliations->perPage(),
                'total' => $reconciliations->total(),
                'from' => $reconciliations->firstItem(),
                'to' => $reconciliations->lastItem()
            ]
        ]);
    }

    /**
     * Approve rekonsiliasi
     */
    public function approveReconciliation(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            $reconciliation = RekonsiliasiBankHistory::findOrFail($id);

            // Cek apakah user memiliki hak untuk approve
            // Tambahkan logic authorization sesuai kebutuhan

            $reconciliation->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'notes' => $request->notes ?
                    ($reconciliation->notes ? $reconciliation->notes . "\n\n" . $request->notes : $request->notes) :
                    $reconciliation->notes
            ]);

            Log::info('Rekonsiliasi Bank Disetujui:', [
                'reconciliation_id' => $reconciliation->reconciliation_id,
                'approved_by' => Auth::user()->name,
                'approved_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rekonsiliasi berhasil disetujui',
                'data' => [
                    'status' => $reconciliation->status,
                    'approved_by' => Auth::user()->name,
                    'approved_at' => $reconciliation->approved_at->format('Y-m-d H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error approving reconciliation:', [
                'id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->name
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui rekonsiliasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject rekonsiliasi
     */
    public function rejectReconciliation(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000'
        ]);

        try {
            $reconciliation = RekonsiliasiBankHistory::findOrFail($id);

            $reconciliation->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'notes' => $reconciliation->notes ?
                    $reconciliation->notes . "\n\nDITOLAK: " . $request->reason :
                    "DITOLAK: " . $request->reason
            ]);

            Log::info('Rekonsiliasi Bank Ditolak:', [
                'reconciliation_id' => $reconciliation->reconciliation_id,
                'rejected_by' => Auth::user()->name,
                'reason' => $request->reason
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rekonsiliasi berhasil ditolak',
                'data' => [
                    'status' => $reconciliation->status,
                    'rejected_by' => Auth::user()->name,
                    'reason' => $request->reason
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak rekonsiliasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export rekonsiliasi ke Excel/PDF
     */
    public function exportReconciliation(Request $request)
    {
        try {
            $data = $request->all();

            // Validate required data
            if (!isset($data['rekening_id']) || !isset($data['periode'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rekening ID dan periode diperlukan untuk export'
                ], 400);
            }

            // Get account information
            $rekening = RekeningBank::find($data['rekening_id']);
            if (!$rekening) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rekening tidak ditemukan'
                ], 404);
            }

            // Prepare export data
            $exportData = [
                'account_info' => [
                    'nama_bank' => $rekening->nama_bank,
                    'nomor_rekening' => $rekening->nomor_rekening,
                    'atas_nama' => $rekening->atas_nama,
                ],
                'periode' => $data['periode'] ?? date('Y-m'),
                'balances' => $data['balances'] ?? [],
                'transactions' => $data['transactions'] ?? [],
                'summary' => $data['summary'] ?? [],
                'generated_at' => now(),
                'generated_by' => Auth::user()->name ?? 'System'
            ];

            // Create Excel export using simple array-to-CSV approach
            $csvData = $this->generateReconciliationCSV($exportData);

            // Return CSV file
            $fileName = 'Rekonsiliasi_' . $rekening->nama_bank . '_' . $data['periode'] . '_' . date('YmdHis') . '.csv';

            return response($csvData)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->header('Pragma', 'public');
        } catch (\Exception $e) {
            Log::error('Error exporting reconciliation:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengekspor data rekonsiliasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate CSV content for reconciliation export
     */
    private function generateReconciliationCSV($data)
    {
        $csv = [];

        // Header information
        $csv[] = ['LAPORAN REKONSILIASI BANK'];
        $csv[] = [''];
        $csv[] = ['Bank', $data['account_info']['nama_bank'] ?? 'N/A'];
        $csv[] = ['Nomor Rekening', $data['account_info']['nomor_rekening'] ?? 'N/A'];
        $csv[] = ['Atas Nama', $data['account_info']['atas_nama'] ?? 'N/A'];
        $csv[] = ['Periode', $data['periode']];
        $csv[] = ['Dibuat Pada', $data['generated_at']->format('d/m/Y H:i:s')];
        $csv[] = ['Dibuat Oleh', $data['generated_by']];
        $csv[] = [''];

        // Summary section
        $csv[] = ['RINGKASAN REKONSILIASI'];
        $csv[] = [''];
        if (isset($data['balances'])) {
            $csv[] = ['Saldo ERP', 'Rp ' . number_format($data['balances']['erp_balance'] ?? 0, 0, ',', '.')];
            $csv[] = ['Saldo Bank Statement', 'Rp ' . number_format($data['balances']['bank_balance'] ?? 0, 0, ',', '.')];
            $csv[] = ['Selisih', 'Rp ' . number_format(abs($data['balances']['difference'] ?? 0), 0, ',', '.')];
        }

        if (isset($data['summary'])) {
            $csv[] = ['Total Transaksi ERP', $data['summary']['total_erp_transactions'] ?? 0];
            $csv[] = ['Total Transaksi Bank', $data['summary']['total_bank_transactions'] ?? 0];
            $csv[] = ['Transaksi Cocok', $data['summary']['matched_count'] ?? 0];
            $csv[] = ['Persentase Kecocokan', ($data['summary']['reconciliation_percentage'] ?? 0) . '%'];
        }
        $csv[] = [''];

        // ERP Transactions
        if (isset($data['transactions']['erp']) && is_array($data['transactions']['erp'])) {
            $csv[] = ['TRANSAKSI ERP'];
            $csv[] = ['Tanggal', 'Referensi', 'Deskripsi', 'Jumlah', 'Status'];

            foreach ($data['transactions']['erp'] as $transaction) {
                $csv[] = [
                    $transaction['date'] ?? '',
                    $transaction['reference'] ?? '',
                    $transaction['description'] ?? '',
                    'Rp ' . number_format($transaction['amount'] ?? 0, 0, ',', '.'),
                    ($transaction['matched'] ?? false) ? 'Cocok' : 'Belum Cocok'
                ];
            }
            $csv[] = [''];
        }

        // Bank Transactions
        if (isset($data['transactions']['bank']) && is_array($data['transactions']['bank'])) {
            $csv[] = ['TRANSAKSI BANK'];
            $csv[] = ['Tanggal', 'Referensi', 'Deskripsi', 'Jumlah', 'Status'];

            foreach ($data['transactions']['bank'] as $transaction) {
                $csv[] = [
                    $transaction['date'] ?? '',
                    $transaction['reference'] ?? '',
                    $transaction['description'] ?? '',
                    'Rp ' . number_format($transaction['amount'] ?? 0, 0, ',', '.'),
                    ($transaction['matched'] ?? false) ? 'Cocok' : 'Belum Cocok'
                ];
            }
            $csv[] = [''];
        }

        // Unmatched ERP Transactions
        if (isset($data['transactions']['unmatched_erp']) && is_array($data['transactions']['unmatched_erp'])) {
            $csv[] = ['TRANSAKSI ERP BELUM COCOK'];
            $csv[] = ['Tanggal', 'Referensi', 'Deskripsi', 'Jumlah', 'Alasan'];

            foreach ($data['transactions']['unmatched_erp'] as $transaction) {
                $csv[] = [
                    $transaction['date'] ?? '',
                    $transaction['reference'] ?? '',
                    $transaction['description'] ?? '',
                    'Rp ' . number_format($transaction['amount'] ?? 0, 0, ',', '.'),
                    'Tidak ditemukan di bank statement'
                ];
            }
            $csv[] = [''];
        }

        // Unmatched Bank Transactions
        if (isset($data['transactions']['unmatched_bank']) && is_array($data['transactions']['unmatched_bank'])) {
            $csv[] = ['TRANSAKSI BANK BELUM COCOK'];
            $csv[] = ['Tanggal', 'Referensi', 'Deskripsi', 'Jumlah', 'Alasan'];

            foreach ($data['transactions']['unmatched_bank'] as $transaction) {
                $csv[] = [
                    $transaction['date'] ?? '',
                    $transaction['reference'] ?? '',
                    $transaction['description'] ?? '',
                    'Rp ' . number_format($transaction['amount'] ?? 0, 0, ',', '.'),
                    'Tidak ditemukan di sistem ERP'
                ];
            }
        }

        // Convert array to CSV string
        $output = '';
        foreach ($csv as $row) {
            $output .= '"' . implode('","', $row) . '"' . "\n";
        }

        return $output;
    }

    /**
     * Helper method untuk mendapatkan status badge
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Pending'],
            'balanced' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Seimbang'],
            'reviewed' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Reviewed'],
            'approved' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Approved'],
            'rejected' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejected']
        ];

        return $badges[$status] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Unknown'];
    }

    /**
     * Upload and parse bank statement file
     */
    public function uploadBankStatement(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,xlsx,xls,csv|max:10240', // Max 10MB
            'rekening_id' => 'required|exists:rekening_bank,id',
            'periode' => 'required|string' // Format: YYYY-MM
        ]);

        try {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('bank_statements', $fileName, 'public');

            Log::info('Bank statement file uploaded:', [
                'file_name' => $fileName,
                'file_path' => $filePath,
                'rekening_id' => $request->rekening_id,
                'periode' => $request->periode
            ]);

            // Parse the file based on its type
            $transactions = $this->parseStatementFile($file);

            return response()->json([
                'success' => true,
                'message' => 'Bank statement uploaded and parsed successfully',
                'data' => [
                    'file_path' => $filePath,
                    'transactions' => $transactions,
                    'total_transactions' => count($transactions),
                    'total_debit' => collect($transactions)->where('type', 'debit')->sum('amount'),
                    'total_credit' => collect($transactions)->where('type', 'credit')->sum('amount')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading bank statement:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload bank statement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Parse statement file based on file type
     */
    private function parseStatementFile($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());

        switch ($extension) {
            case 'csv':
                return $this->processCsvStatement($file);
            case 'xlsx':
            case 'xls':
                return $this->processExcelStatement($file);
            case 'pdf':
                return $this->processPdfStatement($file);
            default:
                throw new \Exception('Unsupported file format: ' . $extension);
        }
    }

    /**
     * Add manual bank transaction
     */
    public function addManualBankTransaction(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|not_in:0',
            'type' => 'required|string|in:credit,debit',
            'reference' => 'nullable|string|max:100'
        ]);

        try {
            // Generate unique ID for manual transaction
            $transactionId = 'MANUAL_' . time() . '_' . rand(1000, 9999);

            $transaction = [
                'id' => $transactionId,
                'date' => $request->date,
                'description' => $request->description,
                'amount' => abs($request->amount), // Always positive, type determines debit/credit
                'type' => $request->type,
                'reference' => $request->reference ?? $transactionId,
                'matched' => false,
                'isManual' => true
            ];

            return response()->json([
                'success' => true,
                'message' => 'Manual bank transaction added successfully',
                'transaction' => $transaction
            ]);
        } catch (\Exception $e) {
            Log::error('Error adding manual bank transaction:', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to add manual transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enhanced auto-matching with better algorithms
     */
    public function enhancedAutoMatch(Request $request)
    {
        $erpTransactions = $request->erp_transactions ?? [];
        $bankTransactions = $request->bank_transactions ?? [];

        $matched = [];
        $suggestions = [];

        foreach ($erpTransactions as $erpIndex => $erpTx) {
            if (isset($matched['erp'][$erpIndex])) {
                continue;
            }

            $bestMatch = null;
            $bestScore = 0;

            foreach ($bankTransactions as $bankIndex => $bankTx) {
                if (isset($matched['bank'][$bankIndex])) {
                    continue;
                }

                $score = $this->calculateMatchScore($erpTx, $bankTx);

                if ($score > $bestScore && $score >= 0.8) { // 80% threshold
                    $bestScore = $score;
                    $bestMatch = $bankIndex;
                }
            }

            if ($bestMatch !== null) {
                $matched['erp'][$erpIndex] = true;
                $matched['bank'][$bestMatch] = true;

                $suggestions[] = [
                    'erp_index' => $erpIndex,
                    'bank_index' => $bestMatch,
                    'score' => $bestScore,
                    'confidence' => $this->getConfidenceLevel($bestScore)
                ];
            }
        }

        return response()->json([
            'success' => true,
            'matched' => $matched,
            'suggestions' => $suggestions,
            'total_matched' => count($suggestions),
            'message' => 'Enhanced auto-matching completed'
        ]);
    }

    /**
     * Calculate match score between ERP and bank transactions
     */
    private function calculateMatchScore($erpTx, $bankTx)
    {
        $score = 0;
        $totalFactors = 0;

        // Amount matching (most important factor - 40%)
        if (abs($erpTx['amount']) == abs($bankTx['amount'])) {
            $score += 0.4;
        } elseif (abs(abs($erpTx['amount']) - abs($bankTx['amount'])) <= 1) {
            $score += 0.3; // Close match (rounding differences)
        }
        $totalFactors += 0.4;

        // Date matching (30%)
        $erpDate = Carbon::parse($erpTx['date']);
        $bankDate = Carbon::parse($bankTx['date']);
        $daysDiff = abs($erpDate->diffInDays($bankDate));

        if ($daysDiff == 0) {
            $score += 0.3;
        } elseif ($daysDiff <= 1) {
            $score += 0.2; // 1 day difference
        } elseif ($daysDiff <= 3) {
            $score += 0.1; // 2-3 days difference
        }
        $totalFactors += 0.3;

        // Type matching (20%)
        if ($erpTx['type'] == $bankTx['type']) {
            $score += 0.2;
        }
        $totalFactors += 0.2;

        // Reference/description similarity (10%)
        $erpRef = strtolower($erpTx['reference'] ?? $erpTx['description'] ?? '');
        $bankRef = strtolower($bankTx['reference'] ?? $bankTx['description'] ?? '');

        if ($erpRef && $bankRef) {
            $similarity = 0;
            similar_text($erpRef, $bankRef, $similarity);
            $score += ($similarity / 100) * 0.1;
        }
        $totalFactors += 0.1;

        return $totalFactors > 0 ? $score / $totalFactors : 0;
    }

    /**
     * Get confidence level based on match score
     */
    private function getConfidenceLevel($score)
    {
        if ($score >= 0.95) {
            return 'very_high';
        } elseif ($score >= 0.85) {
            return 'high';
        } elseif ($score >= 0.7) {
            return 'medium';
        } elseif ($score >= 0.5) {
            return 'low';
        } else {
            return 'very_low';
        }
    }

    /**
     * Bulk match transactions
     */
    public function bulkMatchTransactions(Request $request)
    {
        $request->validate([
            'matches' => 'required|array',
            'matches.*.erp_index' => 'required|integer',
            'matches.*.bank_index' => 'required|integer'
        ]);

        try {
            $matches = $request->matches;
            $matched = [
                'erp' => [],
                'bank' => []
            ];

            foreach ($matches as $match) {
                $matched['erp'][$match['erp_index']] = true;
                $matched['bank'][$match['bank_index']] = true;
            }

            return response()->json([
                'success' => true,
                'matched' => $matched,
                'total_matches' => count($matches),
                'message' => 'Bulk matching completed successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in bulk matching:', [
                'error' => $e->getMessage(),
                'matches' => $request->matches
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to complete bulk matching: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper methods

    /**
     * Get opening balance for the specified period
     */
    private function getSaldoAwal($rekeningId, $tahun, $bulan)
    {
        // Calculate opening balance for the period
        // This gets all transactions before the specified period
        $previousTransactions = TransaksiBank::where('rekening_bank_id', $rekeningId)
            ->where(function ($query) use ($tahun, $bulan) {
                $query->whereYear('tanggal_transaksi', '<', $tahun)
                    ->orWhere(function ($q) use ($tahun, $bulan) {
                        $q->whereYear('tanggal_transaksi', $tahun)
                            ->whereMonth('tanggal_transaksi', '<', $bulan);
                    });
            })
            ->get();

        $totalDebet = $previousTransactions->where('jenis_transaksi', 'debet')->sum('jumlah');
        $totalKredit = $previousTransactions->where('jenis_transaksi', 'kredit')->sum('jumlah');

        return $totalDebet - $totalKredit;
    }

    /**
     * Process CSV statement file
     */
    private function processCsvStatement($file)
    {
        $transactions = [];
        $handle = fopen($file->getPathname(), 'r');

        if ($handle !== false) {
            // Try to detect CSV format by reading first few lines
            $header = fgetcsv($handle); // Read header row
            $sampleRow = fgetcsv($handle); // Read first data row

            // Reset file pointer to beginning
            fseek($handle, 0);
            fgetcsv($handle); // Skip header again

            // Detect bank format based on header structure
            $format = $this->detectBankFormat($header, $sampleRow);

            Log::info('CSV Bank Statement Processing:', [
                'header' => $header,
                'sample_row' => $sampleRow,
                'detected_format' => $format
            ]);

            $rowNumber = 1;
            while (($data = fgetcsv($handle)) !== false) {
                $rowNumber++;

                try {
                    $transaction = $this->parseCsvRow($data, $format, $rowNumber);
                    if ($transaction) {
                        $transactions[] = $transaction;
                    }
                } catch (\Exception $e) {
                    Log::warning("Error parsing CSV row {$rowNumber}: " . $e->getMessage(), [
                        'row_data' => $data
                    ]);
                    // Continue processing other rows
                }
            }

            fclose($handle);
        }

        return $transactions;
    }

    /**
     * Process Excel statement file
     */
    private function processExcelStatement($file)
    {
        // For now, return empty array
        // In production, you would use a library like PhpSpreadsheet
        // to read Excel files and extract transaction data

        // Example implementation would be:
        // $reader = IOFactory::createReader('Xlsx');
        // $spreadsheet = $reader->load($file->getPathname());
        // $worksheet = $spreadsheet->getActiveSheet();
        // ... process rows ...

        return [];
    }

    /**
     * Process PDF statement file
     */
    private function processPdfStatement($file)
    {
        // For now, return empty array
        // In production, you would use a PDF parsing library
        // to extract transaction data from PDF bank statements

        // Example implementation would be:
        // $parser = new PDFParser();
        // $pdf = $parser->parseFile($file->getPathname());
        // $text = $pdf->getText();
        // ... parse transaction data from text ...

        return [];
    }

    /**
     * Detect bank format based on CSV header
     */
    private function detectBankFormat($header, $sampleRow)
    {
        // Convert to lowercase for comparison
        $headerStr = strtolower(implode(',', $header ?? []));

        // Common bank formats
        if (strpos($headerStr, 'tanggal') !== false && strpos($headerStr, 'keterangan') !== false) {
            return 'bca'; // BCA format
        } elseif (strpos($headerStr, 'tgl') !== false && strpos($headerStr, 'uraian') !== false) {
            return 'mandiri'; // Bank Mandiri format
        } elseif (strpos($headerStr, 'date') !== false && strpos($headerStr, 'description') !== false) {
            return 'bni'; // BNI format (English)
        } elseif (strpos($headerStr, 'posting') !== false && strpos($headerStr, 'desc') !== false) {
            return 'bri'; // BRI format
        }

        // Default generic format
        return 'generic';
    }

    /**
     * Parse CSV row based on detected format
     */
    private function parseCsvRow($data, $format, $rowNumber)
    {
        switch ($format) {
            case 'bca':
                return $this->parseBcaFormat($data, $rowNumber);
            case 'mandiri':
                return $this->parseMandiriFormat($data, $rowNumber);
            case 'bni':
                return $this->parseBniFormat($data, $rowNumber);
            case 'bri':
                return $this->parseBriFormat($data, $rowNumber);
            default:
                return $this->parseGenericFormat($data, $rowNumber);
        }
    }

    /**
     * Parse BCA format CSV
     */
    private function parseBcaFormat($data, $rowNumber)
    {
        // BCA format: Tanggal, Keterangan, CBG, Mutasi, Saldo
        if (count($data) < 4) {
            return null;
        }

        $date = $this->parseDate($data[0]);
        $description = trim($data[1] ?? '');
        $amount = $this->parseAmount($data[3] ?? '0');

        if (!$date || !$description || $amount == 0) {
            return null;
        }

        return [
            'date' => $date,
            'reference' => 'BCA-' . $rowNumber,
            'description' => $description,
            'amount' => $amount,
            'type' => $amount > 0 ? 'credit' : 'debit'
        ];
    }

    /**
     * Parse Mandiri format CSV
     */
    private function parseMandiriFormat($data, $rowNumber)
    {
        // Mandiri format: Tgl, Uraian, Debet, Kredit, Saldo
        if (count($data) < 5) {
            return null;
        }

        $date = $this->parseDate($data[0]);
        $description = trim($data[1] ?? '');
        $debit = $this->parseAmount($data[2] ?? '0');
        $credit = $this->parseAmount($data[3] ?? '0');

        $amount = $credit > 0 ? $credit : -$debit;

        if (!$date || !$description || $amount == 0) {
            return null;
        }

        return [
            'date' => $date,
            'reference' => 'MDR-' . $rowNumber,
            'description' => $description,
            'amount' => $amount,
            'type' => $amount > 0 ? 'credit' : 'debit'
        ];
    }

    /**
     * Parse BNI format CSV
     */
    private function parseBniFormat($data, $rowNumber)
    {
        // BNI format: Date, Description, Amount, Balance
        if (count($data) < 3) {
            return null;
        }

        $date = $this->parseDate($data[0]);
        $description = trim($data[1] ?? '');
        $amount = $this->parseAmount($data[2] ?? '0');

        if (!$date || !$description || $amount == 0) {
            return null;
        }

        return [
            'date' => $date,
            'reference' => 'BNI-' . $rowNumber,
            'description' => $description,
            'amount' => $amount,
            'type' => $amount > 0 ? 'credit' : 'debit'
        ];
    }

    /**
     * Parse BRI format CSV
     */
    private function parseBriFormat($data, $rowNumber)
    {
        // BRI format: Posting Date, Desc, Debit, Credit, Balance
        if (count($data) < 5) {
            return null;
        }

        $date = $this->parseDate($data[0]);
        $description = trim($data[1] ?? '');
        $debit = $this->parseAmount($data[2] ?? '0');
        $credit = $this->parseAmount($data[3] ?? '0');

        $amount = $credit > 0 ? $credit : -$debit;

        if (!$date || !$description || $amount == 0) {
            return null;
        }

        return [
            'date' => $date,
            'reference' => 'BRI-' . $rowNumber,
            'description' => $description,
            'amount' => $amount,
            'type' => $amount > 0 ? 'credit' : 'debit'
        ];
    }

    /**
     * Parse generic format CSV
     */
    private function parseGenericFormat($data, $rowNumber)
    {
        // Generic format: assume Date, Description, Amount structure
        if (count($data) < 3) {
            return null;
        }

        $date = $this->parseDate($data[0]);
        $description = trim($data[1] ?? '');
        $amount = $this->parseAmount($data[2] ?? '0');

        if (!$date || !$description || $amount == 0) {
            return null;
        }

        return [
            'date' => $date,
            'reference' => 'GEN-' . $rowNumber,
            'description' => $description,
            'amount' => $amount,
            'type' => $amount > 0 ? 'credit' : 'debit'
        ];
    }

    /**
     * Parse date from various formats
     */
    private function parseDate($dateString)
    {
        try {
            // Try various date formats
            $formats = [
                'd/m/Y',
                'd-m-Y',
                'Y-m-d',
                'd/m/y',
                'd-m-y',
                'm/d/Y',
                'm-d-Y'
            ];

            foreach ($formats as $format) {
                $date = \DateTime::createFromFormat($format, trim($dateString));
                if ($date !== false) {
                    return $date->format('Y-m-d');
                }
            }

            // Try Carbon parsing as fallback
            return Carbon::parse($dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            Log::warning("Unable to parse date: {$dateString}");
            return null;
        }
    }

    /**
     * Parse amount from various formats
     */
    private function parseAmount($amountString)
    {
        try {
            // Remove common currency symbols and formatting
            $cleaned = preg_replace('/[^\d\-\.,]/', '', trim($amountString));

            // Handle different decimal separators
            if (substr_count($cleaned, '.') > 1) {
                // Multiple dots, assume thousands separator
                $cleaned = str_replace('.', '', $cleaned);
            } elseif (substr_count($cleaned, ',') > 1) {
                // Multiple commas, assume thousands separator
                $parts = explode(',', $cleaned);
                $cleaned = implode('', array_slice($parts, 0, -1)) . '.' . end($parts);
            } elseif (strpos($cleaned, ',') !== false && strpos($cleaned, '.') !== false) {
                // Both comma and dot, determine which is decimal
                $lastComma = strrpos($cleaned, ',');
                $lastDot = strrpos($cleaned, '.');

                if ($lastDot > $lastComma) {
                    // Dot is decimal separator
                    $cleaned = str_replace(',', '', $cleaned);
                } else {
                    // Comma is decimal separator
                    $cleaned = str_replace('.', '', $cleaned);
                    $cleaned = str_replace(',', '.', $cleaned);
                }
            } elseif (strpos($cleaned, ',') !== false) {
                // Only comma, could be thousands or decimal
                $parts = explode(',', $cleaned);
                if (count($parts) == 2 && strlen(end($parts)) <= 2) {
                    // Likely decimal separator
                    $cleaned = str_replace(',', '.', $cleaned);
                } else {
                    // Likely thousands separator
                    $cleaned = str_replace(',', '', $cleaned);
                }
            }

            return floatval($cleaned);
        } catch (\Exception $e) {
            Log::warning("Unable to parse amount: {$amountString}");
            return 0;
        }
    }
}
