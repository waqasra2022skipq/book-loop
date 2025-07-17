<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'book_instance_id',
        'user_id',
        'name',
        'email',
        'address',
        'status',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function bookInstance()
    {
        return $this->belongsTo(BookInstance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
