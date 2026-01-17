<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asiri | Anonymous Messaging</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/site/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/site/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/site/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/site/favicon/favicon-16x16.png">
    <link rel="manifest" href="/site/favicon/site.webmanifest">
    <!-- Primary Meta Tags -->
    <meta name="title" content="@yield('meta_title', 'Asiri - Receive Anonymous Messages')">
    <meta name="description" content="@yield('meta_description', 'Create a personal link to receive anonymous messages from friends, followers, or anyone. No registration required for senders!')">
    <meta name="keywords" content="@yield('meta_keywords', 'anonymous messages, anonymous feedback, secret messages, anonymous chat, feedback tool')">
    <meta name="author" content="Asiri">

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
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:url" content="@yield('twitter_url', url()->current())">
    <meta name="twitter:title" content="@yield('twitter_title', 'Asiri - Receive Anonymous Messages')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Get honest feedback with your personal anonymous messaging link. Free & easy to use!')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('site/images/auth_illustration.png'))">

    <!-- Additional -->
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#7C3AED">
    <meta name="application-name" content="Asiri">

    <!-- User profile (optional) -->
    @isset($user)
        <meta property="profile:username" content="{{ $user->username }}">
    @endisset
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="ph-fill ph-lock-key brand-icon"></i>
                Asiri
            </a>
            <button class="navbar-toggler navbar-dark" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center gap-3">
                   
                    @guest
                     <li class="nav-item">
                        <a class="nav-link" href="/#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#how-it-works">How it Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="/register" class="btn btn-sm btn-premium px-4 py-2">Get Link</a>
                    </li>
                    @endguest
                    <li class="nav-item">
                        <a href="/room" class="nav-link">Enter Room</a>
                    </li>
                    @auth
                     <li class="nav-item">
                        <a class="nav-link" href="/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard/messages">Messages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard/polls">Polls</a>
                    </li>
                    @if(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/polls">Admin</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="/profile">Profile</a>
                    </li>
                    <li class="nav-item">
                        <form action="/logout" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-premium px-4 py-2">Logout</button>
                        </form>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @if(isset($slot))
        {{ $slot }}
    @else
        @yield("content")
    @endif

    <!-- Footer -->
    <footer class="py-4">
        <div class="container text-center">
            <div class="mb-3">
                <a class="navbar-brand justify-content-center" href="#">
                    <i class="ph-fill ph-lock-key brand-icon"></i>
                    Asiri
                </a>
            </div>
            <p class="text-white-50 small mb-0">&copy; {{date('Y')}} Asiri App. All rights reserved.</p>
            <div class="mt-3">
                <a href="{{ route('site.privacy') }}" class="text-white-50 mx-2 small text-decoration-none">Privacy Policy</a>
                <a href="{{ route('site.terms') }}" class="text-white-50 mx-2 small text-decoration-none">Terms of Service</a>
                <a href="{{ route('site.about') }}" class="text-white-50 mx-2 small text-decoration-none">About Us</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <!-- Custom JS -->
    <script src="/site/script.js"></script>
</body>

</html>