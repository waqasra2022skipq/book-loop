<div class="px-3 sm:px-4 py-4 sm:py-6">
    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded text-sm">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded text-sm">{{ session('error') }}</div>
    @endif

    @auth
        @if ($existingRequest)
            <div class="max-w-md mx-auto bg-white rounded-lg shadow p-4 border mb-4">
                <div class="font-bold text-lg mb-3">Your Book Request</div>
                <div class="space-y-2 text-sm">
                    <div><span class="font-semibold">Name:</span> {{ $existingRequest->name }}</div>
                    <div><span class="font-semibold">Email:</span> {{ $existingRequest->email }}</div>
                    <div><span class="font-semibold">Address:</span> {{ $existingRequest->address }}</div>
                    <div><span class="font-semibold">Message:</span> {{ $existingRequest->message }}</div>
                    <div class="mt-3">
                        <span class="font-semibold">Status:</span>
                        <span class="inline-block px-2 py-1 rounded text-xs
                            @if($existingRequest->status === 'accepted') bg-green-100 text-green-800
                            @elseif($existingRequest->status === 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($existingRequest->status ?? 'pending') }}
                        </span>
                    </div>
                </div>
            </div>
        @else
            <form wire:submit.prevent="submit" class="space-y-4 max-w-md mx-auto">
                <div>
                    <flux:input wire:model="name" label="Name"/>
                </div>
                <div>
                    <flux:input wire:model.blur="email" label="Email" type="email"/>
                </div>
                <div>
                    <flux:input wire:model="phone" label="Phone Number" type="text" autocomplete="tel"/>
                </div>
                <div>
                    <flux:input wire:model="address" label="Address"/>
                </div>
                <div>
                    <flux:textarea wire:model="message" label="Message" rows="3"/>
                </div>
                <button type="submit"
                    class="px-4 sm:px-5 py-2.5 sm:py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition w-full text-sm sm:text-base font-medium"
                    @if ($errors->any()) disabled class="opacity-50 cursor-not-allowed" @endif>
                    Send Request
                </button>
            </form>
        @endif
    @else
        {{-- Always show form for guests --}}
        <form wire:submit.prevent="submit" class="space-y-4 max-w-md mx-auto">
            <div>
                <flux:input wire:model="name" label="Name"/>
            </div>
            <div>
                <flux:input wire:model.blur="email" label="Email" type="email"/>
            </div>
            <div>
                <flux:input wire:model="phone" label="Phone Number" type="text" autocomplete="tel"/>
            </div>
            <div>
                <flux:input wire:model="address" label="Address"/>
            </div>
            <div>
                <flux:textarea wire:model="message" label="Message" rows="3"/>
            </div>
            <button type="submit"
                class="px-4 sm:px-5 py-2.5 sm:py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition w-full text-sm sm:text-base font-medium"
                @if ($errors->any()) disabled class="opacity-50 cursor-not-allowed" @endif>
                Send Request
            </button>
            <span wire:loading class="text-xs sm:text-sm text-gray-500 text-center block mt-2">Saving...</span> 
        </form>
    @endauth
</div>