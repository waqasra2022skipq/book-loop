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
    public $showAcceptModal = false;
    public $selectedRequest = null;
    public $loanDuration = 30; // default 30 days

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
        $this->selectedRequest = BookRequest::findOrFail($requestId);
        $this->confirmAccept();
        $this->showAcceptModal = true;
    }

    public function confirmAccept()
    {
        if ($this->selectedRequest) {
            BookRequestService::updateStatus($this->selectedRequest, 'accepted', $this->loanDuration);
            
            // set all other requests for this book instance to rejected
            BookRequestService::rejectPendingRequests($this->selectedRequest->book_instance_id);

            $this->fetchRequests();
            $this->closeAcceptModal();
            $this->dispatch('acceptedRequest');
            session()->flash('message', 'Request accepted and loan created successfully!');
        }
    }

    public function closeAcceptModal()
    {
        $this->showAcceptModal = false;
        $this->selectedRequest = null;
        $this->loanDuration = 30;
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
