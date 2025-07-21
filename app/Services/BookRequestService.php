<?php

namespace App\Services;

use App\Models\BookRequest;
use Illuminate\Support\Facades\Auth;

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
