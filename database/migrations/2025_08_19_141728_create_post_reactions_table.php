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
        Schema::create('post_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reactable_type'); // Polymorphic - 'App\Models\Post', 'App\Models\PostComment'
            $table->unsignedBigInteger('reactable_id');
            $table->enum('reaction_type', ['like', 'love', 'laugh', 'angry', 'sad', 'wow'])->default('like');
            $table->timestamps();

            // Indexes for performance
            $table->index(['reactable_type', 'reactable_id'], 'idx_reactable');
            $table->index(['user_id', 'created_at'], 'idx_user_reactions');
            $table->index('reaction_type', 'idx_reaction_type');

            // Ensure one reaction per user per item
            $table->unique(['user_id', 'reactable_type', 'reactable_id'], 'unique_user_reaction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_reactions');
    }
};
