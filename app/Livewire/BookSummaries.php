<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BookSummary;
use App\Models\Book;

class BookSummaries extends Component
{
    public $bookId;
    public Book $book;
    public $perPage = 10;

    public function mount(Book $book)
    {
        $this->book = $book;
        $this->bookId = $book->id;
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
        return view('livewire.book-summaries', compact('summaries', 'total'))->layoutData([
            'title' => 'User reviews for ' . $this->book->title,
            'description' => 'Read the latest reviews for ' . $this->book->title
        ]);
    }
}
