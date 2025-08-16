<div class="space-y-4">
    @auth
        @livewire('create-post')
    @else
        <div class="mb-4">
            <p class="text-gray-600">
                <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:underline">What are you waiting for?
                    Log in to create a post.</a>
            </p>
        </div>
    @endauth

    @livewire('posts-feed')
</div>
