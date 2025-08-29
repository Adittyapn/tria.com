<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('product_type')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            // Description & Media
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();

            // Pricing System
            $table->enum('pricing_type', ['per_piece', 'per_meter_square', 'per_meter_linear', 'fixed_size', 'bulk_package'])->default('per_piece');
            $table->string('unit_label')->nullable();
            $table->integer('minimum_quantity')->default(1);
            $table->integer('step_quantity')->default(1);
            $table->boolean('allows_custom_size')->default(false);
            $table->decimal('base_price', 12, 2);
            $table->decimal('promo_price', 12, 2)->nullable();

            // Size Presets & Volume Pricing
            $table->json('size_presets')->nullable();
            $table->json('volume_pricing')->nullable();

            // Materials & Finishing
            $table->string('default_material')->nullable();
            $table->string('default_finishing')->nullable();
            $table->json('material_options')->nullable();
            $table->json('finishing_options')->nullable();

            // File Upload & Design
            $table->boolean('requires_design_file')->default(true);
            $table->boolean('offers_design_service')->default(false);
            $table->text('file_requirements')->nullable();
            $table->integer('max_file_size_mb')->default(10);
            $table->json('allowed_formats')->nullable();
            $table->decimal('design_service_price', 12, 2)->nullable();

            // Production & Shipping
            $table->integer('production_days')->default(2);
            $table->enum('production_priority', ['standard', 'express', 'same_day'])->default('standard');
            $table->integer('estimated_weight_per_unit')->nullable(); // gram
            $table->boolean('requires_approval')->default(true);
            $table->text('production_notes')->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('keywords')->nullable();

            // Legacy Fields
            $table->integer('stock_quantity')->default(0);
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'on_backorder'])->default('in_stock');
            $table->integer('minimum_order')->default(1);
            $table->integer('weight')->nullable(); // in grams
            $table->integer('production_time')->default(3); // in days
            $table->boolean('requires_design_approval')->default(false);
            $table->decimal('package_length', 8, 2)->nullable();
            $table->decimal('package_width', 8, 2)->nullable();
            $table->decimal('package_height', 8, 2)->nullable();
            $table->json('tags')->nullable();
            $table->json('specifications')->nullable();
            $table->text('file_upload_notes')->nullable();
            $table->integer('max_file_size')->default(10); // legacy
            $table->json('allowed_file_types')->nullable(); // legacy

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index(['is_active', 'created_at']);
            $table->index(['category_id', 'is_active']);
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
