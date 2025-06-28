<div>
    <section class="w-full">
    <h1 class="text-2xl font-semibold mb-4">My Books</h1>

    @if($books->isEmpty())
        <p class="text-gray-500">You haven't added any books yet.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($books as $instance)
                <div class="p-4 border rounded-lg shadow bg-white">
                    @if($instance->book->cover_image)
                        <img src="{{ asset('storage/' . $instance->book->cover_image) }}" class="w-full h-48 object-cover rounded mb-3" alt="Book cover">
                    @else
                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400 rounded mb-3">
                            No Image
                        </div>
                    @endif
                    

                    <h2 class="text-lg font-semibold">{{ $instance->book->title }}</h2>
                    <p class="text-sm text-gray-600 mb-1">By {{ $instance->book->author }}</p>
                    <p class="text-sm text-gray-500 mb-2">Status: <span class="font-medium capitalize">{{ $instance->status }}</span></p>
                    <p class="text-sm text-gray-700">{{ $instance->condition_notes }}</p>

                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('books.editBook', $instance->book->id) }}" class="text-blue-600 hover:underline">Edit Book</a>
                        <button wire:click="delete({{ $instance->id }})" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>

</div>
