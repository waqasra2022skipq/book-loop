<section class="p-3 sm:p-4 md:p-6 space-y-4 sm:space-y-6 max-w-2xl mx-auto">
        <form wire:submit.prevent="submit" class="my-2 w-full space-y-4 sm:space-y-6">
            
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

            <!-- Address fields -->
            <div>
                <flux:input wire:model="city" :label="__('City')" type="text" autocomplete="address-level2" />
            </div>
            <div>
                <flux:input wire:model="address" :label="__('Address')" type="text" autocomplete="street-address" />
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end w-full">
                    <flux:button variant="primary" type="submit" class="w-full py-2.5 sm:py-3 text-sm sm:text-base">{{ __('Add Book') }}</flux:button>
                    <span wire:loading class="text-xs sm:text-sm text-gray-500 ml-2">Saving...</span> 
                </div>
            </div>
        </form>
</section>