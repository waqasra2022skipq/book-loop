<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $posts = [
            [
                'body' => 'My First Book Review: Just finished reading "The Great Gatsby" and I must say it was an incredible journey through the American Dream. The symbolism and character development are simply outstanding.',
            ],
            [
                'body' => 'Looking for Book Recommendations: I\'m looking for some good science fiction books. I loved "Dune" and "Foundation" series. Any recommendations?',
            ],
            [
                'body' => 'Book Club Meeting This Weekend: Don\'t forget about our book club meeting this Saturday at 2 PM. We\'ll be discussing "To Kill a Mockingbird". See you all there!',
            ],
            [
                'body' => 'New Arrivals at the Library: Exciting news! Our library just received several new releases including the latest from Stephen King and Margaret Atwood. Come check them out!',
            ],
            [
                'body' => 'Reading Challenge Update: I\'m halfway through my 50-book reading challenge for this year! Currently at 25 books. How is everyone else doing with their reading goals?',
            ],
        ];

        foreach ($posts as $postData) {
            Post::create([
                'user_id' => $users->random()->id,
                'body' => $postData['body'],
                'visibility' => 'public',
            ]);
        }

        $this->command->info('Sample posts created successfully!');
    }
}
