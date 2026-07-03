<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Message;

class AdminMessageReportedNotification extends Notification
{
    use Queueable;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Message reported',
            'description' => 'A message has been reported and requires review.',
            'link' => route('admin.reports'),
            'icon' => 'ph-warning',
            'color' => 'danger'
        ];
    }
}
