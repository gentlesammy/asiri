<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\User;
use Illuminate\Support\Facades\File;

class UserProfile extends Component
{
    use WithFileUploads;

    public $name;
    public $username;
    public $bio;
    public $avatar;
    public $currentAvatar;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->username = $user->username;
        $this->bio = $user->bio;
        $this->currentAvatar = $user->dp;
    }

    public function updatedAvatar()
    {   
        $this->validate([
            'avatar' => 'image|max:4096', // 4MB Max
        ]);
        
        // Auto-save avatar immediately when file is selected
        $this->saveAvatar();
    }

    private function saveAvatar()
    {
        if ($this->avatar) {
            try {
                $user = auth()->user();
                
                // Generate unique filename
                $avatarName = 'asiri_' . time() . '.' . $this->avatar->getClientOriginalExtension();
                
                // Ensure directory exists
                $publicPath = public_path('images/users');
                if (!File::exists($publicPath)) {
                    File::makeDirectory($publicPath, 0755, true);
                }
                
                // Delete old avatar if exists
                if ($user->dp && File::exists(public_path('images/users/' . $user->dp))) {
                    File::delete(public_path('images/users/' . $user->dp));
                }
                
                // Save new avatar
                $sourcePath = $this->avatar->getRealPath();
                $destinationPath = $publicPath . '/' . $avatarName;
                
                // Copy file
                File::copy($sourcePath, $destinationPath);
                
                // Update database
                $user->dp = $avatarName;
                $user->save();
                
                // Update current avatar for preview
                $this->currentAvatar = $avatarName;
                $this->avatar = null;
                
                session()->flash('avatar_success', 'Profile picture updated successfully!');
                
            } catch (\Exception $e) {
                session()->flash('avatar_error', 'Failed to upload avatar: ' . $e->getMessage());
            }
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();
        $user->name = $this->name;
        $user->bio = $this->bio;
        $user->save();

        session()->flash('success', 'Profile updated successfully!');
    }

    #[Title('Edit Profile')]
    public function render()
    {
        return view('livewire.user-profile')->extends('layouts.site')->section('content');
    }
}