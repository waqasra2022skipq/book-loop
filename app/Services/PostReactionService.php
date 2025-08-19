<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostReaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PostReactionService
{
    /**
     * Toggle a reaction on a post or comment
     */
    public function toggleReaction($reactable, User $user, string $reactionType = 'like')
    {
        return DB::transaction(function () use ($reactable, $user, $reactionType) {
            $existingReaction = $reactable->reactions()
                ->where('user_id', $user->id)
                ->first();

            if ($existingReaction) {
                if ($existingReaction->reaction_type === $reactionType) {
                    // Remove reaction (user clicked same reaction again)
                    $existingReaction->delete();
                    $this->decrementCounters($reactable, $existingReaction->reaction_type);
                    return ['action' => 'removed', 'reaction' => null];
                } else {
                    // Update reaction type (user clicked different reaction)
                    $this->decrementCounters($reactable, $existingReaction->reaction_type);
                    $existingReaction->update(['reaction_type' => $reactionType]);
                    $this->incrementCounters($reactable, $reactionType);
                    return ['action' => 'updated', 'reaction' => $existingReaction->fresh()];
                }
            } else {
                // Add new reaction
                $reaction = $reactable->reactions()->create([
                    'user_id' => $user->id,
                    'reaction_type' => $reactionType
                ]);
                $this->incrementCounters($reactable, $reactionType);
                return ['action' => 'added', 'reaction' => $reaction];
            }
        });
    }

    /**
     * Get a summary of all reactions for a post/comment
     */
    public function getReactionsSummary($reactable)
    {
        return $reactable->reactions()
            ->select('reaction_type', DB::raw('count(*) as count'))
            ->groupBy('reaction_type')
            ->orderBy('count', 'desc')
            ->get()
            ->pluck('count', 'reaction_type')
            ->toArray();
    }

    /**
     * Get users who reacted to a post/comment
     */
    public function getReactionUsers($reactable, string $reactionType = null, int $limit = 10)
    {
        $query = $reactable->reactions()->with('user');

        if ($reactionType) {
            $query->where('reaction_type', $reactionType);
        }

        return $query->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($reaction) {
                return [
                    'user' => $reaction->user,
                    'reaction_type' => $reaction->reaction_type,
                    'created_at' => $reaction->created_at
                ];
            });
    }

    /**
     * Check if a user has reacted to a post/comment
     */
    public function hasUserReacted($reactable, User $user, string $reactionType = null)
    {
        $query = $reactable->reactions()->where('user_id', $user->id);

        if ($reactionType) {
            $query->where('reaction_type', $reactionType);
        }

        return $query->exists();
    }

    /**
     * Increment counters based on the reactable type
     */
    private function incrementCounters($reactable, string $reactionType)
    {
        if ($reactable instanceof Post) {
            $reactable->increment('reactions_count');
            if ($reactionType === 'like') {
                $reactable->increment('likes_count');
            }
        }
        // For comments, we might want to add counters later
    }

    /**
     * Decrement counters based on the reactable type
     */
    private function decrementCounters($reactable, string $reactionType)
    {
        if ($reactable instanceof Post) {
            $reactable->decrement('reactions_count');
            if ($reactionType === 'like' && $reactable->likes_count > 0) {
                $reactable->decrement('likes_count');
            }
        }
        // For comments, we might want to add counters later
    }

    /**
     * Get reaction statistics for a post
     */
    public function getPostReactionStats(Post $post)
    {
        return [
            'total_reactions' => $post->reactions_count,
            'total_likes' => $post->likes_count,
            'reactions_breakdown' => $this->getReactionsSummary($post),
            'recent_reactions' => $this->getReactionUsers($post, null, 5)
        ];
    }
}
