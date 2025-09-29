<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TestCartController;
use Illuminate\Support\Facades\Route;
use PHPUnit\Event\Code\Test;

Route::get('/', [HomeController::class, 'index'])->name('welcome');

// Resource routes for the shop

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);
Route::resource('brands', BrandController::class);
Route::resource('orders', OrderController::class);

// Route::resource('carts', CartController::class)->middleware('auth');
//  Quick add to cart route
// Route::post('/cart/quick-add', [CartController::class, 'quickAdd'])->name('cart.quick-add')->middleware('auth');




// Testing routes
Route::get('/test',[TestCartController::class,'index'])->name('test.index')->middleware('auth');
Route::post('/test/quick-add',[TestCartController::class,'quickAdd'])->name('test.quick-add')->middleware('auth');