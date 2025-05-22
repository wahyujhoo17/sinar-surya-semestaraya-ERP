<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Inventaris\TransferGudangController;
use App\Http\Controllers\Penjualan\QuotationController;
use App\Models\Customer;

// Purchase Order routes
Route::get('supplier/{id}/purchase-orders', [PurchaseOrderController::class, 'getBySupplier']);

// Inventory routes
Route::get('gudang/{id}/produks', [TransferGudangController::class, 'getProduksByGudang']);

// Quotation routes
Route::get('quotations', [QuotationController::class, 'getQuotationsForSelect'])->name('api.quotations');

// Customer routes
Route::get('customers/{id}', function ($id) {
    $customer = Customer::findOrFail($id);
    return response()->json([
        'id' => $customer->id,
        'nama' => $customer->nama,
        'company' => $customer->company,
        'alamat_utama' => $customer->alamat_utama,
        'alamat_pengiriman' => $customer->alamat_pengiriman
    ]);
});
