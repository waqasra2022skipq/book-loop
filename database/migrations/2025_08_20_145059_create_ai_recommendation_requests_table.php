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
        Schema::create('ai_recommendation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('recent_books'); // Array of book objects
            $table->text('user_prompt');
            $table->json('preferences')->nullable(); // Genre, length, era, rating filters
            $table->text('generated_prompt')->nullable(); // The actual prompt sent to AI
            $table->json('ai_response')->nullable(); // Full AI response
            $table->integer('total_tokens_used')->default(0);
            $table->decimal('response_time', 5, 2)->default(0); // seconds
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_recommendation_requests');
    }
};
