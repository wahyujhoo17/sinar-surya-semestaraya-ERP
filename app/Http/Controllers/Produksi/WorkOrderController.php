<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\WorkOrderMaterial;
use App\Models\PerencanaanProduksi;
use App\Models\PerencanaanProduksiDetail;
use App\Models\StokProduk;
use App\Models\Produk;
use App\Models\Gudang;
use App\Models\Satuan;
use App\Models\BillOfMaterial;
use App\Models\PengambilanBahanBaku;
use App\Models\PengambilanBahanBakuDetail;
use App\Models\QualityControl;
use App\Models\QualityControlDetail;
use App\Models\RiwayatStok;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\NotificationService;

class WorkOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:work_order.view')->only(['index', 'show']);
        $this->middleware('permission:work_order.create')->only(['create', 'store', 'createPengambilanBahanBaku', 'storePengambilanBahanBaku', 'createQualityControl', 'storeQualityControl']);
        $this->middleware('permission:work_order.edit')->only(['edit', 'update']);
        $this->middleware('permission:work_order.delete')->only(['destroy']);
        $this->middleware('permission:work_order.change_status')->only(['changeStatus']);
    }

    /**
     * Helper function to convert date format from d/m/Y to Y-m-d
     */
    private function convertDateFormat($date)
    {
        if (!$date) {
            return null;
        }

        // If already in Y-m-d format (from date input), return as is
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $date;
        }

        // If in d/m/Y format (from text input with datepicker), convert
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
            try {
                return \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        // Try to parse as a general date
        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Menampilkan daftar work order
     */
    public function index(Request $request)
    {
        $query = WorkOrder::with(['produk', 'salesOrder', 'perencanaanProduksi']);

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
                    ->orWhereHas('produk', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%")
                            ->orWhere('kode', 'like', "%{$search}%");
                    })
                    ->orWhereHas('salesOrder', function ($sq) use ($search) {
                        $sq->where('nomor', 'like', "%{$search}%");
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
        if (in_array($sort, ['nomor', 'tanggal', 'status', 'tanggal_mulai', 'tanggal_selesai'])) {
            $query->orderBy($sort, $direction);
        } else if ($sort === 'produk') {
            $query->join('produk', 'work_order.produk_id', '=', 'produk.id')
                ->orderBy('produk.nama', $direction)
                ->select('work_order.*');
        } else {
            $query->orderBy('tanggal', 'desc');
        }

        $workOrders = $query->paginate(10);

        return view('produksi.work-order.index', compact('workOrders'));
    }

    /**
     * Menampilkan form untuk membuat work order baru
     */
    public function create(Request $request)
    {
        $perencanaanId = $request->perencanaan_id;
        $produkId = $request->produk_id;
        $detailPerencanaan = null; // Initialize variable to avoid undefined variable error

        if ($perencanaanId) {
            // Load perencanaan with eager loading of related models
            $perencanaan = PerencanaanProduksi::with([
                'salesOrder.customer',
                'detailPerencanaan.produk',
                'detailPerencanaan.satuan'
            ])->findOrFail($perencanaanId);

            if ($produkId) {
                // Find the detail for the selected product with proper null checking
                $detailPerencanaan = $perencanaan->detailPerencanaan
                    ->where('produk_id', $produkId)
                    ->first();

                if (!$detailPerencanaan) {
                    return redirect()->back()
                        ->with('error', 'Produk tidak ditemukan dalam perencanaan produksi.');
                }
            } else {
                // If no product is selected, show the product selection page
                return view('produksi.work-order.select-product', compact('perencanaan'));
            }
        } else {
            // Get approved production plans, with eager loading to improve performance
            $perencanaanList = PerencanaanProduksi::with(['salesOrder.customer'])
                ->where('status', 'disetujui')
                ->orderBy('tanggal', 'desc')
                ->get();

            return view('produksi.work-order.select-perencanaan', compact('perencanaanList'));
        }

        // Get all active warehouses
        $gudang = Gudang::all();

        // Find active BOMs for the selected product
        $boms = BillOfMaterial::where('produk_id', $produkId)
            ->where('is_active', true)
            ->get();

        return view('produksi.work-order.create', compact('perencanaan', 'detailPerencanaan', 'gudang', 'boms'));
    }

    /**
     * Menyimpan work order baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perencanaan_produksi_id' => 'required|exists:perencanaan_produksi,id',
            'produk_id' => 'required|exists:produk,id',
            'bom_id' => 'required|exists:bill_of_materials,id',
            'gudang_produksi_id' => 'required|exists:gudang,id',
            'gudang_hasil_id' => 'required|exists:gudang,id',
            'quantity' => 'required|numeric|min:0.01',
            'satuan_id' => 'required|exists:satuan,id',
            'tanggal' => 'required|date',
            'tanggal_mulai' => 'required|date',
            'deadline' => 'required|date|after_or_equal:tanggal_mulai',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ensure gudang_produksi_id and gudang_hasil_id are different
        if ($request->gudang_produksi_id == $request->gudang_hasil_id) {
            return redirect()->back()
                ->with('error', 'Gudang Produksi dan Gudang Hasil tidak boleh sama.')
                ->withInput();
        }

        // Verify the product exists in the production plan
        $detailPerencanaan = PerencanaanProduksiDetail::where('perencanaan_produksi_id', $request->perencanaan_produksi_id)
            ->where('produk_id', $request->produk_id)
            ->first();

        if (!$detailPerencanaan) {
            return redirect()->back()
                ->with('error', 'Produk tidak ditemukan dalam perencanaan produksi.')
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Generate nomor work order
            $nomor = $this->generateNomorWorkOrder();

            // Get sales order ID from production plan
            $perencanaan = PerencanaanProduksi::findOrFail($request->perencanaan_produksi_id);

            // Konversi format tanggal menggunakan helper function
            $tanggal = $this->convertDateFormat($request->tanggal);
            $tanggal_mulai = $this->convertDateFormat($request->tanggal_mulai);
            $deadline = $this->convertDateFormat($request->deadline);

            // Create work order
            $workOrder = WorkOrder::create([
                'nomor' => $nomor,
                'tanggal' => $tanggal,
                'bom_id' => $request->bom_id,
                'sales_order_id' => $perencanaan->sales_order_id,
                'perencanaan_produksi_id' => $request->perencanaan_produksi_id,
                'produk_id' => $request->produk_id,
                'gudang_produksi_id' => $request->gudang_produksi_id,
                'gudang_hasil_id' => $request->gudang_hasil_id,
                'user_id' => Auth::id(),
                'quantity' => $request->quantity,
                'satuan_id' => $request->satuan_id,
                'tanggal_mulai' => $tanggal_mulai,
                'deadline' => $deadline,
                'status' => 'direncanakan', // Changed from 'draft' to 'direncanakan' to match enum values
                'catatan' => $request->catatan,
            ]);

            // Ambil BOM dan tambahkan material ke work order
            $bom = BillOfMaterial::with('details')->findOrFail($request->bom_id);
            foreach ($bom->details as $component) {
                // Use quantity column from bill_of_material_details table and prevent division by zero
                if ($request->quantity > 0) {
                    $kebutuhan = ($component->quantity) * $request->quantity;
                } else {
                    $kebutuhan = 0;
                }

                WorkOrderMaterial::create([
                    'work_order_id' => $workOrder->id,
                    'produk_id' => $component->komponen_id, // Fix: changed from material_id to komponen_id to match the model
                    'quantity' => $kebutuhan, // Fix: changed from jumlah to quantity to match the model
                    'satuan_id' => $component->satuan_id,
                ]);
            }

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Membuat work order baru: ' . $nomor,
                'modul' => 'produksi',
                'id_referensi' => $workOrder->id,
                'jenis_referensi' => 'work_order',
            ]);

            DB::commit();

            return redirect()->route('produksi.work-order.show', $workOrder->id)
                ->with('success', 'Work Order berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail work order
     */
    public function show($id)
    {
        $workOrder = WorkOrder::with([
            'produk',
            'salesOrder',
            'salesOrder.customer',
            'bom',
            'perencanaanProduksi',
            'gudangProduksi',
            'gudangHasil',
            'materials',
            'materials.produk',
            'materials.satuan',
            'pengambilanBahanBaku',
            'pengambilanBahanBaku.detail',
            'pengambilanBahanBaku.detail.produk',
            'pengambilanBahanBaku.detail.satuan',
            'qualityControl'
        ])->findOrFail($id);

        return view('produksi.work-order.show', compact('workOrder'));
    }

    /**
     * Menampilkan form untuk edit work order
     */
    public function edit($id)
    {
        $workOrder = WorkOrder::with(['produk', 'salesOrder', 'materials.produk', 'materials.satuan'])->findOrFail($id);

        // Hanya bisa edit jika status masih direncanakan
        if ($workOrder->status !== 'direncanakan') {
            return redirect()->route('produksi.work-order.show', $id)
                ->with('error', 'Work Order tidak dapat diedit karena status bukan direncanakan.');
        }

        $gudang = Gudang::all();
        $boms = BillOfMaterial::where('produk_id', $workOrder->produk_id)->get();

        return view('produksi.work-order.edit', compact('workOrder', 'gudang', 'boms'));
    }

    /**
     * Update work order
     */
    public function update(Request $request, $id)
    {
        $workOrder = WorkOrder::findOrFail($id);

        // Hanya bisa update jika status masih direncanakan
        if ($workOrder->status !== 'direncanakan') {
            return redirect()->route('produksi.work-order.show', $id)
                ->with('error', 'Work Order tidak dapat diedit karena status bukan direncanakan.');
        }

        $validator = Validator::make($request->all(), [
            'bom_id' => 'required|exists:bill_of_materials,id',
            'gudang_produksi_id' => 'required|exists:gudang,id',
            'gudang_hasil_id' => 'required|exists:gudang,id',
            'quantity' => 'required|numeric|min:0.01',
            'satuan_id' => 'required|exists:satuan,id',
            'tanggal' => 'required|date_format:d/m/Y',
            'tanggal_mulai' => 'required|date_format:d/m/Y',
            'deadline' => 'required|date_format:d/m/Y',
            'catatan' => 'nullable|string',
        ]);

        // Custom validation untuk memastikan deadline setelah atau sama dengan tanggal_mulai
        $validator->after(function ($validator) use ($request) {
            if ($request->tanggal_mulai && $request->deadline) {
                try {
                    $tanggal_mulai = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_mulai);
                    $deadline = \Carbon\Carbon::createFromFormat('d/m/Y', $request->deadline);

                    if ($deadline->lt($tanggal_mulai)) {
                        $validator->errors()->add('deadline', 'Deadline harus setelah atau sama dengan tanggal mulai.');
                    }
                } catch (\Exception $e) {
                    // Format tanggal tidak valid, sudah ditangani oleh rule date_format
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            // Konversi format tanggal menggunakan helper function
            $tanggal = $this->convertDateFormat($request->tanggal);
            $tanggal_mulai = $this->convertDateFormat($request->tanggal_mulai);
            $deadline = $this->convertDateFormat($request->deadline);

            // Update work order
            $workOrder->update([
                'tanggal' => $tanggal,
                'bom_id' => $request->bom_id,
                'gudang_produksi_id' => $request->gudang_produksi_id,
                'gudang_hasil_id' => $request->gudang_hasil_id,
                'quantity' => $request->quantity,
                'satuan_id' => $request->satuan_id,
                'tanggal_mulai' => $tanggal_mulai,
                'deadline' => $deadline,
                'catatan' => $request->catatan,
            ]);

            // Hapus material lama
            WorkOrderMaterial::where('work_order_id', $workOrder->id)->delete();

            // Tambahkan material baru berdasarkan BOM
            $bom = BillOfMaterial::with('details')->findOrFail($request->bom_id);
            foreach ($bom->details as $component) {
                // Use quantity column from bill_of_material_details table and prevent division by zero
                if ($request->quantity > 0) {
                    $kebutuhan = ($component->quantity) * $request->quantity;
                } else {
                    $kebutuhan = 0;
                }

                WorkOrderMaterial::create([
                    'work_order_id' => $workOrder->id,
                    'produk_id' => $component->komponen_id, // Fix: changed from material_id to komponen_id to match the model
                    'quantity' => $kebutuhan, // Fix: changed from jumlah to quantity to match the model
                    'satuan_id' => $component->satuan_id,
                ]);
            }

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Mengupdate work order: ' . $workOrder->nomor,
                'modul' => 'produksi',
                'id_referensi' => $workOrder->id,
                'jenis_referensi' => 'work_order',
            ]);

            DB::commit();

            return redirect()->route('produksi.work-order.show', $workOrder->id)
                ->with('success', 'Work Order berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Mengubah status work order
     */
    public function changeStatus(Request $request, $id)
    {
        $workOrder = WorkOrder::findOrFail($id);
        $newStatus = $request->status;

        // Validasi perubahan status
        $validStatus = [
            'direncanakan' => ['berjalan'],
            'berjalan' => ['selesai_produksi', 'dibatalkan'],
            'selesai_produksi' => ['berjalan', 'qc_passed'],
            'qc_passed' => ['pengembalian_material'],
            'pengembalian_material' => ['selesai'],
            'selesai' => ['selesai'], // Maintain the current status
            'dibatalkan' => ['dibatalkan'] // Maintain the current status
        ];

        if (!isset($validStatus[$workOrder->status]) || !in_array($newStatus, $validStatus[$workOrder->status])) {
            return redirect()->back()->with('error', 'Perubahan status tidak valid.');
        }

        DB::beginTransaction();

        try {
            if ($newStatus === 'berjalan') {
                // Cek apakah sudah ada pengambilan bahan baku
                if (!$workOrder->pengambilanBahanBaku()->exists()) {
                    return redirect()->back()->with('error', 'Tidak dapat memulai produksi karena belum ada pengambilan bahan baku.');
                }
            }

            if ($newStatus === 'selesai') {
                // Set tanggal selesai jika belum ada
                if (!$workOrder->tanggal_selesai) {
                    $workOrder->tanggal_selesai = now();
                }
            }

            $workOrder->status = $newStatus;
            $workOrder->save();

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Mengubah status work order ' . $workOrder->nomor . ' menjadi ' . $newStatus,
                'modul' => 'produksi',
                'id_referensi' => $workOrder->id,
                'jenis_referensi' => 'work_order',
            ]);

            // Jika status menjadi selesai, tambahkan stok barang jadi
            if ($newStatus === 'selesai') {
                // Determine the quantity to add to stock
                // If QC exists, use passed quantity; otherwise use full work order quantity
                $quantityToAdd = $workOrder->qualityControl ? $workOrder->qualityControl->jumlah_lolos : $workOrder->quantity;

                // Tambahkan stok barang jadi ke gudang hasil
                $stokProduk = StokProduk::updateOrCreate(
                    [
                        'produk_id' => $workOrder->produk_id,
                        'gudang_id' => $workOrder->gudang_hasil_id,
                    ],
                    [
                        'jumlah' => DB::raw('jumlah + ' . $quantityToAdd),
                    ]
                );

                // Reload stok untuk mendapatkan nilai yang telah diupdate
                $stokProduk->refresh();

                // Catat di riwayat stok untuk produk jadi
                RiwayatStok::create([
                    'stok_id' => $stokProduk->id,
                    'produk_id' => $workOrder->produk_id,
                    'gudang_id' => $workOrder->gudang_hasil_id,
                    'user_id' => Auth::id(),
                    'jumlah_sebelum' => $stokProduk->jumlah - $quantityToAdd,
                    'jumlah_perubahan' => $quantityToAdd,
                    'jumlah_setelah' => $stokProduk->jumlah,
                    'jenis' => 'masuk',
                    'referensi_tipe' => 'work_order',
                    'referensi_id' => $workOrder->id,
                    'keterangan' => 'Hasil produksi dari Work Order ' . $workOrder->nomor
                ]);

                // Update status perencanaan produksi jika semua work order selesai
                $this->checkPerencanaanCompletion($workOrder->perencanaan_produksi_id);

                // Send work order completion notification
                $notificationService = new NotificationService();
                $notificationService->notifyWorkOrderCompleted($workOrder, Auth::user());
            }

            DB::commit();

            return redirect()->route('produksi.work-order.show', $workOrder->id)
                ->with('success', 'Status Work Order berhasil diubah menjadi ' . $newStatus);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Membuat pengambilan bahan baku untuk work order
     */
    public function createPengambilanBahanBaku($id)
    {
        $workOrder = WorkOrder::with(['materials.produk', 'materials.satuan', 'gudangProduksi'])->findOrFail($id);

        // Cek stok bahan baku di gudang produksi
        foreach ($workOrder->materials as $material) {
            $stokTersedia = StokProduk::where('produk_id', $material->produk_id)
                ->where('gudang_id', $workOrder->gudang_produksi_id)
                ->sum('jumlah');

            $material->stok_tersedia = $stokTersedia;
            $material->kekurangan = max(0, $material->quantity - $stokTersedia);

            // Debug log untuk memverifikasi data stok
            Log::info("Material {$material->produk->nama} - Stok Tersedia: {$stokTersedia}", [
                'produk_id' => $material->produk_id,
                'gudang_id' => $workOrder->gudang_produksi_id,
                'quantity_needed' => $material->quantity,
                'stok_tersedia' => $stokTersedia
            ]);
        }

        return view('produksi.work-order.create-pengambilan', compact('workOrder'));
    }

    /**
     * Menyimpan pengambilan bahan baku
     */
    public function storePengambilanBahanBaku(Request $request, $id)
    {

        $workOrder = WorkOrder::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date_format:d/m/Y',
            'catatan' => 'nullable|string',
            'detail' => 'required|array',
            'detail.*.produk_id' => 'required|exists:produk,id',
            'detail.*.jumlah_diminta' => 'required|numeric|min:0.01',
            'detail.*.jumlah_diambil' => 'required|numeric|min:0.01',
            'detail.*.satuan_id' => 'required|exists:satuan,id',
            'detail.*.tipe' => 'nullable|in:bom,manual',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            // Convert date format from d/m/Y to Y-m-d
            $tanggal = \DateTime::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');

            // Generate nomor pengambilan bahan baku
            $nomor = $this->generateNomorPengambilanBahanBaku();

            // Buat pengambilan bahan baku
            $pengambilan = PengambilanBahanBaku::create([
                'nomor' => $nomor,
                'tanggal' => $tanggal,
                'work_order_id' => $workOrder->id,
                'gudang_id' => $workOrder->gudang_produksi_id,
                'status' => 'completed',
                'catatan' => $request->catatan,
                'created_by' => Auth::id(),
            ]);

            // Simpan detail pengambilan
            foreach ($request->detail as $item) {
                if (isset($item['produk_id']) && isset($item['jumlah_diminta']) && $item['jumlah_diambil'] > 0) {
                    // Simpan detail pengambilan
                    PengambilanBahanBakuDetail::create([
                        'pengambilan_bahan_baku_id' => $pengambilan->id,
                        'produk_id' => $item['produk_id'],
                        'jumlah_diminta' => $item['jumlah_diminta'],
                        'jumlah_diambil' => $item['jumlah_diambil'],
                        'satuan_id' => $item['satuan_id'],
                        'keterangan' => $item['keterangan'] ?? null,
                    ]);

                    // Kurangi stok bahan baku di gudang
                    $stok = StokProduk::where('produk_id', $item['produk_id'])
                        ->where('gudang_id', $workOrder->gudang_produksi_id)
                        ->where('jumlah', '>', 0)
                        ->orderBy('created_at')
                        ->first();

                    if ($stok) {
                        $jumlahSebelum = $stok->jumlah;
                        $jumlahPerubahan = $item['jumlah_diambil'];
                        $jumlahSetelah = max(0, $jumlahSebelum - $jumlahPerubahan);

                        // Update stok
                        $stok->jumlah = $jumlahSetelah;
                        $stok->save();

                        // Catat di riwayat stok
                        RiwayatStok::create([
                            'stok_id' => $stok->id,
                            'produk_id' => $item['produk_id'],
                            'gudang_id' => $workOrder->gudang_produksi_id,
                            'user_id' => Auth::id(),
                            'jumlah_sebelum' => $jumlahSebelum,
                            'jumlah_perubahan' => -$jumlahPerubahan, // Nilai negatif karena pengurangan stok
                            'jumlah_setelah' => $jumlahSetelah,
                            'jenis' => 'keluar',
                            'referensi_tipe' => 'pengambilan_bahan_baku',
                            'referensi_id' => $pengambilan->id,
                            'keterangan' => 'Pengambilan bahan baku untuk Work Order ' . $workOrder->nomor
                        ]);
                    }
                }
            }

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Membuat pengambilan bahan baku: ' . $nomor . ' untuk work order ' . $workOrder->nomor,
                'modul' => 'produksi',
                'id_referensi' => $pengambilan->id,
                'jenis_referensi' => 'pengambilan_bahan_baku',
            ]);

            DB::commit();

            return redirect()->route('produksi.work-order.show', $workOrder->id)
                ->with('success', 'Pengambilan bahan baku berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Membuat quality control untuk work order
     */
    public function createQualityControl($id)
    {
        $workOrder = WorkOrder::with(['produk', 'satuan'])->findOrFail($id);

        // Hanya bisa membuat QC jika status selesai_produksi
        if ($workOrder->status !== 'selesai_produksi') {
            return redirect()->route('produksi.work-order.show', $id)
                ->with('error', 'Quality Control hanya dapat dibuat untuk work order yang telah selesai produksi.');
        }

        return view('produksi.work-order.create-qc', compact('workOrder'));
    }

    /**
     * Menyimpan quality control
     */
    public function storeQualityControl(Request $request, $id)
    {
        $workOrder = WorkOrder::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tanggal_inspeksi' => 'required|date',
            'jumlah_lolos' => 'required|numeric|min:0',
            'jumlah_gagal' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
            'kriteria' => 'required|array',
            'kriteria.*.nama' => 'required|string',
            'kriteria.*.hasil' => 'required|in:lolos,gagal',
            'kriteria.*.keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validasi jumlah total QC sama dengan quantity work order
        $totalQC = $request->jumlah_lolos + $request->jumlah_gagal;
        if ($totalQC != $workOrder->quantity) {
            return redirect()->back()
                ->with('error', 'Total jumlah lolos dan gagal (' . $totalQC . ') harus sama dengan jumlah work order (' . $workOrder->quantity . ').')
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Generate nomor quality control
            $prefix = 'QC-';
            $date = now()->format('Ymd');

            $lastQC = DB::table('quality_control')
                ->where('nomor', 'like', $prefix . $date . '-%')
                ->selectRaw('MAX(CAST(SUBSTRING(nomor, ' . (strlen($prefix . $date . '-') + 1) . ') AS UNSIGNED)) as last_num')
                ->first();

            $newNumberSuffix = '001';
            if ($lastQC && !is_null($lastQC->last_num)) {
                $newNumberSuffix = str_pad($lastQC->last_num + 1, 3, '0', STR_PAD_LEFT);
            }

            $nomorQC = $prefix . $date . '-' . $newNumberSuffix;

            // Buat quality control
            $qc = QualityControl::create([
                'nomor' => $nomorQC,
                'work_order_id' => $workOrder->id,
                'tanggal' => $request->tanggal_inspeksi,
                'status' => $request->jumlah_gagal > 0 ? 'sebagian_gagal' : 'semua_lolos',
                'jumlah_lolos' => $request->jumlah_lolos,
                'jumlah_gagal' => $request->jumlah_gagal,
                'catatan' => $request->catatan,
                'inspector_id' => Auth::id(),
            ]);

            // Simpan detail kriteria QC
            foreach ($request->kriteria as $item) {
                QualityControlDetail::create([
                    'quality_control_id' => $qc->id,
                    'parameter' => $item['nama'],
                    'hasil' => $item['hasil'],
                    'status' => $item['hasil'] === 'lolos' ? 'pass' : 'fail',
                    'keterangan' => $item['keterangan'] ?? null,
                ]);
            }

            // Update status work order setelah QC
            // Jika QC lolos, ubah ke qc_passed
            // Jika QC gagal, kembalikan ke berjalan untuk perbaikan
            $newStatus = $request->jumlah_gagal > 0 ? 'berjalan' : 'qc_passed';
            $workOrder->status = $newStatus;
            $workOrder->save();

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Membuat quality control untuk work order ' . $workOrder->nomor . ' dengan hasil ' .
                    ($newStatus === 'qc_passed' ? 'lulus QC' : 'perlu perbaikan'),
                'modul' => 'produksi',
                'id_referensi' => $qc->id,
                'jenis_referensi' => 'quality_control',
            ]);

            DB::commit();

            return redirect()->route('produksi.work-order.show', $workOrder->id)
                ->with('success', 'Quality Control berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Check if all work orders for a perencanaan are completed
     */
    private function checkPerencanaanCompletion($perencanaanId)
    {
        $perencanaan = PerencanaanProduksi::findOrFail($perencanaanId);
        $allCompleted = true;

        foreach ($perencanaan->workOrders as $wo) {
            if ($wo->status !== 'selesai' && $wo->status !== 'dibatalkan') {
                $allCompleted = false;
                break;
            }
        }

        if ($allCompleted && $perencanaan->status === 'disetujui') {
            $perencanaan->status = 'selesai';
            $perencanaan->save();

            // Log the completion
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Perencanaan produksi ' . $perencanaan->nomor . ' telah selesai',
                'modul' => 'produksi',
                'id_referensi' => $perencanaan->id,
                'jenis_referensi' => 'perencanaan_produksi',
            ]);
        }
    }

    /**
     * Generate nomor work order
     */
    private function generateNomorWorkOrder()
    {
        $prefix = 'WO-';
        $date = now()->format('Ymd');

        $lastWorkOrder = DB::table('work_order')
            ->where('nomor', 'like', $prefix . $date . '-%')
            ->selectRaw('MAX(CAST(SUBSTRING(nomor, ' . (strlen($prefix . $date . '-') + 1) . ') AS UNSIGNED)) as last_num')
            ->first();

        $newNumberSuffix = '001';
        if ($lastWorkOrder && !is_null($lastWorkOrder->last_num)) {
            $newNumberSuffix = str_pad($lastWorkOrder->last_num + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . $date . '-' . $newNumberSuffix;
    }

    /**
     * Generate nomor pengambilan bahan baku
     */
    private function generateNomorPengambilanBahanBaku()
    {
        $prefix = 'PBB-';
        $date = now()->format('Ymd');

        $lastPengambilan = DB::table('pengambilan_bahan_baku')
            ->where('nomor', 'like', $prefix . $date . '-%')
            ->selectRaw('MAX(CAST(SUBSTRING(nomor, ' . (strlen($prefix . $date . '-') + 1) . ') AS UNSIGNED)) as last_num')
            ->first();

        $newNumberSuffix = '001';
        if ($lastPengambilan && !is_null($lastPengambilan->last_num)) {
            $newNumberSuffix = str_pad($lastPengambilan->last_num + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . $date . '-' . $newNumberSuffix;
    }

    /**
     * Mengambil material yang tersedia di gudang untuk dropdown AJAX
     */
    public function getAvailableMaterials(Request $request, $workOrderId)
    {
        try {
            Log::info("getAvailableMaterials called", [
                'workOrderId' => $workOrderId,
                'request' => $request->all()
            ]);

            $workOrder = WorkOrder::find($workOrderId);
            if (!$workOrder) {
                Log::error("Work Order not found", ['workOrderId' => $workOrderId]);
                return response()->json([
                    'items' => [],
                    'has_more' => false,
                    'total' => 0,
                    'error' => 'Work Order tidak ditemukan'
                ], 404);
            }

            $search = $request->get('q', '');
            $page = $request->get('page', 1);
            $perPage = 20;

            Log::info("Query parameters", [
                'search' => $search,
                'page' => $page,
                'perPage' => $perPage,
                'gudang_produksi_id' => $workOrder->gudang_produksi_id
            ]);

            // Mengambil produk yang memiliki stok di gudang produksi dengan query yang lebih sederhana
            $subQuery = DB::table('stok_produk')
                ->select('produk_id')
                ->where('gudang_id', $workOrder->gudang_produksi_id)
                ->where('jumlah', '>', 0)
                ->groupBy('produk_id');

            $query = Produk::select('id', 'nama', 'kode', 'satuan_id')
                ->with(['satuan:id,nama'])
                ->whereIn('id', $subQuery);

            // Filter pencarian
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('kode', 'like', "%{$search}%");
                });
            }

            $totalQuery = clone $query;
            $total = $totalQuery->count();

            $products = $query->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get();

            Log::info("Products found", ['count' => $products->count(), 'total' => $total]);

            $items = [];
            foreach ($products as $product) {
                // Ambil stok untuk produk ini
                $stok = DB::table('stok_produk')
                    ->where('produk_id', $product->id)
                    ->where('gudang_id', $workOrder->gudang_produksi_id)
                    ->sum('jumlah');

                if ($stok > 0) {
                    $items[] = [
                        'id' => $product->id,
                        'nama' => $product->nama,
                        'kode' => $product->kode ?? '',
                        'satuan_id' => $product->satuan_id,
                        'satuan_nama' => $product->satuan ? $product->satuan->nama : '',
                        'stok_tersedia' => floatval($stok),
                        'text' => $product->nama . ' (' . ($product->kode ?? 'No Code') . ')'
                    ];
                }
            }

            $hasMore = ($page * $perPage) < $total;

            Log::info("Response prepared", [
                'items_count' => count($items),
                'has_more' => $hasMore,
                'total' => count($items)
            ]);

            return response()->json([
                'items' => $items,
                'has_more' => $hasMore,
                'total' => count($items)
            ]);
        } catch (\Exception $e) {
            Log::error("Error in getAvailableMaterials", [
                'workOrderId' => $workOrderId,
                'request' => $request->all(),
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'items' => [],
                'has_more' => false,
                'total' => 0,
                'error' => 'Error: ' . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ], 500);
        }
    }

    /**
     * Mengambil stok material tertentu di gudang
     */
    public function getMaterialStock(Request $request, $workOrderId, $produkId)
    {
        try {
            $workOrder = WorkOrder::findOrFail($workOrderId);

            $stok = StokProduk::where('produk_id', $produkId)
                ->where('gudang_id', $workOrder->gudang_produksi_id)
                ->sum('jumlah');

            return response()->json([
                'success' => true,
                'stok' => floatval($stok)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'stok' => 0,
                'error' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
