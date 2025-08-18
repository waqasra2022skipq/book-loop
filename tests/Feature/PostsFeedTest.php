<?php

use App\Models\Post;
use App\Models\User;

it('paginates posts newest first', function () {
    $user = User::factory()->create();
    Post::factory()->count(25)->create(['user_id' => $user->id]);

    $firstPage = \App\Models\Post::query()->latest()->cursorPaginate(20);
    expect($firstPage->count())->toBe(20);
});
