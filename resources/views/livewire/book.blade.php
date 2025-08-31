<section class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50">
    <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
        <!-- Modern Header -->
        <div class="mb-8 sm:mb-12">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Enhanced Cover Section -->
                    <div class="lg:w-80 p-6 sm:p-8 bg-gradient-to-br from-blue-50 to-indigo-50 flex justify-center">
                        <div class="relative group">
                            <div
                                class="absolute -inset-2 bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 rounded-2xl opacity-20 group-hover:opacity-30 transition-opacity blur">
                            </div>
                            <div
                                class="relative w-48 h-64 sm:w-52 sm:h-72 bg-white rounded-xl shadow-2xl overflow-hidden transform group-hover:scale-105 transition-transform duration-300">
                                @if ($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}"
                                        alt="{{ $book->title }} cover" class="object-cover w-full h-full" />
                                @else
                                    <div
                                        class="flex items-center justify-center w-full h-full bg-gradient-to-br from-gray-50 to-gray-100">
                                        <div class="text-center text-gray-400">
                                            <svg class="w-16 h-16 mx-auto mb-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                            <span class="text-sm font-medium">No Cover</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Info Section -->
                    <div class="flex-1 p-6 sm:p-8 lg:p-10">
                        <div class="space-y-4">
                            <div>
                                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-2">
                                    {{ $book->title }}
                                </h1>
                                <p class="text-lg sm:text-xl text-gray-600 font-medium">
                                    by <span class="text-gray-800 font-semibold">{{ $book->author }}</span>
                                </p>
                            </div>

                            <!-- Enhanced Badges -->
                            <div class="flex flex-wrap gap-3">
                                <div class="flex items-center gap-2 bg-blue-50 px-4 py-2 rounded-full">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span
                                        class="text-sm font-semibold text-blue-700">{{ $book->genre->name ?? 'General' }}</span>
                                </div>

                                <div class="flex items-center gap-2 bg-amber-50 px-4 py-2 rounded-full">
                                    <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <a href="{{ route('reviews.all', ['book' => $book->slug]) }}"
                                        class="text-xs text-amber-600">
                                        <span
                                            class="text-sm font-semibold text-amber-700">{{ $book->average_rating() ?? '0.00' }}/5</span>
                                        ({{ $book->total_ratings() }} reviews)</a>
                                </div>

                                <div class="flex items-center gap-2 bg-green-50 px-4 py-2 rounded-full">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9.5H6a1 1 0 100 2h4.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-semibold text-green-700">{{ $instances->count() }}
                                        {{ $instances->count() === 1 ? 'copy' : 'copies' }} available</span>
                                </div>
                            </div>

                            <!-- Quick Stats -->
                            @if ($book->isbn)
                                <div class="pt-4">
                                    <div class="text-sm text-gray-500">ISBN: <span
                                            class="font-mono text-gray-700">{{ $book->isbn }}</span></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Tabs -->
        <div x-data="{ tab: 'details' }" class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-1">
                <div class="flex flex-wrap gap-1">
                    <button @click="tab = 'details'"
                        :class="tab === 'details' ? 'bg-blue-600 text-white shadow-md' :
                            'text-gray-600 hover:text-blue-600 hover:bg-blue-50'"
                        class="flex-1 sm:flex-none px-6 py-3 font-semibold rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                clip-rule="evenodd" />
                        </svg>
                        Details
                    </button>
                    <button @click="tab = 'reviews'"
                        :class="tab === 'reviews' ? 'bg-blue-600 text-white shadow-md' :
                            'text-gray-600 hover:text-blue-600 hover:bg-blue-50'"
                        class="flex-1 sm:flex-none px-6 py-3 font-semibold rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd" />
                            <path fill-rule="evenodd"
                                d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                clip-rule="evenodd" />
                        </svg>
                        Reviews
                    </button>
                    <button @click="tab = 'instances'"
                        :class="tab === 'instances' ? 'bg-blue-600 text-white shadow-md' :
                            'text-gray-600 hover:text-blue-600 hover:bg-blue-50'"
                        class="flex-1 sm:flex-none px-6 py-3 font-semibold rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Available Copies
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Enhanced Details Tab -->
                <div x-show="tab === 'details'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100" class="p-6 sm:p-8">
                    <div class="space-y-8">
                        <!-- Description Section -->
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Description</h3>
                            </div>
                            <div class="prose prose-lg max-w-none">
                                <p class="text-gray-700 leading-relaxed text-base">
                                    {{ $book->description ?: 'No description available for this book.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Book Information Grid -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Book Information</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Title -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Title</dt>
                                    <dd class="text-lg font-semibold text-gray-900">{{ $book->title }}</dd>
                                </div>

                                <!-- Author -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Author</dt>
                                    <dd class="text-lg font-semibold text-gray-900">{{ $book->author }}</dd>
                                </div>

                                <!-- Genre -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Genre</dt>
                                    <dd class="text-lg font-semibold text-gray-900">
                                        {{ $book->genre->name ?? 'General' }}</dd>
                                </div>

                                <!-- ISBN -->
                                @if ($book->isbn)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <dt class="text-sm font-medium text-gray-500 mb-1">ISBN</dt>
                                        <dd class="text-lg font-mono font-semibold text-gray-900">{{ $book->isbn }}
                                        </dd>
                                    </div>
                                @endif

                                <!-- Average Rating -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Average Rating</dt>
                                    <dd class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                        <span>{{ $book->average_rating() ?? '0.00' }}/5</span>
                                        <div class="flex">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= floor($book->average_rating() ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                    </dd>
                                </div>

                                <!-- Total Reviews -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Total Reviews</dt>
                                    <dd class="text-lg font-semibold text-gray-900">{{ $book->total_ratings() }}
                                        {{ $book->total_ratings() === 1 ? 'review' : 'reviews' }}</dd>
                                </div>

                                <!-- Available Copies -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Available Copies</dt>
                                    <dd class="text-lg font-semibold text-gray-900">{{ $instances->count() }}
                                        {{ $instances->count() === 1 ? 'copy' : 'copies' }}</dd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div x-show="tab === 'reviews'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100" class="p-6 sm:p-8">
                    @livewire('book-summaries', ['book' => $book])
                </div>

                <!-- Enhanced Available Copies Tab -->
                <div x-show="tab === 'instances'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100" class="p-6 sm:p-8">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Available Copies</h3>
                            </div>
                            <div class="bg-blue-50 px-3 py-1 rounded-full">
                                <span class="text-sm font-semibold text-blue-700">{{ $instances->count() }}
                                    {{ $instances->count() === 1 ? 'copy' : 'copies' }}</span>
                            </div>
                        </div>

                        @if ($instances->isEmpty())
                            <div class="text-center py-12">
                                <div
                                    class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-900 mb-2">No copies available</h4>
                                <p class="text-gray-600">Check back later or contact us about availability.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach ($instances as $instance)
                                    <div
                                        class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                                        <!-- Owner Header -->
                                        <div
                                            class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center">
                                                    <span
                                                        class="text-white font-semibold text-sm">{{ strtoupper(substr($instance->owner->name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900">
                                                        {{ $instance->owner->name }}</h4>
                                                    <p class="text-sm text-gray-600">Book Owner</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Details -->
                                        <div class="p-6 space-y-4">
                                            <!-- Location -->
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-gray-600" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Location</p>
                                                    <p class="font-semibold text-gray-900">
                                                        {{ $instance->city }}{{ $instance->address ? ', ' . $instance->address : '' }}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Condition -->
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-gray-600" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Condition</p>
                                                    <p class="font-semibold text-gray-900">
                                                        {{ $instance->condition_notes ?: 'Good condition' }}</p>
                                                </div>
                                            </div>

                                            <!-- Status -->
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 {{ $instance->status === 'available' ? 'text-green-600' : 'text-red-600' }}"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Status</p>
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $instance->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ ucfirst($instance->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="px-6 pb-6 pt-2 flex gap-3">
                                            <a href="{{ route('books.instance', ['id' => $instance->id]) }}"
                                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2.5 px-4 rounded-lg transition-colors duration-200 text-center text-sm">
                                                View Details
                                            </a>
                                            @if ($instance->status === 'available')
                                                <a href="{{ route('books.instance.request', ['bookInstance' => $instance->id]) }}"
                                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-colors duration-200 text-center text-sm">
                                                    Request Book
                                                </a>
                                            @else
                                                <button disabled
                                                    class="flex-1 bg-gray-300 text-gray-500 font-semibold py-2.5 px-4 rounded-lg cursor-not-allowed text-center text-sm">
                                                    Not Available
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
