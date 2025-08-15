<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($genre) {
            if (empty($genre->slug)) {
                $genre->slug = Str::slug($genre->name);
            }
        });

        static::updating(function ($genre) {
            if ($genre->isDirty('name') && empty($genre->getOriginal('slug'))) {
                $genre->slug = Str::slug($genre->name);
            }
        });
    }

    /**
     * Get books that belong to this genre
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    /**
     * Scope to get only active genres
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by name
     */
    public function scopeOrderByName($query)
    {
        return $query->orderBy('name');
    }

    /**
     * Get formatted name for display
     */
    public function getDisplayNameAttribute(): string
    {
        return ucfirst($this->name);
    }

    /**
     * Get URL-friendly slug
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
