<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'created_by'];

    public function posts()
    {
        return $this->hasMany(RoomPost::class);
    }
}
