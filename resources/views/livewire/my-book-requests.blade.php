<div>
    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('message') }}</div>
    @endif

    <div class="space-y-4">
        @forelse ($requests as $request)
            <div class="flex items-center bg-white rounded shadow p-4 border">
                <div class="w-20 h-28 flex-shrink-0 mr-4">
                    @if($request->book->cover_image)
                        <img src="{{ asset('storage/' . $request->book->cover_image) }}" alt="Book Cover" class="w-full h-full object-cover rounded">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded text-gray-400">No Image</div>
                    @endif
                </div>
                <div class="flex-1">
                    <div class="font-bold text-lg">{{ $request->book->title }}</div>
                    <div class="text-sm text-gray-600">Requested by: <span class="font-semibold">{{ $request->requester?->name ?? $request->name }}</span></div>
                    <div class="text-sm text-gray-600">Email: {{ $request->email }}</div>
                    <div class="text-sm text-gray-600">Message: <span class="italic">{{ $request->message }}</span></div>
                    <div class="mt-2">
                        <span class="font-semibold">Status:</span>
                        <span class="inline-block px-2 py-1 rounded 
                            @if($request->status === 'approved') bg-green-100 text-green-800
                            @elseif($request->status === 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($request->status ?? 'pending') }}
                        </span>
                    </div>
                </div>
                <div class="ml-4 flex flex-col gap-2">
                    @if($request->status === 'pending')
                        <button wire:click="accept({{ $request->id }})" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Accept</button>
                        <button wire:click="reject({{ $request->id }})" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Reject</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-gray-500 text-center py-8">No requests received yet.</div>
        @endforelse
    </div>
</div>
