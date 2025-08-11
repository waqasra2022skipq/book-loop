<?php

namespace App\Services;

use App\Models\BookRequest;
use App\Models\User;
use App\Notifications\BookRequestStatusNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BookRequestService
{
    /**
     * Create a book request, optionally creating a user profile for guests
     */
    public function createBookRequest(array $data, ?int $bookInstanceId = null, ?int $bookId = null): array
    {
        return DB::transaction(function () use ($data, $bookInstanceId, $bookId) {
            $user = null;
            $isNewUser = false;

            // If user is not authenticated, create or find user profile
            if (!auth()->check()) {
                $userData = $this->createOrFindUser($data);
                $user = $userData['user'];
                $isNewUser = $userData['isNewUser'];
            }

            // Create the book request
            $bookRequest = BookRequest::create([
                'user_id' => $user?->id ?? auth()->id(),
                'book_id' => $bookId,
                'book_instance_id' => $bookInstanceId,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'],
                'message' => $data['message'] ?? null,
                'status' => 'pending',
            ]);

            return [
                'bookRequest' => $bookRequest,
                'user' => $user,
                'isNewUser' => $isNewUser,
                'isAuthenticated' => auth()->check(),
            ];
        });
    }

    /**
     * Create or find existing user based on email
     */
    public function createOrFindUser(array $data): array
    {
        $existingUser = User::where('email', $data['email'])->first();

        if ($existingUser) {
            // Update existing user with new information if provided
            $existingUser->update([
                'name' => $data['name'] ?? $existingUser->name,
            ]);

            return [
                'user' => $existingUser,
                'isNewUser' => false,
            ];
        }

        // Create new user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'password' => Hash::make(Str::random(12)), // Random password
            'email_verified_at' => now(), // Auto-verify for guest interactions
        ]);

        return [
            'user' => $user,
            'isNewUser' => true,
        ];
    }

    /**
     * Get success message based on request context
     */
    public function getSuccessMessage(array $result): string
    {
        if ($result['isAuthenticated']) {
            return 'Your book request has been submitted successfully!';
        }

        if ($result['isNewUser']) {
            return 'Your book request has been submitted and a profile has been created for you! You can log in using your email.';
        }

        return 'Your book request has been submitted successfully! We found your existing profile.';
    }
    /**
     * Check if user already has a pending request for this book
     */
    public function hasExistingRequest(?int $bookId, ?string $email = null): ?BookRequest
    {
        $query = BookRequest::where('book_instance_id', $bookId)
            ->whereIn('status', ['pending', 'accepted']);

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } elseif ($email) {
            $query->where('email', $email);
        }

        return $query->first();
    }

    /**
     * Get all requests for books owned by the current user.
     */
    public static function getReceivedRequestsForUser($userId)
    {
        return BookRequest::with(['book', 'bookInstance', 'requester'])
            ->whereHas('bookInstance', function ($q) use ($userId) {
                $q->where('owner_id', $userId);
            })
            ->orderByRaw("FIELD(status, 'pending') DESC")
            ->latest()
            ->get();
    }

    /**
     * Update the status of a book request.
     */
    public static function updateStatus(BookRequest $request, string $status, ?int $loanDurationDays = null)
    {
        $request->status = $status;
        $request->save();

        // If request is accepted, create a loan
        if ($status === 'accepted') {
            $loan = BookLoanService::createLoanFromRequest($request, $loanDurationDays);
        }

        // Notify the requester
        $user = $request->requester;
        self::sendStatusNotification($user, $status, $request);

        return $request;
    }

    // Write a method to send notification based on status
    // if status is 'accepted' or 'rejected', send a notification to the requester
    // if status is 'pending', do not send a notification to the owner
    public static function sendStatusNotification($user, string $status, BookRequest $request)
    {
        if (!$user) return;
        if ($status === 'accepted' || $status === 'rejected') {
            $user->notify(new BookRequestStatusNotification($request, $status, 'Your book request has been ' . $status));
        } else if ($status === 'pending') {
            // Notify the book owner
            $user->notify(new BookRequestStatusNotification($request, 'received', 'You have received a new book request.'));
        }
    }

    /**
     * Get requests by status for books owned by the current user.
     */
    public static function getRequestsByStatus($userId, $status)
    {
        return BookRequest::with(['book', 'bookInstance', 'requester'])
            ->whereHas('bookInstance', function ($q) use ($userId) {
                $q->where('owner_id', $userId);
            })
            ->where('status', $status)
            ->latest()
            ->get();
    }

    /**
     * Reject all the pending request for a bookinstance
     */

    public static function rejectPendingRequests($bookInstanceId)
    {
        $requests = BookRequest::where('book_instance_id', $bookInstanceId)
            ->where('status', 'pending')
            ->get();

        foreach ($requests as $request) {
            $request->status = 'rejected';
            $request->save();
            self::sendStatusNotification($request->requester, 'rejected', $request);
        }
    }
}
