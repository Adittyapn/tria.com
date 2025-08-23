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
            // Flexible Pricing System
            $table->enum('pricing_type', ['per_piece', 'per_meter_square', 'per_meter_linear', 'bulk_tier'])
                  ->default('per_piece')
                  ->after('sale_price')
                  ->comment('Sistem pricing: per_piece, per_meter_square, per_meter_linear, bulk_tier');

            $table->string('unit_label', 20)
                  ->default('pcs')
                  ->after('pricing_type')
                  ->comment('Label satuan: pcs, m², meter, set, pak, dll');

            // Size and customization options
            $table->boolean('has_custom_size')
                  ->default(false)
                  ->after('unit_label')
                  ->comment('Customer bisa input ukuran custom');

            $table->boolean('has_size_presets')
                  ->default(false)
                  ->after('has_custom_size')
                  ->comment('Ada preset ukuran standar');

            $table->json('size_presets')
                  ->nullable()
                  ->after('has_size_presets')
                  ->comment('Array preset ukuran standar');

            // Enhanced quantity management
            $table->json('quantity_tiers')
                  ->nullable()
                  ->after('size_presets')
                  ->comment('Tier harga berdasarkan quantity');

            $table->integer('minimum_quantity')
                  ->default(1)
                  ->after('quantity_tiers')
                  ->comment('Minimum pembelian');

            $table->integer('maximum_quantity')
                  ->nullable()
                  ->after('minimum_quantity')
                  ->comment('Maximum pembelian (optional)');

            $table->integer('step_quantity')
                  ->default(1)
                  ->after('maximum_quantity')
                  ->comment('Kelipatan order (1 untuk retail, 50 untuk grosir)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'pricing_type',
                'unit_label',
                'has_custom_size',
                'has_size_presets',
                'size_presets',
                'quantity_tiers',
                'minimum_quantity',
                'maximum_quantity',
                'step_quantity'
            ]);
        });
    }
};
