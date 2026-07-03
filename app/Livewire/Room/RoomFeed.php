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
use App\Notifications\Admin\AdminNewRoomPostNotification;
use App\Models\User;

class RoomFeed extends Component
{

    public $content;
    public $nickname;
    public $userIdentifier;
    public $room; // Hold the ChatRoom model
    public $isFollowing = false;

    public function mount($room = 'general')
    {
        // Resolve the room from the slug
        $this->room = ChatRoom::where('slug', $room)->firstOrFail();

        // Check if authenticated user is following
        if (auth()->check()) {
            $this->isFollowing = auth()->user()->followedRooms()->where('chat_room_id', $this->room->id)->exists();
        }

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

    public function toggleFollow()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if ($this->isFollowing) {
            auth()->user()->followedRooms()->detach($this->room->id);
            $this->isFollowing = false;
        } else {
            auth()->user()->followedRooms()->attach($this->room->id);
            $this->isFollowing = true;
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

        $post = RoomPost::create([
            'content' => $this->content,
            'nickname' => $this->nickname,
            'user_identifier' => $this->userIdentifier,
            'chat_room_id' => $this->room->id,
        ]);

        // Notify followers (exclude current user if logged in)
        $followers = $this->room->followers();
        if (auth()->check()) {
            $followers->where('user_id', '!=', auth()->id());
        }
        
        $followers->each(function ($user) use ($post) {
            $user->notify(new \App\Notifications\NewRoomPostNotification($post, $this->room));
        });

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new AdminNewRoomPostNotification($post, $this->room));
        }

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
