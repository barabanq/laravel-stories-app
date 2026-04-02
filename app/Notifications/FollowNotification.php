<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class FollowNotification extends Notification
{
    use Queueable;

    public $follower;

    public function __construct($follower)
    {
        $this->follower = $follower;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->follower->id,
            'type' => 'follow',
            'message' => $this->follower->name . ' подписался на вас',
        ];
    }
}