<div class="container py-5" style="margin-top: 80px;">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold gradient-text mb-3">
            Anonymous Chat Rooms
        </h1>
        <p class="lead text-muted">
            Join a room and start sharing your secrets anonymously.
        </p>
    </div>

    <div class="row g-4">
        @foreach($rooms as $room)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('room.feed', ['room' => $room->slug]) }}" class="text-decoration-none text-reset">
                    <div class="card h-100 border-0 shadow-sm hover-lift transition-all">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h2 class="h4 card-title fw-bold mb-0 text-dark">
                                    {{ $room->name }}
                                </h2>
                                <span class="badge bg-primary-subtle text-primary rounded-pill">
                                    {{ $room->posts_count }} Posts
                                </span>
                            </div>
                            <p class="card-text text-muted mb-4 flex-grow-1">
                                {{ $room->description ?? 'No description available for this room.' }}
                            </p>
                        </div>
                        <div class="card-footer bg-light border-0 p-3">
                            <div class="d-flex align-items-center justify-content-between text-primary fw-semibold small">
                                <span>Join Room</span>
                                <i class="ph-bold ph-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
