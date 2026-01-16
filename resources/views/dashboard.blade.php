@extends('layouts.site')

@section('content')
        <!-- Stats Content -->
    <section class="container py-5 mt-5">
        
        @if(auth()->check() && auth()->user()->role === 'admin' && auth()->user()->status === 'active')
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card bg-dark border-secondary p-4">
                        <h4 class="fw-bold mb-3 text-accent"><i class="ph-bold ph-shield-check me-2"></i>Admin Tools</h4>
                        <div class="d-flex gap-3">
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-light">
                                <i class="ph-bold ph-users me-2"></i> Manage Users
                            </a>
                            <a href="{{ route('admin.reports') }}" class="btn btn-outline-light">
                                <i class="ph-bold ph-flag me-2"></i> Manage Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row mb-5">
            <div class="col-md-8 offset-md-2 text-center">
                <h2 class="fw-bold mb-3">My Analytics</h2>
                <p class="text-light fs-5"><strong>Share Your Link </strong> to your social media platforms and start receiving anonymous messages</p>
                <!-- Add a way user can copy their personal link -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control" value="{{url('/')}}/user/{{ Auth::user()->username}}" id="personal-link" readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="copyLink()" id="button-addon2">Copy</button>
                    <div id="copy-success" class="text-success"></div>
                    <div id="copy-error" class="text-danger"></div>

                </div>
                
                <!-- Social Share Buttons -->
                <div class="mt-4">
                    <p class="text-white-50 small mb-3">Share directly to:</p>
                    <div class="d-flex justify-content-center gap-3">
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/user/' . Auth::user()->username)) }}" 
                           target="_blank" class="btn btn-dark rounded-circle p-2 d-flex align-items-center justify-content-center" 
                           style="width: 48px; height: 48px; background-color: #1877F2; border-color: #1877F2;" title="Share on Facebook">
                            <i class="ph-bold ph-facebook-logo fs-4 text-white"></i>
                        </a>

                        <!-- X (Twitter) -->
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode('Send me anonymous messages!') }}&url={{ urlencode(url('/user/' . Auth::user()->username)) }}" 
                           target="_blank" class="btn btn-dark rounded-circle p-2 d-flex align-items-center justify-content-center" 
                           style="width: 48px; height: 48px; background-color: #000000; border-color: #333;" title="Share on X">
                            <i class="ph-bold ph-x-logo fs-4 text-white"></i>
                        </a>

                        <!-- LinkedIn -->
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url('/user/' . Auth::user()->username)) }}" 
                           target="_blank" class="btn btn-dark rounded-circle p-2 d-flex align-items-center justify-content-center" 
                           style="width: 48px; height: 48px; background-color: #0A66C2; border-color: #0A66C2;" title="Share on LinkedIn">
                            <i class="ph-bold ph-linkedin-logo fs-4 text-white"></i>
                        </a>

                        <!-- Instagram (Copy Link workaround) -->
                        <a href="#" onclick="copyLink(); alert('Link copied! Paste it in your Instagram Bio or Story.'); return false;" 
                           class="btn btn-dark rounded-circle p-2 d-flex align-items-center justify-content-center" 
                           style="width: 48px; height: 48px; background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%); border: none;" title="Copy for Instagram">
                            <i class="ph-bold ph-instagram-logo fs-4 text-white"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Card 1: Visits -->
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="ph-duotone ph-eye stats-icon text-primary"></i>
                    <div class="stats-value">{{ $uniqueVisits ?? 0 }}</div>
                    <div class="stats-label">Unique Profile Visits</div>
                </div>
            </div>

            <!-- Card 2: Messages -->
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="ph-duotone ph-envelope-open stats-icon" style="color: var(--accent);"></i>
                    <div class="stats-value"> {{ count(Auth::user()->messages)}} </div>
                    <div class="stats-label">Messages Received</div>
                </div>
            </div>

            <!-- Card 3: Repeat Senders -->
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="ph-duotone ph-users-three stats-icon text-info"></i>
                    <div class="stats-value">
                        <!-- count number of repeat senders using ip address for current logged in user  -->
                        {{ count(Auth::user()->messages->unique('ip_address')) }}


                    </div>
                    <div class="stats-label"> Senders Count </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <div class="p-3 border border-secondary rounded d-inline-block"
                    style="background: rgba(255,255,255,0.02)">
                    <p class="mb-0 text-muted small">
                        <i class="ph-fill ph-shield-check me-2 text-success"></i>
                        Sender IP addresses are analyzed securely to detect repeats but are never revealed to you.
                    </p>
                </div>
            </div>
        </div>

    </section>

@endsection