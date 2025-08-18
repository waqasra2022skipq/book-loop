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
        <div class="bg-white dark:bg-zinc-900 rounded-lg p-3 sm:p-4 shadow border border-zinc-200 dark:border-zinc-800">
            <div class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                <a href="{{ route('users.profile', $post->user_id) }}"
                    class="font-medium text-zinc-800 dark:text-zinc-200">{{ $post->user->name }}</a>
                <span>•</span>
                <span
                    title="{{ $post->created_at->toDayDateTimeString() }}">{{ $post->created_at->diffForHumans() }}</span>
            </div>
            <div class="mt-2 text-[15px] leading-relaxed whitespace-pre-line">{{ $post->body }}</div>
            @if ($post->book)
                <div class="mt-2 text-xs text-zinc-600 dark:text-zinc-400">
                    Reading: <a class="underline"
                        href="{{ route('books.instance', $post->book->id) }}">{{ $post->book->title }}</a>
                </div>
            @endif
        </div>
    @endforeach

    <div class="pt-2">
        {{ $posts->links() }}
    </div>
</div>
