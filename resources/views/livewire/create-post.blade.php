<div class="bg-white dark:bg-zinc-900 rounded-lg p-3 sm:p-4 shadow border border-zinc-200 dark:border-zinc-800">
    <form wire:submit.prevent="submit" class="space-y-3">
        <div>
            <label for="body" class="sr-only">What are you reading?</label>
            <textarea id="body" wire:model.defer="body" rows="3" maxlength="1000" autocomplete="off"
                class="w-full rounded-md border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Share what you're reading today..."></textarea>
            @error('body')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-2">
            <label for="bookId" class="text-xs text-zinc-600 dark:text-zinc-400">Tag a book (optional)</label>
            <select id="bookId" wire:model.defer="bookId"
                class="flex-1 min-w-0 rounded-md border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-2 text-sm">
                <option value="">-- None --</option>
                @foreach (\App\Models\Book::orderBy('title')->limit(200)->get() as $book)
                    <option value="{{ $book->id }}">{{ $book->title }} — {{ $book->author }}</option>
                @endforeach
            </select>
            @error('bookId')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end">
            <button type="submit"
                class="px-4 py-2 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50">
                Post
            </button>
        </div>
    </form>
</div>
