
<section class="p-6 space-y-6">

    @if($books->isEmpty())
        <p class="text-gray-500 text-base sm:text-lg">You haven't added any books yet.</p>
    @else
        <div class="flex flex-col gap-4 sm:grid sm:grid-cols-2 lg:grid-cols-3 sm:gap-6">
            @foreach($books as $instance)
                <div class="flex flex-col sm:block p-3 sm:p-4 border rounded-lg shadow bg-white">
                    @if($instance->book->cover_image)
                        <img src="{{ asset('storage/' . $instance->book->cover_image) }}" class="w-full h-40 sm:h-48 object-cover rounded mb-2 sm:mb-3" alt="Book cover">
                    @else
                        <div class="w-full h-40 sm:h-48 bg-gray-100 flex items-center justify-center text-gray-400 rounded mb-2 sm:mb-3">
                            No Image
                        </div>
                    @endif

                    <h2 class="text-base sm:text-lg font-semibold leading-tight">{{ $instance->book->title }}</h2>
                    <p class="text-xs sm:text-sm text-gray-600 mb-0.5 sm:mb-1">By {{ $instance->book->author }}</p>
                    <p class="text-xs sm:text-sm text-gray-500 mb-1 sm:mb-2">Status: <span class="font-medium capitalize">{{ $instance->status }}</span></p>
                    <p class="text-xs sm:text-sm text-gray-700">{{ $instance->condition_notes }}</p>

                    <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 mt-2">
                        <a href="{{ route('books.editBook', $instance->book->id) }}" class="text-blue-600 hover:underline text-sm">Edit Book</a>
                        <a href="{{ route('books.summary.write', $instance->book->id) }}" class="text-green-600 hover:underline text-sm">Write Summary</a>
                        <button wire:click="delete({{ $instance->id }})" class="text-red-600 hover:underline text-sm text-left sm:text-center" wire:confirm="Are you sure you want to delete this Book">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>
        <x-action-message class="me-3 text-green-600" on="book-deleted">
                {{ __('Book deleted successfully.') }}
        </x-action-message>
    @endif
</section>

