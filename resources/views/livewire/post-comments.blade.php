<div class="post-comments">
    {{-- Comments Toggle Header --}}
    <div class="flex items-center justify-between mb-4">
        <button wire:click="toggleComments" class="flex items-center space-x-2 text-gray-600 hover:text-gray-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                </path>
            </svg>
            <span class="font-medium">
                {{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}
            </span>
        </button>
    </div>

    @if ($showComments)
        {{-- Add New Comment Form --}}
        @auth
            <div class="mb-6 bg-gray-50 rounded-lg p-4">
                <div class="flex space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-sm text-white font-semibold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <flux:textarea wire:model.live.debounce.900ms="newComment" placeholder="Write a comment..."
                            rows="3" class="w-full"></flux:textarea>
                        @error('newComment')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <div class="flex justify-end mt-3 space-x-2">
                            <flux:button wire:click="addComment" variant="primary" size="sm" :disabled="!$newComment">
                                Post Comment
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-6 p-4 bg-blue-50 rounded-lg text-center">
                <p class="text-gray-600">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Sign in</a>
                    to join the conversation
                </p>
            </div>
        @endauth

        {{-- Comments List --}}
        @if ($comments && $comments->count() > 0)
            <div class="space-y-4">
                @foreach ($comments as $comment)
                    <div class="comment-item border-l-2 border-gray-200 pl-4">
                        {{-- Comment Content --}}
                        <div class="flex space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                                    <span class="text-sm text-white font-semibold">
                                        {{ substr($comment->user->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1">
                                {{-- Comment Header --}}
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="font-semibold text-gray-900">{{ $comment->user->name }}</span>
                                    <span class="text-sm text-gray-500">{{ $comment->time_ago }}</span>
                                    @if ($comment->is_edited)
                                        <span class="text-xs text-gray-400">(edited)</span>
                                    @endif
                                </div>

                                {{-- Comment Body --}}
                                @if ($editingComment === $comment->id)
                                    {{-- Edit Mode --}}
                                    <div class="space-y-2">
                                        <flux:textarea wire:model="editContent" rows="3" class="w-full">
                                        </flux:textarea>
                                        @error('editContent')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                        @enderror
                                        <div class="flex space-x-2">
                                            <flux:button wire:click="saveEdit" size="sm">Save</flux:button>
                                            <flux:button wire:click="cancelEdit" variant="ghost" size="sm">Cancel
                                            </flux:button>
                                        </div>
                                    </div>
                                @else
                                    {{-- Display Mode --}}
                                    <p class="text-gray-800 leading-relaxed">{{ $comment->content }}</p>

                                    {{-- Comment Actions --}}
                                    <div class="flex items-center space-x-4 mt-2">
                                        {{-- Like Button --}}
                                        <livewire:post-reactions :reactable="$comment" :key="'comment-reactions-' . $comment->id" />

                                        {{-- Reply Button --}}
                                        @auth
                                            <button wire:click="startReply({{ $comment->id }})"
                                                class="text-sm text-gray-500 hover:text-gray-700">
                                                Reply
                                            </button>
                                        @endauth

                                        {{-- Edit/Delete for comment owner --}}
                                        @auth
                                            @if (auth()->user()->id === $comment->user_id)
                                                <button
                                                    wire:click="startEdit({{ $comment->id }}, '{{ $comment->content }}')"
                                                    class="text-sm text-gray-500 hover:text-gray-700">
                                                    Edit
                                                </button>
                                                <button wire:click="deleteComment({{ $comment->id }})"
                                                    class="text-sm text-red-500 hover:text-red-700"
                                                    onclick="return confirm('Are you sure you want to delete this comment?')">
                                                    Delete
                                                </button>
                                            @endif
                                        @endauth
                                    </div>
                                @endif

                                {{-- Reply Form --}}
                                @if ($replyingTo === $comment->id)
                                    <div class="mt-3 pl-4 border-l-2 border-blue-200">
                                        <div class="flex space-x-3">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                                    <span class="text-xs text-white font-semibold">
                                                        {{ substr(auth()->user()->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <flux:textarea wire:model="replyContent" placeholder="Write a reply..."
                                                    rows="2" class="w-full"></flux:textarea>
                                                @error('replyContent')
                                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                                <div class="flex justify-end mt-2 space-x-2">
                                                    <flux:button wire:click="addReply" size="sm">Reply
                                                    </flux:button>
                                                    <flux:button wire:click="cancelReply" variant="ghost"
                                                        size="sm">Cancel</flux:button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Replies --}}
                                @if ($comment->replies && $comment->replies->count() > 0)
                                    <div class="mt-3 space-y-3 pl-4 border-l-2 border-gray-100">
                                        @foreach ($comment->replies as $reply)
                                            <div class="flex space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div
                                                        class="w-6 h-6 bg-gray-400 rounded-full flex items-center justify-center">
                                                        <span class="text-xs text-white font-semibold">
                                                            {{ substr($reply->user->name, 0, 1) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-2 mb-1">
                                                        <span
                                                            class="font-medium text-gray-900">{{ $reply->user->name }}</span>
                                                        <span
                                                            class="text-xs text-gray-500">{{ $reply->time_ago }}</span>
                                                        @if ($reply->is_edited)
                                                            <span class="text-xs text-gray-400">(edited)</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-gray-800 text-sm leading-relaxed">
                                                        {{ $reply->content }}</p>

                                                    {{-- Reply Actions --}}
                                                    <div class="flex items-center space-x-3 mt-1">
                                                        <livewire:post-reactions :reactable="$reply" :key="'reply-reactions-' . $reply->id" />

                                                        @auth
                                                            @if (auth()->user()->id === $reply->user_id)
                                                                <button wire:click="deleteComment({{ $reply->id }})"
                                                                    class="text-xs text-red-500 hover:text-red-700"
                                                                    onclick="return confirm('Are you sure you want to delete this reply?')">
                                                                    Delete
                                                                </button>
                                                            @endif
                                                        @endauth
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($comments->hasPages())
                <div class="mt-6">
                    {{ $comments->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                    </path>
                </svg>
                <p class="text-gray-500">No comments yet. Be the first to share your thoughts!</p>
            </div>
        @endif
    @endif

    {{-- Loading State --}}
    <div wire:loading class="flex justify-center py-4">
        <div class="flex items-center space-x-2 text-gray-500">
            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="text-sm">Loading comments...</span>
        </div>
    </div>
</div>

{{-- Flash Messages --}}
@if (session()->has('success'))
    <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded" x-data x-show="true"
        x-init="setTimeout(() => $el.style.display = 'none', 4000)">
        {{ session('success') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded" x-data x-show="true"
        x-init="setTimeout(() => $el.style.display = 'none', 6000)">
        {{ session('error') }}
    </div>
@endif
