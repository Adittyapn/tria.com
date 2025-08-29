<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ShippingController;

// ===============================================
// 🏠 HOME
// ===============================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ===============================================
// 🔑 AUTHENTICATION
// ===============================================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Forgot Password
    Route::get('/forgot-password', function() {
        return view('auth.forgot-password');
    })->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ===============================================
// 📦 PRODUCT
// ===============================================
Route::prefix('product')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{product:slug}', [ProductController::class, 'show'])->name('show');
    Route::post('/{product}/calculate-price', [ProductController::class, 'calculatePrice'])->name('calculate-price');
});

// ===============================================
// 🛒 CART
// ===============================================
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::patch('/{cart}', [CartController::class, 'update'])->name('update');
    Route::delete('/{cart}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

// ===============================================
// 💳 CHECKOUT
// ===============================================
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    Route::post('/buy-now/{product}', [CheckoutController::class, 'buyNow'])->name('buy-now');

    Route::get('/cities', [CheckoutController::class, 'getCities'])->name('get-cities');
    Route::get('/districts', [CheckoutController::class, 'getDistricts'])->name('get-districts');
    Route::post('/calculate-shipping', [CheckoutController::class, 'calculateShipping'])->name('calculate-shipping');
});

// ===============================================
// 🚚 SHIPPING
// ===============================================
Route::prefix('shipping')->name('shipping.')->group(function () {
    Route::get('/provinces', [ShippingController::class, 'getProvinces'])->name('provinces');
    Route::get('/cities/{provinceId?}', [ShippingController::class, 'getCities'])->name('cities');
    Route::get('/districts/{cityId?}', [ShippingController::class, 'getDistricts'])->name('districts');

    Route::get('/popular-cities', [ShippingController::class, 'getPopularCities'])->name('popular-cities');
    Route::get('/popular-districts', [ShippingController::class, 'getPopularDistricts'])->name('popular-districts');

    Route::post('/cost', [ShippingController::class, 'calculateCost'])->name('calculate-cost');
    Route::post('/track', [ShippingController::class, 'trackShipment'])->name('track');

    Route::get('/test-connection', [ShippingController::class, 'testConnection'])->name('test-connection');
    Route::get('/info', [ShippingController::class, 'getShippingInfo'])->name('info');
});

// ===============================================
// 📑 ORDERS
// ===============================================
Route::prefix('orders')->name('orders.')->group(function () {
    // Guest accessible
    Route::get('/lookup', [OrderController::class, 'showLookup'])->name('lookup.show');
    Route::post('/lookup', [OrderController::class, 'lookup'])->name('lookup');
    Route::get('/track/{orderNumber}', [OrderController::class, 'track'])->name('track');

    Route::get('/{orderNumber}', [OrderController::class, 'show'])->name('show');
    Route::post('/{orderNumber}/upload-payment', [OrderController::class, 'uploadPaymentProof'])->name('upload-payment');
    Route::post('/{orderNumber}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    Route::get('/{orderNumber}/items/{item}/download', [OrderController::class, 'downloadDesignFile'])->name('download-design');

    // Auth only
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
    });
});

// ===============================================
// 🔐 SECURE TRACKING
// ===============================================
Route::group(['prefix' => 'track', 'as' => 'orders.track.'], function() {
    Route::get('{orderNumber}/{token}', [OrderController::class, 'trackWithToken'])
        ->name('secure')
        ->where('orderNumber', 'DP-\d{8}-\d{3}')
        ->where('token', '[a-f0-9]{64}');

    Route::get('{orderNumber}', [OrderController::class, 'showTrackingVerification'])
        ->name('verify')
        ->where('orderNumber', 'DP-\d{8}-\d{3}');

    Route::post('{orderNumber}/verify', [OrderController::class, 'verifyTracking'])
        ->name('verify.process')
        ->where('orderNumber', 'DP-\d{8}-\d{3}');

    Route::get('{orderNumber}/verified', [OrderController::class, 'trackVerified'])
        ->name('verified')
        ->where('orderNumber', 'DP-\d{8}-\d{3}');
});

// Backward compatibility
Route::get('orders/track/{orderNumber}', function($orderNumber) {
    return redirect()->route('orders.track.verify', $orderNumber, 301);
})->where('orderNumber', 'DP-\d{8}-\d{3}');

// ===============================================
// 🛠️ TEST ROUTES (remove in production)
// ===============================================
Route::get('/test', function() {
    return response()->json([
        'message' => 'Laravel berjalan dengan baik!',
        'timestamp' => now(),
        'env' => app()->environment()
    ]);
})->name('test');

Route::get('/test-provinces', function() {
    $service = new \App\Services\RajaOngkirService();
    return $service->getProvinces();
});

Route::get('/test-districts/{cityId}', function($cityId) {
    $service = new \App\Services\RajaOngkirService();
    return response()->json([
        'city_id' => $cityId,
        'districts' => $service->getDistricts($cityId),
        'popular_districts' => $service->getPopularDistricts(),
    ]);
})->name('test-districts');

Route::get('/test-shipping-cost', function() {
    $service = new \App\Services\RajaOngkirService();
    $result = $service->getShippingCost(574, 1360, 1000); // 1kg
    return response()->json([
        'origin_district_id' => 574,
        'destination_district_id' => 1360,
        'weight' => '1kg',
        'shipping_options' => $result
    ]);
})->name('test-shipping-cost');

// ===============================================
// 👨‍💻 ADMIN (placeholder)
// ===============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
});
