<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User;
use App\Models\BookInstance;

class BookInstanceSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        Book::all()->each(function ($book) use ($users) {
            // Assign each book to 1-3 random users as instances
            $owners = $users->random(rand(1, min(3, $users->count())));
            foreach ($owners as $user) {
                BookInstance::factory()->create([
                    'book_id' => $book->id,
                    'owner_id' => $user->id,
                ]);
            }
        });
    }
}
