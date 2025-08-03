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

    public function mount($bookid)
    {
        $this->bookId = $bookid;
        $existing = BookSummary::where('book_id', $bookid)->where('user_id', Auth::id())->first();
        if ($existing) {
            $this->summary = $existing->summary;
            $this->rating = $existing->rating;
        }
    }

    public function save()
    {
        $this->validate([
            'summary' => 'required|string|min:10',
            'rating' => 'nullable|integer|min:1|max:5',
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
