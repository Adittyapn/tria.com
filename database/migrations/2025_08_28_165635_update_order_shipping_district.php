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
        Schema::table('orders', function (Blueprint $table) {
            // ✅ ADD: District fields for more accurate shipping
            $table->integer('shipping_district_id')->nullable()->after('shipping_city_id');
            $table->string('shipping_district_name')->nullable()->after('shipping_city_name');

            // ✅ ADD: Index for better performance
            $table->index(['shipping_province_id', 'shipping_city_id', 'shipping_district_id'], 'orders_shipping_location_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_shipping_location_index');
            $table->dropColumn(['shipping_district_id', 'shipping_district_name']);
        });
    }
};
