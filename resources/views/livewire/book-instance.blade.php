
<section class="w-full max-w-3xl mx-auto px-2 sm:px-4 md:px-0 py-8">
    <!-- Book Header -->
    <div class="flex flex-col sm:flex-row items-center gap-6 mb-8">
        <div class="w-32 h-44 bg-gray-100 rounded shadow flex items-center justify-center overflow-hidden">
            @if($bookInstance->book->cover_image)
                <img src="{{ asset('storage/' . $bookInstance->book->cover_image) }}" alt="Book cover" class="object-cover w-full h-full" />
            @else
                <span class="text-gray-400 text-sm">No Image</span>
            @endif
        </div>
        <div class="flex-1 text-center sm:text-left">
            <h1 class="text-2xl sm:text-3xl font-bold text-blue-700 mb-1">{{ $bookInstance->book->title }}</h1>
            <h2 class="text-lg text-gray-600 mb-2">By {{ $bookInstance->book->author }}</h2>
        </div>
    </div>

    <!-- Tabs -->
    <div x-data="{ tab: 'detail' }" class="">
        <div class="flex border-b mb-6">
            <button @click="tab = 'detail'" :class="tab === 'detail' ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500'" class="px-4 py-2 font-medium border-b-2 focus:outline-none transition">Book Detail</button>
            <button @click="tab = 'summaries'" :class="tab === 'summaries' ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500'" class="px-4 py-2 font-medium border-b-2 focus:outline-none transition">Book Summaries</button>
            <button @click="tab = 'request'" :class="tab === 'request' ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500'" class="px-4 py-2 font-medium border-b-2 focus:outline-none transition">Request the Book</button>
            <button @click="tab = 'owner'" :class="tab === 'owner' ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500'" class="px-4 py-2 font-medium border-b-2 focus:outline-none transition">Owner Info</button>
        </div>

        <!-- Tab Panels -->
        <div>
            <div x-show="tab === 'detail'" class="animate-fade-in">
                <h3 class="text-lg font-semibold mb-2">Description</h3>
                <p class="text-gray-700 mb-4">{{ $bookInstance->book->description }}</p>
                <div class="text-sm text-gray-500">Status: <span class="font-medium capitalize">{{ $bookInstance->status }}</span></div>
                <div class="text-sm text-gray-500">Condition: <span class="font-medium">{{ $bookInstance->condition_notes }}</span></div>
            </div>
            <div x-show="tab === 'summaries'" class="animate-fade-in">
                <h3 class="text-lg font-semibold mb-2">Book Summaries</h3>
                <p class="text-gray-600 italic">No summaries available yet.</p>
            </div>
            <div x-show="tab === 'request'" class="animate-fade-in">
                <h3 class="text-lg font-semibold mb-2">Request this Book</h3>
                @livewire('book-request', ['bookInstance' => $bookInstance])
            </div>
            <div x-show="tab === 'owner'" class="animate-fade-in">
                <h3 class="text-lg font-semibold mb-2">Owner Information</h3>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-lg">
                        {{ strtoupper(substr($bookInstance->owner->name,0,1)) }}
                    </div>
                    <div>
                        <div class="font-medium">{{ $bookInstance->owner->name }}</div>
                        <div class="text-xs text-gray-500">{{ $bookInstance->owner->email }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
