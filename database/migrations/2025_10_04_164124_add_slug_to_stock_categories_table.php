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
        Schema::table('stock_categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->dropColumn('is_medical_supplies');
        });

        // Generate slugs for existing records
        \App\Models\Stock\StockCategory::all()->each(function ($category) {
            $category->update(['slug' => \Str::slug($category->name)]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_categories', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->boolean('is_medical_supplies')->default(false)->after('description');
        });
    }
};
