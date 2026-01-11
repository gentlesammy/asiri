<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;

class MessageController extends Controller
{
    //send message
    public function sendMessage(Request $request, $username){
        $request->validate([
            'category' => 'required',
            'message' => 'required',
        ]);
        // get the username from the url, and find the user
        $user = User::where('username', $username)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }
        
        //create a new message
        $message = new Message();
        $message->user_id = $user->id;
        $message->msg_cat = $request->category;
        $message->sender_ip = $request->ip();
        $message->content = $request->message;
        $message->save();
        
        return redirect()->back()->with('success', 'Your message has been sent to ' . $user->username);         
    }   


    //show user his message list view
    public function showMessageList(){
        return view('users.messages');
    }    
}
