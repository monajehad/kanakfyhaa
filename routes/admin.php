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
    
    Route::post('logout', [AuthController::class, 'adminLogout'])->name('logout');
    // Logout
    
    // يمكنك إضافة routes إضافية هنا
});

// ============================================
// Route لإعادة التوجيه (اختياري)
// ============================================
Route::get('login', function () {
    return redirect()->route('admin.login-view');
})->name('login');