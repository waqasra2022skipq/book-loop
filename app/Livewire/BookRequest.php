<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BookRequest as BookRequestModel;
use Illuminate\Support\Facades\Auth;

class BookRequest extends Component
{
    public $name;
    public $email;
    public $address;
    public $message;
    public $bookInstance;
    public $user_id = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'address' => 'required|string|max:255',
        'message' => 'required|string',
    ];

    public function mount($bookInstance)
    {
        $this->bookInstance = $bookInstance;

        // Pre-populate fields if user is logged in
        if (Auth::check()) {
            $user = Auth::user();
            $this->user_id = $user->id;
            $this->name = $user->name ?? '';
            $this->email = $user->email ?? '';
            $this->address = $user->address ?? '';
        }
    }

    public function submit()
    {
        $this->validate();

        BookRequestModel::create([
            'book_id' => $this->bookInstance->book_id,
            'book_instance_id' => $this->bookInstance->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'message' => $this->message,
        ]);

        session()->flash('success', 'Your request has been sent!');
        $this->reset(['name', 'email', 'address', 'message']);
    }

    public function render()
    {
        return view('livewire.book-request');
    }
}
