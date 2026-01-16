<div>
    <div class="profile-hero">
            <div class="profile-avatar-container">
                    <div class="position-relative d-inline-block">
                        <!-- Avatar Display logic -->
                        @if ($avatar)
                            <!-- Temporary Preview -->
                            <img src="{{ $avatar->temporaryUrl() }}" alt="Profile Preview" class="profile-avatar object-fit-cover" wire:key="preview-{{ $avatar->getClientOriginalName() }}">
                        @elseif ($currentAvatar)
                            <!-- Current Avatar -->
                            <img src="{{ asset('images/users/' . $currentAvatar) }}?v={{ time() }}" alt="Profile" class="profile-avatar object-fit-cover" wire:key="avatar-{{ $currentAvatar }}">
                        @else
                            <!-- Default Avatar -->
                            <img src="{{ asset('site/images/default_avatar.png') }}" alt="Default Profile" class="profile-avatar object-fit-cover" wire:key="default-avatar">
                        @endif

                        <label for="avatarUpload" class="btn btn-sm btn-premium position-absolute bottom-0 end-0 rounded-circle p-2"
                            style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                            <i class="ph-bold ph-pencil-simple text-white" wire:loading.remove wire:target="avatar"></i>
                            <div wire:loading wire:target="avatar" class="spinner-border spinner-border-sm text-white" role="status"></div>
                        </label>

                        <input wire:model="avatar" type="file" id="avatarUpload" class="d-none" accept="image/*">
                    </div>
                    
                    @error('avatar') 
                        <span class="d-block text-danger small mt-2">{{ $message }}</span> 
                    @enderror
                    
                    @if (session('avatar_success'))
                        <span class="d-block text-success small mt-2">{{ session('avatar_success') }}</span>
                    @endif
                    
                    @if (session('avatar_error'))
                        <span class="d-block text-danger small mt-2">{{ session('avatar_error') }}</span>
                    @endif
                </div>
    </div>

    <div>
        <!-- Profile Form Section -->
        <section class="container py-5 mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                      @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    <div class="auth-card mx-auto mt-4">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">Edit Profile</h3>
                        </div>

                        <form wire:submit="save">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" value="{{ $username }}" readonly
                                    style="opacity: 0.7; cursor: not-allowed;">
                            </div>
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror" id="fullName">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio (Message to visitors)</label>
                                <textarea wire:model="bio" class="form-control @error('bio') is-invalid @enderror" id="bio" rows="4"
                                    placeholder="Send me something honest!"></textarea>
                                @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-premium" wire:loading.attr="disabled">
                                    <span wire:loading.remove>Save Changes</span>
                                    <span wire:loading>Saving...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
