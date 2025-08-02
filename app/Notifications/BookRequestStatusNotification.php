<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class BookRequestStatusNotification extends Notification
{
    // use Queueable;

    public $bookRequest;
    public $status;
    public $message;

    public function __construct($bookRequest, $status, $message = null)
    {
        $this->bookRequest = $bookRequest;
        $this->status = $status;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'book_request_id' => $this->bookRequest->id,
            'status' => $this->status,
            'message' => $this->message,
            'book_title' => $this->bookRequest->book->title ?? null,
            'requested_by' => $this->bookRequest->name,
            'requested_by_email' => $this->bookRequest->email,
        ];
    }

    public function toDatabase($notifiable)
    {
        return $this->toArray($notifiable);
    }

    // public function toBroadcast($notifiable)
    // {
    //     return new BroadcastMessage($this->toArray($notifiable));
    // }
}
