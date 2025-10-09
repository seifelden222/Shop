<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\BrandApiController;
use App\Http\Controllers\BrandController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'Register']);
Route::post('/login', [AuthController::class, 'Login']);
Route::post('/logout', [AuthController::class, 'Logout'])->middleware('auth:sanctum');

// Public Routes
// Route::apiResource('products', ProductApiController::class)->only(['index', 'show']);
// Route::apiResource('brands', BrandApiController::class)->only(['index', 'show']);

// Brand Analytics Routes
Route::get('/brands/analytics', [BrandApiController::class, 'analytics']);
Route::get('/brands/{brand}/products', [BrandApiController::class, 'products']);

// Cart & Order Routes (with auth) - name routes with the 'api.' prefix to avoid collisions with web routes
Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::apiResource('carts', CartApiController::class);
    Route::apiResource('orders', OrderApiController::class);
});

    Route::get('/brands/total-products/{brandId?}', [BrandController::class, 'TotalProducts']);
