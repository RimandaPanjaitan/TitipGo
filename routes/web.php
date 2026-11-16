<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JastipController;

// Sisi Pembeli
Route::get('/', [JastipController::class, 'index'])->name('home');
Route::get('/item/{id}', [JastipController::class, 'show'])->name('catalog.show');
Route::post('/order/{id}', [JastipController::class, 'storeOrder'])->name('order.store');
Route::get('/my-orders', [JastipController::class, 'myOrders'])->name('orders.index');

// Upgrade Satu Arah (Pembeli -> Jastiper Permanen)
Route::post('/upgrade-jastiper', [JastipController::class, 'upgradeToJastiper'])->name('jastiper.upgrade');

// Sisi Eksklusif Jastiper
Route::get('/jastiper/dashboard', [JastipController::class, 'jastiperDashboard'])->name('jastiper.dashboard');
Route::get('/jastiper/create', [JastipController::class, 'createItem'])->name('jastiper.create');
Route::post('/jastiper/store', [JastipController::class, 'storeItem'])->name('jastiper.store');
Route::patch('/jastiper/item/{id}/quota', [JastipController::class, 'adjustQuota'])->name('jastiper.item.quota');
Route::delete('/jastiper/item/{id}', [JastipController::class, 'destroyItem'])->name('jastiper.item.destroy');
Route::patch('/jastiper/order/{id}/status', [JastipController::class, 'updateOrderStatus'])->name('jastiper.order.status');