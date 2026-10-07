<?php

use App\Http\Controllers\Guest\AboutController;
use App\Http\Controllers\Guest\ContactController;
use App\Http\Controllers\Guest\HomeController;
use App\Http\Controllers\Guest\HowItWorksController;
use App\Http\Controllers\Guest\PartnershipsController;
use App\Http\Controllers\Guest\PeopleController;
use App\Http\Controllers\Guest\RafflesController;
use App\Http\Controllers\Guest\RegulationController;
use App\Http\Controllers\Guest\TechnologyController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutController::class)->name('about');
Route::get('/how-it-works', HowItWorksController::class)->name('how-it-works');
Route::get('/raffles', RafflesController::class)->name('raffles');
Route::get('/partnerships', PartnershipsController::class)->name('partnerships');
Route::get('/technology', TechnologyController::class)->name('technology');
Route::get('/people', PeopleController::class)->name('people');
Route::get('/regulation', RegulationController::class)->name('regulation');
Route::get('/contact', ContactController::class)->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:contact')->name('contact.store');

require __DIR__.'/settings.php';
