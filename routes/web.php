<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\OrderHistoryController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\AffiliateController;
use App\Http\Controllers\EarnController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Storefront
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// SEO sitemap
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.show');

// Product reviews
Route::post('/product/{product:slug}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])
    ->middleware('auth')->name('product.reviews.store');

// Cart
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Checkout (cart icon points here)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// Order success + invoice
Route::get('/order/{order:order_number}/success', [OrderController::class, 'success'])->name('order.success');

// Content pages
Route::get('/page/{page:slug}', [PageController::class, 'show'])->name('page.show');

// Earn with us (affiliate signup)
Route::get('/earn', [EarnController::class, 'index'])->name('earn');
Route::post('/earn', [EarnController::class, 'store'])->name('earn.store');

/*
|--------------------------------------------------------------------------
| Auth (customers + contributors)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.store');
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| User Dashboard
|--------------------------------------------------------------------------
*/
require __DIR__ . '/admin.php';

Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Orders
    Route::get('/orders', [OrderHistoryController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [OrderHistoryController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [OrderHistoryController::class, 'invoice'])->name('orders.invoice');

    // Profile & password
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    // Affiliate / earnings
    Route::get('/earnings', [AffiliateController::class, 'earnings'])->name('earnings');
    Route::get('/withdrawals', [AffiliateController::class, 'withdrawals'])->name('withdrawals');
    Route::post('/withdrawals', [AffiliateController::class, 'requestWithdrawal'])->name('withdrawals.request');
    Route::get('/bank', [AffiliateController::class, 'bank'])->name('bank');
    Route::post('/bank', [AffiliateController::class, 'saveBank'])->name('bank.save');
});
