<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\UserReview;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserReview>
 */
class UserReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reviewed_user_id' => User::factory(),
            'reviewer_user_id' => User::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'review' => $this->faker->optional(0.8)->paragraph(),
            'transaction_type' => $this->faker->optional(0.6)->randomElement(['book_request', 'book_loan', 'general']),
            'transaction_id' => $this->faker->optional(0.6)->numberBetween(1, 100),
            'is_public' => $this->faker->boolean(85), // 85% public
            'reviewed_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Create a review for a book request
     */
    public function forBookRequest($bookRequestId = null): static
    {
        return $this->state(fn(array $attributes) => [
            'transaction_type' => 'book_request',
            'transaction_id' => $bookRequestId ?? $this->faker->numberBetween(1, 100),
        ]);
    }

    /**
     * Create a review for a book loan
     */
    public function forBookLoan($bookLoanId = null): static
    {
        return $this->state(fn(array $attributes) => [
            'transaction_type' => 'book_loan',
            'transaction_id' => $bookLoanId ?? $this->faker->numberBetween(1, 100),
        ]);
    }

    /**
     * Create a public review
     */
    public function public(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_public' => true,
        ]);
    }

    /**
     * Create a private review
     */
    public function private(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_public' => false,
        ]);
    }

    /**
     * Create a high rating review (4-5 stars)
     */
    public function positive(): static
    {
        return $this->state(fn(array $attributes) => [
            'rating' => $this->faker->numberBetween(4, 5),
            'review' => $this->faker->randomElement([
                'Great experience! Very reliable and trustworthy person.',
                'Excellent communication and very prompt with responses.',
                'Smooth transaction, would definitely work with again.',
                'Professional and courteous throughout the entire process.',
                'Highly recommended! Great person to deal with.',
            ]),
        ]);
    }

    /**
     * Create a negative rating review (1-2 stars)
     */
    public function negative(): static
    {
        return $this->state(fn(array $attributes) => [
            'rating' => $this->faker->numberBetween(1, 2),
            'review' => $this->faker->randomElement([
                'Communication could have been better.',
                'Had some issues with the transaction.',
                'Not as described, somewhat disappointing.',
                'Response time was very slow.',
                'Had difficulties coordinating the exchange.',
            ]),
        ]);
    }
}
