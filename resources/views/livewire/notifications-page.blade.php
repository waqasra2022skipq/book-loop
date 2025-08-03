<div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-2 mb-6">
        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <h1 class="text-2xl font-bold text-zinc-800 dark:text-zinc-100">Notifications</h1>
    </div>
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow divide-y divide-zinc-100 dark:divide-zinc-800">
        @forelse($notifications as $notification)
            <div class="flex flex-col gap-1 p-5 hover:bg-blue-50/60 dark:hover:bg-zinc-800/60 transition">
                <div class="flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full mr-2 {{ $notification->read_at ? 'bg-zinc-300' : 'bg-blue-500 animate-pulse' }}"></span>
                    <span class="text-base text-zinc-800 dark:text-zinc-100">{!! $notification->data['message'] ?? 'You have a new notification.' !!}</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-zinc-400 mt-1">
                    <span>{{ $notification->created_at->diffForHumans() }}</span>
                    @if($notification->read_at === null)
                        <button wire:click="markAsRead('{{ $notification->id }}')" class="ml-auto text-blue-500 hover:underline text-xs">Mark as read</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-zinc-400 text-base">No notifications yet.</div>
        @endforelse
    </div>
</div>
