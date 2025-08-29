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
            // 🔐 Secure tracking token (64 chars for SHA-256 hash)
            $table->string('tracking_token', 64)->unique()->nullable()->after('order_number');

            // 📅 Token expiry (when order completed)
            $table->timestamp('tracking_token_expires_at')->nullable()->after('tracking_token');

            // 🔍 Add index for faster lookup
            $table->index(['tracking_token', 'tracking_token_expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['tracking_token', 'tracking_token_expires_at']);
            $table->dropColumn(['tracking_token', 'tracking_token_expires_at']);
        });
    }
};
