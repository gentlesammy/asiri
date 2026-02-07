<?php

namespace App\Livewire\Room;

use Livewire\Component;
use App\Models\ChatRoom;
use Livewire\Attributes\Title;

class RoomList extends Component
{
    #[Title('Chat Rooms | Asiri')]
    public function render()
    {
        return view('livewire.room.room-list', [
            'rooms' => ChatRoom::withCount('posts')->latest()->get()
        ])->extends('layouts.site')->section('content');
    }
}
