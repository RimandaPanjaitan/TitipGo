public function up(): void
{
    Schema::table('jastip_items', function (Blueprint $table) {
        $table->boolean('is_open')->default(true)->after('quota');
    });
}