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
        Schema::table('transactions', function (Blueprint $table) {
            // Add missing fields
            if (!Schema::hasColumn('transactions', 'coins_received')) {
                $table->integer('coins_received')->default(0)->after('coin_package_id');
            }
            
            if (!Schema::hasColumn('transactions', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('payment_type');
            }
            
            if (!Schema::hasColumn('transactions', 'is_coins_added')) {
                $table->boolean('is_coins_added')->default(false)->after('is_paid');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Remove columns if they exist
            if (Schema::hasColumn('transactions', 'coins_received')) {
                $table->dropColumn('coins_received');
            }
            
            if (Schema::hasColumn('transactions', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            
            if (Schema::hasColumn('transactions', 'is_coins_added')) {
                $table->dropColumn('is_coins_added');
            }
        });
    }
};
