<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    //set fillables
    protected $fillable = [
        'user_id',
        'category',
        'is_flagged',
        'message',
    ];
    //relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
