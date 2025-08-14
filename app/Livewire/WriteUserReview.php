<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UserReview;
use App\Services\UserReviewService;
use Illuminate\Support\Facades\Auth;

class WriteUserReview extends Component
{
    public $userId;
    public $user;
    public $transactionType;
    public $transactionId;

    // Form fields
    public $rating = 5;
    public $review = '';
    public $isPublic = true;

    // State
    public $isSubmitting = false;
    public $showSuccess = false;
    public $existingReview = null;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'review' => 'nullable|string|max:1000',
        'isPublic' => 'boolean',
    ];

    protected $messages = [
        'rating.required' => 'Please select a rating.',
        'rating.min' => 'Rating must be between 1 and 5.',
        'rating.max' => 'Rating must be between 1 and 5.',
        'review.max' => 'Review cannot exceed 1000 characters.',
    ];

    public function mount($userId, $transactionType = null, $transactionId = null)
    {
        $this->userId = $userId;
        $this->user = User::findOrFail($userId);
        $this->transactionType = $transactionType;
        $this->transactionId = $transactionId;

        // Check if review already exists
        if ($this->transactionType && $this->transactionId) {
            $this->existingReview = UserReview::where('reviewer_user_id', Auth::id())
                ->where('transaction_type', $this->transactionType)
                ->where('transaction_id', $this->transactionId)
                ->first();

            if ($this->existingReview) {
                $this->rating = $this->existingReview->rating;
                $this->review = $this->existingReview->review ?? '';
                $this->isPublic = $this->existingReview->is_public;
            }
        }
    }

    public function submitReview()
    {
        // Check if user is trying to review themselves
        if (Auth::id() == $this->userId) {
            $this->addError('rating', 'You cannot review yourself.');
            return;
        }

        $this->isSubmitting = true;

        try {
            $this->validate();

            $reviewService = app(UserReviewService::class);

            $data = [
                'reviewed_user_id' => $this->userId,
                'reviewer_user_id' => Auth::id(),
                'rating' => $this->rating,
                'review' => $this->review ?: null,
                'transaction_type' => $this->transactionType,
                'transaction_id' => $this->transactionId,
                'is_public' => $this->isPublic,
            ];

            if ($this->existingReview) {
                $reviewService->updateReview($this->existingReview, $data);
            } else {
                $reviewService->createReview($data);
            }

            $this->showSuccess = true;
        } catch (\InvalidArgumentException $e) {
            $this->addError('rating', $e->getMessage());
        } catch (\Exception $e) {
            $this->addError('rating', 'There was an error submitting your review. Please try again.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function closeSuccess()
    {
        $this->showSuccess = false;
        return redirect()->back();
    }

    public function render()
    {
        return view('livewire.write-user-review');
    }
}
