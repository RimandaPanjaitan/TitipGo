<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jastip_items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('origin_country', 80);
            $table->string('origin_city', 100);
            $table->string('category', 100);
            $table->string('estimated_shipping', 60);
            $table->unsignedBigInteger('price');
            $table->unsignedInteger('quota')->default(10);
            $table->string('weight', 50)->nullable();
            $table->date('po_close_date')->nullable();
            $table->string('jastiper_name', 100)->default('Rimanda Santa');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jastip_item_id')->constrained('jastip_items')->onDelete('cascade');
            $table->string('order_code', 30)->unique();
            $table->string('buyer_name', 100)->default('Rimanda Santa');
            $table->string('buyer_email', 100)->default('rimandasnt@gmail.com');
            $table->string('buyer_phone', 30)->nullable();
            $table->text('shipping_address');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('service_fee')->default(5000);
            $table->unsignedBigInteger('total_price');
            $table->string('payment_method', 50)->default('QRIS / Transfer');
            $table->string('payment_status', 30)->default('Menunggu Pembayaran');
            $table->unsignedTinyInteger('step_status')->default(1);
            $table->string('status_label', 100)->default('1. Pesanan Diterima & Dibeli di Negara Asal');
            $table->string('courier_vehicle', 80)->nullable();
            $table->string('courier_plate', 30)->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('role', 20);
            $table->string('title', 150);
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 50);
            $table->string('account_number', 100);
            $table->string('account_holder', 100)->default('Rimanda Santa');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 50);
            $table->string('account_number', 100);
            $table->unsignedBigInteger('amount');
            $table->string('status', 40)->default('Berhasil Dicairkan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('jastip_items');
    }
};