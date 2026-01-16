<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileVisit extends Model
{
    /**
     * Disable default timestamps (using custom visited_at field)
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'visitor_ip',
        'visitor_user_id',
        'user_agent',
        'visited_at',
    ];

    /**
     * Get the user whose profile was visited
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the visitor (if authenticated)
     */
    public function visitor()
    {
        return $this->belongsTo(User::class, 'visitor_user_id');
    }
}
