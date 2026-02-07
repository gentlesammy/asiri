<?php

namespace App\Livewire;

use Livewire\Component;

class SiteNavbarNotifications extends Component
{
    public function render()
    {
        return view('livewire.site-navbar-notifications', [
            'unreadCount' => auth()->check() ? auth()->user()->unreadNotifications->count() : 0
        ]);
    }
}
