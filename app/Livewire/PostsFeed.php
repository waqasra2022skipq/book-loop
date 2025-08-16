<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PostsFeed extends Component
{
    use WithPagination;

    public ?int $userId = null;
    public ?int $bookId = null;

    public function mount(?int $userId = null, ?int $bookId = null): void
    {
        $this->userId = $userId;
        $this->bookId = $bookId;
    }

    protected $listeners = ['post-created' => '$refresh'];

    public function getPostsProperty()
    {
        return Post::query()
            ->with(['user', 'book'])
            ->when($this->userId, fn($q) => $q->where('user_id', $this->userId))
            ->when($this->bookId, fn($q) => $q->where('book_id', $this->bookId))
            ->latest()
            ->cursorPaginate(20);
    }

    public function render()
    {
        return view('livewire.posts-feed', [
            'posts' => $this->posts,
        ]);
    }
}
