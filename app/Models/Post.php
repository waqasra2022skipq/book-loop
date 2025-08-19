<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'body',
        'book_id',
        'visibility',
        'user_id',
        'reactions_count',
        'comments_count',
        'likes_count'
    ];

    protected $casts = [
        'reactions_count' => 'integer',
        'comments_count' => 'integer',
        'likes_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get all reactions to this post
     */
    public function reactions()
    {
        return $this->morphMany(PostReaction::class, 'reactable');
    }

    /**
     * Get like reactions only
     */
    public function likes()
    {
        return $this->reactions()->where('reaction_type', 'like');
    }

    /**
     * Get all comments for this post (top-level only)
     */
    public function comments()
    {
        return $this->hasMany(PostComment::class)->whereNull('parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Get all comments including replies
     */
    public function allComments()
    {
        return $this->hasMany(PostComment::class)->orderBy('created_at', 'asc');
    }

    /**
     * Check if the post is liked by a specific user
     */
    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Get the user's reaction to this post
     */
    public function getReactionBy($user)
    {
        if (!$user) return null;
        return $this->reactions()->where('user_id', $user->id)->first();
    }

    /**
     * Increment reaction counters
     */
    public function incrementReactionsCount($reactionType = 'like')
    {
        $this->increment('reactions_count');
        if ($reactionType === 'like') {
            $this->increment('likes_count');
        }
    }

    /**
     * Decrement reaction counters
     */
    public function decrementReactionsCount($reactionType = 'like')
    {
        $this->decrement('reactions_count');
        if ($reactionType === 'like') {
            $this->decrement('likes_count');
        }
    }

    /**
     * Get formatted time ago
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
