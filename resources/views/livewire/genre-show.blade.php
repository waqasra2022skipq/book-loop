<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-6 lg:mb-0">
                    <!-- Breadcrumb -->
                    <nav class="text-sm text-gray-500 mb-2">
                        <a href="{{ route('welcome') }}" class="hover:text-blue-600 transition-colors">Home</a>
                        <span class="mx-2">/</span>
                        <a href="{{ route('genres.index') }}" class="hover:text-blue-600 transition-colors">Genres</a>
                        <span class="mx-2">/</span>
                        <span class="text-gray-900">{{ $genre->display_name }}</span>
                    </nav>

                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $genre->display_name }}</h1>
                    
                    @if($genre->description)
                        <p class="text-lg text-gray-600 max-w-3xl">{{ $genre->description }}</p>
                    @endif
                    
                    <div class="flex items-center gap-4 mt-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $instances->total() }} {{ Str::plural('book', $instances->total()) }} available
                        </span>
                    </div>
                </div>

                <!-- Genre Icon -->
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Search -->
                <div class="flex-1 max-w-lg">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="search"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Search books in {{ $genre->display_name }}...">
                    </div>
                </div>

                <!-- Sort Options -->
                <div class="flex items-center gap-4">
                    <label class="text-sm font-medium text-gray-700">Sort by:</label>
                    <select wire:model.live="sortBy" 
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="title_asc">Title A-Z</option>
                        <option value="title_desc">Title Z-A</option>
                    </select>
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
                Loading books...
            </div>
        </div>

        <!-- Books Grid -->
        <div wire:loading.remove.delay>
            @if($instances->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach($instances as $instance)
                        <livewire:book-card :instance="$instance" :key="$instance->id" />
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $instances->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-12 text-center">
                    <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No books found</h3>
                    <p class="text-gray-500 mb-6">
                        @if($search)
                            No books in {{ $genre->display_name }} match your search "{{ $search }}".
                        @else
                            There are no books available in the {{ $genre->display_name }} genre yet.
                        @endif
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        @if($search)
                            <button wire:click="$set('search', '')" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors">
                                Clear search
                            </button>
                        @endif
                        <a href="{{ route('genres.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            Browse all genres
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>