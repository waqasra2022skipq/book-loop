<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Book;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        // Add a function to add slug to existing book titles
        $this->addSlugsToBooks();
    }

    private function addSlugsToBooks(): void
    {
        $books = Book::all();

        foreach ($books as $book) {
            $book->slug = \Str::slug($book->title);
            $book->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
