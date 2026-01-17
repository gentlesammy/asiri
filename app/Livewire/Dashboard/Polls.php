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
            'status' => 'active'
        ]);

        // Create Options
        foreach ($this->options as $optionText) {
            $poll->options()->create(['text' => $optionText]);
        }

        $this->reset(['question', 'options']);
        $this->options = ['', '']; // Reset to 2 options
        session()->flash('message', 'Poll created successfully! 1 Unit deducted.');
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
