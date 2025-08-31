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

    #[Url(as: 'city', keep: true)]
    public $citySearch = '';

    public $searchPlaceholder = 'Search books by title, author, genre...';
    public $citySearchPlaceholder = 'Search by city...';

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'q'],
        'citySearch' => ['except' => '', 'as' => 'city'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCitySearch()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->citySearch = '';
        $this->resetPage();
    }

    public function getDescriptionProperty()
    {
        $searchTerms = [];
        if ($this->search) {
            $searchTerms[] = "'{$this->search}'";
        }
        if ($this->citySearch) {
            $searchTerms[] = "city '{$this->citySearch}'";
        }

        if (!empty($searchTerms)) {
            $searchText = implode(' and ', $searchTerms);
            return "Search results for {$searchText} - Find books by title, author, genre, or location in your area.";
        }

        return 'Explore hundreds of free books to read in your city. Find your next great read from our community of book lovers.';
    }

    public function getTitleProperty()
    {
        $searchTerms = [];
        if ($this->search) {
            $searchTerms[] = $this->search;
        }
        if ($this->citySearch) {
            $searchTerms[] = $this->citySearch;
        }

        if (!empty($searchTerms)) {
            $searchText = implode(', ', $searchTerms);
            return "Search: {$searchText} - Book Explorer";
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

        if ($this->citySearch) {
            $query->where(function ($q) {
                $q->where('city', 'like', '%' . $this->citySearch . '%')
                    ->orWhere('address', 'like', '%' . $this->citySearch . '%');
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
