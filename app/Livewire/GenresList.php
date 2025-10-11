<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Genre;

class GenresList extends Component
{
    use WithPagination;

    public $search = '';
    
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $genres = Genre::active()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->withCount('books')
            ->orderByName()
            ->paginate(12);

        return view('livewire.genres-list', compact('genres'))
            ->title('Browse Genres - Book Loop');
    }
}