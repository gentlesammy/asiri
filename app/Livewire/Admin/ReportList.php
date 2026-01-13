<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Message;
use App\Models\BlockedIp;

class ReportList extends Component
{
    use WithPagination;


    #[Title('Admin | Report Management')]
    public function render()
    {
        $reports = Message::where('reported_status', true)
            ->with(['user'])
            ->latest()
            ->paginate(10);

        return view('livewire.admin.report-list', [
            'reports' => $reports
        ])->extends('layouts.site')->section('content');
    }

    public function dismissReport($messageId)
    {
        $message = Message::findOrFail($messageId);
        $message->update(['reported_status' => false]);
        session()->flash('success', 'Report dismissed.');
    }

    public function deleteMessage($messageId)
    {
        $message = Message::findOrFail($messageId);
        $message->delete();
        session()->flash('success', 'Message deleted.');
    }

    public function banIp($messageId)
    {
        $message = Message::findOrFail($messageId);
        
        if (!$message->sender_ip) {
            session()->flash('error', 'No IP address associated with this message.');
            return;
        }

        BlockedIp::firstOrCreate(
            ['ip_address' => $message->sender_ip],
            ['reason' => 'Banned from admin panel via report (Message ID: ' . $messageId . ')']
        );

        $message->delete(); // Optionally delete message after banning
        session()->flash('success', 'IP banned and message deleted.');
    }
}
