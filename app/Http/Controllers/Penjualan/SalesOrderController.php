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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesOrderController extends Controller
{

    private function generateNewSalesOrderNumber()
    {
        $prefix = 'SO-';
        $date = now()->format('Ymd');

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
        $query = SalesOrder::with('customer');

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
        $customers = Customer::orderBy('nama', 'asc')->get();
        $products = Produk::orderBy('nama', 'asc')->get();
        $satuans = Satuan::orderBy('nama', 'asc')->get();
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

        $request->validate([
            'nomor' => 'required|string|unique:sales_order,nomor',
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
            $salesOrder->save();

            // Create Sales Order Details
            foreach ($items as $item) {
                $productTotal = $item['harga'] * $item['kuantitas'];
                $diskonPersenItem = $item['diskon_persen'] ?? 0;
                $diskonNominalItem = 0;

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
        $customers = Customer::orderBy('nama', 'asc')->get();
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
