<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBookPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'favorite_genres',
        'disliked_genres',
        'preferred_length',
        'preferred_era',
        'min_rating',
        'recommendations_count',
        'avg_feedback_score',
    ];

    protected $casts = [
        'favorite_genres' => 'array',
        'disliked_genres' => 'array',
        'min_rating' => 'decimal:2',
        'recommendations_count' => 'integer',
        'avg_feedback_score' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns these preferences
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get default preferences for a user
     */
    public static function getDefaults()
    {
        return [
            'favorite_genres' => [],
            'disliked_genres' => [],
            'preferred_length' => 'any',
            'preferred_era' => 'any',
            'min_rating' => 0,
            'recommendations_count' => 0,
            'avg_feedback_score' => 0,
        ];
    }

    /**
     * Update feedback score based on new recommendation feedback
     */
    public function updateFeedbackScore($feedbackScore)
    {
        $totalScore = ($this->avg_feedback_score * $this->recommendations_count) + $feedbackScore;
        $this->recommendations_count++;
        $this->avg_feedback_score = $totalScore / $this->recommendations_count;
        $this->save();
    }
}
