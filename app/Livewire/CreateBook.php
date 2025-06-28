<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\BookInstance;

class CreateBook extends Component
{
    use WithFileUploads;

    public $searchTerm;
    public $title;
    public $author;
    public $isbn;
    public $status = "available"; // Default status
    public $notes;
    public $cover_image;

    public function render()
    {
        return view('livewire.create-book');
    }

    public function submit()
    {
        // Validate input
        $validated = $this->validate([
            'title' => 'required|string',
            'author' => 'nullable|string',
            'isbn' => 'nullable|string',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);



        // Save book if new
        $book = Book::firstOrCreate(
            ['isbn' => $this->isbn ?: null, 'title' => $this->title],
            ['author' => $this->author]
        );

        // Handle cover upload
        $coverPath = null;
        if ($this->cover_image) {
            $coverPath = $this->cover_image->store('cover_images', 'public');
            $book->cover_image = $coverPath;
            $book->save();
        }


        // Add entry to book_instances
        BookInstance::create([
            'book_id' => $book->id,
            'owner_id' => Auth::id(),
            'condition_notes' => $this->notes,
            'status' => $this->status,
        ]);

        // Reset form
        $this->reset(['searchTerm', 'title', 'author', 'isbn', 'status', 'notes', 'cover_image']);
        session()->flash('message', 'Book added successfully!');
    }
}
