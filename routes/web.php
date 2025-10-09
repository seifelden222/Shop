<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use PHPUnit\Event\Code\Test;

Route::get('/', [HomeController::class, 'index'])->name('welcome');


Route::get('/contact', function () {
    return view('contact');
})->name('contact');
// Handle contact form submissions (rate limited to prevent abuse)
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:10,1')->name('contact.store');
// Resource routes for the shop

Route::get('/dashboard', [CartController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';




// Legacy path redirect: prevent /products/mine being captured by the products.show resource
Route::get('/products/mine', function () {
    return redirect()->route('products.mine');
});

Route::resource('products', ProductController::class);
Route::resource('brands', BrandController::class);
Route::resource('categories', CategoryController::class);

// My Products - products created by the authenticated user
Route::middleware('auth')->get('/my-products', [ProductController::class, 'mine'])->name('products.mine');

// Search route (site-wide)
Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::middleware('auth')->group(function () {
    // Cart routes
    Route::resource('orders', OrderController::class);
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/{cartId}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/quick-add', [CartController::class, 'quickAdd'])->name('cart.quick-add'); 
    
    //favorite routes
    // Index (list) — serve at /favorites (GET) and name it favorites.index so controller redirects work
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{favoriteId}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

});
