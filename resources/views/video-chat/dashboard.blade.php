@extends('layouts.site')

@section('content')
<section class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center mb-5">
            <h2 class="fw-bold mb-3">Video Chat Hub</h2>
            <p class="text-light fs-5">Launch a video call and invite friends to chat in real-time.</p>
        </div>
    </div>

    @if (session('error'))
        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                <div class="alert alert-danger border-secondary bg-dark text-danger rounded-3 p-3 d-flex align-items-center gap-2">
                    <i class="ph-bold ph-warning-circle fs-4"></i>
                    <div>{{ session('error') }}</div>
                </div>
            </div>
        </div>
    @endif

    <div class="row justify-content-center g-4">
        <!-- Launch Call Card -->
        <div class="col-md-6">
            <div class="feature-card text-center d-flex flex-column justify-content-between p-5 h-100">
                <div>
                    <i class="ph-bold ph-video-camera feature-icon text-accent fs-1"></i>
                    <h3 class="fw-bold mb-3 mt-2">Start a New Call</h3>
                    <p class="text-white-50 mb-4">Create a private, encrypted video room instantly. You'll get an invitation link to share with anyone you want to join.</p>
                </div>
                <div>
                    <form action="{{ route('video-chat.launch') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-premium w-100 py-3">
                            <i class="ph-bold ph-paper-plane-tilt me-2"></i> Launch Video Room
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Invitation Link Card -->
        <div class="col-md-6">
            <div class="feature-card text-center d-flex flex-column justify-content-between p-5 h-100">
                <div>
                    <i class="ph-bold ph-share-network feature-icon text-primary fs-1"></i>
                    <h3 class="fw-bold mb-3 mt-2">Your Invitation Link</h3>
                    <p class="text-white-50 mb-4">Share your personal link. Any logged-in user who clicks this link will be redirected to join your active video call session.</p>
                </div>
                <div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" value="{{ route('video-chat.join', Auth::user()->username) }}" id="invite-link" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyInviteLink()" id="btn-copy-invite">Copy</button>
                    </div>
                    <div id="copy-status" class="text-accent small" style="min-height: 20px;"></div>
                </div>
            </div>
        </div>
    </div>

    @if ($activeRooms->count() > 0)
        <div class="row justify-content-center mt-5">
            <div class="col-md-12">
                <div class="card bg-dark border-secondary p-4 rounded-4" style="background: rgba(255, 255, 255, 0.02); backdrop-filter: blur(10px);">
                    <h4 class="fw-bold mb-4 text-accent"><i class="ph-bold ph-activity me-2"></i>Active Call Sessions</h4>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Host</th>
                                    <th>Guest</th>
                                    <th>Status</th>
                                    <th>Started</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activeRooms as $room)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $room->host->name }}</span>
                                            <span class="text-muted small">({{ $room->host->username }})</span>
                                        </td>
                                        <td>
                                            @if ($room->guest)
                                                <span>{{ $room->guest->name }}</span>
                                                <span class="text-muted small">({{ $room->guest->username }})</span>
                                            @else
                                                <span class="text-warning small"><i class="ph-bold ph-spinner spinner me-1"></i> Waiting for guest...</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $room->status === 'active' ? 'bg-success' : 'bg-warning' }} px-3 py-2 text-capitalize">
                                                {{ $room->status }}
                                            </span>
                                        </td>
                                        <td>{{ $room->created_at->diffForHumans() }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('video-chat.room', $room->id) }}" class="btn btn-sm btn-outline-glow py-2 px-4">
                                                Re-join
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>

<script>
function copyInviteLink() {
    const inviteInput = document.getElementById('invite-link');
    inviteInput.select();
    inviteInput.setSelectionRange(0, 99999); // For mobile devices

    navigator.clipboard.writeText(inviteInput.value).then(() => {
        const status = document.getElementById('copy-status');
        status.innerHTML = '<i class="ph-bold ph-check-circle me-1"></i> Link copied to clipboard!';
        setTimeout(() => {
            status.innerHTML = '';
        }, 3000);
    }).catch(err => {
        console.error('Failed to copy text: ', err);
        const status = document.getElementById('copy-status');
        status.innerHTML = '<span class="text-danger">Failed to copy link. Please manually select and copy.</span>';
    });
}
</script>

<style>
.spinner {
    animation: spin 2s linear infinite;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection
