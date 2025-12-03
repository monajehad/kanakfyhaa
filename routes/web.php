<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\PaymentController;

// Main Page Route
Route::get('/', HomeController::class)->name('pages-home');

// Search Routes
Route::get('/search', [\App\Http\Controllers\Website\SearchController::class, 'index'])->name('search');
Route::get('/api/search', [\App\Http\Controllers\Website\SearchController::class, 'search'])->name('search.api');

// Load More Products API
Route::get('/api/products/load-more', [HomeController::class, 'loadMore'])->name('products.load-more');

// Load More Cities API
Route::get('/api/cities', [HomeController::class, 'loadCities'])->name('cities.load');

// Checkout Route
Route::get('/checkout', \App\Http\Controllers\Website\CheckoutController::class)->name('checkout');

// Payments
Route::post('/api/orders', [PaymentController::class, 'storeOrder'])->name('orders.store');
Route::post('/api/stripe/create-intent', [PaymentController::class, 'stripeCreatePaymentIntent'])->name('stripe.intent');
Route::post('/webhooks/stripe', [PaymentController::class, 'stripeWebhook'])->name('stripe.webhook');

// Beautiful URL for experience page; keep route name for compatibility
Route::get(uri: '/discover/{uuid}', action: [App\Http\Controllers\ExperienceController::class, 'show'])->name('experience.show');

// Backward-compatible redirect from old /experience/{uuid}
Route::get('/experience/{uuid}', function ($uuid) {
	return redirect()->route('experience.show', ['uuid' => $uuid], 301);
});

// Order success
Route::view('/order-success', 'website.layout.pages.order-success')->name('order.success');

// Product details
Route::get('/product/{uuid}', [\App\Http\Controllers\Website\ProductController::class, 'show'])->name('product.show');

// Language switcher
Route::get('/lang/{locale}', [LanguageController::class, 'swap'])->name('lang.swap');


require __DIR__ . '/admin.php';