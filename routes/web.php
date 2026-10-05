<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;


Route::get('/', [GuestController::class, 'index'])->name('home');
Route::get('/about', [GuestController::class, 'about'])->name('about');
Route::get('/how-it-works', [GuestController::class, 'howItWorks'])->name('how-it-works');
Route::get('/people', [GuestController::class, 'people'])->name('people');
Route::get('/technology', [GuestController::class, 'technology'])->name('technology');
Route::get('/raffles', [GuestController::class, 'raffles'])->name('raffles');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
