<?php

namespace App\Models;

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
}
