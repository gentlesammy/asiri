<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoChatSignal extends Model
{
    use HasFactory;

    protected $fillable = ['video_chat_id', 'sender_id', 'type', 'payload'];

    public function videoChat()
    {
        return $this->belongsTo(VideoChat::class, 'video_chat_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
