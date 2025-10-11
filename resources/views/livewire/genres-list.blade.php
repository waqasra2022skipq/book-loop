<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Browse Genres</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Discover books by your favorite genres and explore new categories
            </p>
        </div>

        <!-- Search Section -->
        <div class="mb-8">
            <div class="max-w-lg mx-auto">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="search"
                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Search genres...">
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div wire:loading.delay class="text-center py-8">
            <div class="inline-flex items-center px-4 py-2 text-sm text-blue-600">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                Loading genres...
            </div>
        </div>

        <!-- Genres Grid -->
        <div wire:loading.remove.delay>
            @if($genres->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach($genres as $genre)
                        <div class="group bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl hover:border-blue-300 transition-all duration-300">
                            <div class="p-6">
                                <!-- Genre Icon -->
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-105 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                </div>

                                <!-- Genre Info -->
                                <div class="space-y-2">
                                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                        {{ $genre->display_name }}
                                    </h3>
                                    
                                    @if($genre->description)
                                        <p class="text-sm text-gray-600 line-clamp-2">
                                            {{ $genre->description }}
                                        </p>
                                    @endif

                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">
                                            {{ $genre->books_count }} {{ Str::plural('book', $genre->books_count) }}
                                        </span>
                                        
                                        <a href="{{ route('genres.show', $genre->slug) }}" 
                                           class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                            Explore
                                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $genres->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No genres found</h3>
                    <p class="text-gray-500 mb-4">
                        @if($search)
                            No genres match your search "{{ $search }}". Try a different search term.
                        @else
                            No genres are available at the moment.
                        @endif
                    </p>
                    @if($search)
                        <button wire:click="$set('search', '')" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors">
                            Clear search
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>