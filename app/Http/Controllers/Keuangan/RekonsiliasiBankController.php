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
            // Debug logging
            Log::info('=== DEBUG getReconciliationHistory ===', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name ?? 'Unknown',
                'request_params' => $request->all(),
                'rekening_id' => $request->rekening_id,
                'periode' => $request->periode,
                'status' => $request->status
            ]);

            $query = RekonsiliasiBankHistory::with(['rekeningBank', 'user'])
                ->orderBy('created_at', 'desc');

            // Debug: count total records before filtering
            $totalRecords = RekonsiliasiBankHistory::count();
            Log::info('Total records in database:', ['count' => $totalRecords]);

            // Filter berdasarkan rekening
            if ($request->rekening_id) {
                $query->where('rekening_bank_id', $request->rekening_id);
                Log::info('Applied rekening filter:', ['rekening_id' => $request->rekening_id]);
            }

            // Filter berdasarkan periode
            if ($request->periode) {
                // Format periode: YYYY-MM (dari frontend) -> extract bulan saja
                if (strpos($request->periode, '-') !== false) {
                    $periodeParts = explode('-', $request->periode);
                    $tahun = $periodeParts[0];
                    $bulan = $periodeParts[1]; // Format: MM (e.g., "06")

                    $query->where('tahun', $tahun)
                        ->where('bulan', $bulan);

                    Log::info('Applied periode filter (YYYY-MM):', [
                        'periode_input' => $request->periode,
                        'tahun' => $tahun,
                        'bulan' => $bulan
                    ]);
                } else {
                    // Fallback: jika hanya bulan saja
                    $query->where('bulan', $request->periode);
                    Log::info('Applied periode filter (bulan only):', ['periode' => $request->periode]);
                }
            }

            // Filter berdasarkan status
            if ($request->status) {
                $query->where('status', $request->status);
                Log::info('Applied status filter:', ['status' => $request->status]);
            }

            // Debug: check query after filters
            $queryCount = $query->count();
            Log::info('Records after filters:', ['count' => $queryCount]);

            $reconciliations = $query->paginate($request->per_page ?? 15);

            Log::info('Pagination result:', [
                'total' => $reconciliations->total(),
                'count' => $reconciliations->count(),
                'current_page' => $reconciliations->currentPage(),
                'per_page' => $reconciliations->perPage()
            ]);

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

            return response($csvData, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Pragma' => 'public'
            ]);
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
            $output .= implode(',', array_map(function ($field) {
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row)) . "\n";
        }

        return $output;
    }
}
