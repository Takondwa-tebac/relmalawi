<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;

// guest Routes 


Route::inertia('/',[GuestController::class,'index'])->name('home');

Route::group( function () {
   
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
