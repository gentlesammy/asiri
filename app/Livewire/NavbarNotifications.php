<?php

namespace App\Livewire;

use Livewire\Component;

class NavbarNotifications extends Component
{
    public function render()
    {
        return view('livewire.navbar-notifications', [
            'unreadCount' => auth()->check() ? auth()->user()->unreadNotifications->count() : 0
        ]);
    }
}
