<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;


Route::get('/', [HomeController::class, 'index'])->name('home');

// Products Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Category Routes
// Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
// Atau jika ingin menggunakan route resource
// Route::resource('products', ProductController::class);

// Nanti bisa ditambah route lain seperti:
// Route::get('/products', [ProductController::class, 'index'])->name('products.index');
// Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');