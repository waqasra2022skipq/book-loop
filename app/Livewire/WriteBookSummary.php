<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BookSummary;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class WriteBookSummary extends Component
{
    public $bookId;
    public $summary = '';
    public $rating;

    public function mount($bookId = null, $book = null)
    {
        // Handle both old route (bookId) and new route (book)
        $this->bookId = $bookId ?: $book;

        $existing = BookSummary::where('book_id', $this->bookId)->where('user_id', Auth::id())->first();
        if ($existing) {
            $this->summary = $existing->summary;
            $this->rating = $existing->rating;
        }
    }

    public function save()
    {
        $this->validate([
            'summary' => 'required|string|min:10|max:2000',
            'rating' => 'nullable|integer|min:1|max:5',
        ], [
            'summary.required' => 'Please write a summary.',
            'summary.min' => 'Summary must be at least 10 characters long.',
            'summary.max' => 'Summary cannot exceed 2000 characters.',
            'rating.min' => 'Rating must be between 1 and 5 stars.',
            'rating.max' => 'Rating must be between 1 and 5 stars.',
        ]);

        BookSummary::updateOrCreate(
            [
                'book_id' => $this->bookId,
                'user_id' => Auth::id(),
            ],
            [
                'summary' => $this->summary,
                'rating' => $this->rating,
            ]
        );

        session()->flash('message', 'Summary saved!');
        return 1;
    }

    public function render()
    {
        $book = Book::findOrFail($this->bookId);
        return view('livewire.write-book-summary', compact('book'));
    }
}
