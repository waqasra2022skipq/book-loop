<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PostService
{
    public function createPost(User $user, string $body, ?int $bookId = null, string $visibility = 'public'): Post
    {
        $this->ensureNotRateLimited($user);

        $body = trim($body);
        if ($body === '' || Str::length($body) > 1000) {
            throw ValidationException::withMessages([
                'body' => 'Post must be between 1 and 1000 characters.',
            ]);
        }

        // Basic sanitization: keep plain text only
        $body = strip_tags($body);

        $post = new Post([
            'body' => $body,
            'book_id' => $bookId,
            'visibility' => $visibility,
        ]);
        $post->user()->associate($user);
        $post->save();

        RateLimiter::hit($this->throttleKey($user));

        return $post->load(['user', 'book']);
    }

    protected function ensureNotRateLimited(User $user): void
    {
        $key = $this->throttleKey($user);
        if (RateLimiter::tooManyAttempts($key, 10)) {
            throw ValidationException::withMessages([
                'rate_limit' => 'Too many posts. Please try again later.',
            ]);
        }
    }

    protected function throttleKey(User $user): string
    {
        return 'posts:' . $user->id;
    }
}
