<?php

namespace App\Services;

use App\Models\BookRequest;
use App\Models\User;
use App\Notifications\BookRequestStatusNotification;

class BookRequestService
{
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
    public static function updateStatus(BookRequest $request, string $status)
    {
        $request->status = $status;
        $request->save();

        // Notify the requester
        $user = $request->requester;
        self::sendStatusNotification($user, $status, $request);
    }

    // Write a method to send notification based on status
    // if status is 'accepted' or 'rejected', send a notification to the requester
    // if status is 'pending', do not send a notification to the owner
    public static function sendStatusNotification($user, string $status, BookRequest $request)
    {
        if(!$user) return;
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
}
