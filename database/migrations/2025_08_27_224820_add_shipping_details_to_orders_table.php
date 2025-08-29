<?php

// database/migrations/xxxx_xx_xx_add_shipping_details_to_orders_table.php
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
        Schema::table('orders', function (Blueprint $table) {
            // Add shipping related columns
            $table->integer('shipping_province_id')->nullable()->after('shipping_cost');
            $table->integer('shipping_city_id')->nullable()->after('shipping_province_id');
            $table->string('shipping_province_name')->nullable()->after('shipping_city_id');
            $table->string('shipping_city_name')->nullable()->after('shipping_province_name');
            $table->text('shipping_address')->nullable()->after('shipping_city_name');
            $table->string('shipping_courier', 50)->nullable()->after('shipping_address');
            $table->string('shipping_service')->nullable()->after('shipping_courier');
            $table->string('shipping_etd', 50)->nullable()->after('shipping_service');

            // Tracking information
            $table->string('tracking_number')->nullable()->after('shipping_etd');
            $table->timestamp('shipped_at')->nullable()->after('tracking_number');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_province_id',
                'shipping_city_id',
                'shipping_province_name',
                'shipping_city_name',
                'shipping_address',
                'shipping_courier',
                'shipping_service',
                'shipping_etd',
                'tracking_number',
                'shipped_at',
                'delivered_at'
            ]);
        });
    }
};
