<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationsPage extends Component
{
    public $notifications = [];

    public function mount()
    {
        $user = Auth::user();
        $this->notifications = $user ? $user->notifications()->latest()->take(30)->get() : collect();
    }

    public function markAsRead($notificationId)
    {
        $user = Auth::user();
        if ($user) {
            $notification = $user->notifications()->find($notificationId);
            if ($notification) {
                $notification->markAsRead();
                $this->notifications = $user->notifications()->latest()->take(30)->get();
            }
        }
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        if ($user) {
            $user->unreadNotifications()->update(['read_at' => now()]);
            $this->notifications = $user->notifications()->latest()->take(30)->get();
        }
    }

    public function render()
    {
        return view('livewire.notifications-page');
    }
}