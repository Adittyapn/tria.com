<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Add new columns
            $table->decimal('custom_size_width', 8, 2)->nullable()->after('subtotal');
            $table->decimal('custom_size_height', 8, 2)->nullable()->after('custom_size_width');
            $table->string('selected_material')->nullable()->after('custom_size_height');
            $table->string('selected_finishing')->nullable()->after('selected_material');
            $table->text('design_notes')->nullable()->after('selected_finishing');
            $table->string('design_file_path')->nullable()->after('design_notes');
            $table->boolean('requires_design_service')->default(false)->after('design_file_path');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn([
                'custom_size_width',
                'custom_size_height',
                'selected_material',
                'selected_finishing',
                'design_notes',
                'design_file_path',
                'requires_design_service'
            ]);
        });
    }
};
