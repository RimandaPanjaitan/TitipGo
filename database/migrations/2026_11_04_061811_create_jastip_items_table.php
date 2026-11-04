<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('jastip_items', function (Blueprint $table) {
        $table->id();
        $table->string('name', 150);
        $table->text('description')->nullable();
        $table->string('origin_city', 100);
        $table->unsignedBigInteger('price');
        $table->unsignedInteger('quota')->default(0);
        $table->string('image')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jastip_items');
    }
};
