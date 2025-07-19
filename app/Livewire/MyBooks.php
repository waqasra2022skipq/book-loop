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
        return view('livewire.my-books')->layout('layouts.dashboard', [
            'heading' => __('My Books'),
            'subheading' => __('Here you can manage the books you have added to your collection.')
        ]);
    }

    public function delete($id)
    {
        $book = BookInstance::where('id', $id)->where('owner_id', Auth::id())->firstOrFail();
        $book->delete();
        $this->fetchBooks();
        session()->flash('message', 'Book deleted successfully!');
    }
}
