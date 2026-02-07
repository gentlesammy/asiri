<div class="container py-5" style="margin-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <!-- Header -->
            <div class="text-center mb-5">
                <div class="d-flex align-items-center justify-content-center gap-3 mb-2">
                    <h2 class="display-5 fw-bold gradient-text mb-0">
                        {{ $room->name }} Room
                    </h2>
                    @auth
                        <button wire:click="toggleFollow" class="btn btn-sm {{ $isFollowing ? 'btn-outline-danger' : 'btn-outline-primary' }} rounded-pill px-3">
                            <i class="ph-bold {{ $isFollowing ? 'ph-bell-slash' : 'ph-bell' }} me-1"></i>
                            {{ $isFollowing ? 'Unfollow' : 'Follow' }}
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="tooltip" title="Login to follow this room">
                            <i class="ph-bold ph-bell me-1"></i> Follow
                        </a>
                    @endauth
                </div>
                <div class="d-inline-block px-4 py-2 rounded-pill bg-light border shadow-sm">
                    <span class="small text-muted">You are posting as: <strong class="text-primary ms-1">{{ $nickname }}</strong></span>
                </div>
                <div class="mt-3">
                     <a href="{{ route('room.list') }}" class="text-decoration-none text-muted small hover-primary d-inline-flex align-items-center">
                        <i class="ph-bold ph-arrow-left me-1"></i>
                        Back to Rooms
                     </a>
                </div>
            </div>

            <!-- Post Input Card -->
            <div class="card border-0 shadow-lg mb-5 rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                            <i class="ph-fill ph-check-circle me-2 fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif
                    
                    <form wire:submit.prevent="post">
                        <div class="mb-3">
                            <textarea wire:model="content" class="form-control form-control-lg bg-light border-0" rows="3" 
                                placeholder="Share a secret anonymously in {{ $room->name }}... (Max 5 posts/day)" required maxlength="500" style="resize: none;"></textarea>
                            @error('content') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small><a href="{{ route('room.terms') }}" class="text-muted text-decoration-none hover-underline">Rules & Terms</a></small>
                            <button type="submit" class="btn btn-premium px-4 py-2 d-flex align-items-center gap-2">
                                <span wire:loading.remove wire:target="post">Post Secret</span>
                                <span wire:loading wire:target="post">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Posting...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Feed -->
            <div class="d-flex flex-column gap-4">
                @forelse($posts as $post)
                    <div class="card border-0 shadow-sm rounded-4 hover-shadow transition-all">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-placeholder rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 45px; height: 45px;">
                                        {{ substr($post->nickname, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark">{{ $post->nickname }}</span>
                                        <span class="small text-muted">{{ $post->created_at->diffForHumans(null, true, true) }}</span>
                                    </div>
                                </div>
                                @auth
                                    @if(in_array(auth()->user()->role, ['admin', 'moderator']))
                                        <button wire:click="deletePost({{ $post->id }})" 
                                                wire:confirm="Are you sure you want to delete this post?"
                                                class="btn btn-link text-danger p-0 border-0" title="Delete Post">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    @endif
                                @endauth
                            </div>
                            <p class="card-text fs-5 text-dark lh-base font-monospace-secondary">{{ $post->content }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="mb-3 text-muted opacity-25">
                            <i class="ph-duotone ph-chat-teardrop-text" style="font-size: 4rem;"></i>
                        </div>
                        <p class="text-muted fs-5">It's quiet in here... be the first to share a secret.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-5 text-muted small">
                 <i class="ph-fill ph-shield-check me-1"></i>
                 Posts disappear safely.
            </div>
        </div>
    </div>
</div>
