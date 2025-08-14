<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Services\UserReviewService;

class UserReviews extends Component
{
    use WithPagination;

    public $userId;
    public $user;
    public $showOnlyPublic = true;

    public function mount($userId)
    {
        $this->userId = $userId;
        $this->user = User::findOrFail($userId);
    }

    public function render()
    {
        $reviewService = app(UserReviewService::class);

        $reviews = $reviewService->getUserReviews(
            $this->userId,
            10,
            $this->showOnlyPublic
        );

        $stats = $reviewService->getUserReviewStats($this->userId);

        return view('livewire.user-reviews', [
            'reviews' => $reviews,
            'stats' => $stats,
        ]);
    }
}
