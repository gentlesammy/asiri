<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\User;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $filterStatus = '';

    protected $queryString = ['search', 'sortField', 'sortDirection', 'filterStatus'];

    public function updateStatus($userId, $status)
    {
        $user = User::findOrFail($userId);
        
        // Prevent changing own status if admin
        if ($user->id === auth()->id()) {
             session()->flash('error', 'You cannot change your own status.');
             return;
        }

        $user->update(['status' => $status]);
        session()->flash('success', "User status updated to {$status}.");
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }


    #[Title('Admin | User Management')]
    public function render()
    {
        $users = User::query()
            ->withCount('messages')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('username', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->sortField === 'messages_count', function ($query) {
                $query->orderBy('messages_count', $this->sortDirection);
            }, function ($query) {
                // Default sort
                 if ($this->sortField !== 'messages_count') {
                    $query->orderBy($this->sortField, $this->sortDirection);
                 }
            })
            ->paginate(10);

        return view('livewire.admin.user-list', [
            'users' => $users
        ])->extends('layouts.site')->section('content');
    }
}
