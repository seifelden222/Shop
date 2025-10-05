<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;

Route::get('/', function () {
    return response()->json([
        'message' => 'Central Payment API',
        'version' => '1.0.0',
        'status' => 'active'
    ]);
});

// CSRF Token Refresh Endpoint
Route::get('/csrf-token', function () {
    return response()->json([
        'csrf_token' => csrf_token()
    ]);
});

// Checkout Routes (Legacy - will be deprecated)
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/process', [CheckoutController::class, 'processProvider'])->name('checkout.process');
Route::get('/checkout/success/{transaction}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel/{transaction}', [CheckoutController::class, 'cancel'])->name('checkout.cancel');



use App\Http\Controllers\PayTestController;

Route::get('/pay/test', [PayTestController::class, 'showForm'])->name('pay.test.form');
Route::post('/pay/test', [PayTestController::class, 'create'])->name('pay.test.create');
Route::get('/pay/success', [PayTestController::class, 'success'])->name('pay.success');
Route::get('/pay/cancel', [PayTestController::class, 'cancel'])->name('pay.cancel');
