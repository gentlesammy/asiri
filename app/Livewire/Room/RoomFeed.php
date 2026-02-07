<?php

namespace App\Livewire\Room;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Cookie;
use App\Services\IdentityGenerator;
use App\Models\RoomPost;
use App\Models\ChatRoom;
use Illuminate\Support\Str;

class RoomFeed extends Component
{

    public $content;
    public $nickname;
    public $userIdentifier;
    public $room; // Hold the ChatRoom model

    public function mount($room = 'general')
    {
        // Resolve the room from the slug
        $this->room = ChatRoom::where('slug', $room)->firstOrFail();

        // Handle Identity
        $this->userIdentifier = Cookie::get('room_user_id');
        $this->nickname = Cookie::get('room_nickname');

        if (!$this->userIdentifier || !$this->nickname) {
            $this->userIdentifier = (string) Str::uuid();
            $this->nickname = IdentityGenerator::generate();
            
            Cookie::queue('room_user_id', $this->userIdentifier, 60 * 24 * 365); // 1 year
            Cookie::queue('room_nickname', $this->nickname, 60 * 24 * 365);
        }
    }

    public function post()
    {
        $this->validate([
            'content' => 'required|min:3|max:500',
        ]);

        // Rate Limiting: 5 posts per day per user_identifier IN THIS ROOM
        $count = RoomPost::where('user_identifier', $this->userIdentifier)
            ->where('chat_room_id', $this->room->id)
            ->whereDate('created_at', now()->today())
            ->count();

        if ($count >= 5) {
            $this->addError('content', 'You have reached the daily limit of 5 secrets for this room.');
            return;
        }

        RoomPost::create([
            'content' => $this->content,
            'nickname' => $this->nickname,
            'user_identifier' => $this->userIdentifier,
            'chat_room_id' => $this->room->id,
        ]);

        $this->content = '';
        session()->flash('success', 'Secret shared anonymously!');
    }

    public function deletePost($postId)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'moderator'])) {
            return;
        }

        $post = RoomPost::where('chat_room_id', $this->room->id)->findOrFail($postId);
        $post->update(['status' => 'deleted']);
        
        session()->flash('success', 'Post deleted successfully.');
    }

    public function render()
    {
        return view('livewire.room.room-feed', [
            'posts' => RoomPost::where('status', 'active')
                        ->where('chat_room_id', $this->room->id)
                        ->latest()
                        ->get()
        ])->extends('layouts.site')
          ->section('content')
          ->title($this->room->name . ' | Anonymous Room | Asiri');
    }
}
