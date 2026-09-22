<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomeSectionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/duplicate', [ProductController::class, 'duplicate'])->name('products.duplicate');
        Route::post('/products/{product}/status', [ProductController::class, 'updateStatus'])->name('products.status');

        // Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');

        // Collections
        Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
        Route::get('/collections/create', [CollectionController::class, 'create'])->name('collections.create');
        Route::post('/collections', [CollectionController::class, 'store'])->name('collections.store');
        Route::get('/collections/{collection}/edit', [CollectionController::class, 'edit'])->name('collections.edit');
        Route::put('/collections/{collection}', [CollectionController::class, 'update'])->name('collections.update');
        Route::delete('/collections/{collection}', [CollectionController::class, 'destroy'])->name('collections.destroy');

        // Orders
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');

        // Customers
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{user}', [CustomerController::class, 'show'])->name('customers.show');
        Route::put('/customers/{user}', [CustomerController::class, 'update'])->name('customers.update');

        // Coupons
        Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
        Route::get('/coupons/create', [CouponController::class, 'create'])->name('coupons.create');
        Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
        Route::get('/coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('coupons.edit');
        Route::put('/coupons/{coupon}', [CouponController::class, 'update'])->name('coupons.update');
        Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');

        // Promotions
        Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');
        Route::get('/promotions/create', [PromotionController::class, 'create'])->name('promotions.create');
        Route::post('/promotions', [PromotionController::class, 'store'])->name('promotions.store');
        Route::get('/promotions/{promotion}/edit', [PromotionController::class, 'edit'])->name('promotions.edit');
        Route::put('/promotions/{promotion}', [PromotionController::class, 'update'])->name('promotions.update');
        Route::delete('/promotions/{promotion}', [PromotionController::class, 'destroy'])->name('promotions.destroy');

        // Reviews
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

        // Returns
        Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
        Route::get('/returns/{returnRequest}', [ReturnController::class, 'show'])->name('returns.show');
        Route::put('/returns/{returnRequest}', [ReturnController::class, 'update'])->name('returns.update');

        // Homepage + settings
        Route::get('/home', [HomeSectionController::class, 'index'])->name('home.index');
        Route::get('/home/create', [HomeSectionController::class, 'create'])->name('home.create');
        Route::post('/home', [HomeSectionController::class, 'store'])->name('home.store');
        Route::get('/home/{section}/edit', [HomeSectionController::class, 'edit'])->name('home.edit');
        Route::put('/home/{section}', [HomeSectionController::class, 'update'])->name('home.update');
        Route::delete('/home/{section}', [HomeSectionController::class, 'destroy'])->name('home.destroy');
        Route::post('/home/reorder', [HomeSectionController::class, 'reorder'])->name('home.reorder');

        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });