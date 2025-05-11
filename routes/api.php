<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PurchaseOrderController;

// Purchase Order routes
Route::get('supplier/{id}/purchase-orders', [PurchaseOrderController::class, 'getBySupplier']);
