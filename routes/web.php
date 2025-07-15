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
use App\Http\Controllers\ManagementPenggunaController;
use App\Http\Controllers\Pembelian\PermintaanPembelianController;
use App\Http\Controllers\Pembelian\PurchasingOrderController;
use App\Http\Controllers\hr_karyawan\DataKaryawanController;
use App\Http\Controllers\hr_karyawan\StrukturOrganisasiController;
use App\Http\Controllers\hr_karyawan\CutiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Inventaris\StokBarangController;
use App\Http\Controllers\Inventaris\PermintaanBarangController;
use App\Http\Controllers\Pembelian\PenerimaanBarangController;
use App\Http\Controllers\Pembelian\RiwayatTransaksiController;
use App\Http\Controllers\Pembelian\ReturPembelianController;
use App\Http\Controllers\Laporan\LaporanStokController;
use App\Http\Controllers\Laporan\LaporanPembelianController;
use App\Http\Controllers\Laporan\LaporanPenjualanController;
use App\Http\Controllers\Laporan\LaporanProduksiController;
use App\Http\Controllers\Laporan\LaporanKeuanganController;
use App\Http\Controllers\Keuangan\KasDanBankController;
use App\Http\Controllers\Keuangan\HutangUsahaController;
use App\Http\Controllers\Keuangan\PembayaranHutangController;
use App\Http\Controllers\Keuangan\PiutangUsahaController;
use App\Http\Controllers\Keuangan\PembayaranPiutangController;
use App\Http\Controllers\Keuangan\ManagementPajakController;
use App\Http\Controllers\Keuangan\RekonsiliasiBankController;
use App\Http\Controllers\Keuangan\BukuBesarController;
use App\Http\Controllers\Inventaris\TransferGudangController;
use App\Http\Controllers\Inventaris\PenyesuaianStokController;
use App\Http\Controllers\Produksi\BOMController;
use App\Http\Controllers\Produksi\PerencanaanProduksiController;
use App\Http\Controllers\Produksi\WorkOrderController;
use App\Http\Controllers\Produksi\PengambilanBahanBakuController;
use App\Http\Controllers\Produksi\QualityControlController;
use App\Http\Controllers\Produksi\PengembalianMaterialController;
use App\Http\Controllers\Penjualan\QuotationController;
use App\Http\Controllers\Penjualan\SalesOrderController;
use App\Http\Controllers\Penjualan\DeliveryOrderController;
use App\Http\Controllers\Penjualan\InvoiceController;
use App\Http\Controllers\Penjualan\ReturPenjualanController;
use App\Http\Controllers\Penjualan\NotaKreditController;
use App\Http\Controllers\Penjualan\RiwayatTransaksiPenjualanController;
use App\Http\Controllers\CRM\ProspekLeadController;
use App\Http\Controllers\CRM\ProspekAktivitasController;
use App\Http\Controllers\Pengaturan\LogAktivitasController;
use App\Http\Controllers\Pengaturan\PengaturanUmumController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Test routes without auth for debugging
Route::get('test-financial', [LaporanKeuanganController::class, 'index'])->name('test.financial.index');
Route::get('test-financial/balance-sheet', [LaporanKeuanganController::class, 'getBalanceSheet'])->name('test.financial.balance-sheet');
Route::get('test-financial/income-statement', [LaporanKeuanganController::class, 'getIncomeStatement'])->name('test.financial.income-statement');
Route::get('test-financial/cash-flow', [LaporanKeuanganController::class, 'getCashFlow'])->name('test.financial.cash-flow');
Route::get('test-financial/validate/operating-expenses', [LaporanKeuanganController::class, 'validateOperatingExpenses'])->name('test.financial.validate.operating-expenses');
Route::get('test-financial/expense-category/{category}', [LaporanKeuanganController::class, 'getExpenseCategoryDetail'])->name('test.financial.expense-category.detail');

