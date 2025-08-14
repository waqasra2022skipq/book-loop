<div class="min-h-screen bg-gray-50 py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <button onclick="history.back()"
                    class="flex items-center text-gray-600 hover:text-gray-900 transition-colors mr-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">Back</span>
                </button>
            </div>

            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                {{ $existingReview ? 'Update Review' : 'Write a Review' }}
            </h1>
            <p class="text-gray-600">
                {{ $existingReview ? 'Update your review for' : 'Share your experience with' }}
                <span class="font-semibold">{{ $user->name }}</span>
            </p>
        </div>

        <!-- User Info Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex items-center space-x-4">
                <div
                    class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                    {{ $user->initials() }}
                </div>

                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 text-lg">{{ $user->name }}</h3>
                    @if ($user->getAverageRating())
                        <div class="flex items-center space-x-2 mt-1">
                            <div class="flex items-center">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= floor($user->getAverageRating()) ? 'text-yellow-400' : 'text-gray-300' }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600">
                                {{ number_format($user->getAverageRating(), 1) }}
                                ({{ $user->getReviewsCount() }} {{ Str::plural('review', $user->getReviewsCount()) }})
                            </span>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 mt-1">No reviews yet</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Review Form -->
        <form wire:submit.prevent="submitReview"
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="space-y-6">
                <!-- Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Your Rating *</label>
                    <div class="flex items-center space-x-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="$set('rating', {{ $i }})"
                                class="p-1 focus:outline-none transition-colors">
                                <svg class="w-8 h-8 {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </button>
                        @endfor
                        <span class="ml-3 text-sm text-gray-600">({{ $rating }}/5)</span>
                    </div>
                    @error('rating')
                        <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Review Text -->
                <div>
                    <label for="review" class="block text-sm font-medium text-gray-700 mb-2">
                        Your Review (Optional)
                    </label>
                    <textarea wire:model="review" id="review" rows="6"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                        placeholder="Share your experience working with {{ $user->name }}. What was great about your interaction? Was communication clear? Were they reliable?"></textarea>
                    <div class="flex justify-between items-center mt-2">
                        @error('review')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @else
                            <span class="text-sm text-gray-500">
                                {{ strlen($review) }}/1000 characters
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Privacy Setting -->
                <div class="flex items-center space-x-3">
                    <input type="checkbox" wire:model="isPublic" id="isPublic"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    <label for="isPublic" class="text-sm text-gray-700">
                        Make this review public (others can see it on {{ $user->name }}'s profile)
                    </label>
                </div>

                @if ($transactionType)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm text-blue-800">
                                This review is for your {{ str_replace('_', ' ', $transactionType) }} interaction with
                                {{ $user->name }}.
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        wire:loading.attr="disabled" wire:target="submitReview">
                        <span wire:loading.remove wire:target="submitReview">
                            {{ $existingReview ? 'Update Review' : 'Submit Review' }}
                        </span>
                        <span wire:loading wire:target="submitReview" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            {{ $existingReview ? 'Updating...' : 'Submitting...' }}
                        </span>
                    </button>

                    <button type="button" onclick="history.back()"
                        class="flex-1 sm:flex-none bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Success Modal -->
    @if ($showSuccess)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">
                        Review {{ $existingReview ? 'Updated' : 'Submitted' }}!
                    </h3>

                    <p class="text-gray-600 text-center mb-6">
                        Thank you for sharing your feedback about {{ $user->name }}.
                        @if ($isPublic)
                            Your review is now public and will help other users.
                        @else
                            Your review has been saved privately.
                        @endif
                    </p>

                    <button wire:click="closeSuccess"
                        class="w-full bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
