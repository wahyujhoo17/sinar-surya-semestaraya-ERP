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
use App\Http\Controllers\hr_karyawan\DataKaryawanController;


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
        Route::get('satuan/{satuan}/get', [SatuanController::class, 'getSatuan'])->name('satuan.get');
        Route::resource('satuan', SatuanController::class);
    });

    Route::prefix('pembelian')->name('pembelian.')->group(function () {
        Route::put('permintaan-pembelian/{permintaan_pembelian}/change-status', [PermintaanPembelianController::class, 'changeStatus'])->name('permintaan-pembelian.change-status');
        Route::put('permintaan-pembelian/{permintaan_pembelian}/approve', [PermintaanPembelianController::class, 'approve'])->name('permintaan-pembelian.approve');
        Route::put('permintaan-pembelian/{permintaan_pembelian}/reject', [PermintaanPembelianController::class, 'reject'])->name('permintaan-pembelian.reject');
        Route::resource('permintaan-pembelian', PermintaanPembelianController::class);
    });

    // HR & Karyawan
    Route::prefix('hr')->name('hr.')->group(function () {
        Route::delete('karyawan/bulk-destroy', [DataKaryawanController::class, 'bulkDestroy'])->name('karyawan.bulk-destroy');
        Route::resource('karyawan', DataKaryawanController::class);
        // Add other HR routes here if needed
    });
});

require __DIR__ . '/auth.php';
