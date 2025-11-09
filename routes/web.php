<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// === AUTH ===
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// === DASHBOARD ===
Route::get('/dashboard', function () {
    $user = session('user');

    if (!$user) {
        return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu!']);
    }

    if ($user->role === 'admin') {
        return view('admin.dashboard');
    }

    return view('customer.dashboard');
})->name('dashboard');

// === CATEGORY ===
Route::middleware('admin')->group(function () {
    Route::resource('categories', CategoryController::class)->except(['show', 'index']);
});

// semua user bisa lihat kategori
Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// === PRODUCT ===

// Semua user bisa lihat produk
Route::get('products', [ProductController::class, 'index'])->name('products.index');
Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

// Admin khusus kelola produk
Route::middleware('admin')->prefix('admin')->group(function () {
    Route::resource('products', ProductController::class)->except(['show', 'index']);
});

// === ORDER ===
Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');

Route::middleware('admin')->group(function () {
    Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
});

Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

Route::fallback(function () {
    abort(404, 'Halaman tidak ditemukan.');
});
