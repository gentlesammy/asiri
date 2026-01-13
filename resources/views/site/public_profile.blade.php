<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message to {{$user->username}} | Asiri</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('site/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('site/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('site/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('site/favicon/site.webmanifest')}}">
    <!-- social meta tags -->
    <!-- Primary Meta Tags -->
    <meta name="title" content="Send Message to {{$user->username}} | Asiri">
    <meta name="description" content="Create a personal link to receive anonymous messages from friends, followers, or anyone. No registration required for senders!">
    <meta name="keywords" content="anonymous messages, anonymous feedback, secret messages, anonymous chat, feedback tool">
    <meta name="author" content="Asiri">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{url()->current()}}">
    <meta property="og:title" content="Send Message to {{$user->username}} | Asiri">
    <meta property="og:description" content="Create a personal link to receive anonymous messages from friends, followers, or anyone. No registration required for senders!">
    <meta property="og:image" content="{{asset('site/images/auth_illustration.png')}}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Asiri">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{url()->current()}}">
    <meta property="twitter:title" content="Send Message to {{$user->username}} | Asiri">
    <meta property="twitter:description" content="Create a personal link to receive anonymous messages from friends, followers, or anyone. No registration required for senders!">
    <meta property="twitter:image" content="{{asset('site/images/auth_illustration.png')}}">
    
    <!-- Additional Meta Tags -->
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#7C3AED">
    <meta name="application-name" content="Asiri">
    
    <meta property="profile:username" content="{{ $user->username }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('site/style.css') }}">
</head>

<body>

    <!-- Simple Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container justify-content-center">
            <a class="navbar-brand" href="/">
                <i class="ph-fill ph-lock-key brand-icon"></i>
                Asiri
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="profile-hero">
        <div class="profile-avatar-container">
            {{-- if user does not have profile use default else use the profile --}}
            @if (!$user->dp)
                <img src="{{ asset('site/images/default_avatar.png') }}" alt="Profile"
                    class="profile-avatar">
            @else
                <img src="{{ asset('images/users/' . $user->dp) }}" alt="{{ $user->username }}"
                    class="profile-avatar">
            @endif
        
        </div>
    </div>

    <!-- Public Profile Content -->
    <section class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                
                <!-- Profile Header -->
                <div class="text-center mb-5 mt-4">
                    <h2 class="fw-bold mb-2">Send a secret to <span class="text-accent"> {{ucfirst($user->username) }} </span></h2>
                    <p class="text-light mx-auto" style="max-width: 400px;">
                        
                        {{ $user->bio ?? "Send Me an anonymous message" }}
                     
                    </p>
                </div>

                <!-- Message Form Card -->  
                <div class="auth-card mx-auto">
                    <!-- alert showing success message: use alert dismissible instead -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form  method="POST" action="{{ route('site.send_message', $user->username) }}">
                        @csrf
                        <div class="mb-4">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" class="form-select form-control" id="category" required>
                                <option value="" selected disabled>Select a category...</option>
                                <option value="confession">Confession 🤫</option>
                                <option value="question">Question ❓</option>
                                <option value="crush">Secret Crush 💘</option>
                                <option value="advice">Advice 💡</option>
                                <option value="compliment">Compliment ✨</option>
                                <option value="other">Other 📝</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label">Your Message</label>
                            <textarea name="message" class="form-control" id="message" rows="5"
                                placeholder="Type your secret message here..." required></textarea>
                            <div class="form-text mt-2">
                                <i class="ph-bold ph-shield-check me-1 text-success"></i>
                                100% Anonymous. Your identity is hidden.
                            </div>
                            @error('message')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-premium btn-lg">Send Secret Message</button>
                        </div>
                    </form>
                </div>

                <div class="text-center mt-5">
                    <p class="text-muted mb-3">Want to receive secret messages too?</p>
                    <a href="/register" class="btn btn-outline-glow btn-sm">Create Your Link</a>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4">
        <div class="container text-center">
            <p class="text-muted small mb-0">&copy; {{date('Y')}} Asiri App. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <!-- Custom JS -->
    <script src="{{ asset('site/script.js') }}"></script>
</body>

</html>