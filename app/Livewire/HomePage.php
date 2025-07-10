<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;

class HomePage extends Component
{
    public $search = '';
    public $newlyUploadedBooks = [];
    public $popularBooks = [];

    public function mount()
    {
        // Load sample data - replace with actual database queries
        $this->loadNewlyUploadedBooks();
        $this->loadPopularBooks();
    }

    public function updatedSearch()
    {
        // Handle search functionality
        if (strlen($this->search) > 2) {
            // Perform search logic here
            $this->dispatch('search-books', search: $this->search);
        }
    }

    private function loadNewlyUploadedBooks()
    {
        // Sample data - replace with: Book::latest()->limit(3)->get()
        $this->newlyUploadedBooks = Book::latest()->limit(3)->get();
        // dd($this->newlyUploadedBooks);
    }

    private function loadPopularBooks()
    {
        // Sample data - replace with: Book::withCount('requests')->orderBy('requests_count', 'desc')->limit(6)->get()
        $this->popularBooks = [
            [
                'id' => 4,
                'title' => 'Ank Bunies',
                'cover' => 'https://images.unsplash.com/photo-1592496431122-2349e0fbc666?w=200&h=280&fit=crop',
                'category' => 'Vegoting books'
            ],
            [
                'id' => 5,
                'title' => 'Book Boiny',
                'cover' => 'https://images.unsplash.com/photo-1519904981063-b0cf448d479e?w=200&h=280&fit=crop',
                'category' => 'Cooks'
            ],
            [
                'id' => 6,
                'title' => 'Choyet',
                'cover' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=200&h=280&fit=crop',
                'category' => 'Free Bon and Nary'
            ],
            [
                'id' => 7,
                'title' => 'Adventure Tales',
                'cover' => 'https://images.unsplash.com/photo-1476275466078-4007374efbbe?w=200&h=280&fit=crop',
                'category' => 'Adventure'
            ],
            [
                'id' => 8,
                'title' => 'Cooking Mastery',
                'cover' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?w=200&h=280&fit=crop',
                'category' => 'Culinary'
            ],
            [
                'id' => 9,
                'title' => 'Poetry Collection',
                'cover' => 'https://images.unsplash.com/photo-1524578271613-d550eacf6090?w=200&h=280&fit=crop',
                'category' => 'Poetry'
            ]
        ];
    }

    public function render()
    {
        return view('livewire.home-page');
    }
}
