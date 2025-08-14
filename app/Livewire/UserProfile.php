<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Services\UserReviewService;
use Illuminate\Support\Facades\Auth;

class UserProfile extends Component
{
    public $userId;
    public $user;

    public function mount($userId)
    {
        $this->userId = $userId;
        $this->user = User::findOrFail($userId);
    }

    public function render()
    {
        $reviewService = app(UserReviewService::class);
        $recentReviews = $reviewService->getUserReviews($this->userId, 5, true);
        $stats = $reviewService->getUserReviewStats($this->userId);

        return view('livewire.user-profile', [
            'recentReviews' => $recentReviews,
            'stats' => $stats,
        ]);
    }
}
