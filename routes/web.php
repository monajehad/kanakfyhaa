<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\Website\HomeController;

// Main Page Route
Route::get('/', HomeController::class)->name('pages-home');


require __DIR__ . '/admin.php';