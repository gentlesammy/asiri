@extends('layouts.site')

@section('content')

<!-- Profile Hero -->
<div class="profile-hero">
    <div class="profile-avatar-container">
        <div class="position-relative d-inline-block">
            <!-- if current logged in user dp column is empty use the default image else use his dp to be stored in public/images/users  -->
            @if (Auth::user()->dp == null)
                <img src="{{ asset('site/images/default_avatar.png') }}" alt="Profile" class="profile-avatar" id="avatarPreview">
            @else
                <img src="{{ asset('images/users/' . Auth::user()->dp) }}" alt="Profile" class="profile-avatar" id="avatarPreview">
            @endif
            <label for="avatarUpload" class="btn btn-sm btn-premium position-absolute bottom-0 end-0 rounded-circle p-2"
                style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                <i class="ph-bold ph-pencil-simple text-white"></i>
            </label>

            <input type="file" id="avatarUpload" class="d-none" accept="image/*" onchange="updateAvatar()">
        </div>
    </div>
</div>

<!-- Profile Form Section -->
<section class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="auth-card mx-auto mt-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Edit Profile</h3>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form id="profileForm" action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" value="{{ Auth::user()->username }}" readonly
                            style="opacity: 0.7; cursor: not-allowed;">
                    </div>
                    <div class="mb-3">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" name="name" value="{{ Auth::user()->name }}">
                    </div>
                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio (Message to visitors)</label>
                        <textarea class="form-control" id="bio" name="bio" rows="4"
                            placeholder="Send me something honest!">{{ Auth::user()->bio }}</textarea>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-premium">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection