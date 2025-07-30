<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserQuery;
use Illuminate\Support\Facades\Auth;

class ContactUs extends Component
{
    public string $name = '';
    public string $email = '';
    public string $subject = '';
    public string $message = '';
    public string $type = 'query';

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
        }
    }

    public function submit()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10',
            'type' => 'required|in:suggestion,comment,query',
        ]);

        UserQuery::create([
            'user_id' => Auth::id(),
            ...$validated,
        ]);

        session()->flash('success', 'Thank you for your feedback! We appreciate your input.');
        $this->reset(['subject', 'message', 'type']);
    }

    public function render()
    {
        return view('livewire.contact-us');
    }
}
