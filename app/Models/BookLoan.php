<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class BookLoan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'book_request_id',
        'book_id',
        'book_instance_id',
        'borrower_id',
        'owner_id',
        'delivered_date',
        'due_date',
        'return_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'delivered_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    /**
     * Loan statuses
     */
    const STATUS_DELIVERED = 'delivered';
    const STATUS_RECEIVED = 'received';
    const STATUS_READING = 'reading';
    const STATUS_RETURNED = 'returned';
    const STATUS_RETURN_CONFIRMED = 'return_confirmed';
    const STATUS_RETURN_DENIED = 'return_denied';
    const STATUS_LOST = 'lost';
    const STATUS_DISPUTED = 'disputed';

    /**
     * Get all possible statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_DELIVERED,
            self::STATUS_RECEIVED,
            self::STATUS_READING,
            self::STATUS_RETURNED,
            self::STATUS_RETURN_CONFIRMED,
            self::STATUS_RETURN_DENIED,
            self::STATUS_LOST,
            self::STATUS_DISPUTED,
        ];
    }

    /**
     * Relationships
     */
    public function bookRequest()
    {
        return $this->belongsTo(BookRequest::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function bookInstance()
    {
        return $this->belongsTo(BookInstance::class);
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            self::STATUS_DELIVERED,
            self::STATUS_RECEIVED,
            self::STATUS_READING,
        ]);
    }

    public function scopeOverdue($query)
    {
        return $query->active()
            ->where('due_date', '<', Carbon::now()->toDateString());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForBorrower($query, $borrowerId)
    {
        return $query->where('borrower_id', $borrowerId);
    }

    public function scopeForOwner($query, $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }

    /**
     * Accessors & Mutators
     */
    public function getIsOverdueAttribute()
    {
        return $this->isActive() && $this->due_date < Carbon::now()->toDateString();
    }

    public function getIsActiveAttribute()
    {
        return in_array($this->status, [
            self::STATUS_DELIVERED,
            self::STATUS_RECEIVED,
            self::STATUS_READING,
        ]);
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->is_overdue) {
            return 0;
        }
        
        return Carbon::parse($this->due_date)->diffInDays(Carbon::now());
    }

    public function getDaysUntilDueAttribute()
    {
        if (!$this->is_active) {
            return null;
        }
        
        $daysUntilDue = Carbon::now()->diffInDays(Carbon::parse($this->due_date), false);
        return $daysUntilDue >= 0 ? $daysUntilDue : 0;
    }

    /**
     * Helper methods
     */
    public function isActive()
    {
        return in_array($this->status, [
            self::STATUS_DELIVERED,
            self::STATUS_RECEIVED,
            self::STATUS_READING,
        ]);
    }

    public function isReturned()
    {
        return in_array($this->status, [
            self::STATUS_RETURNED,
            self::STATUS_RETURN_CONFIRMED,
        ]);
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_RETURN_CONFIRMED;
    }

    public function hasIssues()
    {
        return in_array($this->status, [
            self::STATUS_RETURN_DENIED,
            self::STATUS_LOST,
            self::STATUS_DISPUTED,
        ]);
    }

    /**
     * Status transition methods
     */
    public function markAsReceived($notes = null)
    {
        $this->status = self::STATUS_RECEIVED;
        if ($notes) {
            $this->notes = $notes;
        }
        $this->save();
    }

    public function markAsReading($notes = null)
    {
        $this->status = self::STATUS_READING;
        if ($notes) {
            $this->notes = $notes;
        }
        $this->save();
    }

    public function markAsReturned($notes = null)
    {
        $this->status = self::STATUS_RETURNED;
        $this->return_date = Carbon::now()->toDateString();
        if ($notes) {
            $this->notes = $notes;
        }
        $this->save();
    }

    public function confirmReturn($notes = null)
    {
        $this->status = self::STATUS_RETURN_CONFIRMED;
        if (!$this->return_date) {
            $this->return_date = Carbon::now()->toDateString();
        }
        if ($notes) {
            $this->notes = $notes;
        }
        $this->save();
    }

    public function denyReturn($notes = null)
    {
        $this->status = self::STATUS_RETURN_DENIED;
        if ($notes) {
            $this->notes = $notes;
        }
        $this->save();
    }

    public function markAsLost($notes = null)
    {
        $this->status = self::STATUS_LOST;
        if ($notes) {
            $this->notes = $notes;
        }
        $this->save();
    }

    public function markAsDisputed($notes = null)
    {
        $this->status = self::STATUS_DISPUTED;
        if ($notes) {
            $this->notes = $notes;
        }
        $this->save();
    }
}
