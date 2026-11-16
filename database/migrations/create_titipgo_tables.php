<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Katalog Barang Jastip
        Schema::create('jastip_items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('origin_city', 100);
            $table->string('category', 80)->default('Makanan & Snack');
            $table->unsignedBigInteger('price');
            $table->unsignedInteger('quota')->default(0);
            $table->string('weight', 50)->nullable();
            $table->date('arrival_date')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Pesanan Pembeli
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jastip_item_id')->constrained('jastip_items')->onDelete('cascade');
            $table->string('buyer_name')->default('Rimanda Panjaitan');
            $table->string('buyer_phone')->nullable();
            $table->text('shipping_address')->nullable();
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('total_price');
            $table->string('status')->default('Menunggu Pembelian Jastiper');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('jastip_items');
    }
};