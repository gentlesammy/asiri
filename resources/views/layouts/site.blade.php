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
    <link rel="apple-touch-icon" sizes="180x180" href="site/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="site/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="site/favicon/favicon-16x16.png">
    <link rel="manifest" href="site/favicon/site.webmanifest">
    {{-- social media meta tags --}}
        @yield('meta_tags')
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
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How it Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="/register" class="btn btn-sm btn-premium px-4 py-2">Get Link</a>
                    </li>
                    @endguest
                    @auth
                     <li class="nav-item">
                        <a class="nav-link" href="/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/messages">Messages</a>
                    </li>
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

    @yield("content")

    <!-- Footer -->
    <footer class="py-4">
        <div class="container text-center">
            <div class="mb-3">
                <a class="navbar-brand justify-content-center" href="#">
                    <i class="ph-fill ph-lock-key brand-icon"></i>
                    Asiri
                </a>
            </div>
            <p class="text-muted small mb-0">&copy; 2024 Asiri App. All rights reserved.</p>
            <div class="mt-3">
                <a href="{{ route('site.privacy') }}" class="text-muted mx-2 small text-decoration-none">Privacy Policy</a>
                <a href="{{ route('site.terms') }}" class="text-muted mx-2 small text-decoration-none">Terms of Service</a>
                <a href="{{ route('site.about') }}" class="text-muted mx-2 small text-decoration-none">About Us</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <!-- Custom JS -->
    <script src="site/script.js"></script>
</body>

</html>