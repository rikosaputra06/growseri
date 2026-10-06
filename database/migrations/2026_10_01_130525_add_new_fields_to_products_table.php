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
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->nullable();
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->boolean('is_discount_active')->default(false);
            $table->boolean('is_out_of_stock')->default(false);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->boolean('is_discount_active')->default(false);
            $table->boolean('is_out_of_stock')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['discount_price', 'is_discount_active', 'is_out_of_stock']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['category', 'discount_price', 'is_discount_active', 'is_out_of_stock']);
        });
    }
};
