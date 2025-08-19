<div class="relative">
    <button wire:click="toggleLike"
        class="flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium rounded-lg transition-all duration-200 w-full
            {{ $userHasLiked ? 'text-blue-600 bg-blue-50 hover:bg-blue-100' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}"
        @if (!auth()->check()) onclick="window.location.href='{{ route('login') }}'" @endif
        wire:loading.attr="disabled">

        {{-- Heart icon --}}
        <svg class="w-4 h-4 mr-1 sm:mr-2 transition-colors duration-200"
            fill="{{ $userHasLiked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
            wire:loading.class="opacity-50">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
            </path>
        </svg>

        <span wire:loading.class="opacity-50">{{ $userHasLiked ? 'Liked' : 'Like' }}</span>

        @if ($likesCount > 0)
            <span class="ml-1" wire:loading.class="opacity-50">({{ $likesCount }})</span>
        @endif
    </button>

    {{-- Loading State Overlay --}}
    <div wire:loading wire:target="toggleLike"
        class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 rounded-lg">
        <svg class="animate-spin w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
            </circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
    </div>
</div>
