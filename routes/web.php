<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
// Import Controller untuk Master Data
use App\Http\Controllers\MasterData\ProdukController;
use App\Http\Controllers\MasterData\KategoriProdukController;
use App\Http\Controllers\MasterData\GudangController;
use App\Http\Controllers\MasterData\CustomerController;
use App\Http\Controllers\MasterData\SupplierController;
use App\Http\Controllers\MasterData\SatuanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\HakAksesController;
use App\Http\Controllers\Pembelian\PermintaanPembelianController;
use App\Http\Controllers\Pembelian\PurchasingOrderController;
use App\Http\Controllers\hr_karyawan\DataKaryawanController;
use App\Http\Controllers\Inventaris\StokBarangController;
use App\Http\Controllers\Pembelian\PenerimaanBarangController;
use App\Http\Controllers\Pembelian\RiwayatTransaksiController;
use App\Http\Controllers\Pembelian\ReturPembelianController;
use App\Http\Controllers\Laporan\LaporanStokController;
use App\Http\Controllers\Keuangan\KasDanBankController;
use App\Http\Controllers\Keuangan\HutangUsahaController;
use App\Http\Controllers\Keuangan\PembayaranHutangController;
use App\Http\Controllers\Keuangan\PiutangUsahaController; // Import PiutangUsahaController
use App\Http\Controllers\Keuangan\PembayaranPiutangController; // Import PembayaranPiutangController
use App\Http\Controllers\Inventaris\TransferGudangController;
use App\Http\Controllers\Inventaris\PenyesuaianStokController;
use App\Http\Controllers\Produksi\BOMController;
use App\Http\Controllers\Penjualan\QuotationController;
use App\Http\Controllers\Penjualan\SalesOrderController;
use App\Http\Controllers\Penjualan\DeliveryOrderController;
use App\Http\Controllers\Penjualan\InvoiceController;
use App\Http\Controllers\Penjualan\ReturPenjualanController;
use App\Http\Controllers\Penjualan\NotaKreditController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Notifications routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/latest', [NotificationController::class, 'getLatest'])->name('notifications.latest');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Pengaturan Hak Akses
    Route::get('/pengaturan/hak-akses', [HakAksesController::class, 'index'])->name('pengaturan.hak-akses.index');
    Route::post('/pengaturan/hak-akses', [HakAksesController::class, 'update'])->name('pengaturan.hak-akses.update');

    // --- Master Data Group ---
    Route::prefix('master-data')->name('master.')->group(function () {


        // PRODUK
        Route::delete('produk/bulk-destroy', [ProdukController::class, 'bulkDestroy'])->name('produk.bulk-destroy.any');
        Route::get('produk/{produk}/get', [ProdukController::class, 'getById'])->name('produk.get');
        Route::get('/produk/generate-code', [ProdukController::class, 'generateCode'])
            ->name('produk.generate-code');
        Route::post('produk/import', [ProdukController::class, 'import'])->name('produk.import');
        Route::post('produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
        Route::get('produk/export', [ProdukController::class, 'export'])->name('produk.export');
        Route::get('produk/template', [ProdukController::class, 'downloadTemplate'])->name('produk.template');
        Route::resource('produk', ProdukController::class);

        // KATEGORI PRODUK
        Route::delete('kategori-produk/bulk-destroy', [KategoriProdukController::class, 'bulkDestroy'])->name('kategori-produk.bulk-destroy');
        Route::get('kategori-produk/{kategoriProduk}/get', [KategoriProdukController::class, 'getKategori'])->name('kategori-produk.get');
        Route::resource('kategori-produk', KategoriProdukController::class)->parameters([
            'kategori-produk' => 'kategoriProduk' // Ensure parameter name matches controller type hint
        ]);


        // CUSTOMER
        Route::post('pelanggan/bulk-destroy', [CustomerController::class, 'bulkDestroy'])->name('pelanggan.bulk-destroy.any');
        Route::get('pelanggan/{pelanggan}/get', [CustomerController::class, 'getById'])->name('pelanggan.get');
        Route::get('pelanggan/generate-code', [CustomerController::class, 'generateCode'])->name('pelanggan.generate-code');
        Route::get('pelanggan/export', [CustomerController::class, 'export'])->name('pelanggan.export');
        Route::post('pelanggan/import', [CustomerController::class, 'import'])->name('pelanggan.import');
        Route::resource('pelanggan', CustomerController::class);

        // SUPPLIER
        Route::post('supplier/bulk-destroy', [SupplierController::class, 'bulkDestroy'])->name('supplier.bulk-destroy.any');
        Route::get('supplier/{supplier}/get', [SupplierController::class, 'getById'])->name('supplier.get');
        Route::get('supplier/generate-code', [SupplierController::class, 'generateCode'])->name('supplier.generate-code');
        Route::post('supplier/import', [SupplierController::class, 'import'])->name('supplier.import');
        Route::get('supplier/export', [SupplierController::class, 'export'])->name('supplier.export');
        Route::resource('supplier', SupplierController::class);

        // GUDANG
        Route::delete('gudang/bulk-destroy', [GudangController::class, 'bulkDestroy'])->name('gudang.bulk-destroy');
        Route::get('gudang/{gudang}/get', [GudangController::class, 'getGudang'])->name('gudang.get');
        Route::resource('gudang', GudangController::class);

        // SATUAN
        Route::delete('satuan/bulk-destroy', [SatuanController::class, 'bulkDestroy'])->name('satuan.bulk-destroy');
        Route::get('satuan/{satuan}/get', [SatuanController::class, 'getSSatuan'])->name('satuan.get');
        Route::resource('satuan', SatuanController::class);
    });

    // --- INVENTARIS ----
    Route::prefix('inventaris')->name('inventaris.')->group(function () {
        // Stok Barang
        Route::resource('stok', StokBarangController::class);

        // Transfer Barang
        Route::post('transfer-gudang/{id}/proses', [TransferGudangController::class, 'prosesTransfer'])->name('transfer-gudang.proses');
        Route::post('transfer-gudang/{id}/selesai', [TransferGudangController::class, 'selesaikanTransfer'])->name('transfer-gudang.selesai');
        Route::get('transfer-gudang/get-stok', [TransferGudangController::class, 'getStokProduk'])->name('transfer-gudang.get-stok');
        Route::resource('transfer-gudang', TransferGudangController::class);

        // Penyesuaian Stok
        Route::post('penyesuaian-stok/{id}/proses', [PenyesuaianStokController::class, 'prosesPenyesuaian'])->name('penyesuaian-stok.proses');
        Route::get('penyesuaian-stok/get-stok', [PenyesuaianStokController::class, 'getStokProduk'])->name('penyesuaian-stok.get-stok');
        Route::get('gudang/{id}/produks', [PenyesuaianStokController::class, 'getProduksByGudang'])->name('penyesuaian-stok.get-produks');
        Route::get('penyesuaian-stok/{id}/pdf', [PenyesuaianStokController::class, 'printPdf'])->name('penyesuaian-stok.pdf');
        Route::get('penyesuaian-stok/pdf/draft', [PenyesuaianStokController::class, 'printDraftPdf'])->name('penyesuaian-stok.pdf.draft');
        Route::resource('penyesuaian-stok', PenyesuaianStokController::class);
    });

    Route::prefix('penjualan')->name('penjualan.')->group(function () {

        // Quotation routes
        Route::post('quotation/{quotation}/change-status', [QuotationController::class, 'changeStatus'])->name('quotation.changeStatus');
        Route::get('quotation/{id}/pdf', [QuotationController::class, 'exportPdf'])->name('quotation.pdf');
        Route::get('api/quotations', [QuotationController::class, 'getQuotationsForSelect'])->name('api.quotations');
        Route::resource('quotation', QuotationController::class);

        // Sales Order routes
        Route::post('sales-order/{sales_order}/change-status', [SalesOrderController::class, 'changeStatus'])->name('sales-order.changeStatus');
        Route::get('sales-order/{id}/pdf', [SalesOrderController::class, 'exportPdf'])->name('sales-order.pdf');
        Route::get('sales-order/get-quotation-data/{id}', [SalesOrderController::class, 'getQuotationData'])->name('sales-order.get-quotation-data');
        Route::resource('sales-order', SalesOrderController::class);

        // Invoice routes
        Route::get('invoice/get-sales-order/{id}', [InvoiceController::class, 'getSalesOrderData'])->name('invoice.get-sales-order');
        Route::get('invoice/{invoice}/print', [InvoiceController::class, 'print'])->name('invoice.print');
        Route::get('sales-order/{salesOrder}/generate-invoice', [InvoiceController::class, 'generateFromSalesOrder'])->name('sales-order.generate-invoice');
        Route::resource('invoice', InvoiceController::class);

        // Delivery Order routes
        Route::post('delivery-order/{id}/proses', [DeliveryOrderController::class, 'prosesDelivery'])->name('delivery-order.proses');
        Route::post('delivery-order/{id}/selesaikan', [DeliveryOrderController::class, 'selesaikanDelivery'])->name('delivery-order.selesaikan');
        Route::post('delivery-order/{id}/selesai', [DeliveryOrderController::class, 'selesaikanDelivery'])->name('delivery-order.selesai');
        Route::post('delivery-order/{id}/batalkan', [DeliveryOrderController::class, 'batalkanDelivery'])->name('delivery-order.batalkan');
        Route::get('delivery-order/get-sales-order-data/{id}', [DeliveryOrderController::class, 'getSalesOrderData'])->name('delivery-order.get-sales-order-data');
        Route::get('delivery-order/get-stock-info', [DeliveryOrderController::class, 'getStockInformation'])->name('delivery-order.get-stock-info');
        Route::get('delivery-orders/ajax/table', [DeliveryOrderController::class, 'table'])->name('delivery-order.table');
        Route::get('delivery-order/{id}/print', [DeliveryOrderController::class, 'print'])->name('delivery-order.print');
        Route::resource('delivery-order', DeliveryOrderController::class);

        // Retur Penjualan routes
        Route::post('retur/{id}/proses', [ReturPenjualanController::class, 'prosesRetur'])->name('retur.proses');
        Route::post('retur/{id}/selesai', [ReturPenjualanController::class, 'selesaikanRetur'])->name('retur.selesai');
        Route::post('retur/{id}/kirim-pengganti', [ReturPenjualanController::class, 'prosesKirimPengganti'])->name('retur.kirim-pengganti');
        Route::get('retur/{id}/pdf', [ReturPenjualanController::class, 'exportPdf'])->name('retur.pdf');
        Route::get('retur/get-sales-orders', [ReturPenjualanController::class, 'getSalesOrders'])->name('retur.get-sales-orders');
        Route::get('retur/get-sales-order-details', [ReturPenjualanController::class, 'getSalesOrderDetails'])->name('retur.get-sales-order-details');
        Route::get('retur/analyze', [ReturPenjualanController::class, 'analyzeReturns'])->name('retur.analyze');
        Route::post('retur/{id}/submit-approval', [ReturPenjualanController::class, 'submitForApproval'])->name('retur.submit-approval');
        Route::post('retur/{id}/approve', [ReturPenjualanController::class, 'approveReturn'])->name('retur.approve');
        Route::post('retur/{id}/reject', [ReturPenjualanController::class, 'rejectReturn'])->name('retur.reject');
        Route::get('retur/{id}/quality-control', [ReturPenjualanController::class, 'showQualityControlForm'])->name('retur.quality-control');
        Route::post('retur/{id}/quality-control', [ReturPenjualanController::class, 'processQualityControl'])->name('retur.process-quality-control');
        Route::get('retur/{id}/quality-control-detail', [ReturPenjualanController::class, 'showQualityControlDetail'])->name('retur.quality-control-detail');
        Route::get('retur/{id}/create-credit-note', [ReturPenjualanController::class, 'createCreditNote'])->name('retur.create-credit-note');
        Route::get('retur/qc-report', [ReturPenjualanController::class, 'qcReport'])->name('retur.qc-report');
        Route::get('retur/{id}/kirim-barang-pengganti', [ReturPenjualanController::class, 'showTerimaBarangPengganti'])->name('retur.kirim-barang-pengganti');
        Route::post('retur/{id}/kirim-barang-pengganti', [ReturPenjualanController::class, 'terimaBarangPengganti'])->name('retur.kirim-barang-pengganti');
        Route::get('retur/get-stok', [ReturPenjualanController::class, 'getStokProduk'])->name('retur.get-stok');
        Route::resource('retur', ReturPenjualanController::class);

        // Nota Kredit routes
        Route::post('nota-kredit/{notaKredit}/finalize', [NotaKreditController::class, 'finalize'])->name('nota-kredit.finalize');

        // Nota Kredit (Credit Note) routes
        Route::get('nota-kredit/{id}/pdf', [NotaKreditController::class, 'exportPdf'])->name('nota-kredit.pdf');
        Route::post('nota-kredit/{notaKredit}/complete', [NotaKreditController::class, 'completeNotaKredit'])->name('nota-kredit.complete');
        Route::resource('nota-kredit', NotaKreditController::class);
    });

    // API route for product details (used in Sales Order form)
    Route::get('/api/products/{id}', [ProdukController::class, 'apiGetById'])->name('api.products.show');

    // API route for customer details (used in Sales Order form)
    Route::get('/api/customers/{id}', function ($id) {
        $customer = \App\Models\Customer::findOrFail($id);
        return response()->json([
            'id' => $customer->id,
            'nama' => $customer->nama,
            'company' => $customer->company,
            'alamat_utama' => $customer->alamat_utama,
            'alamat_pengiriman' => $customer->alamat_pengiriman
        ]);
    })->name('api.customers.show');

    // --- PEMBELIAN ---
    Route::prefix('pembelian')->name('pembelian.')->group(function () {
        // Permintaan Pembelian
        Route::put('permintaan-pembelian/{permintaan_pembelian}/change-status', [PermintaanPembelianController::class, 'changeStatus'])->name('permintaan-pembelian.change-status');
        Route::put('permintaan-pembelian/{permintaan_pembelian}/approve', [PermintaanPembelianController::class, 'approve'])->name('permintaan-pembelian.approve');
        Route::put('permintaan-pembelian/{permintaan_pembelian}/reject', [PermintaanPembelianController::class, 'reject'])->name('permintaan-pembelian.reject');
        Route::get('permintaan-pembelian/{id}/pdf', [PermintaanPembelianController::class, 'exportPdf'])->name('permintaan-pembelian.pdf');
        Route::resource('permintaan-pembelian', PermintaanPembelianController::class);

        // PURCHASING ORDER
        Route::put('purchasing-order/{purchasing_order}/change-status', [PurchasingOrderController::class, 'changeStatus'])->name('purchasing-order.change-status');
        Route::get('purchasing-order/{id}/pdf', [PurchasingOrderController::class, 'exportPdf'])->name('purchasing-order.pdf');
        Route::resource('purchasing-order', PurchasingOrderController::class);
        Route::get('purchase-order/supplier-produk', [PurchasingOrderController::class, 'getSupplierProduk'])->name('pembelian.purchasing-order.supplier-produk');
        Route::get('purchase-order/pr-items', [PurchasingOrderController::class, 'getPurchaseRequestItems'])->name('pembelian.purchasing-order.pr-items');

        //PENERIMAAN BARANG
        Route::resource('penerimaan-barang', PenerimaanBarangController::class);

        // RETUR PEMBELIAN
        Route::post('retur-pembelian/{id}/proses', [ReturPembelianController::class, 'prosesRetur'])->name('retur-pembelian.proses');
        Route::post('retur-pembelian/{id}/selesai', [ReturPembelianController::class, 'selesaikanRetur'])->name('retur-pembelian.selesai');
        Route::get('retur-pembelian/{id}/terima-barang-pengganti', [ReturPembelianController::class, 'showTerimaBarangPengganti'])->name('retur-pembelian.terima-barang-pengganti');
        Route::post('retur-pembelian/{id}/terima-barang-pengganti', [ReturPembelianController::class, 'terimaBarangPengganti'])->name('retur-pembelian.proses-terima-barang-pengganti');
        Route::get('retur-pembelian/{id}/create-refund', [ReturPembelianController::class, 'createRefund'])->name('retur-pembelian.create-refund');
        Route::get('retur-pembelian/{id}/pdf', [ReturPembelianController::class, 'exportPdf'])->name('retur-pembelian.pdf');
        Route::get('retur-pembelian/get-purchase-orders', [ReturPembelianController::class, 'getPurchaseOrders'])->name('retur-pembelian.get-purchase-orders');
        Route::get('retur-pembelian/get-purchase-order-items', [ReturPembelianController::class, 'getPurchaseOrderItems'])->name('retur-pembelian.get-purchase-order-items');
        Route::resource('retur-pembelian', ReturPembelianController::class);

        //RIWAYAT TRANSAKSI
        Route::get('riwayat-transaksi', [RiwayatTransaksiController::class, 'index'])->name('riwayat-transaksi.index');
        Route::get('riwayat-transaksi/{id}', [RiwayatTransaksiController::class, 'show'])->name('riwayat-transaksi.show');
    });

    Route::prefix('produksi')->name('produksi.')->group(function () {
        // Bill of Material (BOM) Routes
        Route::get('bom/{id}/get', [BOMController::class, 'getById'])->name('bom.get');
        Route::get('bom-data', [BOMController::class, 'data'])->name('bom.data');
        Route::post('bom/{id}/components', [BOMController::class, 'addComponent'])->name('bom.add-component');
        Route::put('bom-component/{id}', [BOMController::class, 'updateComponent'])->name('bom.update-component');
        Route::delete('bom-component/{id}', [BOMController::class, 'deleteComponent'])->name('bom.delete-component');
        Route::get('bom-component-unit/{id}', [BOMController::class, 'getComponentUnit'])->name('bom.component-unit');
        Route::resource('bom', BOMController::class);
    });

    // -- HR & Karyawan --
    Route::prefix('hr')->name('hr.')->group(function () {
        Route::delete('karyawan/bulk-destroy', [DataKaryawanController::class, 'bulkDestroy'])->name('karyawan.bulk-destroy');
        Route::resource('karyawan', DataKaryawanController::class);
        // Add other HR routes here if needed
    });

    // -- Keuangan --
    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        // Rutas para Kas dan Bank
        Route::get('/kas-dan-bank', [KasDanBankController::class, 'index'])->name('kas-dan-bank.index');
        Route::get('/kas-dan-bank/kas/{id}', [KasDanBankController::class, 'detailKas'])->name('kas-dan-bank.kas');
        Route::get('/kas-dan-bank/rekening/{id}', [KasDanBankController::class, 'detailRekening'])->name('kas-dan-bank.rekening');

        // Kas CRUD
        Route::post('/kas-dan-bank/kas', [KasDanBankController::class, 'storeKas'])->name('kas-dan-bank.store-kas');
        Route::put('/kas-dan-bank/kas/{id}', [KasDanBankController::class, 'updateKas'])->name('kas-dan-bank.update-kas');
        Route::delete('/kas-dan-bank/kas/{id}', [KasDanBankController::class, 'destroyKas'])->name('kas-dan-bank.destroy-kas');

        // Rekening Bank CRUD
        Route::post('/kas-dan-bank/rekening', [KasDanBankController::class, 'storeRekening'])->name('kas-dan-bank.store-rekening');
        Route::put('/kas-dan-bank/rekening/{id}', [KasDanBankController::class, 'updateRekening'])->name('kas-dan-bank.update-rekening');
        Route::delete('/kas-dan-bank/rekening/{id}', [KasDanBankController::class, 'destroyRekening'])->name('kas-dan-bank.destroy-rekening');

        // Hutang Usaha Routes
        Route::get('hutang-usaha', [HutangUsahaController::class, 'index'])->name('hutang-usaha.index');
        Route::get('hutang-usaha/show/{id}', [HutangUsahaController::class, 'show'])->name('hutang-usaha.show');
        Route::get('hutang-usaha/history/{id}', [HutangUsahaController::class, 'history'])->name('hutang-usaha.history');
        Route::get('hutang-usaha/print/{id}', [HutangUsahaController::class, 'print'])->name('hutang-usaha.print');
        Route::get('hutang-usaha/export', [HutangUsahaController::class, 'export'])->name('hutang-usaha.export');
        Route::get('hutang-usaha/pdf', [HutangUsahaController::class, 'generatePdf'])->name('hutang-usaha.pdf');

        // Piutang Usaha Routes
        Route::get('piutang-usaha', [PiutangUsahaController::class, 'index'])->name('piutang-usaha.index');
        Route::get('piutang-usaha/show/{id}', [PiutangUsahaController::class, 'show'])->name('piutang-usaha.show');
        Route::get('piutang-usaha/history/{id}', [PiutangUsahaController::class, 'history'])->name('piutang-usaha.history');
        Route::get('piutang-usaha/print/{id}', [PiutangUsahaController::class, 'print'])->name('piutang-usaha.print');
        Route::get('piutang-usaha/export', [PiutangUsahaController::class, 'export'])->name('piutang-usaha.export');
        Route::get('piutang-usaha/pdf', [PiutangUsahaController::class, 'generatePdf'])->name('piutang-usaha.pdf');

        // Pembayaran Piutang Routes
        Route::get('pembayaran-piutang/print/{id}', [PembayaranPiutangController::class, 'print'])->name('pembayaran-piutang.print');
        Route::resource('pembayaran-piutang', PembayaranPiutangController::class);
        Route::get('customers/{customer}/invoices', [PembayaranPiutangController::class, 'getCustomerInvoices'])->name('customers.invoices'); // New route

        // Pembayaran Hutang Routes
        Route::get('pembayaran-hutang/print/{id}', [PembayaranHutangController::class, 'print'])->name('pembayaran-hutang.print');
        Route::resource('pembayaran-hutang', PembayaranHutangController::class);

        // Pengembalian Dana Routes
        Route::get('pengembalian-dana/print/{id}', [\App\Http\Controllers\Keuangan\PengembalianDanaController::class, 'print'])->name('pengembalian-dana.print');
        Route::get('pengembalian-dana/get-po-data', [\App\Http\Controllers\Keuangan\PengembalianDanaController::class, 'getPurchaseOrderData'])->name('pengembalian-dana.get-po-data');
        Route::get('pengembalian-dana/data', [\App\Http\Controllers\Keuangan\PengembalianDanaController::class, 'data'])->name('pengembalian-dana.data');
        Route::resource('pengembalian-dana', \App\Http\Controllers\Keuangan\PengembalianDanaController::class);
    });

    // -- Laporan --
    Route::prefix('laporan')->name('laporan.')->group(function () {
        // Stock Report Routes
        Route::get('stok', [LaporanStokController::class, 'index'])->name('stok.index');
        Route::get('stok/data', [LaporanStokController::class, 'getData'])->name('stok.data');
        Route::get('stok/export/excel', [LaporanStokController::class, 'exportExcel'])->name('stok.export.excel');
        Route::get('stok/export/pdf', [LaporanStokController::class, 'exportPdf'])->name('stok.export.pdf');
        Route::get('stok/detail/{produk_id}/{gudang_id?}', [LaporanStokController::class, 'detail'])->name('stok.detail');
    });
});

require __DIR__ . '/auth.php';