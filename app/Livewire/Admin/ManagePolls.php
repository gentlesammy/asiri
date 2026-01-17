<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use App\Models\Poll;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.site')]
class ManagePolls extends Component
{
    use WithPagination;

    public $searchPoll = '';
    public $searchUser = '';
    public $tab = 'polls'; // 'polls' or 'units'
    
    // For unit management
    public $unitAmount = 1;

    public function mount()
    {
        // Simple role check
        if (auth()->guest() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
    }

    public function deletePoll($id)
    {
        Poll::findOrFail($id)->delete();
        session()->flash('message', 'Poll deleted successfully.');
    }

    public function cancelPoll($id)
    {
        $poll = Poll::findOrFail($id);
        // Only active polls can be canceled (to differentiate from expired)
        if ($poll->status !== 'canceled') {
            $poll->update(['status' => 'canceled']);
            session()->flash('message', 'Poll has been canceled/deactivated.');
        }
    }

    public function addUnits($userId)
    {
        $user = User::findOrFail($userId);
        
        if (!$user->pollUnit) {
            $user->pollUnit()->create(['balance' => 0]);
        }

        $user->pollUnit()->increment('balance', $this->unitAmount);
        session()->flash('user_message', "Added {$this->unitAmount} units to {$user->username}.");
    }

    public function removeUnits($userId)
    {
        $user = User::findOrFail($userId);
        
        if ($user->pollUnit && $user->pollUnit->balance >= $this->unitAmount) {
            $user->pollUnit()->decrement('balance', $this->unitAmount);
            session()->flash('user_message', "Removed {$this->unitAmount} units from {$user->username}.");
        } else {
             session()->flash('user_error', "Insufficient balance.");
        }
    }

    public function render()
    {
        $polls = Poll::query()
            ->when($this->searchPoll, function($q) {
                $q->where('question', 'like', '%'.$this->searchPoll.'%')
                  ->orWhereHas('user', fn($u) => $u->where('username', 'like', '%'.$this->searchPoll.'%'));
            })
            ->with(['user', 'options'])
            ->latest()
            ->paginate(10, ['*'], 'pollsPage');

        $users = User::query()
            ->when($this->searchUser, function($q) {
                $q->where('username', 'like', '%'.$this->searchUser.'%')
                  ->orWhere('email', 'like', '%'.$this->searchUser.'%');
            })
            ->with('pollUnit')
            ->paginate(10, ['*'], 'usersPage');

        return view('livewire.admin.manage-polls', [
            'polls' => $polls,
            'users' => $users
        ]);
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
    }
}
