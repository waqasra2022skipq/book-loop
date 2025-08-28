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

    public function average_rating()
    {
        // Return average rating rounded to 2 decimal points
        return number_format($this->hasMany(BookSummary::class)->avg('rating'), 2);
    }

    public function total_ratings()
    {
        return $this->hasMany(BookSummary::class)->count();
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
