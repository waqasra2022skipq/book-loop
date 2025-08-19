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
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedInteger('reactions_count')->default(0)->after('visibility');
            $table->unsignedInteger('comments_count')->default(0)->after('reactions_count');
            $table->unsignedInteger('likes_count')->default(0)->after('comments_count');

            // Indexes for performance
            $table->index('reactions_count', 'idx_reactions_count');
            $table->index(['reactions_count', 'comments_count', 'created_at'], 'idx_engagement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('idx_reactions_count');
            $table->dropIndex('idx_engagement');
            $table->dropColumn(['reactions_count', 'comments_count', 'likes_count']);
        });
    }
};
