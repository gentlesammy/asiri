<div class="container py-5 mt-5">
    <div class="row g-4">
        <!-- Sidebar placeholder or just main content since layout takes care of nav -->
        
        <!-- Main Content -->
        <div class="col-lg-8 mx-auto">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-white">My Polls</h2>
                <div class="bg-dark p-2 px-3 rounded-pill border border-secondary">
                    <span class="text-muted small">Available Units:</span>
                    <span class="fw-bold text-accent ms-1">{{ $balance }}</span>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @error('balance')
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @enderror

            <!-- Create Poll Card -->
            <div class="card bg-dark border-secondary mb-5">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold text-white mb-3">Create New Poll</h5>
                    
                    <form wire:submit.prevent="createPoll">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Question</label>
                            <input type="text" class="form-control bg-black text-white border-secondary" 
                                wire:model="question" placeholder="e.g. Who is the best artist?">
                            @error('question') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                         <div class="mb-3">
                            <label class="form-label text-muted small">Closing Date</label>
                            <input type="datetime-local" class="form-control bg-black text-white border-secondary" 
                                wire:model="closing_date" min="{{ now()->format('Y-m-d\TH:i') }}">
                            @error('closing_date') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <label class="form-label text-muted small">Options ({{ count($options) }}/4)</label>
                        @foreach($options as $index => $option)
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-secondary border-secondary text-white">{{ $index + 1 }}</span>
                                <input type="text" class="form-control bg-black text-white border-secondary" 
                                    wire:model="options.{{ $index }}" placeholder="Option {{ $index + 1 }}">
                                
                                @if(count($options) > 2)
                                    <button class="btn btn-outline-danger" type="button" wire:click="removeOption({{ $index }})">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                @endif
                            </div>
                            @error('options.'.$index) <span class="text-danger small">{{ $message }}</span> @enderror
                        @endforeach

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            @if(count($options) < 4)
                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="addOption">
                                    <i class="ph-bold ph-plus"></i> Add Option
                                </button>
                            @else
                                <div></div>
                            @endif

                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>Create Poll (1 Unit)</span>
                                <span wire:loading class="spinner-border spinner-border-sm" role="status"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Polls List -->
            @forelse($polls as $poll)
                <div class="card bg-dark border-secondary mb-3">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="fw-bold text-white mb-2">{{ $poll->question }}</h5>
                                    @if($poll->status === 'active')
                                         <span class="badge bg-success">Active</span>
                                    @elseif($poll->status === 'closed')
                                         <span class="badge bg-warning text-dark">Expired</span>
                                    @elseif($poll->status === 'canceled')
                                         <span class="badge bg-danger">Canceled by Admin</span>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    @foreach($poll->options as $option)
                                        <span class="badge bg-secondary me-1 mb-1 fw-normal">{{ $option->text }} ({{ $option->vote_count }})</span>
                                    @endforeach
                                </div>
                                
                                <div class="d-flex gap-2 align-items-center flex-wrap">
                                    @if($poll->status === 'active' || $poll->status === 'closed')
                                        <a href="{{ route('poll.view', $poll->slug) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="ph-bold ph-eye"></i> View
                                        </a>
                                    @endif

                                    @if($poll->status === 'active')
                                        <button class="btn btn-sm btn-outline-warning" 
                                            wire:click="expirePoll({{ $poll->id }})"
                                            wire:confirm="This will stop voting immediately. Cannot be undone.">
                                            <i class="ph-bold ph-clock"></i> Expire
                                        </button>
                                    @endif

                                    <button class="btn btn-sm btn-outline-danger ms-auto" 
                                        wire:click="deletePoll({{ $poll->id }})"
                                        wire:confirm="Are you sure you want to delete this poll?">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 text-end d-flex justify-content-between">
                            @if($poll->closes_at)
                                <small class="text-muted" style="font-size: 0.7rem;">Closes: {{ $poll->closes_at->format('M d, Y H:i') }}</small>
                            @else
                                <div></div>
                            @endif
                            <small class="text-muted" style="font-size: 0.7rem;">Created {{ $poll->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="ph-duotone ph-chart-bar text-secondary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-white">No Polls Created</h5>
                    <p class="text-muted">Create your first poll above to get started.</p>
                </div>
            @endforelse

        </div>
    </div>
</div>
