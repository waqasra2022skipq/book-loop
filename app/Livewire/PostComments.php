<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\PostComment;
use App\Services\PostCommentService;
use Livewire\Component;
use Livewire\WithPagination;

class PostComments extends Component
{
    use WithPagination;

    public Post $post;
    public $newComment = '';
    public $replyingTo = null;
    public $replyContent = '';
    public $editingComment = null;
    public $editContent = '';
    public $showComments = true;

    protected $postCommentService;

    protected $rules = [
        'newComment' => 'required|string|max:1000|min:1',
        'replyContent' => 'required|string|max:1000|min:1',
        'editContent' => 'required|string|max:1000|min:1',
    ];

    protected $messages = [
        'newComment.required' => 'Please write a comment before posting.',
        'newComment.max' => 'Comment is too long. Maximum 1000 characters allowed.',
        'replyContent.required' => 'Please write a reply before posting.',
        'replyContent.max' => 'Reply is too long. Maximum 1000 characters allowed.',
        'editContent.required' => 'Comment cannot be empty.',
        'editContent.max' => 'Comment is too long. Maximum 1000 characters allowed.',
    ];

    public function boot(PostCommentService $postCommentService)
    {
        $this->postCommentService = $postCommentService;
    }

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function addComment()
    {
        if (!auth()->check()) {
            return $this->redirect(route('login'));
        }

        $this->validate(['newComment' => $this->rules['newComment']]);

        try {
            $this->postCommentService->createComment(
                $this->post,
                auth()->user(),
                $this->newComment
            );

            $this->newComment = '';
            $this->post = $this->post->fresh(['user']); // Refresh post with updated counts
            $this->resetPage();

            session()->flash('success', 'Comment added successfully!');

            // Dispatch event for real-time updates
            $this->dispatch('comment-added', ['postId' => $this->post->id]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add comment. Please try again.');
        }
    }

    public function addReply()
    {
        if (!auth()->check()) {
            return $this->redirect(route('login'));
        }

        $this->validate(['replyContent' => $this->rules['replyContent']]);

        try {
            $this->postCommentService->createComment(
                $this->post,
                auth()->user(),
                $this->replyContent,
                $this->replyingTo
            );

            $this->replyContent = '';
            $this->replyingTo = null;
            $this->post = $this->post->fresh(['user']);

            session()->flash('success', 'Reply added successfully!');

            // Dispatch event for real-time updates
            $this->dispatch('reply-added', ['postId' => $this->post->id]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add reply. Please try again.');
        }
    }

    public function startReply($commentId)
    {
        if (!auth()->check()) {
            return $this->redirect(route('login'));
        }

        $this->replyingTo = $commentId;
        $this->replyContent = '';
    }

    public function cancelReply()
    {
        $this->replyingTo = null;
        $this->replyContent = '';
        $this->resetValidation(['replyContent']);
    }

    public function startEdit($commentId, $currentContent)
    {
        $this->editingComment = $commentId;
        $this->editContent = $currentContent;
    }

    public function saveEdit()
    {
        $this->validate(['editContent' => $this->rules['editContent']]);

        $comment = PostComment::find($this->editingComment);

        if (!$comment || !$this->postCommentService->canUserEditComment(auth()->user(), $comment)) {
            session()->flash('error', 'You are not authorized to edit this comment.');
            return;
        }

        try {
            $this->postCommentService->updateComment($comment, $this->editContent);

            $this->editingComment = null;
            $this->editContent = '';

            session()->flash('success', 'Comment updated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update comment. Please try again.');
        }
    }

    public function cancelEdit()
    {
        $this->editingComment = null;
        $this->editContent = '';
        $this->resetValidation(['editContent']);
    }

    public function deleteComment($commentId)
    {
        $comment = PostComment::find($commentId);

        if (!$comment || !$this->postCommentService->canUserDeleteComment(auth()->user(), $comment)) {
            session()->flash('error', 'You are not authorized to delete this comment.');
            return;
        }

        try {
            $this->postCommentService->deleteComment($comment);
            $this->post = $this->post->fresh(['user']);

            session()->flash('success', 'Comment deleted successfully!');

            // Dispatch event for real-time updates
            $this->dispatch('comment-deleted', ['postId' => $this->post->id]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete comment. Please try again.');
        }
    }

    public function toggleComments()
    {
        $this->showComments = !$this->showComments;
    }

    public function render()
    {
        $comments = $this->showComments
            ? $this->postCommentService->getCommentsWithReplies($this->post, $this->getPage())
            : collect();

        return view('livewire.post-comments', [
            'comments' => $comments
        ]);
    }
}
