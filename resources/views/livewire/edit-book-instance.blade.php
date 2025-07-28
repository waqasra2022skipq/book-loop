<section class="p-6 space-y-6">
    <h1 class="text-2xl font-bold mb-2">Edit Book Info</h1>

    <form wire:submit.prevent="update" class="space-y-6">
        <!-- Book Metadata -->
        <div>
            <flux:input wire:model="title" :label="__('Title')" type="text" required />
        </div>
        <div>
            <flux:input wire:model="author" :label="__('Author')" type="text" />
        </div>
        <div>
            <flux:input wire:model="isbn" :label="__('ISBN')" type="text" />
        </div>

        <!-- Image Upload -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
            @if ($currentImage)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $currentImage)}}" alt="Current Cover" class="h-32 rounded shadow">
                </div>
            @endif
            @if ($image)
                <div class="mb-2">
                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="h-32 rounded shadow">
                </div>
            @endif
            <input type="file" wire:model="image" accept="image/*" class="block w-full text-sm text-gray-500" />
            @error('image') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Instance Metadata -->
        <div>
            <flux:radio.group wire:model="status" label="Availability Status">
                <flux:radio value="available" label="Available" description="Available for others to request" />
                <flux:radio value="reading" label="Reading" description="You’re currently reading this" />
                <flux:radio value="reserved" label="Reserved" description="Not sharing for now" />
            </flux:radio.group>
        </div>
        <div>
            <flux:textarea wire:model="notes" label="Condition Notes"
                placeholder="Describe the book’s condition (optional)" />
        </div>
        <!-- Address fields -->
        <div>
            <flux:input wire:model="city" :label="__('City')" type="text" autocomplete="address-level2" />
        </div>
        <div>
            <flux:input wire:model="address" :label="__('Address')" type="text" autocomplete="street-address" />
        </div>
        <div class="flex justify-between items-center">
            <flux:button variant="primary" type="submit">{{ __('Update Book') }}</flux:button>
            <a href="{{ route('books.mybooks') }}" class="text-gray-500 hover:underline">Cancel</a>
        </div>
        <x-action-message class="me-3" on="bookUpdated">
            {{ __('Book updated successfully.') }}
        </x-action-message>
    </form>
</section>
