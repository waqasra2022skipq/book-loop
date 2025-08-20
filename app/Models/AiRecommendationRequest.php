<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiRecommendationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recent_books',
        'user_prompt',
        'preferences',
        'generated_prompt',
        'ai_response',
        'total_tokens_used',
        'response_time',
        'status',
        'error_message',
    ];

    protected $casts = [
        'recent_books' => 'array',
        'preferences' => 'array',
        'ai_response' => 'array',
        'total_tokens_used' => 'integer',
        'response_time' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the recommendation request
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the recommendations for this request
     */
    public function recommendations()
    {
        return $this->hasMany(AiBookRecommendation::class, 'request_id');
    }

    /**
     * Check if the request was successful
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the request failed
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Check if the request is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }
}
