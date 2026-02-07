<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-white mb-0">Notifications</h2>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <button wire:click="markAllAsRead" class="btn btn-sm btn-outline-light">
                        <i class="ph-bold ph-checks me-1"></i> Mark All Read
                    </button>
                @endif
            </div>

            <div class="card bg-dark border-secondary shadow-lg overflow-hidden">
                <div class="list-group list-group-flush">
                    @forelse($notifications as $notification)
                        <div class="list-group-item bg-dark border-secondary p-4 {{ $notification->read_at ? 'opacity-75' : 'border-start border-primary border-4' }}">
                            <div class="d-flex gap-3">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-dark-subtle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="ph-duotone {{ $notification->data['icon'] ?? 'ph-bell' }} fs-4 text-{{ $notification->data['color'] ?? 'primary' }}"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h5 class="mb-1 text-white {{ $notification->read_at ? '' : 'fw-bold' }}">
                                            {{ $notification->data['message'] ?? 'New Notification' }}
                                        </h5>
                                        <small class="text-white-50">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-2 text-white-50 small">
                                        {{ $notification->data['description'] ?? '' }}
                                    </p>
                                    <div class="d-flex gap-2">
                                        @if(isset($notification->data['link']))
                                            <a href="{{ $notification->data['link'] }}" class="btn btn-sm btn-primary">
                                                View
                                            </a>
                                        @endif
                                        @if(!$notification->read_at)
                                            <button wire:click="markAsRead('{{ $notification->id }}')" class="btn btn-sm btn-outline-secondary">
                                                Mark as Read
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="mb-3 text-white-50 opacity-25">
                                <i class="ph-duotone ph-bell-slash" style="font-size: 4rem;"></i>
                            </div>
                            <p class="text-white-50 fs-5">No notifications yet.</p>
                        </div>
                    @endforelse
                </div>
                <div class="card-footer bg-dark border-secondary p-3">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
