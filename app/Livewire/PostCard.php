<?php

namespace App\Livewire;

use App\Models\Post;
use App\Services\PostCommentService;
use Livewire\Component;

class PostCard extends Component
{
    public Post $post;
    public $showAddCommentModal = false;
    public $showCommentsModal = false;
    public $newComment = '';

    protected $postCommentService;
    protected $listeners = ['comment-added' => 'refreshPost', 'comment-deleted' => 'refreshPost'];

    protected $rules = [
        'newComment' => 'required|string|max:1000|min:1',
    ];

    public function boot(PostCommentService $postCommentService)
    {
        $this->postCommentService = $postCommentService;
    }

    public function refreshPost()
    {
        $this->post = $this->post->fresh();
    }

    public function mount(Post $post)
    {
        $this->post = $post->load([
            'user',
            'reactions.user',
            'comments.user',
            'comments.replies.user'
        ]);
    }

    public function openAddCommentModal()
    {
        $this->showAddCommentModal = true;
    }

    public function closeAddCommentModal()
    {
        $this->showAddCommentModal = false;
        $this->newComment = '';
        $this->resetValidation();
    }

    public function openCommentsModal()
    {
        $this->showCommentsModal = true;
    }

    public function closeCommentsModal()
    {
        $this->showCommentsModal = false;
    }

    public function addComment()
    {
        if (!auth()->check()) {
            return $this->redirect(route('login'));
        }

        $this->validate();

        try {
            $this->postCommentService->createComment(
                $this->post,
                auth()->user(),
                $this->newComment
            );

            // Refresh post with updated counts
            $this->post = $this->post->fresh();
            $this->post->load(['comments.user', 'comments.replies.user']);

            $this->closeAddCommentModal();
            $this->dispatch('comment-added');

            session()->flash('success', 'Comment added successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add comment. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.post-card');
    }
}
