<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name,
            'isbn' => $this->faker->isbn13,
            'genre' => $this->faker->word,
            'description' => $this->faker->paragraph,
            // add other fields as needed
        ];
    }
}
