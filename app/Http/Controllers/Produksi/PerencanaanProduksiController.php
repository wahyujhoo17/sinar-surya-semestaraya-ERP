<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\PerencanaanProduksi;
use App\Models\PerencanaanProduksiDetail;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\StokProduk;
use App\Models\Produk;
use App\Models\Gudang;
use App\Models\Satuan;
use App\Models\BillOfMaterial;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\NotificationService;

class PerencanaanProduksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:perencanaan_produksi.view')->only(['index', 'show', 'getSoItems', 'getSalesOrderData']);
        $this->middleware('permission:perencanaan_produksi.create')->only(['create', 'store', 'createWorkOrder']);
        $this->middleware('permission:perencanaan_produksi.edit')->only(['edit', 'update', 'submit']);
        $this->middleware('permission:perencanaan_produksi.delete')->only(['destroy']);
        $this->middleware('permission:perencanaan_produksi.approve')->only(['approve', 'reject']);
        $this->middleware('permission:perencanaan_produksi.view')->only(['changeStatus']);
    }

    /**
     * Menampilkan daftar perencanaan produksi
     */
    public function index(Request $request)
    {
        $query = PerencanaanProduksi::with(['salesOrder', 'salesOrder.customer']);

        $sort = $request->get('sort', 'tanggal');
        $direction = $request->get('direction', 'desc');

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhere('catatan', 'like', "%{$search}%")
                    ->orWhereHas('salesOrder', function ($sq) use ($search) {
                        $sq->where('nomor', 'like', "%{$search}%");
                    })
                    ->orWhereHas('salesOrder.customer', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%")
                            ->orWhere('company', 'like', "%{$search}%");
                    });
            });
        }

        // Filter berdasarkan periode
        if ($request->filled('periode') && $request->periode !== 'all') {
            $periode = explode(' - ', $request->periode);
            if (count($periode) == 2) {
                $start = date('Y-m-d', strtotime($periode[0]));
                $end = date('Y-m-d', strtotime($periode[1]));
                $query->whereBetween('tanggal', [$start, $end]);
            }
        }

        // Sorting
        if ($sort === 'nomor' || $sort === 'tanggal' || $sort === 'status') {
            $query->orderBy($sort, $direction);
        } else if ($sort === 'sales_order') {
            $query->leftJoin('sales_order', 'perencanaan_produksi.sales_order_id', '=', 'sales_order.id')
                ->orderBy('sales_order.nomor', $direction)
                ->select('perencanaan_produksi.*');
        } else if ($sort === 'customer') {
            $query->leftJoin('sales_order', 'perencanaan_produksi.sales_order_id', '=', 'sales_order.id')
                ->leftJoin('customer', 'sales_order.customer_id', '=', 'customer.id')
                ->orderBy('customer.nama', $direction)
                ->select('perencanaan_produksi.*');
        } else {
            $query->orderBy('tanggal', 'desc');
        }

        $perencanaanProduksi = $query->paginate(10);

        return view('produksi.perencanaan-produksi.index', compact('perencanaanProduksi'));
    }

    /**
     * Menampilkan form untuk membuat perencanaan produksi baru
     */
    public function create()
    {
        $salesOrders = SalesOrder::with('customer')
            ->where('status_pengiriman', '!=', 'dikirim')
            ->orderBy('tanggal', 'desc')
            ->get();
        $gudangs = Gudang::orderBy('nama')->get();
        $nomor = $this->generateNomorPerencanaan();
        $produks = Produk::with('satuan')->where('is_active', 1)->orderBy('nama')->get();

        return view('produksi.perencanaan-produksi.create', compact('salesOrders', 'gudangs', 'nomor', 'produks'));
    }

    /**
     * Mengambil data sales order untuk perencanaan produksi
     */
    public function getSalesOrderData(Request $request, $id)
    {
        $salesOrder = SalesOrder::with(['customer', 'details.produk', 'details.satuan'])->findOrFail($id);

        // Check stok untuk setiap produk di sales order
        foreach ($salesOrder->details as $detail) {
            $stokTersedia = StokProduk::where('produk_id', $detail->produk_id)
                ->where('jumlah', '>', 0)
                ->sum('jumlah');

            $detail->stok_tersedia = $stokTersedia;
            $detail->kekurangan = max(0, $detail->jumlah - $stokTersedia);
        }

        return response()->json($salesOrder);
    }

    /**
     * Mendapatkan item-item dari sales order untuk perencanaan produksi
     */
    public function getSoItems(Request $request)
    {
        $salesOrderId = $request->sales_order_id;
        $gudangId = $request->gudang_id;

        if (!$salesOrderId || !$gudangId) {
            return response()->json(['items' => []], 400);
        }

        $salesOrder = SalesOrder::with(['details.produk', 'details.satuan'])->find($salesOrderId);

        if (!$salesOrder) {
            return response()->json(['items' => []], 404);
        }

        $items = [];

        foreach ($salesOrder->details as $detail) {
            $produk = $detail->produk;

            // Dianggap semua produk bisa direncanakan produksinya karena field tipe tidak ada
            if ($produk) {
                $stokTersedia = StokProduk::where('produk_id', $produk->id)
                    ->where('gudang_id', $gudangId)
                    ->sum('jumlah');

                $items[] = [
                    'produk_id' => $produk->id,
                    'produk_nama' => $produk->nama,
                    'kode_produk' => $produk->kode,
                    'quantity' => $detail->quantity,
                    'quantity_terkirim' => $detail->quantity_terkirim,
                    'quantity_sisa' => $detail->quantity - $detail->quantity_terkirim,
                    'satuan_id' => $detail->satuan_id,
                    'satuan_nama' => $detail->satuan->nama ?? 'Satuan tidak ditemukan',
                    'stok_tersedia' => $stokTersedia,
                    'kekurangan' => max(0, $detail->quantity - $detail->quantity_terkirim - $stokTersedia)
                ];
            }
        }

        return response()->json(['items' => $items]);
    }

    /**
     * Mendapatkan stok produk di gudang tertentu
     */
    public function getStok(Request $request)
    {
        $produkId = $request->produk_id;
        $gudangId = $request->gudang_id;

        if (!$produkId || !$gudangId) {
            return response()->json(['stok' => 0], 400);
        }

        $stok = StokProduk::where('produk_id', $produkId)
            ->where('gudang_id', $gudangId)
            ->sum('jumlah');

        return response()->json(['stok' => $stok]);
    }

    /**
     * Menyimpan perencanaan produksi baru
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'sales_order_id' => 'nullable|exists:sales_order,id',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
            'detail' => 'required|array',
            'detail.*.produk_id' => 'required|exists:produk,id',
            'detail.*.jumlah_produksi' => 'required|numeric|min:1',
            'detail.*.satuan_id' => 'required|exists:satuan,id',
        ]);

        DB::beginTransaction();

        try {
            // Generate nomor perencanaan produksi
            $nomor = $this->generateNomorPerencanaan();

            // Buat perencanaan produksi
            $perencanaan = PerencanaanProduksi::create([
                'nomor' => $nomor,
                'tanggal' => $request->tanggal,
                'sales_order_id' => $request->sales_order_id,
                'catatan' => $request->catatan ?? NULL,
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);

            // Simpan detail perencanaan
            foreach ($request->detail as $item) {

                if (isset($item['produk_id']) && isset($item['jumlah_produksi']) && $item['jumlah_produksi'] > 0) {
                    PerencanaanProduksiDetail::create([
                        'perencanaan_produksi_id' => $perencanaan->id,
                        'produk_id' => $item['produk_id'],
                        'jumlah' => $item['jumlah'],
                        'satuan_id' => $item['satuan_id'],
                        'stok_tersedia' => $item['stok_tersedia'] ?? 0,
                        'jumlah_produksi' => $item['jumlah_produksi'],
                        'keterangan' => $item['keterangan'] ?? null,
                    ]);
                }
            }

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Membuat perencanaan produksi baru: ' . $nomor,
                'modul' => 'produksi',
                'id_referensi' => $perencanaan->id,
                'jenis_referensi' => 'perencanaan_produksi',
            ]);

            // Send notification to production managers
            $notificationService = new NotificationService();
            $notificationService->notifyProductionPlanCreated($perencanaan, Auth::user());

            DB::commit();

            return redirect()->route('produksi.perencanaan-produksi.show', $perencanaan->id)
                ->with('success', 'Perencanaan produksi berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Mengajukan perencanaan produksi untuk persetujuan
     */
    public function submit($id)
    {
        DB::beginTransaction();

        try {
            $perencanaan = PerencanaanProduksi::findOrFail($id);

            // Hanya bisa diajukan jika masih dalam status draft
            if ($perencanaan->status !== 'draft') {
                return redirect()->back()->with('error', 'Hanya perencanaan dengan status draft yang dapat diajukan untuk persetujuan.');
            }

            $perencanaan->status = 'menunggu_persetujuan';
            $perencanaan->save();

            // Send notification to production managers for approval
            $notificationService = new NotificationService();
            $notificationService->notifyApprovalRequired('perencanaan_produksi', $perencanaan, ['manager_produksi', 'production']);

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Mengajukan perencanaan produksi untuk persetujuan: ' . $perencanaan->nomor,
                'modul' => 'produksi',
                'id_referensi' => $perencanaan->id,
                'tabel_referensi' => 'perencanaan_produksi'
            ]);

            DB::commit();

            return redirect()->route('produksi.perencanaan-produksi.show', $perencanaan->id)
                ->with('success', 'Perencanaan produksi berhasil diajukan untuk persetujuan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menyetujui perencanaan produksi
     */
    public function approve($id)
    {
        DB::beginTransaction();

        try {
            $perencanaan = PerencanaanProduksi::findOrFail($id);

            // Hanya bisa disetujui jika statusnya menunggu persetujuan
            if ($perencanaan->status !== 'menunggu_persetujuan') {
                return redirect()->back()->with('error', 'Hanya perencanaan dengan status menunggu persetujuan yang dapat disetujui.');
            }

            $perencanaan->status = 'disetujui';
            $perencanaan->approved_by = Auth::id();
            $perencanaan->approved_at = now();
            $perencanaan->save();

            // Send notification to creator about approval
            $notificationService = new NotificationService();
            $notificationService->sendToUsers(
                [$perencanaan->created_by],
                'success',
                'Perencanaan Produksi Disetujui',
                "Perencanaan Produksi #{$perencanaan->nomor} telah disetujui oleh " . Auth::user()->name,
                [
                    'url' => route('produksi.perencanaan-produksi.show', $perencanaan->id),
                    'production_plan_id' => $perencanaan->id,
                    'approved_by' => Auth::id()
                ]
            );

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Menyetujui perencanaan produksi: ' . $perencanaan->nomor,
                'modul' => 'produksi',
                'id_referensi' => $perencanaan->id,
                'tabel_referensi' => 'perencanaan_produksi'
            ]);

            DB::commit();

            return redirect()->route('produksi.perencanaan-produksi.show', $perencanaan->id)
                ->with('success', 'Perencanaan produksi berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menolak perencanaan produksi
     */
    public function reject($id)
    {
        DB::beginTransaction();

        try {
            $perencanaan = PerencanaanProduksi::findOrFail($id);

            // Hanya bisa ditolak jika statusnya menunggu persetujuan
            if ($perencanaan->status !== 'menunggu_persetujuan') {
                return redirect()->back()->with('error', 'Hanya perencanaan dengan status menunggu persetujuan yang dapat ditolak.');
            }

            $perencanaan->status = 'ditolak';
            $perencanaan->approved_by = Auth::id();
            $perencanaan->approved_at = now();
            $perencanaan->save();

            // Send notification to creator about rejection
            $notificationService = new NotificationService();
            $notificationService->sendToUsers(
                [$perencanaan->created_by],
                'warning',
                'Perencanaan Produksi Ditolak',
                "Perencanaan Produksi #{$perencanaan->nomor} telah ditolak oleh " . Auth::user()->name,
                [
                    'url' => route('produksi.perencanaan-produksi.show', $perencanaan->id),
                    'production_plan_id' => $perencanaan->id,
                    'rejected_by' => Auth::id()
                ]
            );

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Menolak perencanaan produksi: ' . $perencanaan->nomor,
                'modul' => 'produksi',
                'id_referensi' => $perencanaan->id,
                'tabel_referensi' => 'perencanaan_produksi'
            ]);

            DB::commit();

            return redirect()->route('produksi.perencanaan-produksi.show', $perencanaan->id)
                ->with('success', 'Perencanaan produksi telah ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Membuat Work Order dari perencanaan produksi
     */
    public function createWorkOrder($id)
    {
        $perencanaan = PerencanaanProduksi::with(['detailPerencanaan.produk', 'detailPerencanaan.satuan', 'salesOrder'])
            ->findOrFail($id);

        // Hanya bisa membuat work order jika perencanaan disetujui atau sedang berjalan
        if (!in_array($perencanaan->status, ['disetujui', 'berjalan'])) {
            return redirect()->back()->with('error', 'Hanya perencanaan dengan status disetujui atau berjalan yang dapat dibuatkan Work Order.');
        }

        $boms = BillOfMaterial::with('produk')->orderBy('kode')->get();
        $gudangs = Gudang::orderBy('nama')->get();

        return view('produksi.perencanaan-produksi.create-work-order', compact('perencanaan', 'boms', 'gudangs'));
    }

    /**
     * Update perencanaan produksi
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
            'detail' => 'required|array',
            'detail.*.id' => 'required|exists:perencanaan_produksi_detail,id',
            'detail.*.jumlah_produksi' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $perencanaan = PerencanaanProduksi::findOrFail($id);

            // Hanya bisa diupdate jika masih dalam status draft
            if ($perencanaan->status !== 'draft') {
                return redirect()->back()->with('error', 'Hanya perencanaan dengan status draft yang dapat diubah.');
            }

            $perencanaan->tanggal = $request->tanggal;
            $perencanaan->catatan = $request->catatan;
            $perencanaan->save();

            // Update detail perencanaan
            foreach ($request->detail as $item) {
                if (isset($item['id'])) {
                    $detail = PerencanaanProduksiDetail::find($item['id']);

                    if ($detail && $detail->perencanaan_produksi_id == $perencanaan->id) {
                        $detail->jumlah_produksi = $item['jumlah_produksi'];
                        $detail->keterangan = $item['keterangan'] ?? null;
                        $detail->save();
                    }
                }
            }

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Mengupdate perencanaan produksi: ' . $perencanaan->nomor,
                'modul' => 'produksi',
                'id_referensi' => $perencanaan->id,
                'tabel_referensi' => 'perencanaan_produksi'
            ]);

            DB::commit();

            return redirect()->route('produksi.perencanaan-produksi.show', $perencanaan->id)
                ->with('success', 'Perencanaan produksi berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk mengedit perencanaan produksi
     */
    public function edit($id)
    {
        $perencanaan = PerencanaanProduksi::with([
            'detailPerencanaan.produk',
            'detailPerencanaan.satuan',
            'salesOrder.customer'
        ])->findOrFail($id);

        // Hanya bisa diedit jika masih dalam status draft
        if ($perencanaan->status !== 'draft') {
            return redirect()->route('produksi.perencanaan-produksi.show', $perencanaan->id)
                ->with('error', 'Hanya perencanaan dengan status draft yang dapat diubah.');
        }

        // Get salesOrders and gudangs for the form
        $salesOrders = SalesOrder::with('customer')
            ->where('status_pengiriman', '!=', 'dikirim')
            ->orderBy('tanggal', 'desc')
            ->get();
        $gudangs = Gudang::orderBy('nama')->get();
        $produks = Produk::with('satuan')->where('is_active', 1)->orderBy('nama')->get();

        // Set details property for the view
        $perencanaan->details = $perencanaan->detailPerencanaan;

        return view('produksi.perencanaan-produksi.edit', compact('perencanaan', 'salesOrders', 'gudangs', 'produks'));
    }

    /**
     * Menampilkan detail perencanaan produksi
     */
    public function show($id)
    {
        $perencanaan = PerencanaanProduksi::with([
            'detailPerencanaan.produk',
            'detailPerencanaan.satuan',
            'salesOrder.customer',
            'workOrders.produk',
            'workOrders.satuan',
            'creator',
            'approver'
        ])->findOrFail($id);

        return view('produksi.perencanaan-produksi.show', compact('perencanaan'));
    }

    /**
     * Generate nomor perencanaan produksi
     */
    private function generateNomorPerencanaan()
    {
        $prefix = 'PP-';
        $date = now()->format('Ymd');

        $lastPerencanaan = DB::table('perencanaan_produksi')
            ->where('nomor', 'like', $prefix . $date . '-%')
            ->selectRaw('MAX(CAST(SUBSTRING(nomor, ' . (strlen($prefix . $date . '-') + 1) . ') AS UNSIGNED)) as last_num')
            ->first();

        $newNumberSuffix = '001';
        if ($lastPerencanaan && !is_null($lastPerencanaan->last_num)) {
            $newNumberSuffix = str_pad($lastPerencanaan->last_num + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . $date . '-' . $newNumberSuffix;
    }
}
