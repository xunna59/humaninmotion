<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BagController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PlaceholderImageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// ── Demo placeholder imagery ──────────────────────────────────────
Route::get('/placeholder/{name}.svg', PlaceholderImageController::class)->name('placeholder');

// ── Storefront ────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/new-in', [ShopController::class, 'newIn'])->name('new-in');
Route::get('/best-sellers', [ShopController::class, 'bestSellers'])->name('best-sellers');
Route::get('/sale', [ShopController::class, 'sale'])->name('sale');

Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
Route::get('/collections/{slug}', [CollectionController::class, 'show'])->name('collection.show');

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/search', [SearchController::class, 'show'])->name('search');

Route::get('/bag', [BagController::class, 'show'])->name('bag');
Route::post('/bag/add', [BagController::class, 'add'])->name('bag.add');
Route::post('/bag/update', [BagController::class, 'update'])->name('bag.update');
Route::post('/bag/remove', [BagController::class, 'remove'])->name('bag.remove');
Route::post('/bag/coupon', [BagController::class, 'applyCoupon'])->name('bag.coupon');
Route::delete('/bag/coupon', [BagController::class, 'removeCoupon'])->name('bag.coupon.remove');

// ── Checkout (requires sign-in) ────────────────────────────────────
Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'show'])->name('index');
    Route::post('/', [CheckoutController::class, 'place'])->name('place');
    Route::get('/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('confirmation');
});

Route::get('/journal', [JournalController::class, 'index'])->name('journal.index');
Route::get('/journal/{slug}', [JournalController::class, 'show'])->name('journal.show');

Route::get('/pages/{slug}', [PageController::class, 'show'])->name('page.show');

// ── Newsletter ─────────────────────────────────────────────────────
Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// ── Customer account ───────────────────────────────────────────────
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [AccountController::class, 'orderDetails'])->name('orders.show');
    Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [AccountController::class, 'storeAddress'])->name('addresses.store');
    Route::post('/addresses/{address}', [AccountController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AccountController::class, 'deleteAddress'])->name('addresses.destroy');
    Route::get('/wishlist', [AccountController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/{product}', [AccountController::class, 'removeWishlistItem'])->name('wishlist.remove');
    Route::get('/details', [AccountController::class, 'details'])->name('details');
    Route::post('/details', [AccountController::class, 'updateDetails'])->name('details.update');
    Route::get('/password', [AccountController::class, 'password'])->name('password');
    Route::post('/password', [AccountController::class, 'updatePassword'])->name('password.update');
});

// ── CDN-ready public assets ────────────────────────────────────────
Route::get('/unknown', fn () => abort(404))->where('slug', '.*');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';