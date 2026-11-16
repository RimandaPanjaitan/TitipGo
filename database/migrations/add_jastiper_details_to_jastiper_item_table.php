public function up(): void
{
    Schema::table('jastip_items', function (Blueprint $table) {$table->string('category', 50)->default('Makanan & Snack')->after('origin_city');
        $table->date('estimated_arrival')->nullable()->after('category');$table->unsignedInteger('weight_grams')->default(500)->after('quota');
    });
}