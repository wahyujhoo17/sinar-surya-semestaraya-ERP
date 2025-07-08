<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\PermintaanBarang;
use App\Models\PermintaanBarangDetail;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\Customer;
use App\Models\Gudang;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\StokProduk;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PermintaanBarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permintaan_barang.view')->only(['index', 'show']);
        $this->middleware('permission:permintaan_barang.create')->only(['create', 'store', 'generateFromSalesOrder', 'autoProsesFromSalesOrder']);
        $this->middleware('permission:permintaan_barang.edit')->only(['edit', 'update', 'updateStatus']);
        $this->middleware('permission:permintaan_barang.delete')->only(['destroy']);
        $this->middleware('permission:permintaan_barang.view')->only(['createDeliveryOrder']);
    }
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
     * Generate nomor permintaan barang baru
     */
    private function generateNomorPermintaan()
    {
        $prefix = 'PB-';
        $date = now()->format('Ymd');

        $lastNumber = DB::table('permintaan_barang')
            ->where('nomor', 'like', $prefix . $date . '-%')
            ->selectRaw('MAX(CAST(SUBSTRING(nomor FROM ' . (strlen($prefix . $date . '-') + 1) . ') AS INTEGER)) as last_num')
            ->first();

        $newNumberSuffix = '001';
        if ($lastNumber && !is_null($lastNumber->last_num)) {
            $newNumberSuffix = str_pad($lastNumber->last_num + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . $date . '-' . $newNumberSuffix;
    }

    /**
     * Display a listing of permintaan barang.
     */
    public function index(Request $request)
    {
        $query = PermintaanBarang::with(['salesOrder', 'customer', 'gudang', 'user']);

        // Filter dan sorting
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhereHas('salesOrder', function ($soQuery) use ($search) {
                        $soQuery->where('nomor', 'like', "%{$search}%");
                    })
                    ->orWhereHas('customer', function ($custQuery) use ($search) {
                        $custQuery->where('nama', 'like', "%{$search}%")
                            ->orWhere('company', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('sales_order_id')) {
            $query->where('sales_order_id', $request->sales_order_id);
        }

        if ($request->filled('gudang_id')) {
            $query->where('gudang_id', $request->gudang_id);
        }

        // Date filtering
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        $permintaanBarang = $query->orderBy($request->get('sort', 'tanggal'), $request->get('direction', 'desc'))
            ->paginate(10)
            ->withQueryString();

        // Data untuk filter dropdown
        $salesOrders = SalesOrder::whereIn('status_pengiriman', ['belum_dikirim', 'sebagian', 'selesai'])
            ->orderBy('tanggal', 'desc')
            ->get();
        $gudangs = Gudang::orderBy('nama', 'asc')->get();

        // Cek sales order yang belum memiliki permintaan barang
        $pendingSalesOrders = [];
        if (!$request->filled('hide_pending')) {
            $pendingSalesOrders = $this->getPendingSalesOrders();
        }
        // dd($pendingSalesOrders);

        if ($request->ajax()) {
            return response()->json([
                'table_html' => view('inventaris.permintaan_barang._table', compact('permintaanBarang'))->render(),
                'pagination_html' => $permintaanBarang->links()->toHtml(),
            ]);
        }

        return view('inventaris.permintaan_barang.index', compact(
            'permintaanBarang',
            'salesOrders',
            'gudangs',
            'pendingSalesOrders'
        ));
    }

    /**
     * Mendapatkan daftar sales order yang belum memiliki permintaan barang
     */
    private function getPendingSalesOrders()
    {
        // Ambil sales order yang sudah disetujui, belum selesai pengirimannya, dan belum memiliki permintaan barang

        // Ambil hanya sales_order_id yang tidak null
        $processedSalesOrderIds = PermintaanBarang::whereIn('status', ['menunggu', 'diproses', 'selesai'])
            ->whereNotNull('sales_order_id')
            ->pluck('sales_order_id')
            ->toArray();



        $salesOrders = SalesOrder::with(['details.produk', 'customer'])
            ->whereIn('status_pengiriman', ['belum_dikirim', 'sebagian'])
            ->whereNotIn('id', $processedSalesOrderIds)
            ->orderBy('tanggal', 'desc')
            ->get();

        // Debug log jika sales order baru tidak ditemukan
        if ($salesOrders->isEmpty()) {
            \Log::info('getPendingSalesOrders: Tidak ada sales order yang memenuhi filter', [
                'processedSalesOrderIds' => $processedSalesOrderIds,
                'all_sales_orders' => SalesOrder::pluck('id', 'nomor')->toArray(),
            ]);
        }


        return $salesOrders->map(function ($salesOrder) {
            // Filter to only include items that need processing (physical products with remaining quantity)
            $validProducts = $salesOrder->details->filter(function ($detail) {
                // Only include physical products (not services)
                if ($detail->produk->tipe === 'jasa') {
                    return false;
                }

                // Check if there's quantity remaining to be shipped
                return ($detail->quantity - $detail->quantity_terkirim) > 0;
            });

            if ($validProducts->count() > 0) {
                return [
                    'id' => $salesOrder->id,
                    'nomor' => $salesOrder->nomor,
                    'tanggal' => $salesOrder->tanggal,
                    'customer_nama' => $salesOrder->customer->nama ?? $salesOrder->customer->company,
                    'jumlah_item' => $validProducts->count(),
                    'status_pengiriman' => $salesOrder->status_pengiriman,
                ];
            }
            return null;
        })
            ->filter() // Remove null values
            ->values(); // Reset array indexes
    }

    /**
     * Auto-generate permintaan barang dari sales order.
     */
    public function generateFromSalesOrder(Request $request)
    {
        $request->validate([
            'sales_order_id' => 'required|exists:sales_order,id',
            'gudang_id' => 'required|exists:gudang,id',
        ]);

        try {
            DB::beginTransaction();

            $salesOrder = SalesOrder::with(['details.produk', 'customer'])->findOrFail($request->sales_order_id);

            // Periksa apakah sudah ada permintaan barang yang belum selesai untuk sales order ini
            $existingPermintaan = PermintaanBarang::where('sales_order_id', $salesOrder->id)
                ->whereIn('status', ['menunggu', 'diproses'])
                ->first();

            if ($existingPermintaan) {
                return redirect()->route('inventaris.permintaan-barang.show', $existingPermintaan->id)
                    ->with('warning', 'Permintaan barang untuk Sales Order ini sudah ada dan sedang diproses.');
            }

            // Buat permintaan barang baru
            $permintaanBarang = PermintaanBarang::create([
                'nomor' => $this->generateNomorPermintaan(),
                'tanggal' => now(),
                'sales_order_id' => $salesOrder->id,
                'customer_id' => $salesOrder->customer_id,
                'user_id' => Auth::id(),
                'gudang_id' => $request->gudang_id,
                'status' => 'menunggu',
                'catatan' => 'Auto-generated dari Sales Order: ' . $salesOrder->nomor .
                    ($salesOrder->status_pengiriman === 'sebagian' ? ' (sisa pengiriman)' : ''),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Buat detail permintaan barang untuk setiap item di sales order
            foreach ($salesOrder->details as $soDetail) {
                // Lewati jika tipe produk adalah jasa
                if ($soDetail->produk->tipe === 'jasa') {
                    continue;
                }

                // Hitung sisa jumlah yang perlu dikirim
                $remainingQty = $soDetail->quantity - $soDetail->quantity_terkirim;

                // Lewati jika sudah tidak ada sisa yang perlu dikirim
                if ($remainingQty <= 0) {
                    continue;
                }

                // Cek stok tersedia di gudang
                $stokTersedia = StokProduk::where('produk_id', $soDetail->produk_id)
                    ->where('gudang_id', $request->gudang_id)
                    ->value('jumlah') ?? 0;

                // Ensure remaining quantity is not null
                $safeRemainingQty = $remainingQty > 0 ? $remainingQty : 0;

                PermintaanBarangDetail::create([
                    'permintaan_barang_id' => $permintaanBarang->id,
                    'produk_id' => $soDetail->produk_id,
                    'sales_order_detail_id' => $soDetail->id,
                    'satuan_id' => $soDetail->satuan_id,
                    'jumlah' => $safeRemainingQty,
                    'jumlah_tersedia' => $stokTersedia,
                    'keterangan' => $stokTersedia < $safeRemainingQty ? 'Stok kurang' : '',
                ]);
            }

            DB::commit();

            $this->logUserAktivitas(
                'membuat permintaan barang otomatis',
                'permintaan_barang',
                $permintaanBarang->id,
                'dari sales order: ' . $salesOrder->nomor .
                    ($salesOrder->status_pengiriman === 'sebagian' ? ' (sisa pengiriman)' : '')
            );

            return redirect()->route('inventaris.permintaan-barang.show', $permintaanBarang->id)
                ->with('success', 'Permintaan barang berhasil dibuat dari Sales Order.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating permintaan barang: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat permintaan barang: ' . $e->getMessage());
        }
    }

    /**
     * Auto-proses permintaan barang dari sales order tanpa perlu mengisi form
     */
    public function autoProsesFromSalesOrder(Request $request)
    {
        $request->validate([
            'sales_order_id' => 'required|exists:sales_order,id',
            'gudang_id' => 'required|exists:gudang,id',
        ]);

        try {
            DB::beginTransaction();

            $salesOrder = SalesOrder::with(['details.produk', 'customer'])->findOrFail($request->sales_order_id);

            // Periksa apakah sudah ada permintaan barang yang belum selesai untuk sales order ini
            $existingPermintaan = PermintaanBarang::where('sales_order_id', $salesOrder->id)
                ->whereIn('status', ['menunggu', 'diproses'])
                ->first();

            if ($existingPermintaan) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Permintaan barang untuk Sales Order ini sudah ada dan sedang diproses.',
                    'redirect' => route('inventaris.permintaan-barang.show', $existingPermintaan->id)
                ]);
            }

            // Buat permintaan barang baru
            $permintaanBarang = PermintaanBarang::create([
                'nomor' => $this->generateNomorPermintaan(),
                'tanggal' => now(),
                'sales_order_id' => $salesOrder->id,
                'customer_id' => $salesOrder->customer_id,
                'user_id' => Auth::id(),
                'gudang_id' => $request->gudang_id,
                'status' => 'menunggu',
                'catatan' => 'Auto-generated dari Sales Order: ' . $salesOrder->nomor .
                    ($salesOrder->status_pengiriman === 'sebagian' ? ' (sisa pengiriman)' : ''),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Buat detail permintaan barang untuk setiap item di sales order
            $produkCount = 0;
            $produkNotAvailable = [];

            foreach ($salesOrder->details as $soDetail) {
                // Lewati jika tipe produk adalah jasa
                if ($soDetail->produk->tipe === 'jasa') {
                    continue;
                }

                // Hitung sisa jumlah yang perlu dikirim
                $remainingQty = $soDetail->quantity - $soDetail->quantity_terkirim;

                // Lewati jika sudah tidak ada sisa yang perlu dikirim
                if ($remainingQty <= 0) {
                    continue;
                }

                $produkCount++;

                // Cek stok tersedia di gudang
                $stokTersedia = StokProduk::where('produk_id', $soDetail->produk_id)
                    ->where('gudang_id', $request->gudang_id)
                    ->value('jumlah') ?? 0;

                // Ensure remaining quantity is not null
                $safeRemainingQty = $remainingQty > 0 ? $remainingQty : 0;

                if ($stokTersedia < $safeRemainingQty) {
                    $produkNotAvailable[] = [
                        'nama' => $soDetail->produk->nama,
                        'kode' => $soDetail->produk->kode,
                        'jumlah_diminta' => $safeRemainingQty,
                        'jumlah_tersedia' => $stokTersedia
                    ];
                }

                PermintaanBarangDetail::create([
                    'permintaan_barang_id' => $permintaanBarang->id,
                    'produk_id' => $soDetail->produk_id,
                    'sales_order_detail_id' => $soDetail->id,
                    'satuan_id' => $soDetail->satuan_id,
                    'jumlah' => $safeRemainingQty,
                    'jumlah_tersedia' => $stokTersedia,
                    'keterangan' => $stokTersedia < $safeRemainingQty ? 'Stok kurang' : '',
                ]);
            }

            DB::commit();

            $this->logUserAktivitas(
                'membuat permintaan barang otomatis',
                'permintaan_barang',
                $permintaanBarang->id,
                'dari sales order: ' . $salesOrder->nomor .
                    ($salesOrder->status_pengiriman === 'sebagian' ? ' (sisa pengiriman)' : '')
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Permintaan barang berhasil dibuat dari Sales Order' .
                    (count($produkNotAvailable) > 0 ? ' (terdapat produk dengan stok kurang)' : ''),
                'redirect' => route('inventaris.permintaan-barang.show', $permintaanBarang->id),
                'produk_tidak_tersedia' => $produkNotAvailable
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating permintaan barang: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat membuat permintaan barang: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new permintaan barang.
     */
    public function create()
    {
        $salesOrders = SalesOrder::where('status_pengiriman', '!=', 'selesai')
            ->orderBy('tanggal', 'desc')
            ->get();
        $gudangs = Gudang::orderBy('nama', 'asc')->get();

        return view('inventaris.permintaan_barang.create', compact('salesOrders', 'gudangs'));
    }

    /**
     * Store a newly created permintaan barang in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sales_order_id' => 'required|exists:sales_order,id',
            'gudang_id' => 'required|exists:gudang,id',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            return $this->generateFromSalesOrder($request);
        } catch (\Exception $e) {
            Log::error('Error storing permintaan barang: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan permintaan barang: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified permintaan barang.
     */
    public function show(PermintaanBarang $permintaanBarang)
    {
        $permintaanBarang->load(['details.produk', 'details.satuan', 'salesOrder', 'customer', 'gudang', 'user']);

        // Debug: Log the permintaan barang details count
        \Illuminate\Support\Facades\Log::info('Permintaan Barang Details', [
            'id' => $permintaanBarang->id,
            'nomor' => $permintaanBarang->nomor,
            'details_count' => $permintaanBarang->details->count(),
            'has_details' => $permintaanBarang->details->isNotEmpty(),
            'details' => $permintaanBarang->details->toArray()
        ]);

        return view('inventaris.permintaan_barang.show', compact('permintaanBarang'));
    }

    /**
     * Update the status of permintaan barang
     */
    public function updateStatus(Request $request, PermintaanBarang $permintaanBarang)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai,dibatalkan',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $permintaanBarang->status;
            $permintaanBarang->status = $request->status;

            if ($request->filled('catatan')) {
                $permintaanBarang->catatan = $request->catatan;
            }

            $permintaanBarang->updated_by = Auth::id();
            $permintaanBarang->save();

            DB::commit();

            $this->logUserAktivitas(
                'mengubah status permintaan barang',
                'permintaan_barang',
                $permintaanBarang->id,
                "dari {$oldStatus} menjadi {$request->status}"
            );

            return redirect()->route('inventaris.permintaan-barang.show', $permintaanBarang->id)
                ->with('success', 'Status permintaan barang berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating permintaan barang status: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Create Delivery Order from permintaan barang
     */
    public function createDeliveryOrder(PermintaanBarang $permintaanBarang)
    {
        // Redirect ke halaman pembuatan Delivery Order dengan data awal dari permintaan barang
        return redirect()->route('penjualan.delivery-order.create', [
            'permintaan_barang_id' => $permintaanBarang->id,
            'sales_order_id' => $permintaanBarang->sales_order_id,
            'gudang_id' => $permintaanBarang->gudang_id
        ]);
    }
}