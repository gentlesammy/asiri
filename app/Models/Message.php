<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'msg_cat',
        'content',
        'status',
        'reported_status',
        'is_flagged',
        'sender_ip',
    ];

    //relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
