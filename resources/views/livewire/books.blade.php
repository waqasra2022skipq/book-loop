{{-- resources/views/livewire/books.blade.php --}}
<section class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
        <!-- Header Section -->
        <div class="text-center mb-8 sm:mb-12">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent mb-3 sm:mb-4">
                Discover Amazing Books
            </h1>
            <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto px-2">
                Explore our curated collection of books waiting to find their next reader
            </p>
            <div class="w-16 sm:w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-500 mx-auto mt-3 sm:mt-4 rounded-full"></div>
        </div>

        <!-- Search Section -->
        <div class="max-w-2xl mx-auto mb-6 sm:mb-8">
            <div class="relative px-2 sm:px-0">
                <!-- Search Input -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="{{ $searchPlaceholder }}"
                        class="block w-full pl-9 sm:pl-10 pr-10 sm:pr-12 py-2.5 sm:py-3 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-all duration-200 text-sm sm:text-base"
                        autocomplete="off"
                    >
                    <!-- Clear Button -->
                    @if($search)
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button 
                                wire:click="clearSearch"
                                class="h-5 w-5 text-gray-400 hover:text-gray-600 transition-colors duration-150"
                                type="button"
                            >
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Search Results Summary -->
                @if($search)
                    <div class="mt-3 text-sm text-gray-600 text-center">
                        @if($instances->total() > 0)
                            Found {{ $instances->total() }} {{ Str::plural('book', $instances->total()) }} for "<span class="font-medium text-gray-900">{{ $search }}</span>"
                        @else
                            No books found for "<span class="font-medium text-gray-900">{{ $search }}</span>"
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Loading Indicator -->
        <div wire:loading.delay class="flex justify-center mb-4">
            <div class="inline-flex items-center px-4 py-2 bg-white rounded-full shadow-sm border">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm text-gray-600">Searching...</span>
            </div>
        </div>

        <!-- Books Grid -->
        <div wire:loading.remove.delay>
            @if($instances->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8 px-2 sm:px-0">
                    @foreach($instances as $instance)
                        <livewire:book-card :instance="$instance" :key="$instance->id" />
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 sm:mt-12 px-2 sm:px-0">
                    {{ $instances->links() }}
                </div>
            @endif
        </div>

        <!-- Empty State -->
        @if($instances->isEmpty())
            <div wire:loading.remove.delay class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    @if($search)
                        <!-- Search Empty State Icon -->
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    @else
                        <!-- Default Empty State Icon -->
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    @endif
                </div>
                @if($search)
                    <h3 class="text-2xl font-semibold text-gray-900 mb-2">No Results Found</h3>
                    <p class="text-gray-600 mb-4">We couldn't find any books matching your search.</p>
                    <button 
                        wire:click="clearSearch"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Clear Search
                    </button>
                @else
                    <h3 class="text-2xl font-semibold text-gray-900 mb-2">No Books Available</h3>
                    <p class="text-gray-600">Check back later for new additions to our collection.</p>
                @endif
            </div>
        @endif
    </div>
</section>