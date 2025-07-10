<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\Customer;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\Quotation;
use App\Models\LogAktivitas;
use App\Models\StokProduk;
use App\Models\PerencanaanProduksi;
use App\Models\PerencanaanProduksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\NotificationService;

class SalesOrderController extends Controller
{

    private function generateNewSalesOrderNumber()
    {
        $prefix = 'SO-';
        $date = now()->format('Ymd');

        // MySQL compatible query - use UNSIGNED instead of INTEGER
        $lastSalesOrder = DB::table('sales_order')
            ->where('nomor', 'like', $prefix . $date . '-%')
            ->selectRaw('MAX(CAST(SUBSTRING(nomor, ' . (strlen($prefix . $date . '-') + 1) . ') AS UNSIGNED)) as last_num')
            ->first();

        $newNumberSuffix = '001';
        if ($lastSalesOrder && !is_null($lastSalesOrder->last_num)) {
            $newNumberSuffix = str_pad($lastSalesOrder->last_num + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . $date . '-' . $newNumberSuffix;
    }


    public function index(Request $request)
    {
        $query = null;

        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager_penjualan')) {
            $query = SalesOrder::query();
        } else {
            $query = SalesOrder::whereHas('customer', function ($q) {
                $q->where('sales_id', Auth::id());
            });
        }

        $sort = $request->get('sort', 'tanggal');
        $direction = $request->get('direction', 'desc');

        // Map frontend sort field names to database column names
        $sortMapping = [
            'no' => 'nomor',
            'no_sales_order' => 'nomor',
            'tanggal' => 'tanggal',
            'customer' => 'customer_id',
            'kontak' => 'customer_id',
            'status_pembayaran' => 'status_pembayaran',
            'status_pengiriman' => 'status_pengiriman',
            'total' => 'total'
        ];

        // Get the actual database column name to sort by
        $dbSortField = $sortMapping[$sort] ?? $sort;

        $validSorts = ['nomor', 'tanggal', 'status_pembayaran', 'status_pengiriman', 'total'];

        if (in_array($dbSortField, $validSorts) || Schema::hasColumn('sales_order', $dbSortField)) {
            $query->orderBy($dbSortField, $direction);
        } elseif ($sort === 'customer' || $sort === 'kontak') {
            // Join with customer table to sort by customer name
            $query->leftJoin('customer', 'sales_order.customer_id', '=', 'customer.id')
                ->orderBy('customer.nama', $direction)
                ->select('sales_order.*');
        } else {
            $query->orderBy('tanggal', 'desc');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhere('status_pembayaran', 'like', "%{$search}%")
                    ->orWhere('status_pengiriman', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q_customer) use ($search) {
                        $q_customer->where('nama', 'like', "%{$search}%")
                            ->orWhere('company', 'like', "%{$search}%");
                    });

                if (is_numeric($search)) {
                    $q->orWhere('total', '=', $search);
                }
            });
        }

        if ($request->filled('status_pembayaran') && $request->status_pembayaran !== 'all' && $request->status_pembayaran !== '') {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        if ($request->filled('status_pengiriman') && $request->status_pengiriman !== 'all' && $request->status_pengiriman !== '') {
            $query->where('status_pengiriman', $request->status_pengiriman);
        }

        // Add date filtering
        // Always check tanggal_awal and tanggal_akhir directly first (they take precedence)
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        // If periode is set but no explicit dates, calculate the date range
        elseif ($request->filled('periode') && $request->periode !== 'custom' && !$request->filled('tanggal_awal')) {
            $today = now();
            $startDate = null;
            $endDate = $today;

            switch ($request->periode) {
                case 'today':
                    $startDate = $today->copy();
                    break;
                case 'yesterday':
                    $startDate = $today->copy()->subDay();
                    $endDate = $startDate->copy();
                    break;
                case 'this_week':
                    $startDate = $today->copy()->startOfWeek();
                    break;
                case 'last_week':
                    $startDate = $today->copy()->subWeek()->startOfWeek();
                    $endDate = $today->copy()->subWeek()->endOfWeek();
                    break;
                case 'this_month':
                    $startDate = $today->copy()->startOfMonth();
                    break;
                case 'last_month':
                    $startDate = $today->copy()->subMonth()->startOfMonth();
                    $endDate = $today->copy()->subMonth()->endOfMonth();
                    break;
                case 'this_year':
                    $startDate = $today->copy()->startOfYear();
                    break;
            }

            if ($startDate) {
                $query->whereDate('tanggal', '>=', $startDate->format('Y-m-d'))
                    ->whereDate('tanggal', '<=', $endDate->format('Y-m-d'));
            }
        }

        try {
            $salesOrders = $query->paginate(10)->withQueryString();

            if ($request->ajax()) {
                return response()->json([
                    'table_html' => view('penjualan.sales-order._table', compact('salesOrders', 'sort', 'direction'))->render(),
                    'pagination_html' => $salesOrders->links()->toHtml(),
                    'sort_field' => $sort,
                    'sort_direction' => $direction,
                ]);
            }

            return view('penjualan.sales-order.index', compact('salesOrders', 'sort', 'direction'));
        } catch (\Exception $e) {
            Log::error('Error in sales order index: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            $userFriendlyMessage = 'Terjadi kesalahan saat memuat data. Silakan coba lagi.';

            // Add more context for certain types of errors
            if (strpos($e->getMessage(), 'SQLSTATE') !== false) {
                $userFriendlyMessage = 'Terjadi kesalahan pada database. Hubungi admin untuk bantuan.';
            }

            if (app()->environment('local', 'development', 'staging')) {
                $userFriendlyMessage .= ' Detail: ' . $e->getMessage();
            }

            if ($request->ajax()) {
                return response()->json([
                    'table_html' => '<tr><td colspan="7" class="px-6 py-4 text-center text-red-500">Terjadi kesalahan saat memuat data. Silakan muat ulang halaman ini.</td></tr>',
                    'pagination_html' => '',
                    'error' => $userFriendlyMessage
                ], 500);
            }

            return view('penjualan.sales-order.index', [
                'salesOrders' => collect([]),
                'sort' => $sort,
                'direction' => $direction,
                'error' => $userFriendlyMessage
            ]);
        }
    }



    public function create(Request $request)
    {
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager_penjualan') || Auth::user()->hasRole('admin_penjualan')) {
            // Allow access to all customers
            $customers = Customer::orderBy('nama', 'asc')->get();
        } else {
            // Only show customers assigned to the sales user
            $customers = Customer::where('sales_id', Auth::id())->orderBy('nama', 'asc')->get();
        }
        // $customers = Customer::where('sales_id', Auth::id())->orderBy('nama', 'asc')->get();
        $products = Produk::orderBy('nama', 'asc')->get();
        $satuans = Satuan::orderBy('nama', 'asc')->get();
        $gudangs = \App\Models\Gudang::orderBy('nama', 'asc')->get();
        $nomor = $this->generateNewSalesOrderNumber();
        $tanggal = now()->format('Y-m-d');
        $tanggal_kirim = now()->addDays(3)->format('Y-m-d');

        $quotation_id = $request->quotation_id;
        $quotation = null;

        if ($quotation_id) {
            $quotation = Quotation::with(['customer', 'details.produk', 'details.satuan'])->find($quotation_id);
        }

        $status_pembayaran = [
            'belum_bayar' => 'Belum Bayar',
            'sebagian' => 'Sebagian',
            'lunas' => 'Lunas'
        ];

        $status_pengiriman = [
            'belum_dikirim' => 'Belum Dikirim',
            'sebagian' => 'Sebagian',
            'dikirim' => 'Dikirim'
        ];

        return view('penjualan.sales-order.create', compact(
            'customers',
            'products',
            'satuans',
            'gudangs',
            'nomor',
            'tanggal',
            'tanggal_kirim',
            'quotation',
            'status_pembayaran',
            'status_pengiriman'
        ));
    }

    public function store(Request $request)
    {

        // Cek stok produk jika check_stock aktif dan gudang_id ada
        if ($request->check_stock && $request->gudang_id) {
            $items = $request->items;
            $gudangId = $request->gudang_id;
            $stokKurang = [];
            foreach ($items as $idx => $item) {
                $produkId = $item['produk_id'];
                $quantity = isset($item['kuantitas']) ? $item['kuantitas'] : $item['quantity'];
                $produk = Produk::find($produkId);
                $stokTersedia = StokProduk::where('produk_id', $produkId)
                    ->where('gudang_id', $gudangId)
                    ->sum('jumlah');

                // Hitung jumlah produk yang sudah dipesan di SO lain (belum_dikirim/sebagian)
                // Hitung booked untuk status pengiriman 'belum_dikirim'
                $qtyBookedBelum = SalesOrderDetail::where('produk_id', $produkId)
                    ->whereHas('salesOrder', function ($q) {
                        $q->where('status_pengiriman', 'belum_dikirim');
                    })
                    ->sum(DB::raw('quantity - quantity_terkirim'));

                // Hitung booked untuk status pengiriman 'sebagian' (hanya sisa yang belum terkirim)
                $qtyBookedSebagian = SalesOrderDetail::where('produk_id', $produkId)
                    ->whereHas('salesOrder', function ($q) {
                        $q->where('status_pengiriman', 'sebagian');
                    })
                    ->sum(DB::raw('quantity - quantity_terkirim'));

                $qtyBooked = $qtyBookedBelum + $qtyBookedSebagian;

                // Stok tersedia dikurangi yang sudah dipesan di SO lain
                $stokTersediaNet = $stokTersedia - $qtyBooked;

                if ($stokTersediaNet < $quantity) {
                    $stokKurang[] = [
                        'produk' => $produk ? ($produk->nama ?? 'Produk ID ' . $produkId) : 'Produk ID ' . $produkId,
                        'diminta' => $quantity,
                        'tersedia' => $stokTersediaNet,
                        'produk_id' => $produkId
                    ];
                }
            }
            if (!empty($stokKurang)) {
                // Kirim notifikasi stok kurang khusus sales order (detail per item)
                $soNomor = null;
                $soId = null;
                if ($request->nomor) {
                    $soNomor = $request->nomor;
                }
                $userId = Auth::id();
                $salesOrderStockNotif = app(\App\Services\SalesOrderStockNotificationService::class);
                $salesOrderStockNotif->notifyStockInsufficientForSalesOrder($stokKurang, $gudangId, $soNomor, $soId, $userId);
            }
        }
        // dd($request->all());

        $request->validate([
            'nomor' => 'required|string|unique:sales_order,nomor',
            'nomor_po' => 'nullable|string',
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customer,id',
            'quotation_id' => 'nullable|exists:quotation,id',
            'tanggal_kirim' => 'nullable|date',
            'alamat_pengiriman' => 'nullable|string',
            'status_pembayaran' => 'required|string|in:belum_bayar,sebagian,lunas',
            'status_pengiriman' => 'required|string|in:belum_dikirim,sebagian,dikirim',
            'catatan' => 'nullable|string',
            'syarat_ketentuan' => 'nullable|string',
            'terms_pembayaran' => 'nullable|string',
            'terms_pembayaran_hari' => 'nullable|integer|min:0',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.kuantitas' => 'required_without:items.*.quantity|numeric|min:0.01',
            'items.*.quantity' => 'required_without:items.*.kuantitas|numeric|min:0.01',
            'items.*.satuan_id' => 'required|exists:satuan,id',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.deskripsi' => 'nullable|string',
            'items.*.diskon_persen' => 'nullable|numeric|min:0|max:100',
            'diskon_global_persen' => 'nullable|numeric|min:0|max:100',
            'diskon_global_nominal' => 'nullable|numeric|min:0',
            'ongkos_kirim' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0|max:100',
            'check_stock' => 'nullable|boolean',
            'create_production_plan' => 'nullable|boolean',
            'gudang_id' => 'nullable|exists:gudang,id',
        ]);

        try {
            DB::beginTransaction();

            $items = $request->items;
            $subtotal = 0;

            // Calculate subtotal
            foreach ($items as $item) {
                $quantity = isset($item['kuantitas']) ? $item['kuantitas'] : $item['quantity'];
                $productTotal = $item['harga'] * $quantity;
                $discountValue = 0;

                if (isset($item['diskon_persen']) && $item['diskon_persen'] > 0) {
                    $discountValue = ($item['diskon_persen'] / 100) * $productTotal;
                }

                $subtotal += $productTotal - $discountValue;
            }

            // Calculate discounts and taxes
            $diskonGlobalPersen = $request->diskon_global_persen ?? 0;
            $diskonGlobalNominal = $request->diskon_global_nominal ?? 0;

            if ($diskonGlobalPersen > 0) {
                $diskonGlobalNominal = ($diskonGlobalPersen / 100) * $subtotal;
            }

            $afterDiscount = $subtotal - $diskonGlobalNominal;

            $ppn = $request->ppn ?? 0;
            $ppnValue = ($ppn / 100) * $afterDiscount;

            $ongkosKirim = $request->ongkos_kirim ?? 0;

            $total = $afterDiscount + $ppnValue + $ongkosKirim;

            // Create Sales Order
            $salesOrder = new SalesOrder();
            $salesOrder->nomor = $request->nomor;
            $salesOrder->nomor_po = $request->nomor_po;
            $salesOrder->tanggal = $request->tanggal;
            $salesOrder->customer_id = $request->customer_id;
            $salesOrder->quotation_id = $request->quotation_id;
            $salesOrder->user_id = Auth::id();
            $salesOrder->subtotal = $subtotal;
            $salesOrder->diskon_persen = $diskonGlobalPersen;
            $salesOrder->diskon_nominal = $diskonGlobalNominal;
            $salesOrder->ppn = $ppn;
            $salesOrder->ongkos_kirim = $ongkosKirim;
            $salesOrder->total = $total;
            $salesOrder->status_pembayaran = $request->status_pembayaran;
            $salesOrder->status_pengiriman = $request->status_pengiriman;
            $salesOrder->tanggal_kirim = $request->tanggal_kirim;
            $salesOrder->alamat_pengiriman = $request->alamat_pengiriman;
            $salesOrder->catatan = $request->catatan;
            $salesOrder->syarat_ketentuan = $request->syarat_ketentuan;
            $salesOrder->terms_pembayaran = $request->terms_pembayaran;
            $salesOrder->terms_pembayaran_hari = $request->terms_pembayaran_hari;
            $salesOrder->save();

            // Array untuk menyimpan produk yang perlu diproduksi
            $needsProduction = [];
            $gudangId = $request->gudang_id;

            // Create Sales Order Details
            foreach ($items as $item) {
                $productTotal = $item['harga'] * (isset($item['kuantitas']) ? $item['kuantitas'] : $item['quantity']);
                $diskonPersenItem = $item['diskon_persen'] ?? 0;
                $diskonNominalItem = 0;

                if ($diskonPersenItem > 0) {
                    $diskonNominalItem = ($diskonPersenItem / 100) * $productTotal;
                }

                $subtotalItem = $productTotal - $diskonNominalItem;
                $quantity = isset($item['kuantitas']) ? $item['kuantitas'] : $item['quantity'];

                $salesOrderDetail = new SalesOrderDetail();
                $salesOrderDetail->sales_order_id = $salesOrder->id;
                $salesOrderDetail->produk_id = $item['produk_id'];
                $salesOrderDetail->deskripsi = $item['deskripsi'] ?? null;
                $salesOrderDetail->quantity = $quantity;
                $salesOrderDetail->quantity_terkirim = 0;
                $salesOrderDetail->satuan_id = $item['satuan_id'];
                $salesOrderDetail->harga = $item['harga'];
                $salesOrderDetail->diskon_persen = $diskonPersenItem;
                $salesOrderDetail->diskon_nominal = $diskonNominalItem;
                $salesOrderDetail->subtotal = $subtotalItem;
                $salesOrderDetail->save();

                // Update harga_jual di model Produk agar selalu relevan
                $produk = Produk::find($item['produk_id']);
                if ($produk) {
                    $produk->harga_jual = $item['harga'];
                    $produk->save();
                }

                // Jika opsi check_stock diaktifkan, cek ketersediaan stok
                if ($request->check_stock && $gudangId) {
                    $produk = Produk::find($item['produk_id']);
                    $stokTersedia = StokProduk::where('produk_id', $item['produk_id'])
                        ->where('gudang_id', $gudangId)
                        ->sum('jumlah');

                    // Jika stok tidak mencukupi, tambahkan ke daftar produk yang perlu diproduksi
                    if ($stokTersedia < $quantity && $produk && $produk->tipe === 'produk_jadi') {
                        $needsProduction[] = [
                            'produk_id' => $item['produk_id'],
                            'jumlah' => $quantity,
                            'stok_tersedia' => $stokTersedia,
                            'jumlah_produksi' => $quantity - $stokTersedia,
                            'satuan_id' => $item['satuan_id'],
                            'keterangan' => 'Stok tidak mencukupi untuk SO: ' . $salesOrder->nomor
                        ];
                    }
                }
            }

            // Tambahkan log aktivitas untuk pembuatan sales order
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'create',
                'modul' => 'sales_order',
                'data_id' => $salesOrder->id,
                'ip_address' => request()->ip(),
                'detail' => json_encode([
                    'nomor' => $salesOrder->nomor,
                    'nomor_po' => $salesOrder->nomor_po,
                    'tanggal' => $salesOrder->tanggal,
                    'customer' => $salesOrder->customer->nama ?? $salesOrder->customer->company ?? 'Customer tidak ditemukan',
                    'customer_id' => $salesOrder->customer_id,
                    'quotation_id' => $salesOrder->quotation_id,
                    'status_pembayaran' => $salesOrder->status_pembayaran,
                    'status_pengiriman' => $salesOrder->status_pengiriman,
                    'subtotal' => $salesOrder->subtotal,
                    'diskon_persen' => $salesOrder->diskon_persen,
                    'diskon_nominal' => $salesOrder->diskon_nominal,
                    'ppn' => $salesOrder->ppn,
                    'ongkos_kirim' => $salesOrder->ongkos_kirim,
                    'total' => $salesOrder->total,
                    'tanggal_kirim' => $salesOrder->tanggal_kirim,
                    'jumlah_item' => count($items),
                    'user' => Auth::user()->name
                ])
            ]);

            // Send notification to managers about new sales order
            $notificationService = app(NotificationService::class);
            $notificationService->notifySalesOrderCreated($salesOrder, Auth::user());

            // Update quotation status if sales order was created from quotation
            if ($salesOrder->quotation_id) {
                $quotation = Quotation::find($salesOrder->quotation_id);
                if ($quotation && $quotation->status !== 'disetujui') {
                    $quotation->status = 'disetujui';
                    $quotation->save();

                    // Add log for quotation status change
                    LogAktivitas::create([
                        'user_id' => Auth::id(),
                        'aktivitas' => 'change_status',
                        'modul' => 'quotation',
                        'data_id' => $quotation->id,
                        'ip_address' => request()->ip(),
                        'detail' => json_encode([
                            'nomor' => $quotation->nomor,
                            'status_lama' => 'dikirim',
                            'status_baru' => 'disetujui',
                            'catatan' => 'Disetujui karena pembuatan Sales Order #' . $salesOrder->nomor
                        ])
                    ]);
                }
            }

            // Jika ada produk yang perlu diproduksi dan opsi create_production_plan diaktifkan
            if (!empty($needsProduction) && $request->create_production_plan) {
                try {
                    // Buat perencanaan produksi
                    $perencanaanProduksiController = new \App\Http\Controllers\Produksi\PerencanaanProduksiController();

                    // Generate nomor perencanaan produksi
                    $nomorPerencanaan = $perencanaanProduksiController->generateNomorPerencanaan();

                    // Buat perencanaan produksi
                    $perencanaan = new \App\Models\PerencanaanProduksi();
                    $perencanaan->nomor = $nomorPerencanaan;
                    $perencanaan->tanggal = now();
                    $perencanaan->sales_order_id = $salesOrder->id;
                    $perencanaan->catatan = 'Dibuat otomatis dari Sales Order #' . $salesOrder->nomor;
                    $perencanaan->status = 'draft';
                    $perencanaan->created_by = Auth::id();
                    $perencanaan->save();

                    // Simpan detail perencanaan
                    foreach ($needsProduction as $item) {
                        $detailPerencanaan = new \App\Models\PerencanaanProduksiDetail();
                        $detailPerencanaan->perencanaan_produksi_id = $perencanaan->id;
                        $detailPerencanaan->produk_id = $item['produk_id'];
                        $detailPerencanaan->jumlah = $item['jumlah'];
                        $detailPerencanaan->satuan_id = $item['satuan_id'];
                        $detailPerencanaan->stok_tersedia = $item['stok_tersedia'];
                        $detailPerencanaan->jumlah_produksi = $item['jumlah_produksi'];
                        $detailPerencanaan->jumlah_selesai = 0;
                        $detailPerencanaan->keterangan = $item['keterangan'];
                        $detailPerencanaan->save();
                    }

                    // Catat log aktivitas
                    LogAktivitas::create([
                        'user_id' => Auth::id(),
                        'aktivitas' => 'create',
                        'modul' => 'perencanaan_produksi_auto',
                        'data_id' => $perencanaan->id,
                        'ip_address' => request()->ip(),
                        'detail' => 'Perencanaan produksi otomatis dibuat dari Sales Order #' . $salesOrder->nomor
                    ]);
                } catch (\Exception $e) {
                    // Hanya log error tapi tidak rollback transaksi SO
                    Log::error('Error creating production planning: ' . $e->getMessage());
                }
            }

            // Cek apakah user ingin otomatis membuat permintaan barang
            if ($request->has('buat_permintaan_barang') && $request->buat_permintaan_barang == 1 && $request->gudang_id) {
                try {
                    // Panggil controller PermintaanBarang untuk generate permintaan barang
                    $permintaanController = new \App\Http\Controllers\Inventaris\PermintaanBarangController();
                    $permintaanRequest = new Request([
                        'sales_order_id' => $salesOrder->id,
                        'gudang_id' => $request->gudang_id,
                    ]);
                    $permintaanController->generateFromSalesOrder($permintaanRequest);

                    // Log aktivitas pembuatan permintaan barang otomatis
                    LogAktivitas::create([
                        'user_id' => Auth::id(),
                        'aktivitas' => 'create',
                        'modul' => 'permintaan_barang_auto',
                        'data_id' => $salesOrder->id,
                        'ip_address' => request()->ip(),
                        'detail' => 'Permintaan barang otomatis dibuat dari Sales Order #' . $salesOrder->nomor
                    ]);
                } catch (\Exception $e) {
                    // Hanya log error tapi tidak rollback transaksi SO
                    Log::error('Error creating permintaan barang otomatis: ' . $e->getMessage());
                }
            }

            DB::commit();

            return redirect()->route('penjualan.sales-order.index')
                ->with('success', 'Sales Order berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating sales order: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat sales order. Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $salesOrder = SalesOrder::with([
            'customer',
            'quotation',
            'details.produk',
            'details.satuan',
            'logAktivitas.user',
            'workOrders',
            'deliveryOrders',
            'invoices'
        ])->findOrFail($id);


        return view('penjualan.sales-order.show', compact('salesOrder'));
    }

    public function edit($id)
    {
        $salesOrder = SalesOrder::with(['customer', 'quotation', 'details.produk', 'details.satuan'])->findOrFail($id);
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager_penjualan') || Auth::user()->hasRole('admin_penjualan')) {
            // Allow access to all customers
            $customers = Customer::orderBy('nama', 'asc')->get();
        } else {
            // Only show customers assigned to the sales user
            $customers = Customer::where('sales_id', Auth::id())->orderBy('nama', 'asc')->get();
        }
        $products = Produk::orderBy('nama', 'asc')->get();
        $satuans = Satuan::orderBy('nama', 'asc')->get();

        $status_pembayaran = [
            'belum_bayar' => 'Belum Bayar',
            'sebagian' => 'Sebagian',
            'lunas' => 'Lunas'
        ];

        $status_pengiriman = [
            'belum_dikirim' => 'Belum Dikirim',
            'sebagian' => 'Sebagian',
            'dikirim' => 'Dikirim'
        ];

        // Check if sales order has related delivery orders or invoices
        if ($salesOrder->deliveryOrders()->exists() || $salesOrder->invoices()->exists() || $salesOrder->workOrders()->exists()) {
            return redirect()->route('penjualan.sales-order.index')
                ->with('error', 'Sales Order ini tidak dapat diedit karena sudah memiliki Delivery Order, Work Order, atau Invoice terkait.');
        }

        return view('penjualan.sales-order.edit', compact(
            'salesOrder',
            'customers',
            'products',
            'satuans',
            'status_pembayaran',
            'status_pengiriman'
        ));
    }

    public function update(Request $request, $id)
    {
        $salesOrder = SalesOrder::findOrFail($id);


        // Check if sales order has related delivery orders or invoices
        if ($salesOrder->deliveryOrders()->exists() || $salesOrder->invoices()->exists() || $salesOrder->workOrders()->exists()) {
            return redirect()->route('penjualan.sales-order.index')
                ->with('error', 'Sales Order ini tidak dapat diupdate karena sudah memiliki Delivery Order, Work Order, atau Invoice terkait.');
        }

        $request->validate([
            'nomor' => 'required|string|unique:sales_order,nomor,' . $id . ',id',
            'nomor_po' => 'nullable|string',
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customer,id',
            'quotation_id' => 'nullable|exists:quotation,id',
            'tanggal_kirim' => 'nullable|date',
            'alamat_pengiriman' => 'nullable|string',
            'status_pembayaran' => 'required|string|in:belum_bayar,sebagian,lunas',
            'status_pengiriman' => 'required|string|in:belum_dikirim,sebagian,dikirim',
            'catatan' => 'nullable|string',
            'syarat_ketentuan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.kuantitas' => 'required_without:items.*.quantity|numeric|min:0',
            'items.*.quantity' => 'required_without:items.*.kuantitas|numeric|min:0',
            'items.*.satuan_id' => 'required|exists:satuan,id',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.deskripsi' => 'nullable|string',
            'diskon_global_persen' => 'nullable|numeric|min:0|max:100',
            'diskon_global_nominal' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            // Simpan data lama untuk log
            $oldData = [
                'nomor' => $salesOrder->nomor,
                'nomor_po' => $salesOrder->nomor_po,
                'tanggal' => $salesOrder->tanggal,
                'customer_id' => $salesOrder->customer_id,
                'customer' => $salesOrder->customer->nama ?? $salesOrder->customer->company ?? 'Customer tidak ditemukan',
                'quotation_id' => $salesOrder->quotation_id,
                'status_pembayaran' => $salesOrder->status_pembayaran,
                'status_pengiriman' => $salesOrder->status_pengiriman,
                'subtotal' => $salesOrder->subtotal,
                'diskon_persen' => $salesOrder->diskon_persen,
                'diskon_nominal' => $salesOrder->diskon_nominal,
                'ppn' => $salesOrder->ppn,
                'total' => $salesOrder->total,
                'tanggal_kirim' => $salesOrder->tanggal_kirim,
                'jumlah_item' => $salesOrder->details->count(),
                'user' => Auth::user()->name
            ];

            $items = $request->items;
            $subtotal = 0;

            // Calculate subtotal
            foreach ($items as $item) {
                $quantity = isset($item['kuantitas']) ? $item['kuantitas'] : $item['quantity'];
                $productTotal = $item['harga'] * $quantity;
                $discountValue = 0;

                if (isset($item['diskon_persen_item']) && $item['diskon_persen_item'] > 0) {
                    $discountValue = ($item['diskon_persen_item'] / 100) * $productTotal;
                } elseif (isset($item['diskon_persen']) && $item['diskon_persen'] > 0) {
                    $discountValue = ($item['diskon_persen'] / 100) * $productTotal;
                } elseif (isset($item['diskon_nominal_item']) && $item['diskon_nominal_item'] > 0) {
                    $discountValue = $item['diskon_nominal_item'];
                }

                $subtotal += $productTotal - $discountValue;
            }

            // Calculate discounts and taxes
            $diskonPersen = $request->diskon_global_persen ?? 0;
            $diskonNominal = $request->diskon_global_nominal ?? 0;

            if ($diskonPersen > 0) {
                $diskonNominal = ($diskonPersen / 100) * $subtotal;
            }

            $afterDiscount = $subtotal - $diskonNominal;
            $ppn = $request->ppn ?? 0;
            $ppnValue = ($ppn / 100) * $afterDiscount;
            $ongkosKirim = $request->ongkos_kirim ?? 0;
            $total = $afterDiscount + $ppnValue + $ongkosKirim;

            // Update Sales Order
            $salesOrder->nomor = $request->nomor;
            $salesOrder->nomor_po = $request->nomor_po;
            $salesOrder->tanggal = $request->tanggal;
            $salesOrder->customer_id = $request->customer_id;
            $salesOrder->quotation_id = $request->quotation_id;
            $salesOrder->subtotal = $subtotal;
            $salesOrder->diskon_persen = $diskonPersen;
            $salesOrder->diskon_nominal = $diskonNominal;
            $salesOrder->ppn = $ppn;
            $salesOrder->total = $total;
            $salesOrder->status_pembayaran = $request->status_pembayaran;
            $salesOrder->status_pengiriman = $request->status_pengiriman;
            $salesOrder->tanggal_kirim = $request->tanggal_kirim;
            $salesOrder->alamat_pengiriman = $request->alamat_pengiriman;
            $salesOrder->ongkos_kirim = $ongkosKirim;
            $salesOrder->catatan = $request->catatan;
            $salesOrder->syarat_ketentuan = $request->syarat_ketentuan;
            $salesOrder->terms_pembayaran = $request->terms_pembayaran;
            $salesOrder->terms_pembayaran_hari = $request->terms_pembayaran_hari;
            $salesOrder->save();

            // Delete existing details and create new ones
            SalesOrderDetail::where('sales_order_id', $salesOrder->id)->delete();

            // Create Sales Order Details
            foreach ($items as $item) {
                $productTotal = $item['harga'] * (isset($item['kuantitas']) ? $item['kuantitas'] : $item['quantity']);
                $diskonPersenItem = $item['diskon_persen_item'] ?? $item['diskon_persen'] ?? 0;
                $diskonNominalItem = $item['diskon_nominal_item'] ?? 0;

                if ($diskonPersenItem > 0) {
                    $diskonNominalItem = ($diskonPersenItem / 100) * $productTotal;
                }

                $subtotalItem = $productTotal - $diskonNominalItem;

                $salesOrderDetail = new SalesOrderDetail();
                $salesOrderDetail->sales_order_id = $salesOrder->id;
                $salesOrderDetail->produk_id = $item['produk_id'];
                $salesOrderDetail->deskripsi = $item['deskripsi'] ?? null;
                $salesOrderDetail->quantity = isset($item['kuantitas']) ? $item['kuantitas'] : $item['quantity'];
                $salesOrderDetail->quantity_terkirim = 0;
                $salesOrderDetail->satuan_id = $item['satuan_id'];
                $salesOrderDetail->harga = $item['harga'];
                $salesOrderDetail->diskon_persen = $diskonPersenItem;
                $salesOrderDetail->diskon_nominal = $diskonNominalItem;
                $salesOrderDetail->subtotal = $subtotalItem;
                $salesOrderDetail->save();

                // Update harga_jual di model Produk agar selalu relevan
                $produk = Produk::find($item['produk_id']);
                if ($produk) {
                    $produk->harga_jual = $item['harga'];
                    $produk->save();
                }
            }

            // Tambahkan log aktivitas untuk update
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'update',
                'modul' => 'sales_order',
                'data_id' => $salesOrder->id,
                'ip_address' => request()->ip(),
                'detail' => json_encode([
                    'before' => $oldData,
                    'after' => [
                        'nomor' => $salesOrder->nomor,
                        'nomor_po' => $salesOrder->nomor_po,
                        'tanggal' => $salesOrder->tanggal,
                        'customer_id' => $salesOrder->customer_id,
                        'customer' => $salesOrder->customer->nama ?? $salesOrder->customer->company ?? 'Customer tidak ditemukan',
                        'quotation_id' => $salesOrder->quotation_id,
                        'status_pembayaran' => $salesOrder->status_pembayaran,
                        'status_pengiriman' => $salesOrder->status_pengiriman,
                        'subtotal' => $salesOrder->subtotal,
                        'diskon_persen' => $salesOrder->diskon_persen,
                        'diskon_nominal' => $salesOrder->diskon_nominal,
                        'ppn' => $salesOrder->ppn,
                        'total' => $salesOrder->total,
                        'tanggal_kirim' => $salesOrder->tanggal_kirim,
                        'jumlah_item' => count($items),
                        'user' => Auth::user()->name
                    ]
                ])
            ]);

            DB::commit();

            return redirect()->route('penjualan.sales-order.index')
                ->with('success', 'Sales Order berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating sales order: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupdate sales order. Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $salesOrder = SalesOrder::findOrFail($id);

            // Check if the sales order has delivery orders, work orders, or invoices
            if ($salesOrder->deliveryOrders()->exists() || $salesOrder->workOrders()->exists() || $salesOrder->invoices()->exists()) {
                return redirect()->route('penjualan.sales-order.index')
                    ->with('error', 'Tidak dapat menghapus sales order karena sudah memiliki delivery order, work order, atau invoice terkait.');
            }

            // Simpan data untuk log sebelum dihapus
            $salesOrderData = [
                'id' => $salesOrder->id,
                'nomor' => $salesOrder->nomor,
                'customer' => $salesOrder->customer->nama ?? $salesOrder->customer->company ?? 'Customer tidak ditemukan',
                'total' => $salesOrder->total
            ];

            DB::beginTransaction();

            // Delete sales order details
            SalesOrderDetail::where('sales_order_id', $id)->delete();

            // Delete sales order
            $salesOrder->delete();

            // Tambahkan log aktivitas untuk delete
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'delete',
                'modul' => 'sales_order',
                'data_id' => $salesOrderData['id'],
                'ip_address' => request()->ip(),
                'detail' => json_encode($salesOrderData)
            ]);

            DB::commit();

            return redirect()->route('penjualan.sales-order.index')
                ->with('success', 'Sales Order berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting sales order: ' . $e->getMessage());

            return redirect()->route('penjualan.sales-order.index')
                ->with('error', 'Terjadi kesalahan saat menghapus sales order. Error: ' . $e->getMessage());
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:belum_bayar,sebagian,lunas',
            'status_pengiriman' => 'required|in:belum_dikirim,sebagian,dikirim',
            'catatan_status' => 'nullable|string',
        ]);

        try {
            $salesOrder = SalesOrder::findOrFail($id);
            $oldStatusPembayaran = $salesOrder->status_pembayaran;
            $oldStatusPengiriman = $salesOrder->status_pengiriman;
            $newStatusPembayaran = $request->status_pembayaran;
            $newStatusPengiriman = $request->status_pengiriman;

            $salesOrder->status_pembayaran = $newStatusPembayaran;
            $salesOrder->status_pengiriman = $newStatusPengiriman;
            $salesOrder->save();

            // Tambahkan log aktivitas untuk perubahan status
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'change_status',
                'modul' => 'sales_order',
                'data_id' => $salesOrder->id,
                'ip_address' => request()->ip(),
                'detail' => json_encode([
                    'nomor' => $salesOrder->nomor,
                    'status_pembayaran_lama' => $oldStatusPembayaran,
                    'status_pembayaran_baru' => $newStatusPembayaran,
                    'status_pengiriman_lama' => $oldStatusPengiriman,
                    'status_pengiriman_baru' => $newStatusPengiriman,
                    'catatan' => $request->catatan_status ?? '-'
                ])
            ]);

            // Log status change
            Log::info("Sales Order {$salesOrder->nomor} status changed. Payment: {$oldStatusPembayaran} to {$newStatusPembayaran}, Delivery: {$oldStatusPengiriman} to {$newStatusPengiriman}");

            return redirect()->route('penjualan.sales-order.show', $salesOrder->id)
                ->with('success', "Status sales order berhasil diubah");
        } catch (\Exception $e) {
            Log::error('Error changing sales order status: ' . $e->getMessage());

            return redirect()->route('penjualan.sales-order.show', $id)
                ->with('error', 'Terjadi kesalahan saat mengubah status sales order. Error: ' . $e->getMessage());
        }
    }

    public function getQuotationData($id)
    {
        try {
            $quotation = Quotation::with(['customer', 'details.produk', 'details.satuan'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $quotation
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Quotation not found for ID {$id} in getQuotationData: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Quotation tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            Log::error("Error in getQuotationData for ID {$id}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data quotation. Silakan coba lagi.'
            ], 500); // Use 500 for general server errors
        }
    }

    /**
     * Generate PDF for a sales order
     */
    public function exportPdf($id)
    {
        $salesOrder = SalesOrder::with(['customer', 'user', 'details.produk', 'details.satuan'])
            ->findOrFail($id);

        // Load the PDF view
        $pdf = Pdf::loadView('penjualan.sales-order.pdf', ['salesOrder' => $salesOrder]);

        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');

        // Download the PDF with a specific filename
        return $pdf->download('SalesOrder-' . $salesOrder->nomor . '.pdf');
    }
}
