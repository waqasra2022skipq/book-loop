
<div>
    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <form wire:submit.prevent="submit" class="space-y-4 max-w-md mx-auto">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" class="w-full border rounded p-2" required wire:model.defer="name">
            @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" class="w-full border rounded p-2" required wire:model.defer="email">
            @error('email') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
            <input type="text" name="address" class="w-full border rounded p-2" required wire:model.defer="address">
            @error('address') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
            <textarea name="message" class="w-full border rounded p-2" rows="3" required wire:model.defer="message"></textarea>
            @error('message') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition w-full">Send Request</button>
    </form>
</div>
