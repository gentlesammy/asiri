@extends('layouts.site')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="auth-card p-5">
                <h2 class="fw-bold mb-4 text-center">Anonymous Room <span class="text-accent">Rules</span></h2>
                
                <div class="mb-4">
                    <h5 class="text-white mb-2">1. Anonymity & Identity</h5>
                    <p class="text-muted text-justify">
                        You are assigned a random pseudonym (e.g., "Neon Tiger") based on your session. This identity allows you to post without revealing your real name. However, your device/network is tracked to prevent abuse.
                    </p>
                </div>

                <div class="mb-4">
                    <h5 class="text-white mb-2">2. Ephemeral Content</h5>
                    <p class="text-muted text-justify">
                        All posts in the Anonymous Room are <strong>automatically deleted every day at 12:00 PM</strong>. We do not archive or store these posts permanently.
                    </p>
                </div>

                <div class="mb-4">
                    <h5 class="text-white mb-2">3. Rate Limiting</h5>
                    <p class="text-muted text-justify">
                        To maintain quality and prevent spam, users are limited to sharing a maximum of <strong>5 secrets per day</strong>.
                    </p>
                </div>

                <div class="mb-4">
                    <h5 class="text-white mb-2">4. Prohibited Content</h5>
                    <p class="text-muted text-justify">
                        Do not post hate speech, threats, doxxing (revealing private info), illegal content, or graphic violence. We reserve the right to ban users who violate these terms.
                    </p>
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('room.feed') }}" class="btn btn-premium px-5">
                        <i class="ph-bold ph-arrow-left me-2"></i> Enter Room
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
