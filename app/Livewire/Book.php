<?php

namespace App\Livewire;

use App\Models\Book as BookModel;
use Livewire\Component;

class Book extends Component
{
    public BookModel $book;

    public function mount(BookModel $book)
    {
        $this->book = $book->load(['genre']);
    }

    public function render()
    {
        return view('livewire.book', [
            'instances' => $this->book->instances()
                ->with(['owner'])
                ->orderByRaw("status = 'available' DESC")
                ->latest()
                ->get(),
        ])->layoutData([
            'title' => $this->book->title . ' by ' . $this->book->author,
            'description' => "Discover '{$this->book->title}' by {$this->book->author}.",
        ]);
    }
}
