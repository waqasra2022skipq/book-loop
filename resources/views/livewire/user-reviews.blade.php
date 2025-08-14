<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Reviews for {{ $user->name }}
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                {{ $stats['total_reviews'] }} {{ Str::plural('review', $stats['total_reviews']) }}
                @if ($stats['average_rating'])
                    • {{ number_format($stats['average_rating'], 1) }} average rating
                @endif
            </p>
        </div>

        @if ($stats['average_rating'])
            <div class="flex items-center space-x-2">
                <div class="flex items-center">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= floor($stats['average_rating']))
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @elseif($i == ceil($stats['average_rating']) && $stats['average_rating'] - floor($stats['average_rating']) >= 0.5)
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <defs>
                                    <linearGradient id="half-star">
                                        <stop offset="50%" stop-color="currentColor" />
                                        <stop offset="50%" stop-color="#e5e7eb" />
                                    </linearGradient>
                                </defs>
                                <path fill="url(#half-star)"
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endif
                    @endfor
                </div>
                <span class="text-lg font-semibold text-gray-900">
                    {{ number_format($stats['average_rating'], 1) }}
                </span>
            </div>
        @endif
    </div>

    <!-- Rating Breakdown -->
    @if ($stats['total_reviews'] > 0)
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Rating Breakdown</h3>
            <div class="space-y-2">
                @for ($i = 5; $i >= 1; $i--)
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-600 w-6">{{ $i }}★</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            @php
                                $percentage =
                                    $stats['total_reviews'] > 0
                                        ? ($stats['rating_breakdown'][$i] / $stats['total_reviews']) * 100
                                        : 0;
                            @endphp
                            <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm text-gray-600 w-8">{{ $stats['rating_breakdown'][$i] }}</span>
                    </div>
                @endfor
            </div>
        </div>
    @endif

    <!-- Reviews List -->
    @if ($reviews->count() > 0)
        <div class="space-y-4">
            @foreach ($reviews as $review)
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
                        <p class="text-gray-700 leading-relaxed">{{ $review->review }}</p>
                    @else
                        <p class="text-gray-500 italic">No written review provided.</p>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @else
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8m0 0V6a2 2 0 012-2h10a2 2 0 012 2v2M9 10h6">
                    </path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Reviews Yet</h3>
            <p class="text-gray-600">{{ $user->name }} hasn't received any reviews yet.</p>
        </div>
    @endif
</div>
