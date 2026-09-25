<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\CustomerEventsController;
use App\Http\Controllers\CustomerVenuesController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SeatLockController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {
        Route::resource('venues', VenueController::class);
        Route::resource('events', EventController::class);
    });

Route::get('/auth/login', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

Route::get('events', [CustomerEventsController::class, 'index'])->name('customer.events');
Route::get('events/{event}', [CustomerEventsController::class, 'show'])->name('customer.showEvent');

Route::get('venues', [CustomerVenuesController::class, 'index'])->name('customer.venues');

Route::middleware('auth')->group(function () {
    Route::post('/seats/{seat}/lock', [SeatLockController::class, 'lock'])->name('seat.lock');
    Route::post('/seats/{seat}/release', [SeatLockController::class, 'release'])->name('seat.release');
});

require __DIR__.'/settings.php';
