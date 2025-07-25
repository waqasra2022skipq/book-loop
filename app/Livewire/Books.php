<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\BookInstance;
use Livewire\Attributes\Title;

class Books extends Component
{
    use WithPagination;

    #[Url(as: 'search', keep: true)]
    public $search = '';

    public $searchPlaceholder = 'Search books by title, author, genre...';

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'q'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    #[Title('Explore Books')] 
    public function render()
    {
        $query = BookInstance::with('book')
            ->whereNot('owner_id', auth()->id());

        if ($this->search) {
            $query->whereHas('book', function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('author', 'like', '%' . $this->search . '%')
                    ->orWhere('genre', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        $instances = $query->paginate(12);

        return view('livewire.books', compact('instances'));
    }
}
