<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\BookRequestService;
use Illuminate\Support\Facades\Auth;
use App\Models\BookRequest;

class MyBookRequests extends Component
{
    public $requests;

    public function mount()
    {
        $this->requests = BookRequestService::getReceivedRequestsForUser(Auth::id());
    }

    public function accept($requestId)
    {
        $request = BookRequest::findOrFail($requestId);
        BookRequestService::updateStatus($request, 'accepted');
        $this->mount();
        session()->flash('message', 'Request approved.');
    }

    public function reject($requestId)
    {
        $request = BookRequest::findOrFail($requestId);
        BookRequestService::updateStatus($request, 'rejected');
        $this->mount();
        session()->flash('message', 'Request rejected.');
    }

    public function render()
    {
        return view('livewire.my-book-requests')->layout('layouts.dashboard', [
            'heading' => __('Book Requests'),
            'subheading' => __('Manage requests you have received for your books.')
        ]);
    }
}
