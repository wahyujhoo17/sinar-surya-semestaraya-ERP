<?php

namespace App\Http\Controllers\Pembelian;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\PurchaseRequest;
use App\Models\SupplierProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchasingOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validStatuses = [
            'draft',
            'diproses',
            'dikirim',
            'selesai',
            'dibatalkan'
        ];

        // Ambil status dari request, default 'draft' agar konsisten dengan frontend
        $status = $request->input('status', 'draft');

        $query = PurchaseOrder::with(['supplier', 'user']);

        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }

        // Filter pencarian
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhere('catatan', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        // Filter supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->input('supplier_id'));
        }

        // Filter status pembayaran (parameter harus sama dengan frontend: payment_status)
        if ($request->filled('payment_status')) {
            $query->where('status_pembayaran', $request->input('payment_status'));
        }

        // Filter tanggal
        if ($request->filled('date_filter')) {
            $dateFilter = $request->input('date_filter');
            if ($dateFilter === 'today') {
                $query->whereDate('tanggal', now()->toDateString());
            } elseif ($dateFilter === 'this_week') {
                $query->whereBetween('tanggal', [
                    now()->startOfWeek()->toDateString(),
                    now()->endOfWeek()->toDateString()
                ]);
            } elseif ($dateFilter === 'this_month') {
                $query->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year);
            } elseif ($dateFilter === 'range') {
                if ($request->filled('date_start')) {
                    $query->whereDate('tanggal', '>=', $request->input('date_start'));
                }
                if ($request->filled('date_end')) {
                    $query->whereDate('tanggal', '<=', $request->input('date_end'));
                }
            }
        }

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $purchaseOrders = $query->paginate(10)->withQueryString();
        $suppliers = Supplier::orderBy('nama')->get();

        // AJAX response for table (like permintaan pembelian)
        if ($request->ajax()) {
            $table_html = view('pembelian.purchase_order._table', [
                'purchaseOrders' => $purchaseOrders
            ])->render();
            $pagination_html = $purchaseOrders->links('vendor.pagination.tailwind-custom')->toHtml();

            return response()->json([
                'table_html' => $table_html,
                'pagination_html' => $pagination_html,
            ]);
        }

        return view('pembelian.purchase_order.index', [
            'purchaseOrders' => $purchaseOrders,
            'validStatuses' => $validStatuses,
            'suppliers' => $suppliers,
            'currentStatus' => $status,
            'search' => $request->input('search', ''),
            'dateFilter' => $request->input('date_filter', ''),
            'dateStart' => $request->input('date_start', ''),
            'dateEnd' => $request->input('date_end', ''),
            'paymentStatus' => $request->input('payment_status', ''),
            'supplierId' => $request->input('supplier_id', ''),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('nama')->get();
        $purchaseRequests = PurchaseRequest::where('status', 'diajukan')->get();
        $produks = Produk::orderBy('nama')->get();
        $satuans = Satuan::orderBy('nama')->get();

        // Generate PO number
        $nomorPO = $this->generatePONumber();

        return view('pembelian.purchase_order.create', compact('suppliers', 'purchaseRequests', 'produks', 'satuans', 'nomorPO'));
    }

    /**
     * Endpoint AJAX: Get PR detail items for PO create
     */
    public function getPurchaseRequestItems(Request $request)
    {
        $prId = $request->input('pr_id');
        $pr = PurchaseRequest::with(['details.produk'])->findOrFail($prId);
        $items = $pr->details->map(function ($detail) {
            return [
                'produk_id' => $detail->produk_id,
                'nama_item' => $detail->produk->nama,
                'deskripsi' => $detail->deskripsi ?? $detail->produk->nama,
                'quantity' => $detail->quantity,
                'satuan_id' => $detail->satuan_id,
                'harga' => $detail->produk->harga_beli,
                'diskon_persen' => 0,
                'diskon_nominal' => 0,
                'subtotal' => $detail->quantity * $detail->produk->harga_beli,
            ];
        });
        return response()->json(['items' => $items]);
    }

    /**
     * Endpoint AJAX: Get produk list for a supplier (prioritize owned products)
     */
    public function getSupplierProduk(Request $request)
    {
        $supplierId = $request->input('supplier_id');
        if (!$supplierId) {
            return response()->json(['produks' => []]);
        }
        // Produk yang dimiliki supplier
        $ownedProdukIds = DB::table('supplier_produks')
            ->where('supplier_id', $supplierId)
            ->pluck('produk_id')
            ->toArray();
        $ownedProduks = Produk::whereIn('id', $ownedProdukIds)->orderBy('nama')->get();
        $otherProduks = Produk::whereNotIn('id', $ownedProdukIds)->orderBy('nama')->get();
        $produks = $ownedProduks->map(function ($p) {
            return [
                'id' => $p->id,
                'kode' => $p->kode,
                'nama' => $p->nama,
                'ukuran' => $p->ukuran,
                'satuan_id' => $p->satuan_id,
                'harga_beli' => $p->harga_beli,
                'owned' => true
            ];
        })->concat($otherProduks->map(function ($p) {
            return [
                'id' => $p->id,
                'kode' => $p->kode,
                'nama' => $p->nama,
                'ukuran' => $p->ukuran,
                'satuan_id' => $p->satuan_id,
                'harga_beli' => $p->harga_beli,
                'owned' => false
            ];
        }))->values();
        return response()->json(['produks' => $produks]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'nomor' => 'required|string|max:50|unique:purchase_order',
            'tanggal' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
            'pr_id' => 'nullable|exists:purchase_request,id',
            'status' => 'required|string|in:draft,submit,approved',
            'tanggal_pengiriman' => 'nullable|date',
            'alamat_pengiriman' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'syarat_ketentuan' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0',
            'diskon_persen' => 'nullable|numeric|min:0|max:100',
            'diskon_nominal' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0',
            'include_ppn' => 'nullable|boolean',
            'total' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.nama_item' => 'nullable|string',
            'items.*.deskripsi' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.satuan_id' => 'required|exists:satuan,id',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.diskon_persen' => 'nullable|numeric|min:0|max:100',
            'items.*.diskon_nominal' => 'nullable|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
        ]);

        // Hitung ulang subtotal dari data mentah item
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $qty = floatval($item['quantity']);
            $harga = floatval($item['harga']);
            $diskon_item = floatval($item['diskon_nominal'] ?? 0);
            $item_subtotal = max(0, ($qty * $harga) - $diskon_item);
            $subtotal += $item_subtotal;
        }
        $diskon_nominal = $validated['diskon_nominal'] ?? 0;
        $diskon_nominal = min($diskon_nominal, $subtotal);
        $after_discount = max(0, $subtotal - $diskon_nominal);

        // Cek apakah PPN diaktifkan (bisa dari request atau default true)
        $ppn_persen = 11; // default 11%
        $includePPN = $request->has('include_ppn') ? (bool)$request->input('include_ppn') : true;
        $ppn_nominal = $includePPN ? round($after_discount * ($ppn_persen / 100), 2) : 0;
        $total = $after_discount + $ppn_nominal;

        try {
            DB::beginTransaction();

            // Create Purchase Order
            $purchaseOrder = PurchaseOrder::create([
                'nomor' => $validated['nomor'],
                'tanggal' => $validated['tanggal'],
                'supplier_id' => $validated['supplier_id'],
                'pr_id' => $validated['pr_id'] ?? null,
                'user_id' => Auth::id(),
                'tanggal_pengiriman' => $validated['tanggal_pengiriman'] ?? null,
                'alamat_pengiriman' => $validated['alamat_pengiriman'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
                'syarat_ketentuan' => $validated['syarat_ketentuan'] ?? null,
                'subtotal' => $subtotal,
                'diskon_persen' => $validated['diskon_persen'] ?? 0,
                'diskon_nominal' => $diskon_nominal,
                'ppn' => $includePPN ? $ppn_persen : 0, // simpan persentase PPN
                'total' => $total,
                'status' => $validated['status'],
                'status_pembayaran' => 'belum_bayar',
                'status_penerimaan' => 'belum_diterima',
            ]);

            // Simpan detail dengan subtotal yang dihitung ulang
            foreach ($validated['items'] as $item) {
                $qty = floatval($item['quantity']);
                $harga = floatval($item['harga']);
                $diskon_item = floatval($item['diskon_nominal'] ?? 0);
                $item_subtotal = max(0, ($qty * $harga) - $diskon_item);

                PurchaseOrderDetail::create([
                    'po_id' => $purchaseOrder->id,
                    'produk_id' => $item['produk_id'],
                    'nama_item' => $item['nama_item'] ?? null,
                    'deskripsi' => $item['deskripsi'] ?? null,
                    'quantity' => $qty,
                    'quantity_diterima' => 0,
                    'satuan_id' => $item['satuan_id'],
                    'harga' => $harga,
                    'diskon_persen' => $item['diskon_persen'] ?? 0,
                    'diskon_nominal' => $diskon_item,
                    'subtotal' => $item_subtotal,
                ]);

                // Tambahkan ke supplier_produks jika belum ada
                $exists = SupplierProduk::where('supplier_id', $purchaseOrder->supplier_id)
                    ->where('produk_id', $item['produk_id'])
                    ->exists();
                if (!$exists) {
                    SupplierProduk::create([
                        'supplier_id' => $purchaseOrder->supplier_id,
                        'produk_id' => $item['produk_id'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('pembelian.purchasing-order.show', $purchaseOrder->id)
                ->with('success', 'Purchase Order berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'user', 'purchaseRequest', 'details.produk', 'details.satuan'])
            ->findOrFail($id);

        // Status information for display
        $statusBgColor = $this->getStatusBackgroundColor($purchaseOrder->status);
        $statusTextColor = $this->getStatusTextColor($purchaseOrder->status);

        return view('pembelian.purchase_order.show', compact('purchaseOrder', 'statusBgColor', 'statusTextColor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'user', 'purchaseRequest', 'details.produk', 'details.satuan'])
            ->findOrFail($id);

        // Only allow editing of draft purchase orders
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->route('pembelian.purchasing-order.show', $purchaseOrder->id)
                ->with('error', 'Hanya Purchase Order dengan status draft yang dapat diedit.');
        }

        $suppliers = Supplier::orderBy('nama')->get();
        $purchaseRequests = PurchaseRequest::where('status', 'disetujui')->get();
        $produks = Produk::orderBy('nama')->get();
        $satuans = Satuan::orderBy('nama')->get();

        return view('pembelian.purchase_order.edit', compact('purchaseOrder', 'suppliers', 'purchaseRequests', 'produks', 'satuans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        // Only allow updating of draft purchase orders
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->route('pembelian.purchasing-order.show', $purchaseOrder->id)
                ->with('error', 'Hanya Purchase Order dengan status draft yang dapat diubah.');
        }

        // Validate request
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
            'pr_id' => 'nullable|exists:purchase_request,id',
            'status' => 'required|string|in:draft,submit,approved',
            'tanggal_pengiriman' => 'nullable|date',
            'alamat_pengiriman' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'syarat_ketentuan' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0',
            'diskon_persen' => 'nullable|numeric|min:0|max:100',
            'diskon_nominal' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0',
            'include_ppn' => 'nullable|boolean',
            'total' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:purchase_order_detail,id',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.nama_item' => 'nullable|string',
            'items.*.deskripsi' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.satuan_id' => 'required|exists:satuan,id',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.diskon_persen' => 'nullable|numeric|min:0|max:100',
            'items.*.diskon_nominal' => 'nullable|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
        ]);

        // Hitung ulang subtotal dari data mentah item
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $qty = floatval($item['quantity']);
            $harga = floatval($item['harga']);
            $diskon_item = floatval($item['diskon_nominal'] ?? 0);
            $item_subtotal = max(0, ($qty * $harga) - $diskon_item);
            $subtotal += $item_subtotal;
        }
        $diskon_nominal = $validated['diskon_nominal'] ?? 0;
        $diskon_nominal = min($diskon_nominal, $subtotal);
        $after_discount = max(0, $subtotal - $diskon_nominal);
        $ppn_persen = 11; // default 11%
        $includePPN = $request->has('include_ppn') ? (bool)$request->input('include_ppn') : true;
        $ppn_nominal = $includePPN ? round($after_discount * ($ppn_persen / 100), 2) : 0;
        $total = $after_discount + $ppn_nominal;

        try {
            DB::beginTransaction();

            // Update Purchase Order
            $purchaseOrder->update([
                'tanggal' => $validated['tanggal'],
                'supplier_id' => $validated['supplier_id'],
                'pr_id' => $validated['pr_id'] ?? null,
                'tanggal_pengiriman' => $validated['tanggal_pengiriman'] ?? null,
                'alamat_pengiriman' => $validated['alamat_pengiriman'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
                'syarat_ketentuan' => $validated['syarat_ketentuan'] ?? null,
                'subtotal' => $subtotal,
                'diskon_persen' => $validated['diskon_persen'] ?? 0,
                'diskon_nominal' => $diskon_nominal,
                'ppn' => $includePPN ? $ppn_persen : 0, // simpan persentase PPN
                'total' => $total,
                'status' => $validated['status'],
            ]);

            // Delete existing details that aren't in the request
            $existingIds = collect($validated['items'])
                ->pluck('id')
                ->filter()
                ->toArray();

            $purchaseOrder->details()
                ->whereNotIn('id', $existingIds)
                ->delete();

            // Update or create details dengan subtotal yang dihitung ulang
            foreach ($validated['items'] as $item) {
                $qty = floatval($item['quantity']);
                $harga = floatval($item['harga']);
                $diskon_item = floatval($item['diskon_nominal'] ?? 0);
                $item_subtotal = max(0, ($qty * $harga) - $diskon_item);

                if (isset($item['id']) && $item['id']) {
                    // Update existing detail
                    PurchaseOrderDetail::where('id', $item['id'])
                        ->update([
                            'produk_id' => $item['produk_id'],
                            'nama_item' => $item['nama_item'] ?? null,
                            'deskripsi' => $item['deskripsi'] ?? null,
                            'quantity' => $qty,
                            'satuan_id' => $item['satuan_id'],
                            'harga' => $harga,
                            'diskon_persen' => $item['diskon_persen'] ?? 0,
                            'diskon_nominal' => $diskon_item,
                            'subtotal' => $item_subtotal,
                        ]);
                } else {
                    // Create new detail
                    PurchaseOrderDetail::create([
                        'po_id' => $purchaseOrder->id,
                        'produk_id' => $item['produk_id'],
                        'nama_item' => $item['nama_item'] ?? null,
                        'deskripsi' => $item['deskripsi'] ?? null,
                        'quantity' => $qty,
                        'quantity_diterima' => 0,
                        'satuan_id' => $item['satuan_id'],
                        'harga' => $harga,
                        'diskon_persen' => $item['diskon_persen'] ?? 0,
                        'diskon_nominal' => $diskon_item,
                        'subtotal' => $item_subtotal,
                    ]);

                    // Tambahkan ke supplier_produks jika belum ada
                    $exists = SupplierProduk::where('supplier_id', $purchaseOrder->supplier_id)
                        ->where('produk_id', $item['produk_id'])
                        ->exists();
                    if (!$exists) {
                        SupplierProduk::create([
                            'supplier_id' => $purchaseOrder->supplier_id,
                            'produk_id' => $item['produk_id'],
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('pembelian.purchasing-order.show', $purchaseOrder->id)
                ->with('success', 'Purchase Order berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        // Only allow deletion of draft purchase orders
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->route('pembelian.purchasing-order.index')
                ->with('error', 'Hanya Purchase Order dengan status draft yang dapat dihapus.');
        }

        try {
            DB::beginTransaction();

            // Delete all details first
            $purchaseOrder->details()->delete();

            // Delete the purchase order
            $purchaseOrder->delete();

            DB::commit();

            return redirect()->route('pembelian.purchasing-order.index')
                ->with('success', 'Purchase Order berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Change the status of a purchase order
     */
    public function changeStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:draft,diproses,dikirim,selesai,dibatalkan',
        ]);

        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->status = $request->status;
        $purchaseOrder->save();

        return redirect()->route('pembelian.purchasing-order.show', $purchaseOrder->id)
            ->with('success', 'Status Purchase Order berhasil diubah.');
    }

    /**
     * Generate a new PO number
     */
    private function generatePONumber()
    {
        $today = now();
        $prefix = 'PO-' . $today->format('Ymd');

        // Find last number with today's prefix
        $lastOrder = PurchaseOrder::where('nomor', 'like', $prefix . '%')
            ->orderBy('nomor', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->nomor, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get background color for status
     */
    private function getStatusBackgroundColor($status)
    {
        switch ($status) {
            case 'draft':
                return 'bg-gray-100 dark:bg-gray-700';
            case 'diproses':
                return 'bg-blue-100 dark:bg-blue-900/20';
            case 'dikirim':
                return 'bg-amber-100 dark:bg-amber-900/20';
            case 'selesai':
                return 'bg-emerald-100 dark:bg-emerald-900/20';
            case 'dibatalkan':
                return 'bg-red-100 dark:bg-red-900/20';
            default:
                return 'bg-gray-100 dark:bg-gray-700';
        }
    }

    /**
     * Get text color for status
     */
    private function getStatusTextColor($status)
    {
        switch ($status) {
            case 'draft':
                return 'text-gray-700 dark:text-gray-300';
            case 'diproses':
                return 'text-blue-700 dark:text-blue-300';
            case 'dikirim':
                return 'text-amber-700 dark:text-amber-300';
            case 'selesai':
                return 'text-emerald-700 dark:text-emerald-300';
            case 'dibatalkan':
                return 'text-red-700 dark:text-red-300';
            default:
                return 'text-gray-700 dark:text-gray-300';
        }
    }
}
