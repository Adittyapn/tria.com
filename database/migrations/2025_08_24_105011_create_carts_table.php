<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('custom_size_width', 8, 2)->nullable();
            $table->decimal('custom_size_height', 8, 2)->nullable();
            $table->string('selected_material')->nullable();
            $table->string('selected_finishing')->nullable();
            $table->text('design_notes')->nullable();
            $table->string('design_file_path')->nullable();
            $table->boolean('requires_design_service')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'session_id']);
            $table->index(['session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
