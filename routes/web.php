<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Kopi Pablo Self-Service Ordering System (PWA)
|--------------------------------------------------------------------------
|
| Skripsi: Rancang Bangun Sistem Pemesanan Self-Service Berbasis Web (PWA)
| dengan Pendekatan User Centered Design pada Kopi Pablo.
|
*/

// ==========================================
// 1. HALAMAN BERANDA (PUBLIK)
// ==========================================
Route::get('/', [CustomerController::class, 'landing'])->name('landing');
Route::get('/tiket/{orderNumber}', [CustomerController::class, 'verifyTicket'])->name('ticket.verify');

// ==========================================
// 2. AUTENTIKASI CUSTOMER (LOGIN & REGISTER)
// ==========================================
Route::get('/login', [CustomerAuthController::class, 'loginForm'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'authenticate'])->name('login.process');
Route::get('/register', [CustomerAuthController::class, 'registerForm'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'processRegister'])->name('register.process');
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

// ==========================================
// 3. ALUR PEMESANAN CUSTOMER (SELF-SERVICE TANPA LOGIN)
// ==========================================
// Halaman Menu & Produk
Route::get('/menu', [CustomerController::class, 'menu'])->name('menu');

// Keranjang Belanja
Route::get('/cart', [CustomerController::class, 'cart'])->name('cart');
Route::post('/cart/add', [CustomerController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CustomerController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/clear', [CustomerController::class, 'clearCart'])->name('cart.clear');

// Halaman Checkout & Pembayaran QR
Route::get('/checkout', [CustomerController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [CustomerController::class, 'processCheckout'])->name('checkout.process');

// Halaman Pembayaran QRIS / QR Code Pembayaran
Route::get('/payment/{orderNumber}', [CustomerController::class, 'paymentQr'])->name('checkout.payment');
Route::post('/payment/{orderNumber}/confirm', [CustomerController::class, 'confirmPayment'])->name('checkout.payment.confirm');

// Tiket QR Pengambilan Pesanan & Status
Route::get('/order/{orderNumber}', [CustomerController::class, 'orderStatus'])->name('order.status');
Route::get('/order/{orderNumber}/check-status', [CustomerController::class, 'checkOrderStatus'])->name('order.check_status');
Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');

// ==========================================
// 4. BACK-OFFICE ADMIN
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Autentikasi Admin
    Route::get('/login', [AdminController::class, 'login'])->name('login');
    Route::post('/login', [AdminController::class, 'authenticate'])->name('authenticate');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    // Dashboard & Fitur Manajemen Admin
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Kelola Produk & Kategori
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::match(['put', 'patch', 'post'], '/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');

    // Kelola Pesanan & Status Pemesanan Real-time
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::match(['patch', 'put', 'post'], '/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.updateStatus');

    // Laporan Penjualan
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});
