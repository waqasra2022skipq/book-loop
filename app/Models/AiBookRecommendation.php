<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiBookRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'user_id',
        'title',
        'author',
        'genre',
        'description',
        'ai_reason',
        'publication_year',
        'pages',
        'confidence_score',
        'cover_url',
        'rating',
        'user_feedback',
        'feedback_at',
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'pages' => 'integer',
        'confidence_score' => 'decimal:2',
        'rating' => 'decimal:2',
        'feedback_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the recommendation request that owns this recommendation
     */
    public function request()
    {
        return $this->belongsTo(AiRecommendationRequest::class, 'request_id');
    }

    /**
     * Get the user that owns this recommendation
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if user provided feedback
     */
    public function hasFeedback()
    {
        return !is_null($this->user_feedback);
    }

    /**
     * Check if user saved this recommendation
     */
    public function isSaved()
    {
        return $this->user_feedback === 'saved';
    }

    /**
     * Check if user marked as not interested
     */
    public function isNotInterested()
    {
        return $this->user_feedback === 'not_interested';
    }

    /**
     * Check if user already read this book
     */
    public function isAlreadyRead()
    {
        return $this->user_feedback === 'already_read';
    }
}
