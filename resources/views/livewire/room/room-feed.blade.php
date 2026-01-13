<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <!-- Feed Column -->
        <div class="col-lg-6 col-md-8">
            
            <!-- Header -->
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-2">Anonymous <span class="text-accent">Room</span></h2>
                <div class="d-inline-block px-3 py-1 rounded-pill bg-dark border border-secondary">
                    <small class="text-light">You are posting as: <strong class="text-accent">{{ $nickname }}</strong></small>
                </div>
            </div>

            <!-- Post Input Card -->
            <div class="auth-card mb-5">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form wire:submit.prevent="post">
                    <div class="mb-3">
                        <textarea wire:model="content" class="form-control" rows="3" 
                            placeholder="Share a secret anonymously... (Max 5 posts/day)" required maxlength="500"></textarea>
                        @error('content') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted"><a href="{{ route('room.terms') }}" class="text-decoration-none text-muted">Rules & Terms</a></small>
                        <button type="submit" class="btn btn-premium btn-sm px-4">
                            <span wire:loading.remove wire:target="post">Post Secret</span>
                            <span wire:loading wire:target="post" class="spinner-border spinner-border-sm" role="status"></span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Feed -->
            <div class="d-flex flex-column gap-3">
                @forelse($posts as $post)
                    <div class="message-card pointer-event-none cursor-default" style="cursor: default;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center text-light fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ substr($post->nickname, 0, 1) }}
                                </div>
                                <span class="fw-bold text-light small">{{ $post->nickname }}</span>
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ $post->created_at->diffForHumans(null, true, true) }}</small>
                        </div>
                        <p class="mb-0 text-light">{{ $post->content }}</p>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="ph-duotone ph-ghost text-muted fs-1 mb-3"></i>
                        <p class="text-muted">It's quiet in here... be the first to share a secret.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-5 text-muted small">
                 <i class="ph-fill ph-clock-counter-clockwise me-1"></i> Posts disappear daily at 12:00 PM
            </div>

        </div>
    </div>
</div>
