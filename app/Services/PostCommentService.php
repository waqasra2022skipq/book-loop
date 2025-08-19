<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PostCommentService
{
    /**
     * Create a new comment on a post
     */
    public function createComment(Post $post, User $user, string $content, ?int $parentId = null)
    {
        return DB::transaction(function () use ($post, $user, $content, $parentId) {
            // Validate parent comment if provided
            if ($parentId) {
                $parentComment = PostComment::where('id', $parentId)
                    ->where('post_id', $post->id)
                    ->first();

                if (!$parentComment) {
                    throw new \InvalidArgumentException('Parent comment not found or does not belong to this post.');
                }
            }

            $comment = PostComment::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
                'content' => trim($content),
                'parent_id' => $parentId
            ]);

            // Only increment post comments count for top-level comments (not replies)
            if (!$parentId) {
                $post->increment('comments_count');
            }

            return $comment->load('user', 'replies');
        });
    }

    /**
     * Update an existing comment
     */
    public function updateComment(PostComment $comment, string $content)
    {
        $comment->update([
            'content' => trim($content),
            'is_edited' => true,
            'edited_at' => now()
        ]);

        return $comment->fresh(['user', 'replies']);
    }

    /**
     * Delete a comment (soft delete)
     */
    public function deleteComment(PostComment $comment)
    {
        return DB::transaction(function () use ($comment) {
            $post = $comment->post;
            $isTopLevel = is_null($comment->parent_id);

            // Soft delete the comment (and its replies via cascade)
            $comment->delete();

            // Only decrement post comments count for top-level comments
            if ($isTopLevel) {
                $post->decrement('comments_count');
            }

            return true;
        });
    }

    /**
     * Get comments for a post with pagination
     */
    public function getCommentsWithReplies(Post $post, int $page = 1, int $perPage = 10)
    {
        return PostComment::where('post_id', $post->id)
            ->whereNull('parent_id') // Only top-level comments
            ->with([
                'user',
                'replies' => function ($query) {
                    $query->with('user')->orderBy('created_at', 'asc');
                },
                'replies.reactions' => function ($query) {
                    $query->where('reaction_type', 'like'); // For now, only likes
                }
            ])
            ->withCount([
                'reactions as likes_count' => function ($query) {
                    $query->where('reaction_type', 'like');
                }
            ])
            ->orderBy('created_at', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Get comment statistics for a post
     */
    public function getCommentStats(Post $post)
    {
        return [
            'total_comments' => $post->comments_count,
            'top_level_comments' => $post->comments()->count(),
            'total_replies' => $post->allComments()->whereNotNull('parent_id')->count(),
            'recent_commenters' => $this->getRecentCommenters($post, 5)
        ];
    }

    /**
     * Get recent users who commented on a post
     */
    public function getRecentCommenters(Post $post, int $limit = 5)
    {
        return $post->allComments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($comment) {
                return [
                    'user' => $comment->user,
                    'content' => \Str::limit($comment->content, 50),
                    'created_at' => $comment->created_at,
                    'is_reply' => !is_null($comment->parent_id)
                ];
            });
    }

    /**
     * Search comments within a post
     */
    public function searchComments(Post $post, string $query, int $perPage = 10)
    {
        return PostComment::where('post_id', $post->id)
            ->where('content', 'like', '%' . $query . '%')
            ->with(['user', 'parent'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get comments by a specific user on a post
     */
    public function getUserComments(Post $post, User $user, int $perPage = 10)
    {
        return PostComment::where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->with(['parent', 'replies'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Check if user can edit comment
     */
    public function canUserEditComment(User $user, PostComment $comment)
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Check if user can delete comment
     */
    public function canUserDeleteComment(User $user, PostComment $comment)
    {
        // User can delete their own comment, or post owner can delete any comment
        return $user->id === $comment->user_id || $user->id === $comment->post->user_id;
    }

    /**
     * Get comment thread (parent + all replies)
     */
    public function getCommentThread(PostComment $comment)
    {
        // If it's a reply, get the parent comment
        $parentComment = $comment->parent ?? $comment;

        return $parentComment->load(['user', 'replies.user']);
    }
}
