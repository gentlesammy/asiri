<div class="container py-5 mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">User <span class="text-accent">Management</span></h2>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm me-2">Back to Dashboard</a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card bg-dark border-secondary mb-4 p-3">
        <div class="row g-3">
            <div class="col-md-6">
                <input wire:model.live.debounce.300ms="search" type="text" class="form-control bg-black text-light border-secondary" placeholder="Search by name, email, or username...">
            </div>
            <div class="col-md-3">
                 <select wire:model.live="filterStatus" class="form-select bg-black text-light border-secondary">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="blocked">Blocked</option>
                    <option value="deleted">Deleted</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card bg-dark border-secondary">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th wire:click="sortBy('name')" role="button">Name <i class="ph-bold ph-arrows-down-up small ms-1"></i></th>
                        <th wire:click="sortBy('username')" role="button">Username <i class="ph-bold ph-arrows-down-up small ms-1"></i></th>
                        <th wire:click="sortBy('email')" role="button">Email</th>
                        <th wire:click="sortBy('messages_count')" role="button" class="text-center">Messages <i class="ph-bold ph-arrows-down-up small ms-1"></i></th>
                        <th wire:click="sortBy('status')" role="button" class="text-center">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; overflow: hidden;">
                                        @if($user->dp)
                                            <img src="{{ asset('storage/' . $user->dp) }}" alt="{{ $user->name }}" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <span>{{ substr($user->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary rounded-pill">{{ $user->messages_count }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge 
                                    @if($user->status === 'active') bg-success 
                                    @elseif($user->status === 'blocked') bg-danger 
                                    @elseif($user->status === 'inactive') bg-warning text-dark
                                    @else bg-secondary @endif">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Manage
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark">
                                        <li><button wire:click="updateStatus({{ $user->id }}, 'active')" class="dropdown-item text-success"><i class="ph-bold ph-check-circle me-2"></i> Activate</button></li>
                                        <li><button wire:click="updateStatus({{ $user->id }}, 'inactive')" class="dropdown-item text-warning"><i class="ph-bold ph-pause-circle me-2"></i> Deactivate</button></li>
                                        <li><button wire:click="updateStatus({{ $user->id }}, 'blocked')" class="dropdown-item text-danger"><i class="ph-bold ph-prohibit me-2"></i> Block</button></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><button wire:click="updateStatus({{ $user->id }}, 'deleted')" class="dropdown-item text-muted"><i class="ph-bold ph-trash me-2"></i> Mark Deleted</button></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-dark border-secondary">
             {{ $users->links() }}
        </div>
    </div>
</div>
