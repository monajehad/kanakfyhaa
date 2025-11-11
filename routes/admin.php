<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// ============================================
// Guest Routes for Admin (middleware: guest:admin)
// ============================================
Route::prefix('admin')->middleware('guest:admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'adminLogin'])->name('login.submit');
    // Redirect if already logged in (just in case)
    Route::get('/', function () {
        return redirect()->route('admin.login');
    })->name('redirect');
});

// ============================================
// Authenticated Routes for Admin (middleware: auth:admin)
// ============================================
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'adminLogout'])->name('logout');

    // Redirect /admin to /admin/dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    })->name('home');

    // ================== [Resources] ===================
    // Product Management
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index','show','update']);

    // Category Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    // Country Management
    Route::resource('countries', \App\Http\Controllers\Admin\CountryController::class);

    // City Management
    Route::resource('cities', \App\Http\Controllers\Admin\CityController::class);


    Route::resource('landmarks', \App\Http\Controllers\Admin\LandmarkController::class);

    Route::resource('artifacts', \App\Http\Controllers\Admin\ArtifactController::class);
});




// ============================================
// Fallback to redirect /admin (not logged) to login
Route::get('admin', function () {
    return redirect()->route('admin.login');
})->middleware('guest:admin');


Route::get('login', function () {

    return redirect()->route('admin.login');
})->name('login');
