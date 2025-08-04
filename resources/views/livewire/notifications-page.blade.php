<div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
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