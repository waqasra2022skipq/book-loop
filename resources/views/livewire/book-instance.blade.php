<section class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <!-- Hero Section with Floating Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-32 h-32 bg-blue-200/30 rounded-full blur-xl"></div>
        <div class="absolute top-40 right-16 w-48 h-48 bg-purple-200/20 rounded-full blur-2xl"></div>
        <div class="absolute bottom-20 left-1/4 w-24 h-24 bg-indigo-300/25 rounded-full blur-lg"></div>
    </div>

    <div class="relative w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Book Header with Glass Effect -->
        <div class="mb-12">
            <div class="backdrop-blur-sm bg-white/80 rounded-3xl shadow-2xl border border-white/50 p-8 sm:p-12">
                <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-12">
                    <!-- Enhanced Book Cover -->
                    <div class="relative group">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-2xl blur opacity-75 group-hover:opacity-100 transition duration-500">
                        </div>
                        <div class="relative w-48 h-64 sm:w-56 sm:h-72 bg-white rounded-2xl shadow-2xl overflow-hidden">
                            @if ($bookInstance->book->cover_image)
                                <img src="{{ asset('storage/' . $bookInstance->book->cover_image) }}" alt="Book cover"
                                    class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-105" />
                            @else
                                <div
                                    class="flex items-center justify-center w-full h-full bg-gradient-to-br from-gray-100 to-gray-200">
                                    <div class="text-center text-gray-400">
                                        <svg class="w-16 h-16 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm font-medium">No Image</span>
                                    </div>
                                </div>
                            @endif
                            <!-- Reflection Effect -->
                            <div class="absolute inset-0 bg-gradient-to-t from-transparent via-transparent to-white/20">
                            </div>
                        </div>
                    </div>

                    <!-- Book Info -->
                    <div class="flex-1 text-center lg:text-left space-y-4">
                        <div class="space-y-2">
                            <h1
                                class="text-4xl sm:text-5xl lg:text-6xl font-black bg-gradient-to-r from-blue-700 via-purple-700 to-indigo-700 bg-clip-text text-transparent leading-tight">
                                {{ $bookInstance->book->title }}
                            </h1>
                            <h2 class="text-xl sm:text-2xl text-gray-600 font-medium">
                                By <span class="text-gray-800 font-semibold">{{ $bookInstance->book->author }}</span>
                            </h2>
                        </div>

                        <!-- Status Pills -->
                        <div class="flex flex-wrap justify-center lg:justify-start gap-3 mt-6">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-lg">
                                <div class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></div>
                                {{ ucfirst($bookInstance->status) }}
                            </span>
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-lg">
                                @if ($bookInstance->status === 'available')
                                    <a href="{{ route('books.instance.request', ['bookInstance' => $bookInstance->id]) }}"
                                        class="rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H10m2-12a9 9 0 00-9 9v0a9 9 0 009 9v0a9 9 0 009-9v0a9 9 0 00-9-9z">
                                            </path>
                                        </svg>
                                        Request Book
                                    </a>
                                @else
                                    <button disabled
                                        class="text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m0 0v2m0-2h2m-2 0H10m2-12a9 9 0 00-9 9v0a9 9 0 009 9v0a9 9 0 009-9v0a9 9 0 00-9-9z">
                                            </path>
                                        </svg>
                                        {{ ucfirst($bookInstance->status) }}
                                    </button>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Tabs -->
        <div x-data="{ tab: 'detail' }" class="space-y-8">
            <!-- Tab Navigation -->
            <div class="backdrop-blur-sm bg-white/70 rounded-2xl shadow-xl border border-white/50 p-2">
                <div class="flex flex-wrap justify-center gap-2">
                    <button @click="tab = 'detail'"
                        :class="tab === 'detail' ?
                            'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg scale-105' :
                            'text-gray-600 hover:text-gray-800 hover:bg-white/50'"
                        class="flex-1 sm:flex-none px-6 py-3 font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 min-w-max">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                    clip-rule="evenodd" />
                            </svg>
                            Details
                        </span>
                    </button>
                    <button @click="tab = 'summaries'"
                        :class="tab === 'summaries' ?
                            'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg scale-105' :
                            'text-gray-600 hover:text-gray-800 hover:bg-white/50'"
                        class="flex-1 sm:flex-none px-6 py-3 font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 min-w-max">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Summaries
                        </span>
                    </button>
                    <button @click="tab = 'owner'"
                        :class="tab === 'owner' ?
                            'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg scale-105' :
                            'text-gray-600 hover:text-gray-800 hover:bg-white/50'"
                        class="flex-1 sm:flex-none px-6 py-3 font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 min-w-max">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                            Owner
                        </span>
                    </button>
                </div>
            </div>

            <!-- Tab Panels -->
            <div
                class="backdrop-blur-sm bg-white/80 rounded-3xl shadow-2xl border border-white/50 p-8 sm:p-12 min-h-96">
                <!-- Book Details -->
                <div x-show="tab === 'detail'" x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="space-y-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Book Description</h3>
                    </div>

                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed text-lg">{{ $bookInstance->book->description }}</p>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500">Status</div>
                                <div class="text-lg font-semibold text-gray-800 capitalize">{{ $bookInstance->status }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500">Condition</div>
                                <div class="text-lg font-semibold text-gray-800">{{ $bookInstance->condition_notes }}
                                </div>
                            </div>
                        </div>

                        {{-- Address related info of the book instance --}}
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl flex items-center justify-center">
                                <flux:icon name="map-pin" class="w-6 h-6 text-white" />
                            </div>
                            <div class="text-lg font-semibold text-gray-800">{{ $bookInstance->city }},
                                {{ $bookInstance->address }}</div>
                        </div>

                        {{-- Show Book Ratings --}}
                        <div class="flex items-center gap-1">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl flex items-center justify-center">
                                <flux:icon.star variant="solid" class="text-yellow-500" />
                            </div>
                            <span
                                class="text-sm font-semibold">{{ $bookInstance->book->average_rating() ?? 0 }}/5</span>

                            {{-- add a button to switch to summaries tab --}}
                            <button @click="tab = 'summaries'" class="text-sm text-blue-500 hover:underline">
                                View Summaries
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Summaries -->
                <div x-show="tab === 'summaries'" x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="text-center py-12">
                    @livewire('book-summaries', ['bookId' => $bookInstance->book->id])
                </div>


                <!-- Owner Info -->
                <div x-show="tab === 'owner'" x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex items-center gap-3 mb-8">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Owner Information</h3>
                    </div>

                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-4 sm:p-8">
                        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
                            <div class="relative flex-shrink-0">
                                <div
                                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl sm:text-2xl shadow-lg">
                                    {{ strtoupper(substr($bookInstance->owner->name, 0, 1)) }}
                                </div>
                                <div
                                    class="absolute -bottom-1 -right-1 w-5 h-5 sm:w-6 sm:h-6 bg-green-500 rounded-full border-2 sm:border-4 border-white">
                                </div>
                            </div>
                            <div class="flex-1 space-y-2 text-center sm:text-left">
                                <div class="text-lg sm:text-xl font-bold text-gray-800">
                                    {{ $bookInstance->owner->name }}
                                </div>
                                <div class="text-sm sm:text-base text-gray-600 font-medium break-all sm:break-normal">
                                    {{ $bookInstance->owner->email }}</div>
                                <div
                                    class="flex items-center justify-center sm:justify-start gap-2 text-xs sm:text-sm text-gray-500">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Active member
                                </div>
                            </div>
                        </div>

                        <!-- View Profile Button -->
                        <div class="mt-6 flex justify-center sm:justify-end">
                            <a href="{{ route('users.profile', $bookInstance->owner->id) }}"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                View Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
