<?php

namespace Database\Factories;

use App\Models\BookLoan;
use App\Models\Book;
use App\Models\BookInstance;
use App\Models\BookRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class BookLoanFactory extends Factory
{
    protected $model = BookLoan::class;

    public function definition(): array
    {
        $deliveredDate = $this->faker->dateTimeBetween('-60 days', '-1 days');
        $dueDate = Carbon::instance($deliveredDate)->addDays(30);
        
        return [
            'book_request_id' => BookRequest::factory(),
            'book_id' => Book::factory(),
            'book_instance_id' => BookInstance::factory(),
            'borrower_id' => User::factory(),
            'owner_id' => User::factory(),
            'delivered_date' => $deliveredDate,
            'due_date' => $dueDate,
            'return_date' => null,
            'status' => $this->faker->randomElement([
                BookLoan::STATUS_DELIVERED,
                BookLoan::STATUS_RECEIVED,
                BookLoan::STATUS_READING,
            ]),
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }

    public function delivered(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => BookLoan::STATUS_DELIVERED,
                'delivered_date' => $this->faker->dateTimeBetween('-7 days', 'now'),
            ];
        });
    }

    public function received(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => BookLoan::STATUS_RECEIVED,
                'delivered_date' => $this->faker->dateTimeBetween('-14 days', '-7 days'),
            ];
        });
    }

    public function reading(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => BookLoan::STATUS_READING,
                'delivered_date' => $this->faker->dateTimeBetween('-30 days', '-7 days'),
            ];
        });
    }

    public function returned(): Factory
    {
        return $this->state(function (array $attributes) {
            $returnDate = $this->faker->dateTimeBetween('-7 days', 'now');
            return [
                'status' => BookLoan::STATUS_RETURNED,
                'delivered_date' => $this->faker->dateTimeBetween('-60 days', '-30 days'),
                'return_date' => $returnDate,
            ];
        });
    }

    public function returnConfirmed(): Factory
    {
        return $this->state(function (array $attributes) {
            $returnDate = $this->faker->dateTimeBetween('-30 days', 'now');
            return [
                'status' => BookLoan::STATUS_RETURN_CONFIRMED,
                'delivered_date' => $this->faker->dateTimeBetween('-90 days', '-30 days'),
                'return_date' => $returnDate,
            ];
        });
    }

    public function overdue(): Factory
    {
        return $this->state(function (array $attributes) {
            $deliveredDate = $this->faker->dateTimeBetween('-60 days', '-31 days');
            $dueDate = Carbon::instance($deliveredDate)->addDays(30);
            
            return [
                'status' => $this->faker->randomElement([
                    BookLoan::STATUS_RECEIVED,
                    BookLoan::STATUS_READING,
                ]),
                'delivered_date' => $deliveredDate,
                'due_date' => $dueDate,
            ];
        });
    }

    public function lost(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => BookLoan::STATUS_LOST,
                'delivered_date' => $this->faker->dateTimeBetween('-60 days', '-30 days'),
                'notes' => 'Book reported as lost by borrower/owner',
            ];
        });
    }

    public function disputed(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => BookLoan::STATUS_DISPUTED,
                'delivered_date' => $this->faker->dateTimeBetween('-60 days', '-7 days'),
                'notes' => 'Dispute raised regarding book condition/return',
            ];
        });
    }
}
