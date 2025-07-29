<div>
    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    @auth
        @if ($existingRequest)
            <div class="max-w-md mx-auto bg-white rounded shadow p-4 border mb-4">
                <div class="font-bold text-lg mb-2">Your Book Request</div>
                <div><span class="font-semibold">Name:</span> {{ $existingRequest->name }}</div>
                <div><span class="font-semibold">Email:</span> {{ $existingRequest->email }}</div>
                <div><span class="font-semibold">Address:</span> {{ $existingRequest->address }}</div>
                <div><span class="font-semibold">Message:</span> {{ $existingRequest->message }}</div>
                <div class="mt-2">
                    <span class="font-semibold">Status:</span>
                    <span class="inline-block px-2 py-1 rounded 
                        @if($existingRequest->status === 'accepted') bg-green-100 text-green-800
                        @elseif($existingRequest->status === 'rejected') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($existingRequest->status ?? 'pending') }}
                    </span>
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
                    class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition w-full"
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
                class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition w-full"
                @if ($errors->any()) disabled class="opacity-50 cursor-not-allowed" @endif>
                Send Request
            </button>
            <span wire:loading>Saving...</span> 
        </form>
    @endauth
</div>