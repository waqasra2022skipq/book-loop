<?php

use App\Models\Book;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;

it('allows an authenticated user to create a post without book', function () {
    $user = User::factory()->create();
    $service = app(PostService::class);

    $post = $service->createPost($user, 'Reading today');

    expect($post->exists)->toBeTrue()
        ->and($post->user_id)->toBe($user->id)
        ->and($post->book_id)->toBeNull();
});

it('allows an authenticated user to create a post with a book', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();
    $service = app(PostService::class);

    $post = $service->createPost($user, 'Great chapter', $book->id);

    expect($post->book_id)->toBe($book->id);
});

it('rejects overly long posts', function () {
    $user = User::factory()->create();
    $service = app(PostService::class);

    $this->expectException(\Illuminate\Validation\ValidationException::class);
    $service->createPost($user, str_repeat('a', 1001));
});