// Test QR Code with Logo
Route::get('/test-qr-logo', function () {
    try {
        $qrService = app(\App\Services\QRCodeService::class);

        // Test 1: Simple QR Code without logo
        $simpleQR = $qrService->generateSimpleQRCode('Test QR Code Without Logo', 200, false);

        // Test 2: QR Code with logo
        $qrWithLogo = $qrService->generateSimpleQRCode('Test QR Code With Logo', 200, true);

        $user = \App\Models\User::first();
        if (!$user) {
            return "No users found in database";
        }

        // Test 3: Document QR Code with logo
        $documentQR = $qrService->generateDocumentQRCode('test_document', 'TEST-001', $user->name, [
            'status' => 'processed',
            'test' => true
        ]);

        // Test 4: Signature QR Code with logo
        $role = $user->roles->first()->name ?? 'User';
        $signatureQR = $qrService->generateSignatureQRCode($user->name, $user->email, $role, 'tested');

        return view('test-qr-with-logo', compact('simpleQR', 'qrWithLogo', 'documentQR', 'signatureQR'));
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage() . '<br><br>Stack trace:<br>' . nl2br($e->getTraceAsString());
    }
})->name('test.qr.logo');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/employee', [ProfileController::class, 'updateEmployee'])->name('profile.employee.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');

    // Notifications routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/latest', [NotificationController::class, 'getLatest'])->name('notifications.latest');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::post('/notifications/clean-old', [NotificationController::class, 'cleanOldNotifications'])->name('notifications.cleanOld');
    Route::delete('/notifications/delete-all', [NotificationController::class, 'deleteAll'])->name('notifications.deleteAll');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Pengaturan Hak Akses
    Route::get('/pengaturan/hak-akses', [HakAksesController::class, 'index'])->name('pengaturan.hak-akses.index');
    Route::post('/pengaturan/hak-akses', [HakAksesController::class, 'update'])->name('pengaturan.hak-akses.update');

    // Pengaturan Umum
    Route::get('/pengaturan/umum', [PengaturanUmumController::class, 'index'])->name('pengaturan.umum');
    Route::post('/pengaturan/umum', [PengaturanUmumController::class, 'update'])->name('pengaturan.umum.update');

    // Management Pengguna
    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::delete('management-pengguna/bulk-destroy', [ManagementPenggunaController::class, 'bulkDestroy'])->name('management-pengguna.bulk-destroy');
        Route::patch('management-pengguna/{user}/toggle-status', [ManagementPenggunaController::class, 'toggleStatus'])->name('management-pengguna.toggle-status');
        Route::patch('management-pengguna/{user}/reset-password', [ManagementPenggunaController::class, 'resetPassword'])->name('management-pengguna.reset-password');

        // Role management routes
        Route::get('management-pengguna/roles', [ManagementPenggunaController::class, 'getRoles'])->name('management-pengguna.roles');
        Route::post('management-pengguna/roles', [ManagementPenggunaController::class, 'storeRole'])->name('management-pengguna.roles.store');
        Route::put('management-pengguna/roles/{role}', [ManagementPenggunaController::class, 'updateRole'])->name('management-pengguna.roles.update');
        Route::delete('management-pengguna/roles/{role}', [ManagementPenggunaController::class, 'destroyRole'])->name('management-pengguna.roles.destroy');

        Route::resource('management-pengguna', ManagementPenggunaController::class);

        // Log Aktivitas Routes
        Route::get('log-aktivitas/export', [LogAktivitasController::class, 'export'])->name('log-aktivitas.export');
        Route::post('log-aktivitas/bulk-delete', [LogAktivitasController::class, 'bulkDelete'])->name('log-aktivitas.bulk-delete');
        Route::post('log-aktivitas/cleanup', [LogAktivitasController::class, 'cleanOldLogs'])->name('log-aktivitas.cleanup');
        Route::resource('log-aktivitas', LogAktivitasController::class)->only(['index', 'show']);
    });

    // --- Master Data Group ---
    Route::prefix('master-data')->name('master.')->group(function () {

        // PRODUK - with permission middleware
        Route::middleware('permission:produk.delete')->group(function () {
            Route::delete('produk/bulk-destroy', [ProdukController::class, 'bulkDestroy'])->name('produk.bulk-destroy.any');
        });
        Route::middleware('permission:produk.view')->group(function () {
            Route::get('produk/{produk}/get', [ProdukController::class, 'getById'])->name('produk.get');
        });
        Route::middleware('permission:produk.create')->group(function () {
            Route::get('/produk/generate-code', [ProdukController::class, 'generateCode'])->name('produk.generate-code');
        });
        Route::middleware('permission:produk.import')->group(function () {
            Route::post('produk/import', [ProdukController::class, 'import'])->name('produk.import');
        });
        Route::middleware('permission:produk.edit')->group(function () {
            Route::post('produk/{produk}', [ProdukController::class, 'update'])->name('produk.custom-update');
        });
        Route::middleware('permission:produk.export')->group(function () {
            Route::get('produk/export', [ProdukController::class, 'export'])->name('produk.export');
            Route::get('produk/template', [ProdukController::class, 'downloadTemplate'])->name('produk.template');
        });
        Route::resource('produk', ProdukController::class);

        // KATEGORI PRODUK - with permission middleware
        Route::middleware('permission:kategori_produk.delete')->group(function () {
            Route::delete('kategori-produk/bulk-destroy', [KategoriProdukController::class, 'bulkDestroy'])->name('kategori-produk.bulk-destroy');
        });
        Route::middleware('permission:kategori_produk.view')->group(function () {
            Route::get('kategori-produk/{kategoriProduk}/get', [KategoriProdukController::class, 'getKategori'])->name('kategori-produk.get');
        });
        Route::resource('kategori-produk', KategoriProdukController::class)->parameters([
            'kategori-produk' => 'kategoriProduk' // Ensure parameter name matches controller type hint
        ]);

        // CUSTOMER - with permission middleware
        Route::middleware('permission:pelanggan.delete')->group(function () {
            Route::post('pelanggan/bulk-destroy', [CustomerController::class, 'bulkDestroy'])->name('pelanggan.bulk-destroy.any');
        });
        Route::middleware('permission:pelanggan.view')->group(function () {
            Route::get('pelanggan/{pelanggan}/get', [CustomerController::class, 'getById'])->name('pelanggan.get');
        });
        Route::middleware('permission:pelanggan.create')->group(function () {
            Route::get('pelanggan/generate-code', [CustomerController::class, 'generateCode'])->name('pelanggan.generate-code');
            Route::get('pelanggan/get-sales-users', [CustomerController::class, 'getSalesUsers'])->name('pelanggan.get-sales-users');
        });
        Route::middleware('permission:pelanggan.export')->group(function () {
            Route::get('pelanggan/export', [CustomerController::class, 'export'])->name('pelanggan.export');
        });
        Route::middleware('permission:pelanggan.import')->group(function () {
            Route::post('pelanggan/import', [CustomerController::class, 'import'])->name('pelanggan.import');
        });
        Route::resource('pelanggan', CustomerController::class);

        // SUPPLIER - with permission middleware
        Route::middleware('permission:supplier.delete')->group(function () {
            Route::post('supplier/bulk-destroy', [SupplierController::class, 'bulkDestroy'])->name('supplier.bulk-destroy.any');
        });
        Route::middleware('permission:supplier.view')->group(function () {
            Route::get('supplier/{supplier}/get', [SupplierController::class, 'getById'])->name('supplier.get');
        });
        Route::middleware('permission:supplier.create')->group(function () {
            Route::get('supplier/generate-code', [SupplierController::class, 'generateCode'])->name('supplier.generate-code');
        });
        Route::middleware('permission:supplier.import')->group(function () {
            Route::post('supplier/import', [SupplierController::class, 'import'])->name('supplier.import');
        });
        Route::middleware('permission:supplier.export')->group(function () {
            Route::get('supplier/export', [SupplierController::class, 'export'])->name('supplier.export');
        });
        Route::resource('supplier', SupplierController::class);

        // GUDANG - with permission middleware
        Route::middleware('permission:gudang.delete')->group(function () {
            Route::delete('gudang/bulk-destroy', [GudangController::class, 'bulkDestroy'])->name('gudang.bulk-destroy');
        });
        Route::middleware('permission:gudang.view')->group(function () {
            Route::get('gudang/{gudang}/get', [GudangController::class, 'getGudang'])->name('gudang.get');
        });
        Route::resource('gudang', GudangController::class);

        // SATUAN - with permission middleware
        Route::middleware('permission:satuan.delete')->group(function () {
            Route::delete('satuan/bulk-destroy', [SatuanController::class, 'bulkDestroy'])->name('satuan.bulk-destroy');
        });
        Route::middleware('permission:satuan.view')->group(function () {
            Route::get('satuan/{satuan}/get', [SatuanController::class, 'getSSatuan'])->name('satuan.get');
        });
        Route::resource('satuan', SatuanController::class);
    });    // --- INVENTARIS ----
    Route::prefix('inventaris')->name('inventaris.')->group(function () {
        // Stok Barang - with permission middleware
        Route::resource('stok', StokBarangController::class);

        // Additional stock management routes
        Route::post('stok/initialize', [StokBarangController::class, 'initializeStock'])->name('stok.initialize');
        Route::get('produk/count', [ProdukController::class, 'getProductCount'])->name('produk.count');

        // Transfer Barang - with permission middleware
        Route::middleware('permission:transfer_gudang.process')->group(function () {
            Route::post('transfer-gudang/{id}/proses', [TransferGudangController::class, 'prosesTransfer'])->name('transfer-gudang.proses');
            Route::post('transfer-gudang/{id}/selesai', [TransferGudangController::class, 'selesaikanTransfer'])->name('transfer-gudang.selesai');
        });
        Route::middleware('permission:transfer_gudang.view')->group(function () {
            Route::get('transfer-gudang/get-stok', [TransferGudangController::class, 'getStokProduk'])->name('transfer-gudang.get-stok');
        });
        Route::resource('transfer-gudang', TransferGudangController::class);

        // Penyesuaian Stok - with permission middleware
        Route::middleware('permission:penyesuaian_stok.process')->group(function () {
            Route::post('penyesuaian-stok/{id}/proses', [PenyesuaianStokController::class, 'prosesPenyesuaian'])->name('penyesuaian-stok.proses');
        });
        Route::middleware('permission:penyesuaian_stok.view')->group(function () {
            Route::get('penyesuaian-stok/get-stok', [PenyesuaianStokController::class, 'getStokProduk'])->name('penyesuaian-stok.get-stok');
            Route::get('gudang/{id}/produks', [PenyesuaianStokController::class, 'getProduksByGudang'])->name('penyesuaian-stok.get-produks');
            Route::get('penyesuaian-stok/{id}/pdf', [PenyesuaianStokController::class, 'printPdf'])->name('penyesuaian-stok.pdf');
            Route::get('penyesuaian-stok/pdf/draft', [PenyesuaianStokController::class, 'printDraftPdf'])->name('penyesuaian-stok.pdf.draft');
        });
        Route::resource('penyesuaian-stok', PenyesuaianStokController::class);

        // Permintaan Barang - with permission middleware
        Route::middleware('permission:permintaan_barang.create')->group(function () {
            Route::post('permintaan-barang/auto-generate', [App\Http\Controllers\Inventaris\PermintaanBarangController::class, 'generateFromSalesOrder'])->name('permintaan-barang.auto-generate');
            Route::post('permintaan-barang/auto-proses', [App\Http\Controllers\Inventaris\PermintaanBarangController::class, 'autoProsesFromSalesOrder'])->name('permintaan-barang.auto-proses');
        });
        Route::middleware('permission:permintaan_barang.edit')->group(function () {
            Route::put('permintaan-barang/{permintaanBarang}/update-status', [App\Http\Controllers\Inventaris\PermintaanBarangController::class, 'updateStatus'])->name('permintaan-barang.update-status');
        });
        Route::middleware('permission:permintaan_barang.view')->group(function () {
            Route::get('permintaan-barang/{permintaanBarang}/create-do', [App\Http\Controllers\Inventaris\PermintaanBarangController::class, 'createDeliveryOrder'])->name('permintaan-barang.create-do');
        });
        Route::resource('permintaan-barang', App\Http\Controllers\Inventaris\PermintaanBarangController::class);
    });

    Route::prefix('penjualan')->name('penjualan.')->group(function () {

        // Quotation routes
        Route::middleware('permission:quotation.change_status')->group(function () {
            Route::post('quotation/{quotation}/change-status', [QuotationController::class, 'changeStatus'])->name('quotation.changeStatus');
        });
        Route::middleware('permission:quotation.export_pdf')->group(function () {
            Route::get('quotation/{id}/pdf', [QuotationController::class, 'exportPdf'])->name('quotation.pdf');
        });
        Route::middleware('permission:quotation.view')->group(function () {
            Route::get('api/quotations', [QuotationController::class, 'getQuotationsForSelect'])->name('api.quotations');
        });
        Route::resource('quotation', QuotationController::class);

        // Sales Order routes
        Route::middleware('permission:sales_order.change_status')->group(function () {
            Route::post('sales-order/{sales_order}/change-status', [SalesOrderController::class, 'changeStatus'])->name('sales-order.changeStatus');
        });

        Route::get('sales-order/{id}/pdf', [SalesOrderController::class, 'exportPdf'])->name('sales-order.pdf');

        Route::middleware('permission:sales_order.view')->group(function () {
            Route::get('sales-order/get-quotation-data/{id}', [SalesOrderController::class, 'getQuotationData'])->name('sales-order.get-quotation-data');
        });
        Route::resource('sales-order', SalesOrderController::class);

        // Invoice routes
        Route::middleware('permission:invoice.view')->group(function () {
            Route::get('invoice/get-sales-order/{id}', [InvoiceController::class, 'getSalesOrderData'])->name('invoice.get-sales-order');
        });
        Route::middleware('permission:invoice.print')->group(function () {
            Route::get('invoice/{invoice}/print', [InvoiceController::class, 'print'])->name('invoice.print');
        });
        Route::middleware('permission:sales_order.generate_invoice')->group(function () {
            Route::get('sales-order/{salesOrder}/generate-invoice', [InvoiceController::class, 'generateFromSalesOrder'])->name('sales-order.generate-invoice');
        });
        Route::get('invoice/{id}/print-template', [InvoiceController::class, 'printTemplate'])->name('invoice.print-template');
        Route::resource('invoice', InvoiceController::class);

        // Delivery Order routes
        Route::middleware('permission:delivery_order.process')->group(function () {
            Route::post('delivery-order/{id}/proses', [DeliveryOrderController::class, 'prosesDelivery'])->name('delivery-order.proses');
            Route::post('delivery-order/{id}/selesaikan', [DeliveryOrderController::class, 'selesaikanDelivery'])->name('delivery-order.selesaikan');
            Route::post('delivery-order/{id}/selesai', [DeliveryOrderController::class, 'selesaikanDelivery'])->name('delivery-order.selesai');
        });
        Route::middleware('permission:delivery_order.cancel')->group(function () {
            Route::post('delivery-order/{id}/batalkan', [DeliveryOrderController::class, 'batalkanDelivery'])->name('delivery-order.batalkan');
        });
        Route::middleware('permission:delivery_order.view')->group(function () {
            Route::get('delivery-order/get-sales-order-data/{id}', [DeliveryOrderController::class, 'getSalesOrderData'])->name('delivery-order.get-sales-order-data');
            Route::get('delivery-order/get-stock-info', [DeliveryOrderController::class, 'getStockInformation'])->name('delivery-order.get-stock-info');
            Route::get('delivery-orders/ajax/table', [DeliveryOrderController::class, 'table'])->name('delivery-order.table');
        });
        Route::middleware('permission:delivery_order.print')->group(function () {
            Route::get('delivery-order/{id}/print', [DeliveryOrderController::class, 'print'])->name('delivery-order.print');
            Route::get('delivery-order/{id}/print-template', [DeliveryOrderController::class, 'printTemplate'])->name('delivery-order.print-template');
            Route::get('delivery-order/{id}/download-template', [DeliveryOrderController::class, 'downloadTemplate'])->name('delivery-order.download-template');
            Route::get('delivery-order/{id}/test-coordinates', [DeliveryOrderController::class, 'testTemplateCoordinates'])->name('delivery-order.test-coordinates');
            Route::get('delivery-order/{id}/custom-coordinates', [DeliveryOrderController::class, 'generateWithCustomCoordinates'])->name('delivery-order.custom-coordinates');
            Route::get('delivery-order/coordinates-helper', [DeliveryOrderController::class, 'getTemplateCoordinates'])->name('delivery-order.coordinates-helper');
            Route::get('delivery-order/template-info', [DeliveryOrderController::class, 'getTemplateInfo'])->name('delivery-order.template-info');
        });
        Route::resource('delivery-order', DeliveryOrderController::class);

        // Retur Penjualan routes
        Route::middleware('permission:retur_penjualan.process')->group(function () {
            Route::post('retur/{id}/proses', [ReturPenjualanController::class, 'prosesRetur'])->name('retur.proses');
            Route::post('retur/{id}/selesai', [ReturPenjualanController::class, 'selesaikanRetur'])->name('retur.selesai');
            Route::post('retur/{id}/kirim-pengganti', [ReturPenjualanController::class, 'prosesKirimPengganti'])->name('retur.kirim-pengganti');
        });
        Route::middleware('permission:retur_penjualan.print')->group(function () {
            Route::get('retur/{id}/pdf', [ReturPenjualanController::class, 'exportPdf'])->name('retur.pdf');
        });
        Route::middleware('permission:retur_penjualan.view')->group(function () {
            Route::get('retur/get-sales-orders', [ReturPenjualanController::class, 'getSalesOrders'])->name('retur.get-sales-orders');
            Route::get('retur/get-sales-order-details', [ReturPenjualanController::class, 'getSalesOrderDetails'])->name('retur.get-sales-order-details');
            Route::get('retur/analyze', [ReturPenjualanController::class, 'analyzeReturns'])->name('retur.analyze');
            Route::get('retur/{id}/quality-control', [ReturPenjualanController::class, 'showQualityControlForm'])->name('retur.quality-control');
            Route::get('retur/{id}/quality-control-detail', [ReturPenjualanController::class, 'showQualityControlDetail'])->name('retur.quality-control-detail');
            Route::get('retur/{id}/create-credit-note', [ReturPenjualanController::class, 'createCreditNote'])->name('retur.create-credit-note');
            Route::get('retur/qc-report', [ReturPenjualanController::class, 'qcReport'])->name('retur.qc-report');
            Route::get('retur/{id}/kirim-barang-pengganti', [ReturPenjualanController::class, 'showTerimaBarangPengganti'])->name('retur.kirim-barang-pengganti');
            Route::get('retur/get-stok', [ReturPenjualanController::class, 'getStokProduk'])->name('retur.get-stok');
            Route::post('retur/{id}/submit-approval', [ReturPenjualanController::class, 'submitForApproval'])->name('retur.submit-approval');
            Route::post('retur/{id}/kirim-barang-pengganti', [ReturPenjualanController::class, 'terimaBarangPengganti'])->name('retur.proses-kirim-barang-pengganti');
            Route::post('retur/{id}/quality-control', [ReturPenjualanController::class, 'processQualityControl'])->name('retur.process-quality-control');
        });
        Route::middleware('permission:retur_penjualan.approve')->group(function () {
            Route::post('retur/{id}/approve', [ReturPenjualanController::class, 'approveReturn'])->name('retur.approve');
            Route::post('retur/{id}/reject', [ReturPenjualanController::class, 'rejectReturn'])->name('retur.reject');
        });
        Route::resource('retur', ReturPenjualanController::class);

        // Nota Kredit routes
        Route::middleware('permission:nota_kredit.edit')->group(function () {
            Route::post('nota-kredit/{notaKredit}/finalize', [NotaKreditController::class, 'finalize'])->name('nota-kredit.finalize');
            Route::post('nota-kredit/{notaKredit}/complete', [NotaKreditController::class, 'completeNotaKredit'])->name('nota-kredit.complete');
            Route::post('nota-kredit/{notaKredit}/apply-to-invoice/{invoice}', [NotaKreditController::class, 'applyToInvoice'])->name('nota-kredit.apply-to-invoice');
        });
        Route::middleware('permission:nota_kredit.print')->group(function () {
            Route::get('nota-kredit/{id}/pdf', [NotaKreditController::class, 'exportPdf'])->name('nota-kredit.pdf');
        });
        Route::resource('nota-kredit', NotaKreditController::class);

        // Riwayat Transaksi routes
        Route::middleware('permission:sales_order.view,invoice.view')->group(function () {
            Route::get('riwayat-transaksi', [RiwayatTransaksiPenjualanController::class, 'index'])->name('riwayat-transaksi.index');
            Route::get('riwayat-transaksi/data', [RiwayatTransaksiPenjualanController::class, 'getData'])->name('riwayat-transaksi.data');
            Route::get('riwayat-transaksi/export/{type}', [RiwayatTransaksiPenjualanController::class, 'export'])->name('riwayat-transaksi.export');
        });
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
        Route::get('permintaan-pembelian/{id}/print', [PermintaanPembelianController::class, 'printPdf'])->name('permintaan-pembelian.print');
        Route::resource('permintaan-pembelian', PermintaanPembelianController::class);

        // PURCHASING ORDER
        Route::put('purchasing-order/{purchasing_order}/change-status', [PurchasingOrderController::class, 'changeStatus'])->name('purchasing-order.change-status');
        Route::get('purchasing-order/{id}/pdf', [PurchasingOrderController::class, 'exportPdf'])->name('purchasing-order.pdf');
        Route::get('purchasing-order/{id}/print', [PurchasingOrderController::class, 'printPdf'])->name('purchasing-order.print');
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
        Route::get('retur-pembelian/{id}/print', [ReturPembelianController::class, 'printPdf'])->name('retur-pembelian.print');
        Route::get('retur-pembelian/get-purchase-orders', [ReturPembelianController::class, 'getPurchaseOrders'])->name('retur-pembelian.get-purchase-orders');
        Route::get('retur-pembelian/get-purchase-order-items', [ReturPembelianController::class, 'getPurchaseOrderItems'])->name('retur-pembelian.get-purchase-order-items');
        Route::resource('retur-pembelian', ReturPembelianController::class);

        //RIWAYAT TRANSAKSI
        Route::get('riwayat-transaksi', [RiwayatTransaksiController::class, 'index'])->name('riwayat-transaksi.index');
        Route::get('riwayat-transaksi/{id}', [RiwayatTransaksiController::class, 'show'])->name('riwayat-transaksi.show');
    });

    // --- Production Module Routes ---
    Route::prefix('produksi')->name('produksi.')->group(function () {

        // BOM (Bill of Materials) Routes - with permission middleware
        Route::middleware('permission:bill_of_material.view')->group(function () {
            Route::get('bom/{id}/get', [BOMController::class, 'getById'])->name('bom.get');
            Route::get('bom-data', [BOMController::class, 'data'])->name('bom.data');
            Route::get('bom-component-unit/{id}', [BOMController::class, 'getComponentUnit'])->name('bom.component-unit');
        });
        Route::middleware('permission:bill_of_material.add_component')->group(function () {
            Route::post('bom/{id}/components', [BOMController::class, 'addComponent'])->name('bom.add-component');
        });
        Route::middleware('permission:bill_of_material.edit_component')->group(function () {
            Route::put('bom-component/{id}', [BOMController::class, 'updateComponent'])->name('bom.update-component');
        });
        Route::middleware('permission:bill_of_material.delete_component')->group(function () {
            Route::delete('bom-component/{id}', [BOMController::class, 'deleteComponent'])->name('bom.delete-component');
        });
        Route::resource('bom', BOMController::class);

        // Perencanaan Produksi Routes - with permission middleware
        Route::middleware('permission:perencanaan_produksi.view')->group(function () {
            Route::get('perencanaan-produksi/get-so-items', [PerencanaanProduksiController::class, 'getSoItems'])->name('perencanaan-produksi.get-so-items');
            Route::get('perencanaan-produksi/get-sales-order/{id}', [PerencanaanProduksiController::class, 'getSalesOrderData'])->name('perencanaan-produksi.get-sales-order');
            Route::get('perencanaan-produksi/{id}/create-work-order', [PerencanaanProduksiController::class, 'createWorkOrder'])->name('perencanaan-produksi.create-work-order');
            Route::post('perencanaan-produksi/{id}/change-status', [PerencanaanProduksiController::class, 'changeStatus'])->name('perencanaan-produksi.change-status');
        });
        Route::middleware('permission:perencanaan_produksi.edit')->group(function () {
            Route::put('perencanaan-produksi/{id}/submit', [PerencanaanProduksiController::class, 'submit'])->name('perencanaan-produksi.submit');
        });
        Route::middleware('permission:perencanaan_produksi.approve')->group(function () {
            Route::put('perencanaan-produksi/{id}/approve', [PerencanaanProduksiController::class, 'approve'])->name('perencanaan-produksi.approve');
            Route::put('perencanaan-produksi/{id}/reject', [PerencanaanProduksiController::class, 'reject'])->name('perencanaan-produksi.reject');
        });
        Route::resource('perencanaan-produksi', PerencanaanProduksiController::class);

        // Work Order Routes - with permission middleware
        Route::middleware('permission:work_order.change_status')->group(function () {
            Route::post('work-order/{id}/change-status', [WorkOrderController::class, 'changeStatus'])->name('work-order.change-status');
            // Routes for Pengambilan Bahan Baku
            Route::get('work-order/{id}/create-pengambilan', [WorkOrderController::class, 'createPengambilanBahanBaku'])->name('work-order.create-pengambilan');
            Route::post('work-order/{id}/store-pengambilan', [WorkOrderController::class, 'storePengambilanBahanBaku'])->name('work-order.store-pengambilan');
            Route::get('work-order/{id}/pengambilan-bahan-baku', [WorkOrderController::class, 'createPengambilanBahanBaku'])->name('work-order.pengambilan-bahan-baku');
            Route::post('work-order/{id}/pengambilan-bahan-baku', [WorkOrderController::class, 'storePengambilanBahanBaku'])->name('work-order.store-pengambilan-bahan-baku');
        });
        // Routes for Quality Control
        Route::get('work-order/{id}/create-qc', [WorkOrderController::class, 'createQualityControl'])->name('work-order.create-qc');
        Route::post('work-order/{id}/store-qc', [WorkOrderController::class, 'storeQualityControl'])->name('work-order.store-qc');
        Route::get('work-order/{id}/quality-control', [WorkOrderController::class, 'createQualityControl'])->name('work-order.quality-control');
        Route::post('work-order/{id}/quality-control', [WorkOrderController::class, 'storeQualityControl'])->name('work-order.store-quality-control');

        // AJAX Routes for Material Selection (put outside permission middleware for easier access)
        Route::middleware('auth')->group(function () {
            Route::get('work-order/{id}/get-available-materials', [WorkOrderController::class, 'getAvailableMaterials'])->name('work-order.get-available-materials');
            Route::get('work-order/{id}/get-stok/{produk_id}', [WorkOrderController::class, 'getMaterialStock'])->name('work-order.get-material-stock');
        });
        Route::middleware('permission:work_order.view')->group(function () {
            Route::get('work-order/select-product/{perencanaan_id}', [WorkOrderController::class, 'create'])->name('work-order.select-product');
        });
        Route::middleware('permission:work_order.create')->group(function () {
            Route::get('work-order/{id}/create-pengembalian', [PengembalianMaterialController::class, 'create'])->name('work-order.create-pengembalian');
            Route::post('work-order/{id}/store-pengembalian', [PengembalianMaterialController::class, 'store'])->name('work-order.store-pengembalian');
        });

        Route::resource('work-order', WorkOrderController::class);

        // Pengambilan Bahan Baku Routes - with permission middleware (using existing permissions)
        Route::middleware('permission:work_order.view')->group(function () {
            Route::get('pengambilan-bahan-baku/check-stok', [PengambilanBahanBakuController::class, 'checkStok'])->name('pengambilan-bahan-baku.check-stok');
        });
        Route::middleware('permission:work_order.view')->group(function () {
            Route::get('pengambilan-bahan-baku/{id}/pdf', [PengambilanBahanBakuController::class, 'exportPdf'])->name('pengambilan-bahan-baku.pdf');
        });
        Route::resource('pengambilan-bahan-baku', PengambilanBahanBakuController::class)->only(['index', 'show']);


        // Quality Control Routes - with permission middleware  
        Route::middleware('permission:quality_control.print')->group(function () {
            Route::get('quality-control/{id}/pdf', [QualityControlController::class, 'exportPdf'])->name('quality-control.pdf');
            Route::get('quality-control/report', [QualityControlController::class, 'report'])->name('quality-control.report');
            Route::get('quality-control/export-pdf', [QualityControlController::class, 'exportPdf'])->name('quality-control.export-pdf');
        });
        Route::middleware('permission:quality_control.approve')->group(function () {
            Route::put('quality-control/{id}/approve', [QualityControlController::class, 'approve'])->name('quality-control.approve');
            Route::put('quality-control/{id}/reject', [QualityControlController::class, 'reject'])->name('quality-control.reject');
        });
        Route::resource('quality-control', QualityControlController::class)->only(['index', 'show']);
    });

    // -- HR & Karyawan --
    Route::prefix('hr')->name('hr.')->group(function () {
        Route::delete('karyawan/bulk-destroy', [DataKaryawanController::class, 'bulkDestroy'])->name('karyawan.bulk-destroy');
        Route::resource('karyawan', DataKaryawanController::class);

        // Struktur Organisasi routes
        Route::get('struktur-organisasi/department/{id}', [App\Http\Controllers\hr_karyawan\StrukturOrganisasiController::class, 'getDepartmentDetails'])->name('struktur-organisasi.department');
        Route::get('struktur-organisasi', [App\Http\Controllers\hr_karyawan\StrukturOrganisasiController::class, 'index'])->name('struktur-organisasi.index');

        // Department CRUD routes
        Route::post('struktur-organisasi/departments', [App\Http\Controllers\hr_karyawan\StrukturOrganisasiController::class, 'storeDepartment'])->name('struktur-organisasi.departments.store');
        Route::put('struktur-organisasi/departments/{id}', [App\Http\Controllers\hr_karyawan\StrukturOrganisasiController::class, 'updateDepartment'])->name('struktur-organisasi.departments.update');
        Route::delete('struktur-organisasi/departments/{id}', [App\Http\Controllers\hr_karyawan\StrukturOrganisasiController::class, 'deleteDepartment'])->name('struktur-organisasi.departments.delete');

        // Jabatan CRUD routes  
        Route::post('jabatan', [App\Http\Controllers\hr_karyawan\StrukturOrganisasiController::class, 'storeJabatan'])->name('jabatan.store');
        Route::put('jabatan/{id}', [App\Http\Controllers\hr_karyawan\StrukturOrganisasiController::class, 'updateJabatan'])->name('jabatan.update');
        Route::delete('jabatan/{id}', [App\Http\Controllers\hr_karyawan\StrukturOrganisasiController::class, 'deleteJabatan'])->name('jabatan.delete');

        // Employees API route
        Route::get('karyawan/api/employees', [App\Http\Controllers\hr_karyawan\StrukturOrganisasiController::class, 'getAvailableEmployees'])->name('karyawan.api.employees');

        // Absensi & Kehadiran routes
        Route::get('absensi/export-excel', [AbsensiController::class, 'exportExcel'])->name('absensi.export-excel');
        Route::get('absensi/export-pdf', [AbsensiController::class, 'exportPdf'])->name('absensi.export-pdf');
        Route::post('absensi/import', [AbsensiController::class, 'import'])->name('absensi.import');
        Route::get('absensi/departemen', [AbsensiController::class, 'getDepartemen'])->name('absensi.departemen');
        Route::resource('absensi', AbsensiController::class);

        // API routes for kas and rekening bank transactions
        Route::get('/api/kas/{id}/transaksi', [App\Http\Controllers\Keuangan\KasDanBankController::class, 'getKasTransaksi'])->name('api.kas.transaksi');
        Route::get('/api/rekening/{id}/transaksi', [App\Http\Controllers\Keuangan\KasDanBankController::class, 'getRekeningTransaksi'])->name('api.rekening.transaksi');

        // Penggajian routes
        Route::post('penggajian/get-komisi', [App\Http\Controllers\hr_karyawan\PenggajianController::class, 'getKomisiKaryawan'])->name('penggajian.get-komisi');
        Route::post('penggajian/{id}/approve', [App\Http\Controllers\hr_karyawan\PenggajianController::class, 'approve'])->name('penggajian.approve');
        Route::post('penggajian/{id}/process-payment', [App\Http\Controllers\hr_karyawan\PenggajianController::class, 'processPayment'])->name('penggajian.process-payment');
        Route::get('penggajian/{id}/pdf', [App\Http\Controllers\hr_karyawan\PenggajianController::class, 'exportPdf'])->name('penggajian.pdf');
        Route::get('penggajian/{id}/print', [App\Http\Controllers\hr_karyawan\PenggajianController::class, 'printPdf'])->name('penggajian.print');
        Route::resource('penggajian', App\Http\Controllers\hr_karyawan\PenggajianController::class);

        // Cuti & Izin routes
        Route::get('cuti/export-excel', [App\Http\Controllers\hr_karyawan\CutiController::class, 'exportExcel'])->name('cuti.export-excel');
        Route::get('cuti/export-pdf', [App\Http\Controllers\hr_karyawan\CutiController::class, 'exportPdf'])->name('cuti.export-pdf');
        Route::get('cuti/departments', [App\Http\Controllers\hr_karyawan\CutiController::class, 'getDepartments'])->name('cuti.departments');
        Route::get('cuti/departments/{id}/employees', [App\Http\Controllers\hr_karyawan\CutiController::class, 'getEmployeesByDepartment'])->name('cuti.departments.employees');
        Route::put('cuti/{id}/status', [App\Http\Controllers\hr_karyawan\CutiController::class, 'updateStatus'])->name('cuti.status');
        Route::resource('cuti', App\Http\Controllers\hr_karyawan\CutiController::class);
    });



    // -- Keuangan --
    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        // Chart of Accounts (COA) Routes
        Route::get('/coa/generate-code', [App\Http\Controllers\Keuangan\COAController::class, 'generateCode'])->name('coa.generate-code');
        Route::resource('coa', App\Http\Controllers\Keuangan\COAController::class);

        // Jurnal Umum Routes
        Route::get('jurnal-umum/export-excel', [App\Http\Controllers\Keuangan\JurnalUmumController::class, 'exportExcel'])->name('jurnal-umum.export-excel');
        Route::resource('jurnal-umum', App\Http\Controllers\Keuangan\JurnalUmumController::class);

        // Buku Besar Routes
        Route::get('buku-besar', [BukuBesarController::class, 'index'])->name('buku-besar.index');
        Route::get('buku-besar/export-excel', [BukuBesarController::class, 'exportExcel'])->name('buku-besar.export-excel');
        Route::get('buku-besar/export', [BukuBesarController::class, 'export'])->name('buku-besar.export');
        Route::get('buku-besar/account-balance', [BukuBesarController::class, 'getAccountBalance'])->name('buku-besar.account-balance');

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

        // Transaksi Routes
        Route::post('/kas-dan-bank/transaksi/store', [KasDanBankController::class, 'storeTransaksi'])->name('kas-dan-bank.store-transaksi');

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

        // Management Pajak Routes
        Route::post('management-pajak/auto-report', [ManagementPajakController::class, 'generateAutoReport'])->name('management-pajak.auto-report');
        Route::post('management-pajak/{id}/finalize', [ManagementPajakController::class, 'finalize'])->name('management-pajak.finalize');
        Route::get('management-pajak/{id}/export-pdf', [ManagementPajakController::class, 'exportPdf'])->name('management-pajak.export-pdf');
        Route::resource('management-pajak', ManagementPajakController::class);


        // Rekonsiliasi Bank Routes
        Route::get('rekonsiliasi', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'index'])->name('rekonsiliasi.index');
        Route::get('rekonsiliasi/erp-transactions', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'getErpTransactions'])->name('rekonsiliasi.erp-transactions');
        Route::get('rekonsiliasi/balance/rekening', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'getRekeningBalance'])->name('rekonsiliasi.rekening-balance');
        Route::get('rekonsiliasi/history/data', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'getReconciliationHistory'])->name('rekonsiliasi.history');
        Route::get('rekonsiliasi/list/data', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'getReconciliationList'])->name('rekonsiliasi.list');
        Route::get('rekonsiliasi/stats', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'getReconciliationStats'])->name('rekonsiliasi.stats');
        Route::get('rekonsiliasi/{id}', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'show'])->name('rekonsiliasi.show');
        Route::post('rekonsiliasi/save', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'saveReconciliation'])->name('rekonsiliasi.save');
        Route::post('rekonsiliasi/auto-match', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'autoMatch'])->name('rekonsiliasi.auto-match');
        Route::post('rekonsiliasi/process-statement', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'processStatement'])->name('rekonsiliasi.process-statement');

        // Enhanced Bank Statement Processing Routes
        Route::post('rekonsiliasi/upload-statement', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'uploadBankStatement'])->name('rekonsiliasi.upload-statement');
        Route::post('rekonsiliasi/add-manual-transaction', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'addManualBankTransaction'])->name('rekonsiliasi.add-manual-transaction');
        Route::post('rekonsiliasi/enhanced-auto-match', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'enhancedAutoMatch'])->name('rekonsiliasi.enhanced-auto-match');
        Route::post('rekonsiliasi/bulk-match', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'bulkMatchTransactions'])->name('rekonsiliasi.bulk-match');
        Route::post('rekonsiliasi/{id}/approve', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'approveReconciliation'])->name('rekonsiliasi.approve');
        Route::post('rekonsiliasi/{id}/reject', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'rejectReconciliation'])->name('rekonsiliasi.reject');
        Route::get('rekonsiliasi/{id}/export', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'exportReconciliation'])->name('rekonsiliasi.export');
        Route::post('rekonsiliasi/export', [\App\Http\Controllers\Keuangan\RekonsiliasiBankController::class, 'exportReconciliation'])->name('rekonsiliasi.export-data');
    });

    // -- Laporan --
    Route::prefix('laporan')->name('laporan.')->group(function () {
        // Stock Report Routes
        Route::get('stok', [LaporanStokController::class, 'index'])->name('stok.index');
        Route::get('stok/data', [LaporanStokController::class, 'getData'])->name('stok.data');
        Route::get('stok/export/excel', [LaporanStokController::class, 'exportExcel'])->name('stok.export.excel');
        Route::get('stok/export/pdf', [LaporanStokController::class, 'exportPdf'])->name('stok.export.pdf');
        Route::get('stok/detail/{produk_id}/{gudang_id?}', [LaporanStokController::class, 'detail'])->name('stok.detail');

        // Purchase Report Routes
        Route::get('pembelian', [LaporanPembelianController::class, 'index'])->name('pembelian.index');
        Route::get('pembelian/data', [LaporanPembelianController::class, 'getData'])->name('pembelian.data');
        Route::get('pembelian/chart-data', [LaporanPembelianController::class, 'getChartData'])->name('pembelian.chart-data');
        Route::get('pembelian/export/excel', [LaporanPembelianController::class, 'exportExcel'])->name('pembelian.export.excel');
        Route::get('pembelian/export/pdf', [LaporanPembelianController::class, 'exportPdf'])->name('pembelian.export.pdf');
        Route::get('pembelian/detail/{id}', [LaporanPembelianController::class, 'detail'])->name('pembelian.detail');
        Route::get('pembelian/detail/{id}/pdf', [LaporanPembelianController::class, 'detailPdf'])->name('pembelian.detail.pdf');

        // Sales Report Routes
        Route::get('penjualan', [LaporanPenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('penjualan/data', [LaporanPenjualanController::class, 'getData'])->name('penjualan.data');
        Route::get('penjualan/chart-data', [LaporanPenjualanController::class, 'getChartData'])->name('penjualan.chart-data');
        Route::get('penjualan/export/excel', [LaporanPenjualanController::class, 'exportExcel'])->name('penjualan.export.excel');
        Route::get('penjualan/export/pdf', [LaporanPenjualanController::class, 'exportPdf'])->name('penjualan.export.pdf');
        Route::get('penjualan/detail/{id}', [LaporanPenjualanController::class, 'detail'])->name('penjualan.detail');
        Route::get('penjualan/detail/{id}/pdf', [LaporanPenjualanController::class, 'detailPdf'])->name('penjualan.detail.pdf');

        // Production Report Routes
        Route::get('produksi', [LaporanProduksiController::class, 'index'])->name('produksi.index');
        Route::get('produksi/data', [LaporanProduksiController::class, 'getData'])->name('produksi.data');
        Route::get('produksi/export/excel', [LaporanProduksiController::class, 'exportExcel'])->name('produksi.export.excel');
        Route::get('produksi/export/pdf', [LaporanProduksiController::class, 'exportPdf'])->name('produksi.export.pdf');
        Route::get('produksi/detail/{id}', [LaporanProduksiController::class, 'detail'])->name('produksi.detail');
        Route::get('produksi/detail/{id}/pdf', [LaporanProduksiController::class, 'detailPdf'])->name('produksi.detail.pdf');

        // Financial Report Routes
        Route::get('keuangan', [LaporanKeuanganController::class, 'index'])->name('keuangan.index');
        Route::get('keuangan/balance-sheet', [LaporanKeuanganController::class, 'getBalanceSheet'])->name('keuangan.balance-sheet');
        Route::get('keuangan/income-statement', [LaporanKeuanganController::class, 'getIncomeStatement'])->name('keuangan.income-statement');
        Route::get('keuangan/cash-flow', [LaporanKeuanganController::class, 'getCashFlow'])->name('keuangan.cash-flow');
        Route::get('keuangan/export/excel', [LaporanKeuanganController::class, 'exportExcel'])->name('keuangan.export.excel');
        Route::get('keuangan/export/pdf', [LaporanKeuanganController::class, 'exportPdf'])->name('keuangan.export.pdf');

        // Enhanced Financial Validation Routes
        Route::get('keuangan/validate/operating-expenses', [LaporanKeuanganController::class, 'validateOperatingExpenses'])->name('keuangan.validate.operating-expenses');
        Route::get('keuangan/expense-category/{category}', [LaporanKeuanganController::class, 'getExpenseCategoryDetail'])->name('keuangan.expense-category.detail');
    });

    // -- CRM --
    Route::prefix('crm')->name('crm.')->group(function () {
        // Prospek & Lead Routes
        Route::get('prospek/data', [ProspekLeadController::class, 'data'])->name('prospek.data');
        Route::resource('prospek', ProspekLeadController::class);

        // Aktivitas Routes
        Route::get('aktivitas/followups', [ProspekAktivitasController::class, 'followups'])->name('aktivitas.followups');
        Route::patch('aktivitas/{aktivita}/followup', [ProspekAktivitasController::class, 'updateFollowupStatus'])->name('aktivitas.followup.update');
        // Batch Operations
        Route::post('aktivitas/batch-delete', [ProspekAktivitasController::class, 'batchDelete'])->name('aktivitas.batch-delete');
        Route::post('aktivitas/followup/batch-update', [ProspekAktivitasController::class, 'batchUpdateFollowup'])->name('aktivitas.followup.batch-update');
        Route::resource('aktivitas', ProspekAktivitasController::class);

        // Pipeline Penjualan Routes
        Route::get('pipeline', [App\Http\Controllers\CRM\PipelinePenjualanController::class, 'index'])->name('pipeline.index');
        Route::get('pipeline/data', [App\Http\Controllers\CRM\PipelinePenjualanController::class, 'data'])->name('pipeline.data');
        Route::patch('pipeline/{prospek}/status', [App\Http\Controllers\CRM\PipelinePenjualanController::class, 'updateStatus'])->name('pipeline.update-status');
        Route::get('pipeline/export/excel', [App\Http\Controllers\CRM\PipelinePenjualanController::class, 'exportExcel'])->name('pipeline.export.excel');
        Route::get('pipeline/export/csv', [App\Http\Controllers\CRM\PipelinePenjualanController::class, 'exportCsv'])->name('pipeline.export.csv');
    });
});

require __DIR__ . '/auth.php';