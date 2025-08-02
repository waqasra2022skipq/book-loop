<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Locked;
use App\Models\BookRequest as BookRequestModel;
use App\Models\BookInstance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Notifications\BookRequestStatusNotification;

class BookRequest extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public string $message = '';
    
    #[Locked]
    public BookInstance $bookInstance;
    
    #[Locked]
    public ?int $user_id = null;
    
    public ?BookRequestModel $existingRequest = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'address' => 'required|string|max:255',
        'message' => 'nullable|string',
    ];

    public function mount(BookInstance $bookInstance)
    {
        $this->bookInstance = $bookInstance;

        if (Auth::check()) {
            $user = Auth::user();
            $this->user_id = $user->id;
            
            // Use fill() for bulk assignment as recommended in docs
            $this->fill([
                'name' => $user->name ?? '',
                'email' => $user->email ?? '',
                'phone' => $user->phone ?? '',
                'address' => $user->address ?? '',
            ]);
            
            // Only for logged in user, show status card if request exists
            $this->existingRequest = BookRequestModel::where('user_id', $user->id)
                ->where('book_instance_id', $this->bookInstance->id)
                ->latest()
                ->first();
        }
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
            $validated = $this->validate();

            $request = BookRequestModel::create([
                'book_id' => $this->bookInstance->book_id,
                'book_instance_id' => $this->bookInstance->id,
                'user_id' => $this->user_id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'message' => $validated['message'],
            ]);

            // Only set $existingRequest for logged in user
            if (Auth::check()) {
                $this->existingRequest = $request;
            }

            // Notify the book owner
            $owner = $this->bookInstance->owner;
            if ($owner) {
                $owner->notify(new BookRequestStatusNotification($request, 'received', 'You have received a new book request.'));
            }

            // Dispatch Livewire event for UI feedback
            $this->dispatch('notify', type: 'success', message: 'Your request has been sent!');

            // Use reset() method as recommended in docs
            $this->reset(['name', 'email', 'phone', 'address', 'message']);
            
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                $this->dispatch('notify', type: 'error', message: 'You have already requested this book.');
            } else {
                $this->dispatch('notify', type: 'error', message: 'An unexpected error occurred. Please try again.');
            }
        } catch (\Throwable $th) {
            $this->dispatch('notify', type: 'error', message: $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.book-request');
    }
}
