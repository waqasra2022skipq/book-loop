<div class="min-h-screen bg-gray-50 py-4 sm:py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <div class="mb-4 px-4">
            <a href="{{ route('feed') }}"
                class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Feed
            </a>
        </div>

        <!-- Single Post -->
        <div class="px-4">
            <livewire:post-card :post="$post" :key="'single-post-' . $post->id" />
        </div>

        <!-- Additional Context -->
        @if ($post->book)
            <div class="mt-6 px-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-semibold mb-2">Related Book</h3>
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-16 bg-gray-200 rounded flex-shrink-0 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $post->book->title }}</h4>
                            <p class="text-sm text-gray-600">by {{ $post->book->author }}</p>
                            <a href="{{ route('books.show', $post->book->slug) }}"
                                class="text-sm text-blue-600 hover:text-blue-800 transition-colors">
                                View Book →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
