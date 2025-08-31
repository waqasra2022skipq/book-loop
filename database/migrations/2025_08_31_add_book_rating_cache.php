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
        Schema::table('books', function (Blueprint $table) {
            $table->unsignedInteger('ratings_count')->default(0)->after('isbn');
            $table->decimal('avg_rating', 3, 2)->nullable()->after('ratings_count')->comment('Average rating from book summaries');

            // Index for sorting/filtering by rating
            $table->index('avg_rating');
            $table->index(['avg_rating', 'ratings_count']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex(['avg_rating', 'ratings_count']);
            $table->dropIndex(['avg_rating']);
            $table->dropColumn(['ratings_count', 'avg_rating']);
        });
    }
};
