<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JastipController;

// Mengarahkan halaman utama (/) langsung ke catalog.index
Route::get('/', [JastipController::class, 'index'])->name('home');

// Rute katalog
Route::get('/catalog', [JastipController::class, 'index'])->name('catalog.index');

// Rute simpan pesanan
Route::post('/order/{id}', [JastipController::class, 'storeOrder'])->name('order.store');