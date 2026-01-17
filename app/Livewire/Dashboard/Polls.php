<?php

namespace App\Livewire\Dashboard;

use Livewire\Attributes\Layout;
use App\Models\Poll;
use Illuminate\Support\Str;
use Livewire\Component;

#[Layout('layouts.site')]
class Polls extends Component
{
    public $question;
    public $options = ['', '']; // Start with 2 empty options
    public $closing_date;

    public function addOption()
    {
        if (count($this->options) < 4) {
            $this->options[] = '';
        }
    }

    public function removeOption($index)
    {
        if (count($this->options) > 2) {
            unset($this->options[$index]);
            $this->options = array_values($this->options);
        }
    }

    public function createPoll()
    {
        $this->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2|max:4',
            'options.*' => 'required|string|distinct|max:100',
            'closing_date' => 'required|date|after:now',
        ]);

        $user = auth()->user();
        
        // Ensure PollUnit exists (just in case)
        if (!$user->pollUnit) {
            $user->pollUnit()->create(['balance' => 0]);
            $user->refresh();
        }

        if ($user->pollUnit->balance < 1) {
            $this->addError('balance', 'Insufficient Poll Units. Please top up.');
            return;
        }

        // Deduct Unit
        $user->pollUnit->decrement('balance');

        // Create Poll
        $poll = $user->polls()->create([
            'question' => $this->question,
            'slug' => Str::random(10), // Short slug
            'status' => 'active',
            'closes_at' => $this->closing_date
        ]);

        // Create Options
        foreach ($this->options as $optionText) {
            $poll->options()->create(['text' => $optionText]);
        }

        $this->reset(['question', 'options', 'closing_date']);
        $this->options = ['', '']; // Reset to 2 options
        session()->flash('message', 'Poll created successfully! 1 Unit deducted.');
    }

    public function expirePoll($id)
    {
        $poll = auth()->user()->polls()->findOrFail($id);
        $poll->update(['status' => 'closed']);
        session()->flash('message', 'Poll has been expired manually.');
    }

    public function deletePoll($id)
    {
        auth()->user()->polls()->findOrFail($id)->delete();
        session()->flash('message', 'Poll deleted successfully.');
    }

    public function render()
    {
        return view('livewire.dashboard.polls', [
            'polls' => auth()->user()->polls()->with('options')->latest()->get(),
            'balance' => auth()->user()->pollUnit->balance ?? 0
        ]);
    }
}
