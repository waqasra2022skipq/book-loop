<div>
    <h3 class="text-2xl font-bold text-gray-800 mb-4">Book Summaries</h3>

    <!-- Add Summary Button -->
    @auth
        <a href="{{ route('books.summary.write', ['book' => $bookId]) }}"
            class="flex-1 text-center px-4 py-2 text-sm font-semibold text-purple-700 bg-purple-50 rounded-lg flex items-center justify-center gap-2 hover:bg-purple-100 focus:bg-purple-200 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                </path>
            </svg>
            Add Summary
        </a>
    @else
        <a href="{{ route('books.guest.write.summary', ['bookId' => $bookId]) }}"
            class="flex-1 text-center px-4 py-2 text-sm font-semibold text-purple-700 bg-purple-50 rounded-lg flex items-center justify-center gap-2 hover:bg-purple-100 focus:bg-purple-200 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                </path>
            </svg>
            Add Summary
        </a>
    @endauth
    @if ($summaries->isEmpty())
        <p class="text-gray-600 text-lg">No summaries available yet. Be the first to add one!</p>
    @else
        <div class="space-y-6">
            @foreach ($summaries as $summary)
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 shadow">
                    <div class="flex items-center gap-4 mb-2">
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($summary->writer?->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">{{ $summary->writer?->name }}</div>
                            <div class="text-xs text-gray-500">{{ $summary->created_at->diffForHumans() }}</div>
                        </div>
                        @if ($summary->rating)
                            <div class="ml-auto flex items-center gap-1">
                                <span class="text-yellow-500 font-bold">&#9733;</span>
                                <span class="text-sm font-semibold">{{ $summary->rating }}/5</span>
                            </div>
                        @endif
                    </div>
                    <div class="text-gray-700 text-base leading-relaxed mt-2">{{ $summary->summary }}</div>
                </div>
            @endforeach
        </div>
        @if ($summaries->count() < $total)
            {{-- <div class="mt-8 flex justify-center">
                <button wire:click="loadMore" class="px-6 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    More Summaries
                </button>
            </div> --}}
            <div x-intersect="$wire.loadMore()">
                Loading More
            </div>
        @endif
    @endif
</div>
