<div class="space-y-3">
    @auth
        @livewire('create-post')
    @else
        <div class="mb-4">
            <p class="text-gray-600">
                <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:underline">What are you reading?
                    Log in to share.</a>
            </p>
        </div>
    @endauth
    @foreach ($posts as $post)
        <livewire:post-card :post="$post" :key="'post-' . $post->id" />
    @endforeach

    <div class="pt-2">
        {{ $posts->links() }}
    </div>
</div>
