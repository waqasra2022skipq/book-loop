<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'city',
        'state',
        'postal_code',
        'lat',
        'lng',
        'phone',
        'reviews_count',
        'avg_rating',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Loan relationships
     */
    public function borrowedLoans()
    {
        return $this->hasMany(BookLoan::class, 'borrower_id');
    }

    public function ownedLoans()
    {
        return $this->hasMany(BookLoan::class, 'owner_id');
    }

    public function activeBorrowedLoans()
    {
        return $this->borrowedLoans()->active();
    }

    public function activeOwnedLoans()
    {
        return $this->ownedLoans()->active();
    }

    /**
     * Review relationships
     */
    public function receivedReviews()
    {
        return $this->hasMany(UserReview::class, 'reviewed_user_id');
    }

    public function givenReviews()
    {
        return $this->hasMany(UserReview::class, 'reviewer_user_id');
    }

    public function publicReceivedReviews()
    {
        return $this->receivedReviews()->public();
    }

    /**
     * Get average rating (from cached column)
     */
    public function getAverageRating(): ?float
    {
        return $this->avg_rating;
    }

    /**
     * Get reviews count (from cached column)
     */
    public function getReviewsCount(): int
    {
        return $this->reviews_count ?? 0;
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
            ' (' . number_format($this->avg_rating, 1) . ')';
    }

    /**
     * Update cached review statistics
     */
    public function updateReviewStats(): void
    {
        $reviews = $this->receivedReviews()->public();

        $this->update([
            'reviews_count' => $reviews->count(),
            'avg_rating' => $reviews->avg('rating'),
        ]);
    }
}
