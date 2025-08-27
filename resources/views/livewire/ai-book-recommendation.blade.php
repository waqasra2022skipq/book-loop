<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
        <!-- Header -->
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                    🤖 AI Book Recommendations
                </h1>
                <p class="text-sm sm:text-base text-gray-600">
                    Get personalized book recommendations powered by AI
                </p>
                @guest
                    <p class="text-xs text-blue-600 mt-2">
                        ✨ No account required! We'll create a profile for you to save your recommendations.
                    </p>
                @endguest
            </div>

            <!-- Navigation Tabs -->
            @auth
                <div class="flex space-x-1 mb-6 bg-white rounded-lg p-1 shadow-sm">
                    <button wire:click="$set('showHistory', false)"
                        class="flex-1 px-3 py-2 text-xs sm:text-sm font-medium rounded-md transition-colors duration-200
                        {{ !$showHistory ? 'bg-blue-600 text-white' : 'text-gray-600 hover:text-blue-600' }}">
                        New Recommendation
                    </button>
                    <button wire:click="toggleHistory"
                        class="flex-1 px-3 py-2 text-xs sm:text-sm font-medium rounded-md transition-colors duration-200
                        {{ $showHistory ? 'bg-blue-600 text-white' : 'text-gray-600 hover:text-blue-600' }}">
                        History
                    </button>
                </div>
            @endauth

            @if (!$showHistory)
                <!-- Recommendation Form -->
                <form wire:submit.prevent="generateRecommendations" class="space-y-6">

                    <!-- Recent Books Section -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Recent Books You've Read</h2>
                            <span class="text-xs sm:text-sm text-gray-500">(Optional, max 5)</span>
                        </div>

                        <div class="space-y-3">
                            @forelse ($recentBooks as $index => $book)
                                <div class="flex space-x-2 p-3 border rounded-lg bg-gray-50">
                                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-3">
                                        <input type="text" wire:model.defer="recentBooks.{{ $index }}.title"
                                            placeholder="Book Title*"
                                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                            required>
                                        <input type="text" wire:model.defer="recentBooks.{{ $index }}.author"
                                            placeholder="Author"
                                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <input type="text" wire:model.defer="recentBooks.{{ $index }}.genre"
                                            placeholder="Genre"
                                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    </div>
                                    <button type="button" wire:click="removeBook({{ $index }})"
                                        class="px-2 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('recentBooks.' . $index . '.title')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">No books added yet. Add your recent
                                    reads to get better recommendations!</p>
                            @endforelse

                            @if (count($recentBooks) < 5)
                                <button type="button" wire:click="addBook"
                                    class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-400 hover:text-blue-600 transition-colors duration-200 text-sm font-medium">
                                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Book ({{ 5 - count($recentBooks) }} remaining)
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- User Prompt Section -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">What Are You Looking For?</h2>
                        <div class="space-y-3">
                            <textarea wire:model.defer="userPrompt"
                                placeholder="Tell the AI what kind of books you're looking for... For example: 'I want sci-fi books with strong female protagonists and complex world-building, similar to The Left Hand of Darkness'"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm resize-none"
                                maxlength="1000"></textarea>
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>Be specific for better recommendations</span>
                                <span>{{ 1000 - strlen($userPrompt) }} characters remaining</span>
                            </div>
                            @error('userPrompt')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @guest
                        <!-- Guest User Information Section -->
                        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border-l-4 border-blue-500">
                            <div class="flex items-center mb-4">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <h2 class="text-lg font-semibold text-gray-900">Your Information</h2>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                                    <input type="text" wire:model.defer="guestName" placeholder="Your name"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    @error('guestName')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                    <input type="email" wire:model.defer="guestEmail" placeholder="your@email.com"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    @error('guestEmail')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                <p class="text-xs text-blue-700">
                                    📧 We'll create a profile with this email to save your recommendations. You can access
                                    them anytime!
                                </p>
                            </div>
                        </div>
                    @endguest

                    <!-- Rate Limit Information -->
                    @if (isset($rateLimitInfo))
                        <div
                            class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border-l-4 
                            {{ $rateLimitInfo['remaining_requests'] > 0 ? 'border-green-500' : 'border-orange-500' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 {{ $rateLimitInfo['remaining_requests'] > 0 ? 'text-green-600' : 'text-orange-600' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3
                                        class="text-sm font-medium {{ $rateLimitInfo['remaining_requests'] > 0 ? 'text-green-800' : 'text-orange-800' }}">
                                        Usage Limit
                                    </h3>
                                </div>
                                <div class="text-right">
                                    <p
                                        class="text-sm font-semibold {{ $rateLimitInfo['remaining_requests'] > 0 ? 'text-green-800' : 'text-orange-800' }}">
                                        {{ $rateLimitInfo['remaining_requests'] }} of 3 requests remaining
                                    </p>
                                    @if ($rateLimitInfo['remaining_requests'] == 0)
                                        <p class="text-xs text-orange-600">
                                            Next request available in {{ $rateLimitInfo['minutes_until_reset'] }}
                                            minutes
                                        </p>
                                    @endif
                                </div>
                            </div>

                            @if ($rateLimitInfo['remaining_requests'] > 0)
                                <div class="mt-2">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full transition-all duration-300"
                                            style="width: {{ ($rateLimitInfo['remaining_requests'] / 3) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="mt-3 p-3 bg-orange-50 rounded-lg">
                                    <p class="text-xs text-orange-700">
                                        ⏰ You've reached the hourly limit of 3 AI recommendations. This helps us manage
                                        server costs and ensures quality for all users.
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <button type="submit" wire:loading.attr="disabled"
                            @if (isset($rateLimitInfo) && $rateLimitInfo['remaining_requests'] == 0) disabled @endif
                            class="flex-1 px-6 py-3 font-medium rounded-lg transition-colors duration-200
                                @if (isset($rateLimitInfo) && $rateLimitInfo['remaining_requests'] == 0) bg-gray-400 text-gray-600 cursor-not-allowed
                                @else
                                    bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed @endif">
                            <div wire:loading wire:target="generateRecommendations"
                                class="flex items-center justify-center">
                                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Generating Recommendations...
                            </div>
                            <span wire:loading.remove wire:target="generateRecommendations">
                                @if (isset($rateLimitInfo) && $rateLimitInfo['remaining_requests'] == 0)
                                    ⏰ Rate Limited ({{ $rateLimitInfo['minutes_until_reset'] }}m remaining)
                                @else
                                    🤖 Get AI Recommendations
                                @endif
                            </span>
                        </button>

                        <button type="button" wire:click="clearForm"
                            class="px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors duration-200">
                            Clear Form
                        </button>
                    </div>
                </form>

                <!-- Recommendations Results -->
                @if (!empty($recommendations))
                    <div class="mt-8 bg-white rounded-lg shadow-sm p-4 sm:p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Your AI Book Recommendations</h2>

                        <!-- Responsive Grid with max width on extra large screens -->
                        <div class="max-w-7xl mx-auto">
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 recommendation-grid">
                                @foreach ($recommendations as $recommendation)
                                    <div
                                        class="book-card border rounded-lg p-4 bg-white h-full flex flex-col shadow-sm">
                                        <!-- Book Cover Placeholder with better proportions -->
                                        <div
                                            class="w-full h-40 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center mb-4 flex-shrink-0 relative overflow-hidden">
                                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z">
                                                </path>
                                            </svg>
                                            <!-- Genre badge overlay -->
                                            <div class="absolute top-2 left-2">
                                                <span
                                                    class="px-2 py-1 bg-white/90 backdrop-blur text-blue-800 text-xs rounded-full font-medium shadow-sm">
                                                    {{ $recommendation->genre }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Book Details -->
                                        <div class="flex-1 flex flex-col">
                                            <div class="mb-3">
                                                <h3
                                                    class="text-lg font-bold text-gray-900 line-clamp-2 mb-2 leading-tight">
                                                    {{ $recommendation->title }}
                                                </h3>
                                                <p class="text-gray-600 text-sm mb-3 font-medium">by
                                                    {{ $recommendation->author }}</p>

                                                <!-- Metadata tags -->
                                                <div class="flex flex-wrap gap-1 mb-3">
                                                    @if ($recommendation->publication_year)
                                                        <span
                                                            class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-md font-medium">
                                                            {{ $recommendation->publication_year }}
                                                        </span>
                                                    @endif
                                                    @if ($recommendation->pages)
                                                        <span
                                                            class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-md font-medium">
                                                            {{ $recommendation->pages }}p
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Confidence Score with better design -->
                                                <div
                                                    class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-3 mb-3">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <span class="text-xs font-medium text-gray-600">Match
                                                            Score</span>
                                                        <span class="text-sm font-bold text-green-700">
                                                            {{ number_format($recommendation->confidence_score * 100) }}%
                                                        </span>
                                                    </div>
                                                    <div class="w-full bg-green-100 rounded-full h-2">
                                                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full transition-all duration-500"
                                                            style="width: {{ $recommendation->confidence_score * 100 }}%">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Description -->
                                            <p class="text-gray-700 text-sm mb-4 flex-1 line-clamp-3 leading-relaxed">
                                                {{ $recommendation->description }}
                                            </p>

                                            <!-- AI Reason with better styling -->
                                            <div
                                                class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-3 mb-4 border-l-4 border-blue-400">
                                                <div class="flex items-start gap-2">
                                                    <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                    <p class="text-blue-800 text-xs leading-relaxed">
                                                        <strong class="text-blue-900">Why recommended:</strong>
                                                        {{ Str::limit($recommendation->ai_reason, 150) }}
                                                    </p>
                                                </div>
                                            </div> <!-- Feedback Buttons -->
                                            @if (!$recommendation->user_feedback)
                                                <div class="flex flex-wrap gap-2 mt-auto">
                                                    <button
                                                        wire:click="submitFeedback({{ $recommendation->id }}, 'saved')"
                                                        class="flex-1 px-3 py-2 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition-colors duration-200 text-center">
                                                        💾 Save
                                                    </button>
                                                    <button
                                                        wire:click="submitFeedback({{ $recommendation->id }}, 'already_read')"
                                                        class="flex-1 px-3 py-2 bg-orange-600 text-white text-xs rounded-lg hover:bg-orange-700 transition-colors duration-200 text-center">
                                                        ✓ Read
                                                    </button>
                                                    <button
                                                        wire:click="submitFeedback({{ $recommendation->id }}, 'not_interested')"
                                                        class="flex-1 px-3 py-2 bg-gray-600 text-white text-xs rounded-lg hover:bg-gray-700 transition-colors duration-200 text-center">
                                                        ✕ Pass
                                                    </button>
                                                </div>
                                            @else
                                                <div
                                                    class="text-xs text-gray-500 mt-auto p-2 bg-gray-50 rounded-lg text-center">
                                                    @if ($recommendation->user_feedback === 'saved')
                                                        ✅ Saved to your list
                                                    @elseif ($recommendation->user_feedback === 'already_read')
                                                        📖 Marked as already read
                                                    @elseif ($recommendation->user_feedback === 'not_interested')
                                                        ❌ Marked as not interested
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <!-- History View -->
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Recommendation History</h2>

                    @if ($history && $history->count())
                        <div class="space-y-6">
                            @foreach ($history as $request)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <div class="text-sm text-gray-600">
                                                {{ $request->created_at->format('M j, Y \a	 g:i A') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $request->recommendations->count() }} recommendations •
                                                {{ $request->response_time }}s response time
                                            </div>
                                        </div>
                                        <span
                                            class="px-2 py-1 text-xs rounded-full
                                            {{ $request->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>

                                    <p class="text-sm text-gray-700 mb-3">
                                        "{{ Str::limit($request->user_prompt, 100) }}"</p>

                                    @if ($request->recommendations->count())
                                        <div class="grid gap-2">
                                            @foreach ($request->recommendations->take(3) as $rec)
                                                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                                    <div>
                                                        <div class="font-medium text-sm">{{ $rec->title }}</div>
                                                        <div class="text-xs text-gray-600">{{ $rec->author }}</div>
                                                    </div>
                                                    @if ($rec->user_feedback)
                                                        <span
                                                            class="text-xs px-2 py-1 rounded-full
                                                            {{ $rec->user_feedback === 'saved'
                                                                ? 'bg-green-100 text-green-700'
                                                                : ($rec->user_feedback === 'already_read'
                                                                    ? 'bg-orange-100 text-orange-700'
                                                                    : 'bg-gray-100 text-gray-700') }}">
                                                            {{ ucfirst(str_replace('_', ' ', $rec->user_feedback)) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                            @if ($request->recommendations->count() > 3)
                                                <div class="text-xs text-gray-500 text-center">
                                                    +{{ $request->recommendations->count() - 3 }} more recommendations
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $history->links() }}
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <p>No recommendation history yet.</p>
                            <p class="text-sm">Generate your first AI book recommendation!</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50" x-data
            x-show="true" x-init="setTimeout(() => $el.style.display = 'none', 5000)">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg z-50" x-data
            x-show="true" x-init="setTimeout(() => $el.style.display = 'none', 7000)">
            {{ session('error') }}
        </div>
    @endif
</div>
