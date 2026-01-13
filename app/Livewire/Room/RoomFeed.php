<?php

namespace App\Livewire\Room;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Cookie;
use App\Services\IdentityGenerator;
use App\Models\RoomPost;
use Illuminate\Support\Str;

class RoomFeed extends Component
{

    public $content;
    public $nickname;
    public $userIdentifier;

    #[Title('Anonymous Room | Asiri')]
    public function mount()
    {
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

        // Rate Limiting: 5 posts per day per user_identifier
        $count = RoomPost::where('user_identifier', $this->userIdentifier)
            ->whereDate('created_at', now()->today())
            ->count();

        if ($count >= 5) {
            $this->addError('content', 'You have reached the daily limit of 5 secrets.');
            return;
        }

        RoomPost::create([
            'content' => $this->content,
            'nickname' => $this->nickname,
            'user_identifier' => $this->userIdentifier,
        ]);

        $this->content = '';
        session()->flash('success', 'Secret shared anonymously!');
    }

    public function deletePost($postId)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'moderator'])) {
            return;
        }

        $post = RoomPost::findOrFail($postId);
        $post->update(['status' => 'deleted']);
        
        session()->flash('success', 'Post deleted successfully.');
    }

    public function render()
    {
        return view('livewire.room.room-feed', [
            'posts' => RoomPost::where('status', 'active')->latest()->get()
        ])->extends('layouts.site')->section('content');
    }
}
