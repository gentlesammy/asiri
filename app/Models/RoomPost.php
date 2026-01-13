<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPost extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = ['content', 'nickname', 'user_identifier', 'status'];
}
