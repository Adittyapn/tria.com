<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->cascadeOnDelete();

            // Media & Display
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->default('#3B82F6');
            $table->integer('sort_order')->default(0);

            // Product Settings
            $table->boolean('requires_design_file')->default(true);
            $table->json('available_materials')->nullable(); // ['Flexi China', 'Vinyl', ...]
            $table->json('available_finishes')->nullable(); // ['Glossy', 'Matte', ...]
            $table->json('standard_sizes')->nullable(); // ['A4', '60x100', ...]

            // Templates & Guidelines
            $table->json('design_templates')->nullable(); // file paths
            $table->longText('design_guidelines')->nullable();
            $table->text('production_notes')->nullable();

            // SEO & Status
            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_homepage')->default(false);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['is_active', 'sort_order']);
            $table->index(['parent_id', 'is_active']);
            $table->index('show_on_homepage');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
