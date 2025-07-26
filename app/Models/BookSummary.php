<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function writer()
    {
        return $this->belongsTo(User::class);
    }
}
