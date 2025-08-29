<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Str;
use Livewire\Component;

class SinglePost extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post->load([
            'user',
            'book',
            'reactions.user',
            'comments.user',
            'comments.replies.user'
        ]);
    }

    public function render()
    {
        $pageTitle = 'Post by ' . $this->post->user->name;
        $description = Str::limit(strip_tags($this->post->body), 160);

        return view('livewire.single-post')->layoutData([
            'title' => $pageTitle,
            'description' => $description,
        ]);
    }
}
