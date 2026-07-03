<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\RoomPost;
use App\Models\ChatRoom;

class AdminNewRoomPostNotification extends Notification
{
    use Queueable;

    public $post;
    public $room;

    public function __construct(RoomPost $post, ChatRoom $room)
    {
        $this->post = $post;
        $this->room = $room;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'New secret post in ' . $this->room->name,
            'description' => \Illuminate\Support\Str::limit($this->post->content, 50),
            'link' => route('room.feed', $this->room->slug),
            'icon' => 'ph-lock-key',
            'color' => 'success'
        ];
    }
}
