<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostReaction extends Model
{
    use HasFactory;

    const REACTION_LIKE = 'like';
    const REACTION_LOVE = 'love';
    const REACTION_LAUGH = 'laugh';
    const REACTION_ANGRY = 'angry';
    const REACTION_SAD = 'sad';
    const REACTION_WOW = 'wow';

    protected $fillable = [
        'user_id',
        'reactable_type',
        'reactable_id',
        'reaction_type'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who made the reaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that the reaction belongs to (polymorphic)
     */
    public function reactable()
    {
        return $this->morphTo();
    }

    /**
     * Scope to filter by reaction type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('reaction_type', $type);
    }

    /**
     * Scope to filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get all available reaction types
     */
    public static function getTypes()
    {
        return [
            self::REACTION_LIKE,
            self::REACTION_LOVE,
            self::REACTION_LAUGH,
            self::REACTION_ANGRY,
            self::REACTION_SAD,
            self::REACTION_WOW,
        ];
    }

    /**
     * Get reaction type display name
     */
    public function getDisplayNameAttribute()
    {
        return match ($this->reaction_type) {
            self::REACTION_LIKE => 'Like',
            self::REACTION_LOVE => 'Love',
            self::REACTION_LAUGH => 'Haha',
            self::REACTION_ANGRY => 'Angry',
            self::REACTION_SAD => 'Sad',
            self::REACTION_WOW => 'Wow',
            default => 'Like'
        };
    }

    /**
     * Get reaction emoji
     */
    public function getEmojiAttribute()
    {
        return match ($this->reaction_type) {
            self::REACTION_LIKE => '👍',
            self::REACTION_LOVE => '❤️',
            self::REACTION_LAUGH => '😂',
            self::REACTION_ANGRY => '😡',
            self::REACTION_SAD => '😢',
            self::REACTION_WOW => '😮',
            default => '👍'
        };
    }
}
