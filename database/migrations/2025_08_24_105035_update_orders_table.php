<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop existing columns yang tidak perlu
            $table->dropColumn(['name', 'email', 'phone', 'address']);

            // Add new columns
            $table->foreignId('customer_id')->after('id')->constrained()->onDelete('cascade');
            $table->decimal('subtotal_items', 12, 2)->after('total_amount');
            $table->decimal('shipping_cost', 12, 2)->default(0)->after('subtotal_items');
            $table->decimal('tax_amount', 12, 2)->default(0)->after('shipping_cost');
            $table->enum('payment_status', ['pending', 'verified', 'rejected'])->default('pending')->after('status');
            $table->string('payment_proof')->nullable()->after('payment_status');

            // Update status enum
            $table->enum('status', ['pending_payment', 'paid', 'processing', 'ready', 'shipped', 'completed', 'cancelled'])->default('pending_payment')->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn(['customer_id', 'subtotal_items', 'shipping_cost', 'tax_amount', 'payment_status', 'payment_proof']);

            // Restore old columns
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('address');
            $table->enum('status', ['pending', 'confirmed', 'processing', 'completed', 'cancelled'])->default('pending')->change();
        });
    }
};
