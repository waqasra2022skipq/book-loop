<div class="bg-white rounded-lg shadow-md mb-4 mx-2 sm:mx-0">
    <!-- Post Header -->
    <div class="p-3 sm:p-4 border-b">
        <div class="flex items-center">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-300 rounded-full flex items-center justify-center">
                <span class="text-xs sm:text-sm font-semibold text-gray-700">
                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                </span>
            </div>
            <div class="ml-2 sm:ml-3">
                <h4 class="text-sm sm:text-base font-semibold">{{ $post->user->name }}</h4>
                <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    <!-- Post Content -->
    <div class="p-3 sm:p-4">
        <p class="text-gray-700 text-sm sm:text-base leading-relaxed">{{ $post->body }}</p>

        @if ($post->image)
            <img src="{{ $post->image }}" alt="Post image" class="w-full rounded-lg mt-3">
        @endif
    </div>

    <!-- Reaction Stats -->
    <div class="px-3 sm:px-4 py-2 border-b text-xs sm:text-sm text-gray-600">
        <div class="flex justify-between items-center">
            <span>{{ $post->reactions_count }} {{ Str::plural('like', $post->reactions_count) }}</span>
            <button wire:click="openCommentsModal"
                class="text-gray-600 hover:text-blue-600 transition-colors duration-200 cursor-pointer">
                {{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}
            </button>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="px-3 sm:px-4 py-3 border-b bg-gray-50">
        <div class="flex space-x-1 sm:space-x-4">
            <!-- Like Button (using existing PostReactions component) -->
            <div class="flex-1">
                <livewire:post-reactions :reactable="$post" :key="'reactions-' . $post->id" />
            </div>

            <!-- Comment Button -->
            @auth
                <button wire:click="openAddCommentModal"
                    class="flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.003 9.003 0 01-8.716-6.747M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2h-3M7 8h10">
                        </path>
                    </svg>
                    Comment
                </button>
            @endauth
        </div>
    </div>

    <!-- Add Comment Modal -->
    @if ($showAddCommentModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
            wire:click="closeAddCommentModal">
            <div class="bg-white rounded-lg w-full max-w-md max-h-[90vh] overflow-hidden" wire:click.stop>
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold">Add Comment</h3>
                    <button wire:click="closeAddCommentModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-4">
                    <form wire:submit.prevent="addComment">
                        <div class="mb-4">
                            <label for="newComment" class="block text-sm font-medium text-gray-700 mb-2">Your
                                Comment</label>
                            <textarea wire:model.defer="newComment" id="newComment" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                placeholder="Write your comment here..." maxlength="1000"></textarea>
                            @error('newComment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex space-x-3">
                            <button type="button" wire:click="closeAddCommentModal"
                                class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                                Cancel
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                Post Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Comments Modal -->
    @if ($showCommentsModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
            wire:click="closeCommentsModal">
            <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-hidden" wire:click.stop>
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold">
                        Comments ({{ $post->comments_count }})
                    </h3>
                    <button wire:click="closeCommentsModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="overflow-y-auto max-h-[calc(90vh-120px)]">
                    <livewire:post-comments :post="$post" :key="'modal-comments-' . $post->id" />
                </div>
            </div>
        </div>
    @endif
</div>
