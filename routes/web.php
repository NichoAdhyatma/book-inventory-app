<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Profile\ChangePassword;
use App\Livewire\Profile\EditProfile;
use App\Livewire\UserList;
use App\Livewire\BookManagement;
use App\Livewire\LandingPage;
use App\Livewire\Home;

/*
|--------------------------------------------------------------------------
| Public Routes (Accessible by everyone)
|--------------------------------------------------------------------------
*/

Route::get('/', LandingPage::class)->name('landing-page');

/*
|--------------------------------------------------------------------------
| Guest Routes (Only for non-authenticated users)
|--------------------------------------------------------------------------
*/

Route::middleware(['guest'])->group(function () {
    // Authentication Routes
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');

    // Password Reset Routes
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

/*
|--------------------------------------------------------------------------
| Email Verification Route (Can be accessed by authenticated but unverified users)
|--------------------------------------------------------------------------
*/


Route::get('/email/verify/{id}/{hash}', VerifyEmail::class)
    ->middleware(['signed'])
    ->name('verification.verify');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');

Route::post('/email/verification-notification', function () {
    auth()->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Verification link sent!');
})->middleware(['throttle:6,1'])->name('verification.send');


/*
|--------------------------------------------------------------------------
| Authenticated Routes (Requires login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Home/Dashboard
    Route::get('/home', Home::class)->name('home');
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    });

    // User Management
    Route::get('/users', UserList::class)->name('users');

    // Book Management
    Route::get('/books', BookManagement::class)->name('books');
    Route::get('/books/create', BookManagement::class)->name('books.create');
    Route::get('/books/{book}/edit', BookManagement::class)->name('books.edit');

    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', EditProfile::class)->name('index');
        Route::get('/change-password', ChangePassword::class)->name('password');
    });

    // Logout Route
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Requires admin role)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/users', function () {
        return view('admin.users');
    })->name('users');

    Route::get('/books', function () {
        return view('admin.books');
    })->name('books');
});

/*
|--------------------------------------------------------------------------
| API Routes for Livewire Components
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->prefix('api')->name('api.')->group(function () {
    Route::get('/users/search', function () {
        return \App\Models\User::where('name', 'like', '%' . request('q') . '%')
            ->orWhere('email', 'like', '%' . request('q') . '%')
            ->limit(10)
            ->get();
    })->name('users.search');

    Route::get('/books/search', function () {
        return \App\Models\Book::where('title', 'like', '%' . request('q') . '%')
            ->orWhere('author', 'like', '%' . request('q') . '%')
            ->limit(10)
            ->get();
    })->name('books.search');
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return view('errors.404');
});