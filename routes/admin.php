<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// ============================================
// Guest Routes (بدون middleware guest:admin)
// ============================================
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login-view');
    Route::post('/login', [AuthController::class, 'adminLogin'])->name('admin.login');
});

// ============================================
// Authenticated Routes
// ============================================
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    //// Logout =================================================================================

    Route::post('logout', [AuthController::class, 'adminLogout'])->name('logout');
    
// ==========================================[Recources]==================================
    // Product Management
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
        // Category Management

        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
            // Country Management
        Route::resource('countries', \App\Http\Controllers\Admin\CountryController::class);
         // City Management
         Route::resource('cities', \App\Http\Controllers\Admin\CityController::class);


});

// ============================================
// Route لإعادة التوجيه (اختياري)
// ============================================
Route::get('login', function () {
    return redirect()->route('admin.login-view');
})->name('login');
