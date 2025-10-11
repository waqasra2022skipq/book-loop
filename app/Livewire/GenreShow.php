<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Genre;
use App\Models\BookInstance;

class GenreShow extends Component
{
    use WithPagination;

    public Genre $genre;
    public $search = '';
    public $sortBy = 'newest';

    public function mount(Genre $genre)
    {
        $this->genre = $genre;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function render()
    {
        $instances = BookInstance::whereHas('book', function ($query) {
                $query->where('genre_id', $this->genre->id);
            })
            ->when($this->search, function ($query) {
                $query->whereHas('book', function ($bookQuery) {
                    $bookQuery->where('title', 'like', '%' . $this->search . '%')
                             ->orWhere('author', 'like', '%' . $this->search . '%');
                });
            })
            ->with(['book', 'owner'])
            ->when($this->sortBy === 'newest', function ($query) {
                $query->latest();
            })
            ->when($this->sortBy === 'oldest', function ($query) {
                $query->oldest();
            })
            ->when($this->sortBy === 'title_asc', function ($query) {
                $query->join('books', 'book_instances.book_id', '=', 'books.id')
                      ->orderBy('books.title', 'asc')
                      ->select('book_instances.*');
            })
            ->when($this->sortBy === 'title_desc', function ($query) {
                $query->join('books', 'book_instances.book_id', '=', 'books.id')
                      ->orderBy('books.title', 'desc')
                      ->select('book_instances.*');
            })
            ->paginate(12);

        return view('livewire.genre-show', compact('instances'))
            ->title($this->genre->display_name . ' Books - Book Loop');
    }
}