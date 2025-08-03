<flux:dropdown position="top" align="start" class="relative  md:block">
    <button
        @click="open = !open"
        class="relative flex items-center justify-center w-10 h-10 rounded-full bg-white shadow hover:bg-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
        aria-label="Notifications"
    >
        <svg class="w-6 h-6 text-zinc-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if(auth()->user() && auth()->user()->unreadNotifications->count())
            <span class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full animate-pulse">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </button>

    <flux:menu>
   
        @forelse($notifications as $notification)
            <flux:menu.item variant="danger" icon="trash"><li class="px-6 py-4 hover:bg-zinc-50 flex flex-col gap-2">
                <div class="text-sm text-zinc-800 leading-relaxed">{!! $notification->data['message'] ?? 'You have a new notification.' !!}</div>
                <div class="text-xs text-zinc-400 flex items-center justify-between">
                    <span>{{ $notification->created_at->diffForHumans() }}</span>
                    @if($notification->read_at === null)
                        <button wire:click="markAsRead('{{ $notification->id }}')" class="text-blue-500 hover:underline text-xs font-medium">Mark as read</button>
                    @endif
                </div>
            </li></flux:menu.item>
            <flux:menu.separator />

            
        @empty
            <flux:menu.item icon="bell">
                <li class="px-6 py-4 text-sm text-zinc-400 text-center">No notifications.</li>
            </flux:menu.item>
        @endforelse
    </flux:menu>
</flux:dropdown>