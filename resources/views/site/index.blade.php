@extends("layouts.site")
    @section('meta_tags')
        <!-- Primary Meta Tags -->
        <meta name="title" content="@yield('meta_title', 'Asiri - Receive Anonymous Messages')">
        <meta name="description" content="@yield('meta_description', 'Create a personal link to receive anonymous messages from friends, followers, or anyone. No registration required for senders!')">
        <meta name="keywords" content="@yield('meta_keywords', 'anonymous messages, anonymous feedback, secret messages, anonymous chat, feedback tool')">
        <meta name="author" content="Your Company Name">
        
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="@yield('og_type', 'website')">
        <meta property="og:url" content="@yield('og_url', url()->current())">
        <meta property="og:title" content="@yield('og_title', 'Asiri - Get Anonymous Messages')">
        <meta property="og:description" content="@yield('og_description', 'Create your personal Asiri to receive honest, anonymous messages from anyone. Perfect for feedback, confessions, or fun Q&A!')">
        <meta property="og:image" content="@yield('og_image', asset('site/images/auth_illustration.png'))">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:site_name" content="Asiri">
        
        <!-- Twitter -->
        <meta property="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
        <meta property="twitter:url" content="@yield('twitter_url', url()->current())">
        <meta property="twitter:title" content="@yield('twitter_title', 'Asiri - Receive Anonymous Messages')">
        <meta property="twitter:description" content="@yield('twitter_description', 'Get honest feedback with your personal anonymous messaging link. Free & easy to use!')">
        <meta property="twitter:image" content="@yield('twitter_image', asset('site/images/auth_illustration.png'))">
        
        <!-- Additional Meta Tags -->
        <meta name="robots" content="index, follow">
        <meta name="theme-color" content="#7C3AED">
        <meta name="application-name" content="Asiri">
        
        <!-- For User Profile Pages (Dynamic) -->
        @if(isset($user) && $user instanceof \App\Models\User)
        <meta property="profile:username" content="{{ $user->username }}">
        @endif
    @endsection

    @section("content")

    <!-- Hero Section -->
    <section class="hero-section container text-center py-5">
        <div class="row justify-content-center py-5">
            <div class="col-lg-10 py-5">
                <span class="badge bg-secondary text-accent mb-3 rounded-pill px-3 py-2 border border-secondary">
                    <i class="ph-bold ph-star me-1"></i> New: Anonymous Polls & Rooms
                </span>
                <h1 class="hero-title display-3 fw-bold mb-4">
                    Express Yourself,<br>
                    <span class="glow-text text-primary">Anonymously.</span>
                </h1>
                <p class="hero-subtitle lead text-white-50 mb-5 mx-auto" style="max-width: 700px;">
                   The ultimate platform for secret messages, public confessions, and honest opinions. 
                   Join thousands of users sharing their thoughts freely.
                </p>
                <div class="d-flex justify-content-center flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="btn btn-premium px-4 py-2">Get Started</a>
                    <a href="{{ route('room.feed') }}" class="btn btn-outline-glow px-4 py-2">
                        <i class="ph-bold ph-chats-circle me-2"></i> Visit The Room
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Features Section -->
    <section id="features" class="container py-5">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-6 text-center">
                <h2 class="section-title fw-bold display-5">Features</h2>
                <p class="text-white-50">What makes Asiri unique and special.</p>
            </div>
        </div>
        <div class="row g-4">
            <!-- Feature 1: Messages -->
            <div class="col-md-4">
                <div class="card bg-dark border-secondary h-100 hover-lift transition-all">
                    <div class="card-body p-4 text-center">
                        <div class="mb-4 d-inline-block p-3 rounded-circle bg-primary bg-opacity-10 border border-primary">
                            <i class="ph-duotone ph-envelope-simple text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h4 fw-bold text-white mb-3">Anonymous Messages</h3>
                        <p class="text-white-50 mb-4">
                            Create your personal inbox. Share your link on social media. Receive honest feedback, confessions, and secrets privately.
                        </p>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-outline-primary w-100">Create Inbox</a>
                    </div>
                </div>
            </div>
            
            <!-- Feature 2: The Room -->
            <div class="col-md-4">
                <div class="card bg-dark border-secondary h-100 hover-lift transition-all">
                    <div class="card-body p-4 text-center">
                        <div class="mb-4 d-inline-block p-3 rounded-circle bg-success bg-opacity-10 border border-success">
                            <i class="ph-duotone ph-users-three text-success" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h4 fw-bold text-white mb-3">The Room</h3>
                        <p class="text-white-50 mb-4">
                            Step into the public square. Share your thoughts with the community or read what others are saying anonymously in a public feed.
                        </p>
                        <a href="{{ route('room.feed') }}" class="btn btn-sm btn-outline-success w-100">Join the Conversation</a>
                    </div>
                </div>
            </div>

            <!-- Feature 3: Polls -->
            <div class="col-md-4">
                <div class="card bg-dark border-secondary h-100 hover-lift transition-all">
                    <div class="card-body p-4 text-center">
                        <div class="mb-4 d-inline-block p-3 rounded-circle bg-warning bg-opacity-10 border border-warning">
                            <i class="ph-duotone ph-chart-bar text-warning" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h4 fw-bold text-white mb-3">Anonymous Polls</h3>
                        <p class="text-white-50 mb-4">
                            Settling a debate? Create an anonymous poll and let the crowd vote without bias. Get honest statistics instantly.
                        </p>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-outline-warning w-100">Create a Poll</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="container py-5 my-5">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-6 text-center">
                <h2 class="section-title fw-bold display-5">How It Works</h2>
                <p class="text-white-50">Simple steps to get started in seconds.</p>
            </div>
        </div>

        <div class="row g-4 align-items-center">
            <div class="col-md-4">
                <div class="step-card p-4">
                    <span class="step-number">01</span>
                    <h4 class="mt-4 fw-bold">Create Account</h4>
                    <p class="text-white-50">Register with your username to generate your personal inbox.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card p-4">
                    <span class="step-number">02</span>
                    <h4 class="mt-4 fw-bold">Share Link</h4>
                    <p class="text-white-50">Post your Asiri link on your social media stories or bios.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card p-4">
                    <span class="step-number">03</span>
                    <h4 class="mt-4 fw-bold">Read Secrets</h4>
                    <p class="text-white-50">Open your inbox and see what people really think about you.</p>
                </div>
            </div>
        </div>
    </section>

    @endsection
   