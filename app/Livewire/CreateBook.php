<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\BookInstance;
use App\Models\Genre;
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
    public $genre_id = '';
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

    public function submit()
    {
        $validated = $this->validate([
            'title' => 'required|string',
            'author' => 'nullable|string',
            'isbn' => 'nullable|string',
            'genre_id' => 'nullable|exists:genres,id',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'city' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $book = Book::firstOrCreate(
            ['isbn' => $validated['isbn'] ?: null, 'title' => $validated['title']],
            [
                'author' => $validated['author'],
                'genre_id' => $validated['genre_id'] ?: null,
            ]
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

        $this->reset(['searchTerm', 'title', 'author', 'isbn', 'genre_id', 'status', 'notes', 'cover_image', 'city', 'address', 'lat', 'lng']);
        return redirect()->route('books.my-books');
    }

    public function render()
    {
        $genres = Genre::active()->orderByName()->get();
        return view('livewire.create-book', compact('genres'));
    }
}
