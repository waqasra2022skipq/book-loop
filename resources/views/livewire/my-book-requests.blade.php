<div>
    {{-- Notification for request status changes --}}
    <x-action-message class="me-3" on="acceptedRequest">
        {{ __('Request accepted.') }}
    </x-action-message>

    <x-action-message class="me-3" on="rejectedRequest">
        {{ __('Request rejected.') }}
    </x-action-message>

    <div class="flex flex-col sm:flex-row gap-2 mb-4">
        <button wire:click="setTab('pending')" class="flex-1 py-2 rounded 
            {{ $tab === 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700' }}">
            Pending
        </button>
        <button wire:click="setTab('accepted')" class="flex-1 py-2 rounded 
            {{ $tab === 'accepted' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700' }}">
            Approved
        </button>
        <button wire:click="setTab('rejected')" class="flex-1 py-2 rounded 
            {{ $tab === 'rejected' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700' }}">
            Rejected
        </button>
    </div>

    <div>
        @if($tab === 'pending')
            @foreach ($pendingRequests as $request)
                <div class="flex flex-col sm:flex-row items-start sm:items-center bg-white rounded shadow p-4 border mb-3">
                    <div class="w-full sm:w-20 h-28 flex-shrink-0 mb-2 sm:mb-0 sm:mr-4">
                        @if($request->book->cover_image)
                            <img src="{{ asset('storage/' . $request->book->cover_image) }}" alt="Book Cover" class="w-full h-full object-cover rounded">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded text-gray-400">No Image</div>
                        @endif
                    </div>
                    <div class="flex-1 w-full">
                        <div class="font-bold text-lg">{{ $request->book->title }}</div>
                        <div class="text-sm text-gray-600">Requested by: <span class="font-semibold">{{ $request->requester?->name ?? $request->name }}</span></div>
                        <div class="text-sm text-gray-600">Email: {{ $request->email }}</div>
                        <div class="text-sm text-gray-600">Message: <span class="italic">{{ $request->message }}</span></div>
                        <div class="mt-2">
                            <span class="font-semibold">Status:</span>
                            <span class="inline-block px-2 py-1 rounded bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-row sm:flex-col gap-2 mt-2 sm:mt-0 sm:ml-4">
                        <button wire:click="accept({{ $request->id }})" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Accept</button>
                        <button wire:click="reject({{ $request->id }})" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Reject</button>
                    </div>
                </div>
            @endforeach
            @if(count($pendingRequests) === 0)
                <div class="text-gray-500 text-center py-8">No pending requests.</div>
            @endif
        @elseif($tab === 'accepted')
            @foreach ($approvedRequests as $request)
                <div class="flex flex-col sm:flex-row items-start sm:items-center bg-white rounded shadow p-4 border mb-3">
                    <div class="w-full sm:w-20 h-28 flex-shrink-0 mb-2 sm:mb-0 sm:mr-4">
                        @if($request->book->cover_image)
                            <img src="{{ asset('storage/' . $request->book->cover_image) }}" alt="Book Cover" class="w-full h-full object-cover rounded">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded text-gray-400">No Image</div>
                        @endif
                    </div>
                    <div class="flex-1 w-full">
                        <div class="font-bold text-lg">{{ $request->book->title }}</div>
                        <div class="text-sm text-gray-600">Requested by: <span class="font-semibold">{{ $request->requester?->name ?? $request->name }}</span></div>
                        <div class="text-sm text-gray-600">Email: {{ $request->email }}</div>
                        <div class="text-sm text-gray-600">Message: <span class="italic">{{ $request->message }}</span></div>
                        <div class="mt-2">
                            <span class="font-semibold">Status:</span>
                            <span class="inline-block px-2 py-1 rounded bg-green-100 text-green-800">
                                Approved
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
            @if(count($approvedRequests) === 0)
                <div class="text-gray-500 text-center py-8">No approved requests.</div>
            @endif
        @elseif($tab === 'rejected')
            @foreach ($rejectedRequests as $request)
                <div class="flex flex-col sm:flex-row items-start sm:items-center bg-white rounded shadow p-4 border mb-3">
                    <div class="w-full sm:w-20 h-28 flex-shrink-0 mb-2 sm:mb-0 sm:mr-4">
                        @if($request->book->cover_image)
                            <img src="{{ asset('storage/' . $request->book->cover_image) }}" alt="Book Cover" class="w-full h-full object-cover rounded">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded text-gray-400">No Image</div>
                        @endif
                    </div>
                    <div class="flex-1 w-full">
                        <div class="font-bold text-lg">{{ $request->book->title }}</div>
                        <div class="text-sm text-gray-600">Requested by: <span class="font-semibold">{{ $request->requester?->name ?? $request->name }}</span></div>
                        <div class="text-sm text-gray-600">Email: {{ $request->email }}</div>
                        <div class="text-sm text-gray-600">Message: <span class="italic">{{ $request->message }}</span></div>
                        <div class="mt-2">
                            <span class="font-semibold">Status:</span>
                            <span class="inline-block px-2 py-1 rounded bg-red-100 text-red-800">
                                Rejected
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
            @if(count($rejectedRequests) === 0)
                <div class="text-gray-500 text-center py-8">No rejected requests.</div>
            @endif
        @endif
    </div>
</div>
