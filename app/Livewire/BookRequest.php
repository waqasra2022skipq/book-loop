<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Locked;
use App\Models\BookRequest as BookRequestModel;
use App\Models\BookInstance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\BookRequestService;

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

    protected BookRequestService $bookRequestService;

    public function boot(BookRequestService $bookRequestService)
    {
        $this->bookRequestService = $bookRequestService;
    }

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
        $this->validate();
        
        $result = $this->bookRequestService->createBookRequest([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'message' => $this->message,
            ], $this->bookInstance->id, $this->bookInstance->book_id);
        
        $this->dispatch('notify', type: 'success', message: 'Your request has been sent!');
        
        $this->reset(['name', 'email', 'phone', 'address', 'message']);
    }

    public function render()
    {
        return view('livewire.book-request');
    }
}
