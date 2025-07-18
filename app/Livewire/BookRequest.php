<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BookRequest as BookRequestModel;
use App\Models\BookInstance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class BookRequest extends Component
{
    public $name;
    public $email;
    public $address;
    public $message;
    public BookInstance $bookInstance;
    public $user_id = null;
    public $existingRequest = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'address' => 'required|string|max:255',
        'message' => 'required|string',
    ];

    public function mount(BookInstance $bookInstance)
    {
        $this->bookInstance = $bookInstance;

        if (Auth::check()) {
            $user = Auth::user();
            $this->user_id = $user->id;
            $this->name = $user->name ?? '';
            $this->email = $user->email ?? '';
            $this->address = $user->address ?? '';
            // Only for logged in user, show status card if request exists
            $this->existingRequest = BookRequestModel::where('user_id', $user->id)
                ->where('book_instance_id', $this->bookInstance->id)
                ->latest()
                ->first();
        }
        // For guests, do not set $existingRequest here
    }

    public function updatedEmail($email)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->existingRequest = BookRequestModel::where('user_id', $user->id)
                ->where('book_instance_id', $this->bookInstance->id)
                ->latest()
                ->first();
            if ($this->existingRequest) {
                $this->addError('email', 'You have already requested this book.');
            } else {
                $this->resetErrorBag('email');
            }
        } else {
            // For guests, just check by email and show error, but do not set $existingRequest
            $exists = BookRequestModel::where('email', $email)
                ->where('book_instance_id', $this->bookInstance->id)
                ->exists();
            if ($exists) {
                $this->addError('email', 'You have already requested this book.');
            } else {
                $this->resetErrorBag('email');
            }
        }
    }

    public function submit()
    {
        if ($this->getErrorBag()->any()) {
            return;
        }

        try {
            $this->validate();

            $request = BookRequestModel::create([
                'book_id' => $this->bookInstance->book_id,
                'book_instance_id' => $this->bookInstance->id,
                'user_id' => $this->user_id,
                'name' => $this->name,
                'email' => $this->email,
                'address' => $this->address,
                'message' => $this->message,
            ]);

            // Only set $existingRequest for logged in user
            if (Auth::check()) {
                $this->existingRequest = $request;
            }

            session()->flash('success', 'Your request has been sent!');
            $this->reset(['name', 'email', 'address', 'message']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                session()->flash('error', 'You have already requested this book.');
            } else {
                session()->flash('error', 'An unexpected error occurred. Please try again.');
            }
        } catch (\Throwable $th) {
            session()->flash('error', 'An error occurred. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.book-request');
    }
}
