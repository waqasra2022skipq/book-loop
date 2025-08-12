<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Book;
use App\Models\BookInstance;
use Illuminate\Support\Facades\Auth;
use App\Services\BookImageService;
use Livewire\Attributes\Locked;


class EditBookInstance extends Component
{
    public string $city = '';
    public string $address = '';
    use WithFileUploads;

    #[Locked]
    public Book $book;
    #[Locked]
    public BookInstance $bookInstance;

    // Book metadata
    public string $title = '';
    public string $author = '';
    public string $isbn = '';

    // BookInstance metadata
    public string $status = '';
    public string $notes = '';

    public $image;
    public $currentImage;

    public function mount($bookId)
    {
        $this->book = Book::findOrFail($bookId);
        $this->bookInstance = BookInstance::where('book_id', $bookId)
            ->where('owner_id', Auth::id())
            ->firstOrFail();
        $this->fill([
            'title' => $this->book->title,
            'author' => $this->book->author,
            'isbn' => $this->book->isbn,
            'status' => $this->bookInstance->status,
            'notes' => $this->bookInstance->condition_notes,
            'currentImage' => $this->bookInstance->book->cover_image ?? null,
            'city' => $this->bookInstance->city,
            'address' => $this->bookInstance->address,
        ]);
    }

    public function update()
    {
        $validated = $this->validate([
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
            'title' => $validated['title'],
            'author' => $validated['author'],
            'isbn' => $validated['isbn'],
        ]);

        $updateData = [
            'status' => $validated['status'],
            'condition_notes' => $validated['notes'],
            'city' => $validated['city'],
            'address' => $validated['address'],
        ];

        BookImageService::uploadAndSave($this->book, $validated['image'] ?? null);

        if ($validated['image']) {
            $this->currentImage = $this->book->cover_image;
        }

        $this->bookInstance->update($updateData);

        session()->flash('message', 'Book updated successfully!');
        $this->dispatch('bookUpdated', "Book updated successfully!");
    }

    public function render()
    {
        return view('livewire.edit-book-instance');
    }
}
