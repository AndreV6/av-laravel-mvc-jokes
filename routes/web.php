<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaticPageController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () { return view('welcome');});
Route::get('/', [StaticPageController::class, 'home'])
    ->name('static.home');

Route::get('/about', [StaticPageController::class, 'about'])
    ->name('static.about');

Route::get('/contact', [StaticPageController::class, 'contact'])
    ->name('static.contact');

Route::get('/index', [StaticPageController::class, 'index'])
    ->name('static.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
