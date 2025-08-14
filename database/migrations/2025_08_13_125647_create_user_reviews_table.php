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
        Schema::create('user_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewed_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewer_user_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned()->comment('Rating from 1 to 5');
            $table->text('review')->nullable();
            $table->string('transaction_type')->nullable()->comment('book_request, book_loan, general, etc.');
            $table->unsignedBigInteger('transaction_id')->nullable()->comment('ID of related transaction (book_request_id, book_loan_id, etc.)');
            $table->boolean('is_public')->default(true);
            $table->timestamp('reviewed_at')->useCurrent();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['reviewed_user_id', 'is_public']);
            $table->index(['reviewer_user_id']);
            $table->index(['transaction_type', 'transaction_id']);
            $table->index('rating');

            // Ensure one review per transaction per reviewer
            $table->unique(['reviewer_user_id', 'transaction_type', 'transaction_id'], 'unique_review_per_transaction');

            // Prevent self-reviews
            // $table->check('reviewed_user_id != reviewer_user_id');

            // Rating must be between 1 and 5
            // $table->check('rating >= 1 AND rating <= 5');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_reviews');
    }
};
