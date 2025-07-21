<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookInstanceFactory extends Factory
{
    public function definition()
    {
        return [
            'condition_notes' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['available', 'reading', 'reserved']),
            // book_id and owner_id will be set in the seeder
        ];
    }
}
