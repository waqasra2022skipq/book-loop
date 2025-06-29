<section class="max-w-2xl mx-auto p-6 space-y-6">
    <h1 class="text-2xl font-bold mb-2">Edit Book Info</h1>

    @if (session()->has('message'))
        <p class="text-green-600 font-medium">{{ session('message') }}</p>
    @endif

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

        <div class="flex justify-between items-center">
            <flux:button variant="primary" type="submit">{{ __('Update Book') }}</flux:button>
            <a href="{{ route('books.mybooks') }}" class="text-gray-500 hover:underline">Cancel</a>
        </div>
    </form>
</section>
