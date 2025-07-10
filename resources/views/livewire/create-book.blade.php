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
                <flux:radio.group wire:model="status" label="Avaiablity Status" class="flex flex-row gap-4">
                    <flux:radio value="reading" label="Reading"  class="flex items-center"/>
                    <flux:radio value="reserved" label="Reserved" class="flex items-center"/>
                    <flux:radio value="available" label="Available"  class="flex items-center" />

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