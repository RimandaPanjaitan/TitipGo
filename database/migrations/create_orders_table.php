public function up(): void
{
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