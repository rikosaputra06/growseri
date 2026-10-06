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
        Schema::table('store_settings', function (Blueprint $table) {
            $table->boolean('payment_transfer_active')->default(true)->after('delivery_active_days');
            $table->text('payment_transfer_details')->nullable()->after('payment_transfer_active');
            $table->boolean('payment_qris_active')->default(true)->after('payment_transfer_details');
            $table->string('payment_qris_image_path')->nullable()->after('payment_qris_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_transfer_active',
                'payment_transfer_details',
                'payment_qris_active',
                'payment_qris_image_path'
            ]);
        });
    }
};
