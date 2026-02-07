
<div class="container py-5 mt-5">
    <h2 class="fw-bold text-white mb-4">Manage Chat Rooms</h2>

    <!-- Tabs -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'rooms' ? 'active bg-primary' : 'text-muted' }}" 
               href="#" wire:click.prevent="setTab('rooms')">
               Manage Rooms
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'form' ? 'active bg-primary' : 'text-muted' }}" 
               href="#" wire:click.prevent="setTab('create')">
               {{ $isEditing ? 'Edit Room' : 'Create New Room' }}
            </a>
        </li>
    </ul>

    <!-- Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Rooms Tab -->
    @if($tab === 'rooms')
        <div class="card bg-dark border-secondary">
            <div class="card-header border-secondary">
                <input type="text" class="form-control bg-black text-white border-secondary" 
                       placeholder="Search rooms by name or description..." wire:model.live.debounce.300ms="search">
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Description</th>
                                <th>Posts</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $room)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-white">{{ $room->name }}</span>
                                    </td>
                                    <td class="text-accent font-monospace small">{{ $room->slug }}</td>
                                    <td>
                                        <div class="text-truncate text-muted" style="max-width: 300px;">
                                            {{ $room->description ?? 'No description' }}
                                        </div>
                                    </td>
                                    <td>
                                         <span class="badge bg-secondary">
                                            {{ $room->posts_count }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button wire:click="edit({{ $room->id }})" class="btn btn-sm btn-outline-info me-1">
                                            <i class="ph-bold ph-pencil"></i>
                                        </button>
                                        <button wire:click="delete({{ $room->id }})" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="confirm('Are you sure you want to delete this room?') || event.stopImmediatePropagation()">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No rooms found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $rooms->links() }}
                </div>
            </div>
        </div>
    @endif

    <!-- Form Tab -->
    @if($tab === 'form')
        <div class="card bg-dark border-secondary" style="max-width: 800px;">
            <div class="card-header border-secondary">
                <h5 class="mb-0 text-white">{{ $isEditing ? 'Edit Room Details' : 'Create New Room' }}</h5>
            </div>
            <div class="card-body p-4">
                <form wire:submit.prevent="{{ $isEditing ? 'update' : 'create' }}">
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold">Room Name</label>
                        <input wire:model.live="name" type="text" class="form-control bg-black text-white border-secondary" placeholder="Enter room name">
                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold">URL Slug</label>
                        <div class="input-group">
                            <span class="input-group-text bg-secondary border-secondary text-light">/room/</span>
                            <input wire:model="slug" type="text" class="form-control bg-black text-white border-secondary" placeholder="auto-generated-slug">
                        </div>
                        @error('slug') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase fw-bold">Description</label>
                        <textarea wire:model="description" class="form-control bg-black text-white border-secondary" rows="4" placeholder="Briefly describe what this room is about..."></textarea>
                        @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="ph-bold {{ $isEditing ? 'ph-check' : 'ph-plus' }} me-2"></i>
                            {{ $isEditing ? 'Update Room' : 'Create Room' }}
                        </button>
                        <button type="button" wire:click="cancel" class="btn btn-outline-secondary px-4">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
