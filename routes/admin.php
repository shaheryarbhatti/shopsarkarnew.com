<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContributorController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WithdrawalController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

    // Auth (public)
    Route::get('login', [AuthController::class, 'show'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.store');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Protected
    Route::middleware('admin')->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::middleware('permission:products.view')->group(function () {
            Route::get('products', [ProductController::class, 'index'])->name('products.index');
        });
        Route::middleware('permission:products.create')->group(function () {
            Route::get('products-create/new', [ProductController::class, 'create'])->name('products.create');
            Route::post('products', [ProductController::class, 'store'])->name('products.store');
        });
        Route::middleware('permission:products.edit')->group(function () {
            Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('products/{product}/media/{media}', [ProductController::class, 'deleteMedia'])->name('products.media.delete');
        });
        Route::middleware('permission:products.delete')->group(function () {
            Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        });

        // Categories
        Route::middleware('permission:categories.manage')->group(function () {
            Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);
        });

        // Orders
        Route::middleware('permission:orders.view')->group(function () {
            Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
            Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
            Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
        });
        Route::middleware('permission:orders.edit')->group(function () {
            Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
        });
        Route::middleware('permission:orders.delete')->group(function () {
            Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
        });

        // Customers
        Route::middleware('permission:customers.view')->group(function () {
            Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
            Route::get('customers/{user}', [CustomerController::class, 'show'])->name('customers.show');
        });
        Route::middleware('permission:customers.delete')->group(function () {
            Route::delete('customers/{user}', [CustomerController::class, 'destroy'])->name('customers.destroy');
        });

        // Reviews
        Route::middleware('permission:reviews.view')->group(function () {
            Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
        });
        Route::middleware('permission:reviews.manage')->group(function () {
            Route::put('reviews/{review}/status', [ReviewController::class, 'updateStatus'])->name('reviews.status');
            Route::put('reviews/{review}/link', [ReviewController::class, 'saveLink'])->name('reviews.link');
            Route::post('reviews/{review}/youtube', [ReviewController::class, 'uploadYoutube'])->name('reviews.youtube');
            Route::delete('reviews/{review}/youtube', [ReviewController::class, 'deleteYoutube'])->name('reviews.youtube.delete');
            Route::delete('reviews/{review}/video', [ReviewController::class, 'deleteVideo'])->name('reviews.video.delete');
            Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
        });

        // Contributors
        Route::middleware('permission:contributors.view')->group(function () {
            Route::get('contributors', [ContributorController::class, 'index'])->name('contributors.index');
            Route::get('contributors/{user}', [ContributorController::class, 'show'])->name('contributors.show');
        });

        // Withdrawals
        Route::middleware('permission:withdrawals.view')->group(function () {
            Route::get('withdrawals', [WithdrawalController::class, 'index'])->name('withdrawals.index');
            Route::get('withdrawals/{withdrawal}', [WithdrawalController::class, 'show'])->name('withdrawals.show');
        });
        Route::middleware('permission:withdrawals.manage')->group(function () {
            Route::put('withdrawals/{withdrawal}', [WithdrawalController::class, 'update'])->name('withdrawals.update');
        });

        // Expenses
        Route::middleware('permission:expenses.view')->group(function () {
            Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        });
        Route::middleware('permission:expenses.manage')->group(function () {
            Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
            Route::put('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
            Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
        });

        // Settings
        Route::middleware('permission:settings.manage')->group(function () {
            Route::get('settings/{group?}', [SettingController::class, 'edit'])->name('settings.edit');
            Route::post('settings/{group}', [SettingController::class, 'update'])->name('settings.update');
        });

        // Pages
        Route::middleware('permission:pages.manage')->group(function () {
            Route::resource('pages', PageController::class)->except(['show']);
        });

        // Users / Staff
        Route::middleware('permission:users.manage')->group(function () {
            Route::resource('users', UserController::class)->except(['show']);
        });

        // Roles & permissions
        Route::middleware('permission:roles.manage')->group(function () {
            Route::resource('roles', RoleController::class)->except(['show']);
        });
    });
});
