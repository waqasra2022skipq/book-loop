<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Str;
use Livewire\Component;

class PostShare extends Component
{
    public Post $post;
    public $showShareModal = false;

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function openShareModal()
    {
        $this->showShareModal = true;
    }

    public function closeShareModal()
    {
        $this->showShareModal = false;
    }

    public function getPostUrlProperty()
    {
        return route('posts.show', $this->post->id);
    }

    public function getShareUrl($platform)
    {
        $postUrl = urlencode($this->postUrl);
        $title = urlencode(Str::limit($this->post->body, 100));
        $via = urlencode(config('app.name'));

        switch ($platform) {
            case 'facebook':
                return "https://www.facebook.com/sharer/sharer.php?u={$postUrl}";

            case 'twitter':
                return "https://twitter.com/intent/tweet?url={$postUrl}&text={$title}&via={$via}";

            case 'linkedin':
                return "https://www.linkedin.com/sharing/share-offsite/?url={$postUrl}";

            case 'whatsapp':
                return "https://wa.me/?text={$title} {$postUrl}";

            case 'telegram':
                return "https://t.me/share/url?url={$postUrl}&text={$title}";

            case 'email':
                $subject = urlencode("Check out this post on " . config('app.name'));
                return "mailto:?subject={$subject}&body={$title} {$postUrl}";

            default:
                return $this->postUrl;
        }
    }

    public function copyToClipboard()
    {
        // This will trigger JavaScript to copy the URL
        $this->dispatch('copy-to-clipboard', ['url' => $this->postUrl]);
    }

    public function render()
    {
        return view('livewire.post-share');
    }
}
