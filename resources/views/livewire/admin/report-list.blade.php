<div class="container py-5 mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Reported <span class="text-accent">Messages</span></h2>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm me-2">Back to Dashboard</a>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="card bg-dark border-secondary">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>User (Recipient)</th>
                        <th>Message Content</th>
                        <th>Sender IP</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>
                                <span class="d-block text-white small">{{ $report->created_at->format('M d, Y') }}</span>
                                <small class="text-muted">{{ $report->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                @if($report->user)
                                    <div class="d-flex align-items-center">
                                        {{ $report->user->name }}
                                        <small class="text-muted ms-1">({{ $report->user->username }})</small>
                                    </div>
                                @else
                                    <span class="text-muted">Deleted User</span>
                                @endif
                            </td>
                            <td style="max-width: 400px;">
                                <div class="p-2 border border-secondary rounded bg-black bg-opacity-25 mb-1 text-break">
                                    {{ $report->content }}
                                </div>
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 small">Reported</span>
                            </td>
                            <td>
                                <span class="font-monospace text-muted small">{{ $report->sender_ip ?? 'Unknown' }}</span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button wire:click="dismissReport('{{ $report->id }}')" class="btn btn-sm btn-outline-secondary" title="Dismiss Report">
                                        <i class="ph-bold ph-check"></i> Dismiss
                                    </button>
                                    <button wire:click="deleteMessage('{{ $report->id }}')" 
                                            wire:confirm="Are you sure you want to delete this message?"
                                            class="btn btn-sm btn-outline-danger" title="Delete Message">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                    <button wire:click="banIp('{{ $report->id }}')" 
                                            wire:confirm="Are you sure you want to BAN this IP? This will also delete the message."
                                            class="btn btn-sm btn-danger px-3" title="Ban Sender IP">
                                        <i class="ph-bold ph-prohibit me-1"></i> Ban IP
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="ph-duotone ph-check-circle fs-1 mb-3 text-success"></i>
                                <p class="mb-0">No reported messages found. Good job!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-dark border-secondary">
             {{ $reports->links() }}
        </div>
    </div>
</div>
