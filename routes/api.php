<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API Routes
Route::prefix('v1')->name('api.v1.')->group(function () {
    // Public book listing
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    
    // Authentication endpoints
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::post('/register', [UserController::class, 'register'])->name('register');
});

// Protected API Routes
Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.v1.')->group(function () {
    // User endpoints
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    
    // Book management
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    
    // User's books
    Route::get('/my-books', [BookController::class, 'myBooks'])->name('my-books');
});

// Admin API Routes
Route::middleware(['auth:sanctum', 'admin'])->prefix('v1/admin')->name('api.v1.admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    Route::get('/books/all', [BookController::class, 'allBooks'])->name('books.all');
    Route::delete('/books/{book}/force', [BookController::class, 'forceDestroy'])->name('books.force-destroy');
});