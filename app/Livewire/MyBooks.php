<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\BookInstance;

class MyBooks extends Component
{
    public $books;

    protected $listeners = ['book-updated' => 'fetchBooks'];

    public function mount()
    {
        $this->fetchBooks();
    }

    public function fetchBooks()
    {
        $this->books = BookInstance::with('book')
            ->where('owner_id', Auth::id())
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.my-books');
    }

    public function delete(BookInstance $bookInstance)
    {
        $bookInstance->delete();
        $this->fetchBooks();
        $this->dispatch('book-deleted', "Book deleted successfully!");
    }
}
