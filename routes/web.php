<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use PHPUnit\Event\Code\Test;

Route::get('/', [HomeController::class, 'index'])->name('welcome');

// Resource routes for the shop

Route::get('/dashboard', [CartController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';





Route::middleware('auth')->group(function () {
    // Cart routes
    Route::resource('products', ProductController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('orders', OrderController::class);
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/{cartId}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/quick-add', [CartController::class, 'quickAdd'])->name('cart.quick-add');
});
