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
    public $status;
    public $notes;

    public function mount($bookid)
    {
        $this->book = Book::findOrFail($bookid);
        // Get instance owned by the current user
        $this->bookInstance = BookInstance::where('book_id', $bookid)
            ->where('owner_id', Auth::id())
            ->firstOrFail();

        $this->status = $this->bookInstance->status;
        $this->notes = $this->bookInstance->condition_notes;
    }

    public function update()
    {
        $this->validate([
            'status' => 'required|string|in:available,reading,reserved',
            'notes' => 'nullable|string',
        ]);

        $this->bookInstance->update([
            'status' => $this->status,
            'condition_notes' => $this->notes,
        ]);

        session()->flash('message', 'Book updated successfully!');
        return redirect()->route('books.mybooks'); // or wherever your MyBooks page is
    }

    public function render()
    {
        return view('livewire.edit-book-instance');
    }
}
