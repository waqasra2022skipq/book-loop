<?php

namespace App\Models;

use App\Livewire\BookSummaries;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'cover',
        'genre_id',
        'slug',
        'ratings_count',
        'avg_rating',
    ];

    /**
     * Boot the model and auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($book) {
            $book->slug = $book->generateSlug($book->title);
        });

        static::updating(function ($book) {
            if ($book->isDirty('title')) {
                $book->slug = $book->generateSlug($book->title);
            }
        });
    }

    /**
     * Generate a unique slug for the book
     */
    private function generateSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function instances()
    {
        return $this->hasMany(BookInstance::class);
    }

    public function loans()
    {
        return $this->hasMany(BookLoan::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function getCoverUrlAttribute()
    {
        return $this->cover ? asset('storage/' . $this->cover) : null;
    }

    /**
     * Get average rating from cached column (fast)
     */
    public function average_rating()
    {
        return $this->avg_rating ? number_format($this->avg_rating, 2) : '0.00';
    }

    /**
     * Get total ratings from cached column (fast)
     */
    public function total_ratings()
    {
        return $this->ratings_count ?? 0;
    }

    /**
     * Update cached rating statistics
     */
    public function updateRatingStats(): void
    {
        $ratingsCollection = $this->summaries()
            ->whereNotNull('rating')
            ->pluck('rating');

        $count = $ratingsCollection->count();
        $average = $count > 0 ? $ratingsCollection->avg() : null;

        $this->update([
            'ratings_count' => $count,
            'avg_rating' => $average,
        ]);
    }

    /**
     * Get rating breakdown
     */
    public function getRatingBreakdown(): array
    {
        $breakdown = [];
        for ($i = 1; $i <= 5; $i++) {
            $breakdown[$i] = $this->summaries()
                ->where('rating', $i)
                ->count();
        }
        return $breakdown;
    }

    /**
     * Get star rating display
     */
    public function getStarRatingAttribute(): string
    {
        if (!$this->avg_rating) {
            return 'No ratings yet';
        }

        $fullStars = floor($this->avg_rating);
        $halfStar = ($this->avg_rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

        return str_repeat('★', $fullStars) .
            ($halfStar ? '½' : '') .
            str_repeat('☆', $emptyStars) .
            ' (' . $this->average_rating() . ')';
    }

    public function summaries()
    {
        return $this->hasMany(BookSummary::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
