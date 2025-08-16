<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\BookInstance;

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

    public function getDescriptionProperty()
    {
        if ($this->search) {
            return "Search results for '{$this->search}' - Find books by title, author, or genre in your area.";
        }

        return 'Explore hundreds of free books to read in your city. Find your next great read from our community of book lovers.';
    }

    public function getTitleProperty()
    {
        if ($this->search) {
            return "Search: {$this->search} - Book Explorer";
        }

        return 'Explore Books - Book Loop';
    }

    public function render()
    {
        $query = BookInstance::with('book')
            ->where('status', 'available')
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

        return view('livewire.books', compact('instances'))
            ->layoutData([
                'title' => $this->title,
                'description' => $this->description
            ]);
    }
}
