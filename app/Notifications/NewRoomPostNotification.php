<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\RoomPost;
use App\Models\ChatRoom;

class NewRoomPostNotification extends Notification
{
    use Queueable;

    public $post;
    public $room;

    /**
     * Create a new notification instance.
     */
    public function __construct(RoomPost $post, ChatRoom $room)
    {
        $this->post = $post;
        $this->room = $room;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
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
