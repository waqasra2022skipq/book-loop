<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserReview;
use App\Models\BookRequest;
use App\Models\BookLoan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserReviewService
{
    /**
     * Create a new user review
     */
    public function createReview(array $data): UserReview
    {
        return DB::transaction(function () use ($data) {
            // Validate that users are different
            if ($data['reviewed_user_id'] == $data['reviewer_user_id']) {
                throw new \InvalidArgumentException('Users cannot review themselves.');
            }

            // Check if review already exists for this transaction
            if (isset($data['transaction_type']) && isset($data['transaction_id'])) {
                $existingReview = UserReview::where('reviewer_user_id', $data['reviewer_user_id'])
                    ->where('transaction_type', $data['transaction_type'])
                    ->where('transaction_id', $data['transaction_id'])
                    ->first();

                if ($existingReview) {
                    throw new \InvalidArgumentException('Review already exists for this transaction.');
                }
            }

            // Create the review
            $review = UserReview::create([
                'reviewed_user_id' => $data['reviewed_user_id'],
                'reviewer_user_id' => $data['reviewer_user_id'],
                'rating' => $data['rating'],
                'review' => $data['review'] ?? null,
                'transaction_type' => $data['transaction_type'] ?? null,
                'transaction_id' => $data['transaction_id'] ?? null,
                'is_public' => $data['is_public'] ?? true,
                'reviewed_at' => now(),
            ]);

            // Update the reviewed user's cached statistics
            $this->updateUserReviewStats($data['reviewed_user_id']);

            Log::info('User review created', [
                'review_id' => $review->id,
                'reviewed_user_id' => $data['reviewed_user_id'],
                'reviewer_user_id' => $data['reviewer_user_id'],
                'rating' => $data['rating']
            ]);

            return $review;
        });
    }

    /**
     * Update a user review
     */
    public function updateReview(UserReview $review, array $data): UserReview
    {
        return DB::transaction(function () use ($review, $data) {
            $review->update([
                'rating' => $data['rating'] ?? $review->rating,
                'review' => $data['review'] ?? $review->review,
                'is_public' => $data['is_public'] ?? $review->is_public,
            ]);

            // Update the reviewed user's cached statistics
            $this->updateUserReviewStats($review->reviewed_user_id);

            return $review->fresh();
        });
    }

    /**
     * Delete a user review
     */
    public function deleteReview(UserReview $review): bool
    {
        return DB::transaction(function () use ($review) {
            $reviewedUserId = $review->reviewed_user_id;
            $deleted = $review->delete();

            if ($deleted) {
                // Update the reviewed user's cached statistics
                $this->updateUserReviewStats($reviewedUserId);
            }

            return $deleted;
        });
    }

    /**
     * Update cached review statistics for a user
     */
    public function updateUserReviewStats(int $userId): void
    {
        $user = User::find($userId);
        if ($user) {
            $user->updateReviewStats();
        }
    }

    /**
     * Get reviews for a user with pagination
     */
    public function getUserReviews(int $userId, int $perPage = 10, bool $publicOnly = true)
    {
        $query = UserReview::with(['reviewer'])
            ->forUser($userId)
            ->latest('reviewed_at');

        if ($publicOnly) {
            $query->public();
        }

        return $query->paginate($perPage);
    }

    /**
     * Check if a user can review another user for a specific transaction
     */
    public function canReviewUser(int $reviewerId, int $reviewedUserId, ?string $transactionType = null, ?int $transactionId = null): bool
    {
        // Cannot review yourself
        if ($reviewerId == $reviewedUserId) {
            return false;
        }

        // If transaction is specified, check if review already exists
        if ($transactionType && $transactionId) {
            $existingReview = UserReview::where('reviewer_user_id', $reviewerId)
                ->where('transaction_type', $transactionType)
                ->where('transaction_id', $transactionId)
                ->exists();

            return !$existingReview;
        }

        return true;
    }

    /**
     * Create review from book request completion
     */
    public function createReviewFromBookRequest(BookRequest $bookRequest, int $reviewerId, array $reviewData): UserReview
    {
        // Determine who is being reviewed
        $reviewedUserId = ($reviewerId == $bookRequest->user_id)
            ? $bookRequest->bookInstance->owner_id  // Requester reviewing owner
            : $bookRequest->user_id;                // Owner reviewing requester

        return $this->createReview([
            'reviewed_user_id' => $reviewedUserId,
            'reviewer_user_id' => $reviewerId,
            'rating' => $reviewData['rating'],
            'review' => $reviewData['review'] ?? null,
            'transaction_type' => 'book_request',
            'transaction_id' => $bookRequest->id,
            'is_public' => $reviewData['is_public'] ?? true,
        ]);
    }

    /**
     * Create review from book loan completion
     */
    public function createReviewFromBookLoan(BookLoan $bookLoan, int $reviewerId, array $reviewData): UserReview
    {
        // Determine who is being reviewed
        $reviewedUserId = ($reviewerId == $bookLoan->borrower_id)
            ? $bookLoan->owner_id    // Borrower reviewing owner
            : $bookLoan->borrower_id; // Owner reviewing borrower

        return $this->createReview([
            'reviewed_user_id' => $reviewedUserId,
            'reviewer_user_id' => $reviewerId,
            'rating' => $reviewData['rating'],
            'review' => $reviewData['review'] ?? null,
            'transaction_type' => 'book_loan',
            'transaction_id' => $bookLoan->id,
            'is_public' => $reviewData['is_public'] ?? true,
        ]);
    }

    /**
     * Get review statistics for a user
     */
    public function getUserReviewStats(int $userId): array
    {
        $user = User::find($userId);

        if (!$user) {
            return [
                'total_reviews' => 0,
                'average_rating' => null,
                'rating_breakdown' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0],
            ];
        }

        $reviews = $user->receivedReviews()->public();
        $ratingBreakdown = [];

        for ($i = 1; $i <= 5; $i++) {
            $ratingBreakdown[$i] = $reviews->where('rating', $i)->count();
        }

        return [
            'total_reviews' => $user->getReviewsCount(),
            'average_rating' => $user->getAverageRating(),
            'rating_breakdown' => $ratingBreakdown,
        ];
    }

    /**
     * Get top-rated users
     */
    public function getTopRatedUsers(int $limit = 10)
    {
        return User::whereNotNull('avg_rating')
            ->where('reviews_count', '>=', 3) // Minimum reviews to be considered
            ->orderBy('avg_rating', 'desc')
            ->orderBy('reviews_count', 'desc')
            ->limit($limit)
            ->get();
    }
}
