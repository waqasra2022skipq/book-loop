<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            [
                'name' => 'Fiction',
                'description' => 'Imaginative or invented stories, novels, and narratives.',
            ],
            [
                'name' => 'Non-Fiction',
                'description' => 'Factual books, biographies, essays, and informational content.',
            ],
            [
                'name' => 'Mystery',
                'description' => 'Stories involving puzzles, crimes, or unexplained events.',
            ],
            [
                'name' => 'Romance',
                'description' => 'Stories centered around love and romantic relationships.',
            ],
            [
                'name' => 'Science Fiction',
                'description' => 'Stories set in the future or involving advanced technology.',
            ],
            [
                'name' => 'Fantasy',
                'description' => 'Stories with magical or supernatural elements.',
            ],
            [
                'name' => 'Thriller',
                'description' => 'Suspenseful stories designed to keep readers on edge.',
            ],
            [
                'name' => 'Horror',
                'description' => 'Stories intended to frighten, unsettle, or create suspense.',
            ],
            [
                'name' => 'Biography',
                'description' => 'Life stories of real people.',
            ],
            [
                'name' => 'Autobiography',
                'description' => 'Life stories written by the person themselves.',
            ],
            [
                'name' => 'History',
                'description' => 'Books about past events, people, and civilizations.',
            ],
            [
                'name' => 'Philosophy',
                'description' => 'Books exploring fundamental questions about existence and knowledge.',
            ],
            [
                'name' => 'Psychology',
                'description' => 'Books about the mind, behavior, and mental processes.',
            ],
            [
                'name' => 'Self-Help',
                'description' => 'Books designed to help readers improve their lives.',
            ],
            [
                'name' => 'Business',
                'description' => 'Books about commerce, entrepreneurship, and management.',
            ],
            [
                'name' => 'Technology',
                'description' => 'Books about computers, programming, and technological advancement.',
            ],
            [
                'name' => 'Health & Fitness',
                'description' => 'Books about physical and mental wellness.',
            ],
            [
                'name' => 'Cooking',
                'description' => 'Recipe books and culinary guides.',
            ],
            [
                'name' => 'Travel',
                'description' => 'Guidebooks and travel narratives.',
            ],
            [
                'name' => 'Art & Design',
                'description' => 'Books about visual arts, design, and creativity.',
            ],
            [
                'name' => 'Music',
                'description' => 'Books about musical theory, history, and musicians.',
            ],
            [
                'name' => 'Sports',
                'description' => 'Books about athletics, games, and physical competition.',
            ],
            [
                'name' => 'Children\'s Books',
                'description' => 'Books specifically written for young readers.',
            ],
            [
                'name' => 'Young Adult',
                'description' => 'Books targeted at teenage readers.',
            ],
            [
                'name' => 'Poetry',
                'description' => 'Collections of poems and verse.',
            ],
            [
                'name' => 'Drama',
                'description' => 'Plays and theatrical works.',
            ],
            [
                'name' => 'Comics & Graphic Novels',
                'description' => 'Visual storytelling through illustrations and text.',
            ],
            [
                'name' => 'Religion & Spirituality',
                'description' => 'Books about faith, spirituality, and religious practices.',
            ],
            [
                'name' => 'Science',
                'description' => 'Books about scientific discoveries and natural phenomena.',
            ],
            [
                'name' => 'Mathematics',
                'description' => 'Books about mathematical concepts and applications.',
            ],
            [
                'name' => 'Education',
                'description' => 'Textbooks and educational materials.',
            ],
            [
                'name' => 'Politics',
                'description' => 'Books about government, political theory, and current affairs.',
            ],
            [
                'name' => 'Law',
                'description' => 'Books about legal systems and jurisprudence.',
            ],
            [
                'name' => 'Economics',
                'description' => 'Books about economic theory and financial systems.',
            ],
            [
                'name' => 'Adventure',
                'description' => 'Action-packed stories with exciting journeys.',
            ],
            [
                'name' => 'Western',
                'description' => 'Stories set in the American Old West.',
            ],
            [
                'name' => 'Historical Fiction',
                'description' => 'Fiction set in the past, recreating historical periods.',
            ],
            [
                'name' => 'Contemporary Fiction',
                'description' => 'Fiction set in the present day.',
            ],
            [
                'name' => 'Literary Fiction',
                'description' => 'Character-driven fiction emphasizing artistic expression.',
            ],
            [
                'name' => 'Memoir',
                'description' => 'Personal accounts of specific life experiences.',
            ],
        ];

        foreach ($genres as $genreData) {
            Genre::firstOrCreate(
                ['name' => $genreData['name']],
                [
                    'slug' => Str::slug($genreData['name']),
                    'description' => $genreData['description'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Genres seeded successfully!');
    }
}
