<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    
    // Rute Dashboard Admin
    Route::get('/dashboard', function () {
        // Mengarahkan ke views/admin/dashboard.blade.php
        return view('admin.dashboard'); 
    })->name('admin.dashboard'); 

    Route::resource('products', ProductController::class)->except(['show', 'index']);
    
    // CRUD Kategori
    Route::resource('categories', CategoryController::class)->except(['show', 'index']);
    
    // Kelola Order (Edit, Update, Delete Order oleh Admin)
    Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('customer.dashboard');
    })->name('dashboard'); 
});


// Semua user (termasuk yang belum login) bisa lihat kategori
Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Semua user (termasuk yang belum login) bisa lihat produk
Route::get('products', [ProductController::class, 'index'])->name('products.index');
Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');


// === RUTE ORDER (Pesanan) Customer ===
Route::middleware('auth')->group(function () {
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});


// === FALLBACK ===
Route::fallback(function () {
    abort(404, 'Halaman tidak ditemukan.');
});