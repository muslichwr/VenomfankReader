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
        Schema::create('reading_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('series_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('chapter_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('last_read_at');
            $table->integer('progress_percentage')->default(0); // For continuing reading from a specific position
            $table->softDeletes();
            $table->timestamps();
            
            // A user can only have one reading history per chapter
            $table->unique(['user_id', 'chapter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_histories');
    }
};
