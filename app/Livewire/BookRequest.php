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

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'address' => 'required|string|max:255',
        'message' => 'required|string',
    ];

    public function mount(BookInstance $bookInstance)
    {
        $this->bookInstance = $bookInstance;

        // Pre-populate fields if user is logged in
        if (Auth::check()) {
            $user = Auth::user();
            $this->user_id = $user->id;
            $this->name = $user->name ?? '';
            $this->email = $user->email ?? '';
            $this->updatedEmail($this->email);
            $this->address = $user->address ?? '';
        }
    }

    public function updatedEmail($email)
    {
        $existingRequest = BookRequestModel::where('email', $email)
            ->where('book_instance_id', $this->bookInstance->id)
            ->first();

        if ($existingRequest) {
            $this->addError('email', 'You have already requested this book.');
            // Disable the submit button or show an error
        } else {
            $this->resetErrorBag('email');
        }
    }

    public function submit()
    {
        try {
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
