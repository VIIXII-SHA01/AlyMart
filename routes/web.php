<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// Redirect root to login or dashboard
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Account status pages (no auth required)
Route::get('/account/deactivated', [AccountController::class, 'deactivated'])->name('account.deactivated');
Route::get('/account/contact-support', [AccountController::class, 'contactSupport'])->name('account.contact-support');

// Dashboard with role-based access
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'user.status'])
    ->name('dashboard');

// Admin routes
Route::middleware(['auth', 'user.status', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
});

// Sales routes  
Route::middleware(['auth', 'user.status', 'role:admin,cashier'])->prefix('sales')->name('sales.')->group(function () {
    Route::get('/', [SalesController::class, 'index'])->name('index');
    Route::get('/create', [SalesController::class, 'create'])->name('create');
    Route::post('/', [SalesController::class, 'store'])->name('store');
    Route::get('/{sale}', [SalesController::class, 'show'])->name('show');
    Route::get('/{sale}/edit', [SalesController::class, 'edit'])->name('edit');
    Route::put('/{sale}', [SalesController::class, 'update'])->name('update');
    Route::delete('/{sale}', [SalesController::class, 'destroy'])->name('destroy');
    Route::get('/{sale}/receipt', [SalesController::class, 'receipt'])->name('receipt');
    Route::get('/api/product/{id}', [SalesController::class, 'getProductDetails'])->name('api.product');
});

// Inventory Staff routes
Route::middleware(['auth', 'user.status', 'role:admin,inventory_staff'])->prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('index');
    Route::get('/create', [InventoryController::class, 'create'])->name('create');
    Route::post('/', [InventoryController::class, 'store'])->name('store');
    Route::get('/low-stock', [InventoryController::class, 'lowStock'])->name('low-stock');
    Route::get('/out-of-stock', [InventoryController::class, 'outOfStock'])->name('out-of-stock');
    Route::get('/reports', [InventoryController::class, 'reports'])->name('reports');
    Route::post('/bulk-update', [InventoryController::class, 'bulkUpdate'])->name('bulk-update');
    Route::get('/api/product/{id}', [InventoryController::class, 'getProductDetails'])->name('api.product');
    Route::get('/{movement}', [InventoryController::class, 'show'])->name('show');
});

// Product Management routes
Route::middleware(['auth', 'user.status', 'role:admin,inventory_staff'])->prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    Route::patch('/{product}/stock', [ProductController::class, 'updateStock'])->name('stock.update');
});

// System Maintenance (admin only)
Route::middleware(['auth', 'user.status', 'role:admin'])->group(function () {
    Route::get('/system/maintenance', [SystemController::class, 'maintenance'])->name('system.maintenance');
    Route::post('/system/cleanup', [SystemController::class, 'runCleanup'])->name('system.cleanup');
    Route::get('/system/statistics', [SystemController::class, 'statistics'])->name('system.statistics');
});

// Common authenticated routes
Route::middleware(['auth', 'user.status'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

require __DIR__.'/auth.php';
