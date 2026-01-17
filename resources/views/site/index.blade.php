
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
    <section class="hero-section container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="hero-title">
                   Someone Somewhere...<br>
                    <span class="glow-text">has something to tell you 👀</span>
                </h1>
                <p class="hero-subtitle">
                   Create your link and start receiving anonymous messages from anyone, anywhere.
                </p>
                <div class="d-flex justify-content-center flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="btn btn-premium">Start Receiving Messages</a>
                    <a href="#how-it-works" class="btn btn-outline-glow">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="container py-5">
        <div class="row g-4">
            <!-- Feature 1 -->
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="ph-duotone ph-mask-happy feature-icon"></i>
                    <h3 class="feature-title">100% Anonymous</h3>
                    <p class="feature-desc">
                        We never reveal the identity of the sender. Receive honest feedback without the fear of
                        judgment.
                    </p>
                </div>
            </div>
            <!-- Feature 2 -->
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="ph-duotone ph-shield-check feature-icon"></i>
                    <h3 class="feature-title">Secure & Private</h3>
                    <p class="feature-desc">
                        Messages are encrypted and stored securely. Your privacy is our top priority.
                    </p>
                </div>
            </div>
            <!-- Feature 3 -->
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="ph-duotone ph-paper-plane-tilt feature-icon"></i>
                    <h3 class="feature-title">Easy Sharing</h3>
                    <p class="feature-desc">
                        Get your unique link instantly and share it on Instagram, Snapchat, or WhatsApp with one click.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="container py-5 my-5">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-6 text-center">
                <h2 class="section-title fw-bold display-5">How It Works</h2>
                <p class="text-muted">Simple steps to get started in seconds.</p>
            </div>
        </div>

        <div class="row g-4 align-items-center">
            <div class="col-md-4">
                <div class="step-card p-4">
                    <span class="step-number">01</span>
                    <h4 class="mt-4 fw-bold">Create Account</h4>
                    <p class="text-muted">Register with your username to generate your personal inbox.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card p-4">
                    <span class="step-number">02</span>
                    <h4 class="mt-4 fw-bold">Share Link</h4>
                    <p class="text-muted">Post your Asiri link on your social media stories or bios.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card p-4">
                    <span class="step-number">03</span>
                    <h4 class="mt-4 fw-bold">Read Secrets</h4>
                    <p class="text-muted">Open your inbox and see what people really think about you.</p>
                </div>
            </div>
        </div>
    </section>

    @endsection
   