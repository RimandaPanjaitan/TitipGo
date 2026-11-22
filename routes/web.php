<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JastipController;

// Landing, Auth Terpisah (Pembeli vs Jastiper), & Setup Lokasi
Route::get('/', [JastipController::class, 'landing'])->name('landing');
Route::get('/auth', [JastipController::class, 'authForm'])->name('auth');
Route::post('/auth', [JastipController::class, 'authSubmit'])->name('auth.submit');
Route::get('/location', [JastipController::class, 'locationSetup'])->name('location.setup');
Route::post('/location', [JastipController::class, 'locationSave'])->name('location.save');

// Update Profil Bersama (Avatar & Galeri Foto)
Route::post('/profile/update', [JastipController::class, 'updateProfile'])->name('profile.update');

// POV PEMBELI
Route::prefix('buyer')->group(function () {
    Route::get('/dashboard', [JastipController::class, 'buyerDashboard'])->name('buyer.dashboard');
    Route::get('/catalog', [JastipController::class, 'buyerCatalog'])->name('buyer.catalog');
    Route::get('/item/{id}', [JastipController::class, 'productDetail'])->name('buyer.item.show');
    Route::post('/order/{id}', [JastipController::class, 'createOrder'])->name('buyer.order.create');
    Route::get('/payment/{orderId}', [JastipController::class, 'paymentPage'])->name('buyer.payment');
    Route::post('/payment/{orderId}', [JastipController::class, 'confirmPayment'])->name('buyer.payment.confirm');
    Route::get('/receipt/{orderId}', [JastipController::class, 'receiptPage'])->name('buyer.receipt');
    Route::get('/track', [JastipController::class, 'trackOrders'])->name('buyer.track');
    Route::get('/notifications', [JastipController::class, 'buyerNotifications'])->name('buyer.notifications');
    Route::get('/profile', [JastipController::class, 'buyerProfile'])->name('buyer.profile');
});

// PENGHUBUNG SATU ARAH: Pembeli -> Jastiper Permanen
Route::get('/apply-jastiper', [JastipController::class, 'applyJastiperForm'])->name('jastiper.apply');
Route::post('/apply-jastiper', [JastipController::class, 'applyJastiperSubmit'])->name('jastiper.apply.submit');

// POV EKSKLUSIF JASTIPER (Tanpa Akses Kembali ke Mode Pembeli)
Route::prefix('jastiper')->group(function () {
    Route::get('/dashboard', [JastipController::class, 'shopperDashboard'])->name('jastiper.dashboard');
    Route::post('/withdraw', [JastipController::class, 'withdrawFunds'])->name('jastiper.withdraw');
    Route::get('/catalog', [JastipController::class, 'shopperCatalog'])->name('jastiper.catalog');
    Route::post('/catalog', [JastipController::class, 'storeCatalogItem'])->name('jastiper.catalog.store');
    Route::get('/orders', [JastipController::class, 'shopperOrders'])->name('jastiper.orders');
    Route::patch('/orders/{id}/advance', [JastipController::class, 'advanceOrderStep'])->name('jastiper.orders.advance');
    Route::get('/notifications', [JastipController::class, 'shopperNotifications'])->name('jastiper.notifications');
    Route::get('/profile', [JastipController::class, 'shopperProfile'])->name('jastiper.profile');
    Route::post('/bank', [JastipController::class, 'storeBank'])->name('jastiper.bank.store');
    Route::patch('/bank/{id}/primary', [JastipController::class, 'setPrimaryBank'])->name('jastiper.bank.primary');
    Route::delete('/bank/{id}', [JastipController::class, 'destroyBank'])->name('jastiper.bank.destroy');
});

// Landing, Auth Terpisah (Pembeli vs Jastiper), Setup Lokasi, & Welcome Screen
Route::get('/', [JastipController::class, 'landing'])->name('landing');
Route::get('/auth', [JastipController::class, 'authForm'])->name('auth');
Route::post('/auth', [JastipController::class, 'authSubmit'])->name('auth.submit');
Route::get('/location', [JastipController::class, 'locationSetup'])->name('location.setup');
Route::post('/location', [JastipController::class, 'locationSave'])->name('location.save');
Route::get('/welcome-user', [JastipController::class, 'welcomeScreen'])->name('welcome.screen');