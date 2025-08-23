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

            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(true);

            // Content
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            // Media
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();

            // Pricing & Stock
            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('minimum_order')->default(1);
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'on_backorder'])->default('in_stock');

            // Specifications
            $table->json('specifications')->nullable(); // [{name: 'Bahan', value: 'Flexi China'}, ...]

            // Size Variants
            $table->json('size_variants')->nullable(); // [{size: '60x100', price: 50000, is_available: true}, ...]

            // File Upload Settings
            $table->text('file_upload_notes')->nullable();
            $table->integer('max_file_size')->default(10); // in MB
            $table->json('allowed_file_types')->nullable(); // ['pdf', 'ai', 'cdr']

            // Shipping & Production
            $table->integer('weight')->nullable(); // in grams
            $table->integer('production_time')->default(3); // in days
            $table->boolean('requires_design_approval')->default(false);
            $table->decimal('package_length', 8, 2)->nullable(); // cm
            $table->decimal('package_width', 8, 2)->nullable(); // cm
            $table->decimal('package_height', 8, 2)->nullable(); // cm

            // SEO & Marketing
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_featured')->default(false);

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
