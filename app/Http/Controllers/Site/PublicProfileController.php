<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
class PublicProfileController extends Controller
{
    //fetch a user public profile page
    public function fetch_profile($username){
        // fetch from users where username is profile or username is rep
        $user = User::where('username', $username)
        ->orWhere('rep', $username)
        ->where('status', "active")
        ->first();
    
        if (!$user) {
            return view('site.profile_not_found', [
                'message' => 'User not found or account is blocked'
            ]);
        }
        
        return view('site.public_profile', compact('user')); 

    }



}
