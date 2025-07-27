<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BookSummary;
use App\Models\Book;

class BookSummaries extends Component
{
    public $bookId;
    public $perPage = 10;

    public function mount($bookId)
    {
        $this->bookId = $bookId;
    }

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function render()
    {
        $summaries = BookSummary::with('writer')
            ->where('book_id', $this->bookId)
            ->latest()
            ->take($this->perPage)
            ->get();
        $total = BookSummary::where('book_id', $this->bookId)->count();
        return view('livewire.book-summaries', compact('summaries', 'total'));
    }
}
