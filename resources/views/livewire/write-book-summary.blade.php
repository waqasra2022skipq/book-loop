<div>
    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('message') }}</div>
    @endif
    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="block font-semibold mb-1">Summary</label>
            <textarea wire:model="summary" rows="6" class="w-full border rounded p-2" required></textarea>
            @error('summary') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block font-semibold mb-1">Rating (optional)</label>
            <select wire:model="rating" class="border rounded p-2">
                <option value="">No Rating</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
            @error('rating') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Summary</button>
    </form>
</div>
