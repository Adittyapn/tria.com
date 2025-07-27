<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Nanti bisa ditambah route lain seperti:
// Route::get('/products', [ProductController::class, 'index'])->name('products.index');
// Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');