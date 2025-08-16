<div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- User Profile Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col lg:flex-row items-start lg:items-center space-y-4 lg:space-y-0 lg:space-x-6">
                <!-- User Avatar -->
                <div class="flex items-center space-x-4 lg:space-x-0 lg:flex-col lg:items-center">
                    <div
                        class="w-16 h-16 sm:w-20 sm:h-20 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xl sm:text-2xl">
                        {{ $user->initials() }}
                    </div>
                </div>

                <!-- User Info -->
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ $user->name }}</h1>
                    <p class="text-gray-600 mt-1 break-words">{{ $user->email }}</p>

                    @if ($user->city || $user->state)
                        <p class="text-sm text-gray-500 mt-1">
                            @if ($user->city)
                                {{ $user->city }}
                                @endif@if ($user->city && $user->state)
                                    ,
                                    @endif@if ($user->state)
                                        {{ $user->state }}
                                    @endif
                        </p>
                    @endif

                    <!-- Rating Display -->
                    @if ($stats['average_rating'])
                        <div class="flex flex-wrap items-center gap-2 mt-3">
                            <div class="flex items-center">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $i <= floor($stats['average_rating']) ? 'text-yellow-400' : 'text-gray-300' }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <span
                                class="font-semibold text-gray-900 text-sm sm:text-base">{{ number_format($stats['average_rating'], 1) }}</span>
                            <span class="text-gray-600 text-sm">({{ $stats['total_reviews'] }}
                                {{ Str::plural('review', $stats['total_reviews']) }})</span>
                        </div>
                    @else
                        <div class="mt-3">
                            <span class="text-gray-500 text-sm sm:text-base">No reviews yet</span>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto lg:flex-col lg:w-auto">
                    @auth
                        @if (Auth::id() !== $user->id)
                            <a href="{{ route('users.write-review', ['userId' => $user->id]) }}"
                                class="inline-flex items-center justify-center bg-blue-600 text-white px-4 sm:px-6 py-2 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors whitespace-nowrap">
                                Write Review
                            </a>
                        @endif
                    @endauth

                    @if ($stats['total_reviews'] > 0)
                        <a href="{{ route('users.reviews', ['userId' => $user->id]) }}"
                            class="inline-flex items-center justify-center bg-gray-100 text-gray-700 px-4 sm:px-6 py-2 rounded-lg font-medium hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors whitespace-nowrap">
                            View All Reviews
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Reviews Section -->
        @if ($recentReviews->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Recent Reviews</h2>
                    @if ($stats['total_reviews'] > 5)
                        <a href="{{ route('users.reviews', ['userId' => $user->id]) }}"
                            class="text-blue-600 hover:text-blue-700 font-medium text-sm sm:text-base">
                            View All →
                        </a>
                    @endif
                </div>

                <div class="space-y-4">
                    @foreach ($recentReviews as $review)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-3 gap-3">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm sm:text-base">
                                        {{ $review->reviewer->initials() }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-medium text-gray-900 text-sm sm:text-base truncate">
                                            {{ $review->reviewer->name }}</p>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <div class="flex items-center">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-xs sm:text-sm text-gray-500 whitespace-nowrap">
                                                {{ $review->reviewed_at->format('M j, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @if ($review->transaction_type)
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 self-start">
                                        {{ ucfirst(str_replace('_', ' ', $review->transaction_type)) }}
                                    </span>
                                @endif
                            </div>

                            @if ($review->review)
                                <p class="text-gray-700 leading-relaxed text-sm sm:text-base">{{ $review->summary }}
                                </p>
                            @else
                                <p class="text-gray-500 italic text-sm sm:text-base">No written review provided.</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sm:p-8 text-center">
                <div
                    class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8m0 0V6a2 2 0 012-2h10a2 2 0 012 2v2M9 10h6">
                        </path>
                    </svg>
                </div>
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">No Reviews Yet</h3>
                <p class="text-gray-600 mb-4 text-sm sm:text-base">{{ $user->name }} hasn't received any reviews yet.
                </p>

                @auth
                    @if (Auth::id() !== $user->id)
                        <a href="{{ route('users.write-review', ['userId' => $user->id]) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm sm:text-base">
                            Be the first to review
                        </a>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</div>
