<?php

namespace App\Http\Controllers\users;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function edit(Request $request)
    {
        return view('users.profile');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->bio = $request->bio;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!', );
    }   

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $avatar = $request->file('avatar');
        $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
        $avatar->move(public_path('images/users'), $avatarName);

        $user = Auth::user();
        $user->dp = $avatarName;
        $user->save();

        return response()->json([
            'success' => true,
            'avatar' => asset('images/users/' . $avatarName),
        ]);
    }


}
