<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\Website\HomeController;

// Main Page Route
Route::get('/', HomeController::class)->name('pages-home');
 Route::get(uri: '/experience/{uuid}', action: [App\Http\Controllers\ExperienceController::class, 'show'])->name('experience.show');


require __DIR__ . '/admin.php';