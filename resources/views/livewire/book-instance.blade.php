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
                                @if ($bookInstance->book->cover_image)
                                    <img src="{{ asset('storage/' . $bookInstance->book->cover_image) }}"
                                        alt="{{ $bookInstance->book->title }} cover"
                                        class="object-cover w-full h-full" />
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
                            <!-- Breadcrumb -->
                            <div class="flex items-center text-sm text-gray-600 mb-4">
                                <a href="{{ route('books.show', ['book' => $bookInstance->book->slug]) }}"
                                    class="hover:text-blue-600 transition-colors">{{ $bookInstance->book->title }}</a>
                                <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-900">Copy by {{ $bookInstance->owner->name }}</span>
                            </div>

                            <div>
                                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-2">
                                    {{ $bookInstance->book->title }}
                                </h1>
                                <p class="text-lg sm:text-xl text-gray-600 font-medium mb-4">
                                    by <span
                                        class="text-gray-800 font-semibold">{{ $bookInstance->book->author }}</span>
                                </p>
                            </div>

                            <!-- Enhanced Badges -->
                            <div class="flex flex-wrap gap-3">
                                <!-- Status Badge -->
                                <div
                                    class="flex items-center gap-2 {{ $bookInstance->status === 'available' ? 'bg-green-50' : 'bg-gray-50' }} px-4 py-2 rounded-full">
                                    <div
                                        class="w-2 h-2 {{ $bookInstance->status === 'available' ? 'bg-green-500' : 'bg-gray-500' }} rounded-full {{ $bookInstance->status === 'available' ? 'animate-pulse' : '' }}">
                                    </div>
                                    <span
                                        class="text-sm font-semibold {{ $bookInstance->status === 'available' ? 'text-green-700' : 'text-gray-700' }}">{{ ucfirst($bookInstance->status) }}</span>
                                </div>

                                <!-- Owner Badge -->
                                <div class="flex items-center gap-2 bg-blue-50 px-4 py-2 rounded-full">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-semibold text-blue-700">Owned by
                                        {{ $bookInstance->owner->name }}</span>
                                </div>

                                <!-- Location Badge -->
                                <div class="flex items-center gap-2 bg-purple-50 px-4 py-2 rounded-full">
                                    <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span
                                        class="text-sm font-semibold text-purple-700">{{ $bookInstance->city }}</span>
                                </div>

                                <!-- Price Badge -->
                                @if ($bookInstance->price)
                                    <div class="flex items-center gap-2 bg-green-50 px-4 py-2 rounded-full">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <span class="text-sm font-semibold text-green-700">${{ number_format($bookInstance->price, 2) }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Button -->
                            @if ($bookInstance->status === 'available')
                                <div class="pt-4">
                                    <a href="{{ route('books.instance.request', ['bookInstance' => $bookInstance->id]) }}"
                                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors duration-200 shadow-lg hover:shadow-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Request this Book
                                    </a>
                                </div>
                            @else
                                <div class="pt-4">
                                    <div
                                        class="inline-flex items-center gap-2 bg-gray-100 text-gray-600 font-semibold px-6 py-3 rounded-xl cursor-not-allowed">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                                            </path>
                                        </svg>
                                        Not Available
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clean Tabs -->
        <div x-data="{ tab: 'detail' }" class="space-y-6">
            <!-- Modern Tab Navigation -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                <div class="flex p-1">
                    <button @click="tab = 'detail'"
                        :class="tab === 'detail' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:text-gray-900'"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-3 font-semibold rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="hidden sm:inline">Details</span>
                    </button>
                    <button @click="tab = 'reviews'"
                        :class="tab === 'reviews' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:text-gray-900'"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-3 font-semibold rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                        <span class="hidden sm:inline">Reviews</span>
                    </button>
                    <button @click="tab = 'owner'"
                        :class="tab === 'owner' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:text-gray-900'"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-3 font-semibold rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="hidden sm:inline">Owner</span>
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 sm:p-8 min-h-96">
                <!-- Book Details -->
                <div x-show="tab === 'detail'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        Description
                    </h3>

                    <div class="prose max-w-none mb-8">
                        <p class="text-gray-700 leading-relaxed">{{ $bookInstance->book->description }}</p>
                    </div>

                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-green-50 rounded-xl p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Status</p>
                                    <p class="font-semibold text-gray-900 capitalize">{{ $bookInstance->status }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-amber-50 rounded-xl p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Condition</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $bookInstance->condition_notes ?: 'Good' }}</p>
                                </div>
                            </div>
                        </div>

                        @if ($bookInstance->price)
                            <div class="bg-green-50 rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Price</p>
                                        <p class="font-semibold text-gray-900">
                                            ${{ number_format($bookInstance->price, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="bg-purple-50 rounded-xl p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Location</p>
                                    <p class="font-semibold text-gray-900">{{ $bookInstance->city }},
                                        {{ $bookInstance->address }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 rounded-xl p-4 lg:col-span-2">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Rating</p>
                                        <p class="font-semibold text-gray-900">
                                            {{ $bookInstance->book->average_rating() ?? 'N/A' }}/5</p>
                                    </div>
                                    <button @click="tab = 'reviews'"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors font-medium text-sm">
                                        View Reviews
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                <div x-show="tab === 'reviews'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @livewire('book-summaries', ['book' => $bookInstance->book])
                </div>

                <!-- Owner Info -->
                <div x-show="tab === 'owner'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

                    <div class="text-center space-y-8">
                        <!-- Profile Picture Section -->
                        <div class="flex justify-center">
                            <div class="relative">
                                <div
                                    class="w-32 h-32 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-xl">
                                    <span class="text-white font-bold text-4xl">
                                        {{ strtoupper(substr($bookInstance->owner->name, 0, 1)) }}
                                    </span>
                                </div>
                                <!-- Online Status Badge -->
                                <div
                                    class="absolute bottom-2 right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Owner Information -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $bookInstance->owner->name }}
                                </h3>
                                <p class="text-lg text-gray-600">{{ $bookInstance->owner->email }}</p>
                            </div>

                            <!-- Status Badge -->
                            <div class="flex justify-center">
                                <div
                                    class="inline-flex items-center gap-2 bg-green-100 text-green-800 px-4 py-2 rounded-full font-medium">
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                    Active Book Owner
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-4">
                            <a href="{{ route('users.profile', $bookInstance->owner->id) }}"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-4 rounded-xl transition-all duration-200 text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                View Full Profile
                            </a>

                            <!-- Contact Information Card -->
                            <div class="max-w-sm mx-auto bg-gray-50 rounded-xl p-6 border border-gray-200">
                                <h4 class="font-semibold text-gray-900 mb-3 text-center">Contact Information</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-center gap-3">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span
                                            class="text-gray-700 text-sm break-all">{{ $bookInstance->owner->email }}</span>
                                    </div>

                                    @if ($bookInstance->city)
                                        <div class="flex items-center justify-center gap-3">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="text-gray-700 text-sm">{{ $bookInstance->city }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
