{{-- resources/views/livewire/book-card.blade.php --}}
<div class="group relative bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-2xl hover:border-blue-200 transition-all duration-300 overflow-hidden">
    <!-- Book Cover -->
    <div class="relative overflow-hidden">
        @if($instance->book->cover_image)
            <img src="{{ asset('storage/' . $instance->book->cover_image) }}" 
                 class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300" 
                 alt="{{ $instance->book->title }} cover">
        @else
            <div class="w-full h-56 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        @endif
        
        <!-- Status Badge -->
        <div class="absolute top-3 right-3">
            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                {{ $instance->status === 'available' ? 'bg-green-100 text-green-700' : 
                   ($instance->status === 'requested' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                {{ ucfirst($instance->status) }}
            </span>
        </div>
    </div>

    <!-- Book Details -->
    <div class="p-6 space-y-4">
        <!-- Title -->
        <h3 class="text-xl font-bold text-gray-900 line-clamp-2 group-hover:text-blue-600 transition-colors">
            {{ $instance->book->title }}
        </h3>

        <!-- Author -->
        <p class="text-sm font-medium text-gray-600 flex items-center">
            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            {{ $instance->book->author }}
        </p>

        <!-- Description -->
        <p class="text-sm text-gray-600 line-clamp-3 leading-relaxed">
            {{ $instance->book->description }}
        </p>

        <!-- Condition Notes -->
        @if($instance->condition_notes)
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                <p class="text-xs text-amber-800 font-medium mb-1">Condition Notes:</p>
                <p class="text-xs text-amber-700">{{ $instance->condition_notes }}</p>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('books.instance', $instance->id) }}" 
               class="flex-1 text-center px-4 py-2.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200 flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                View Details
            </a>
            
            @if($instance->status === 'available')
                <a href="{{ route('books.instance.request', ['bookInstance' => $instance->id]) }}" 
                   class="flex-1 text-center px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Request Book
                </a>
            @else
                <button disabled 
                        class="flex-1 text-center px-4 py-2.5 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m2-12a9 9 0 00-9 9v0a9 9 0 009 9v0a9 9 0 009-9v0a9 9 0 00-9-9z"></path>
                    </svg>
                    {{ ucfirst($instance->status) }}
                </button>
            @endif
        </div>
    </div>
</div>