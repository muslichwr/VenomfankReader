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
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('cover_image')->nullable();
            $table->enum('type', ['novel', 'manga', 'manhwa', 'manhua'])->default('manga');
            $table->enum('status', ['ongoing', 'completed', 'hiatus'])->default('ongoing');
            $table->string('author')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->integer('free_chapters')->default(3); // Number of free chapters before requiring coins
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
