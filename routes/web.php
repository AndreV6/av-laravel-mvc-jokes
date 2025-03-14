<?php

/**
 * Assessment Title: Portfolio Part 3
 * Cluster: SaaS: Fron-End Dev - ICT50220 (Advanced Programming)
 * Qualification: ICT50220 Diploma of Information Technology (Advanced Programming)
 * Name: Andre Velevski
 * Student ID: 20094240
 * Year/Semester: 2024/S2
 *
 * Controller for managing jokes in the system
 */

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JokeController;
use App\http\Controllers\RolesAndPermissionController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () { return view('welcome');});

/**
 * Static page routes accessible to all visitors
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

/**
 * profile auth endpoints
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/**
 * Admin routes for role and permission management
 * Accessible only to superusers and administrators
 */
Route::middleware(['auth', 'role:superuser|administrator'])->prefix('admin')->name('admin.')->group(function () {

    /**
     * User role/permission route
     */
    Route::get('/users/{user}/roles', [RolesAndPermissionController::class, 'assignRole'])
        ->name('roles.assign');

    /**
     * User Roles assignment
     */
    Route::patch('/users/{user}/roles', [RolesAndPermissionController::class, 'updateUserRoles'])
        ->name('roles.update-user');

    /**
     * User permission assignment
     */
    Route::patch('/users/{user}/permissions', [RolesAndPermissionController::class, 'updateUserPermissions'])
        ->name('permissions.update-user');
});

/**
 * Authenticated user routes protected by Sanctum
 * Includes joke and user management features
 */
Route::middleware(['auth:sanctum'])->group(function () {
    // Joke trash routes
    Route::get('/jokes/trashed', [JokeController::class, 'trashed'])
        ->name('jokes.trashed')
        ->middleware('permission:joke.delete|joke.restore|joke.force-delete');
    Route::patch('/jokes/{id}/restore', [JokeController::class, 'restore'])
        ->name('jokes.restore')
        ->middleware('permission:joke.restore');
    Route::delete('/jokes/{id}/force-delete', [JokeController::class, 'forceDelete'])
        ->name('jokes.force-delete')
        ->middleware('permission:joke.force-delete');

    // User trash routes
    Route::get('/users/trashed', [UserController::class, 'trashed'])
        ->name('users.trashed')
        ->middleware('permission:user.delete|user.restore|user.force-delete');
    Route::patch('/users/{id}/restore', [UserController::class, 'restore'])
        ->name('users.restore')
        ->middleware('permission:user.restore');
    Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete'])
        ->name('users.force-delete')
        ->middleware('permission:user.force-delete');
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
 * public jokes routes
 */
Route::get('/jokes', [JokeController::class, 'index'])->name('jokes.index');
Route::get('/jokes/{joke}', [JokeController::class, 'show'])->name('jokes.show');

require __DIR__.'/auth.php';
