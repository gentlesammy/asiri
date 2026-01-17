<?php

namespace App\Livewire;

use Livewire\Component;

use Livewire\Attributes\Layout;

#[Layout('layouts.site')]
class Message extends Component
{
    public $filter = 'all';
    public $selectedMessage = null;

    public function mount()
    {
        // Optionally load the first message or leave empty
    }

    public function getMessagesProperty()
    {
        $query = \App\Models\Message::where('user_id', auth()->id())
            ->latest();

        if ($this->filter === 'read') {
            $query->where('status', 'read');
        } elseif ($this->filter === 'unread') {
            $query->where('status', 'unread');
        }

        return $query->get();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->selectedMessage = null; // Deselect when changing filter
    }

    public function selectMessage($id)
    {
        $message = \App\Models\Message::where('user_id', auth()->id())->find($id);
        
        if ($message) {
            $this->selectedMessage = $message;
            
            if ($message->status === 'unread') {
                $message->update(['status' => 'read']);
            }
            
            $this->dispatch('messageSelected');
        }
    }

    public function reportMessage()
    {
        if ($this->selectedMessage) {
            $this->selectedMessage->update(['reported_status' => 'reported']);
            // Refresh the selected message instance
            $this->selectedMessage->refresh(); 
            // Optional: notify the user
            $this->dispatch('messageReported'); 
        }
    }

    public function render()
    {
        return view('livewire.message', [
            'messages' => $this->messages
        ]);
    }
}
