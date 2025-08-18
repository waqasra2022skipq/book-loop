<?php

namespace App\Livewire;

use App\Models\Book;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CreatePost extends Component
{
    use AuthorizesRequests;
    public string $body = '';
    public ?int $bookId = null;

    public function mount(?int $bookId = null): void
    {
        $this->bookId = $bookId;
    }

    protected function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:1', 'max:1000'],
            'bookId' => ['nullable', Rule::exists('books', 'id')],
        ];
    }

    public function submit(PostService $service)
    {
        $this->validate();
        $user = Auth::user();
        $this->authorize('create', \App\Models\Post::class);

        $service->createPost($user, $this->body, $this->bookId);

        $this->reset(['body', 'bookId']);
        $this->dispatch('post-created');
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
