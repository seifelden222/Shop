<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\OrderApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'Register']);
Route::post('/login', [AuthController::class, 'Login']);
Route::post('/logout', [AuthController::class, 'Logout'])->middleware('auth:sanctum');

// Cart Routes (with auth)
Route::apiResource('carts', CartApiController::class)->middleware('auth:sanctum');


// Order Routes (with auth)
Route::apiResource('orders', OrderApiController::class)->middleware('auth:sanctum');