<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Gudang;
use App\Models\LogAktivitas;
use App\Models\Satuan;
use App\Models\KategoriProduk;
use App\Models\WorkOrder;
use App\Models\SalesOrder;
use App\Models\PurchaseOrder;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Karyawan;
use App\Models\BillOfMaterial;
use App\Models\BillOfMaterialDetail;
use App\Models\Quotation;
use App\Models\DeliveryOrder;
use App\Models\Invoice;
use App\Models\PenerimaanBarang;
use App\Models\PurchaseRequest;
use App\Models\TransferBarang;
use App\Models\StokProduk;
use App\Models\WorkOrderMaterial;
use App\Models\Absensi;
use App\Models\Cuti;
use App\Models\Department;
use App\Models\Jabatan;
use App\Models\JurnalUmum;
use App\Models\AkunAkuntansi;
use App\Models\Kas;
use App\Models\TransaksiKas;
use App\Models\LaporanPajak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama sistem ERP Sinar Surya Semestaraya
     */
    public function index()
    {
        // === Modul Inventory ===
        $totalProduk = Produk::count();
        $produkAktif = Produk::where('is_active', true)->count();

        // Produk dengan stok dibawah minimum (untuk alert restock)
        $produkStokMinimum = DB::table('produk')
            ->join('stok_produk', 'produk.id', '=', 'stok_produk.produk_id')
            ->join('kategori_produk', 'produk.kategori_id', '=', 'kategori_produk.id')
            ->join('satuan', 'produk.satuan_id', '=', 'satuan.id')
            ->select(
                'produk.*',
                'kategori_produk.nama as kategori_nama',
                'satuan.nama as satuan_nama',
                'stok_produk.jumlah as stok'
            )
            ->whereRaw('stok_produk.jumlah < produk.stok_minimum')
            ->distinct('produk.id')
            ->get();
        $countStokMinimum = $produkStokMinimum->count();

        // Multi-gudang statistics
        $gudangList = Gudang::withCount('stok')->get();
        $totalGudang = $gudangList->count();

        // Produk pompa, aksesoris, sparepart, safety gear
        $produkPerKategori = KategoriProduk::withCount('produk')
            ->where('is_active', true) // Only include active categories
            ->having('produk_count', '>', 0) // Only include categories with products
            ->get()
            ->map(function ($kategori) {
                return [
                    'nama' => $kategori->nama,
                    'total' => $kategori->produk_count
                ];
            });

        // If there's no data, provide a fallback for development/testing
        if ($produkPerKategori->isEmpty()) {
            $produkPerKategori = collect([
                ['nama' => 'Pompa', 'total' => 45],
                ['nama' => 'Pipa', 'total' => 30],
                ['nama' => 'Valve', 'total' => 20],
                ['nama' => 'Aksesoris', 'total' => 15]
            ]);
        }

        // Top selling products - disesuaikan dengan struktur tabel InvoiceDetail
        $produkTerlaris = Produk::select('produk.id', 'produk.nama', 'produk.kode', 'produk.kategori_id', 'produk.satuan_id', DB::raw('SUM(invoice_detail.quantity) as total_terjual'))
            ->leftJoin('invoice_detail', 'produk.id', '=', 'invoice_detail.produk_id')
            ->with(['kategori', 'satuan'])
            ->groupBy('produk.id', 'produk.nama', 'produk.kode', 'produk.kategori_id', 'produk.satuan_id')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        // Aktivitas terbaru sistem (log) 
        $aktivitasTerbaru = LogAktivitas::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // === Modul Penjualan (Sales) ===
        // Ringkasan penjualan 6 bulan terakhir (chart data)
        $penjualanPerBulan = $this->getPenjualanBulanan();

        // Quotation & Sales Order terbaru
        $quotationTerbaru = Quotation::with('customer')
            ->latest()
            ->limit(5)
            ->get();

        $salesOrderTerbaru = SalesOrder::with('customer', 'details.produk')
            ->latest()
            ->limit(5)
            ->get();

        // Status pengiriman barang (Delivery Order)
        $deliveryPending = DeliveryOrder::where('status', 'draft')->count();
        $deliveryOnProgress = DeliveryOrder::where('status', 'dikirim')->count();
        $deliveryComplete = DeliveryOrder::where('status', 'diterima')->count();

        // Faktur terbaru (Invoice)
        $fakturTerbaru = Invoice::with('salesOrder.customer')
            ->latest()
            ->limit(5)
            ->get();

        // === Modul Pembelian (Purchasing) ===
        // Purchase Order & GRN terbaru
        $purchaseOrderTerbaru = PurchaseOrder::with('supplier', 'details.produk')
            ->latest()
            ->limit(5)
            ->get();

        $goodsReceiptTerbaru = PenerimaanBarang::with('purchaseOrder', 'supplier', 'details.produk')
            ->latest()
            ->limit(5)
            ->get();

        // Permintaan Pembelian (PR) status
        $prPending = PurchaseRequest::where('status', 'pending')->count();
        $prApproved = PurchaseRequest::whereIn('status', ['approved', 'ordered'])->count();

        // === Modul Produksi ===
        // Work Order statistics
        $workOrderPending = WorkOrder::where('status', 'direncanakan')->count();
        $workOrderProgress = WorkOrder::where('status', 'berjalan')->count();
        $workOrderComplete = WorkOrder::where('status', 'selesai')->count();

        // Bill of Materials (BoM) untuk produk rakitan (pompa, unit lengkap)
        $componentsUsage = BillOfMaterialDetail::select('komponen_id', DB::raw('SUM(quantity) as total_used'))
            ->with('komponen')
            ->groupBy('komponen_id')
            ->orderBy('total_used', 'desc')
            ->limit(5)
            ->get();

        // Production gudang specific inventory
        $gudangProduksi = Gudang::where('jenis', 'produksi')->first();
        $stokProduksi = $gudangProduksi ? StokProduk::where('gudang_id', $gudangProduksi->id)
            ->with('produk')->get() : collect();

        // === Modul Keuangan ===
        // Ringkasan piutang & hutang
        $totalPiutang = Invoice::where('status', 'belum_bayar')->sum('total');
        $totalHutang = PurchaseOrder::where('status_pembayaran', 'belum_bayar')->sum('total');

        // Multi-termin pembayaran jatuh tempo - sesuaikan dengan struktur Invoice
        $terminJatuhTempo = Invoice::where('status', 'belum_bayar')
            ->where('jatuh_tempo', '<=', Carbon::now()->addDays(7))
            ->with('salesOrder.customer')
            ->get();

        // === Modul Karyawan (HR) ===
        // Statistik kehadiran hari ini
        $absensiHariIni = Absensi::whereDate('tanggal', Carbon::today())
            ->count();
        $totalKaryawan = Karyawan::count();
        $persentaseKehadiran = $totalKaryawan > 0 ? ($absensiHariIni / $totalKaryawan) * 100 : 0;

        // Cuti & izin pending
        $cutiPending = Cuti::where('status', 'diajukan')->count();


        return view('/dashboard', compact(
            // Inventory
            'totalProduk',
            'produkAktif',
            'countStokMinimum',
            'produkStokMinimum',
            'produkTerlaris',
            'produkPerKategori',
            'totalGudang',
            'gudangList',
            'aktivitasTerbaru',
            // Sales
            'penjualanPerBulan',
            'quotationTerbaru',
            'salesOrderTerbaru',
            'fakturTerbaru',
            'deliveryPending',
            'deliveryOnProgress',
            'deliveryComplete',
            // Purchasing
            'purchaseOrderTerbaru',
            'goodsReceiptTerbaru',
            'prPending',
            'prApproved',
            // Production
            'workOrderPending',
            'workOrderProgress',
            'workOrderComplete',
            'componentsUsage',
            'stokProduksi',
            // Finance
            'totalPiutang',
            'totalHutang',
            'terminJatuhTempo',
            // HR
            'absensiHariIni',
            'totalKaryawan',
            'persentaseKehadiran',
            'cutiPending'
        ));
    }

    /**
     * Mendapatkan data penjualan bulanan untuk 6 bulan terakhir
     */
    private function getPenjualanBulanan()
    {
        $result = [];

        // Dapatkan data 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $startDate = $month->startOfMonth()->format('Y-m-d');
            $endDate = $month->endOfMonth()->format('Y-m-d');

            // Sesuaikan dengan model Invoice yang benar
            $total = Invoice::whereBetween('tanggal', [$startDate, $endDate])
                ->sum('total');

            // Jika database belum ada data, buat dummy data untuk preview
            // Remove this in production
            if ($total == 0) {
                $total = rand(5000000, 50000000);
            }

            $result[] = [
                'bulan' => $month->format('M Y'),
                'total' => $total
            ];
        }

        return $result;
    }

    /**
     * API endpoint untuk data chart
     */
    public function chartData()
    {
        // Data untuk berbagai chart di dashboard
        $data = [
            'penjualan' => $this->getPenjualanBulanan(),
            'kategori' => KategoriProduk::withCount('produk')->get(),
            'workOrders' => [
                'direncanakan' => WorkOrder::where('status', 'direncanakan')->count(),
                'berjalan' => WorkOrder::where('status', 'berjalan')->count(),
                'selesai' => WorkOrder::where('status', 'selesai')->count()
            ],
            'stokStatus' => [
                'normal' => DB::table('produk')
                    ->join('stok_produk', 'produk.id', '=', 'stok_produk.produk_id')
                    ->whereRaw('stok_produk.jumlah >= produk.stok_minimum')
                    ->distinct('produk.id')
                    ->count('produk.id'),
                'warning' => DB::table('produk')
                    ->join('stok_produk', 'produk.id', '=', 'stok_produk.produk_id')
                    ->whereRaw('stok_produk.jumlah < produk.stok_minimum AND stok_produk.jumlah > 0')
                    ->distinct('produk.id')
                    ->count('produk.id'),
                'empty' => DB::table('produk')
                    ->join('stok_produk', 'produk.id', '=', 'stok_produk.produk_id')
                    ->where('stok_produk.jumlah', 0)
                    ->distinct('produk.id')
                    ->count('produk.id')
            ],
            // Tambah data untuk transfer stok antar gudang
            'transferStok' => TransferBarang::select(DB::raw('COUNT(*) as total, status'))
                ->groupBy('status')
                ->get()
        ];

        return response()->json($data);
    }

    /**
     * Dashboard per modul (route: /dashboard/module)
     */
    public function module($module)
    {
        switch ($module) {
            case 'inventory':
                return $this->inventoryDashboard();
            case 'sales':
                return $this->salesDashboard();
            case 'purchasing':
                return $this->purchasingDashboard();
            case 'production':
                return $this->productionDashboard();
            case 'hr':
                return $this->hrDashboard();
            case 'finance':
                return $this->financeDashboard();
            default:
                return redirect()->route('dashboard');
        }
    }

    /**
     * Dashboard khusus keuangan
     */
    private function financeDashboard()
    {
        // Piutang & hutang berdasarkan umur (aging) - sesuaikan dengan Invoice
        $piutangAging = [
            '0-30' => Invoice::where('status', 'belum_bayar')
                ->whereRaw('DATEDIFF(NOW(), tanggal) <= 30')
                ->sum('total'),
            '31-60' => Invoice::where('status', 'belum_bayar')
                ->whereRaw('DATEDIFF(NOW(), tanggal) BETWEEN 31 AND 60')
                ->sum('total'),
            '61-90' => Invoice::where('status', 'belum_bayar')
                ->whereRaw('DATEDIFF(NOW(), tanggal) BETWEEN 61 AND 90')
                ->sum('total'),
            '>90' => Invoice::where('status', 'belum_bayar')
                ->whereRaw('DATEDIFF(NOW(), tanggal) > 90')
                ->sum('total'),
        ];

        // Faktur yang akan jatuh tempo minggu ini - sesuaikan dengan Invoice
        $fakturJatuhTempo = Invoice::where('status', 'belum_bayar')
            ->whereBetween('jatuh_tempo', [now(), now()->addDays(7)])
            ->with('salesOrder.customer')
            ->get();

        // Laporan kas & bank
        $totalKas = Kas::sum('saldo');

        // Jurnal entries terbaru
        $jurnalTerbaru = JurnalUmum::with(['akun', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        // Transaksi kas terakhir
        $transaksiTerakhir = TransaksiKas::with('user', 'kas')
            ->latest()
            ->limit(10)
            ->get();

        // Akun Akuntansi summary
        $akunSummary = AkunAkuntansi::where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        // Laporan Pajak PPN bulan ini

        return view('modules.finance.dashboard', compact(
            'piutangAging',
            'fakturJatuhTempo',
            'ppnBulanIni',
            'totalKas',
            'jurnalTerbaru',
            'transaksiTerakhir',
            'akunSummary'
        ));
    }

    /**
     * Dashboard khusus Inventaris
     */
    private function inventoryDashboard()
    {
        // Stok per gudang
        $gudangStok = Gudang::with(['stok' => function ($q) {
            $q->with('produk');
        }])->get();

        // Produk dengan stok minimum
        $stokMinimum = DB::table('produk')
            ->join('stok_produk', 'produk.id', '=', 'stok_produk.produk_id')
            ->join('kategori_produk', 'produk.kategori_id', '=', 'kategori_produk.id')
            ->join('satuan', 'produk.satuan_id', '=', 'satuan.id')
            ->select(
                'produk.*',
                'kategori_produk.nama as kategori_nama',
                'satuan.nama as satuan_nama',
                'stok_produk.jumlah as stok'
            )
            ->whereRaw('stok_produk.jumlah < produk.stok_minimum')
            ->distinct('produk.id')
            ->get();

        // Transaksi transfer antar gudang
        $transferTerbaru = TransferBarang::with(['gudangAsal', 'gudangTujuan', 'user', 'details.produk'])
            ->latest()
            ->limit(10)
            ->get();

        // Penyesuaian stok terbaru
        $penyesuaianStok = \App\Models\PenyesuaianStok::with(['gudang', 'user', 'details.produk'])
            ->latest()
            ->limit(10)
            ->get();

        // Stok per kategori
        $stokPerKategori = DB::table('kategori_produk')
            ->leftJoin('produk', 'kategori_produk.id', '=', 'produk.kategori_id')
            ->leftJoin('stok_produk', 'produk.id', '=', 'stok_produk.produk_id')
            ->select('kategori_produk.nama', DB::raw('SUM(stok_produk.jumlah) as total_stok'))
            ->groupBy('kategori_produk.nama')
            ->get();

        return view('modules.inventory.dashboard', compact(
            'gudangStok',
            'stokMinimum',
            'transferTerbaru',
            'penyesuaianStok',
            'stokPerKategori'
        ));
    }

    /**
     * Dashboard khusus Produksi
     */
    private function productionDashboard()
    {
        // Work Orders aktif
        $workOrderAktif = WorkOrder::where('status', 'berjalan')
            ->with(['produk', 'bom', 'materials.produk', 'gudangProduksi', 'gudangHasil'])
            ->get();

        // Material yang sering digunakan dalam produksi
        $materialTerpakai = WorkOrderMaterial::select('produk_id', DB::raw('SUM(quantity) as total_used'))
            ->with('produk')
            ->groupBy('produk_id')
            ->orderBy('total_used', 'desc')
            ->limit(10)
            ->get();

        // Bill of Materials aktif
        $bomAktif = BillOfMaterial::where('is_active', true)
            ->with(['produk', 'details.komponen'])
            ->get();

        // Stok di gudang produksi
        $gudangProduksi = Gudang::where('tipe', 'produksi')->first();
        $stokProduksi = $gudangProduksi ? StokProduk::where('gudang_id', $gudangProduksi->id)
            ->with('produk')
            ->get() : collect();

        // Work Orders berdasarkan status
        $workOrdersStatus = WorkOrder::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        return view('modules.production.dashboard', compact(
            'workOrderAktif',
            'materialTerpakai',
            'bomAktif',
            'stokProduksi',
            'workOrdersStatus'
        ));
    }

    /**
     * Dashboard khusus HR (Kepegawaian)
     */
    private function hrDashboard()
    {
        // Karyawan per departemen
        $karyawanPerDept = Department::withCount('karyawan')
            ->get();

        // Karyawan per jabatan
        $karyawanPerJabatan = Jabatan::withCount('karyawan')
            ->get();

        // Absensi hari ini
        $absensiHariIni = Absensi::whereDate('tanggal', now())
            ->with('karyawan')
            ->get();

        // Pengajuan cuti pending
        $cutiPending = Cuti::where('status', 'diajukan')
            ->with('karyawan')
            ->get();

        // Data kehadiran 30 hari terakhir
        $kehadiranBulanan = DB::table('absensi')
            ->select(DB::raw('DATE(tanggal) as tanggal, COUNT(*) as total'))
            ->whereRaw('tanggal >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)')
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->get();

        // Data penggajian bulan ini
        $penggajianBulanIni = \App\Models\Penggajian::whereMonth('periode', now()->month)
            ->whereYear('periode', now()->year)
            ->with('karyawan')
            ->get();

        return view('modules.hr.dashboard', compact(
            'karyawanPerDept',
            'karyawanPerJabatan',
            'absensiHariIni',
            'cutiPending',
            'kehadiranBulanan',
            'penggajianBulanIni'
        ));
    }

    /**
     * Dashboard khusus Sales
     */
    private function salesDashboard()
    {
        // Sales Order terbaru
        $salesOrderTerbaru = SalesOrder::with(['customer', 'details.produk'])
            ->latest()
            ->limit(10)
            ->get();

        // Quotation conversion stats
        $totalQuotation = Quotation::count();
        $convertedQuotation = Quotation::whereHas('salesOrder')->count();
        $conversionRate = $totalQuotation > 0 ? ($convertedQuotation / $totalQuotation) * 100 : 0;

        // Delivery orders by status
        $deliveryOrders = DeliveryOrder::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        // Top customers
        $topCustomers = Customer::select('customer.id', 'customer.nama', 'customer.email', 'customer.telepon', DB::raw('SUM(invoice.total) as total_pembelian'))
            ->leftJoin('sales_order', 'customer.id', '=', 'sales_order.customer_id')
            ->leftJoin('invoice', 'sales_order.id', '=', 'invoice.sales_order_id')
            ->groupBy('customer.id', 'customer.nama', 'customer.email', 'customer.telepon')
            ->orderBy('total_pembelian', 'desc')
            ->limit(5)
            ->get();

        // Penjualan per kategori produk (chart data)
        $penjualanPerKategori = DB::table('invoice_detail')
            ->join('produk', 'invoice_detail.produk_id', '=', 'produk.id')
            ->join('kategori_produk', 'produk.kategori_id', '=', 'kategori_produk.id')
            ->select('kategori_produk.nama', DB::raw('SUM(invoice_detail.subtotal) as total'))
            ->groupBy('kategori_produk.nama')
            ->get();

        // Invoice belum lunas
        $invoiceBelumLunas = Invoice::where('status', 'belum_bayar')
            ->with('salesOrder.customer')
            ->orderBy('jatuh_tempo')
            ->get();

        return view('modules.sales.dashboard', compact(
            'salesOrderTerbaru',
            'totalQuotation',
            'convertedQuotation',
            'conversionRate',
            'deliveryOrders',
            'topCustomers',
            'penjualanPerKategori',
            'invoiceBelumLunas'
        ));
    }

    /**
     * Dashboard khusus Purchasing
     */
    private function purchasingDashboard()
    {
        // Purchase Order terbaru
        $purchaseOrderTerbaru = PurchaseOrder::with(['supplier', 'details.produk'])
            ->latest()
            ->limit(10)
            ->get();

        // Purchase Request stats
        $purchaseRequestStats = PurchaseRequest::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        // Top suppliers
        $topSuppliers = Supplier::select('supplier.id', 'supplier.nama', 'supplier.email', 'supplier.telepon', DB::raw('SUM(purchase_order.total) as total_pembelian'))
            ->leftJoin('purchase_order', 'supplier.id', '=', 'purchase_order.supplier_id')
            ->groupBy('supplier.id', 'supplier.nama', 'supplier.email', 'supplier.telepon')
            ->orderBy('total_pembelian', 'desc')
            ->limit(5)
            ->get();

        // Penerimaan barang terbaru
        $penerimaanTerbaru = PenerimaanBarang::with(['purchaseOrder', 'supplier', 'details.produk'])
            ->latest()
            ->limit(10)
            ->get();

        // Retur pembelian terbaru
        $returTerbaru = \App\Models\ReturPembelian::with(['supplier', 'details.produk'])
            ->latest()
            ->limit(5)
            ->get();

        // Purchase Order belum lunas
        $poBelumLunas = PurchaseOrder::where('status_pembayaran', 'belum_bayar')
            ->with('supplier')
            ->orderBy('tanggal')
            ->get();

        return view('modules.purchasing.dashboard', compact(
            'purchaseOrderTerbaru',
            'purchaseRequestStats',
            'topSuppliers',
            'penerimaanTerbaru',
            'returTerbaru',
            'poBelumLunas'
        ));
    }
}