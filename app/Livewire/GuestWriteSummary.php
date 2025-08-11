<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;
use App\Models\BookSummary;
use App\Models\User;
use App\Services\BookRequestService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GuestWriteSummary extends Component
{
    public $bookId;
    public $book;

    // User profile fields
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $city = '';
    public $state = '';
    public $postal_code = '';

    // Summary fields
    public $title = '';
    public $summary = '';
    public $rating = 5;

    public $isSubmitting = false;
    public $showSuccess = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'postal_code' => 'nullable|string|max:20',
        'summary' => 'required|string|min:20',
        'rating' => 'required|integer|min:1|max:5',
    ];

    protected $messages = [
        'rating.min' => 'Rating must be between 1 and 5.',
        'rating.max' => 'Rating must be between 1 and 5.',
    ];

    public function mount($bookId)
    {
        $this->bookId = $bookId;
        $this->book = Book::findOrFail($bookId);
    }

    public function submitSummary()
    {
        $this->isSubmitting = true;

        try {
            $this->validate();

            DB::transaction(function () {
                // Create or find user using BookRequestService
                $bookRequestService = app(BookRequestService::class);

                $userData = [
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'address' => $this->address,
                    'city' => $this->city,
                    'state' => $this->state,
                    'postal_code' => $this->postal_code,
                ];

                $user = $bookRequestService->createOrFindUser($userData);
                $user = $user['user'];

                // Create the book summary
                BookSummary::create([
                    'book_id' => $this->book->id,
                    'user_id' => $user->id,
                    'summary' => $this->summary,
                    'rating' => $this->rating,
                ]);

                Log::info('Guest user created book summary', [
                    'user_id' => $user->id,
                    'book_id' => $this->book->id,
                    'email' => $this->email
                ]);
            });

            $this->showSuccess = true;
            $this->reset(['name', 'email', 'phone', 'address', 'city', 'state', 'postal_code', 'title', 'summary', 'rating']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to create guest user summary', [
                'error' => $e->getMessage(),
                'book_id' => $this->book->id,
                'email' => $this->email
            ]);

            $this->addError('summary', 'There was an error submitting your summary. Please try again.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function closeSuccess()
    {
        $this->showSuccess = false;
        return redirect()->route('books.all');
    }

    public function render()
    {
        return view('livewire.guest-write-summary');
    }
}
