<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;
use App\Models\BookInstance;
use Illuminate\Support\Facades\Auth;

class EditBookInstance extends Component
{
    public $book;
    public $bookInstance;

    // Book metadata
    public $title;
    public $author;
    public $isbn;

    // BookInstance metadata
    public $status;
    public $notes;

    public function mount($bookid)
    {
        $this->book = Book::findOrFail($bookid);

        $this->bookInstance = BookInstance::where('book_id', $bookid)
            ->where('owner_id', Auth::id())
            ->firstOrFail();

        // Book fields
        $this->title = $this->book->title;
        $this->author = $this->book->author;
        $this->isbn = $this->book->isbn;

        // Instance fields
        $this->status = $this->bookInstance->status;
        $this->notes = $this->bookInstance->condition_notes;
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'status' => 'required|string|in:available,reading,reserved',
            'notes' => 'nullable|string|max:2000',
        ]);

        $this->book->update([
            'title' => $this->title,
            'author' => $this->author,
            'isbn' => $this->isbn,
        ]);

        $this->bookInstance->update([
            'status' => $this->status,
            'condition_notes' => $this->notes,
        ]);

        session()->flash('message', 'Book updated successfully!');
        return redirect()->route('books.mybooks');
    }

    public function render()
    {
        return view('livewire.edit-book-instance');
    }
}
