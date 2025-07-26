<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Book;
use App\Models\BookInstance;
use Illuminate\Support\Facades\Auth;
use App\Services\BookImageService;

class EditBookInstance extends Component
{
    public $city;
    public $address;
    use WithFileUploads;

    public $book;
    public $bookInstance;

    // Book metadata
    public $title;
    public $author;
    public $isbn;

    // BookInstance metadata
    public $status;
    public $notes;

    public $image;
    public $currentImage;

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

        $this->currentImage = $this->bookInstance->book->cover_image ?? null;

        $this->city = $this->bookInstance->city;
        $this->address = $this->bookInstance->address;
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'status' => 'required|string|in:available,reading,reserved',
            'notes' => 'nullable|string|max:2000',
            'image' => 'nullable|image|max:2048', // 2MB Max
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $this->book->update([
            'title' => $this->title,
            'author' => $this->author,
            'isbn' => $this->isbn,
        ]);

        $updateData = [
            'status' => $this->status,
            'condition_notes' => $this->notes,
            'city' => $this->city,
            'address' => $this->address,
        ];

        // Use service for image upload and save
        BookImageService::uploadAndSave($this->book, $this->image);

        if ($this->image) {
            $this->currentImage = $this->book->cover_image;
        }

        $this->bookInstance->update($updateData);

        session()->flash('message', 'Book updated successfully!');
        return redirect()->route('books.mybooks');
    }

    public function render()
    {
        return view('livewire.edit-book-instance')->layout('layouts.dashboard', [
            'heading' => __('Edit Book'),
            'subheading' => __('Update the details below to modify the book information.')
        ]);
    }
}
