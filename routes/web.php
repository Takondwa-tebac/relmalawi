<?php

use App\Http\Controllers\Guest\AboutController;
use App\Http\Controllers\Guest\ContactController;
use App\Http\Controllers\Guest\FaqController;
use App\Http\Controllers\Guest\HomeController;
use App\Http\Controllers\Guest\HowItWorksController;
use App\Http\Controllers\Guest\PartnershipsController;
use App\Http\Controllers\Guest\PeopleController;
use App\Http\Controllers\Guest\RafflesController;
use App\Http\Controllers\Guest\RegulationController;
use App\Http\Controllers\Guest\TechnologyController;
use Illuminate\Support\Facades\Route;

// Home is always on. Every other public page can be switched off in the CMS (Pages),
// which makes the `page.active:{slug}` middleware answer 404 for visitors.
Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutController::class)->middleware('page.active:about')->name('about');
Route::get('/how-it-works', HowItWorksController::class)->middleware('page.active:how-it-works')->name('how-it-works');
Route::get('/raffles', RafflesController::class)->middleware('page.active:raffles')->name('raffles');
Route::get('/partnerships', PartnershipsController::class)->middleware('page.active:partnerships')->name('partnerships');
Route::get('/technology', TechnologyController::class)->middleware('page.active:technology')->name('technology');
Route::get('/people', PeopleController::class)->middleware('page.active:people')->name('people');
Route::get('/regulation', RegulationController::class)->middleware('page.active:regulation')->name('regulation');
Route::get('/faq', FaqController::class)->middleware('page.active:faq')->name('faq');
Route::get('/contact', ContactController::class)->middleware('page.active:contact')->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware(['page.active:contact', 'throttle:contact'])->name('contact.store');

require __DIR__.'/settings.php';
