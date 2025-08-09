<?php

namespace App\Services;

use App\Models\BookLoan;
use App\Models\BookRequest;
use App\Models\BookInstance;
use App\Models\User;
use App\Notifications\BookRequestStatusNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookLoanService
{
    /**
     * Default loan duration in days
     */
    const DEFAULT_LOAN_DURATION = 30;

    /**
     * Create a loan from an accepted book request
     */
    public static function createLoanFromRequest(BookRequest $request, ?int $loanDurationDays = null): BookLoan
    {
        if ($request->status !== 'accepted') {
            throw new \InvalidArgumentException('Can only create loans from accepted requests');
        }

        $loanDuration = $loanDurationDays ?? self::DEFAULT_LOAN_DURATION;
        $deliveredDate = Carbon::now()->toDateString();
        $dueDate = Carbon::now()->addDays($loanDuration)->toDateString();

        return DB::transaction(function () use ($request, $deliveredDate, $dueDate) {
            // Create the loan
            $loan = BookLoan::create([
                'book_request_id' => $request->id,
                'book_id' => $request->book_id,
                'book_instance_id' => $request->book_instance_id,
                'borrower_id' => $request->user_id,
                'owner_id' => $request->bookInstance->owner_id,
                'delivered_date' => $deliveredDate,
                'due_date' => $dueDate,
                'status' => BookLoan::STATUS_DELIVERED,
            ]);

            // Update book instance status
            $request->bookInstance->update(['status' => 'borrowed']);

            // Notify borrower that book has been delivered
            self::sendLoanNotification($loan, 'delivered');

            return $loan;
        });
    }

    /**
     * Update loan status
     */
    public static function updateLoanStatus(BookLoan $loan, string $status, ?string $notes = null): BookLoan
    {
        $oldStatus = $loan->status;
        
        return DB::transaction(function () use ($loan, $status, $notes, $oldStatus) {
            // Update loan status
            $loan->status = $status;
            if ($notes) {
                $loan->notes = $notes;
            }

            // Handle specific status transitions
            switch ($status) {
                case BookLoan::STATUS_RETURNED:
                    $loan->return_date = Carbon::now()->toDateString();
                    break;

                case BookLoan::STATUS_RETURN_CONFIRMED:
                    if (!$loan->return_date) {
                        $loan->return_date = Carbon::now()->toDateString();
                    }
                    // Mark book instance as available again
                    $loan->bookInstance->update(['status' => 'available']);
                    break;

                case BookLoan::STATUS_LOST:
                    // Mark book instance as lost/unavailable
                    $loan->bookInstance->update(['status' => 'borrowed']); // Keep as borrowed until resolved
                    break;
            }

            $loan->save();

            // Send appropriate notifications
            self::sendLoanNotification($loan, $status, $oldStatus);

            return $loan;
        });
    }

    /**
     * Mark book as received by borrower
     */
    public static function markAsReceived(BookLoan $loan, ?string $notes = null): BookLoan
    {
        return self::updateLoanStatus($loan, BookLoan::STATUS_RECEIVED, $notes);
    }

    /**
     * Mark book as being read
     */
    public static function markAsReading(BookLoan $loan, ?string $notes = null): BookLoan
    {
        return self::updateLoanStatus($loan, BookLoan::STATUS_READING, $notes);
    }

    /**
     * Mark book as returned by borrower
     */
    public static function markAsReturned(BookLoan $loan, ?string $notes = null): BookLoan
    {
        return self::updateLoanStatus($loan, BookLoan::STATUS_RETURNED, $notes);
    }

    /**
     * Confirm return by owner
     */
    public static function confirmReturn(BookLoan $loan, ?string $notes = null): BookLoan
    {
        return self::updateLoanStatus($loan, BookLoan::STATUS_RETURN_CONFIRMED, $notes);
    }

    /**
     * Deny return by owner
     */
    public static function denyReturn(BookLoan $loan, ?string $notes = null): BookLoan
    {
        return self::updateLoanStatus($loan, BookLoan::STATUS_RETURN_DENIED, $notes);
    }

    /**
     * Mark book as lost
     */
    public static function markAsLost(BookLoan $loan, ?string $notes = null): BookLoan
    {
        return self::updateLoanStatus($loan, BookLoan::STATUS_LOST, $notes);
    }

    /**
     * Mark loan as disputed
     */
    public static function markAsDisputed(BookLoan $loan, ?string $notes = null): BookLoan
    {
        return self::updateLoanStatus($loan, BookLoan::STATUS_DISPUTED, $notes);
    }

    /**
     * Get loans for a borrower
     */
    public static function getLoansForBorrower(int $borrowerId, ?string $status = null)
    {
        $query = BookLoan::with(['book', 'bookInstance', 'owner'])
            ->where('borrower_id', $borrowerId)
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    /**
     * Get loans for an owner
     */
    public static function getLoansForOwner(int $ownerId, ?string $status = null)
    {
        $query = BookLoan::with(['book', 'bookInstance', 'borrower'])
            ->where('owner_id', $ownerId)
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    /**
     * Get active loans for a user (both as borrower and owner)
     */
    public static function getActiveLoansForUser(int $userId)
    {
        return [
            'as_borrower' => BookLoan::with(['book', 'bookInstance', 'owner'])
                ->where('borrower_id', $userId)
                ->active()
                ->get(),
            'as_owner' => BookLoan::with(['book', 'bookInstance', 'borrower'])
                ->where('owner_id', $userId)
                ->active()
                ->get(),
        ];
    }

    /**
     * Get overdue loans
     */
    public static function getOverdueLoans()
    {
        return BookLoan::with(['book', 'bookInstance', 'borrower', 'owner'])
            ->overdue()
            ->get();
    }

    /**
     * Get loans needing attention (overdue, return pending confirmation, disputes)
     */
    public static function getLoansNeedingAttention(int $ownerId)
    {
        return BookLoan::with(['book', 'bookInstance', 'borrower'])
            ->where('owner_id', $ownerId)
            ->where(function ($query) {
                $query->where('status', BookLoan::STATUS_RETURNED)
                    ->orWhere('status', BookLoan::STATUS_RETURN_DENIED)
                    ->orWhere('status', BookLoan::STATUS_DISPUTED)
                    ->orWhere(function ($q) {
                        $q->active()->where('due_date', '<', Carbon::now()->toDateString());
                    });
            })
            ->get();
    }

    /**
     * Send loan status notification
     */
    private static function sendLoanNotification(BookLoan $loan, string $newStatus, ?string $oldStatus = null): void
    {
        $messages = [
            BookLoan::STATUS_DELIVERED => [
                'to_borrower' => "Your book '{$loan->book->title}' has been marked as delivered by the owner.",
                'to_owner' => null
            ],
            BookLoan::STATUS_RECEIVED => [
                'to_borrower' => null,
                'to_owner' => "The borrower has confirmed receipt of '{$loan->book->title}'."
            ],
            BookLoan::STATUS_READING => [
                'to_borrower' => null,
                'to_owner' => "The borrower has started reading '{$loan->book->title}'."
            ],
            BookLoan::STATUS_RETURNED => [
                'to_borrower' => null,
                'to_owner' => "The borrower claims to have returned '{$loan->book->title}'. Please confirm receipt."
            ],
            BookLoan::STATUS_RETURN_CONFIRMED => [
                'to_borrower' => "The owner has confirmed receipt of your returned book '{$loan->book->title}'. Thank you!",
                'to_owner' => null
            ],
            BookLoan::STATUS_RETURN_DENIED => [
                'to_borrower' => "The owner has not yet received your returned book '{$loan->book->title}'. Please contact them.",
                'to_owner' => null
            ],
            BookLoan::STATUS_LOST => [
                'to_borrower' => "The book '{$loan->book->title}' has been marked as lost. Please contact the owner.",
                'to_owner' => "The book '{$loan->book->title}' has been marked as lost."
            ],
            BookLoan::STATUS_DISPUTED => [
                'to_borrower' => "There is a dispute regarding the book '{$loan->book->title}'. Please contact the owner.",
                'to_owner' => "A dispute has been raised for the book '{$loan->book->title}'."
            ]
        ];

        if (!isset($messages[$newStatus])) {
            return;
        }

        $messageConfig = $messages[$newStatus];

        // Send notification to borrower
        if ($messageConfig['to_borrower'] && $loan->borrower) {
            $loan->borrower->notify(new BookRequestStatusNotification(
                $loan->bookRequest,
                $newStatus,
                $messageConfig['to_borrower']
            ));
        }

        // Send notification to owner
        if ($messageConfig['to_owner'] && $loan->owner) {
            $loan->owner->notify(new BookRequestStatusNotification(
                $loan->bookRequest,
                $newStatus,
                $messageConfig['to_owner']
            ));
        }
    }

    /**
     * Get loan statistics for a user
     */
    public static function getLoanStatistics(int $userId): array
    {
        $asBorrower = BookLoan::where('borrower_id', $userId);
        $asOwner = BookLoan::where('owner_id', $userId);

        return [
            'as_borrower' => [
                'total' => (clone $asBorrower)->count(),
                'active' => (clone $asBorrower)->active()->count(),
                'completed' => (clone $asBorrower)->where('status', BookLoan::STATUS_RETURN_CONFIRMED)->count(),
                'overdue' => (clone $asBorrower)->overdue()->count(),
                'with_issues' => (clone $asBorrower)->whereIn('status', [
                    BookLoan::STATUS_RETURN_DENIED,
                    BookLoan::STATUS_LOST,
                    BookLoan::STATUS_DISPUTED
                ])->count(),
            ],
            'as_owner' => [
                'total' => (clone $asOwner)->count(),
                'active' => (clone $asOwner)->active()->count(),
                'completed' => (clone $asOwner)->where('status', BookLoan::STATUS_RETURN_CONFIRMED)->count(),
                'pending_return_confirmation' => (clone $asOwner)->where('status', BookLoan::STATUS_RETURNED)->count(),
                'with_issues' => (clone $asOwner)->whereIn('status', [
                    BookLoan::STATUS_RETURN_DENIED,
                    BookLoan::STATUS_LOST,
                    BookLoan::STATUS_DISPUTED
                ])->count(),
            ]
        ];
    }

    /**
     * Extend loan due date
     */
    public static function extendLoan(BookLoan $loan, int $additionalDays, ?string $notes = null): BookLoan
    {
        if (!$loan->isActive()) {
            throw new \InvalidArgumentException('Can only extend active loans');
        }

        $loan->due_date = Carbon::parse($loan->due_date)->addDays($additionalDays)->toDateString();
        if ($notes) {
            $loan->notes = $notes;
        }
        $loan->save();

        // Notify borrower about extension
        if ($loan->borrower) {
            $message = "Your loan for '{$loan->book->title}' has been extended until {$loan->due_date}.";
            $loan->borrower->notify(new BookRequestStatusNotification(
                $loan->bookRequest,
                'extended',
                $message
            ));
        }

        return $loan;
    }
}
