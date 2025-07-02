<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderDetail;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\Customer;
use App\Models\Gudang;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\StokProduk;
use App\Models\RiwayatStok;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\NotificationService;

class DeliveryOrderController extends Controller
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
     * Display a listing of the delivery orders.
     */
    public function index(Request $request)
    {
        $query = DeliveryOrder::with(['salesOrder', 'customer', 'gudang', 'user']);

        // --- Bagian Filter dan Sorting ---
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status', 'semua'), // Default to 'semua' (all statuses)
            'date_filter' => $request->input('date_filter'),
            'date_start' => $request->input('date_start'),
            'date_end' => $request->input('date_end'),
            'customer_id' => $request->input('customer_id'),
            'sales_order_id' => $request->input('sales_order_id'),
            'gudang_id' => $request->input('gudang_id'),
            'sort_by' => $request->input('sort_by', 'tanggal'), // Default to tanggal
            'sort_direction' => $request->input('sort_direction', 'desc'), // Default to desc (newest first)
        ];

        if ($filters['search']) {
            $query->where(function ($q) use ($filters) {
                $q->where('nomor', 'like', '%' . $filters['search'] . '%')
                    ->orWhereHas('salesOrder', function ($soQuery) use ($filters) {
                        $soQuery->where('nomor', 'like', '%' . $filters['search'] . '%');
                    })
                    ->orWhereHas('customer', function ($custQuery) use ($filters) {
                        $custQuery->where('nama', 'like', '%' . $filters['search'] . '%')
                            ->orWhere('company', 'like', '%' . $filters['search'] . '%');
                    });
            });
        }

        if ($filters['status'] !== 'semua') {
            $query->where('status', $filters['status']);
        }

        if ($filters['customer_id']) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if ($filters['sales_order_id']) {
            $query->where('sales_order_id', $filters['sales_order_id']);
        }

        if ($filters['gudang_id']) {
            $query->where('gudang_id', $filters['gudang_id']);
        }

        // Date filtering
        if ($filters['date_start']) {
            $query->whereDate('tanggal', '>=', $filters['date_start']);
        }

        if ($filters['date_end']) {
            $query->whereDate('tanggal', '<=', $filters['date_end']);
        }

        // Sorting
        $query->orderBy($filters['sort_by'], $filters['sort_direction']);

        $deliveryOrders = $query->paginate(5);
        $customers = Customer::orderBy('nama')->get();
        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();
        // dd($customers);

        return view('penjualan.delivery-order.index', compact('deliveryOrders', 'customers', 'gudangs', 'filters'));
    }

    /**
     * Show the form for creating a new delivery order.
     */
    public function create(Request $request)
    {
        $salesOrderId = $request->input('sales_order_id');
        $permintaanBarangId = $request->input('permintaan_barang_id');
        $salesOrder = null;
        $permintaanBarang = null;

        if ($salesOrderId) {
            $salesOrder = SalesOrder::with(['details.produk', 'details.satuan', 'customer'])
                ->findOrFail($salesOrderId);
        }

        if ($permintaanBarangId) {
            $permintaanBarang = \App\Models\PermintaanBarang::with(['details.produk', 'details.satuan'])
                ->findOrFail($permintaanBarangId);

            // Ensure sales order is loaded if coming from permintaan barang
            if (!$salesOrder && $permintaanBarang->sales_order_id) {
                $salesOrder = SalesOrder::with(['details.produk', 'details.satuan', 'customer'])
                    ->findOrFail($permintaanBarang->sales_order_id);
                $salesOrderId = $salesOrder->id;
            }
        }

        $salesOrders = SalesOrder::where('status_pengiriman', '!=', 'dikirim')
            ->orderBy('tanggal', 'desc')
            ->get();

        $customers = Customer::orderBy('nama')->get();
        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();

        // Generate nomor dokumen
        $lastDO = DeliveryOrder::orderBy('created_at', 'desc')->first();
        $lastNumber = $lastDO ? intval(substr($lastDO->nomor, -5)) : 0;
        $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        $prefix = 'DO-' . Carbon::now()->format('Ymd') . '-';
        $nomor = $prefix . $newNumber;

        return view('penjualan.delivery-order.create', compact(
            'salesOrders',
            'customers',
            'gudangs',
            'salesOrder',
            'permintaanBarang',
            'nomor'
        ));
    }

    /**
     * Store a newly created delivery order.
     */
    public function store(Request $request)
    {

        // Validate input
        $request->validate([
            'nomor' => 'required|unique:delivery_order,nomor',
            'tanggal' => 'required|date',
            'sales_order_id' => 'required|exists:sales_order,id',
            'customer_id' => 'required|exists:customer,id',
            'gudang_id' => 'required|exists:gudang,id',
            'alamat_pengiriman' => 'required|string',
            'catatan' => 'nullable|string',
            'produk_id' => 'required|array',
            'produk_id.*' => 'required|exists:produk,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:0',
            'satuan_id' => 'required|array',
            'satuan_id.*' => 'required|exists:satuan,id',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string',
        ]);

        // Custom validation to ensure at least one product has a quantity greater than 0
        $quantities = $request->quantity;
        $nonZeroQuantities = array_filter($quantities, function ($qty) {
            return floatval($qty) > 0;
        });

        if (empty($nonZeroQuantities)) {
            return back()->with('error', 'Minimal satu produk harus memiliki kuantitas lebih dari 0 untuk dikirim.')->withInput();
        }

        // If there's only one product, its quantity must be greater than 0
        if (count($quantities) === 1 && empty($nonZeroQuantities)) {
            return back()->with('error', 'Dengan hanya satu produk, kuantitas tidak boleh 0.')->withInput();
        }

        // dd($request->all());

        try {
            DB::beginTransaction();

            // Create delivery order
            $deliveryOrder = DeliveryOrder::create([
                'nomor' => $request->nomor,
                'tanggal' => $request->tanggal,
                'sales_order_id' => $request->sales_order_id,
                'customer_id' => $request->customer_id,
                'user_id' => Auth::id(),
                'gudang_id' => $request->gudang_id,
                'permintaan_barang_id' => $request->permintaan_barang_id,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'status' => 'draft',
                'catatan' => $request->catatan,
            ]);

            // Get the sales order
            $salesOrder = SalesOrder::findOrFail($request->sales_order_id);

            // Create delivery order details
            $detailCount = count($request->produk_id);
            $addedDetails = 0; // Track how many products with non-zero quantities are added

            for ($i = 0; $i < $detailCount; $i++) {
                // Skip items with zero or negative quantity
                // (We allow zero quantities to pass validation, but we don't create detail records for them)
                if ($request->quantity[$i] <= 0) {
                    continue;
                }

                $addedDetails++;

                // Get the sales order detail for this product
                $salesOrderDetail = SalesOrderDetail::where('sales_order_id', $request->sales_order_id)
                    ->where('produk_id', $request->produk_id[$i])
                    ->first();

                if (!$salesOrderDetail) {
                    throw new \Exception("Produk tidak ditemukan dalam Sales Order");
                }

                // Calculate remaining quantity that can be delivered
                $remainingQty = $salesOrderDetail->quantity - $salesOrderDetail->quantity_terkirim;

                if ($request->quantity[$i] > $remainingQty) {
                    throw new \Exception("Jumlah pengiriman melebihi sisa quantity untuk produk " .
                        Produk::find($request->produk_id[$i])->nama);
                }

                // Check stock availability
                $currentStock = StokProduk::where('gudang_id', $request->gudang_id)
                    ->where('produk_id', $request->produk_id[$i])
                    ->value('jumlah') ?? 0;

                if ($request->quantity[$i] > $currentStock) {
                    $produk = Produk::find($request->produk_id[$i]);
                    $gudang = Gudang::find($request->gudang_id);
                    throw new \Exception("Stok tidak mencukupi untuk produk {$produk->nama} di gudang {$gudang->nama}. Stok tersedia: {$currentStock}, Permintaan: {$request->quantity[$i]}");
                }

                // Create delivery order detail
                DeliveryOrderDetail::create([
                    'delivery_id' => $deliveryOrder->id,
                    'sales_order_detail_id' => $salesOrderDetail->id,
                    'produk_id' => $request->produk_id[$i],
                    'quantity' => $request->quantity[$i],
                    'satuan_id' => $request->satuan_id[$i],
                    'keterangan' => $request->keterangan[$i] ?? null,
                ]);
            }

            // Ensure at least one detail with non-zero quantity was added
            if ($addedDetails === 0) {
                throw new \Exception("Tidak ada produk dengan kuantitas lebih dari 0 yang dapat ditambahkan ke Delivery Order");
            }

            // Log aktivitas
            $this->logUserAktivitas(
                'Membuat Delivery Order baru',
                'delivery_order',
                $deliveryOrder->id,
                "Membuat Delivery Order dengan nomor {$deliveryOrder->nomor} untuk Sales Order {$salesOrder->nomor}"
            );

            // Update permintaan barang status if created from permintaan barang
            if ($request->has('permintaan_barang_id') && $request->permintaan_barang_id) {
                try {
                    // Use our automatic status checker
                    $this->updatePermintaanBarangStatus($request->permintaan_barang_id, $deliveryOrder->id);
                } catch (\Exception $e) {
                    // Just log the error but don't rollback
                    Log::error('Error updating permintaan barang status: ' . $e->getMessage());
                }
            }

            DB::commit();

            return redirect()->route('penjualan.delivery-order.show', $deliveryOrder->id)
                ->with('success', 'Delivery Order berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified delivery order.
     */
    public function show($id)
    {
        $deliveryOrder = DeliveryOrder::with([
            'salesOrder',
            'customer',
            'gudang',
            'user',
            'details.produk',
            'details.satuan'
        ])->findOrFail($id);

        // Get log activities related to this delivery order
        $logActivities = LogAktivitas::where('modul', 'delivery_order')
            ->where('data_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('penjualan.delivery-order.show', compact('deliveryOrder', 'logActivities'));
    }

    /**
     * Show the form for editing the specified delivery order.
     */
    public function edit($id)
    {
        $deliveryOrder = DeliveryOrder::with([
            'salesOrder.details.produk',
            'salesOrder.details.satuan',
            'customer',
            'gudang',
            'details.produk',
            'details.satuan'
        ])->findOrFail($id);

        // Can only edit in draft status
        if ($deliveryOrder->status !== 'draft') {
            return redirect()->route('penjualan.delivery-order.show', $deliveryOrder->id)
                ->with('error', 'Tidak dapat mengedit Delivery Order yang sudah diproses atau selesai!');
        }

        $salesOrders = SalesOrder::where('status_pengiriman', '!=', 'dikirim')
            ->orderBy('tanggal', 'desc')
            ->get();

        $customers = Customer::orderBy('nama')->get();
        $gudangs = Gudang::where('is_active', true)->orderBy('nama')->get();

        return view('penjualan.delivery-order.edit', compact(
            'deliveryOrder',
            'salesOrders',
            'customers',
            'gudangs'
        ));
    }

    /**
     * Update the specified delivery order.
     */
    public function update(Request $request, $id)
    {
        $deliveryOrder = DeliveryOrder::findOrFail($id);

        // Can only update in draft status
        if ($deliveryOrder->status !== 'draft') {
            return redirect()->route('penjualan.delivery-order.show', $deliveryOrder->id)
                ->with('error', 'Tidak dapat mengubah Delivery Order yang sudah diproses atau selesai!');
        }

        // Validate input
        $validatedData = $request->validate([
            'nomor' => 'required|unique:delivery_order,nomor,' . $id,
            'tanggal' => 'required|date',
            'sales_order_id' => 'required|exists:sales_order,id',
            'customer_id' => 'required|exists:customer,id',
            'gudang_id' => 'required|exists:gudang,id',
            'alamat_pengiriman' => 'required|string',
            'keterangan' => 'nullable|string', // This is for the main DO keterangan/catatan

            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.quantity' => 'required|numeric|min:0', // Allow 0 for initial submission, will be filtered later
            'items.*.satuan_id' => 'required|exists:satuan,id',
            'items.*.keterangan' => 'nullable|string',
            'items.*.sales_order_detail_id' => 'required|exists:sales_order_detail,id',
            'items.*.delivery_order_detail_id' => 'nullable|sometimes|exists:delivery_order_detail,id',
        ]);

        $nonZeroQuantities = false;
        foreach ($validatedData['items'] as $item) {
            if (isset($item['quantity']) && floatval($item['quantity']) > 0) {
                $nonZeroQuantities = true;
                break;
            }
        }

        if (!$nonZeroQuantities) {
            return back()->with('error', 'Minimal satu produk harus memiliki kuantitas lebih dari 0 untuk dikirim.')->withInput();
        }

        try {
            DB::beginTransaction();

            $deliveryOrder->update([
                'nomor' => $validatedData['nomor'],
                'tanggal' => $validatedData['tanggal'],
                'sales_order_id' => $validatedData['sales_order_id'],
                'customer_id' => $validatedData['customer_id'],
                'gudang_id' => $validatedData['gudang_id'],
                'alamat_pengiriman' => $validatedData['alamat_pengiriman'],
                'catatan' => $validatedData['keterangan'],
            ]);

            $salesOrder = SalesOrder::findOrFail($validatedData['sales_order_id']);
            $existingDetailIds = $deliveryOrder->details()->pluck('id')->toArray();
            $processedDetailIds = [];
            $addedDetailsCount = 0;

            foreach ($validatedData['items'] as $itemData) {
                if (floatval($itemData['quantity']) <= 0) {
                    // If an existing detail now has 0 quantity, it will be deleted later
                    continue;
                }
                $addedDetailsCount++;

                $salesOrderDetail = SalesOrderDetail::find($itemData['sales_order_detail_id']);

                if (!$salesOrderDetail || $salesOrderDetail->sales_order_id != $deliveryOrder->sales_order_id) {
                    $produk = Produk::find($itemData['produk_id']);
                    throw new \Exception("Detail Sales Order tidak valid atau tidak cocok untuk produk " . ($produk ? $produk->nama : 'Unknown'));
                }

                // Calculate max allowed quantity for this item based on SO and other DOs
                $shippedOnOtherDOs = DeliveryOrderDetail::where('sales_order_detail_id', $salesOrderDetail->id)
                    ->where('delivery_id', '!=', $deliveryOrder->id)
                    ->sum('quantity');
                $maxAllowedForThisItem = $salesOrderDetail->quantity - $shippedOnOtherDOs;

                if (floatval($itemData['quantity']) > $maxAllowedForThisItem) {
                    $produk = Produk::find($itemData['produk_id']);
                    throw new \Exception("Jumlah pengiriman untuk " . ($produk ? $produk->nama : 'Unknown') . " ({$itemData['quantity']}) melebihi sisa yang belum dikirim dari Sales Order ({$maxAllowedForThisItem}).");
                }

                $currentStock = StokProduk::where('gudang_id', $validatedData['gudang_id'])
                    ->where('produk_id', $itemData['produk_id'])
                    ->value('jumlah') ?? 0;

                if (floatval($itemData['quantity']) > $currentStock) {
                    $produk = Produk::find($itemData['produk_id']);
                    $gudang = Gudang::find($validatedData['gudang_id']);
                    throw new \Exception("Stok tidak mencukupi untuk produk " . ($produk ? $produk->nama : 'Unknown') . " di gudang " . ($gudang ? $gudang->nama : 'Unknown') . ". Stok tersedia: {$currentStock}, Permintaan: {$itemData['quantity']}");
                }

                // Use delivery_order_detail_id from form if available and valid, otherwise create new
                $deliveryOrderDetail = null;
                if (!empty($itemData['delivery_order_detail_id'])) {
                    $deliveryOrderDetail = DeliveryOrderDetail::where('id', $itemData['delivery_order_detail_id'])
                        ->where('delivery_id', $deliveryOrder->id)
                        ->first();
                }

                if ($deliveryOrderDetail) {
                    $deliveryOrderDetail->update([
                        'sales_order_detail_id' => $itemData['sales_order_detail_id'],
                        'produk_id' => $itemData['produk_id'],
                        'quantity' => $itemData['quantity'],
                        'satuan_id' => $itemData['satuan_id'],
                        'keterangan' => $itemData['keterangan'] ?? null,
                    ]);
                    $processedDetailIds[] = $deliveryOrderDetail->id;
                } else {
                    $newDetail = DeliveryOrderDetail::create([
                        'delivery_id' => $deliveryOrder->id,
                        'sales_order_detail_id' => $itemData['sales_order_detail_id'],
                        'produk_id' => $itemData['produk_id'],
                        'quantity' => $itemData['quantity'],
                        'satuan_id' => $itemData['satuan_id'],
                        'keterangan' => $itemData['keterangan'] ?? null,
                    ]);
                    $processedDetailIds[] = $newDetail->id;
                }
            }

            // Delete details that were removed from the form or had quantity set to 0
            $detailsToDelete = array_diff($existingDetailIds, $processedDetailIds);
            if (!empty($detailsToDelete)) {
                DeliveryOrderDetail::whereIn('id', $detailsToDelete)->delete();
            }

            if ($addedDetailsCount === 0 && $deliveryOrder->details()->count() === 0) { // Check if after deletions, there are no details left
                throw new \Exception("Tidak ada produk dengan kuantitas valid yang dapat disimpan. Pastikan minimal satu item memiliki kuantitas lebih dari 0.");
            }

            $this->logUserAktivitas(
                'Mengupdate Delivery Order',
                'delivery_order',
                $deliveryOrder->id,
                "Mengupdate Delivery Order dengan nomor {$deliveryOrder->nomor} untuk Sales Order {$salesOrder->nomor}"
            );

            DB::commit();

            return redirect()->route('penjualan.delivery-order.show', $deliveryOrder->id)
                ->with('success', 'Delivery Order berhasil diupdate!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating Delivery Order {$id}: " . $e->getMessage() . " Stack: " . $e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified delivery order.
     */
    public function destroy($id)
    {
        $deliveryOrder = DeliveryOrder::findOrFail($id);

        // Can only delete in draft status
        if ($deliveryOrder->status !== 'draft') {
            return redirect()->route('penjualan.delivery-order.show', $deliveryOrder->id)
                ->with('error', 'Tidak dapat menghapus Delivery Order yang sudah diproses atau selesai!');
        }

        try {
            DB::beginTransaction();

            // Delete all delivery order details
            $deliveryOrder->details()->delete();

            // Log aktivitas before deletion
            $this->logUserAktivitas(
                'Menghapus Delivery Order',
                'delivery_order',
                $deliveryOrder->id,
                "Menghapus Delivery Order dengan nomor {$deliveryOrder->nomor}"
            );

            // Delete the delivery order
            $deliveryOrder->delete();

            DB::commit();

            return redirect()->route('penjualan.delivery-order.index')
                ->with('success', 'Delivery Order berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Process the delivery order (reducing stock).
     */
    public function prosesDelivery($id)
    {
        // dd($id);
        $deliveryOrder = DeliveryOrder::with(['details.produk', 'details.satuan'])->findOrFail($id);

        // Can only process in draft status
        if ($deliveryOrder->status !== 'draft') {
            return redirect()->route('penjualan.delivery-order.show', $deliveryOrder->id)
                ->with('error', 'Delivery Order ini tidak dapat diproses karena sudah dalam status ' . $deliveryOrder->status);
        }

        try {
            DB::beginTransaction();

            // Check stock availability in the warehouse
            foreach ($deliveryOrder->details as $detail) {
                $stok = StokProduk::where('gudang_id', $deliveryOrder->gudang_id)
                    ->where('produk_id', $detail->produk_id)
                    ->first();

                if (!$stok || $stok->jumlah < $detail->quantity) {
                    throw new \Exception("Stok produk {$detail->produk->nama} tidak mencukupi di gudang.");
                }
            }

            // Deduct stock from warehouse and update sales order detail quantities
            foreach ($deliveryOrder->details as $detail) {
                // Update stock
                $this->kurangiStokGudang(
                    $detail->produk_id,
                    $deliveryOrder->gudang_id,
                    $detail->quantity,
                    $deliveryOrder->id
                );

                // Update sales order detail quantity_terkirim
                $salesOrderDetail = SalesOrderDetail::where('sales_order_id', $deliveryOrder->sales_order_id)
                    ->where('produk_id', $detail->produk_id)
                    ->first();

                if ($salesOrderDetail) {
                    $salesOrderDetail->quantity_terkirim += $detail->quantity;
                    $salesOrderDetail->save();
                }
            }

            // Update delivery order status
            $deliveryOrder->status = 'dikirim';
            $deliveryOrder->save();

            // Send notification to managers about shipped delivery order
            $notificationService = app(NotificationService::class);
            $notificationService->notifyDeliveryOrderShipped($deliveryOrder, Auth::user());

            // Update sales order status if all items are delivered
            // $this->updateSalesOrderStatus($deliveryOrder->sales_order_id);

            // Update permintaan barang status if it exists
            if ($deliveryOrder->permintaan_barang_id) {
                try {
                    $this->updatePermintaanBarangStatus($deliveryOrder->permintaan_barang_id, $deliveryOrder->id);
                } catch (\Exception $e) {
                    // Just log the error but don't rollback
                    Log::error('Error updating permintaan barang status: ' . $e->getMessage());
                }
            }

            // Log aktivitas
            $this->logUserAktivitas(
                'Memproses pengiriman Delivery Order',
                'delivery_order',
                $deliveryOrder->id,
                "Memproses pengiriman Delivery Order dengan nomor {$deliveryOrder->nomor}. Stok dikurangi dari gudang."
            );

            DB::commit();

            return redirect()->route('penjualan.delivery-order.show', $deliveryOrder->id)
                ->with('success', 'Delivery Order sedang diproses. Stok di gudang telah dikurangi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Complete the delivery process (mark as received).
     */
    public function selesaikanDelivery(Request $request, $id)
    {
        $deliveryOrder = DeliveryOrder::findOrFail($id);

        // Can only complete in 'dikirim' status
        if ($deliveryOrder->status !== 'dikirim') {
            return redirect()->route('penjualan.delivery-order.show', $deliveryOrder->id)
                ->with('error', 'Delivery Order ini tidak dapat diselesaikan karena statusnya ' . $deliveryOrder->status);
        }

        // Validate input
        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'keterangan_penerima' => 'nullable|string',
            'tanggal_diterima' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Update delivery order
            $deliveryOrder->update([
                'status' => 'diterima',
                'nama_penerima' => $request->nama_penerima,
                'keterangan_penerima' => $request->keterangan_penerima,
                'tanggal_diterima' => $request->tanggal_diterima,
            ]);

            // Log aktivitas
            $this->logUserAktivitas(
                'Menyelesaikan Delivery Order',
                'delivery_order',
                $deliveryOrder->id,
                "Menyelesaikan Delivery Order dengan nomor {$deliveryOrder->nomor}. Diterima oleh: {$request->nama_penerima}"
            );

            // Update permintaan barang status if it exists using our auto-status checker
            if ($deliveryOrder->permintaan_barang_id) {
                try {
                    $this->updatePermintaanBarangStatus($deliveryOrder->permintaan_barang_id, $deliveryOrder->id);
                } catch (\Exception $e) {
                    // Just log the error but don't rollback
                    Log::error('Error updating permintaan barang status: ' . $e->getMessage());
                }
            }

            DB::commit();
            $this->updateSalesOrderStatus($deliveryOrder->sales_order_id);

            return redirect()->route('penjualan.delivery-order.show', $deliveryOrder->id)
                ->with('success', 'Delivery Order telah diselesaikan dan diterima oleh pelanggan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Cancel the delivery order.
     */
    public function batalkanDelivery($id)
    {
        $deliveryOrder = DeliveryOrder::with(['details.produk'])->findOrFail($id);

        // Can only cancel in 'draft' or 'dikirim' status
        if (!in_array($deliveryOrder->status, ['draft', 'dikirim'])) {
            return redirect()->route('penjualan.delivery-order.show', $deliveryOrder->id)
                ->with('error', 'Delivery Order ini tidak dapat dibatalkan karena statusnya ' . $deliveryOrder->status);
        }

        try {
            DB::beginTransaction();

            // If status is 'dikirim', restore stock and update sales order detail
            if ($deliveryOrder->status === 'dikirim') {
                foreach ($deliveryOrder->details as $detail) {
                    // Restore stock
                    $this->tambahStokGudang(
                        $detail->produk_id,
                        $deliveryOrder->gudang_id,
                        $detail->quantity,
                        $deliveryOrder->id
                    );

                    // Update sales order detail quantity_terkirim
                    $salesOrderDetail = SalesOrderDetail::where('sales_order_id', $deliveryOrder->sales_order_id)
                        ->where('produk_id', $detail->produk_id)
                        ->first();

                    if ($salesOrderDetail) {
                        $salesOrderDetail->quantity_terkirim -= $detail->quantity;
                        $salesOrderDetail->save();
                    }
                }

                // Update sales order status
                $this->updateSalesOrderStatus($deliveryOrder->sales_order_id);
            }

            // Update delivery order status
            $deliveryOrder->status = 'dibatalkan';
            $deliveryOrder->save();

            // If this DO is connected to a permintaan barang, update its status
            if ($deliveryOrder->permintaan_barang_id) {
                try {
                    $permintaanBarang = \App\Models\PermintaanBarang::find($deliveryOrder->permintaan_barang_id);
                    if ($permintaanBarang) {
                        // For cancelation, we need to set status to 'menunggu'
                        $oldStatus = $permintaanBarang->status;
                        $permintaanBarang->status = 'menunggu';
                        $permintaanBarang->updated_by = Auth::id();
                        $permintaanBarang->save();

                        // Log the status change
                        $this->logUserAktivitas(
                            'mengubah status permintaan barang',
                            'permintaan_barang',
                            $permintaanBarang->id,
                            "dari {$oldStatus} menjadi menunggu karena Delivery Order #{$deliveryOrder->nomor} dibatalkan"
                        );
                    }
                } catch (\Exception $e) {
                    // Just log the error but don't rollback
                    Log::error('Error updating permintaan barang status: ' . $e->getMessage());
                }
            }

            // Log aktivitas
            $this->logUserAktivitas(
                'Membatalkan Delivery Order',
                'delivery_order',
                $deliveryOrder->id,
                "Membatalkan Delivery Order dengan nomor {$deliveryOrder->nomor}"
            );

            DB::commit();

            return redirect()->route('penjualan.delivery-order.show', $deliveryOrder->id)
                ->with('success', 'Delivery Order telah dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Print the delivery order
     */
    public function print($id)
    {
        // Load delivery order with its relationships
        $deliveryOrder = DeliveryOrder::with([
            'salesOrder',
            'customer',
            'gudang',
            'user',
            'details.produk'
        ])->findOrFail($id);

        // Load the PDF view
        $pdf = Pdf::loadView('penjualan.delivery-order.print', ['deliveryOrder' => $deliveryOrder]);

        $pdf->setPaper('a4', 'portrait');

        // return $pdf->download('SalesOrder-' . $deliveryOrder->nomor . '.pdf');
        return view('penjualan.delivery-order.print', compact('deliveryOrder'));
    }

    /**
     * Print delivery order using PDF template
     */
    public function printTemplate($id)
    {
        try {
            // Load delivery order with its relationships
            $deliveryOrder = DeliveryOrder::with([
                'salesOrder',
                'customer',
                'gudang',
                'user',
                'details.produk.satuan',
                'details.satuan'
            ])->findOrFail($id);

            // Use PDF template service
            $pdfService = new \App\Services\PDFTemplateService();
            $pdf = $pdfService->fillDeliveryOrderTemplate($deliveryOrder);

            // Output PDF
            $filename = 'Surat-Jalan-' . $deliveryOrder->nomor . '.pdf';

            // Log aktivitas
            $this->logUserAktivitas(
                'print template surat jalan',
                'delivery_order',
                $deliveryOrder->id,
                'Print surat jalan menggunakan template PDF: ' . $deliveryOrder->nomor
            );

            return $pdf->Output($filename, 'I'); // 'I' for inline display, 'D' for download

        } catch (\Exception $e) {
            \Log::error('Error printing delivery order template: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mencetak surat jalan: ' . $e->getMessage());
        }
    }

    /**
     * Download delivery order using PDF template
     */
    public function downloadTemplate($id)
    {
        try {
            // Load delivery order with its relationships
            $deliveryOrder = DeliveryOrder::with([
                'salesOrder',
                'customer',
                'gudang',
                'user',
                'details.produk.satuan',
                'details.satuan'
            ])->findOrFail($id);

            // Use PDF template service
            $pdfService = new \App\Services\PDFTemplateService();
            $pdf = $pdfService->fillDeliveryOrderTemplate($deliveryOrder);

            // Output PDF for download
            $filename = 'Surat-Jalan-' . $deliveryOrder->nomor . '.pdf';

            // Log aktivitas
            $this->logUserAktivitas(
                'download template surat jalan',
                'delivery_order',
                $deliveryOrder->id,
                'Download surat jalan menggunakan template PDF: ' . $deliveryOrder->nomor
            );

            return $pdf->Output($filename, 'D'); // 'D' for download

        } catch (\Exception $e) {
            \Log::error('Error downloading delivery order template: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunduh surat jalan: ' . $e->getMessage());
        }
    }

    /**
     * Helper method to get coordinates for template positioning (development use)
     */
    public function getTemplateCoordinates()
    {
        $pdfService = new \App\Services\PDFTemplateService();
        $pdf = $pdfService->getCoordinatesHelper();

        return $pdf->Output('coordinates-helper.pdf', 'I');
    }

    /**
     * Test coordinates for template positioning (development use)
     */
    public function testTemplateCoordinates($id)
    {
        try {
            // Load delivery order with its relationships
            $deliveryOrder = DeliveryOrder::with([
                'salesOrder',
                'customer',
                'gudang',
                'user',
                'details.produk.satuan',
                'details.satuan'
            ])->findOrFail($id);

            // Use PDF template service to test coordinates
            $pdfService = new \App\Services\PDFTemplateService();
            $pdf = $pdfService->testCoordinatesWithTemplate($deliveryOrder);

            // Output PDF for testing
            $filename = 'Test-Coordinates-' . $deliveryOrder->nomor . '.pdf';

            return $pdf->Output($filename, 'I'); // 'I' for inline display

        } catch (\Exception $e) {
            Log::error('Error testing template coordinates: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal testing koordinat: ' . $e->getMessage());
        }
    }

    /**
     * Get template information for debugging/development
     */
    public function getTemplateInfo()
    {
        try {
            $pdfService = new \App\Services\PDFTemplateService();
            $info = $pdfService->getTemplateInfo();

            return response()->json($info);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate delivery order template with custom coordinates
     */
    public function generateWithCustomCoordinates(Request $request, $id)
    {
        try {
            // Load delivery order with its relationships
            $deliveryOrder = DeliveryOrder::with([
                'salesOrder',
                'customer',
                'gudang',
                'user',
                'details.produk.satuan',
                'details.satuan'
            ])->findOrFail($id);

            // Get custom coordinates from request (if any)
            $customCoordinates = $request->input('coordinates', []);

            // Use PDF template service with custom coordinates
            $pdfService = new \App\Services\PDFTemplateService();
            $pdf = $pdfService->generateWithCustomCoordinates($deliveryOrder, $customCoordinates);

            // Output PDF
            $filename = 'Surat-Jalan-Custom-' . $deliveryOrder->nomor . '.pdf';

            return $pdf->Output($filename, 'I'); // 'I' for inline display

        } catch (\Exception $e) {
            Log::error('Error generating custom coordinates PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat PDF dengan koordinat custom: ' . $e->getMessage());
        }
    }

    /**
     * Helper to reduce stock in warehouse.
     */
    private function kurangiStokGudang($produkId, $gudangId, $jumlahKeluar, $referensiId)
    {
        // Find existing stock
        $stok = StokProduk::where('produk_id', $produkId)
            ->where('gudang_id', $gudangId)
            ->first();

        if (!$stok) {
            throw new \Exception("Stok produk tidak ditemukan di gudang yang dipilih");
        }

        // Check if stock is sufficient
        if ($stok->jumlah < $jumlahKeluar) {
            throw new \Exception("Stok produk tidak mencukupi");
        }

        // Note the amount before for history
        $jumlahSebelum = $stok->jumlah;

        // Update stock amount
        $stok->jumlah -= $jumlahKeluar;
        $stok->save();

        // Record stock history
        RiwayatStok::create([
            'stok_id' => $stok->id,
            'produk_id' => $produkId,
            'gudang_id' => $gudangId,
            'user_id' => Auth::id(),
            'jumlah_sebelum' => $jumlahSebelum,
            'jumlah_perubahan' => -$jumlahKeluar,
            'jumlah_setelah' => $stok->jumlah,
            'jenis' => 'keluar',
            'referensi_tipe' => 'delivery_order',
            'referensi_id' => $referensiId,
            'keterangan' => 'Pengurangan dari Delivery Order'
        ]);

        // Check for low stock and send notification if needed
        $notificationService = new \App\Services\NotificationService();
        $notificationService->checkAndNotifyLowStock($produkId, $gudangId);
    }

    /**
     * Helper to add stock to warehouse (used for cancellation).
     */
    private function tambahStokGudang($produkId, $gudangId, $jumlahMasuk, $referensiId)
    {
        // Find or create stock
        $stok = StokProduk::firstOrNew([
            'produk_id' => $produkId,
            'gudang_id' => $gudangId
        ]);

        // Note the amount before for history
        $jumlahSebelum = $stok->jumlah ?? 0;

        // If new, set default values
        if (!$stok->exists) {
            $stok->jumlah = 0;
            $stok->lokasi_rak = '-';
        }

        // Update stock amount
        $stok->jumlah += $jumlahMasuk;
        $stok->save();

        // Record stock history
        RiwayatStok::create([
            'stok_id' => $stok->id,
            'produk_id' => $produkId,
            'gudang_id' => $gudangId,
            'user_id' => Auth::id(),
            'jumlah_sebelum' => $jumlahSebelum,
            'jumlah_perubahan' => $jumlahMasuk,
            'jumlah_setelah' => $stok->jumlah,
            'jenis' => 'masuk',
            'referensi_tipe' => 'delivery_order',
            'referensi_id' => $referensiId,
            'keterangan' => 'Pengembalian dari pembatalan Delivery Order'
        ]);
    }

    /**
     * Helper to update sales order status based on delivery progress.
     */
    private function updateSalesOrderStatus($salesOrderId)
    {
        $salesOrder = SalesOrder::with('details')->findOrFail($salesOrderId);

        // Check if all items are fully delivered
        $allItemsDelivered = true;
        $partiallyDelivered = false;
        $totalItems = 0;
        $deliveredItems = 0;

        foreach ($salesOrder->details as $detail) {
            $totalItems++;

            if ($detail->quantity_terkirim < $detail->quantity) {
                $allItemsDelivered = false;

                if ($detail->quantity_terkirim > 0) {
                    $partiallyDelivered = true;
                }
            }

            // Count fully delivered items
            if ($detail->quantity_terkirim >= $detail->quantity) {
                $deliveredItems++;
            }
        }

        // If some items are fully delivered but others are not, mark as sebagian
        if ($deliveredItems > 0 && $deliveredItems < $totalItems) {
            $partiallyDelivered = true;
        }

        // Update status based on delivery status
        if ($allItemsDelivered) {
            $salesOrder->status_pengiriman = 'dikirim';
        } elseif ($partiallyDelivered) {
            $salesOrder->status_pengiriman = 'sebagian';
        } else {
            $salesOrder->status_pengiriman = 'belum_dikirim';
        }

        $salesOrder->save();

        // Log the status change
        $this->logUserAktivitas(
            'Mengubah status pengiriman Sales Order',
            'sales_order',
            $salesOrder->id,
            "Mengubah status pengiriman Sales Order {$salesOrder->nomor} menjadi {$salesOrder->status_pengiriman} melalui Delivery Order"
        );
    }

    /**
     * Check if all items in a PermintaanBarang have been delivered and update status accordingly
     */
    private function updatePermintaanBarangStatus($permintaanBarangId, $deliveryOrderId)
    {
        $permintaanBarang = \App\Models\PermintaanBarang::with(['details'])->find($permintaanBarangId);

        if (!$permintaanBarang) {
            throw new \Exception("Permintaan Barang tidak ditemukan");
        }

        $deliveryOrder = DeliveryOrder::findOrFail($deliveryOrderId);

        // Check if all items from permintaan barang have been delivered
        $allItemsDelivered = true;
        $totalItemsShipped = 0;

        // Get all delivery orders related to this permintaan barang
        $deliveryOrders = DeliveryOrder::where('permintaan_barang_id', $permintaanBarangId)
            ->whereIn('status', ['dikirim', 'diterima'])
            ->with(['details'])
            ->get();

        // Create a map of product IDs to shipped quantities
        $shippedQuantities = [];
        foreach ($deliveryOrders as $do) {
            foreach ($do->details as $detail) {
                if (!isset($shippedQuantities[$detail->produk_id])) {
                    $shippedQuantities[$detail->produk_id] = 0;
                }
                $shippedQuantities[$detail->produk_id] += $detail->quantity;
            }
        }

        // Check if all items have been fully shipped
        foreach ($permintaanBarang->details as $detail) {
            $shipped = $shippedQuantities[$detail->produk_id] ?? 0;

            if ($shipped < $detail->jumlah) {
                $allItemsDelivered = false;
                break;
            }

            $totalItemsShipped++;
        }

        // If no items have been shipped yet, but we're processing a delivery order,
        // set status to 'diproses'
        if ($totalItemsShipped == 0 && $permintaanBarang->status == 'menunggu') {
            $oldStatus = $permintaanBarang->status;
            $permintaanBarang->status = 'diproses';
            $permintaanBarang->updated_by = Auth::id();
            $permintaanBarang->save();

            // Log the status change
            $this->logUserAktivitas(
                'mengubah status permintaan barang',
                'permintaan_barang',
                $permintaanBarang->id,
                "dari {$oldStatus} menjadi diproses karena Delivery Order #{$deliveryOrder->nomor} diproses"
            );
        }
        // If all items have been shipped, set status to 'selesai'
        elseif ($allItemsDelivered && $totalItemsShipped > 0 && $permintaanBarang->status != 'selesai') {
            $oldStatus = $permintaanBarang->status;
            $permintaanBarang->status = 'selesai';
            $permintaanBarang->updated_by = Auth::id();
            $permintaanBarang->save();

            // Log the status change
            $this->logUserAktivitas(
                'mengubah status permintaan barang',
                'permintaan_barang',
                $permintaanBarang->id,
                "dari {$oldStatus} menjadi selesai karena semua barang telah dikirimkan melalui Delivery Order"
            );
        }
        // If some items have been shipped but not all, set status to 'diproses'
        elseif ($totalItemsShipped > 0 && !$allItemsDelivered && $permintaanBarang->status == 'menunggu') {
            $oldStatus = $permintaanBarang->status;
            $permintaanBarang->status = 'diproses';
            $permintaanBarang->updated_by = Auth::id();
            $permintaanBarang->save();

            // Log the status change
            $this->logUserAktivitas(
                'mengubah status permintaan barang',
                'permintaan_barang',
                $permintaanBarang->id,
                "dari {$oldStatus} menjadi diproses karena sebagian barang telah dikirimkan melalui Delivery Order"
            );
        }
    }

    /**
     * Get sales order data for AJAX request.
     */
    public function getSalesOrderData($id)
    {
        $salesOrder = SalesOrder::with([
            'details.produk',
            'details.satuan',
            'customer'
        ])->findOrFail($id);

        return response()->json([
            'salesOrder' => $salesOrder,
            'customer' => $salesOrder->customer,
            'details' => $salesOrder->details->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'produk_id' => $detail->produk_id,
                    'produk_kode' => $detail->produk->kode,
                    'produk_nama' => $detail->produk->nama,
                    'quantity' => $detail->quantity,
                    'quantity_terkirim' => $detail->quantity_terkirim,
                    'quantity_sisa' => $detail->quantity - $detail->quantity_terkirim,
                    'satuan_id' => $detail->satuan_id,
                    'satuan_nama' => $detail->satuan->nama,
                ];
            })
        ]);
    }

    /**
     * Get delivery order data for AJAX table.
     */
    public function table(Request $request)
    {
        $query = DeliveryOrder::with(['salesOrder', 'customer', 'gudang', 'user']);

        // --- Sorting ---
        $sort_field = $request->input('sort_field', 'tanggal');
        $sort_direction = $request->input('sort_direction', 'desc');

        // Validate sort field to prevent arbitrary column sorting
        $allowedSortFields = ['id', 'nomor', 'tanggal', 'customer_id', 'sales_order_id', 'gudang_id', 'status'];
        if (in_array($sort_field, $allowedSortFields)) {
            $query->orderBy($sort_field, $sort_direction);
        } else {
            $query->orderBy('tanggal', 'desc'); // Default sort
        }

        // --- Filtering ---
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

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('gudang_id')) {
            $query->where('gudang_id', $request->gudang_id);
        }

        // Date filtering - direct date range
        if ($request->filled('date_start')) {
            $query->whereDate('tanggal', '>=', $request->date_start);
        }

        if ($request->filled('date_end')) {
            $query->whereDate('tanggal', '<=', $request->date_end);
        }
        // If periode is set but no explicit dates, calculate the date range
        elseif ($request->filled('periode') && $request->periode !== 'custom' && !$request->filled('date_start')) {
            $today = now();
            $startDate = null;
            $endDate = $today;

            switch ($request->periode) {
                case 'today':
                    $startDate = $today;
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
                    $endDate = $startDate->copy()->endOfWeek();
                    break;
                case 'this_month':
                    $startDate = $today->copy()->startOfMonth();
                    break;
                case 'last_month':
                    $startDate = $today->copy()->subMonth()->startOfMonth();
                    $endDate = $startDate->copy()->endOfMonth();
                    break;
                case 'this_year':
                    $startDate = $today->copy()->startOfYear();
                    break;
                case 'last_year':
                    $startDate = $today->copy()->subYear()->startOfYear();
                    $endDate = $startDate->copy()->endOfYear();
                    break;
            }

            if ($startDate && $endDate) {
                $query->whereDate('tanggal', '>=', $startDate->format('Y-m-d'))
                    ->whereDate('tanggal', '<=', $endDate->format('Y-m-d'));
            }
        }

        try {
            $deliveryOrders = $query->paginate(10)->withQueryString();

            return response()->json([
                'table_html' => view('penjualan.delivery-order._table', compact('deliveryOrders', 'sort_field', 'sort_direction'))->render(),
                'pagination_html' => view('penjualan.delivery-order._pagination', ['paginator' => $deliveryOrders])->render(),
                'sort_field' => $sort_field,
                'sort_direction' => $sort_direction
            ]);
        } catch (\Exception $e) {
            Log::error('Error in delivery order table: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            $userFriendlyMessage = 'Terjadi kesalahan saat memuat data. Silakan coba lagi.';

            // Add more context for certain types of errors
            if (strpos($e->getMessage(), 'SQLSTATE') !== false) {
                $userFriendlyMessage = 'Terjadi kesalahan pada database. Hubungi admin untuk bantuan.';
            }

            if (app()->environment('local', 'development', 'staging')) {
                $userFriendlyMessage .= ' Detail: ' . $e->getMessage();
            }

            return response()->json([
                'table_html' => '<tr><td colspan="8" class="px-6 py-4 text-center text-red-500">Terjadi kesalahan saat memuat data. Silakan muat ulang halaman ini.</td></tr>',
                'pagination_html' => '',
                'error' => $userFriendlyMessage
            ], 500);
        }
    }

    /**
     * Get stock information for products in a warehouse.
     */
    public function getStockInformation(Request $request)
    {
        $request->validate([
            'gudang_id' => 'required|exists:gudang,id',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:produk,id',
        ]);

        $gudangId = $request->input('gudang_id');
        $productIds = $request->input('product_ids');

        // Fetch stock for all requested products in the specified warehouse
        $stocks = StokProduk::where('gudang_id', $gudangId)
            ->whereIn('produk_id', $productIds)
            ->pluck('jumlah', 'produk_id')
            ->toArray();

        // Ensure all requested product IDs have an entry in the response
        $result = [];
        foreach ($productIds as $productId) {
            $result[$productId] = $stocks[$productId] ?? 0;
        }

        return response()->json([
            'success' => true,
            'stocks' => $result
        ]);
    }
}