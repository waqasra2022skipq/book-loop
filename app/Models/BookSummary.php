<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'summary',
        'rating',
        'meta',
    ];

    protected $casts = [
        'rating' => 'integer',
        'meta' => 'array',
    ];

    /**
     * Boot method to handle rating updates
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($summary) {
            if (!is_null($summary->rating)) {
                $summary->book->updateRatingStats();
            }
        });

        static::updated(function ($summary) {
            if ($summary->wasChanged('rating')) {
                $summary->book->updateRatingStats();
            }
        });

        static::deleted(function ($summary) {
            if (!is_null($summary->rating)) {
                $summary->book->updateRatingStats();
            }
        });
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function writer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get star rating display
     */
    public function getStarRatingAttribute(): string
    {
        if (!$this->rating) {
            return 'No rating';
        }

        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Scope for rated summaries only
     */
    public function scopeRated($query)
    {
        return $query->whereNotNull('rating');
    }

    /**
     * Scope for specific rating
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }
}
