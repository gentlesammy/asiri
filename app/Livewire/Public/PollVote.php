<?php

namespace App\Livewire\Public;

use Livewire\Attributes\Layout;
use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

#[Layout('layouts.site')]
class PollVote extends Component
{
    public Poll $poll;
    public $hasVoted = false;
    public $totalVotes = 0;

    public function mount($slug)
    {
        $this->poll = Poll::where('slug', $slug)
            ->with(['options', 'user'])
            ->firstOrFail();

        $this->checkIfVoted();
        $this->calculateTotalVotes();
    }

    public function checkIfVoted()
    {
        $cookieName = 'poll_voted_' . $this->poll->id;

        // Check Cookie
        if (Cookie::has($cookieName)) {
            $this->hasVoted = true;
            return;
        }

        // Check IP (Fallback/Stricter check)
        $ip = Request::ip();
        $exists = $this->poll->votes()->where('ip_address', $ip)->exists();

        if ($exists) {
            $this->hasVoted = true;
        }
    }

    public function calculateTotalVotes()
    {
        $this->totalVotes = $this->poll->options->sum('vote_count');
    }

    public function vote($optionId)
    {
        if ($this->hasVoted) {
            return;
        }

        $option = $this->poll->options()->find($optionId);

        if (!$option) {
            return;
        }

        // Double check voting status
        $this->checkIfVoted();
        if ($this->hasVoted) {
             return;
        }

        // Record Vote
        $this->poll->votes()->create([
            'option_id' => $option->id,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'cookie_id' => Cookie::get('laravel_session') ?? 'unknown'
        ]);

        // Increment Count
        $option->increment('vote_count');
        
        // Refresh State
        $this->hasVoted = true;
        $this->poll->refresh();
        $this->calculateTotalVotes();

        // Set Cookie (1 Year)
        Cookie::queue('poll_voted_' . $this->poll->id, true, 60 * 24 * 365);
    }

    public function render()
    {
        return view('livewire.public.poll-vote');
    }
}
