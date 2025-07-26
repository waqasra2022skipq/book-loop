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
        Schema::create('book_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('summary');
            $table->unsignedTinyInteger('rating')->nullable()->comment('Optional rating 1-5');
            $table->json('meta')->nullable()->comment('For future extensibility, e.g. tags, likes');
            $table->timestamps();
            $table->unique(['book_id', 'user_id'], 'unique_book_user_summary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_summaries');
    }
};
