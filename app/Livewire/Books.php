<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BookInstance;

class Books extends Component
{
    use WithPagination;

    public function render()
    {
        $instances = BookInstance::with('book')
            ->whereNot('owner_id', auth()->id())
            ->paginate(2);

        return view('livewire.books', compact('instances'));
    }
}
