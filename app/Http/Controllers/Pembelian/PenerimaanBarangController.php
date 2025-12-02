<?php

namespace App\Http\Controllers\Pembelian;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pembelian\helpers\PaginationHelper;
use App\Models\PurchaseOrder;
use App\Models\PenerimaanBarang;
use App\Models\PenerimaanBarangDetail;
use App\Models\Gudang;
use App\Models\Supplier;
use App\Models\StokProduk;
use App\Models\RiwayatStok;
use App\Models\LogAktivitas;
use App\Models\JurnalUmum;
use App\Models\AkunAkuntansi;
use App\Models\AccountingConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenerimaanBarangController extends Controller
{
    /**
     * Helper untuk mencatat log aktivitas user
     */
    private function logUserAktivitas($aktivitas, $modul, $data_id = null, $detail = null)
    {
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => $aktivitas,
            'modul' => $modul,
            'data_id' => $data_id,
            'ip_address' => request()->ip(),
            'detail' => $detail ? (is_array($detail) ? json_encode($detail) : $detail) : null,
        ]);
    }

    /**
     * Tampilkan daftar penerimaan barang
     */
    public function index(Request $request)
    {
        $query = PenerimaanBarang::with(['purchaseOrder', 'supplier', 'gudang', 'user']);

        // --- Bagian Filter dan Sorting --- 
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status', 'semua'), // Default to 'semua' (all statuses)
            'date_filter' => $request->input('date_filter'),
            'date_start' => $request->input('date_start'),
            'date_end' => $request->input('date_end'),
            'supplier_id' => $request->input('supplier_id'),
            'purchase_order_id' => $request->input('purchase_order_id'),
            'gudang_id' => $request->input('gudang_id'),
            'sort_by' => $request->input('sort_by', 'tanggal'), // Default to tanggal
            'sort_direction' => $request->input('sort_direction', 'desc'), // Default to desc (newest first)
        ];

        if ($filters['search']) {
            $query->where(function ($q) use ($filters) {
                $q->where('nomor', 'like', '%' . $filters['search'] . '%')
                    ->orWhereHas('purchaseOrder', function ($poQuery) use ($filters) {
                        $poQuery->where('nomor', 'like', '%' . $filters['search'] . '%');
                    })
                    ->orWhereHas('supplier', function ($sQuery) use ($filters) {
                        $sQuery->where('nama', 'like', '%' . $filters['search'] . '%');
                    });
            });
        }
        // Only filter by status if it's not 'semua' (all)
        if ($filters['status'] && $filters['status'] !== 'semua') {
            $query->where('penerimaan_barang.status', $filters['status']);
        }
        if ($filters['supplier_id']) {
            $query->where('supplier_id', $filters['supplier_id']);
        }
        if ($filters['gudang_id']) {
            $query->where('gudang_id', $filters['gudang_id']);
        }
        if ($filters['purchase_order_id']) {
            $query->whereHas('purchaseOrder', function ($poQuery) use ($filters) {
                $poQuery->where('nomor', 'like', '%' . $filters['purchase_order_id'] . '%');
            });
        }

        // Implement date filtering logic
        if ($filters['date_filter']) {
            switch ($filters['date_filter']) {
                case 'today':
                    $query->whereDate('tanggal', today());
                    break;
                case 'yesterday':
                    $query->whereDate('tanggal', today()->subDay());
                    break;
                case 'this_week':
                    $startOfWeek = now()->startOfWeek();
                    $endOfWeek = now()->endOfWeek();
                    $query->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);
                    break;
                case 'last_week':
                    $startOfWeek = now()->subWeek()->startOfWeek();
                    $endOfWeek = now()->subWeek()->endOfWeek();
                    $query->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);
                    break;
                case 'this_month':
                    $startOfMonth = now()->startOfMonth();
                    $endOfMonth = now()->endOfMonth();
                    $query->whereBetween('tanggal', [$startOfMonth, $endOfMonth]);
                    break;
                case 'last_month':
                    $startOfMonth = now()->subMonth()->startOfMonth();
                    $endOfMonth = now()->subMonth()->endOfMonth();
                    $query->whereBetween('tanggal', [$startOfMonth, $endOfMonth]);
                    break;
                case 'range':
                    if ($filters['date_start'] && $filters['date_end']) {
                        $query->whereBetween('tanggal', [$filters['date_start'], $filters['date_end']]);
                    } elseif ($filters['date_start']) {
                        $query->whereDate('tanggal', '>=', $filters['date_start']);
                    } elseif ($filters['date_end']) {
                        $query->whereDate('tanggal', '<=', $filters['date_end']);
                    }
                    break;
            }
        }

        // Handle column sorting
        switch ($filters['sort_by']) {
            case 'nomor':
                $query->orderBy('nomor', $filters['sort_direction']);
                break;
            case 'tanggal':
                $query->orderBy('tanggal', $filters['sort_direction']);
                break;
            case 'supplier':
                $query->join('supplier', 'penerimaan_barang.supplier_id', '=', 'supplier.id')
                    ->orderBy('supplier.nama', $filters['sort_direction'])
                    ->select('penerimaan_barang.*');
                break;
            case 'gudang':
                $query->join('gudang', 'penerimaan_barang.gudang_id', '=', 'gudang.id')
                    ->orderBy('gudang.nama', $filters['sort_direction'])
                    ->select('penerimaan_barang.*');
                break;
            case 'po_number':
                $query->join('purchase_order', 'penerimaan_barang.po_id', '=', 'purchase_order.id')
                    ->orderBy('purchase_order.nomor', $filters['sort_direction'])
                    ->select('penerimaan_barang.*');
                break;
            case 'status':
                $query->orderBy('status', $filters['sort_direction']);
                break;
            default:
                $query->orderBy('tanggal', 'desc');
                break;
        }

        $per_page = $request->input('per_page', 15);
        $penerimaanBarangs = $query->paginate($per_page)->appends($filters);

        if ($request->ajax()) {
            try {
                // Render table HTML
                $tableHtml = view('pembelian.Penerimaan_barang._table', [
                    'penerimaanBarangs' => $penerimaanBarangs,
                    'filters' => $filters
                ])->render();

                // Try to render pagination, but provide fallback if it fails
                $paginationHtml = '';
                try {
                    // Use the custom pagination template directly
                    $paginationHtml = $penerimaanBarangs->links('vendor.pagination.tailwind-custom')->toHtml();
                } catch (\Exception $paginationException) {
                    // If pagination view fails, create a simple fallback pagination
                    try {
                        // First try our controller method
                        $paginationHtml = $this->generateSimplePagination($penerimaanBarangs);
                    } catch (\Exception $e) {
                        // If that fails too, try the helper class
                        try {
                            if (class_exists('\\App\\Http\\Controllers\\Pembelian\\helpers\\PaginationHelper')) {
                                $paginationHtml = \App\Http\Controllers\Pembelian\helpers\PaginationHelper::generateSimplePagination($penerimaanBarangs);
                            } else {
                                // Ultimate fallback - very minimal pagination
                                $paginationHtml = '<div class="alert alert-warning">Error rendering pagination</div>';
                            }
                        } catch (\Exception $helperEx) {
                            $paginationHtml = '<div class="alert alert-warning">Pagination unavailable</div>';
                        }
                    }

                    // Log pagination error
                    \Illuminate\Support\Facades\Log::error('Pagination rendering failed: ' . $paginationException->getMessage(), [
                        'exception' => $paginationException->getMessage(),
                        'trace' => $paginationException->getTraceAsString()
                    ]);
                }

                return response()->json([
                    'table_html' => $tableHtml,
                    'pagination_html' => $paginationHtml,
                ]);
            } catch (\Exception $e) {
                // Return a more graceful error response for AJAX requests
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessage(),
                    'table_html' => '<div class="p-4 text-center text-red-500">Error loading data: ' . $e->getMessage() . '</div>',
                    'pagination_html' => '',
                ], 500);
            }
        }

        // Data untuk view utama (non-AJAX)
        $statusCounts = PenerimaanBarang::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        /**
         * Generate a simple HTML pagination as a fallback when view rendering fails
         *
         * @param \Illuminate\Pagination\LengthAwarePaginator $paginator
         * @return string
         */
        $suppliers = Supplier::orderBy('nama')->get();
        $gudangs = Gudang::orderBy('nama')->get();

        return view('pembelian.Penerimaan_barang.index', array_merge([
            'penerimaanBarangs' => $penerimaanBarangs,
            'statusCounts' => $statusCounts,
            'suppliers' => $suppliers,
            'gudangs' => $gudangs,
        ], $filters));
    }

    /**
     * Create automatic journal entry for purchase receipt
     * 
     * Jurnal yang dibuat:
     * Debit: 50001 - Pembelian (untuk HPP)
     * Debit: 1130 - PPN Masukan (jika ada PPN)
     * Kredit: 2111 - Hutang Usaha
     */
    private function createJournalForPurchaseReceipt($penerimaan, $po)
    {
        try {
            // Get accounting configurations for PERPETUAL system
            $configPersediaan = AccountingConfiguration::where('transaction_type', 'pembelian')
                ->where('account_key', 'persediaan_pembelian')
                ->first();

            // Fallback to old 'pembelian' key if new key not found
            if (!$configPersediaan || !$configPersediaan->akun_id) {
                $configPersediaan = AccountingConfiguration::where('transaction_type', 'pembelian')
                    ->where('account_key', 'pembelian')
                    ->first();
            }

            $configHutang = AccountingConfiguration::where('transaction_type', 'pembelian')
                ->where('account_key', 'hutang_usaha')
                ->first();

            $configPPN = AccountingConfiguration::where('transaction_type', 'pembelian')
                ->where('account_key', 'ppn_masuk')
                ->first();

            // Validate required accounts
            if (!$configPersediaan || !$configPersediaan->akun_id) {
                Log::warning('Akun Persediaan/Pembelian tidak dikonfigurasi, skip auto-journal', [
                    'penerimaan_id' => $penerimaan->id
                ]);
                return;
            }

            if (!$configHutang || !$configHutang->akun_id) {
                Log::warning('Akun Hutang Usaha tidak dikonfigurasi, skip auto-journal', [
                    'penerimaan_id' => $penerimaan->id
                ]);
                return;
            }

            // Calculate total for this receipt
            $totalPembelian = 0;
            foreach ($penerimaan->details as $detail) {
                // Get harga from PO detail
                $poDetail = $po->details->where('id', $detail->po_detail_id)->first();
                if ($poDetail) {
                    $totalPembelian += $detail->quantity * $poDetail->harga;
                }
            }

            // Calculate PPN if applicable
            $totalPPN = 0;
            if ($po->ppn > 0) {
                // Calculate PPN proportional to received items
                $ppnPersentase = $po->ppn / 100;
                $totalPPN = $totalPembelian * $ppnPersentase;
            }

            $totalHutang = $totalPembelian + $totalPPN;

            // Generate journal reference number
            $noReferensi = 'GR-' . $penerimaan->nomor . '-' . date('YmdHis');

            $keterangan = "Penerimaan Barang {$penerimaan->nomor} dari PO {$po->nomor} - Supplier: " . ($po->supplier ? $po->supplier->nama : 'N/A');

            // Create journal entries
            $journalEntries = [];

            // 1. DEBIT: Persediaan (PERPETUAL SYSTEM)
            $journalEntries[] = [
                'tanggal' => $penerimaan->tanggal,
                'no_referensi' => $noReferensi,
                'akun_id' => $configPersediaan->akun_id,
                'debit' => $totalPembelian,
                'kredit' => 0,
                'keterangan' => $keterangan,
                'jenis_jurnal' => 'umum',
                'sumber' => 'penerimaan_barang',
                'ref_type' => 'App\Models\PenerimaanBarang',
                'ref_id' => $penerimaan->id,
                'user_id' => Auth::id(),
                'is_posted' => true,
                'posted_at' => now(),
                'posted_by' => Auth::id(),
            ];

            // 2. DEBIT: PPN Masukan (if applicable)
            if ($totalPPN > 0 && $configPPN && $configPPN->akun_id) {
                $journalEntries[] = [
                    'tanggal' => $penerimaan->tanggal,
                    'no_referensi' => $noReferensi,
                    'akun_id' => $configPPN->akun_id,
                    'debit' => $totalPPN,
                    'kredit' => 0,
                    'keterangan' => "PPN Masukan {$po->ppn}% - " . $keterangan,
                    'jenis_jurnal' => 'umum',
                    'sumber' => 'penerimaan_barang',
                    'ref_type' => 'App\Models\PenerimaanBarang',
                    'ref_id' => $penerimaan->id,
                    'user_id' => Auth::id(),
                    'is_posted' => true,
                    'posted_at' => now(),
                    'posted_by' => Auth::id(),
                ];
            }

            // 3. KREDIT: Hutang Usaha
            $journalEntries[] = [
                'tanggal' => $penerimaan->tanggal,
                'no_referensi' => $noReferensi,
                'akun_id' => $configHutang->akun_id,
                'debit' => 0,
                'kredit' => $totalHutang,
                'keterangan' => $keterangan,
                'jenis_jurnal' => 'umum',
                'sumber' => 'penerimaan_barang',
                'ref_type' => 'App\Models\PenerimaanBarang',
                'ref_id' => $penerimaan->id,
                'user_id' => Auth::id(),
                'is_posted' => true,
                'posted_at' => now(),
                'posted_by' => Auth::id(),
            ];

            // Insert all journal entries
            foreach ($journalEntries as $entry) {
                JurnalUmum::create($entry);
            }

            Log::info('Auto-journal created for purchase receipt', [
                'penerimaan_id' => $penerimaan->id,
                'no_referensi' => $noReferensi,
                'total_pembelian' => $totalPembelian,
                'total_ppn' => $totalPPN,
                'total_hutang' => $totalHutang
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create auto-journal for purchase receipt', [
                'penerimaan_id' => $penerimaan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Don't throw exception, just log it - transaction should continue
        }
    }

    /**
     * Tampilkan form penerimaan barang (pilih PO yang belum selesai)
     */
    public function create(Request $request)
    {
        $poQuery = PurchaseOrder::with(['supplier', 'details.produk'])
            ->where('status', '!=', 'selesai')
            ->where('status', '!=', 'draft')
            ->where('status', '!=', 'dibatalkan'); // Exclude canceled POs
        if ($request->filled('q')) {
            $poQuery->where('nomor', 'like', '%' . $request->q . '%');
        }
        $purchaseOrders = $poQuery->orderBy('created_at', 'desc')->limit(10)->get();
        $gudangs = Gudang::all();
        return view('pembelian.Penerimaan_barang.create', compact('purchaseOrders', 'gudangs'));
    }

    /**
     * Simpan penerimaan barang
     */
    public function store(Request $request)
    {
        $request->validate([
            'po_id' => 'required|exists:purchase_order,id',
            'gudang_id' => 'required|exists:gudang,id',
            'tanggal' => 'required|date',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:purchase_order_detail,id',
            'items.*.qty_diterima' => 'required|numeric|min:0',
            'items.*.lokasi_rak' => 'nullable|string|max:100', // Add validation for lokasi_rak
        ]);

        DB::beginTransaction();
        try {
            $po = PurchaseOrder::with('details')->findOrFail($request->po_id);

            // Generate nomor penerimaan barang otomatis
            $nomor = 'GR-' . date('Ymd') . '-' . str_pad((PenerimaanBarang::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);

            // Buat record penerimaan barang utama
            $penerimaan = PenerimaanBarang::create([
                'nomor' => $nomor,
                'tanggal' => $request->tanggal,
                'po_id' => $po->id,
                'supplier_id' => $po->supplier_id,
                'user_id' => \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::id() : null,
                'gudang_id' => $request->gudang_id,
                'nomor_surat_jalan' => $request->nomor_surat_jalan,
                'tanggal_surat_jalan' => $request->tanggal_surat_jalan,
                'catatan' => $request->catatan,
                'status' => 'parsial', // Default status
            ]);

            $anyItemReceived = false;
            $allItemsFullyReceived = true;  // Flag to check if all PO items are fully received

            foreach ($request->items as $item) {
                $poDetail = $po->details->where('id', $item['id'])->first();

                // Skip jika tidak ada barang yang diterima

                $anyItemReceived = true;
                $qtySisa = $poDetail->quantity - ($poDetail->quantity_diterima ?? 0);

                // Validasi: qty diterima tidak boleh melebihi sisa PO
                if ($item['qty_diterima'] > $qtySisa) {
                    throw new \Exception('Qty diterima tidak boleh melebihi sisa PO.');
                }

                // Buat record detail penerimaan barang (sesuaikan dengan field di database)
                PenerimaanBarangDetail::create([
                    'penerimaan_id' => $penerimaan->id,
                    'po_detail_id' => $poDetail->id,
                    'produk_id' => $poDetail->produk_id,
                    'nama_item' => $poDetail->produk ? $poDetail->produk->nama : ($poDetail->nama_item ?? 'Item #' . $poDetail->id),
                    'deskripsi' => $poDetail->deskripsi,
                    'quantity' => $item['qty_diterima'], // Gunakan field quantity sesuai model
                    'satuan_id' => $poDetail->satuan_id,
                    'keterangan' => $item['catatan'] ?? null, // Gunakan field keterangan sesuai model
                ]);

                // Update qty yang diterima di PO detail
                $poDetail->quantity_diterima = ($poDetail->quantity_diterima ?? 0) + $item['qty_diterima'];
                $poDetail->save();

                // Update stok gudang
                if ($poDetail->produk_id) {
                    $this->updateStokGudang(
                        $poDetail->produk_id,
                        $request->gudang_id,
                        $item['qty_diterima'],
                        $penerimaan->id,
                        $item['lokasi_rak'] ?? null
                    );
                }

                // Jika setelah penerimaan ini masih ada sisa yang belum diterima, maka tidak selesai
                if ($poDetail->quantity_diterima < $poDetail->quantity) {
                    $allItemsFullyReceived = false;
                }
            }

            // Validasi minimal satu item diterima
            if (!$anyItemReceived) {
                throw new \Exception('Minimal harus ada 1 item yang diterima');
            }

            // Update status penerimaan barang jika semua barang sudah diterima penuh
            if ($allItemsFullyReceived) {
                $penerimaan->status = 'selesai';
                $penerimaan->save();
            }

            // Update status PO berdasarkan penerimaan barang
            // Ensure $po is an instance of PurchaseOrder before passing
            if ($po instanceof PurchaseOrder) {
                $this->updatePurchaseOrderStatus($po);
            } else {
                // Log an error if $po is not the expected type
                \Illuminate\Support\Facades\Log::error('Expected PurchaseOrder instance but got ' . get_class($po), ['po_id' => $request->po_id]);
            }


            $this->logUserAktivitas('tambah', 'penerimaan_barang', $penerimaan->id, 'Membuat penerimaan barang: ' . $penerimaan->nomor);
            $this->logUserAktivitas('tambah', 'purchase_order', $po->id, 'Membuat penerimaan barang: ' . $penerimaan->nomor);

            // Create automatic journal entry for this purchase receipt
            // Load details relation for journal creation
            $penerimaan->load('details');
            $this->createJournalForPurchaseReceipt($penerimaan, $po);

            DB::commit();

            // Check if the request wants JSON response
            if ($request->ajax() || $request->expectsJson() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Penerimaan barang berhasil disimpan dengan nomor ' . $nomor,
                    'redirect' => route('pembelian.penerimaan-barang.index')
                ]);
            }

            return redirect()->route('pembelian.penerimaan-barang.index')
                ->with('success', 'Penerimaan barang berhasil disimpan dengan nomor ' . $nomor);
        } catch (\Exception $e) {
            DB::rollBack();

            // For AJAX requests, return JSON error
            if ($request->ajax() || $request->expectsJson() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Update status PO berdasarkan status penerimaan
     */
    private function updatePurchaseOrderStatus(PurchaseOrder $po)
    {
        $po->refresh(); // Refresh model untuk mendapatkan data terbaru

        $allReceived = true;
        $anyReceived = false;

        foreach ($po->details as $detail) {
            // Jika ada quantity yang belum diterima, berarti belum selesai
            if (($detail->quantity_diterima ?? 0) < $detail->quantity) {
                $allReceived = false;
            }

            // Jika ada quantity yang sudah diterima, berarti ada sebagian
            if (($detail->quantity_diterima ?? 0) > 0) {
                $anyReceived = true;
            }
        }

        if ($allReceived) {
            $po->status_penerimaan = 'diterima';
        } else if ($anyReceived) {
            $po->status_penerimaan = 'sebagian';
        } else {
            $po->status_penerimaan = 'belum_diterima';
        }

        // Jika selesai terima dan sudah bayar, maka PO selesai
        if ($po->status_penerimaan == 'diterima' && $po->status_pembayaran == 'lunas') {
            $po->status = 'selesai';
            $po->save();

            // Update harga beli rata-rata ketika PO selesai melalui penerimaan barang
            \App\Http\Controllers\Pembelian\PurchasingOrderController::updateHargaBeliTerbaruFromExternalController($po->id);
        } else if ($po->status != 'dibatalkan') {
            // Jika bukan dibatalkan, maka status jadi approved (proses)
            $po->status = 'dikirim';
            $po->save();
        } else {
            $po->save();
        }
    }

    /**
     * Update stok gudang dan catat history
     */
    private function updateStokGudang($produkId, $gudangId, $jumlahMasuk, $referensiId, $lokasiRak = null)
    {
        // Cari stok yang sudah ada atau buat baru
        $stok = StokProduk::firstOrNew([
            'produk_id' => $produkId,
            'gudang_id' => $gudangId
        ]);

        // Catat jumlah sebelumnya untuk history
        $jumlahSebelum = $stok->jumlah ?? 0;

        // Jika baru dibuat, isi nilai default
        if (!$stok->exists) {
            $stok->jumlah = 0;
            $stok->lokasi_rak = $lokasiRak ?? '-';
        }
        // Jika ada nilai lokasi rak baru, update
        else if ($lokasiRak) {
            $stok->lokasi_rak = $lokasiRak;
        }

        // Update jumlah stok
        $stok->jumlah += $jumlahMasuk;
        $stok->save();

        // Catat riwayat stok
        RiwayatStok::create([
            'stok_id' => $stok->id,
            'produk_id' => $produkId,
            'gudang_id' => $gudangId,
            'user_id' => \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::id() : null,
            'jumlah_sebelum' => $jumlahSebelum,
            'jumlah_perubahan' => $jumlahMasuk,
            'jumlah_setelah' => $stok->jumlah,
            'jenis' => 'masuk',
            'referensi_tipe' => 'penerimaan_barang',
            'referensi_id' => $referensiId,
            'keterangan' => 'Penerimaan barang dari Purchase Order'
        ]);
    }

    /**
     * Tampilkan detail penerimaan barang
     */
    public function show($id)
    {
        $penerimaan = PenerimaanBarang::with(['purchaseOrder', 'supplier', 'gudang', 'user', 'details.produk'])->findOrFail($id);
        return view('pembelian.Penerimaan_barang.show', compact('penerimaan'));
    }

    /**
     * Tampilkan form edit penerimaan barang
     */
    public function edit($id)
    {
        $penerimaan = PenerimaanBarang::with(['details.produk', 'purchaseOrder', 'gudang'])->findOrFail($id);
        $gudangs = Gudang::all();
        return view('pembelian.Penerimaan_barang.edit', compact('penerimaan', 'gudangs'));
    }

    /**
     * Update penerimaan barang
     */
    public function update(Request $request, $id)
    {
        $penerimaan = PenerimaanBarang::findOrFail($id);
        $request->validate([
            'gudang_id' => 'required|exists:gudang,id',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
        ]);
        $penerimaan->update([
            'gudang_id' => $request->gudang_id,
            'tanggal' => $request->tanggal,
            'catatan' => $request->catatan,
        ]);

        // Log aktivitas user - update penerimaan barang
        $this->logUserAktivitas(
            'Mengupdate penerimaan barang',
            'Penerimaan Barang',
            $penerimaan->id,
            'Nomor: ' . $penerimaan->nomor
        );

        return redirect()->route('pembelian.penerimaan-barang.index')->with('success', 'Data berhasil diupdate.');
    }

    /**
     * Hapus penerimaan barang
     */
    public function destroy($id)
    {
        $penerimaan = PenerimaanBarang::findOrFail($id);
        $nomor = $penerimaan->nomor; // Save the number before deleting

        // Log aktivitas user - hapus penerimaan barang
        $this->logUserAktivitas(
            'Menghapus penerimaan barang',
            'Penerimaan Barang',
            $id,
            'Nomor: ' . $nomor
        );

        $penerimaan->delete();
        return redirect()->route('pembelian.penerimaan-barang.index')->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Generate a simple HTML pagination as a fallback when view rendering fails
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $paginator
     * @return string
     */
    private function generateSimplePagination($paginator)
    {
        if (!$paginator->hasPages()) {
            return '';
        }

        $current = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $urlPrefix = $paginator->path() . '?page=';

        // Preserve existing query parameters
        $existingQuery = request()->query();
        unset($existingQuery['page']);
        $queryString = http_build_query($existingQuery);
        $separator = empty($queryString) ? '' : '&';

        // Build pagination HTML
        $html = '<div class="mt-4 flex justify-between items-center">';
        $html .= '<div class="text-sm text-gray-600">Showing page ' . $current . ' of ' . $lastPage . ' (' . $paginator->total() . ' records)</div>';
        $html .= '<div class="flex space-x-2">';

        // Previous button
        if ($current > 1) {
            $html .= '<a href="' . $urlPrefix . ($current - 1) . $separator . $queryString . '" class="px-3 py-1 bg-white border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">Previous</a>';
        } else {
            $html .= '<span class="px-3 py-1 bg-gray-100 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed">Previous</span>';
        }

        // Next button
        if ($current < $lastPage) {
            $html .= '<a href="' . $urlPrefix . ($current + 1) . $separator . $queryString . '" class="px-3 py-1 bg-white border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">Next</a>';
        } else {
            $html .= '<span class="px-3 py-1 bg-gray-100 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed">Next</span>';
        }

        $html .= '</div></div>';

        return $html;
    }
}
