<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookSummary;
use App\Models\Book;
use App\Models\User;

class BookSummarySeeder extends Seeder
{
    public function run(): void
    {
        $books = Book::all();
        $users = User::all();

        foreach ($books as $book) {
            // Pick 1-3 random users to write summaries for each book
            $summaryUsers = $users->random(rand(1, min(3, $users->count())));
            foreach ($summaryUsers as $user) {
                BookSummary::updateOrCreate([
                    'book_id' => $book->id,
                    'user_id' => $user->id,
                ], [
                    'summary' => fake()->paragraphs(rand(1, 3), true),
                    'rating' => rand(3, 5),
                    'meta' => null,
                ]);
            }
        }
    }
}
