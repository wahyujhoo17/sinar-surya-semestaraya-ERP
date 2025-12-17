<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\LaporanPajak;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ManagementPajakController extends Controller
{
    /**
     * Display a listing of tax records.
     */
    public function index(Request $request)
    {
        $query = LaporanPajak::query();

        // Get sort column and direction
        $sortColumn = $request->input('sort', 'tanggal');
        $sortDirection = $request->input('direction', 'desc');

        // Validate sort column to prevent SQL injection
        $allowedColumns = [
            'nomor',
            'tanggal',
            'periode_awal',
            'periode_akhir',
            'jenis_pajak',
            'jumlah_pajak',
            'status'
        ];

        if (in_array($sortColumn, $allowedColumns)) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy('tanggal', 'desc');
            $sortColumn = 'tanggal';
        }

        // Apply filters
        if ($request->filled('jenis')) {
            $query->where('jenis_pajak', $request->jenis);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nomor', 'like', "%{$searchTerm}%")
                    ->orWhere('keterangan', 'like', "%{$searchTerm}%");
            });
        }

        // Paginate the results
        $laporanPajaks = $query->paginate(15)->withQueryString();

        // Calculate tax summaries
        $totalPpnKeluaran = $this->calculatePpnKeluaran($request);
        $totalPpnMasukan = $this->calculatePpnMasukan($request);
        $ppnTerutang = $totalPpnKeluaran - $totalPpnMasukan;

        // Get period statistics
        $currentMonth = Carbon::now()->startOfMonth();
        $currentMonthStats = $this->getMonthlyTaxStats($currentMonth);
        $previousMonthStats = $this->getMonthlyTaxStats($currentMonth->copy()->subMonth());

        if ($request->ajax()) {
            return view('keuangan.management_pajak.partials.table', [
                'laporanPajaks' => $laporanPajaks,
                'request' => $request,
                'sortColumn' => $sortColumn,
                'sortDirection' => $sortDirection,
            ]);
        }

        return view('keuangan.management_pajak.index', [
            'laporanPajaks' => $laporanPajaks,
            'totalPpnKeluaran' => $totalPpnKeluaran,
            'totalPpnMasukan' => $totalPpnMasukan,
            'ppnTerutang' => $ppnTerutang,
            'currentMonthStats' => $currentMonthStats,
            'previousMonthStats' => $previousMonthStats,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
        ]);
    }

    /**
     * Show the form for creating a new tax record.
     */
    public function create()
    {
        return view('keuangan.management_pajak.create');
    }

    /**
     * Store a newly created tax record.
     */
    public function store(Request $request)
    {
        // Debug: Log the request data
        \Log::info('Tax store request received', [
            'data' => $request->all(),
            'url' => $request->url(),
            'method' => $request->method()
        ]);

        $validatedData = $request->validate([
            'jenis_pajak' => 'required|string|in:ppn_keluaran,ppn_masukan,pph21,pph23,pph4_ayat2',
            'tanggal_faktur' => 'required|date',
            'periode' => 'required|string', // Format YYYY-MM
            'no_faktur_pajak' => 'nullable|string|max:50',
            'npwp' => 'nullable|string|max:50',
            'dasar_pengenaan_pajak' => 'required|numeric|min:0',
            'tarif_pajak' => 'required|numeric|min:0|max:100',
            'jumlah_pajak' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'nullable|date',
            'status_pembayaran' => 'required|string|in:belum_bayar,sudah_bayar,lebih_bayar',
            'keterangan' => 'nullable|string|max:500',
        ]);

        \Log::info('Tax store validation passed', ['validated_data' => $validatedData]);

        DB::beginTransaction();
        try {
            // Generate nomor laporan pajak
            $date = Carbon::parse($validatedData['tanggal_faktur']);
            $prefix = $this->getTaxNumberPrefix($validatedData['jenis_pajak']);
            $yearMonth = $date->format('Ym');

            $lastNumber = LaporanPajak::where('nomor', 'like', $prefix . $yearMonth . '%')
                ->orderBy('id', 'desc')
                ->first();

            $sequence = 1;
            if ($lastNumber) {
                $lastSequence = intval(substr($lastNumber->nomor, -4));
                $sequence = $lastSequence + 1;
            }

            $nomor = $prefix . $yearMonth . str_pad($sequence, 4, '0', STR_PAD_LEFT);

            // Convert periode from YYYY-MM to date (first day of month)
            $periodeDate = Carbon::createFromFormat('Y-m', $validatedData['periode'])->startOfMonth();
            $periodeAwal = $periodeDate->copy();
            $periodeAkhir = $periodeDate->copy()->endOfMonth();

            $createData = [
                'jenis_pajak' => $validatedData['jenis_pajak'],
                'nomor' => $nomor,
                'tanggal' => $validatedData['tanggal_faktur'],
                'tanggal_faktur' => $validatedData['tanggal_faktur'],
                'periode' => $periodeDate,
                'periode_awal' => $periodeAwal,
                'periode_akhir' => $periodeAkhir,
                'no_faktur_pajak' => $validatedData['no_faktur_pajak'],
                'npwp' => $validatedData['npwp'],
                'dasar_pengenaan_pajak' => $validatedData['dasar_pengenaan_pajak'],
                'tarif_pajak' => $validatedData['tarif_pajak'],
                'jumlah_pajak' => $validatedData['jumlah_pajak'],
                'nilai' => $validatedData['jumlah_pajak'], // Set nilai = jumlah_pajak untuk kompatibilitas
                'tanggal_jatuh_tempo' => $validatedData['tanggal_jatuh_tempo'],
                'status_pembayaran' => $validatedData['status_pembayaran'],
                'status' => 'draft',
                'keterangan' => $validatedData['keterangan'],
                'user_id' => Auth::id(),
            ];

            \Log::info('Tax store creating record', ['create_data' => $createData]);

            $laporanPajak = LaporanPajak::create($createData);

            \Log::info('Tax store record created', ['id' => $laporanPajak->id, 'nomor' => $nomor]);

            DB::commit();

            return redirect()
                ->route('keuangan.management-pajak.show', $laporanPajak->id)
                ->with('success', 'Laporan pajak berhasil dibuat dengan nomor: ' . $nomor);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Tax store failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified tax record.
     */
    public function show(Request $request, $id)
    {
        $laporanPajak = LaporanPajak::findOrFail($id);

        // Get related transactions based on tax type and period
        // Use periode_awal and periode_akhir if available (for auto-generated reports), otherwise use monthly period
        $periodeAwal = $laporanPajak->periode_awal;
        $periodeAkhir = $laporanPajak->periode_akhir;

        if (!$periodeAwal || !$periodeAkhir) {
            $periodeDate = Carbon::parse($laporanPajak->periode ?? $laporanPajak->tanggal);
            $periodeAwal = $periodeDate->startOfMonth()->format('Y-m-d');
            $periodeAkhir = $periodeDate->endOfMonth()->format('Y-m-d');
        }

        $relatedTransactions = $this->getRelatedTransactions(
            $laporanPajak->jenis_pajak,
            $periodeAwal,
            $periodeAkhir,
            $request->get('per_page', 50)
        );

        return view('keuangan.management_pajak.show', [
            'laporanPajak' => $laporanPajak,
            'relatedTransactions' => $relatedTransactions,
        ]);
    }

    /**
     * Show the form for editing the specified tax record.
     */
    public function edit($id)
    {
        $laporanPajak = LaporanPajak::findOrFail($id);

        // Only allow editing if status is draft
        if ($laporanPajak->status !== 'draft') {
            return redirect()
                ->route('keuangan.management-pajak.show', $id)
                ->with('error', 'Hanya laporan pajak dengan status draft yang dapat diedit.');
        }

        return view('keuangan.management_pajak.edit', ['pajak' => $laporanPajak]);
    }

    /**
     * Update the specified tax record.
     */
    public function update(Request $request, $id)
    {
        $laporanPajak = LaporanPajak::findOrFail($id);

        // Only allow updating if status is draft
        if ($laporanPajak->status !== 'draft') {
            return redirect()
                ->route('keuangan.management-pajak.show', $id)
                ->with('error', 'Hanya laporan pajak dengan status draft yang dapat diperbarui.');
        }

        // Debug: Log the request data
        \Log::info('Tax update request received', [
            'id' => $id,
            'data' => $request->all(),
            'url' => $request->url(),
            'method' => $request->method()
        ]);

        $validatedData = $request->validate([
            'jenis_pajak' => 'required|string|in:ppn_keluaran,ppn_masukan,pph21,pph23,pph4_ayat2',
            'tanggal_faktur' => 'required|date',
            'periode' => 'required|string', // Changed to string to match create method
            'no_faktur_pajak' => 'nullable|string|max:50',
            'npwp' => 'nullable|string|max:50',
            'dasar_pengenaan_pajak' => 'required|numeric|min:0',
            'tarif_pajak' => 'required|numeric|min:0|max:100',
            'jumlah_pajak' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'nullable|date',
            'status_pembayaran' => 'required|string|in:belum_bayar,sudah_bayar,lebih_bayar',
            'keterangan' => 'nullable|string|max:500',
        ]);

        \Log::info('Tax update validation passed', ['validated_data' => $validatedData]);

        DB::beginTransaction();
        try {
            // Convert periode from YYYY-MM to date (first day of month)
            $periodeDate = Carbon::createFromFormat('Y-m', $validatedData['periode'])->startOfMonth();
            $periodeAwal = $periodeDate->copy();
            $periodeAkhir = $periodeDate->copy()->endOfMonth();

            $updateData = [
                'jenis_pajak' => $validatedData['jenis_pajak'],
                'tanggal' => $validatedData['tanggal_faktur'],
                'tanggal_faktur' => $validatedData['tanggal_faktur'],
                'periode' => $periodeDate,
                'periode_awal' => $periodeAwal,
                'periode_akhir' => $periodeAkhir,
                'no_faktur_pajak' => $validatedData['no_faktur_pajak'],
                'npwp' => $validatedData['npwp'],
                'dasar_pengenaan_pajak' => $validatedData['dasar_pengenaan_pajak'],
                'tarif_pajak' => $validatedData['tarif_pajak'],
                'jumlah_pajak' => $validatedData['jumlah_pajak'],
                'nilai' => $validatedData['jumlah_pajak'], // Set nilai = jumlah_pajak untuk kompatibilitas
                'tanggal_jatuh_tempo' => $validatedData['tanggal_jatuh_tempo'],
                'status_pembayaran' => $validatedData['status_pembayaran'],
                'keterangan' => $validatedData['keterangan'],
            ];

            \Log::info('Tax update updating record', ['update_data' => $updateData]);

            $laporanPajak->update($updateData);

            \Log::info('Tax update record updated', ['id' => $laporanPajak->id]);

            DB::commit();

            return redirect()
                ->route('keuangan.management-pajak.show', $id)
                ->with('success', 'Laporan pajak berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Tax update failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified tax record.
     */
    public function destroy($id)
    {
        $laporanPajak = LaporanPajak::findOrFail($id);

        // Only allow deletion if status is draft
        if ($laporanPajak->status !== 'draft') {
            return redirect()
                ->route('keuangan.management-pajak.index')
                ->with('error', 'Hanya laporan pajak dengan status draft yang dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            $laporanPajak->delete();

            DB::commit();

            return redirect()
                ->route('keuangan.management-pajak.index')
                ->with('success', 'Laporan pajak berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('keuangan.management-pajak.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Finalize tax record (change status from draft to final).
     */
    public function finalize($id)
    {
        $laporanPajak = LaporanPajak::findOrFail($id);

        if ($laporanPajak->status !== 'draft') {
            return redirect()
                ->route('keuangan.management-pajak.show', $id)
                ->with('error', 'Laporan pajak sudah di-finalisasi.');
        }

        DB::beginTransaction();
        try {
            $laporanPajak->update(['status' => 'final']);

            DB::commit();

            return redirect()
                ->route('keuangan.management-pajak.show', $id)
                ->with('success', 'Laporan pajak berhasil di-finalisasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('keuangan.management-pajak.show', $id)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Generate tax report PDF.
     */
    public function exportPdf($id)
    {
        $laporanPajak = LaporanPajak::findOrFail($id);

        // For single period, we'll use the month of the periode field
        $periodeDate = Carbon::parse($laporanPajak->periode ?? $laporanPajak->tanggal);
        $periodeAwal = $periodeDate->startOfMonth()->format('Y-m-d');
        $periodeAkhir = $periodeDate->endOfMonth()->format('Y-m-d');

        $relatedTransactions = $this->getRelatedTransactions(
            $laporanPajak->jenis_pajak,
            $periodeAwal,
            $periodeAkhir
        );

        $pdf = Pdf::loadView('keuangan.management_pajak.pdf', [
            'laporanPajak' => $laporanPajak,
            'relatedTransactions' => $relatedTransactions,
        ]);

        return $pdf->download('laporan-pajak-' . $laporanPajak->nomor . '.pdf');
    }

    /**
     * Calculate PPN Keluaran (Output VAT) from sales.
     */
    private function calculatePpnKeluaran($request = null)
    {
        $query = SalesOrder::where('ppn', '>', 0);

        if ($request && $request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request && $request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        return $query->sum(DB::raw('(total - ongkos_kirim) * (ppn / 100)'));
    }

    /**
     * Calculate PPN Masukan (Input VAT) from purchases.
     */
    private function calculatePpnMasukan($request = null)
    {
        $query = PurchaseOrder::where('ppn', '>', 0)
            ->where('status', '!=', 'dibatalkan');

        if ($request && $request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request && $request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        return $query->sum(DB::raw('(subtotal - diskon_nominal) * (ppn / 100)'));
    }

    /**
     * Get monthly tax statistics.
     */
    private function getMonthlyTaxStats($month)
    {
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        return [
            'ppn_keluaran' => SalesOrder::where('ppn', '>', 0)
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->sum(DB::raw('(total - ongkos_kirim) * (ppn / 100)')),
            'ppn_masukan' => PurchaseOrder::where('ppn', '>', 0)
                ->where('status', '!=', 'dibatalkan')
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->sum(DB::raw('(subtotal - diskon_nominal) * (ppn / 100)')),
            'laporan_count' => LaporanPajak::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->count(),
        ];
    }

    /**
     * Get tax number prefix based on tax type.
     */
    private function getTaxNumberPrefix($jenis)
    {
        $prefixes = [
            'ppn_keluaran' => 'PPNK-',
            'ppn_masukan' => 'PPNM-',
            'pph21' => 'PPH21-',
            'pph23' => 'PPH23-',
            'pph4_ayat2' => 'PPH4-',
        ];

        return $prefixes[$jenis] ?? 'TAX-';
    }

    /**
     * Get related transactions for a tax type and period with pagination.
     */
    private function getRelatedTransactions($jenis, $periodeAwal, $periodeAkhir, $perPage = 50)
    {
        $query = null;

        switch ($jenis) {
            case 'ppn_keluaran':
                $query = SalesOrder::with(['customer', 'details.produk'])
                    ->where('ppn', '>', 0)
                    ->whereBetween('tanggal', [$periodeAwal, $periodeAkhir])
                    ->select([
                        'id',
                        'nomor',
                        'tanggal',
                        'customer_id',
                        'total',
                        'ongkos_kirim',
                        'ppn',
                        DB::raw('(total - ongkos_kirim) as base_amount'),
                        DB::raw('((total - ongkos_kirim) * (ppn / 100)) as tax_amount'),
                        DB::raw("'Sales Order' as type"),
                        'customer_id as partner_id'
                    ])
                    ->orderBy('tanggal', 'desc');
                break;

            case 'ppn_masukan':
                $query = PurchaseOrder::with(['supplier', 'details.produk'])
                    ->where('ppn', '>', 0)
                    ->where('status', '!=', 'dibatalkan')
                    ->whereBetween('tanggal', [$periodeAwal, $periodeAkhir])
                    ->select([
                        'id',
                        'nomor',
                        'tanggal',
                        'supplier_id',
                        'subtotal',
                        'diskon_nominal',
                        'ppn',
                        'total',
                        DB::raw('(subtotal - diskon_nominal) as base_amount'),
                        DB::raw('((subtotal - diskon_nominal) * (ppn / 100)) as tax_amount'),
                        DB::raw("'Purchase Order' as type"),
                        'supplier_id as partner_id'
                    ])
                    ->orderBy('tanggal', 'desc');
                break;
        }

        if ($query) {
            return $query->paginate($perPage)->through(function ($transaction) use ($jenis) {
                $partner = null;
                if ($jenis === 'ppn_keluaran') {
                    $partner = $transaction->customer->nama ?? $transaction->customer->company ?? 'N/A';
                } else {
                    $partner = $transaction->supplier->nama ?? 'N/A';
                }

                return [
                    'type' => $transaction->type,
                    'nomor' => $transaction->nomor,
                    'tanggal' => $transaction->tanggal,
                    'partner' => $partner,
                    'base_amount' => $transaction->base_amount,
                    'tax_rate' => $transaction->ppn,
                    'tax_amount' => $transaction->tax_amount,
                    'total' => $transaction->total,
                ];
            });
        }

        return collect();
    }

    /**
     * Generate automatic tax report for a specific period.
     */
    public function generateAutoReport(Request $request)
    {
        $validatedData = $request->validate([
            'jenis_pajak' => 'required|string|in:ppn_keluaran,ppn_masukan',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
        ]);

        // Validate maximum period (90 days)
        $periodeAwal = Carbon::parse($validatedData['periode_awal']);
        $periodeAkhir = Carbon::parse($validatedData['periode_akhir']);
        $daysDifference = $periodeAwal->diffInDays($periodeAkhir);

        if ($daysDifference > 90) {
            return response()->json([
                'success' => false,
                'message' => 'Periode laporan maksimal 90 hari untuk menghindari beban sistem yang berlebih. Untuk laporan tahunan, gunakan laporan manual dengan periode bulanan.',
            ], 422);
        }
        try {
            $jenis = $validatedData['jenis_pajak'];
            $periodeAwal = $validatedData['periode_awal'];
            $periodeAkhir = $validatedData['periode_akhir'];

            // Calculate tax amount
            $taxAmount = 0;
            $tarifPajak = 11; // Default PPN rate
            if ($jenis === 'ppn_keluaran') {
                $taxAmount = $this->calculatePpnKeluaranForPeriod($periodeAwal, $periodeAkhir);
            } else {
                $taxAmount = $this->calculatePpnMasukanForPeriod($periodeAwal, $periodeAkhir);
            }

            // Calculate DPP (base amount)
            $dpp = 0;
            if ($taxAmount > 0 && $tarifPajak > 0) {
                $dpp = $taxAmount / ($tarifPajak / 100);
            }

            // Generate nomor
            $date = Carbon::now();
            $prefix = $this->getTaxNumberPrefix($jenis);
            $yearMonth = $date->format('Ym');

            $lastNumber = LaporanPajak::where('nomor', 'like', $prefix . $yearMonth . '%')
                ->orderBy('id', 'desc')
                ->first();

            $sequence = 1;
            if ($lastNumber) {
                $lastSequence = intval(substr($lastNumber->nomor, -4));
                $sequence = $lastSequence + 1;
            }

            $nomor = $prefix . $yearMonth . str_pad($sequence, 4, '0', STR_PAD_LEFT);

            $laporanPajak = LaporanPajak::create([
                'jenis_pajak' => $jenis,
                'nomor' => $nomor,
                'tanggal' => $date->format('Y-m-d'),
                'tanggal_faktur' => $date->format('Y-m-d'),
                'periode' => Carbon::parse($periodeAwal)->format('Y-m'),
                'periode_awal' => $periodeAwal,
                'periode_akhir' => $periodeAkhir,
                'dasar_pengenaan_pajak' => $dpp,
                'tarif_pajak' => $tarifPajak,
                'jumlah_pajak' => $taxAmount,
                'status' => 'draft',
                'status_pembayaran' => 'belum_bayar',
                'keterangan' => 'Laporan otomatis untuk periode ' . Carbon::parse($periodeAwal)->format('d/m/Y') . ' - ' . Carbon::parse($periodeAkhir)->format('d/m/Y'),
                'user_id' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Laporan pajak otomatis berhasil dibuat',
                'redirect_url' => route('keuangan.management-pajak.show', $laporanPajak->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Calculate PPN Keluaran for specific period.
     */
    private function calculatePpnKeluaranForPeriod($periodeAwal, $periodeAkhir)
    {
        return SalesOrder::where('ppn', '>', 0)
            ->whereBetween('tanggal', [$periodeAwal, $periodeAkhir])
            ->sum(DB::raw('(total - ongkos_kirim) * (ppn / 100)'));
    }

    /**
     * Calculate PPN Masukan for specific period.
     */
    private function calculatePpnMasukanForPeriod($periodeAwal, $periodeAkhir)
    {
        return PurchaseOrder::where('ppn', '>', 0)
            ->where('status', '!=', 'dibatalkan')
            ->whereBetween('tanggal', [$periodeAwal, $periodeAkhir])
            ->sum(DB::raw('(subtotal - diskon_nominal) * (ppn / 100)'));
    }
}
