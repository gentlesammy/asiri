<div class="container py-5 mt-5">
    <h2 class="fw-bold text-white mb-4">Manage Polls & Units</h2>

    <!-- Tabs -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'polls' ? 'active bg-primary' : 'text-muted' }}" 
               href="#" wire:click.prevent="setTab('polls')">
               Manage Polls
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'units' ? 'active bg-primary' : 'text-muted' }}" 
               href="#" wire:click.prevent="setTab('units')">
               Manage Units
            </a>
        </li>
    </ul>

    <!-- Messages -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Polls Tab -->
    @if($tab === 'polls')
        <div class="card bg-dark border-secondary">
            <div class="card-header border-secondary">
                <input type="text" class="form-control bg-black text-white border-secondary" 
                       placeholder="Search polls by question or username..." wire:model.live.debounce.300ms="searchPoll">
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Question</th>
                                <th>User</th>
                                <th>Created</th>
                                <th>Votes</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($polls as $poll)
                                <tr>
                                    <td>{{ $poll->id }}</td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $poll->question }}">
                                            {{ $poll->question }}
                                        </div>
                                    </td>
                                    <td>{{ $poll->user->username }}</td>
                                    <td>{{ $poll->created_at->format('M d, Y') }}</td>
                                    <td>{{ $poll->options->sum('vote_count') }}</td>
                                    <td>
                                        <span class="badge {{ $poll->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($poll->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('poll.view', $poll->slug) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="ph-bold ph-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger" 
                                            wire:click="deletePoll({{ $poll->id }})"
                                            wire:confirm="Are you sure?">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                        @if($poll->status !== 'canceled')
                                            <button class="btn btn-sm btn-outline-warning" 
                                                wire:click="cancelPoll({{ $poll->id }})"
                                                wire:confirm="This will deactivate the poll irreversibly. Are you sure?">
                                                <i class="ph-bold ph-prohibit"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No polls found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $polls->links() }}
                </div>
            </div>
        </div>
    @endif

    <!-- Units Tab -->
    @if($tab === 'units')
        <div class="card bg-dark border-secondary">
             <div class="card-header border-secondary d-flex justify-content-between align-items-center">
                <input type="text" class="form-control bg-black text-white border-secondary w-50" 
                       placeholder="Search users..." wire:model.live.debounce.300ms="searchUser">
                
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">Action Amount:</span>
                    <input type="number" class="form-control bg-black text-white border-secondary form-control-sm" 
                           style="width: 80px;" wire:model="unitAmount" min="1">
                </div>
            </div>
            <div class="card-body">
                 @if (session()->has('user_message'))
                    <div class="alert alert-success">{{ session('user_message') }}</div>
                @endif
                 @if (session()->has('user_error'))
                    <div class="alert alert-danger">{{ session('user_error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Poll Units</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <img src="{{ Str::startsWith($user->dp, 'http') ? $user->dp : asset('images/users/'.$user->dp) }}" 
                                             class="rounded-circle me-2" width="30" height="30">
                                        {{ $user->username }}
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td class="fw-bold text-accent">{{ $user->pollUnit->balance ?? 0 }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1" wire:click="addUnits({{ $user->id }})">
                                            <i class="ph-bold ph-plus"></i> Add
                                        </button>
                                        <button class="btn btn-sm btn-danger" wire:click="removeUnits({{ $user->id }})">
                                            <i class="ph-bold ph-minus"></i> Remove
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
