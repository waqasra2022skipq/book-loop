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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('reviews_count')->default(0)->after('phone');
            $table->decimal('avg_rating', 3, 2)->nullable()->after('reviews_count')->comment('Average rating with 2 decimal places');

            // Index for sorting/filtering by rating
            $table->index('avg_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['avg_rating']);
            $table->dropColumn(['reviews_count', 'avg_rating']);
        });
    }
};
