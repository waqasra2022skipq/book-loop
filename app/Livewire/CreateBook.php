<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\BookInstance;
use App\Services\BookImageService;

class CreateBook extends Component
{
    use WithFileUploads;

    public string $searchTerm = '';
    public string $title = '';
    public string $author = '';
    public string $isbn = '';
    public string $status = "available";
    public string $notes = '';
    public $cover_image;
    public string $city = '';
    public string $address = '';
    public $lat;
    public $lng;
    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->fill([
                'city' => $user->city ?? '',
                'address' => $user->address ?? '',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.create-book')->layout('layouts.dashboard', [
            'heading' => __('Add a Book'),
            'subheading' => __('Fill in the details below to add a new book to your collection.')
        ]);
    }

    public function submit()
    {
        $validated = $this->validate([
            'title' => 'required|string',
            'author' => 'nullable|string',
            'isbn' => 'nullable|string',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'city' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $book = Book::firstOrCreate(
            ['isbn' => $validated['isbn'] ?: null, 'title' => $validated['title']],
            ['author' => $validated['author']]
        );

        BookImageService::uploadAndSave($book, $validated['cover_image'] ?? null);

        BookInstance::create([
            'book_id' => $book->id,
            'owner_id' => Auth::id(),
            'condition_notes' => $validated['notes'],
            'status' => $validated['status'],
            'city' => $validated['city'],
            'address' => $validated['address'],
        ]);

        $this->reset(['searchTerm', 'title', 'author', 'isbn', 'status', 'notes', 'cover_image', 'city', 'address', 'lat', 'lng']);
        session()->flash('message', 'Book added successfully!');
        return redirect()->route('books.mybooks');
    }
}
