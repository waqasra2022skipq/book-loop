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
        Schema::create('book_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_request_id')->constrained('book_requests')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('book_instance_id')->constrained('book_instances')->onDelete('cascade');
            $table->foreignId('borrower_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->date('delivered_date')->nullable();
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status', [
                'delivered',       // Book has been delivered to borrower
                'received',        // Borrower confirmed receipt
                'reading',         // Book is being read (active loan)
                'returned',        // Borrower claims to have returned
                'return_confirmed', // Owner confirmed receipt of return
                'return_denied',   // Owner denied receiving return
                'lost',           // Book is lost
                'disputed'        // There's a dispute about the book
            ])->default('delivered');
            $table->text('notes')->nullable(); // For additional comments or dispute details
            $table->timestamps();
            $table->softDeletes();
            
            // Add indexes for better performance
            $table->index(['borrower_id', 'status']);
            $table->index(['owner_id', 'status']);
            $table->index(['book_instance_id', 'status']);
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_loans');
    }
};
