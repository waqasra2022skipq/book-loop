<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookInstance extends Model
{
    protected $fillable = [
        'book_id',
        'owner_id',
        'condition_notes',
        'status',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }   
}
