<div class="p-4 border rounded bg-gray-100 mt-4">
    <h3 class="font-semibold mb-2">Edit Book Info</h3>

    <div class="space-y-3">
        <label>Status</label>
        <select wire:model="status" class="w-full border rounded p-2">
            <option value="available">Available</option>
            <option value="reading">Reading</option>
            <option value="reserved">Reserved</option>
        </select>

        <label>Condition Notes</label>
        <textarea wire:model="notes" class="w-full border rounded p-2"></textarea>

        <div class="flex gap-2">
            <button wire:click="update" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
        </div>
    </div>
</div>
