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
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'adminLogout'])->name('logout');

    // Redirect /admin to /admin/dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    })->name('home');

    // ================== [Resources] ===================
    // Product Management
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::delete('/products/{product}/media/{media}', [\App\Http\Controllers\Admin\ProductController::class, 'deleteMedia'])->name('products.deleteMedia');
    
    // Product Packages Management
    Route::resource('products.packages', \App\Http\Controllers\Admin\ProductPackageController::class)->except('show')->scoped([
        'product' => 'id',
        'package' => 'id',
    ]);
    
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index','show','update']);

    // Category Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    // Country Management
    Route::resource('countries', \App\Http\Controllers\Admin\CountryController::class);

    // City Management
    Route::resource('cities', \App\Http\Controllers\Admin\CityController::class);
    
    // Media Management (for deleting media files)
    Route::delete('/media/{media}', [\App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('media.destroy');


    Route::resource('landmarks', \App\Http\Controllers\Admin\LandmarkController::class);

    // Slider Management
    Route::resource('sliders', \App\Http\Controllers\Admin\SliderController::class);

    // Settings Management
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'store'])->name('settings.store');
    Route::get('/settings/group/{group}', [\App\Http\Controllers\Admin\SettingsController::class, 'getByGroup'])->name('settings.group');

    // Backup Management
    Route::get('/backup', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/export', [\App\Http\Controllers\Admin\BackupController::class, 'export'])->name('backup.export');
    Route::post('/backup/import', [\App\Http\Controllers\Admin\BackupController::class, 'import'])->name('backup.import');
    Route::delete('/backup/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'delete'])->name('backup.delete');
});




// ============================================
// Fallback to redirect /admin (not logged) to login
Route::get('admin', function () {
    return redirect()->route('admin.login');
})->middleware('guest:admin');


Route::get('login', function () {

    return redirect()->route('admin.login');
})->name('login');
