<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BookInstance;

class Books extends Component
{
    public function render()
    {
        $instances = BookInstance::with('book')
            ->whereNot('owner_id', auth()->id())
            ->get();
        return view('livewire.books', compact('instances'));
    }
}
