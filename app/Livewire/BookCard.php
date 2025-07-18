<?php
// app/Livewire/BookCard.php

namespace App\Livewire;

use Livewire\Component;

class BookCard extends Component
{
    public $instance;

    public function mount($instance)
    {
        $this->instance = $instance;
    }

    public function render()
    {
        return view('livewire.book-card');
    }
}
