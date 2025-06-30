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
            // Hapus kolom amount_paid
            $table->dropColumn('amount_paid');
            
            // Tambah kolom baru
            $table->string('booking_trx_id');
            $table->unsignedInteger('sub_total_amount');
            $table->unsignedInteger('grand_total_amount');
            $table->unsignedInteger('total_tax_amount');
            $table->boolean('is_paid');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Hapus kolom yang ditambahkan
            $table->dropColumn([
                'booking_trx_id',
                'sub_total_amount',
                'grand_total_amount', 
                'total_tax_amount',
                'is_paid'
            ]);
            
            // Kembalikan kolom amount_paid
            $table->decimal('amount_paid', 10, 2);
        });
    }
};
