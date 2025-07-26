<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BookSummary;
use App\Models\Book;

class BookSummaries extends Component
{
    public $bookId;

    public function mount($bookId)
    {
        $this->bookId = $bookId;
    }

    public function render()
    {
        $summaries = BookSummary::with('writer')
            ->where('book_id', $this->bookId)
            ->latest()
            ->get();
        return view('livewire.book-summaries', compact('summaries'));
    }
}
