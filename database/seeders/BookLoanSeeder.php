<?php

namespace Database\Seeders;

use App\Models\BookLoan;
use App\Models\BookRequest;
use App\Models\BookInstance;
use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class BookLoanSeeder extends Seeder
{
    private $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    public function run(): void
    {
        // Get existing accepted book requests to create loans from
        $acceptedRequests = BookRequest::with(['bookInstance.owner', 'requester'])
            ->where('status', 'accepted')
            ->whereNotNull('user_id')
            ->limit(5)
            ->get();

        foreach ($acceptedRequests as $request) {
            if (!$request->loan && $request->user_id && $request->bookInstance) { // Only create loan if it doesn't exist
                BookLoan::factory()->create([
                    'book_request_id' => $request->id,
                    'book_id' => $request->book_id,
                    'book_instance_id' => $request->book_instance_id,
                    'borrower_id' => $request->user_id,
                    'owner_id' => $request->bookInstance->owner_id,
                ]);
            }
        }

        // Create some additional sample loans with different statuses
        $users = User::take(5)->get();
        $bookInstances = BookInstance::with('owner')->take(10)->get();

        if ($users->count() >= 2 && $bookInstances->count() >= 5) {
            // Create loans with different statuses for demonstration
            foreach ($bookInstances->take(3) as $bookInstance) {
                $borrower = $users->where('id', '!=', $bookInstance->owner_id)->first();
                
                if ($borrower) {
                    // Create a book request first
                    $request = BookRequest::create([
                        'book_id' => $bookInstance->book_id,
                        'book_instance_id' => $bookInstance->id,
                        'user_id' => $borrower->id,
                        'name' => $borrower->name,
                        'email' => $borrower->email,
                        'address' => $borrower->address ?? $this->faker->address(),
                        'phone' => $borrower->phone ?? $this->faker->phoneNumber(),
                        'status' => 'accepted',
                    ]);

                    // Create loan with random status
                    $statuses = ['delivered', 'received', 'reading', 'returned', 'returnConfirmed'];
                    $status = $statuses[array_rand($statuses)];
                    
                    BookLoan::factory()->{$status}()->create([
                        'book_request_id' => $request->id,
                        'book_id' => $bookInstance->book_id,
                        'book_instance_id' => $bookInstance->id,
                        'borrower_id' => $borrower->id,
                        'owner_id' => $bookInstance->owner_id,
                    ]);
                }
            }

            // Create some problematic loans
            $problemInstances = $bookInstances->skip(5)->take(2);
            foreach ($problemInstances as $bookInstance) {
                $borrower = $users->where('id', '!=', $bookInstance->owner_id)->first();
                
                if ($borrower) {
                    $request = BookRequest::create([
                        'book_id' => $bookInstance->book_id,
                        'book_instance_id' => $bookInstance->id,
                        'user_id' => $borrower->id,
                        'name' => $borrower->name,
                        'email' => $borrower->email,
                        'address' => $borrower->address ?? $this->faker->address(),
                        'phone' => $borrower->phone ?? $this->faker->phoneNumber(),
                        'status' => 'accepted',
                    ]);

                    $problemStatuses = ['lost', 'disputed'];
                    $status = $problemStatuses[array_rand($problemStatuses)];
                    
                    BookLoan::factory()->{$status}()->create([
                        'book_request_id' => $request->id,
                        'book_id' => $bookInstance->book_id,
                        'book_instance_id' => $bookInstance->id,
                        'borrower_id' => $borrower->id,
                        'owner_id' => $bookInstance->owner_id,
                    ]);
                }
            }
        }
    }
}
