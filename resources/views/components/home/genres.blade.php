<!-- Popular Genres Section -->
<section class="py-16 bg-gradient-to-br from-gray-50 via-white to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Explore Popular Genres</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Discover books from your favorite genres or explore something new
            </p>
        </div>

        <!-- Genres Grid -->
        @if ($popularGenres && count($popularGenres) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach ($popularGenres as $genre)
                    <a href="{{ route('genres.show', $genre->slug) }}"
                        class="group bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl hover:border-blue-300 hover:-translate-y-1 transition-all duration-300">
                        <div class="p-6">
                            <!-- Genre Icon -->
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
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

                                @if ($genre->description)
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        {{ Str::limit($genre->description, 80) }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between pt-2">
                                    <span class="text-sm text-gray-500">
                                        {{ $genre->books_count }} {{ Str::plural('book', $genre->books_count) }}
                                    </span>

                                    <div
                                        class="flex items-center text-sm font-medium text-blue-600 group-hover:text-blue-800 transition-colors">
                                        Explore
                                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- View All Genres Button -->
            <div class="text-center">
                <a href="{{ route('genres.index') }}"
                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-2xl hover:from-blue-700 hover:to-purple-700 transform hover:-translate-y-1 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    View All Genres
                </a>
            </div>
        @else
            <!-- Empty State for Genres -->
            <div class="text-center py-12">
                <div class="w-20 h-20 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Genres Coming Soon</h3>
                <p class="text-gray-500">
                    We're working on categorizing books by genres. Check back soon!
                </p>
            </div>
        @endif
    </div>
</section>
