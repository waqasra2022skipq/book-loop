<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BookInstance as bookInstanceModel;

class BookInstance extends Component
{

    public bookInstanceModel $bookInstance;

    public function mount($id)
    {
        $this->bookInstance = bookInstanceModel::findOrFail($id);
    }
    public function render()
    {
        // Logic to fetch book instance details can be added here
        // For example, you might want to fetch the book by ID from the route parameter


        return view('livewire.book-instance')->layoutData([
            'title' => "Book Instance - {$this->bookInstance->book->title}",
            'description' => "Read '{$this->bookInstance->book->title}' by {$this->bookInstance->book->author}.",
        ]);
    }
}
