<div>
    @php
        $pageTitle = "Vote: " . Str::limit($poll->question, 50) . " - Asiri Polls";
        $pageDesc = "Vote on this poll by @" . $poll->user->username . " on Asiri.com.ng. Anonymous, secure, and fun!";
        $pageUrl = route('poll.view', $poll->slug);
    @endphp

    @section('meta_title', $pageTitle)
    @section('meta_description', $pageDesc)
    
    @section('og_url', $pageUrl)
    @section('og_title', $pageTitle)
    @section('og_description', $pageDesc)
    
    @section('twitter_url', $pageUrl)
    @section('twitter_title', $pageTitle)
    @section('twitter_description', $pageDesc)

    <section class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                
                <!-- Actions Logic -->
                @if($poll->status === 'canceled')
                     <div class="card bg-danger bg-opacity-10 border-danger text-center p-5 mb-4">
                        <div class="mb-3">
                             <i class="ph-duotone ph-prohibit text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <h3 class="fw-bold text-danger">Poll Deactivated</h3>
                        <p class="text-white-50">This poll has been deactivated by the administrator.</p>
                    </div>
                @else

                <!-- Poll Card / Capture Area -->
                <div id="pollCaptureArea" class="card bg-dark border-secondary position-relative overflow-hidden mb-4">
                    <!-- Background decoration (optional) -->
                    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" 
                        style="background: radial-gradient(circle at top right, var(--primary), transparent 40%); pointer-events: none;">
                    </div>

                    <div class="card-body p-4 p-md-5 position-relative z-1">
                        
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-accent" style="font-family: 'Outfit', sans-serif;">Asiri Polls</h3>
                            <small class="text-muted">
                                Asked by <span class="text-white">{{ '@' . $poll->user->username }}</span>
                            </small>
                        </div>

                         @if($poll->status === 'closed')
                             <div class="alert alert-warning text-center border-warning bg-warning bg-opacity-10 mb-4">
                                 <i class="ph-bold ph-warning-circle me-1"></i>
                                 <strong>Poll Ended:</strong> Voting is closed. These are the final results.
                             </div>
                        @endif

                        <!-- Question -->
                        <div class="text-center mb-5">
                            <h2 class="fw-light text-white">{{ $poll->question }}</h2>
                        </div>

                        <!-- Options -->
                        <div class="d-flex flex-column gap-3">
                            @foreach($poll->options as $option)
                                @php
                                    $percent = $totalVotes > 0 ? round(($option->vote_count / $totalVotes) * 100) : 0;
                                @endphp

                                @if(!$hasVoted && $poll->status === 'active')
                                    <!-- Voting Button -->
                                    <button wire:click="vote({{ $option->id }})" 
                                            class="btn btn-outline-light text-start py-3 px-4 rounded-3 d-flex justify-content-between align-items-center position-relative overflow-hidden group-hover-bg"
                                            wire:loading.attr="disabled">
                                        <span class="z-1">{{ $option->text }}</span>
                                        <i class="ph-bold ph-arrow-right opacity-0 group-hover-opacity transition-all"></i>
                                    </button>
                                @else
                                    <!-- Result Bar -->
                                    <div class="position-relative w-100 rounded-3 overflow-hidden bg-black border border-secondary" style="height: 50px;">
                                        <!-- Progress Bar -->
                                        <div class="position-absolute top-0 start-0 h-100 bg-primary opacity-25" 
                                             style="width: {{ $percent }}%; transition: width 1s ease-in-out;"></div>
                                        
                                        <!-- Content -->
                                        <div class="position-relative z-1 h-100 d-flex justify-content-between align-items-center px-4">
                                            <span class="fw-bold text-white">{{ $option->text }}</span>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="small text-muted">{{ $option->vote_count }} votes</span>
                                                <span class="fw-bold text-accent">{{ $percent }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Footer -->
                        <div class="text-center mt-5 opacity-50">
                            <small>asiri.com.ng</small> - <small>{{ $totalVotes }} total votes</small>
                        </div>

                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="d-flex justify-content-center gap-3">
                    @if($hasVoted)
                        <button id="downloadBtn" class="btn btn-premium px-4" onclick="capturePoll()">
                            <i class="ph-bold ph-share-network me-2"></i> Convert to Image
                            <span id="downloadLoader" class="spinner-border spinner-border-sm ms-2 d-none"></span>
                        </button>
                    @endif
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">Create Your Own Poll</a>
                </div>

            </div>
        </div>
    </section>

    <!-- html2canvas -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
        function capturePoll() {
            const btn = document.getElementById('downloadBtn');
            const loader = document.getElementById('downloadLoader');
            
            if(!btn) return;
            
            btn.disabled = true;
            loader.classList.remove('d-none');

            const element = document.getElementById('pollCaptureArea');
            html2canvas(element, {
                backgroundColor: '#1a1a1a',
                scale: 2,
                useCORS: true
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'asiri-poll-{{ $poll->slug }}.png';
                link.href = canvas.toDataURL();
                link.click();
            }).finally(() => {
                btn.disabled = false;
                loader.classList.add('d-none');
            });
        }
    </script>
</div>
