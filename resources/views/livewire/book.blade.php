<section class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="relative w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <!-- Header -->
        <div class="mb-8 sm:mb-12">
            <div class="backdrop-blur-sm bg-white/80 rounded-3xl shadow-2xl border border-white/50 p-6 sm:p-10">
                <div class="flex flex-col lg:flex-row items-center gap-6 lg:gap-10">
                    <!-- Cover -->
                    <div class="relative group">
                        <div class="relative w-36 h-52 sm:w-44 sm:h-60 bg-white rounded-2xl shadow-2xl overflow-hidden">
                            @if ($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Book cover"
                                    class="object-cover w-full h-full" />
                            @else
                                <div
                                    class="flex items-center justify-center w-full h-full bg-gradient-to-br from-gray-100 to-gray-200">
                                    <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="flex-1 text-center lg:text-left space-y-2">
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-blue-800 leading-tight">
                            {{ $book->title }}
                        </h1>
                        <h2 class="text-lg sm:text-xl text-gray-600 font-medium">
                            By <span class="text-gray-800 font-semibold">{{ $book->author }}</span>
                        </h2>
                        <div class="flex flex-wrap justify-center lg:justify-start gap-3 mt-3">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $book->genre->name ?? 'General' }}
                            </span>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                Rating: {{ $book->average_rating() ?? 0 }}/5 ({{ $book->total_ratings() }})
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div x-data="{ tab: 'details' }" class="space-y-6">
            <div class="backdrop-blur-sm bg-white/70 rounded-2xl shadow-xl border border-white/50 p-2">
                <div class="flex flex-wrap justify-center gap-2">
                    <button @click="tab = 'details'"
                        :class="tab === 'details' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' :
                            'text-gray-600 hover:text-gray-800 hover:bg-white/50'"
                        class="flex-1 sm:flex-none px-5 py-2 font-semibold rounded-xl transition-all">Details</button>
                    <button @click="tab = 'summaries'"
                        :class="tab === 'summaries' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' :
                            'text-gray-600 hover:text-gray-800 hover:bg-white/50'"
                        class="flex-1 sm:flex-none px-5 py-2 font-semibold rounded-xl transition-all">Summaries</button>
                    <button @click="tab = 'instances'"
                        :class="tab === 'instances' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' :
                            'text-gray-600 hover:text-gray-800 hover:bg-white/50'"
                        class="flex-1 sm:flex-none px-5 py-2 font-semibold rounded-xl transition-all">Available
                        Copies</button>
                </div>
            </div>

            <div class="backdrop-blur-sm bg-white/80 rounded-3xl shadow-2xl border border-white/50 p-6 sm:p-10">
                <div x-show="tab === 'details'" class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-800">Description</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $book->description }}</p>
                </div>

                <div x-show="tab === 'summaries'">
                    @livewire('book-summaries', ['bookId' => $book->id])
                </div>

                <div x-show="tab === 'instances'" class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-800">Available Copies</h3>
                    @if ($instances->isEmpty())
                        <p class="text-gray-600">No copies available right now.</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($instances as $instance)
                                <div class="p-4 rounded-xl border bg-white flex items-start gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-900 truncate">Owner:
                                            {{ $instance->owner->name }}</div>
                                        <div class="text-sm text-gray-600">Condition:
                                            {{ $instance->condition_notes ?: '—' }}</div>
                                        <div class="text-sm text-gray-600">City: {{ $instance->city }}</div>
                                    </div>
                                    <a href="{{ route('books.instance', ['id' => $book->id, 'bookInstance' => $instance->id]) }}"
                                        class="text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg">View</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
