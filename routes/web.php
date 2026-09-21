<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Auth\GoogleAuthController;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

Route::resource('venues', VenueController::class);
Route::resource('events', EventController::class);

Route::get('/auth/login', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

require __DIR__.'/settings.php';
