<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JastipController;

// Rute Halaman Utama Katalog
Route::get('/', [JastipController::class, 'index'])->name('home');

// Rute Transaksi Pemesanan
Route::post('/order/{id}', [JastipController::class, 'storeOrder'])->name('order.store');