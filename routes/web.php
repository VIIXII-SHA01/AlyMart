<?php
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// kani para makita ang login page
Route::get('/login', function () {
    return view('cover-login.login');
})->name('login')->middleware('guest');

// para sa login gamit ajax
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// ug mo logout pud
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout')->middleware('auth');

// moadtu ni sya sa forgot passsword na page
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request')->middleware('guest');

// default nga route kung asa moadto ang user kung mo access sa root URL
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : view('welcome');
})->name('home');

// dli pede ma navigate kung wala ka login ug dili mao ilang roles
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // kani mao ni para sa admin routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('for_admin.dashboard');
        })->name('for_admin.dashboard');
    });
    
    // kanni para sa cashier wala nay lain makahilabot dree
    Route::middleware(['role:cashier'])->prefix('cashier')->group(function () {
        Route::get('/dashboard', function () {
            return view('for_cashier.dashboard');
        })->name('for_cashier.dashboard');
    });
    
    // para rani sa inventory staff dre 
    Route::middleware(['role:inventory_staff'])->prefix('inventory')->group(function () {
        Route::get('/dashboard', function () {
            return view('for_inventory_staff.dashboard');
        })->name('for_inventory_staff.dashboard');
    });
});