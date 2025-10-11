<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genre;

class PopularGenresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            [
                'name' => 'Fiction',
                'description' => 'Imaginative stories and novels that explore the human experience through creative narratives.',
                'is_active' => true,
            ],
            [
                'name' => 'Non-Fiction',
                'description' => 'Factual books covering real events, people, places, and ideas.',
                'is_active' => true,
            ],
            [
                'name' => 'Mystery',
                'description' => 'Suspenseful stories featuring puzzles, crimes, and investigations to solve.',
                'is_active' => true,
            ],
            [
                'name' => 'Romance',
                'description' => 'Stories centered around love, relationships, and romantic connections.',
                'is_active' => true,
            ],
            [
                'name' => 'Science Fiction',
                'description' => 'Stories that explore futuristic concepts, advanced technology, and space exploration.',
                'is_active' => true,
            ],
            [
                'name' => 'Fantasy',
                'description' => 'Magical worlds filled with mythical creatures, supernatural powers, and epic adventures.',
                'is_active' => true,
            ],
            [
                'name' => 'Thriller',
                'description' => 'Fast-paced, suspenseful stories designed to keep readers on the edge of their seats.',
                'is_active' => true,
            ],
            [
                'name' => 'Biography',
                'description' => 'True stories about the lives of real people, from celebrities to historical figures.',
                'is_active' => true,
            ],
            [
                'name' => 'History',
                'description' => 'Books about past events, civilizations, and historical periods.',
                'is_active' => true,
            ],
            [
                'name' => 'Self-Help',
                'description' => 'Books designed to help readers improve their lives, skills, and personal development.',
                'is_active' => true,
            ],
            [
                'name' => 'Poetry',
                'description' => 'Collections of poems and verse exploring emotions, experiences, and artistic expression.',
                'is_active' => true,
            ],
            [
                'name' => 'Young Adult',
                'description' => 'Books targeted at teenage readers, often featuring coming-of-age themes.',
                'is_active' => true,
            ],
            [
                'name' => 'Horror',
                'description' => 'Stories designed to frighten, unsettle, and create suspense through supernatural or psychological elements.',
                'is_active' => true,
            ],
            [
                'name' => 'Adventure',
                'description' => 'Action-packed stories featuring exciting journeys, exploration, and heroic quests.',
                'is_active' => true,
            ],
            [
                'name' => 'Literary Fiction',
                'description' => 'Character-driven novels that focus on artistic expression and deeper themes.',
                'is_active' => true,
            ]
        ];

        foreach ($genres as $genreData) {
            Genre::firstOrCreate(
                ['name' => $genreData['name']],
                $genreData
            );
        }
    }
}
