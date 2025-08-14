<div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- User Profile Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                <!-- User Avatar -->
                <div
                    class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                    {{ $user->initials() }}
                </div>

                <!-- User Info -->
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-gray-600 mt-1">{{ $user->email }}</p>

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
                        <div class="flex items-center space-x-2 mt-3">
                            <div class="flex items-center">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= floor($stats['average_rating']) ? 'text-yellow-400' : 'text-gray-300' }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <span
                                class="font-semibold text-gray-900">{{ number_format($stats['average_rating'], 1) }}</span>
                            <span class="text-gray-600">({{ $stats['total_reviews'] }}
                                {{ Str::plural('review', $stats['total_reviews']) }})</span>
                        </div>
                    @else
                        <div class="mt-3">
                            <span class="text-gray-500">No reviews yet</span>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    @auth
                        @if (Auth::id() !== $user->id)
                            <a href="{{ route('users.write-review', ['userId' => $user->id]) }}"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors text-center">
                                Write Review
                            </a>
                        @endif
                    @endauth

                    @if ($stats['total_reviews'] > 0)
                        <a href="{{ route('users.reviews', ['userId' => $user->id]) }}"
                            class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors text-center">
                            View All Reviews
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Reviews Section -->
        @if ($recentReviews->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Recent Reviews</h2>
                    @if ($stats['total_reviews'] > 5)
                        <a href="{{ route('users.reviews', ['userId' => $user->id]) }}"
                            class="text-blue-600 hover:text-blue-700 font-medium">
                            View All →
                        </a>
                    @endif
                </div>

                <div class="space-y-4">
                    @foreach ($recentReviews as $review)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ $review->reviewer->initials() }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $review->reviewer->name }}</p>
                                        <div class="flex items-center space-x-2">
                                            <div class="flex items-center">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-sm text-gray-500">
                                                {{ $review->reviewed_at->format('M j, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @if ($review->transaction_type)
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('_', ' ', $review->transaction_type)) }}
                                    </span>
                                @endif
                            </div>

                            @if ($review->review)
                                <p class="text-gray-700 leading-relaxed">{{ $review->summary }}</p>
                            @else
                                <p class="text-gray-500 italic">No written review provided.</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8m0 0V6a2 2 0 012-2h10a2 2 0 012 2v2M9 10h6">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Reviews Yet</h3>
                <p class="text-gray-600 mb-4">{{ $user->name }} hasn't received any reviews yet.</p>

                @auth
                    @if (Auth::id() !== $user->id)
                        <a href="{{ route('users.write-review', ['userId' => $user->id]) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Be the first to review
                        </a>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</div>
