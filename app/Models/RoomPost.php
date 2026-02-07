<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPost extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = ['content', 'nickname', 'user_identifier', 'status', 'chat_room_id'];

    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }
}
