<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;

class AdminNewUserJoinedNotification extends Notification
{
    use Queueable;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'New user joined: ' . $this->user->name,
            'description' => 'A new user has registered with username @' . $this->user->username,
            'link' => route('admin.users'), // Assuming this route exists and leads to user management
            'icon' => 'ph-user-plus',
            'color' => 'info'
        ];
    }
}
