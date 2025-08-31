<div class="min-h-screen bg-gray-50 py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <a href="{{ route('books.all') }}"
                    class="flex items-center text-gray-600 hover:text-gray-900 transition-colors mr-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">Back to Books</span>
                </a>
            </div>

            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Write a Book Summary</h1>
            <p class="text-gray-600">Share your thoughts about this book with other readers</p>
        </div>

        <!-- Book Info Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex space-x-4">
                @if ($book->cover_image)
                    <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}"
                        class="w-16 h-24 object-cover rounded-lg flex-shrink-0">
                @else
                    <div class="w-16 h-24 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                @endif

                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-900 text-lg truncate">{{ $book->title }}</h3>
                    <p class="text-gray-600 text-sm">by {{ $book->author }}</p>
                    @if ($book->genre)
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mt-2">
                            {{ $book->genre }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <form wire:submit.prevent="submitSummary" class="space-y-6">
            <!-- Personal Information Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Your Information</h2>
                <p class="text-sm text-gray-600 mb-4">We'll create a profile for you so others can see your summaries
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <flux:input wire:model="name" label="Full Name" placeholder="Enter your full name" required />
                        @error('name')
                            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <flux:input wire:model="email" type="email" label="Email Address" placeholder="your@email.com"
                            required />
                        @error('email')
                            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <flux:input wire:model="phone" label="Phone Number (Optional)"
                            placeholder="+1 (555) 123-4567" />
                        @error('phone')
                            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <flux:input wire:model="address" label="Address (Optional)" placeholder="Street address" />
                        @error('address')
                            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <flux:input wire:model="city" label="City (Optional)" placeholder="City" />
                        @error('city')
                            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <flux:input wire:model="state" label="State/Province (Optional)"
                            placeholder="State or Province" />
                        @error('state')
                            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <flux:input wire:model="postal_code" label="Postal Code (Optional)" placeholder="12345" />
                        @error('postal_code')
                            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Summary Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Book Summary</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
                        <div class="flex items-center space-x-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="$set('rating', {{ $i }})"
                                    class="p-1 focus:outline-none">
                                    <svg class="w-8 h-8 {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300' }} transition-colors"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </button>
                            @endfor
                            <span class="ml-2 text-sm text-gray-600">({{ $rating }}/5)</span>
                        </div>
                        @error('rating')
                            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="summary" class="block text-sm font-medium text-gray-700 mb-2">Your Summary</label>
                        <textarea wire:model="summary" id="summary" rows="8"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            placeholder="Share your thoughts, insights, and what you learned from this book. What would you tell a friend about it?"
                            required></textarea>
                        <div class="flex justify-between items-center mt-2">
                            @error('summary')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @else
                                <span class="text-sm text-gray-500">
                                    {{ strlen($summary) }}/50 characters minimum
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button type="submit"
                    class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    wire:loading.attr="disabled" wire:target="submitSummary">
                    <span wire:loading.remove wire:target="submitSummary">Submit Summary</span>
                    <span wire:loading wire:target="submitSummary" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Submitting...
                    </span>
                </button>

                <a href="{{ route('books.all') }}"
                    class="flex-1 sm:flex-none bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Success Modal -->
    @if ($showSuccess)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">
                        Summary Submitted!
                    </h3>

                    <p class="text-gray-600 text-center mb-6">
                        Thank you for sharing your thoughts! Your profile has been created and your summary is now live.
                    </p>

                    <button wire:click="closeSuccess"
                        class="w-full bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                        Continue Browsing
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
