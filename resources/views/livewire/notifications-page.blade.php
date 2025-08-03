<div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <h1 class="text-3xl font-semibold text-zinc-900 dark:text-zinc-100">Notifications</h1>
        </div>
        <button wire:click="markAllAsRead" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Mark all as read</button>
    </div>

    <!-- Notifications -->
    <div class="space-y-6">
        @php
            $groupedNotifications = $notifications->groupBy(function ($notification) {
                $date = $notification->created_at;
                if ($date->isToday()) {
                    return 'Today';
                } elseif ($date->isYesterday()) {
                    return 'Yesterday';
                } else {
                    return $date->format('F j, Y');
                }
            });
        @endphp

        @forelse($groupedNotifications as $date => $notifications)
            <div>
                <h2 class="text-lg font-medium text-zinc-700 dark:text-zinc-300 mb-3">{{ $date }}</h2>
                <div class="space-y-3">
                    @foreach($notifications as $notification)
                        <div class="flex items-start gap-4 p-4 bg-white dark:bg-zinc-800 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                            <!-- Notification Dot -->
                            <span class="inline-block w-3 h-3 rounded-full mt-1.5 {{ $notification->read_at ? 'bg-zinc-300' : 'bg-blue-500 animate-pulse' }}"></span>
                            <!-- Content -->
                            <div class="flex-1">
                                <p class="text-base text-zinc-900 dark:text-zinc-100">{!! $notification->data['message'] ?? 'You have a new notification.' !!}</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ $notification->created_at->format('h:i A') }}</span>
                                    @if($notification->read_at === null)
                                        <button wire:click="markAsRead('{{ $notification->id }}')" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Mark as read</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="p-8 text-center bg-white dark:bg-zinc-800 rounded-lg shadow-sm">
                <p class="text-base text-zinc-500 dark:text-zinc-400">No notifications yet.</p>
            </div>
        @endforelse
    </div>
</div>