<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Gudang;
use App\Models\Produk;
use App\Models\StokProduk;
use App\Models\Satuan;
use App\Models\TransferBarang;
use App\Models\TransferBarangDetail;
use App\Models\RiwayatStok;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class TransferGudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['name' => 'Inventaris', 'url' => '#'],
            ['name' => 'Transfer Antar Gudang', 'url' => route('inventaris.transfer-gudang.index')]
        ];
        $currentPage = 'Transfer Antar Gudang';

        $query = TransferBarang::with(['gudangAsal', 'gudangTujuan', 'user'])
            ->orderBy('created_at', 'desc');

        // Filter based on status if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter based on date range if provided
        if ($request->has('tanggal_mulai') && !empty($request->tanggal_mulai)) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_akhir') && !empty($request->tanggal_akhir)) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        // Filter based on warehouse if provided
        if ($request->has('gudang') && !empty($request->gudang)) {
            $query->where(function ($q) use ($request) {
                $q->where('gudang_asal_id', $request->gudang)
                    ->orWhere('gudang_tujuan_id', $request->gudang);
            });
        }

        $transfers = $query->paginate(10);
        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();

        return view('inventaris.transfer_gudang.index', compact(
            'transfers',
            'gudangs',
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
            ['name' => 'Transfer Antar Gudang', 'url' => route('inventaris.transfer-gudang.index')],
            ['name' => 'Buat Transfer Baru', 'url' => route('inventaris.transfer-gudang.create')]
        ];
        $currentPage = 'Buat Transfer Antar Gudang';

        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();
        $satuans = Satuan::all();

        // Generate unique number for the transfer
        $lastTransfer = TransferBarang::orderBy('created_at', 'desc')->first();
        $lastNumber = $lastTransfer ? intval(substr($lastTransfer->nomor, -5)) : 0;
        $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        $prefix = 'TRF-' . Carbon::now()->format('Ymd') . '-';
        $nomor = $prefix . $newNumber;

        // Debug log to check what's happening with stock data
        \Log::info('Fetching product stock data for transfer form');

        $allStokProduk = StokProduk::with(['produk', 'produk.satuan'])
            ->where('jumlah', '>', 0)
            ->whereHas('produk', function ($query) {
                $query->whereNotNull('kode')  // Ensure product has a code
                    ->whereNotNull('nama'); // Ensure product has a name
            })
            ->get();

        \Log::info('Found ' . $allStokProduk->count() . ' stock records with positive quantities');

        $mappedStokProduk = $allStokProduk->map(function ($stok) {
            try {
                // Double-check that produk exists to avoid null access
                if (!$stok->produk) {
                    \Log::warning("Skipping stock record with missing product relation. StokProduk ID: " . $stok->id);
                    return null;
                }

                // Make sure we have valid data before creating the array
                $produk = $stok->produk;

                // Get satuan information safely
                $satuan = $produk->satuan;
                $satuanNama = ($satuan && isset($satuan->nama)) ? $satuan->nama : '-';
                $satuanId = $produk->satuan_id ?? null;

                $result = [
                    'produk_id' => $stok->produk_id,
                    'gudang_id' => $stok->gudang_id,
                    'nama' => $produk->kode . ' - ' . $produk->nama,
                    'stok' => (float)$stok->jumlah, // Ensure stok is a number
                    'satuan_id' => $satuanId,
                    'satuan_nama' => $satuanNama,
                ];

                return $result;
            } catch (\Exception $e) {
                \Log::error("Error mapping stock product ID {$stok->id}: " . $e->getMessage());
                return null;
            }
        })
            ->filter() // Remove any null entries
            ->values(); // Re-index array

        // Log a sample of the final data to help with debugging
        \Log::info('Mapped ' . $mappedStokProduk->count() . ' valid stock products');
        if ($mappedStokProduk->count() > 0) {
            \Log::info('Sample product data: ' . json_encode($mappedStokProduk->first()));
        }

        $allStokProduk = $mappedStokProduk;

        return view('inventaris.transfer_gudang.create', compact(
            'gudangs',
            'satuans',
            'nomor',
            'breadcrumbs',
            'currentPage',
            'allStokProduk'
        ));
    }

    /**
     * Get products available in a specific warehouse
     */
    public function getProduksByGudang($id)
    {
        try {
            // Validate gudang exists
            $gudang = Gudang::findOrFail($id);
            \Log::info("Fetching products for warehouse ID: $id ({$gudang->nama})");

            // Get products with stock in the selected warehouse
            $produksWithStock = StokProduk::where('gudang_id', $id)
                ->where('jumlah', '>', 0)
                ->with(['produk' => function ($query) {
                    $query->select('id', 'nama', 'kode', 'satuan_id');
                }, 'produk.satuan'])
                ->whereHas('produk', function ($query) {
                    $query->whereNotNull('nama')
                        ->whereNotNull('kode');
                })
                ->get();

            \Log::info("Found {$produksWithStock->count()} products with stock in warehouse ID: $id");

            // Format the data for the dropdown
            $produks = $produksWithStock->map(function ($stokProduk) {
                try {
                    if (!$stokProduk->produk) {
                        \Log::warning("Missing product for stok_produk ID: " . $stokProduk->id);
                        return null;
                    }

                    $produk = $stokProduk->produk;

                    // Make sure we have all required fields
                    if (!$produk->id || !$produk->nama || !$produk->kode) {
                        \Log::warning("Product missing required fields. ID: {$produk->id}");
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
                    \Log::error("Error mapping product data: " . $e->getMessage());
                    return null;
                }
            })->filter()->values(); // Remove null entries and reindex array

            \Log::info("Returning {$produks->count()} formatted products for warehouse ID: $id");

            // Log the first product as sample
            if ($produks->count() > 0) {
                \Log::info("Sample product: " . json_encode($produks->first()));
            } else {
                \Log::warning("No products available in warehouse $id");
            }

            return response()->json($produks);
        } catch (\Exception $e) {
            \Log::error('Error in getProduksByGudang: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log incoming request data for debugging
        \Log::info('Transfer Gudang Store Request:', [
            'nomor' => $request->nomor,
            'gudang_asal_id' => $request->gudang_asal_id,
            'gudang_tujuan_id' => $request->gudang_tujuan_id,
            'produk_count' => $request->produk_id ? count($request->produk_id) : 0
        ]);

        $request->validate([
            'nomor' => 'required|string|unique:transfer_barang,nomor',
            'tanggal' => 'required|date',
            'gudang_asal_id' => 'required|exists:gudang,id',
            'gudang_tujuan_id' => 'required|exists:gudang,id|different:gudang_asal_id',
            'catatan' => 'nullable|string',
            'produk_id' => 'required|array|min:1',
            'produk_id.*' => 'required|exists:produk,id',
            'quantity.*' => 'required|numeric|min:0.01',
            'satuan_id.*' => 'required|exists:satuan,id',
            'keterangan.*' => 'nullable|string'
        ]);

        try {
            // Verify that arrays are valid and have the same length
            if (!is_array($request->produk_id)) {
                \Log::error('produk_id bukan array', ['produk_id' => $request->produk_id]);
                throw new \Exception("Data produk tidak valid: produk_id bukan array.");
            }

            if (!is_array($request->quantity)) {
                \Log::error('quantity bukan array', ['quantity' => $request->quantity]);
                throw new \Exception("Data produk tidak valid: quantity bukan array.");
            }

            if (!is_array($request->satuan_id)) {
                \Log::error('satuan_id bukan array', ['satuan_id' => $request->satuan_id]);
                throw new \Exception("Data produk tidak valid: satuan_id bukan array.");
            }

            if (count($request->produk_id) !== count($request->quantity)) {
                \Log::error('Jumlah produk_id dan quantity tidak sama', [
                    'produk_id_count' => count($request->produk_id),
                    'quantity_count' => count($request->quantity)
                ]);
                throw new \Exception("Data produk tidak valid: jumlah produk dan kuantitas tidak sesuai.");
            }

            if (count($request->produk_id) !== count($request->satuan_id)) {
                \Log::error('Jumlah produk_id dan satuan_id tidak sama', [
                    'produk_id_count' => count($request->produk_id),
                    'satuan_id_count' => count($request->satuan_id)
                ]);
                throw new \Exception("Data produk tidak valid: jumlah produk dan satuan tidak sesuai.");
            }

            // Log request details for debugging
            \Log::info('Detail data request transfer:', [
                'produk_id' => $request->produk_id,
                'quantity' => $request->quantity,
                'satuan_id' => $request->satuan_id,
                'keterangan' => $request->keterangan ?? []
            ]);

            DB::beginTransaction();

            $transfer = TransferBarang::create([
                'nomor' => $request->nomor,
                'tanggal' => $request->tanggal,
                'gudang_asal_id' => $request->gudang_asal_id,
                'gudang_tujuan_id' => $request->gudang_tujuan_id,
                'user_id' => Auth::id(),
                'status' => 'draft',
                'catatan' => $request->catatan
            ]);

            // Create transfer details
            $detailCount = count($request->produk_id);
            \Log::info("Processing {$detailCount} transfer detail items");

            for ($i = 0; $i < $detailCount; $i++) {
                // Validate each array index exists
                if (!isset($request->produk_id[$i]) || !isset($request->quantity[$i]) || !isset($request->satuan_id[$i])) {
                    \Log::error("Missing required data at index {$i}");
                    throw new \Exception("Data produk tidak lengkap pada baris " . ($i + 1));
                }

                // Log the detail item we're about to create
                \Log::info("Creating transfer detail {$i}:", [
                    'produk_id' => $request->produk_id[$i],
                    'quantity' => $request->quantity[$i],
                    'satuan_id' => $request->satuan_id[$i],
                ]);

                // Create with safe access to keterangan
                $keterangan = null;
                if (is_array($request->keterangan) && array_key_exists($i, $request->keterangan)) {
                    $keterangan = $request->keterangan[$i];
                }

                TransferBarangDetail::create([
                    'transfer_id' => $transfer->id,
                    'produk_id' => $request->produk_id[$i],
                    'quantity' => $request->quantity[$i],
                    'satuan_id' => $request->satuan_id[$i],
                    'keterangan' => $keterangan
                ]);
            }

            // Add activity log for transfer creation
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Membuat transfer barang baru',
                'modul' => 'Transfer Gudang',
                'data_id' => $transfer->id,
                'ip_address' => request()->ip(),
                'detail' => "Membuat transfer barang dengan nomor {$transfer->nomor} dari gudang {$transfer->gudangAsal->nama} ke gudang {$transfer->gudangTujuan->nama}"
            ]);

            DB::commit();

            return redirect()->route('inventaris.transfer-gudang.show', $transfer->id)
                ->with('success', 'Transfer barang berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $breadcrumbs = [
            ['name' => 'Inventaris', 'url' => '#'],
            ['name' => 'Transfer Antar Gudang', 'url' => route('inventaris.transfer-gudang.index')],
            ['name' => 'Detail Transfer', 'url' => '#']
        ];
        $currentPage = 'Detail Transfer Barang';

        $transfer = TransferBarang::with(['gudangAsal', 'gudangTujuan', 'user', 'details.produk', 'details.satuan'])
            ->findOrFail($id);

        // Get log activities related to this transfer
        $logActivities = LogAktivitas::where('modul', 'Transfer Gudang')
            ->where('data_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Group activities by status
        $logsByStatus = [
            'draft' => $logActivities->where('aktivitas', 'Membuat transfer barang baru')->first(),
            'diproses' => $logActivities->where('aktivitas', 'Memproses transfer barang')->first(),
            'selesai' => $logActivities->where('aktivitas', 'Menyelesaikan transfer barang')->first(),
        ];

        return view('inventaris.transfer_gudang.show', compact('transfer', 'breadcrumbs', 'currentPage', 'logsByStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $breadcrumbs = [
            ['name' => 'Inventaris', 'url' => '#'],
            ['name' => 'Transfer Antar Gudang', 'url' => route('inventaris.transfer-gudang.index')],
            ['name' => 'Edit Transfer', 'url' => '#']
        ];
        $currentPage = 'Edit Transfer Barang';

        $transfer = TransferBarang::with(['gudangAsal', 'gudangTujuan', 'user', 'details.produk', 'details.satuan'])
            ->findOrFail($id);

        // Can only edit in draft status
        if ($transfer->status !== 'draft') {
            return redirect()->route('inventaris.transfer-gudang.show', $transfer->id)
                ->with('error', 'Tidak dapat mengedit transfer yang sudah diproses atau selesai!');
        }

        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();
        $satuans = Satuan::all();

        // Fetch all stok produk for the form, similar to create method
        \Log::info('Fetching product stock data for transfer edit form');

        $allStokProduk = StokProduk::with(['produk', 'produk.satuan'])
            ->where('jumlah', '>', 0)
            ->whereHas('produk', function ($query) {
                $query->whereNotNull('kode')  // Ensure product has a code
                    ->whereNotNull('nama'); // Ensure product has a name
            })
            ->get();

        \Log::info('Found ' . $allStokProduk->count() . ' stock records with positive quantities');

        $mappedStokProduk = $allStokProduk->map(function ($stok) {
            try {
                // Double-check that produk exists to avoid null access
                if (!$stok->produk) {
                    \Log::warning("Skipping stock record with missing product relation. StokProduk ID: " . $stok->id);
                    return null;
                }

                // Make sure we have valid data before creating the array
                $produk = $stok->produk;

                // Get satuan information safely
                $satuan = $produk->satuan;
                $satuanNama = ($satuan && isset($satuan->nama)) ? $satuan->nama : '-';
                $satuanId = $produk->satuan_id ?? null;

                $result = [
                    'produk_id' => $stok->produk_id,
                    'gudang_id' => $stok->gudang_id,
                    'nama' => $produk->kode . ' - ' . $produk->nama,
                    'stok' => (float)$stok->jumlah, // Ensure stok is a number
                    'satuan_id' => $satuanId,
                    'satuan_nama' => $satuanNama,
                ];

                return $result;
            } catch (\Exception $e) {
                \Log::error("Error mapping stock product ID {$stok->id}: " . $e->getMessage());
                return null;
            }
        })
            ->filter() // Remove any null entries
            ->values(); // Re-index array

        $allStokProduk = $mappedStokProduk;

        return view('inventaris.transfer_gudang.edit', compact('transfer', 'gudangs', 'satuans', 'breadcrumbs', 'currentPage', 'allStokProduk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $transfer = TransferBarang::findOrFail($id);

        // Can only update in draft status
        if ($transfer->status !== 'draft') {
            return redirect()->route('inventaris.transfer-gudang.show', $transfer->id)
                ->with('error', 'Tidak dapat mengubah transfer yang sudah diproses atau selesai!');
        }

        $request->validate([
            'nomor' => 'required|string|unique:transfer_barang,nomor,' . $id,
            'tanggal' => 'required|date',
            'gudang_asal_id' => 'required|exists:gudang,id',
            'gudang_tujuan_id' => 'required|exists:gudang,id|different:gudang_asal_id',
            'catatan' => 'nullable|string',
            'produk_id' => 'required|array|min:1',
            'produk_id.*' => 'required|exists:produk,id',
            'quantity.*' => 'required|numeric|min:0.01',
            'satuan_id.*' => 'required|exists:satuan,id',
            'keterangan.*' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $transfer->update([
                'nomor' => $request->nomor,
                'tanggal' => $request->tanggal,
                'gudang_asal_id' => $request->gudang_asal_id,
                'gudang_tujuan_id' => $request->gudang_tujuan_id,
                'catatan' => $request->catatan
            ]);

            // Verify that arrays are valid and have the same length before deleting details
            if (!is_array($request->produk_id)) {
                \Log::error('produk_id bukan array pada update', ['produk_id' => $request->produk_id]);
                throw new \Exception("Data produk tidak valid: produk_id bukan array.");
            }

            if (!is_array($request->quantity)) {
                \Log::error('quantity bukan array pada update', ['quantity' => $request->quantity]);
                throw new \Exception("Data produk tidak valid: quantity bukan array.");
            }

            if (!is_array($request->satuan_id)) {
                \Log::error('satuan_id bukan array pada update', ['satuan_id' => $request->satuan_id]);
                throw new \Exception("Data produk tidak valid: satuan_id bukan array.");
            }

            if (count($request->produk_id) !== count($request->quantity)) {
                \Log::error('Jumlah produk_id dan quantity tidak sama pada update', [
                    'produk_id_count' => count($request->produk_id),
                    'quantity_count' => count($request->quantity)
                ]);
                throw new \Exception("Data produk tidak valid: jumlah produk dan kuantitas tidak sesuai.");
            }

            if (count($request->produk_id) !== count($request->satuan_id)) {
                \Log::error('Jumlah produk_id dan satuan_id tidak sama pada update', [
                    'produk_id_count' => count($request->produk_id),
                    'satuan_id_count' => count($request->satuan_id)
                ]);
                throw new \Exception("Data produk tidak valid: jumlah produk dan satuan tidak sesuai.");
            }

            // Log request details for debugging
            \Log::info('Detail data request update transfer:', [
                'produk_id' => $request->produk_id,
                'quantity' => $request->quantity,
                'satuan_id' => $request->satuan_id,
                'keterangan' => $request->keterangan ?? []
            ]);

            // Delete old details and create new ones
            $transfer->details()->delete();

            $detailCount = count($request->produk_id);
            \Log::info("Updating transfer: Processing {$detailCount} detail items");

            for ($i = 0; $i < $detailCount; $i++) {
                // Validate each array index exists
                if (!isset($request->produk_id[$i]) || !isset($request->quantity[$i]) || !isset($request->satuan_id[$i])) {
                    \Log::error("Missing required data at index {$i} during update");
                    throw new \Exception("Data produk tidak lengkap pada baris " . ($i + 1));
                }

                // Log the detail item we're about to create in update
                \Log::info("Updating transfer detail {$i}:", [
                    'produk_id' => $request->produk_id[$i],
                    'quantity' => $request->quantity[$i],
                    'satuan_id' => $request->satuan_id[$i],
                ]);

                // Create with safe access to keterangan
                $keterangan = null;
                if (is_array($request->keterangan) && array_key_exists($i, $request->keterangan)) {
                    $keterangan = $request->keterangan[$i];
                }

                TransferBarangDetail::create([
                    'transfer_id' => $transfer->id,
                    'produk_id' => $request->produk_id[$i],
                    'quantity' => $request->quantity[$i],
                    'satuan_id' => $request->satuan_id[$i],
                    'keterangan' => $keterangan
                ]);
            }

            // Add activity log for transfer update
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Memperbarui transfer barang',
                'modul' => 'Transfer Gudang',
                'data_id' => $transfer->id,
                'ip_address' => request()->ip(),
                'detail' => "Memperbarui transfer barang dengan nomor {$transfer->nomor} dari gudang {$transfer->gudangAsal->nama} ke gudang {$transfer->gudangTujuan->nama}"
            ]);

            DB::commit();

            return redirect()->route('inventaris.transfer-gudang.show', $transfer->id)
                ->with('success', 'Transfer barang berhasil diperbarui!');
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
        $transfer = TransferBarang::findOrFail($id);

        // Can only delete in draft status
        if ($transfer->status !== 'draft') {
            return redirect()->route('inventaris.transfer-gudang.index')
                ->with('error', 'Tidak dapat menghapus transfer yang sudah diproses atau selesai!');
        }

        try {
            DB::beginTransaction();

            // Delete details first
            $transfer->details()->delete();

            // Capture transfer details before deletion for logging
            $transferInfo = [
                'nomor' => $transfer->nomor,
                'gudang_asal' => $transfer->gudangAsal->nama,
                'gudang_tujuan' => $transfer->gudangTujuan->nama
            ];

            // Delete the transfer
            $transfer->delete();

            // Add activity log for transfer deletion
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Menghapus transfer barang',
                'modul' => 'Transfer Gudang',
                'data_id' => $id,
                'ip_address' => request()->ip(),
                'detail' => "Menghapus transfer barang dengan nomor {$transferInfo['nomor']} dari gudang {$transferInfo['gudang_asal']} ke gudang {$transferInfo['gudang_tujuan']}"
            ]);

            DB::commit();

            return redirect()->route('inventaris.transfer-gudang.index')
                ->with('success', 'Transfer barang berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Process the transfer - deduct stock from source warehouse.
     */
    public function prosesTransfer($id)
    {
        $transfer = TransferBarang::with(['details.produk', 'details.satuan'])->findOrFail($id);

        // Can only process in draft status
        if ($transfer->status !== 'draft') {
            return redirect()->route('inventaris.transfer-gudang.show', $transfer->id)
                ->with('error', 'Transfer ini tidak dapat diproses karena sudah dalam status ' . $transfer->status);
        }

        try {
            DB::beginTransaction();

            // Check stock availability in source warehouse
            foreach ($transfer->details as $detail) {
                $stokAsal = StokProduk::where('gudang_id', $transfer->gudang_asal_id)
                    ->where('produk_id', $detail->produk_id)
                    ->first();

                if (!$stokAsal || $stokAsal->jumlah < $detail->quantity) {
                    throw new \Exception("Stok produk {$detail->produk->nama} tidak mencukupi di gudang asal.");
                }
            }

            // Deduct stock from source warehouse
            foreach ($transfer->details as $detail) {
                $stokAsal = StokProduk::where('gudang_id', $transfer->gudang_asal_id)
                    ->where('produk_id', $detail->produk_id)
                    ->first();

                $stokAsal->jumlah -= $detail->quantity;
                $stokAsal->save();

                // Create stock history for deduction
                RiwayatStok::create([
                    'stok_id' => $stokAsal->id,
                    'produk_id' => $detail->produk_id,
                    'gudang_id' => $transfer->gudang_asal_id,
                    'jumlah_sebelum' => $stokAsal->jumlah + $detail->quantity,
                    'jumlah_perubahan' => -$detail->quantity,
                    'jumlah_setelah' => $stokAsal->jumlah,
                    'jenis' => 'keluar',
                    'referensi_tipe' => 'transfer_barang',
                    'referensi_id' => $transfer->id,
                    'tanggal' => now(),
                    'keterangan' => "Pengurangan dari transfer #{$transfer->nomor}",
                    'user_id' => Auth::id()
                ]);
            }

            // Update transfer status
            $transfer->status = 'diproses';
            $transfer->save();

            // Add activity log for transfer status change to "diproses"
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Memproses transfer barang',
                'modul' => 'Transfer Gudang',
                'data_id' => $transfer->id,
                'ip_address' => request()->ip(),
                'detail' => "Memproses transfer barang #{$transfer->nomor} dari gudang {$transfer->gudangAsal->nama} ke gudang {$transfer->gudangTujuan->nama}. Stok dikurangi dari gudang asal."
            ]);

            DB::commit();

            return redirect()->route('inventaris.transfer-gudang.show', $transfer->id)
                ->with('success', 'Transfer barang sedang diproses. Stok di gudang asal telah dikurangi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Complete the transfer - add stock to destination warehouse.
     */
    public function selesaikanTransfer($id)
    {
        $transfer = TransferBarang::with(['details.produk', 'details.satuan'])->findOrFail($id);

        // Can only complete in 'diproses' status
        if ($transfer->status !== 'diproses') {
            return redirect()->route('inventaris.transfer-gudang.show', $transfer->id)
                ->with('error', 'Transfer ini tidak dapat diselesaikan karena statusnya ' . $transfer->status);
        }

        try {
            DB::beginTransaction();

            // Add stock to destination warehouse
            foreach ($transfer->details as $detail) {
                // Check if stock record exists for this product in destination warehouse
                $stokTujuan = StokProduk::firstOrNew([
                    'gudang_id' => $transfer->gudang_tujuan_id,
                    'produk_id' => $detail->produk_id
                ]);

                $jumlahSebelum = $stokTujuan->jumlah ?? 0;
                $stokTujuan->jumlah = ($stokTujuan->jumlah ?? 0) + $detail->quantity;
                $stokTujuan->lokasi_rak = $stokTujuan->lokasi_rak ?? '-';
                $stokTujuan->batch_number = $stokTujuan->batch_number ?? '-';
                $stokTujuan->save();

                // Create stock history for addition
                RiwayatStok::create([
                    'stok_id' => $stokTujuan->id,
                    'produk_id' => $detail->produk_id,
                    'gudang_id' => $transfer->gudang_tujuan_id,
                    'jumlah_sebelum' => $jumlahSebelum,
                    'jumlah_perubahan' => $detail->quantity,
                    'jumlah_setelah' => $stokTujuan->jumlah,
                    'jenis' => 'masuk',
                    'referensi_tipe' => 'transfer_barang',
                    'referensi_id' => $transfer->id,
                    'tanggal' => now(),
                    'keterangan' => "Penambahan dari transfer #{$transfer->nomor}",
                    'user_id' => Auth::id()
                ]);
            }

            // Update transfer status
            $transfer->status = 'selesai';
            $transfer->save();

            // Add activity log for transfer status change to "selesai"
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Menyelesaikan transfer barang',
                'modul' => 'Transfer Gudang',
                'data_id' => $transfer->id,
                'ip_address' => request()->ip(),
                'detail' => "Menyelesaikan transfer barang #{$transfer->nomor} dari gudang {$transfer->gudangAsal->nama} ke gudang {$transfer->gudangTujuan->nama}. Stok ditambahkan ke gudang tujuan."
            ]);

            DB::commit();

            return redirect()->route('inventaris.transfer-gudang.show', $transfer->id)
                ->with('success', 'Transfer barang telah selesai. Stok di gudang tujuan telah ditambahkan.');
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
                'gudang_id' => 'required|exists:gudang,id'
            ]);

            $produkId = $request->produk_id;
            $gudangId = $request->gudang_id;

            \Log::info("Getting stock for Product ID: $produkId in Warehouse ID: $gudangId");

            // Find the product stock record
            $stok = StokProduk::where('produk_id', $produkId)
                ->where('gudang_id', $gudangId)
                ->first();

            if ($stok) {
                \Log::info("Found stock record: " . json_encode([
                    'id' => $stok->id,
                    'jumlah' => $stok->jumlah,
                    'produk_id' => $stok->produk_id,
                    'gudang_id' => $stok->gudang_id
                ]));
            } else {
                \Log::warning("No stock record found for product $produkId in warehouse $gudangId");
            }

            // Get the product with eager loaded satuan relationship
            $produk = Produk::with(['satuan' => function ($query) {
                $query->select('id', 'nama');
            }])->select('id', 'nama', 'kode', 'satuan_id')->find($produkId);

            // Check if product exists
            if (!$produk) {
                \Log::error("Product with ID $produkId not found");
                return response()->json([
                    'error' => 'Produk tidak ditemukan'
                ], 404);
            }

            // Get satuan information safely
            $satuan = $produk->satuan;
            $satuanNama = ($satuan && isset($satuan->nama)) ? $satuan->nama : '-';
            $satuanId = $produk->satuan_id ?? null;

            // Convert stock to float to ensure it's a number
            $stokJumlah = $stok ? (float)$stok->jumlah : 0;

            $responseData = [
                'stok' => $stokJumlah,
                'satuan' => $satuanNama,
                'satuan_id' => $satuanId,
                'produk_nama' => $produk->nama,
                'produk_kode' => $produk->kode
            ];

            \Log::info("Returning stock data: " . json_encode($responseData));

            return response()->json($responseData);
        } catch (\Exception $e) {
            \Log::error('Error in getStokProduk: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data stok produk: ' . $e->getMessage()
            ], 500);
        }
    }
}
