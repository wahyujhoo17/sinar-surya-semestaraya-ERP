<?php

namespace App\Http\Controllers\Pembelian;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\SupplierProduk;
use App\Services\BOMCostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LogAktivitas;
use App\Services\NotificationService;
use App\Traits\HasPDFQRCode;

class PurchasingOrderController extends Controller
{
    use HasPDFQRCode;

    protected $bomCostService;

    public function __construct(BOMCostService $bomCostService)
    {
        $this->bomCostService = $bomCostService;
        $this->middleware('permission:purchase_order.view')->only(['printPdf', 'exportPdf']);
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
     * Helper untuk update harga beli produk dengan harga terbaru
     * Hanya dipanggil ketika status PO = selesai
     */
    private function updateHargaBeliTerbaru($produk_id, $harga_beli_baru, $quantity_baru = 1, $po_id_saat_ini = null)
    {
        $produk = Produk::find($produk_id);
        if (!$produk) return;

        // Simpan harga lama untuk logging
        $harga_lama = $produk->harga_beli ?? 0;

        // Update langsung dengan harga beli terbaru
        $produk->update(['harga_beli' => $harga_beli_baru]);

        // Log perubahan harga beli
        $this->logUserAktivitas(
            'Update Harga Beli Terbaru',
            'Purchase Order',
            $produk->id,
            "Produk: {$produk->nama_produk}, Harga Lama: " . number_format($harga_lama, 2) .
                ", Harga Baru: " . number_format($harga_beli_baru, 2)
        );
    }

    /**
     * Static method untuk dipanggil dari controller lain
     * Mengupdate harga beli terbaru untuk semua produk dalam PO yang selesai
     */
    public static function updateHargaBeliTerbaruFromExternalController($purchaseOrderId)
    {
        $po = PurchaseOrder::with('details')->find($purchaseOrderId);
        if (!$po || $po->status !== 'selesai') {
            return false;
        }

        $bomCostService = app(BOMCostService::class);
        $instance = new static($bomCostService);

        foreach ($po->details as $detail) {
            if ($detail->produk_id) {
                $instance->updateHargaBeliTerbaru(
                    $detail->produk_id,
                    $detail->harga,
                    $detail->quantity,
                    $po->id
                );

                // Update produk lain yang menggunakan produk ini sebagai komponen BOM
                $instance->bomCostService->updateDependentProducts($detail->produk_id);
            }
        }

        return true;
    }

    /**
     * Helper untuk mengecek apakah semua item dalam PR sudah terpenuhi oleh PO-PO yang ada
     * @param int $prId - ID Purchase Request
     * @return bool - true jika semua item sudah terpenuhi
     */
    private function isPurchaseRequestFullyFulfilled($prId)
    {
        $pr = PurchaseRequest::with('details')->find($prId);
        if (!$pr) return false;

        foreach ($pr->details as $prDetail) {
            // Hitung total quantity yang sudah dipenuhi oleh semua PO aktif (tidak dibatalkan)
            $totalFulfilledQty = PurchaseOrderDetail::whereHas('purchaseOrder', function ($query) use ($prId) {
                $query->where('pr_id', $prId)
                    ->where('status', '!=', 'dibatalkan');
            })
                ->where('produk_id', $prDetail->produk_id)
                ->sum('quantity');

            // Jika masih ada item yang belum terpenuhi sepenuhnya
            if ($totalFulfilledQty < $prDetail->quantity) {
                return false;
            }
        }

        return true;
    }

    /**
     * Helper untuk mengupdate status PR berdasarkan kelengkapan item
     * @param int $prId - ID Purchase Request
     */
    private function updatePurchaseRequestStatus($prId)
    {
        $pr = PurchaseRequest::find($prId);
        if (!$pr || $pr->status !== 'disetujui') {
            return;
        }

        if ($this->isPurchaseRequestFullyFulfilled($prId)) {
            $pr->update(['status' => 'selesai']);
            $this->logUserAktivitas(
                'update_status_pr',
                'purchase_request',
                $pr->id,
                'Status PR diubah menjadi selesai karena semua item sudah terpenuhi oleh PO'
            );
        }
    }

    /**
     * Helper untuk mengembalikan status PR dari 'selesai' ke 'disetujui' jika item belum terpenuhi sepenuhnya
     * @param int $prId - ID Purchase Request
     */
    private function revertPurchaseRequestStatus($prId)
    {
        $pr = PurchaseRequest::find($prId);
        if (!$pr || $pr->status !== 'selesai') {
            return;
        }

        // Jika setelah PO dibatalkan/dihapus, ternyata PR belum fully fulfilled lagi
        if (!$this->isPurchaseRequestFullyFulfilled($prId)) {
            $pr->update(['status' => 'disetujui']);
            $this->logUserAktivitas(
                'revert_status_pr',
                'purchase_request',
                $pr->id,
                'Status PR dikembalikan ke disetujui karena item belum terpenuhi sepenuhnya'
            );
        }
    }

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

        // Calculate counts for each status before applying filters for the main query
        $statusCounts = [];
        foreach ($validStatuses as $validStatus) {
            $statusCounts[$validStatus] = PurchaseOrder::where('status', $validStatus)->count();
        }

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
            'statusCounts' => $statusCounts, // Pass the counts to the view
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

        // Ambil PR yang bisa digunakan untuk membuat PO:
        // 1. Status 'disetujui' (belum ada PO sama sekali atau semua PO dibatalkan)
        // 2. Status 'selesai' tapi belum fully fulfilled (masih ada item yang bisa dibuat PO)
        $purchaseRequests = PurchaseRequest::where(function ($query) {
            // PR dengan status disetujui yang belum ada PO aktif
            $query->where('status', 'disetujui')
                ->whereDoesntHave('purchaseOrders', function ($q) {
                    $q->where('status', '!=', 'dibatalkan');
                });
        })
            ->orWhere(function ($query) {
                // PR dengan status selesai tapi belum fully fulfilled
                $query->where('status', 'selesai')
                    ->whereHas('purchaseOrders', function ($q) {
                        $q->where('status', '!=', 'dibatalkan');
                    });
            })
            ->get()
            ->filter(function ($pr) {
                // Filter manual: untuk PR status selesai, cek apakah masih bisa dibuat PO
                if ($pr->status === 'selesai') {
                    return !$this->isPurchaseRequestFullyFulfilled($pr->id);
                }
                return true;
            });

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
            'ongkos_kirim' => 'nullable|numeric|min:0',
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

        // Cek apakah PPN diaktifkan dari toggle frontend
        $ppn_persen = setting('tax_percentage', 11); // default from settings
        $includePPN = (bool)$request->input('include_ppn', false); // default false jika tidak ada
        $ppn_nominal = $includePPN ? round($after_discount * ($ppn_persen / 100), 2) : 0;

        // Ongkos kirim
        $ongkos_kirim = floatval($request->input('ongkos_kirim', 0));

        // Total = Subtotal setelah diskon + PPN + Ongkos kirim
        $total = $after_discount + $ppn_nominal + $ongkos_kirim;

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
                'ongkos_kirim' => $ongkos_kirim,
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
                    'harga' => $harga, // Ini adalah harga beli
                    'diskon_persen' => $item['diskon_persen'] ?? 0,
                    'diskon_nominal' => $diskon_item,
                    'subtotal' => $item_subtotal,
                ]);

                // TIDAK update harga_beli produk saat draft/approval
                // Harga beli akan diupdate saat status = selesai menggunakan rata-rata

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

            // Update status PR jika PO dibuat dari PR - cek kelengkapan item
            if ($purchaseOrder->pr_id) {
                $this->updatePurchaseRequestStatus($purchaseOrder->pr_id);
            }

            DB::commit();

            $this->logUserAktivitas('tambah', 'purchase_order', $purchaseOrder->id, 'Membuat Purchase Order: ' . $purchaseOrder->nomor);

            // Send notification to managers about new purchase order
            $notificationService = app(NotificationService::class);
            $notificationService->notifyPurchaseOrderCreated($purchaseOrder, Auth::user());

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
        $purchaseOrder = PurchaseOrder::with([
            'supplier',
            'user',
            'purchaseRequest',
            'details.produk',
            'details.satuan',
            'retur.details.produk',
            'retur.details.satuan'
        ])
            ->findOrFail($id);

        // Status information for display
        $statusBgColor = $this->getStatusBackgroundColor($purchaseOrder->status);
        $statusTextColor = $this->getStatusTextColor($purchaseOrder->status);

        // Ambil log aktivitas terkait PO ini
        $logAktivitas = LogAktivitas::with('user')
            ->where('modul', 'purchase_order')
            ->where('data_id', $purchaseOrder->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('pembelian.purchase_order.show', compact('purchaseOrder', 'statusBgColor', 'statusTextColor', 'logAktivitas'));
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

        // Ambil PR yang bisa digunakan untuk edit PO:
        // 1. Status 'disetujui' yang belum digunakan PO aktif lain (kecuali PO ini)
        // 2. Status 'selesai' yang belum fully fulfilled (kecuali PO ini)
        // 3. PR yang sedang digunakan PO ini (selalu bisa dipilih)
        $purchaseRequests = PurchaseRequest::where(function ($query) use ($purchaseOrder) {
            // PR dengan status disetujui yang belum ada PO aktif lain
            $query->where('status', 'disetujui')
                ->where(function ($q) use ($purchaseOrder) {
                    $q->whereDoesntHave('purchaseOrders', function ($subq) use ($purchaseOrder) {
                        $subq->where('status', '!=', 'dibatalkan')
                            ->where('id', '!=', $purchaseOrder->id);
                    });
                });
        })
            ->orWhere(function ($query) use ($purchaseOrder) {
                // PR dengan status selesai tapi belum fully fulfilled
                $query->where('status', 'selesai')
                    ->whereHas('purchaseOrders', function ($q) use ($purchaseOrder) {
                        $q->where('status', '!=', 'dibatalkan');
                    });
            })
            ->orWhere('id', $purchaseOrder->pr_id) // PR yang sedang digunakan PO ini
            ->distinct()
            ->get()
            ->filter(function ($pr) use ($purchaseOrder) {
                // Filter manual: untuk PR status selesai, cek apakah masih bisa dibuat PO
                if ($pr->status === 'selesai' && $pr->id !== $purchaseOrder->pr_id) {
                    return !$this->isPurchaseRequestFullyFulfilled($pr->id);
                }
                return true;
            });

        $produks = Produk::orderBy('nama')->get();
        $satuans = Satuan::orderBy('nama')->get();

        // $this->logUserAktivitas('akses', 'purchase_order', $purchaseOrder->id, 'Akses edit Purchase Order: ' . $purchaseOrder->nomor);

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
            'ongkos_kirim' => 'nullable|numeric|min:0',
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
        $ppn_persen = setting('tax_percentage', 11); // default from settings
        $includePPN = (bool)$request->input('include_ppn', false); // default false jika tidak ada
        $ppn_nominal = $includePPN ? round($after_discount * ($ppn_persen / 100), 2) : 0;

        // Ongkos kirim
        $ongkos_kirim = floatval($request->input('ongkos_kirim', 0));

        // Total = Subtotal setelah diskon + PPN + Ongkos kirim
        $total = $after_discount + $ppn_nominal + $ongkos_kirim;

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
                'ongkos_kirim' => $ongkos_kirim,
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
                            'harga' => $harga, // Ini adalah harga beli
                            'diskon_persen' => $item['diskon_persen'] ?? 0,
                            'diskon_nominal' => $diskon_item,
                            'subtotal' => $item_subtotal,
                        ]);

                    // TIDAK update harga_beli produk saat edit (kecuali status = selesai)
                    // Harga beli akan diupdate saat status = selesai menggunakan rata-rata
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
                        'harga' => $harga, // Ini adalah harga beli
                        'diskon_persen' => $item['diskon_persen'] ?? 0,
                        'diskon_nominal' => $diskon_item,
                        'subtotal' => $item_subtotal,
                    ]);

                    // TIDAK update harga_beli produk saat edit (kecuali status = selesai)
                    // Harga beli akan diupdate saat status = selesai menggunakan rata-rata

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

            // Jika status berubah menjadi "selesai", update harga beli terbaru
            if ($validated['status'] === 'selesai') {
                foreach ($purchaseOrder->details as $detail) {
                    if ($detail->produk_id) {
                        $this->updateHargaBeliTerbaru(
                            $detail->produk_id,
                            $detail->harga,
                            $detail->quantity,
                            $purchaseOrder->id
                        );
                    }
                }
            }

            // Update status PR jika ada perubahan item - cek kelengkapan item
            if ($purchaseOrder->pr_id) {
                $this->updatePurchaseRequestStatus($purchaseOrder->pr_id);
            }

            DB::commit();

            $this->logUserAktivitas('ubah', 'purchase_order', $purchaseOrder->id, 'Mengubah Purchase Order: ' . $purchaseOrder->nomor);

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

            // Jika PO dibuat dari PR, cek ulang status PR setelah PO dihapus
            if ($purchaseOrder->pr_id) {
                $this->revertPurchaseRequestStatus($purchaseOrder->pr_id);
            }

            // Delete all details first
            $purchaseOrder->details()->delete();

            // Delete the purchase order
            $purchaseOrder->delete();

            DB::commit();

            $this->logUserAktivitas('hapus', 'purchase_order', $purchaseOrder->id, 'Menghapus Purchase Order: ' . $purchaseOrder->nomor);

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
        $newStatus = $request->status;

        // Special validation for changing to "selesai" status
        if ($newStatus === 'selesai') {
            // Check if payment is complete
            if ($purchaseOrder->status_pembayaran !== 'lunas') {
                return redirect()->route('pembelian.purchasing-order.show', $purchaseOrder->id)
                    ->with('error', 'Purchase Order tidak dapat diselesaikan. Pembayaran belum lunas.');
            }

            // Check if items are fully received
            if ($purchaseOrder->status_penerimaan !== 'diterima') {
                return redirect()->route('pembelian.purchasing-order.show', $purchaseOrder->id)
                    ->with('error', 'Purchase Order tidak dapat diselesaikan. Barang belum diterima sepenuhnya.');
            }
        }

        // Store previous status for history
        $oldStatus = $purchaseOrder->status;

        // Update the status
        $purchaseOrder->status = $newStatus;
        $purchaseOrder->save();

        // Jika status berubah menjadi "selesai", update harga beli terbaru
        if ($newStatus === 'selesai') {
            foreach ($purchaseOrder->details as $detail) {
                if ($detail->produk_id) {
                    $this->updateHargaBeliTerbaru(
                        $detail->produk_id,
                        $detail->harga,
                        $detail->quantity,
                        $purchaseOrder->id
                    );
                }
            }
        }

        // Jika status berubah menjadi "dibatalkan", cek ulang status PR
        if ($newStatus === 'dibatalkan' && $purchaseOrder->pr_id) {
            $this->revertPurchaseRequestStatus($purchaseOrder->pr_id);
        }

        $this->logUserAktivitas('ubah_status', 'purchase_order', $purchaseOrder->id, 'Ubah status dari ' . $oldStatus . ' ke ' . $newStatus . ' untuk PO: ' . $purchaseOrder->nomor);

        return redirect()->route('pembelian.purchasing-order.show', $purchaseOrder->id)
            ->with('success', 'Status Purchase Order berhasil diubah.');
    }

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

    /**
     * Export Purchase Order as PDF
     */
    public function exportPdf($id)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'user', 'purchaseRequest', 'details.produk', 'details.satuan'])
            ->findOrFail($id);

        // Calculate total
        $total = 0;
        foreach ($purchaseOrder->details as $detail) {
            $total += $detail->quantity * $detail->harga;
        }

        // Get approval/processing information from LogAktivitas
        $createdBy = $purchaseOrder->user;
        $processedBy = null;
        $processedAt = null;
        $isProcessed = in_array($purchaseOrder->status, ['diproses', 'dikirim', 'selesai']);

        if ($isProcessed) {
            // Find the log entry for processing this purchase order
            $processLog = LogAktivitas::with('user')
                ->where('modul', 'purchase_order')
                ->where('data_id', $purchaseOrder->id)
                ->where('aktivitas', 'ubah_status')
                ->where('detail', 'like', '%ke diproses%')
                ->latest()
                ->first();

            if ($processLog) {
                $processedBy = $processLog->user;
                $processedAt = $processLog->created_at;
            }
        }

        // Get QR codes for PDF
        $qrCodes = $this->getPDFQRCodeData(
            'purchase_order',
            $purchaseOrder->nomor,
            $createdBy,
            $processedBy,
            [
                'supplier' => $purchaseOrder->supplier->nama ?? '',
                'total_items' => $purchaseOrder->details->count(),
                'total_amount' => $total,
                'status' => $purchaseOrder->status,
                'status_pembayaran' => $purchaseOrder->status_pembayaran ?? ''
            ]
        );

        // Get template parameter from request
        $template = request()->get('template', 'sinar-surya');

        // Determine the view based on template
        $viewName = 'pembelian.purchase_order.pdf';
        if (in_array($template, ['sinar-surya', 'atsaka', 'hidayah'])) {
            $viewName = "pembelian.purchase_order.pdf.{$template}";
        }

        $pdf = Pdf::loadView($viewName, compact(
            'purchaseOrder',
            'total',
            'createdBy',
            'processedBy',
            'isProcessed',
            'processedAt',
            'qrCodes'
        ));

        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');
        // Optimasi PDF untuk performa
        $pdf->setOptions([
            'dpi' => 96,
            'defaultFont' => 'DejaVu Sans',
            'isRemoteEnabled' => false,
            'isJavascriptEnabled' => false,
            'isHtml5ParserEnabled' => true,
            'fontSubsetting' => true,
            'tempDir' => sys_get_temp_dir(),
            'chroot' => public_path(),
            'logOutputFile' => storage_path('logs/dompdf.log'),
            'defaultMediaType' => 'print',
            'isFontSubsettingEnabled' => true,
        ]);

        // Download the PDF with a specific filename
        return $pdf->stream('PO-' . $purchaseOrder->nomor . '.pdf');
    }

    /**
     * Print PDF for purchase order (stream in browser)
     */
    public function printPdf($id)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'user', 'purchaseRequest', 'details.produk', 'details.satuan'])
            ->findOrFail($id);

        // Calculate total
        $total = 0;
        foreach ($purchaseOrder->details as $detail) {
            $total += $detail->quantity * $detail->harga;
        }

        // Get approval/processing information from LogAktivitas
        $createdBy = $purchaseOrder->user;
        $processedBy = null;
        $processedAt = null;
        $isProcessed = in_array($purchaseOrder->status, ['diproses', 'dikirim', 'selesai']);

        if ($isProcessed) {
            // Find the log entry for processing this purchase order
            $processLog = LogAktivitas::with('user')
                ->where('modul', 'purchase_order')
                ->where('data_id', $purchaseOrder->id)
                ->where('aktivitas', 'ubah_status')
                ->where('detail', 'like', '%ke diproses%')
                ->latest()
                ->first();

            if ($processLog) {
                $processedBy = $processLog->user;
                $processedAt = $processLog->created_at;
            }
        }

        // Get QR codes for PDF
        $qrCodes = $this->getPDFQRCodeData(
            'purchase_order',
            $purchaseOrder->nomor,
            $createdBy,
            $processedBy,
            [
                'supplier' => $purchaseOrder->supplier->nama ?? '',
                'total_items' => $purchaseOrder->details->count(),
                'total_amount' => $total,
                'status' => $purchaseOrder->status,
                'status_pembayaran' => $purchaseOrder->status_pembayaran ?? ''
            ]
        );

        // Get template parameter from request
        $template = request()->get('template', 'sinar-surya');

        // Determine the view based on template
        $viewName = 'pembelian.purchase_order.pdf';
        if (in_array($template, ['sinar-surya', 'atsaka', 'hidayah'])) {
            $viewName = "pembelian.purchase_order.pdf.{$template}";
        }

        $pdf = Pdf::loadView($viewName, compact(
            'purchaseOrder',
            'total',
            'createdBy',
            'processedBy',
            'isProcessed',
            'processedAt',
            'qrCodes'
        ));

        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('PO-' . $purchaseOrder->nomor . '.pdf');
    }
}
