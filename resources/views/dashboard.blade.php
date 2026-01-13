@extends('layouts.site')

@section('content')
        <!-- Stats Content -->
    <section class="container py-5 mt-5">
        <div class="row mb-5">
            <div class="col-8 offset-md-2 text-center">
                <h2 class="fw-bold mb-3">My Analytics</h2>
                <p class="text-light fs-5"><strong>Copy Your Link </strong> and start sharing on your social media platforms to receive messages</p>
                <!-- Add a way user can copy their personal link -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control" value="{{url('/')}}/user/{{ Auth::user()->username}}" id="personal-link" readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="copyLink()" id="button-addon2">Copy</button>
                    <div id="copy-success" class="text-success"></div>
                    <div id="copy-error" class="text-danger"></div>

                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Card 1: Visits -->
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="ph-duotone ph-eye stats-icon text-primary"></i>
                    <div class="stats-value">  0 </div>
                    <div class="stats-label">Profile Visits (coming soon)</div>
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
                    <div class="stats-label"> Senders</div>
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