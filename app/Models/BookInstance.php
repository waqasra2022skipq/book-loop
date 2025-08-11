<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookInstance extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'owner_id',
        'condition_notes',
        'status',
        'city',
        'address',
        'lat',
        'lng',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function requests()
    {
        return $this->hasMany(\App\Models\BookRequest::class, 'book_instance_id');
    }

    public function loans()
    {
        return $this->hasMany(BookLoan::class);
    }

    public function activeLoan()
    {
        return $this->hasOne(BookLoan::class)->active();
    }
}
