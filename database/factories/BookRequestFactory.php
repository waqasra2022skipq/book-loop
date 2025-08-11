<?php

namespace Database\Factories;

use App\Models\BookRequest;
use App\Models\Book;
use App\Models\BookInstance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookRequestFactory extends Factory
{
    protected $model = BookRequest::class;

    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'book_instance_id' => BookInstance::factory(),
            'user_id' => User::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected']),
            'message' => $this->faker->optional(0.7)->sentence(),
        ];
    }

    public function pending(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }

    public function accepted(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'accepted',
            ];
        });
    }

    public function rejected(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'rejected',
            ];
        });
    }
}
