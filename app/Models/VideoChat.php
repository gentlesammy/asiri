<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class VideoChat extends Model
{
    use HasFactory, HasUuids;

    public const VALID_STATUSES = ['waiting', 'active', 'ended'];

    protected $fillable = ['host_id', 'guest_id', 'status'];

    protected $casts = [
        'host_id' => 'integer',
        'guest_id' => 'integer',
    ];

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function guest()
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    public function signals()
    {
        return $this->hasMany(VideoChatSignal::class, 'video_chat_id');
    }
}
