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
        Schema::create('ai_book_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('ai_recommendation_requests')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('author');
            $table->string('genre');
            $table->text('description');
            $table->text('ai_reason');
            $table->integer('publication_year')->nullable();
            $table->integer('pages')->nullable();
            $table->decimal('confidence_score', 3, 2)->default(0);
            $table->string('cover_url')->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->enum('user_feedback', ['saved', 'not_interested', 'already_read'])->nullable();
            $table->timestamp('feedback_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('user_feedback');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_book_recommendations');
    }
};
