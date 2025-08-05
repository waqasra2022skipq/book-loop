<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\BookRequestService;
use Illuminate\Support\Facades\Auth;
use App\Models\BookRequest;

class MyBookRequests extends Component
{
    public $tab = 'pending';
    public $pendingRequests = [];
    public $approvedRequests = [];
    public $rejectedRequests = [];

    public function mount()
    {
        $this->fetchRequests();
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function fetchRequests()
    {
        $userId = Auth::id();
        $this->pendingRequests = BookRequestService::getRequestsByStatus($userId, 'pending');
        $this->approvedRequests = BookRequestService::getRequestsByStatus($userId, 'accepted');
        $this->rejectedRequests = BookRequestService::getRequestsByStatus($userId, 'rejected');
    }

    public function accept($requestId)
    {
        $request = BookRequest::findOrFail($requestId);
        BookRequestService::updateStatus($request, 'accepted');
        $this->fetchRequests();
        $this->dispatch('acceptedRequest');
    }

    public function reject($requestId)
    {
        $request = BookRequest::findOrFail($requestId);
        BookRequestService::updateStatus($request, 'rejected');
        $this->fetchRequests();
        $this->dispatch('rejectedRequest');
    }

    public function render()
    {
        return view('livewire.my-book-requests');
    }
}
