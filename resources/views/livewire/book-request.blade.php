<div>
    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif
    <form wire:submit.prevent="submit" class="space-y-4 max-w-md mx-auto">
        <div>
            <flux:input wire:model="name" label="Name"/>
        </div>
        <div>
            <flux:input wire:model.blur="email" label="Email" type="email"/>
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
</div>