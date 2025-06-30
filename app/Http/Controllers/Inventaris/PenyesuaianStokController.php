<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Gudang;
use App\Models\Produk;
use App\Models\StokProduk;
use App\Models\Satuan;
use App\Models\PenyesuaianStok;
use App\Models\PenyesuaianStokDetail;
use App\Models\RiwayatStok;
use App\Models\LogAktivitas;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class PenyesuaianStokController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:penyesuaian_stok.view')->only(['index', 'show']);
        $this->middleware('permission:penyesuaian_stok.create')->only(['create', 'store']);
        $this->middleware('permission:penyesuaian_stok.edit')->only(['edit', 'update']);
        $this->middleware('permission:penyesuaian_stok.delete')->only(['destroy']);
        $this->middleware('permission:penyesuaian_stok.process')->only(['prosesPenyesuaian']);
        $this->middleware('permission:penyesuaian_stok.view')->only(['printPdf', 'printDraftPdf']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['name' => 'Inventaris', 'url' => '#'],
            ['name' => 'Penyesuaian Stok', 'url' => route('inventaris.penyesuaian-stok.index')]
        ];
        $currentPage = 'Penyesuaian Stok';

        // Default filter status is 'all'
        $statusFilter = $request->status ?? 'all';

        // Query untuk mendapatkan data penyesuaian stok
        $query = PenyesuaianStok::with(['gudang', 'user', 'details.produk', 'details.satuan']);

        // Filter berdasarkan status jika ada
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // Filter berdasarkan tanggal jika ada
        if ($request->tanggal_mulai && $request->tanggal_akhir) {
            $tanggalMulai = Carbon::parse($request->tanggal_mulai)->startOfDay();
            $tanggalAkhir = Carbon::parse($request->tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir]);
        }

        // Filter berdasarkan gudang jika ada
        if ($request->gudang_id) {
            $query->where('gudang_id', $request->gudang_id);
        }

        // Filter berdasarkan pencarian jika ada
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%$search%")
                    ->orWhereHas('gudang', function ($q) use ($search) {
                        $q->where('nama', 'like', "%$search%");
                    })
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            });
        }

        // Ambil data dengan pagination
        $penyesuaianStok = $query->latest()->paginate(10);

        // List gudang untuk filter
        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();

        return view('inventaris.penyesuaian_stok.index', compact(
            'penyesuaianStok',
            'gudangs',
            'statusFilter',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = [
            ['name' => 'Inventaris', 'url' => '#'],
            ['name' => 'Penyesuaian Stok', 'url' => route('inventaris.penyesuaian-stok.index')],
            ['name' => 'Buat Penyesuaian Stok', 'url' => route('inventaris.penyesuaian-stok.create')]
        ];
        $currentPage = 'Buat Penyesuaian Stok';

        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();
        $satuans = Satuan::orderBy('nama')->get();

        // Generate unique number for adjustment
        $lastAdjustment = PenyesuaianStok::orderBy('created_at', 'desc')->first();
        $lastNumber = $lastAdjustment ? intval(substr($lastAdjustment->nomor, -5)) : 0;
        $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        $prefix = 'ADJ-' . Carbon::now()->format('Ymd') . '-';
        $nomor = $prefix . $newNumber;

        return view('inventaris.penyesuaian_stok.create', compact(
            'gudangs',
            'satuans',
            'nomor',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Get products by warehouse for dropdown
     */
    public function getProduksByGudang($id): JsonResponse
    {
        try {
            $produksWithStock = StokProduk::where('gudang_id', $id)
                ->with(['produk' => function ($query) {
                    $query->select('id', 'nama', 'kode', 'satuan_id');
                }, 'produk.satuan'])
                ->whereHas('produk', function ($query) {
                    $query->whereNotNull('nama')
                        ->whereNotNull('kode');
                })
                ->get();

            Log::info("Found {$produksWithStock->count()} products with stock in warehouse ID: $id");

            // Format the data for the dropdown
            $produks = $produksWithStock->map(function ($stokProduk) {
                try {
                    if (!$stokProduk->produk) {
                        Log::warning("Missing product for stok_produk ID: " . $stokProduk->id);
                        return null;
                    }

                    $produk = $stokProduk->produk;

                    // Make sure we have all required fields
                    if (!$produk->id || !$produk->nama || !$produk->kode) {
                        Log::warning("Product missing required fields. ID: {$produk->id}");
                        return null;
                    }

                    // Make sure satuan is loaded before accessing it
                    $satuan = $produk->satuan;
                    $satuanNama = ($satuan && isset($satuan->nama)) ? $satuan->nama : '-';

                    // Convert jumlah to float to ensure it's a number
                    $stokJumlah = (float)$stokProduk->jumlah;

                    return [
                        'id' => $produk->id,
                        'nama' => $produk->kode . ' - ' . $produk->nama,
                        'stok' => $stokJumlah,
                        'satuan_id' => $produk->satuan_id ?? null,
                        'satuan_nama' => $satuanNama
                    ];
                } catch (\Exception $e) {
                    Log::error("Error mapping product data: " . $e->getMessage());
                    return null;
                }
            })->filter()->values(); // Remove null entries and reindex array

            Log::info("Returning {$produks->count()} formatted products for warehouse ID: $id");

            return response()->json($produks);
        } catch (\Exception $e) {
            Log::error("Error in getProduksByGudang: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the store method to handle draft printing
     */
    public function store(Request $request)
    {
        Log::info('Storing penyesuaian stok with data:', [
            'gudang_id' => $request->gudang_id,
            'produk_count' => $request->produk_id ? count($request->produk_id) : 0,
            'print_draft' => $request->has('print_draft') ? 'yes' : 'no'
        ]);

        $request->validate([
            'nomor' => 'required|string|unique:penyesuaian_stok,nomor',
            'tanggal' => 'required|date',
            'gudang_id' => 'required|exists:gudang,id',
            'catatan' => 'nullable|string',
            'produk_id' => 'required|array|min:1',
            'produk_id.*' => 'required|exists:produk,id',
            'stok_tercatat.*' => 'required|numeric|min:0',
            'stok_fisik.*' => 'required|numeric|min:0',
            'satuan_id.*' => 'required|exists:satuan,id',
            'keterangan.*' => 'nullable|string',
            'selisih.*' => 'nullable|numeric',
        ]);

        // Check if we're just printing a draft without saving
        if ($request->has('print_draft')) {
            Log::info('Generating draft PDF without saving to database');

            // Create a temporary object for PDF generation
            $penyesuaian = new \stdClass();
            $penyesuaian->nomor = $request->nomor;
            $penyesuaian->tanggal = $request->tanggal;
            $penyesuaian->catatan = $request->catatan;
            $penyesuaian->status = 'draft';
            $penyesuaian->created_at = now();

            // Add gudang
            $gudang = Gudang::findOrFail($request->gudang_id);
            $penyesuaian->gudang = $gudang;

            // Add user
            $penyesuaian->user = Auth::user();

            // Add details
            $details = [];
            foreach ($request->produk_id as $index => $produkId) {
                $detail = new \stdClass();
                $detail->produk = Produk::findOrFail($produkId);
                $detail->stok_tercatat = $request->stok_tercatat[$index];
                $detail->stok_fisik = $request->stok_fisik[$index];
                $detail->selisih = $request->selisih[$index] ?? $request->stok_fisik[$index] - $request->stok_tercatat[$index];
                $detail->keterangan = $request->keterangan[$index] ?? null;
                $detail->satuan = Satuan::findOrFail($request->satuan_id[$index]);

                $details[] = $detail;
            }
            $penyesuaian->details = collect($details);

            // Calculate summary data
            $countPositive = 0;
            $countNegative = 0;
            $countNoChange = 0;
            $totalPositive = 0;
            $totalNegative = 0;
            $totalItems = count($details);
            $uniqueSatuans = [];

            foreach ($details as $detail) {
                if ($detail->selisih > 0) {
                    $countPositive++;
                    $totalPositive += $detail->selisih;
                } elseif ($detail->selisih < 0) {
                    $countNegative++;
                    $totalNegative += abs($detail->selisih);
                } else {
                    $countNoChange++;
                }

                // Track unique units for display purposes
                if (isset($detail->satuan) && $detail->satuan->nama) {
                    $uniqueSatuans[$detail->satuan->id] = $detail->satuan->nama;
                }
            }

            // Determine unit display text
            $multipleUnits = count($uniqueSatuans) > 1;
            $unitDisplay = $multipleUnits ? 'unit' : (count($uniqueSatuans) === 1 ? reset($uniqueSatuans) : 'unit');

            $pdf = PDF::loadView('inventaris.penyesuaian_stok.pdf', compact(
                'penyesuaian',
                'countPositive',
                'countNegative',
                'countNoChange',
                'totalPositive',
                'totalNegative',
                'totalItems',
                'unitDisplay'
            ));

            // If it's an AJAX request, return PDF URL
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['pdf_url' => route('inventaris.penyesuaian-stok.pdf.draft')]);
            }

            return $pdf->stream('penyesuaian-stok-draft-' . $request->nomor . '.pdf');
        }

        try {
            // Continue with normal save operation
            DB::beginTransaction();

            $penyesuaianStok = new PenyesuaianStok();
            $penyesuaianStok->nomor = $request->nomor;
            $penyesuaianStok->tanggal = $request->tanggal;
            $penyesuaianStok->gudang_id = $request->gudang_id;
            $penyesuaianStok->catatan = $request->catatan;
            $penyesuaianStok->status = 'draft';
            $penyesuaianStok->user_id = Auth::id();
            $penyesuaianStok->save();

            // Loop through all products and save the details
            foreach ($request->produk_id as $index => $produkId) {
                $detail = new PenyesuaianStokDetail();
                $detail->penyesuaian_id = $penyesuaianStok->id;
                $detail->produk_id = $produkId;
                $detail->stok_tercatat = $request->stok_tercatat[$index];
                $detail->stok_fisik = $request->stok_fisik[$index];
                $detail->selisih = $request->selisih[$index] ?? $request->stok_fisik[$index] - $request->stok_tercatat[$index];
                $detail->keterangan = $request->keterangan[$index] ?? null;
                $detail->satuan_id = $request->satuan_id[$index];
                $detail->save();
            }

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Membuat penyesuaian stok baru dengan nomor ' . $penyesuaianStok->nomor,
                'modul' => 'penyesuaian_stok'
            ]);

            // Send notification
            $notificationService = app(NotificationService::class);
            $notificationService->notifyStockAdjustmentCreated($penyesuaianStok, Auth::user());

            DB::commit();

            return redirect()->route('inventaris.penyesuaian-stok.show', $penyesuaianStok->id)
                ->with('success', 'Penyesuaian stok berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating penyesuaian stok: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $breadcrumbs = [
            ['name' => 'Inventaris', 'url' => '#'],
            ['name' => 'Penyesuaian Stok', 'url' => route('inventaris.penyesuaian-stok.index')],
            ['name' => 'Detail Penyesuaian', 'url' => '#']
        ];
        $currentPage = 'Detail Penyesuaian Stok';

        $penyesuaian = PenyesuaianStok::with(['gudang', 'user', 'details.produk', 'details.satuan'])->findOrFail($id);

        return view('inventaris.penyesuaian_stok.show', compact(
            'penyesuaian',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $penyesuaian = PenyesuaianStok::with(['details.produk', 'details.satuan'])->findOrFail($id);

        // Can only edit in draft status
        if ($penyesuaian->status !== 'draft') {
            return redirect()->route('inventaris.penyesuaian-stok.show', $penyesuaian->id)
                ->with('error', 'Tidak dapat mengedit penyesuaian yang sudah diproses atau selesai!');
        }

        $breadcrumbs = [
            ['name' => 'Inventaris', 'url' => '#'],
            ['name' => 'Penyesuaian Stok', 'url' => route('inventaris.penyesuaian-stok.index')],
            ['name' => 'Edit Penyesuaian', 'url' => '#']
        ];
        $currentPage = 'Edit Penyesuaian Stok';

        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();
        $satuans = Satuan::all();


        $stokProduk = StokProduk::with(['produk', 'produk.satuan'])
            ->where('gudang_id', $penyesuaian->gudang_id)
            ->whereHas('produk', function ($query) {
                $query->whereNotNull('kode')
                    ->whereNotNull('nama');
            })
            ->get();

        $mappedStokProduk = $stokProduk->map(function ($stok) {
            try {
                if (!$stok->produk) {
                    return null;
                }

                $produk = $stok->produk;

                // Get satuan information safely
                $satuan = $produk->satuan;
                $satuanNama = ($satuan && isset($satuan->nama)) ? $satuan->nama : '-';
                $satuanId = $produk->satuan_id ?? null;

                $result = [
                    'produk_id' => $stok->produk_id,
                    'gudang_id' => $stok->gudang_id,
                    'nama' => $produk->kode . ' - ' . $produk->nama,
                    'stok' => (float)$stok->jumlah,
                    'satuan_id' => $satuanId,
                    'satuan_nama' => $satuanNama,
                ];

                return $result;
            } catch (\Exception $e) {
                return null;
            }
        })
            ->filter()
            ->values();

        $allStokProduk = $mappedStokProduk;

        // Prepare the items array from penyesuaian details
        $items = $penyesuaian->details->map(function ($detail) {
            $satuan = $detail->satuan;
            $satuanNama = ($satuan && isset($satuan->nama)) ? $satuan->nama : '-';

            return [
                'produk_id' => $detail->produk_id,
                'stok_tercatat' => (float)$detail->stok_tercatat,
                'stok_fisik' => (float)$detail->stok_fisik,
                'selisih' => (float)$detail->selisih,
                'satuan_id' => $detail->satuan_id,
                'satuan_nama' => $satuanNama,
                'keterangan' => $detail->keterangan
            ];
        })->toArray();

        return view('inventaris.penyesuaian_stok.edit', compact(
            'penyesuaian',
            'gudangs',
            'satuans',
            'allStokProduk',
            'items',
            'breadcrumbs',
            'currentPage'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $penyesuaian = PenyesuaianStok::findOrFail($id);

        // Can only update in draft status
        if ($penyesuaian->status !== 'draft') {
            return redirect()->route('inventaris.penyesuaian-stok.show', $penyesuaian->id)
                ->with('error', 'Tidak dapat mengubah penyesuaian yang sudah diproses atau selesai!');
        }

        $request->validate([
            'nomor' => 'required|string|unique:penyesuaian_stok,nomor,' . $id,
            'tanggal' => 'required|date',
            'gudang_id' => 'required|exists:gudang,id',
            'catatan' => 'nullable|string',
            'produk_id' => 'required|array|min:1',
            'produk_id.*' => 'required|exists:produk,id',
            'stok_tercatat.*' => 'required|numeric|min:0',
            'stok_fisik.*' => 'required|numeric|min:0',
            'satuan_id.*' => 'required|exists:satuan,id',
            'keterangan.*' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Update penyesuaian header
            $penyesuaian->update([
                'nomor' => $request->nomor,
                'tanggal' => $request->tanggal,
                'gudang_id' => $request->gudang_id,
                'catatan' => $request->catatan,
            ]);

            // Delete existing details
            $penyesuaian->details()->delete();

            // Create new details
            foreach ($request->produk_id as $index => $produkId) {
                $stokTercatat = $request->stok_tercatat[$index];
                $stokFisik = $request->stok_fisik[$index];
                $selisih = $stokFisik - $stokTercatat;

                PenyesuaianStokDetail::create([
                    'penyesuaian_id' => $penyesuaian->id,
                    'produk_id' => $produkId,
                    'stok_tercatat' => $stokTercatat,
                    'stok_fisik' => $stokFisik,
                    'selisih' => $selisih,
                    'satuan_id' => $request->satuan_id[$index],
                    'keterangan' => $request->keterangan[$index] ?? null
                ]);
            }

            // Add activity log
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Mengupdate penyesuaian stok',
                'modul' => 'Penyesuaian Stok',
                'data_id' => $penyesuaian->id,
                'ip_address' => request()->ip(),
                'detail' => "Mengupdate penyesuaian stok dengan nomor {$penyesuaian->nomor} untuk gudang {$penyesuaian->gudang->nama}"
            ]);

            DB::commit();

            return redirect()->route('inventaris.penyesuaian-stok.show', $penyesuaian->id)
                ->with('success', 'Penyesuaian stok berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penyesuaian = PenyesuaianStok::with(['gudang'])->findOrFail($id);

        // Can only delete in draft status
        if ($penyesuaian->status !== 'draft') {
            return redirect()->route('inventaris.penyesuaian-stok.show', $penyesuaian->id)
                ->with('error', 'Tidak dapat menghapus penyesuaian yang sudah diproses atau selesai!');
        }

        // Store info for log
        $penyesuaianInfo = [
            'nomor' => $penyesuaian->nomor,
            'gudang' => $penyesuaian->gudang->nama,
        ];

        DB::beginTransaction();
        try {
            // Delete details first
            $penyesuaian->details()->delete();

            // Delete penyesuaian
            $penyesuaian->delete();

            // Add activity log
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Menghapus penyesuaian stok',
                'modul' => 'Penyesuaian Stok',
                'data_id' => $id,
                'ip_address' => request()->ip(),
                'detail' => "Menghapus penyesuaian stok dengan nomor {$penyesuaianInfo['nomor']} dari gudang {$penyesuaianInfo['gudang']}"
            ]);

            // Send notification
            $notificationService = app(NotificationService::class);
            $notificationService->notifyStockAdjustmentDeleted($penyesuaianInfo, Auth::user());

            DB::commit();

            return redirect()->route('inventaris.penyesuaian-stok.index')
                ->with('success', 'Penyesuaian stok berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Process the adjustment - adjust stock based on physical count.
     */
    public function prosesPenyesuaian($id)
    {
        $penyesuaian = PenyesuaianStok::with(['details.produk', 'details.satuan'])->findOrFail($id);

        // Can only process in draft status
        if ($penyesuaian->status !== 'draft') {
            return redirect()->route('inventaris.penyesuaian-stok.show', $penyesuaian->id)
                ->with('error', 'Penyesuaian ini tidak dapat diproses karena sudah dalam status ' . $penyesuaian->status);
        }

        try {
            DB::beginTransaction();

            // Check for significant discrepancies before processing
            $discrepancyItems = [];
            $discrepancyThreshold = 50; // Notify if difference is more than 50 units

            foreach ($penyesuaian->details as $detail) {
                if (abs($detail->selisih) >= $discrepancyThreshold) {
                    $discrepancyItems[] = [
                        'nama' => $detail->produk->nama ?? 'Produk',
                        'selisih' => $detail->selisih
                    ];
                }
            }

            // Process each detail
            foreach ($penyesuaian->details as $detail) {
                // Get or create stock record
                $stok = StokProduk::firstOrNew([
                    'gudang_id' => $penyesuaian->gudang_id,
                    'produk_id' => $detail->produk_id
                ]);

                // Calculate adjustment
                $jumlahSebelum = $stok->jumlah ?? 0;
                $selisih = $detail->selisih;

                // Update stock amount
                $stok->jumlah = $detail->stok_fisik;
                $stok->lokasi_rak = $stok->lokasi_rak ?? '-';
                $stok->batch_number = $stok->batch_number ?? '-';
                $stok->save();

                // Create stock history
                RiwayatStok::create([
                    'stok_id' => $stok->id,
                    'produk_id' => $detail->produk_id,
                    'gudang_id' => $penyesuaian->gudang_id,
                    'user_id' => Auth::id(),
                    'jumlah_sebelum' => $jumlahSebelum,
                    'jumlah_perubahan' => $selisih,
                    'jumlah_setelah' => $stok->jumlah,
                    'jenis' => 'penyesuaian',
                    'referensi_tipe' => 'penyesuaian_stok',
                    'referensi_id' => $penyesuaian->id,
                    'keterangan' => "Penyesuaian stok #{$penyesuaian->nomor}: " . ($detail->keterangan ?? ($selisih > 0 ? 'Penambahan stok' : 'Pengurangan stok'))
                ]);
            }

            // Update adjustment status
            $penyesuaian->status = 'selesai';
            $penyesuaian->save();

            // Add activity log
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Memproses penyesuaian stok',
                'modul' => 'Penyesuaian Stok',
                'data_id' => $penyesuaian->id,
                'ip_address' => request()->ip(),
                'detail' => "Memproses penyesuaian stok #{$penyesuaian->nomor} untuk gudang {$penyesuaian->gudang->nama}. Stok telah disesuaikan."
            ]);

            // Send notifications
            $notificationService = app(NotificationService::class);
            $notificationService->notifyStockAdjustmentProcessed($penyesuaian, Auth::user());

            // Notify about significant discrepancies if any
            if (!empty($discrepancyItems)) {
                $notificationService->notifyStockDiscrepancy($penyesuaian, $discrepancyItems);
            }

            // Check for low stock after processing
            foreach ($penyesuaian->details as $detail) {
                $notificationService->checkAndNotifyLowStock($detail->produk_id, $penyesuaian->gudang_id);
            }

            DB::commit();

            return redirect()->route('inventaris.penyesuaian-stok.show', $penyesuaian->id)
                ->with('success', 'Penyesuaian stok berhasil diproses. Stok di gudang telah disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get available stock for a product in a specific warehouse
     */
    public function getStokProduk(Request $request)
    {
        try {
            $request->validate([
                'produk_id' => 'required|exists:produk,id',
                'gudang_id' => 'required|exists:gudang,id',
            ]);

            $produkId = $request->produk_id;
            $gudangId = $request->gudang_id;

            // Get product details
            $produk = Produk::with('satuan')->findOrFail($produkId);

            // Get stock information
            $stokProduk = StokProduk::where('produk_id', $produkId)
                ->where('gudang_id', $gudangId)
                ->first();

            // Default values
            $stokJumlah = 0;
            $satuanNama = $produk->satuan->nama ?? '-';
            $satuanId = $produk->satuan_id;

            if ($stokProduk) {
                $stokJumlah = $stokProduk->jumlah;
            }

            $responseData = [
                'stok' => $stokJumlah,
                'satuan' => $satuanNama,
                'satuan_id' => $satuanId,
                'produk_nama' => $produk->nama,
                'produk_kode' => $produk->kode
            ];

            Log::info("Returning stock data: " . json_encode($responseData));

            return response()->json($responseData);
        } catch (\Exception $e) {
            Log::error('Error in getStokProduk: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data stok produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF for stock adjustment
     */
    public function printPdf($id)
    {
        $penyesuaian = PenyesuaianStok::with(['gudang', 'user', 'details.produk', 'details.satuan'])
            ->findOrFail($id);

        // Calculate summary data
        $countPositive = 0;
        $countNegative = 0;
        $countNoChange = 0;
        $totalPositive = 0;
        $totalNegative = 0;
        $totalItems = count($penyesuaian->details);
        $uniqueSatuans = [];

        foreach ($penyesuaian->details as $detail) {
            if ($detail->selisih > 0) {
                $countPositive++;
                $totalPositive += $detail->selisih;
            } elseif ($detail->selisih < 0) {
                $countNegative++;
                $totalNegative += abs($detail->selisih);
            } else {
                $countNoChange++;
            }

            // Track unique units for display purposes
            if (isset($detail->satuan) && $detail->satuan->nama) {
                $uniqueSatuans[$detail->satuan->id] = $detail->satuan->nama;
            }
        }

        // Determine unit display text
        $multipleUnits = count($uniqueSatuans) > 1;
        $unitDisplay = $multipleUnits ? 'unit' : (count($uniqueSatuans) === 1 ? reset($uniqueSatuans) : 'unit');

        $pdf = PDF::loadView('inventaris.penyesuaian_stok.pdf', compact(
            'penyesuaian',
            'countPositive',
            'countNegative',
            'countNoChange',
            'totalPositive',
            'totalNegative',
            'totalItems',
            'unitDisplay'
        ));

        return $pdf->stream('penyesuaian-stok-' . $penyesuaian->nomor . '.pdf');
    }

    /**
     * Generate PDF for a draft stock adjustment without saving to the database
     * This is used by the AJAX call to generate a draft PDF
     */
    public function printDraftPdf()
    {
        // Access session data to get the temporary draft data
        $penyesuaian = new \stdClass();
        $penyesuaian->nomor = 'DRAFT-' . date('YmdHis');
        $penyesuaian->tanggal = date('Y-m-d');
        $penyesuaian->catatan = 'Draft Penyesuaian Stok - Belum Tersimpan';
        $penyesuaian->status = 'draft';
        $penyesuaian->created_at = now();

        // Create sample data for preview
        $penyesuaian->gudang = new \stdClass();
        $penyesuaian->gudang->nama = 'Preview Gudang';

        $penyesuaian->user = new \stdClass();
        $penyesuaian->user->name = Auth::user()->name;

        $penyesuaian->details = collect([]);

        $countPositive = 0;
        $countNegative = 0;
        $countNoChange = 0;
        $totalPositive = 0;
        $totalNegative = 0;
        $totalItems = 0;
        $unitDisplay = 'unit';

        // Generate PDF with standard template but preview/draft watermark
        $pdf = PDF::loadView('inventaris.penyesuaian_stok.pdf', compact(
            'penyesuaian',
            'countPositive',
            'countNegative',
            'countNoChange',
            'totalPositive',
            'totalNegative',
            'totalItems',
            'unitDisplay'
        ));

        return $pdf->stream('penyesuaian-stok-draft.pdf');
    }
}
