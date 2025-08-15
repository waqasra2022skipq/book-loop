<?php

namespace App\Models;

use App\Livewire\BookSummaries;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'cover',
        'genre_id',
    ];

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
}
