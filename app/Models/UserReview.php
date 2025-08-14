<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewed_user_id',
        'reviewer_user_id',
        'rating',
        'review',
        'transaction_type',
        'transaction_id',
        'is_public',
        'reviewed_at',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'reviewed_at' => 'datetime',
        'rating' => 'integer',
    ];

    /**
     * Get the user being reviewed
     */
    public function reviewedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_user_id');
    }

    /**
     * Get the user who wrote the review
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_user_id');
    }

    /**
     * Get the related transaction (BookRequest, BookLoan, etc.)
     */
    public function transaction()
    {
        return match ($this->transaction_type) {
            'book_request' => $this->belongsTo(BookRequest::class, 'transaction_id'),
            'book_loan' => $this->belongsTo(BookLoan::class, 'transaction_id'),
            default => null,
        };
    }

    /**
     * Scope to get public reviews only
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope to get reviews for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('reviewed_user_id', $userId);
    }

    /**
     * Scope to get reviews by a specific user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('reviewer_user_id', $userId);
    }

    /**
     * Scope to get reviews by rating
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope to get reviews by transaction type
     */
    public function scopeByTransactionType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    /**
     * Get star rating as string (for display)
     */
    public function getStarRatingAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Check if this review is for a specific transaction
     */
    public function isForTransaction($type, $id): bool
    {
        return $this->transaction_type === $type && $this->transaction_id == $id;
    }

    /**
     * Get review summary (truncated review text)
     */
    public function getSummaryAttribute(): string
    {
        if (!$this->review) {
            return 'No written review provided.';
        }

        return strlen($this->review) > 100
            ? substr($this->review, 0, 100) . '...'
            : $this->review;
    }
}
