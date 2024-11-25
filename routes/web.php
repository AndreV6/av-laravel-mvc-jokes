<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JokeController;
use App\http\Controllers\RolesAndPermissionController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () { return view('welcome');});

/**
 * static pages routes
 */
Route::get('/', [StaticPageController::class, 'home'])
    ->name('static.home');

Route::get('/about', [StaticPageController::class, 'about'])
    ->name('static.about');

Route::get('/contact', [StaticPageController::class, 'contact'])
    ->name('static.contact');


/**
 * users auth endpoints
 */
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:superuser|administrator'])->prefix('admin')->name('admin.')->group(function () {

    /**
     * User role/permission route
     */
    Route::get('/users/{user}/roles', [RolesAndPermissionController::class, 'assignRole'])->name('roles.assign');

    /**
     * User Roles assignment
     */
    Route::patch('/users/{user}/roles', [RolesAndPermissionController::class, 'updateUserRoles'])->name('roles.update-user');

    /**
     * User permission assignment
     */
    Route::patch('/users/{user}/permissions', [RolesAndPermissionController::class, 'updateUserPermissions'])->name('permissions.update-user');
});

Route::middleware(['auth:sanctum'])->group(function () {
    /**
     * auth users routes
     */

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    /**
     * auth jokes routes
     */
    Route::get('/jokes/create', [JokeController::class, 'create'])->name('jokes.create');
    Route::post('/jokes', [JokeController::class, 'store'])->name('jokes.store');
    Route::get('/jokes/{joke}/edit', [JokeController::class, 'edit'])->name('jokes.edit');
    Route::patch('/jokes/{joke}', [JokeController::class, 'update'])->name('jokes.update');
    Route::delete('/jokes/{joke}', [JokeController::class, 'destroy'])->name('jokes.destroy');
});

/**
 * jokes routes
 */
Route::get('/jokes', [JokeController::class, 'index'])->name('jokes.index');
Route::get('/jokes/{joke}', [JokeController::class, 'show'])->name('jokes.show');

require __DIR__.'/auth.php';
