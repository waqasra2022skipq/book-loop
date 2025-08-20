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
        Schema::create('user_book_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('favorite_genres')->nullable();
            $table->json('disliked_genres')->nullable();
            $table->enum('preferred_length', ['short', 'medium', 'long', 'any'])->default('any');
            $table->enum('preferred_era', ['classic', 'modern', 'contemporary', 'any'])->default('any');
            $table->decimal('min_rating', 3, 2)->default(0);
            $table->integer('recommendations_count')->default(0);
            $table->decimal('avg_feedback_score', 3, 2)->default(0);
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_book_preferences');
    }
};
