<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Inventaris\TransferGudangController;

// Purchase Order routes
Route::get('supplier/{id}/purchase-orders', [PurchaseOrderController::class, 'getBySupplier']);

// Inventory routes
Route::get('gudang/{id}/produks', [TransferGudangController::class, 'getProduksByGudang']);