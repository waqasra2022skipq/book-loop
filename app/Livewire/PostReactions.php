<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\PostComment;
use App\Services\PostReactionService;
use Livewire\Component;

class PostReactions extends Component
{
    public $reactable;
    public $reactableType;
    public $likesCount = 0;
    public $userHasLiked = false;
    public $showReactionUsers = false;
    public $reactionUsers = [];

    protected $postReactionService;

    public function boot(PostReactionService $postReactionService)
    {
        $this->postReactionService = $postReactionService;
    }

    public function mount($reactable)
    {
        $this->reactable = $reactable;
        $this->reactableType = get_class($reactable);
        $this->loadReactionData();
    }

    public function toggleLike()
    {
        if (!auth()->check()) {
            return $this->redirect(route('login'));
        }

        $result = $this->postReactionService->toggleReaction(
            $this->reactable,
            auth()->user(),
            'like'
        );

        $this->loadReactionData();

        // Dispatch browser event for real-time updates
        $this->dispatch('reaction-updated', [
            'reactableType' => $this->reactableType,
            'reactableId' => $this->reactable->id,
            'action' => $result['action'],
            'likesCount' => $this->likesCount,
            'userHasLiked' => $this->userHasLiked
        ]);

        // Show success message
        if ($result['action'] === 'added') {
            session()->flash('success', 'Liked!');
        } else {
            session()->flash('success', 'Like removed!');
        }
    }

    public function showUsers()
    {
        $this->showReactionUsers = !$this->showReactionUsers;

        if ($this->showReactionUsers) {
            $this->reactionUsers = $this->postReactionService->getReactionUsers(
                $this->reactable,
                'like',
                10
            );
        }
    }

    public function loadReactionData()
    {
        // Get likes count based on reactable type
        if ($this->reactable instanceof Post) {
            $this->likesCount = $this->reactable->likes_count;
        } else {
            $this->likesCount = $this->reactable->likes()->count();
        }

        // Check if current user has liked
        $this->userHasLiked = auth()->check()
            ? $this->reactable->isLikedBy(auth()->user())
            : false;
    }

    public function render()
    {
        return view('livewire.post-reactions');
    }
}
