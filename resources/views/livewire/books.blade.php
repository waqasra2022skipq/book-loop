<section class="w-full px-2 sm:px-4 md:px-8 py-6">
    <h1 class="text-2xl sm:text-3xl font-bold mb-6 text-blue-700 text-center">All Books</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($instances as $instance)
            <div class="bg-white rounded-xl shadow-md border border-zinc-100 hover:shadow-lg transition flex flex-col p-5">
                <h3 class="text-lg font-semibold text-gray-800 mb-2 truncate">{{ $instance->book->title }}</h3>
                <p class="text-sm text-gray-600 mb-3 line-clamp-3">{{ $instance->book->description }}</p>
                <p class="text-xs text-gray-500 mb-4">By {{ $instance->book->author }}</p>
                <p class="text-xs text-gray-500 mb-4">Status: <span class="font-medium capitalize">{{ $instance->status }}</span></p>
                @if($instance->book->cover_image)
                    <img src="{{ asset('storage/' . $instance->book->cover_image) }}" class="w-full h-40 object-cover rounded mb-3" alt="Book cover">
                @else
                    <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400 rounded mb-3">
                        No Image
                    </div>
                @endif
                <p class="text-xs text-gray-500 mb-3">{{ $instance->condition_notes }}</p>
                <a href="{{ route('books.instance', $instance->id) }}" class="mt-auto inline-block text-blue-600 hover:underline text-sm font-medium">View Details</a>
                <a href="{{ route('books.instance.request', ['bookInstance' => $instance->id]) }}" class="inline-block mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm font-medium">
                    Request Book
                </a>
            </div>
        @endforeach
    </div>
</section>
