<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BookLoan;
use App\Services\BookLoanService;
use Illuminate\Support\Facades\Auth;

class BookLoans extends Component
{
    public $activeTab = 'as_borrower';
    public $statusFilter = '';
    public $showModal = false;
    public $selectedLoan = null;
    public $actionType = '';
    public $notes = '';
    public $extensionDays = 7;

    protected $listeners = ['loanUpdated' => '$refresh'];

    public function mount()
    {
        $this->activeTab = 'as_borrower';
    }

    public function render()
    {
        $loans = $this->getLoans();
        $statistics = BookLoanService::getLoanStatistics(Auth::id());

        return view('livewire.book-loans', [
            'loans' => $loans,
            'statistics' => $statistics,
        ]);
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->statusFilter = '';
    }

    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
    }

    private function getLoans()
    {
        $userId = Auth::id();
        
        if ($this->activeTab === 'as_borrower') {
            return BookLoanService::getLoansForBorrower($userId, $this->statusFilter ?: null);
        } else {
            return BookLoanService::getLoansForOwner($userId, $this->statusFilter ?: null);
        }
    }

    public function openModal($loanId, $action)
    {
        $this->selectedLoan = BookLoan::find($loanId);
        $this->actionType = $action;
        $this->notes = '';
        $this->extensionDays = 7;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedLoan = null;
        $this->actionType = '';
        $this->notes = '';
        $this->extensionDays = 7;
    }

    public function performAction()
    {
        if (!$this->selectedLoan) {
            return;
        }

        try {
            switch ($this->actionType) {
                case 'mark_received':
                    BookLoanService::markAsReceived($this->selectedLoan, $this->notes);
                    session()->flash('message', 'Book marked as received successfully!');
                    break;

                case 'mark_reading':
                    BookLoanService::markAsReading($this->selectedLoan, $this->notes);
                    session()->flash('message', 'Book marked as being read!');
                    break;

                case 'mark_returned':
                    BookLoanService::markAsReturned($this->selectedLoan, $this->notes);
                    session()->flash('message', 'Book marked as returned. Waiting for owner confirmation.');
                    break;

                case 'confirm_return':
                    BookLoanService::confirmReturn($this->selectedLoan, $this->notes);
                    session()->flash('message', 'Return confirmed successfully!');
                    break;

                case 'deny_return':
                    BookLoanService::denyReturn($this->selectedLoan, $this->notes);
                    session()->flash('message', 'Return denied. Borrower has been notified.');
                    break;

                case 'mark_lost':
                    BookLoanService::markAsLost($this->selectedLoan, $this->notes);
                    session()->flash('message', 'Book marked as lost.');
                    break;

                case 'mark_disputed':
                    BookLoanService::markAsDisputed($this->selectedLoan, $this->notes);
                    session()->flash('message', 'Dispute has been raised.');
                    break;

                case 'extend_loan':
                    BookLoanService::extendLoan($this->selectedLoan, $this->extensionDays, $this->notes);
                    session()->flash('message', "Loan extended by {$this->extensionDays} days!");
                    break;
            }

            $this->closeModal();
            $this->dispatch('loanUpdated');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function getStatusBadgeColor($status)
    {
        return match ($status) {
            BookLoan::STATUS_DELIVERED => 'blue',
            BookLoan::STATUS_RECEIVED => 'green',
            BookLoan::STATUS_READING => 'indigo',
            BookLoan::STATUS_RETURNED => 'yellow',
            BookLoan::STATUS_RETURN_CONFIRMED => 'green',
            BookLoan::STATUS_RETURN_DENIED => 'red',
            BookLoan::STATUS_LOST => 'red',
            BookLoan::STATUS_DISPUTED => 'purple',
            default => 'gray'
        };
    }

    public function getStatusDisplayName($status)
    {
        return match ($status) {
            BookLoan::STATUS_DELIVERED => 'Delivered',
            BookLoan::STATUS_RECEIVED => 'Received',
            BookLoan::STATUS_READING => 'Reading',
            BookLoan::STATUS_RETURNED => 'Return Pending',
            BookLoan::STATUS_RETURN_CONFIRMED => 'Returned',
            BookLoan::STATUS_RETURN_DENIED => 'Return Denied',
            BookLoan::STATUS_LOST => 'Lost',
            BookLoan::STATUS_DISPUTED => 'Disputed',
            default => $status
        };
    }

    public function canPerformAction($loan, $action)
    {
        $userId = Auth::id();
        $isBorrower = $loan->borrower_id === $userId;
        $isOwner = $loan->owner_id === $userId;

        return match ($action) {
            'mark_received' => $isBorrower && $loan->status === BookLoan::STATUS_DELIVERED,
            'mark_reading' => $isBorrower && in_array($loan->status, [BookLoan::STATUS_RECEIVED]),
            'mark_returned' => $isBorrower && in_array($loan->status, [BookLoan::STATUS_RECEIVED, BookLoan::STATUS_READING]),
            'confirm_return' => $isOwner && $loan->status === BookLoan::STATUS_RETURNED,
            'deny_return' => $isOwner && $loan->status === BookLoan::STATUS_RETURNED,
            'mark_lost' => $isOwner && $loan->isActive(),
            'mark_disputed' => ($isBorrower || $isOwner) && !in_array($loan->status, [BookLoan::STATUS_RETURN_CONFIRMED, BookLoan::STATUS_DISPUTED]),
            'extend_loan' => $isOwner && $loan->isActive(),
            default => false
        };
    }
}
