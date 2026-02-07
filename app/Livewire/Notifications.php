<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class Notifications extends Component
{
    use WithPagination;

    #[Title('Notifications | Asiri')]
    public function render()
    {
        $notifications = auth()->user()->notifications()->paginate(10);
        
        return view('livewire.notifications', [
            'notifications' => $notifications
        ])->layout('layouts.site');
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        session()->flash('success', 'All notifications marked as read.');
    }
}
