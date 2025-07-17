<section class="w-full">
    <x-layouts.books.layout :heading="__('')" :subheading="__('')">
        <h1 class="text-2xl font-semibold mb-4">{{ __('Add a Book') }}</h1>
        <form wire:submit.prevent="submit" class="my-2 w-full space-y-6">
            <!-- Book details (auto-filled if found) -->
            <div>
                <flux:input wire:model="title" :label="__('Title')" type="text" required autocomplete="title" />
            </div>
            <div>
                <flux:input wire:model="author" :label="__('Author')" type="text" autocomplete="author" />
            </div>
            <div>
                <flux:input wire:model="isbn" :label="__('ISBN')" type="text" autocomplete="isbn" />
            </div>

            <!-- Status -->
            <div>
                <flux:radio.group wire:model="status" label="Availability Status">
                    <flux:radio value="available" label="Available" description="Available for others to request" />
                    <flux:radio value="reading" label="Reading" description="You’re currently reading this" />
                    <flux:radio value="reserved" label="Reserved" description="Not sharing for now" />
                </flux:radio.group>
            </div>

            <!-- Notes -->
            <div>
                <flux:textarea wire:model="notes"
                    label="Condition notes"
                    placeholder="No lettuce, tomato, or onion..."
                />
            </div>

            <!-- cover_image upload -->
            <div>
                <flux:input type="file" wire:model="cover_image" label="Cover Image (Optional)"/>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end w-full">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Add Book') }}</flux:button>
                </div>
                @if (session()->has('message'))
                    <span class="text-green-600 font-medium">{{ session('message') }}</span>
                @endif
            </div>
        </form>
    </x-layouts.books.layout>
</section>