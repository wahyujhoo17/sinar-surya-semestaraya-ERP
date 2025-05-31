<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\ReturPenjualan;
use App\Models\ReturPenjualanDetail;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\Customer;
use App\Models\Produk;
use App\Models\Gudang;
use App\Models\Satuan;
use App\Models\StokProduk;
use App\Models\RiwayatStok;
use App\Models\LogAktivitas;
use App\Models\NotaKredit;
use App\Models\ReturPenjualanPengganti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\LOG;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReturPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validStatuses = ['draft', 'menunggu_persetujuan', 'disetujui', 'ditolak', 'diproses', 'menunggu_barang_pengganti', 'selesai'];
        $status = $request->input('status', 'semua');

        if (!in_array($status, array_merge($validStatuses, ['semua']))) {
            $status = 'semua';
        }

        $query = ReturPenjualan::with(['salesOrder', 'customer', 'user']);

        // Get sorting parameters
        $sortBy = $request->input('sort_by', 'tanggal');
        $sortDir = $request->input('sort_dir', 'desc');

        // Apply appropriate sorting based on field
        switch ($sortBy) {
            case 'nomor':
                $query->orderBy('nomor', $sortDir);
                break;
            case 'tanggal':
                $query->orderBy('tanggal', $sortDir);
                break;
            case 'customer':
                $query->join('customer', 'retur_penjualan.customer_id', '=', 'customer.id')
                    ->orderBy('customer.nama', $sortDir)
                    ->select('retur_penjualan.*');
                break;
            case 'sales_order':
                $query->join('sales_order', 'retur_penjualan.sales_order_id', '=', 'sales_order.id')
                    ->orderBy('sales_order.nomor', $sortDir)
                    ->select('retur_penjualan.*');
                break;
            case 'total':
                $query->orderBy('total', $sortDir);
                break;
            case 'status':
                $query->orderBy('status', $sortDir);
                break;
            case 'created_by':
                $query->join('users', 'retur_penjualan.user_id', '=', 'users.id')
                    ->orderBy('users.name', $sortDir)
                    ->select('retur_penjualan.*');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        // Get filter parameters
        $search = $request->input('search', '');
        $dateFilter = $request->input('date_filter', '');
        $dateStart = $request->input('date_start', '');
        $dateEnd = $request->input('date_end', '');
        $customerId = $request->input('customer_id', '');

        // Search functionality
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('company', 'like', "%{$search}%");
                    })
                    ->orWhereHas('salesOrder', function ($q) use ($search) {
                        $q->where('nomor', 'like', "%{$search}%");
                    });
            });
        }

        // Date filter functionality
        if ($dateFilter) {
            $today = Carbon::today();

            try {
                switch ($dateFilter) {
                    case 'today':
                        $query->whereDate('tanggal', $today->toDateString());
                        break;
                    case 'yesterday':
                        $query->whereDate('tanggal', $today->copy()->subDay()->toDateString());
                        break;
                    case 'this_week':
                        $query->whereBetween('tanggal', [
                            $today->copy()->startOfWeek()->toDateString(),
                            $today->copy()->endOfWeek()->toDateString()
                        ]);
                        break;
                    case 'last_week':
                        $lastWeekStart = $today->copy()->subWeek()->startOfWeek()->toDateString();
                        $lastWeekEnd = $today->copy()->subWeek()->endOfWeek()->toDateString();
                        $query->whereBetween('tanggal', [$lastWeekStart, $lastWeekEnd]);
                        break;
                    case 'this_month':
                        $query->whereMonth('tanggal', $today->month)
                            ->whereYear('tanggal', $today->year);
                        break;
                    case 'last_month':
                        $lastMonth = $today->copy()->subMonth();
                        $query->whereMonth('tanggal', $lastMonth->month)
                            ->whereYear('tanggal', $lastMonth->year);
                        break;
                    case 'range':
                        if ($dateStart && $dateEnd) {
                            // Ensure dates are in correct format
                            try {
                                $startDate = Carbon::parse($dateStart)->startOfDay()->toDateString();
                                $endDate = Carbon::parse($dateEnd)->endOfDay()->toDateString();
                                $query->whereBetween('tanggal', [$startDate, $endDate]);
                            } catch (\Exception $e) {
                                Log::error('Error parsing date range: ' . $e->getMessage());
                            }
                        }
                        break;
                }

                // Log debug info if needed
                if (config('app.debug')) {
                    \App\Helpers\ReturPenjualanDebugger::debugDateFilter(
                        $dateFilter,
                        $dateStart,
                        $dateEnd,
                        $today
                    );
                }
            } catch (\Exception $e) {
                Log::error('Error in date filtering: ' . $e->getMessage(), [
                    'date_filter' => $dateFilter,
                    'date_start' => $dateStart,
                    'date_end' => $dateEnd,
                    'today' => $today ? $today->toDateString() : null,
                    'today_class' => $today ? get_class($today) : null,
                    'trace' => $e->getTraceAsString()
                ]);

                // Enhanced error handling - we'll return this in the response for debugging
                if (config('app.debug')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error in date filtering: ' . $e->getMessage(),
                        'filter_used' => $dateFilter,
                        'today_date' => $today ? $today->toDateString() : null,
                        'query_sql' => $query->toSql(),
                        'sql_bindings' => $query->getBindings()
                    ], 500);
                }
            }
        }

        // Customer filter
        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        $returPenjualan = $query->paginate(10);

        // Get status counts for summary cards
        $statusCounts = [
            'draft' => ReturPenjualan::where('status', 'draft')->count(),
            'menunggu_persetujuan' => ReturPenjualan::where('status', 'menunggu_persetujuan')->count(),
            'disetujui' => ReturPenjualan::where('status', 'disetujui')->count(),
            'ditolak' => ReturPenjualan::where('status', 'ditolak')->count(),
            'diproses' => ReturPenjualan::where('status', 'diproses')->count(),
            'menunggu_barang_pengganti' => ReturPenjualan::where('status', 'menunggu_barang_pengganti')->count(),
            'selesai' => ReturPenjualan::where('status', 'selesai')->count(),
        ];

        $customers = Customer::where('is_active', true)->orderBy('nama')->get();

        // Handle AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $tableHtml = view('penjualan.retur_penjualan._table', compact('returPenjualan'))->render();
                $paginationHtml = $returPenjualan->appends(request()->except('page'))->links('vendor.pagination.tailwind-custom')->render();

                return response()->json([
                    'html' => $tableHtml,
                    'pagination' => $paginationHtml,
                    'success' => true
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error processing request: ' . $e->getMessage()
                ], 500);
            }
        }

        // Log aktivitas


        return view('penjualan.retur_penjualan.index', compact(
            'returPenjualan',
            'statusCounts',
            'validStatuses',
            'customers',
            'search',
            'dateFilter',
            'dateStart',
            'dateEnd',
            'customerId'
        ))->with('currentStatus', $status);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('is_active', true)->orderBy('nama')->get();
        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();
        $satuans = Satuan::orderBy('nama')->get();

        // Generate nomor retur
        $nomorRetur = $this->generateNomorRetur();



        return view('penjualan.retur_penjualan.create', compact(
            'customers',
            'gudangs',
            'satuans',
            'nomorRetur'
        ));
    }

    /**
     * Generate nomor retur
     */
    private function generateNomorRetur()
    {
        $today = date('Ymd');
        $lastRetur = ReturPenjualan::where('nomor', 'like', "RP-{$today}%")
            ->orderBy('nomor', 'desc')
            ->first();

        $sequence = '001';
        if ($lastRetur) {
            $lastSequence = (int) substr($lastRetur->nomor, -3);
            $sequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);
        }

        return "RP-{$today}-{$sequence}";
    }

    /**
     * Get sales orders for a customer
     */
    public function getSalesOrders(Request $request)
    {
        $customerId = $request->input('customer_id');
        if (!$customerId) {
            return response()->json(['salesOrders' => []]);
        }

        // Get only sales orders that have been delivered (have delivery orders)
        // Status can be 'selesai' (completed) or 'dikirim' (shipped)
        $salesOrders = SalesOrder::where('customer_id', $customerId)
            ->whereIn('status_pengiriman', ['sebagian', 'dikirim'])
            ->orderBy('tanggal', 'desc')
            ->get();

        /**
         * Get sales order details for a sales order
         */
        return response()->json(['salesOrders' => $salesOrders]);
    }

    /**
     * Get sales order details for a sales order
     */
    public function getSalesOrderDetails(Request $request)
    {
        $salesOrderId = $request->input('sales_order_id');
        if (!$salesOrderId) {
            return response()->json([]);
        }

        // Get sales order details with delivered quantities
        $details = SalesOrderDetail::where('sales_order_id', $salesOrderId)
            ->where('quantity_terkirim', '>', 0)
            ->with(['produk', 'produk.satuan'])
            ->get()
            ->map(function ($item) {
                // Convert all numeric values to float for consistency
                $quantityDelivered = (float) $item->quantity_terkirim;

                return [
                    'id' => $item->id,
                    'produk_id' => $item->produk_id,
                    'produk_kode' => $item->produk->kode,
                    'produk_nama' => $item->produk->nama,
                    'quantity_terkirim' => $quantityDelivered,
                    'qty_terkirim' => $quantityDelivered, // Add this property to match frontend expectations
                    'qty_so' => (float) $item->quantity, // Original ordered quantity
                    'qty' => $quantityDelivered, // Default qty to the delivered amount
                    'satuan_id' => $item->satuan_id,
                    'satuan_nama' => $item->produk->satuan->nama,
                    'harga' => (float) $item->harga,
                ];
            });

        // Log the details for debugging
        Log::debug('Sales Order Details for Return', [
            'sales_order_id' => $salesOrderId,
            'details_count' => $details->count(),
            'first_item' => $details->first(),
            'quantity_debug' => \App\Helpers\ReturPenjualanDebugger::debugQuantityValues($details->toArray())
        ]);

        return response()->json($details);
    }

    /**
     * Get sales order items
     */
    public function getSalesOrderItems(Request $request)
    {
        $salesOrderId = $request->input('sales_order_id');
        if (!$salesOrderId) {
            return response()->json(['details' => []]);
        }

        $salesOrderDetails = SalesOrderDetail::with(['produk', 'satuan'])
            ->where('sales_order_id', $salesOrderId)
            ->where('quantity', '>', 0) // All sold items can potentially be returned
            ->get();

        return response()->json([
            'details' => $salesOrderDetails->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'produk_id' => $detail->produk_id,
                    'produk' => [
                        'id' => $detail->produk_id,
                        'nama' => $detail->produk ? $detail->produk->nama : $detail->deskripsi,
                        'kode' => $detail->produk ? $detail->produk->kode : '',
                    ],
                    'satuan_id' => $detail->satuan_id,
                    'satuan' => [
                        'id' => $detail->satuan_id,
                        'nama' => $detail->satuan ? $detail->satuan->nama : 'Pcs',
                    ],
                    'quantity' => $detail->quantity,
                    'quantity_terkirim' => $detail->quantity_terkirim ?? $detail->quantity,
                    'harga_satuan' => $detail->harga,
                    'subtotal' => $detail->subtotal,
                    'quantity_retur' => 0
                ];
            })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'nomor' => 'required|string|unique:retur_penjualan,nomor',
            'tanggal' => 'required|date',
            'sales_order_id' => 'required|exists:sales_order,id',
            'customer_id' => 'required|exists:customer,id',
            'catatan' => 'nullable|string',
            'tipe_retur' => 'required|in:pengembalian_dana,tukar_barang',
            'details' => 'required|array|min:1',
            'details.*.produk_id' => 'required|exists:produk,id',
            'details.*.quantity' => 'required|numeric|min:0.01',
            'details.*.satuan_id' => 'required|exists:satuan,id',
            'details.*.alasan' => 'required|string',
            'details.*.keterangan' => 'nullable|string',
            'details.*.quality_check' => 'nullable|boolean', // Add quality check field
        ]);

        // Check if details have at least one item with quantity > 0
        $hasValidDetails = false;
        foreach ($validated['details'] as $detail) {
            if ($detail['quantity'] > 0) {
                $hasValidDetails = true;
                break;
            }
        }

        if (!$hasValidDetails) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Minimal harus ada 1 produk dengan quantity retur lebih dari 0.');
        }

        try {
            DB::beginTransaction();

            // Calculate total value
            $totalNilaiRetur = 0;
            foreach ($validated['details'] as $detail) {
                // Skip items with quantity 0
                if ($detail['quantity'] <= 0) {
                    continue;
                }

                // Get the sales order detail to get the price
                $salesOrderDetail = SalesOrderDetail::where('sales_order_id', $validated['sales_order_id'])
                    ->where('produk_id', $detail['produk_id'])
                    ->first();

                $hargaSatuan = $salesOrderDetail ? $salesOrderDetail->harga : 0;
                $subtotal = $hargaSatuan * $detail['quantity'];
                $totalNilaiRetur += $subtotal;
            }

            // Create retur penjualan header
            $returPenjualan = ReturPenjualan::create([
                'nomor' => $validated['nomor'],
                'tanggal' => $validated['tanggal'],
                'sales_order_id' => $validated['sales_order_id'],
                'customer_id' => $validated['customer_id'],
                'user_id' => auth()->id(),
                'catatan' => $validated['catatan'] ?? null,
                'total' => $totalNilaiRetur, // Save the total value
                'status' => 'draft',
                'tipe_retur' => $validated['tipe_retur'],
            ]);

            // Process details
            foreach ($validated['details'] as $detail) {
                // Skip items with quantity 0
                if ($detail['quantity'] <= 0) {
                    continue;
                }

                // Get product information
                $produk = Produk::findOrFail($detail['produk_id']);

                // Get the sales order detail to get the price
                $salesOrderDetail = SalesOrderDetail::where('sales_order_id', $validated['sales_order_id'])
                    ->where('produk_id', $detail['produk_id'])
                    ->first();

                $hargaSatuan = $salesOrderDetail ? $salesOrderDetail->harga : 0;
                $subtotal = $hargaSatuan * $detail['quantity'];

                // Create detail record
                ReturPenjualanDetail::create([
                    'retur_id' => $returPenjualan->id,
                    'produk_id' => $detail['produk_id'],
                    'quantity' => $detail['quantity'],
                    'satuan_id' => $detail['satuan_id'],
                    'alasan' => $detail['alasan'],
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);
            }

            // Log the activity with more detailed information
            $detailLog = 'Membuat retur penjualan baru: ' . $returPenjualan->nomor .
                ' | Customer: ' . $returPenjualan->customer->company ?? $returPenjualan->customer->nama .
                ' | Sales Order: ' . $returPenjualan->salesOrder->nomor .
                ' | Total: Rp ' . number_format($totalNilaiRetur, 0, ',', '.') .
                ' | Jumlah Item: ' . count(array_filter($validated['details'], function ($d) {
                    return $d['quantity'] > 0;
                }));

            $this->logActivity('tambah', $returPenjualan->id, $detailLog);

            DB::commit();

            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('success', 'Retur penjualan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error
            Log::error('Error creating retur penjualan: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $returPenjualan = ReturPenjualan::with([
            'salesOrder',
            'customer',
            'user',
            'details.produk',
            'details.satuan'
        ])->findOrFail($id);

        // Get log aktivitas
        $logAktivitas = LogAktivitas::with('user')
            ->where('modul', 'retur_penjualan')
            ->where('data_id', $returPenjualan->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Get gudang for modal
        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();



        return view('penjualan.retur_penjualan.show', compact(
            'returPenjualan',
            'logAktivitas',
            'gudangs'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $returPenjualan = ReturPenjualan::with(['customer', 'salesOrder', 'details.produk.satuan'])->findOrFail($id);

        // Only draft status can be edited
        if ($returPenjualan->status !== 'draft') {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Hanya retur penjualan dengan status draft yang dapat diubah.');
        }

        // Debug quantity values in the return details
        $quantityDebug = \App\Helpers\ReturPenjualanDebugger::debugQuantityValues(
            $returPenjualan->details->map(function ($detail) {
                return [
                    'produk_id' => $detail->produk_id,
                    'produk_nama' => $detail->produk->nama,
                    'qty_terkirim' => (float) ($detail->quantity_terkirim ?? 0),
                    'quantity_terkirim' => (float) ($detail->quantity_terkirim ?? 0),
                    'qty_so' => (float) ($detail->qty_so ?? 0),
                    'qty' => (float) ($detail->quantity ?? 0)
                ];
            })->toArray()
        );

        // Log the debug data
        Log::debug('Retur Penjualan Edit - Quantity Debug', [
            'retur_id' => $returPenjualan->id,
            'details_count' => $returPenjualan->details->count(),
            'quantity_debug' => $quantityDebug
        ]);

        $customers = Customer::where('is_active', true)->orderBy('nama')->get();
        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();
        $satuans = Satuan::orderBy('nama')->get();

        // Get sales orders for this customer
        $salesOrders = SalesOrder::where('customer_id', $returPenjualan->customer_id)
            ->whereIn('status_pengiriman', ['sebagian', 'dikirim'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('penjualan.retur_penjualan.edit', compact(
            'returPenjualan',
            'customers',
            'gudangs',
            'satuans',
            'salesOrders'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $returPenjualan = ReturPenjualan::with('details')->findOrFail($id);

        // Only draft status can be edited
        if ($returPenjualan->status !== 'draft') {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Hanya retur penjualan dengan status draft yang dapat diubah.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'sales_order_id' => 'required|exists:sales_order,id',
            'customer_id' => 'required|exists:customer,id',
            'catatan' => 'nullable|string',
            'details' => 'required|array|min:1',
            'details.*.produk_id' => 'required|exists:produk,id',
            'details.*.quantity' => 'required|numeric|min:0.01',
            'details.*.satuan_id' => 'required|exists:satuan,id',
            'details.*.alasan' => 'required|string',
            'details.*.keterangan' => 'nullable|string',
            'details.*.id' => 'nullable|exists:retur_penjualan_detail,id',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total value
            $totalNilaiRetur = 0;
            foreach ($validated['details'] as $item) {
                if (empty($item['quantity']) || $item['quantity'] <= 0) {
                    continue; // Skip items with zero or negative quantity
                }

                // Get the sales order detail to get the price
                $salesOrderDetail = SalesOrderDetail::where('sales_order_id', $validated['sales_order_id'])
                    ->where('produk_id', $item['produk_id'])
                    ->first();

                $hargaSatuan = $salesOrderDetail ? $salesOrderDetail->harga : 0;
                $subtotal = $hargaSatuan * $item['quantity'];
                $totalNilaiRetur += $subtotal;
            }

            // Update retur penjualan header
            $returPenjualan->update([
                'tanggal' => $validated['tanggal'],
                'sales_order_id' => $validated['sales_order_id'],
                'customer_id' => $validated['customer_id'],
                'catatan' => $validated['catatan'] ?? null,
                'total' => $totalNilaiRetur, // Update the total value
            ]);

            // Get existing detail IDs
            $existingIds = collect($validated['details'])
                ->pluck('id')
                ->filter()
                ->toArray();

            // Delete removed details
            $returPenjualan->details()
                ->whereNotIn('id', $existingIds)
                ->delete();

            // Update or create details
            foreach ($validated['details'] as $item) {
                if (empty($item['quantity']) || $item['quantity'] <= 0) {
                    continue; // Skip items with zero or negative quantity
                }

                $detailData = [
                    'produk_id' => $item['produk_id'],
                    'quantity' => $item['quantity'],
                    'satuan_id' => $item['satuan_id'],
                    'alasan' => $item['alasan'],
                    'keterangan' => $item['keterangan'] ?? null,
                ];

                if (!empty($item['id'])) {
                    // Update existing detail
                    ReturPenjualanDetail::where('id', $item['id'])
                        ->where('retur_id', $returPenjualan->id)
                        ->update($detailData);
                } else {
                    // Create new detail
                    $detailData['retur_id'] = $returPenjualan->id;
                    ReturPenjualanDetail::create($detailData);
                }
            }

            DB::commit();

            // Enhanced log with detailed information
            $detailLog = 'Mengubah retur penjualan: ' . $returPenjualan->nomor .
                ' | Customer: ' . $returPenjualan->customer->nama .
                ' | Sales Order: ' . $returPenjualan->salesOrder->nomor .
                ' | Total Baru: Rp ' . number_format($totalNilaiRetur, 0, ',', '.') .
                ' | Jumlah Item: ' . count(array_filter($validated['details'], function ($i) {
                    return !empty($i['quantity']) && $i['quantity'] > 0;
                }));

            $this->logActivity('ubah', $returPenjualan->id, $detailLog);

            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('success', 'Retur Penjualan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Process the return - add inventory back to warehouse
     */
    public function prosesRetur($id)
    {
        $returPenjualan = ReturPenjualan::with([
            'details.produk',
            'details.satuan',
            'customer',
            'salesOrder'
        ])->findOrFail($id);

        // Can only process from disetujui status (with the new approval workflow)
        if ($returPenjualan->status !== 'disetujui' && $returPenjualan->status !== 'draft') {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Retur penjualan ini tidak dapat diproses karena statusnya ' . $returPenjualan->status);
        }

        // Check if there are details to process
        if ($returPenjualan->details->isEmpty()) {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Tidak ada item untuk diproses.');
        }

        try {
            DB::beginTransaction();

            // Update status to diproses
            $returPenjualan->status = 'diproses';
            $returPenjualan->save();

            // Enhanced log with detailed information
            $itemCount = $returPenjualan->details->count();
            $totalValue = $returPenjualan->total ?? 0;

            $detailLog = 'Memproses retur penjualan: ' . $returPenjualan->nomor .
                ' | Customer: ' . $returPenjualan->customer->nama .
                ' | Sales Order: ' . $returPenjualan->salesOrder->nomor .
                ' | Total: Rp ' . number_format($totalValue, 0, ',', '.') .
                ' | Jumlah Item: ' . $itemCount;

            $this->logActivity('proses', $returPenjualan->id, $detailLog);

            DB::commit();

            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('success', 'Retur penjualan berhasil diproses. Status diubah menjadi diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Complete the return process - add inventory back to warehouse and update financial status
     */
    public function selesaikanRetur(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'gudang_id' => 'required|exists:gudang,id'
        ]);

        $returPenjualan = ReturPenjualan::with([
            'details.produk',
            'details.satuan',
            'customer',
            'salesOrder.details'
        ])->findOrFail($id);

        // Can only complete from diproses status
        if ($returPenjualan->status !== 'diproses') {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Retur penjualan ini tidak dapat diselesaikan karena statusnya ' . $returPenjualan->status);
        }

        try {
            DB::beginTransaction();

            // Get SO details for validating quantities and getting prices
            $soDetails = $returPenjualan->salesOrder->details;

            // Add inventory back for each product
            foreach ($returPenjualan->details as $detail) {
                // Skip products that didn't pass QC
                if (isset($detail->qc_checked) && $detail->qc_checked && !$detail->qc_passed) {
                    continue;
                }

                // Validate that the return quantity doesn't exceed the delivered quantity in SO
                $matchingSoDetail = $soDetails->where('produk_id', $detail->produk_id)->first();

                if (!$matchingSoDetail) {
                    throw new \Exception("Produk {$detail->produk->nama} tidak ditemukan di Sales Order.");
                }

                if ($matchingSoDetail->quantity_terkirim < $detail->quantity) {
                    throw new \Exception("Kuantitas retur untuk produk {$detail->produk->nama} melebihi kuantitas yang dikirim di SO.");
                }

                // Find or create stock record for this product in the warehouse
                $stok = StokProduk::firstOrCreate(
                    [
                        'produk_id' => $detail->produk_id,
                        'gudang_id' => $request->gudang_id,
                    ],
                    [
                        'jumlah' => 0,
                        'nilai' => 0,
                    ]
                );

                // Record current stock level
                $jumlahSebelum = $stok->jumlah;

                // Add stock back - increase inventory
                $stok->jumlah += $detail->quantity;
                $stok->save();

                // Record stock history
                RiwayatStok::create([
                    'stok_id' => $stok->id,
                    'produk_id' => $detail->produk_id,
                    'gudang_id' => $request->gudang_id,
                    'jumlah_sebelum' => $jumlahSebelum,
                    'jumlah_perubahan' => $detail->quantity,
                    'jumlah_setelah' => $stok->jumlah,
                    'jenis' => 'masuk',
                    'referensi_tipe' => 'retur_penjualan',
                    'referensi_id' => $returPenjualan->id,
                    'tanggal' => now(),
                    'keterangan' => "Penambahan dari retur penjualan #{$returPenjualan->nomor}",
                    'user_id' => Auth::id()
                ]);
            }

            // Calculate total value of returned items
            $totalNilaiRetur = 0;
            foreach ($returPenjualan->details as $detail) {
                // Skip products that didn't pass QC
                if (isset($detail->qc_checked) && $detail->qc_checked && !$detail->qc_passed) {
                    continue;
                }

                $matchingSoDetail = $soDetails->where('produk_id', $detail->produk_id)->first();
                if ($matchingSoDetail) {
                    $totalNilaiRetur += $matchingSoDetail->harga * $detail->quantity;
                }
            }

            // Handle differently based on return type
            if ($returPenjualan->tipe_retur === 'pengembalian_dana') {
                // Update SO payment status based on the return value
                $so = $returPenjualan->salesOrder;

                // For cash refund type, update financial status
                if ($so->status_pembayaran == 'lunas') {
                    // If SO was fully paid, create a credit balance
                    $so->kelebihan_bayar = ($so->kelebihan_bayar ?? 0) + $totalNilaiRetur;
                    $so->status_pembayaran = 'kelebihan_bayar';
                    $so->save();
                } elseif ($so->status_pembayaran == 'sebagian') {
                    // If SO was partially paid, reduce the outstanding amount
                    $outstandingAmount = $so->total - ($so->total_pembayaran ?? 0);
                    if ($totalNilaiRetur >= $outstandingAmount) {
                        // Return value covers remaining amount
                        $so->kelebihan_bayar = $totalNilaiRetur - $outstandingAmount;
                        $so->status_pembayaran = $so->kelebihan_bayar > 0 ? 'kelebihan_bayar' : 'lunas';
                    } else {
                        // Partial credit applied
                        $so->total_pembayaran = ($so->total_pembayaran ?? 0) + $totalNilaiRetur;
                        if ($so->total_pembayaran >= $so->total) {
                            $so->status_pembayaran = 'lunas';
                        }
                    }
                    $so->save();
                }

                // Update status to completed for pengembalian_dana
                $returPenjualan->status = 'selesai';
            } else if ($returPenjualan->tipe_retur === 'tukar_barang') {
                // For product exchange type, set to waiting for replacement
                $returPenjualan->status = 'menunggu_barang_pengganti';

                // No changes to SO financial status for tukar_barang
            }

            // Save changes to retur_penjualan
            $returPenjualan->save();

            // Enhanced completion log with detailed information
            $gudang = Gudang::find($request->gudang_id);

            // Count passed and failed QC items
            $passedQcCount = $returPenjualan->details->filter(function ($detail) {
                return !isset($detail->qc_checked) || !$detail->qc_checked || $detail->qc_passed;
            })->count();

            $failedQcCount = $returPenjualan->details->filter(function ($detail) {
                return isset($detail->qc_checked) && $detail->qc_checked && !$detail->qc_passed;
            })->count();

            $detailLog = 'Menyelesaikan retur penjualan: ' . $returPenjualan->nomor .
                ' | Customer: ' . $returPenjualan->customer->nama .
                ' | Sales Order: ' . $returPenjualan->salesOrder->nomor .
                ' | Nilai Total: Rp ' . number_format($totalNilaiRetur, 0, ',', '.') .
                ' | Jumlah Item: ' . $returPenjualan->details->count() .
                ' | Item Lolos QC: ' . $passedQcCount .
                ' | Item Tidak Lolos QC: ' . $failedQcCount .
                ' | Gudang Tujuan: ' . ($gudang ? $gudang->nama : 'Unknown') .
                ' | Tipe Retur: ' . $returPenjualan->tipe_retur;

            $this->logActivity('selesai', $returPenjualan->id, $detailLog);

            DB::commit();

            if ($returPenjualan->tipe_retur === 'pengembalian_dana') {
                return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                    ->with('success', 'Retur penjualan berhasil diselesaikan. Stok telah dikembalikan ke gudang.');
            } else if ($returPenjualan->tipe_retur === 'tukar_barang') {
                return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                    ->with('success', 'Retur penjualan untuk tukar barang telah diproses. Status diubah menjadi menunggu barang pengganti. Silakan koordinasikan dengan customer untuk penggantian barang.');
            }

            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('success', 'Retur penjualan berhasil diselesaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $returPenjualan = ReturPenjualan::findOrFail($id);

        // Only draft status can be deleted
        if ($returPenjualan->status !== 'draft') {
            return back()->with('error', 'Hanya retur penjualan dengan status draft yang dapat dihapus.');
        }

        try {
            DB::beginTransaction();

            // Store info for logging
            $returInfo = [
                'id' => $returPenjualan->id,
                'nomor' => $returPenjualan->nomor,
                'customer' => $returPenjualan->customer->nama ?? $returPenjualan->customer->company ?? 'Customer tidak ditemukan'
            ];

            // Delete details first (cascade should handle this, but be explicit)
            $returPenjualan->details()->delete();

            // Delete the main record
            $returPenjualan->delete();

            DB::commit();

            // Enhanced deletion log with detailed information
            $detailLog = 'Menghapus retur penjualan: ' . $returInfo['nomor'] .
                ' | Customer: ' . $returInfo['customer'] .
                ' | ID: ' . $returInfo['id'];

            $this->logActivity('hapus', $id, $detailLog);

            return redirect()->route('penjualan.retur.index')
                ->with('success', 'Retur penjualan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export PDF
     */
    public function exportPdf(string $id)
    {
        // Increase the execution time limit for PDF generation
        ini_set('max_execution_time', 300); // Set to 5 minutes (300 seconds)
        // Increase memory limit for larger PDF generation
        ini_set('memory_limit', '512M');

        $returPenjualan = ReturPenjualan::with([
            'salesOrder',
            'customer',
            'user',
            'details.produk',
            'details.satuan'
        ])->findOrFail($id);

        // Enhanced PDF export log with detailed information
        // $detailLog = 'Export PDF retur penjualan: ' . $returPenjualan->nomor .
        //     ' | Customer: ' . $returPenjualan->customer->nama .
        //     ' | Sales Order: ' . $returPenjualan->salesOrder->nomor .
        //     ' | Status: ' . $returPenjualan->status .
        //     ' | Total: Rp ' . number_format($returPenjualan->total ?? 0, 0, ',', '.');

        // $this->logActivity('print', $returPenjualan->id, $detailLog);

        $pdf = Pdf::loadView('penjualan.retur_penjualan.pdf', compact('returPenjualan'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Retur-Penjualan-' . $returPenjualan->nomor . '.pdf');
    }

    /**
     * Log activity helper
     */
    private function logActivity($aktivitas, $dataId, $detail, $jsonData = null)
    {
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => $aktivitas,
            'modul' => 'retur_penjualan',
            'data_id' => $dataId,
            'ip_address' => request()->ip(),
            'detail' => $detail,
            'json_data' => $jsonData
        ]);
    }

    /**
     * Submit return for approval
     */
    public function submitForApproval($id)
    {
        $returPenjualan = ReturPenjualan::with([
            'details.produk',
            'details.satuan',
            'customer',
            'salesOrder'
        ])->findOrFail($id);

        // Can only submit from draft status
        if ($returPenjualan->status !== 'draft') {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Retur penjualan ini tidak dapat diajukan karena statusnya ' . $returPenjualan->status);
        }

        // Check if there are details to process
        if ($returPenjualan->details->isEmpty()) {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Tidak ada item untuk diajukan.');
        }

        try {
            DB::beginTransaction();

            // Update status to waiting for approval
            $returPenjualan->status = 'menunggu_persetujuan';
            $returPenjualan->save();

            // Enhanced log with detailed information
            $itemCount = $returPenjualan->details->count();
            $totalValue = $returPenjualan->total ?? 0;

            $detailLog = 'Mengajukan retur penjualan untuk persetujuan: ' . $returPenjualan->nomor .
                ' | Customer: ' . $returPenjualan->customer->nama .
                ' | Sales Order: ' . $returPenjualan->salesOrder->nomor .
                ' | Total: Rp ' . number_format($totalValue, 0, ',', '.') .
                ' | Jumlah Item: ' . $itemCount;

            $this->logActivity('ajukan_persetujuan', $returPenjualan->id, $detailLog);

            DB::commit();

            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('success', 'Retur penjualan berhasil diajukan untuk persetujuan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Approve a return
     */
    public function approveReturn(Request $request, $id)
    {
        $returPenjualan = ReturPenjualan::with([
            'details.produk',
            'details.satuan',
            'customer',
            'salesOrder'
        ])->findOrFail($id);

        // Can only approve if status is waiting for approval
        if ($returPenjualan->status !== 'menunggu_persetujuan') {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Retur penjualan ini tidak dapat disetujui karena statusnya ' . $returPenjualan->status);
        }

        try {
            DB::beginTransaction();

            // Update status to approved
            $returPenjualan->status = 'disetujui';
            $returPenjualan->save();

            // Enhanced log with detailed information
            $itemCount = $returPenjualan->details->count();
            $totalValue = $returPenjualan->total ?? 0;
            $notes = $request->input('notes') ?? 'Tidak ada catatan';

            $detailLog = 'Menyetujui retur penjualan: ' . $returPenjualan->nomor .
                ' | Customer: ' . $returPenjualan->customer->nama .
                ' | Sales Order: ' . $returPenjualan->salesOrder->nomor .
                ' | Total: Rp ' . number_format($totalValue, 0, ',', '.') .
                ' | Jumlah Item: ' . $itemCount .
                ' | Catatan: ' . $notes;

            $this->logActivity('setujui', $returPenjualan->id, $detailLog);

            DB::commit();

            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('success', 'Retur penjualan berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject a return
     */
    public function rejectReturn(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255'
        ]);

        $returPenjualan = ReturPenjualan::with([
            'details.produk',
            'details.satuan',
            'customer',
            'salesOrder'
        ])->findOrFail($id);

        // Can only reject if status is waiting for approval
        if ($returPenjualan->status !== 'menunggu_persetujuan') {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Retur penjualan ini tidak dapat ditolak karena statusnya ' . $returPenjualan->status);
        }

        try {
            DB::beginTransaction();

            // Update status to rejected
            $returPenjualan->status = 'ditolak';
            $returPenjualan->save();

            // Enhanced log with detailed information including rejection reason
            $itemCount = $returPenjualan->details->count();
            $totalValue = $returPenjualan->total ?? 0;
            $reason = $request->input('rejection_reason');

            $detailLog = 'Menolak retur penjualan: ' . $returPenjualan->nomor .
                ' | Customer: ' . $returPenjualan->customer->nama .
                ' | Sales Order: ' . $returPenjualan->salesOrder->nomor .
                ' | Total: Rp ' . number_format($totalValue, 0, ',', '.') .
                ' | Jumlah Item: ' . $itemCount .
                ' | Alasan Penolakan: ' . $reason;

            $this->logActivity('tolak', $returPenjualan->id, $detailLog);

            DB::commit();

            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('success', 'Retur penjualan ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Analyze return reasons
     */
    public function analyzeReturns(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $customerId = $request->input('customer_id');

        $query = ReturPenjualan::with(['details.produk', 'customer'])
            ->where('status', 'selesai')
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        $returPenjualans = $query->get();

        // Group by reason
        $reasonCounts = [];
        $reasonValues = [];
        $productCounts = [];
        $customerCounts = [];

        foreach ($returPenjualans as $retur) {
            $customerId = $retur->customer_id;
            $customerName = $retur->customer->company ?? $retur->customer->nama ?? 'Unknown';

            if (!isset($customerCounts[$customerId])) {
                $customerCounts[$customerId] = [
                    'id' => $customerId,
                    'name' => $customerName,
                    'count' => 0,
                    'value' => 0
                ];
            }

            $customerCounts[$customerId]['count']++;
            $customerCounts[$customerId]['value'] += $retur->total ?? 0;

            foreach ($retur->details as $detail) {
                $reason = $detail->alasan;
                $produkId = $detail->produk_id;
                $produkName = $detail->produk->nama ?? 'Unknown';

                // Get value for this item
                $salesOrderDetail = SalesOrderDetail::where('sales_order_id', $retur->sales_order_id)
                    ->where('produk_id', $detail->produk_id)
                    ->first();

                $hargaSatuan = $salesOrderDetail ? $salesOrderDetail->harga : 0;
                $value = $hargaSatuan * $detail->quantity;

                // Add to reason analysis
                if (!isset($reasonCounts[$reason])) {
                    $reasonCounts[$reason] = 0;
                    $reasonValues[$reason] = 0;
                }

                $reasonCounts[$reason]++;
                $reasonValues[$reason] += $value;

                // Add to product analysis
                if (!isset($productCounts[$produkId])) {
                    $productCounts[$produkId] = [
                        'id' => $produkId,
                        'name' => $produkName,
                        'count' => 0,
                        'value' => 0
                    ];
                }

                $productCounts[$produkId]['count']++;
                $productCounts[$produkId]['value'] += $value;
            }
        }

        // Convert to arrays for the view
        $reasonAnalysis = [];
        foreach ($reasonCounts as $reason => $count) {
            $reasonAnalysis[] = [
                'reason' => $reason,
                'count' => $count,
                'value' => $reasonValues[$reason],
                'percentage' => array_sum($reasonCounts) > 0 ? round(($count / array_sum($reasonCounts)) * 100, 2) : 0
            ];
        }

        // Sort in descending order of count
        usort($reasonAnalysis, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        // Sort products and customers by count
        $productAnalysis = array_values($productCounts);
        usort($productAnalysis, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        $customerAnalysis = array_values($customerCounts);
        usort($customerAnalysis, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        $customers = Customer::where('is_active', true)->orderBy('nama')->get();

        return view('penjualan.retur_penjualan.analysis', compact(
            'reasonAnalysis',
            'productAnalysis',
            'customerAnalysis',
            'startDate',
            'endDate',
            'customerId',
            'customers'
        ));
    }

    /**
     * Process quality control check for a return
     */
    public function processQualityControl(Request $request, $id)
    {
        $request->validate([
            'qc_passed' => 'required|boolean',
            'qc_notes' => 'nullable|string',
            'details' => 'required|array',
            'details.*.id' => 'required|exists:retur_penjualan_detail,id',
            'details.*.qc_checked' => 'required|boolean',
            'details.*.qc_passed' => 'required|boolean',
            'details.*.qc_notes' => 'nullable|string',
            'details.*.defect_type' => 'nullable|string',
        ]);

        $returPenjualan = ReturPenjualan::with(['details', 'customer', 'salesOrder'])->findOrFail($id);

        // Only disetujui status can be QC checked
        if ($returPenjualan->status !== 'disetujui') {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Retur penjualan ini tidak dapat di-QC karena statusnya ' . $returPenjualan->status);
        }

        try {
            DB::beginTransaction();

            // Update the main retur penjualan record
            $returPenjualan->qc_passed = $request->qc_passed;
            $returPenjualan->qc_notes = $request->qc_notes;
            $returPenjualan->qc_by_user_id = auth()->id();
            $returPenjualan->qc_at = now();

            // If QC passed, automatically set to diproses status
            if ($request->qc_passed) {
                $returPenjualan->status = 'diproses';
            }

            $returPenjualan->save();

            // Update each detail item QC status
            foreach ($request->details as $detail) {
                $returDetail = ReturPenjualanDetail::findOrFail($detail['id']);
                $returDetail->qc_checked = $detail['qc_checked'];
                $returDetail->qc_passed = $detail['qc_passed'];
                $returDetail->qc_notes = $detail['qc_notes'] ?? null;
                $returDetail->defect_type = $detail['defect_type'] ?? null;

                // Handle image uploads if provided
                if ($request->hasFile("details.{$detail['id']}.qc_images")) {
                    $images = [];
                    foreach ($request->file("details.{$detail['id']}.qc_images") as $image) {
                        $path = $image->store('retur-qc-images', 'public');
                        $images[] = $path;
                    }
                    $returDetail->qc_images = json_encode($images);
                }

                $returDetail->save();
            }

            // Log the quality control activity with detailed information
            $qcPassText = $request->qc_passed ? 'Lulus' : 'Tidak Lulus';
            $detailLog = 'Melakukan quality control pada retur penjualan: ' . $returPenjualan->nomor .
                ' | Customer: ' . $returPenjualan->customer->nama .
                ' | Sales Order: ' . $returPenjualan->salesOrder->nomor .
                ' | Hasil QC: ' . $qcPassText .
                ' | Catatan: ' . ($request->qc_notes ?? 'Tidak ada') .
                ' | Item Diperiksa: ' . count($request->details);

            $this->logActivity('quality_control', $returPenjualan->id, $detailLog);

            DB::commit();

            $message = $request->qc_passed
                ? 'Quality control berhasil dilakukan. Retur dinyatakan lulus QC dan status diubah menjadi diproses.'
                : 'Quality control berhasil dilakukan. Retur dinyatakan tidak lulus QC.';

            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display quality control form
     */
    public function showQualityControlForm($id)
    {
        $returPenjualan = ReturPenjualan::with([
            'details.produk',
            'details.satuan',
            'customer',
            'salesOrder'
        ])->findOrFail($id);

        // Only approved returns can be QC checked
        if ($returPenjualan->status !== 'disetujui') {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Hanya retur penjualan dengan status disetujui yang dapat diproses QC.');
        }

        $defectTypes = [
            'produk_rusak' => 'Produk Rusak',
            'cacat_produksi' => 'Cacat Produksi',
            'salah_kirim' => 'Salah Kirim',
            'kualitas_buruk' => 'Kualitas Buruk',
            'tidak_sesuai_spesifikasi' => 'Tidak Sesuai Spesifikasi',
            'expired' => 'Sudah Expired',
            'kemasan_rusak' => 'Kemasan Rusak',
            'lainnya' => 'Lainnya'
        ];

        return view('penjualan.retur_penjualan.quality_control', compact(
            'returPenjualan',
            'defectTypes'
        ));
    }

    /**
     * Display detailed quality control information
     */
    public function showQualityControlDetail($id)
    {
        $returPenjualan = ReturPenjualan::with([
            'details.produk',
            'details.satuan',
            'customer',
            'salesOrder',
            'qcByUser',
            'user'
        ])->findOrFail($id);

        // Check if QC has been performed
        if (!$returPenjualan->qc_at) {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Belum ada data quality control untuk retur penjualan ini.');
        }

        $defectTypes = [
            'produk_rusak' => 'Produk Rusak',
            'cacat_produksi' => 'Cacat Produksi',
            'salah_kirim' => 'Salah Kirim',
            'kualitas_buruk' => 'Kualitas Buruk',
            'tidak_sesuai_spesifikasi' => 'Tidak Sesuai Spesifikasi',
            'expired' => 'Sudah Expired',
            'kemasan_rusak' => 'Kemasan Rusak',
            'lainnya' => 'Lainnya'
        ];

        return view('penjualan.retur_penjualan.quality_control_detail', compact(
            'returPenjualan',
            'defectTypes'
        ));
    }

    /**
     * Create a credit note from a return
     */
    public function createCreditNote($id)
    {
        $returPenjualan = ReturPenjualan::with([
            'details.produk',
            'details.satuan',
            'customer',
            'salesOrder'
        ])->findOrFail($id);

        // Only completed returns can have credit notes created
        if ($returPenjualan->status !== 'selesai') {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Hanya retur penjualan dengan status selesai yang dapat dibuatkan nota kredit.');
        }

        // Only returns with type 'pengembalian_dana' can have credit notes created
        if ($returPenjualan->tipe_retur !== 'pengembalian_dana') {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Hanya retur penjualan dengan tipe pengembalian dana yang dapat dibuatkan nota kredit.');
        }

        // Check if a credit note already exists
        $existingCreditNote = NotaKredit::where('retur_penjualan_id', $id)->first();
        if ($existingCreditNote) {
            return redirect()->route('penjualan.nota-kredit.edit', $existingCreditNote->id)
                ->with('info', 'Nota kredit untuk retur ini sudah ada. Anda dialihkan ke halaman edit.');
        }

        // Generate nomor nota kredit
        $today = date('Ymd');
        $lastNota = NotaKredit::where('nomor', 'like', "NK-{$today}%")
            ->orderBy('nomor', 'desc')
            ->first();

        $sequence = '001';
        if ($lastNota) {
            $lastSequence = (int) substr($lastNota->nomor, -3);
            $sequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);
        }

        $nomorNotaKredit = "NK-{$today}-{$sequence}";
        $customers = Customer::where('is_active', true)->get();
        // Log activity


        return view('penjualan.nota_kredit.create', compact(
            'returPenjualan',
            'nomorNotaKredit',
            'customers'
        ));
    }

    /**
     * Format monetary values for display
     */
    private static function formatMoney($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Get stock information for a product in a specific warehouse
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

            return response()->json($responseData);
        } catch (\Exception $e) {
            Log::error('Error in getStokProduk: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data stok produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a quality control and returns statistics report
     */
    public function qcReport(Request $request)
    {
        // Parse date filters
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $status = $request->input('status');

        // Base query for returns in the date range
        $query = ReturPenjualan::whereBetween('tanggal', [$startDate, $endDate])
            ->with(['customer', 'qcByUser', 'details.produk']);

        // Apply status filter if provided
        if ($status) {
            $query->where('status', $status);
        }

        // Get all returns in the period for statistics
        $returns = $query->get();

        // QC statistics
        $qcPassedCount = $returns->where('qc_passed', true)->count();
        $qcFailedCount = $returns->where('qc_passed', false)->count();

        // Get recent QC activities
        $qcActivities = ReturPenjualan::whereNotNull('qc_at')
            ->with(['customer', 'qcByUser'])
            ->orderBy('qc_at', 'desc')
            ->take(10)
            ->get();

        // Return reason analysis data
        $reasonCounts = DB::table('retur_penjualan_detail')
            ->join('retur_penjualan', 'retur_penjualan.id', '=', 'retur_penjualan_detail.retur_id')
            ->whereBetween('retur_penjualan.tanggal', [$startDate, $endDate])
            ->select('alasan', DB::raw('count(*) as total'))
            ->groupBy('alasan')
            ->get()
            ->pluck('total', 'alasan')
            ->toArray();

        $reasonLabels = array_keys($reasonCounts);
        $reasonData = array_values($reasonCounts);

        // Return the view with statistics
        return view('penjualan.retur_penjualan.report', compact(
            'qcPassedCount',
            'qcFailedCount',
            'qcActivities',
            'reasonLabels',
            'reasonData',
            'returns'
        ));
    }

    /**
     * Show form to record delivery of replacement products
     */
    public function showTerimaBarangPengganti($id)
    {
        $returPenjualan = ReturPenjualan::with([
            'details.produk',
            'details.satuan',
            'customer',
            'salesOrder'
        ])->findOrFail($id);

        // Only return with status "menunggu_barang_pengganti" can receive replacement
        if ($returPenjualan->status !== 'menunggu_barang_pengganti') {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Hanya retur dengan status menunggu barang pengganti yang dapat diproses.');
        }

        // Get products that can be used as replacements
        $produks = Produk::with('satuan')
            ->orderBy('nama')
            ->get();

        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();

        // Log aktivitas


        return view('penjualan.retur_penjualan.terima_barang_pengganti', compact(
            'returPenjualan',
            'produks',
            'gudangs'
        ));
    }

    /**
     * Process delivery of replacement products
     */
    public function terimaBarangPengganti(Request $request, $id)
    {
        $request->validate([
            'tanggal_pengiriman' => 'required|date',
            'gudang_id' => 'required|exists:gudang,id',
            'no_referensi' => 'nullable|string|max:50',
            'catatan_pengiriman' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.satuan_id' => 'required|exists:satuan,id'
        ]);

        $returPenjualan = ReturPenjualan::findOrFail($id);

        // Only return with status "menunggu_barang_pengganti" can deliver replacement
        if ($returPenjualan->status !== 'menunggu_barang_pengganti') {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Hanya retur dengan status menunggu barang pengganti yang dapat diproses.');
        }

        DB::beginTransaction();
        try {
            // Process each replacement item
            foreach ($request->items as $item) {
                // Find stock record for this product in the warehouse
                $stok = StokProduk::firstOrCreate(
                    [
                        'produk_id' => $item['produk_id'],
                        'gudang_id' => $request->gudang_id,
                    ],
                    [
                        'jumlah' => 0,
                        'nilai' => 0,
                    ]
                );

                // Record current stock level
                $jumlahSebelum = $stok->jumlah;

                // Validate stock levels
                if ($stok->jumlah < $item['quantity']) {
                    throw new \Exception("Stok produk tidak mencukupi untuk pengiriman. Tersedia: {$stok->jumlah}, dibutuhkan: {$item['quantity']}.");
                }

                // Deduct stock - decrease inventory for replacement items
                $stok->jumlah -= $item['quantity'];
                $stok->save();

                // Get product name for record
                $produk = Produk::find($item['produk_id']);
                $produkNama = $produk ? $produk->nama : 'Produk #' . $item['produk_id'];

                // Record stock history
                RiwayatStok::create([
                    'stok_id' => $stok->id,
                    'produk_id' => $item['produk_id'],
                    'gudang_id' => $request->gudang_id,
                    'jumlah_sebelum' => $jumlahSebelum,
                    'jumlah_perubahan' => -$item['quantity'],
                    'jumlah_setelah' => $stok->jumlah,
                    'jenis' => 'keluar',
                    'referensi_tipe' => 'retur_penjualan_pengganti',
                    'referensi_id' => $returPenjualan->id,
                    'tanggal' => $request->tanggal_pengiriman,
                    'keterangan' => "Pengiriman barang pengganti {$produkNama} untuk retur #{$returPenjualan->nomor}",
                    'user_id' => Auth::id()
                ]);

                // Record replacement item
                ReturPenjualanPengganti::create([
                    'retur_id' => $returPenjualan->id,
                    'produk_id' => $item['produk_id'],
                    'gudang_id' => $request->gudang_id,
                    'satuan_id' => $item['satuan_id'],
                    'quantity' => $item['quantity'],
                    'tanggal_pengiriman' => $request->tanggal_pengiriman,
                    'no_referensi' => $request->no_referensi,
                    'catatan' => $request->catatan_pengiriman,
                    'user_id' => Auth::id()
                ]);
            }

            // Update status to complete
            $returPenjualan->status = 'selesai';
            $returPenjualan->save();

            // Log aktivitas
            $this->logActivity(
                'kirim_barang_pengganti',
                $returPenjualan->id,
                'Mengirim barang pengganti untuk retur penjualan: ' . $returPenjualan->nomor
            );

            DB::commit();

            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('success', 'Pengiriman barang pengganti berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Process the shipping of replacement items
     */
    public function prosesKirimPengganti($id)
    {
        $returPenjualan = ReturPenjualan::findOrFail($id);

        // Can only process from menunggu_pengiriman status
        if ($returPenjualan->status !== 'menunggu_pengiriman') {
            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('error', 'Pengiriman hanya dapat diproses untuk retur dengan status menunggu pengiriman.');
        }

        try {
            DB::beginTransaction();

            // Update the status to menunggu_barang_pengganti
            $returPenjualan->status = 'menunggu_barang_pengganti';
            $returPenjualan->save();

            // Log aktivitas
            $this->logActivity(
                'proses_kirim_pengganti',
                $returPenjualan->id,
                'Memproses pengiriman barang pengganti untuk retur penjualan: ' . $returPenjualan->nomor
            );

            DB::commit();

            return redirect()->route('penjualan.retur.show', $returPenjualan->id)
                ->with('success', 'Status retur berhasil diubah ke menunggu barang pengganti.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
