{{-- resources/views/livewire/book-card.blade.php --}}
<div
    class="group relative bg-white rounded-2xl shadow-lg border border-blue-100 hover:shadow-xl hover:border-blue-300 transition-all duration-300 overflow-hidden flex flex-col">
    <!-- Book Cover -->
    <a href="{{ route('books.instance', $instance->id) }}" class="relative overflow-hidden block rounded-t-2xl">
        @if ($instance->book->cover_image)
            <img src="{{ asset('storage/' . $instance->book->cover_image) }}"
                class="w-full h-48 sm:h-56 object-cover group-hover:scale-105 transition-transform duration-300 rounded-t-2xl"
                alt="{{ $instance->book->title }} cover">
        @else
            <div
                class="w-full h-48 sm:h-56 bg-gradient-to-br from-blue-50 to-purple-50 flex items-center justify-center rounded-t-2xl">
                <svg class="w-12 sm:w-16 h-12 sm:h-16 text-blue-300" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
            </div>
        @endif

        <!-- Status Badge -->
        <div class="absolute top-2 sm:top-3 right-2 sm:right-3">
            <span
                class="px-2 sm:px-3 py-1 text-xs font-semibold rounded-full shadow-sm border 
                {{ $instance->status === 'available'
                    ? 'bg-green-100 text-green-700 border-green-200'
                    : ($instance->status === 'requested'
                        ? 'bg-yellow-100 text-yellow-700 border-yellow-200'
                        : 'bg-gray-100 text-gray-700 border-gray-200') }}">
                {{ ucfirst($instance->status) }}
            </span>
        </div>
    </a>

    <!-- Book Details -->
    <div class="p-4 sm:p-6 space-y-3 sm:space-y-4 flex-1 flex flex-col">
        <!-- Title -->
        <a href="{{ route('books.instance', $instance->id) }}">
            <h3
                class="text-lg sm:text-xl font-bold text-blue-800 line-clamp-2 group-hover:text-blue-600 transition-colors">
                {{ $instance->book->title }}
            </h3>
        </a>

        <!-- Author -->
        <p class="text-sm font-medium text-gray-500 flex items-center gap-1">
            <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            {{ $instance->book->author }}
        </p>

        {{-- Show ratings of the book with stars representing the ratings --}}
        <div class="flex items-center gap-1">

            <flux:icon.star variant="solid" class="text-yellow-500" />
            <span class="text-sm font-semibold">{{ $instance->book->average_rating() ?? 0 }}/5</span>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-2 sm:gap-3 pt-3 sm:pt-4 border-t border-blue-100 mt-auto">
            <a href="{{ route('books.instance', $instance->id) }}"
                class="flex-1 text-center px-4 py-2 text-sm font-semibold text-blue-700 bg-blue-50 rounded-lg flex items-center justify-center gap-2 hover:bg-blue-100 focus:bg-blue-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                    </path>
                </svg>
                View Details
            </a>
            @if ($instance->status === 'available')
                <a href="{{ route('books.instance.request', ['bookInstance' => $instance->id]) }}"
                    class="flex-1 text-center px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center gap-2 hover:from-blue-700 hover:to-purple-700 focus:ring-2 focus:ring-blue-300 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Request Book
                </a>
            @else
                <button disabled
                    class="flex-1 text-center px-4 py-2 text-sm font-semibold text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m0 0v2m0-2h2m-2 0H10m2-12a9 9 0 00-9 9v0a9 9 0 009 9v0a9 9 0 009-9v0a9 9 0 00-9-9z">
                        </path>
                    </svg>
                    {{ ucfirst($instance->status) }}
                </button>
            @endif
        </div>
    </div>
</div>
