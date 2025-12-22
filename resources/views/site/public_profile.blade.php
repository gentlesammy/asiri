<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message to John Doe | Asiri</title>
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
                <img src="{{ asset('storage/profile_avatars/' . $user->dp) }}" alt="Profile"
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
                    <form id="sendMessageForm">
                        <div class="mb-4">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select form-control" id="category" required>
                                <option value="" selected disabled>Select a category...</option>
                                <option value="confession">Confession 🤫</option>
                                <option value="question">Question ❓</option>
                                <option value="crush">Secret Crush 💘</option>
                                <option value="advice">Advice 💡</option>
                                <option value="compliment">Compliment ✨</option>
                                <option value="other">Other 📝</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label">Your Message</label>
                            <textarea class="form-control" id="message" rows="5"
                                placeholder="Type your secret message here..." required></textarea>
                            <div class="form-text mt-2">
                                <i class="ph-bold ph-shield-check me-1 text-success"></i>
                                100% Anonymous. Your identity is hidden.
                            </div>
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
            <p class="text-muted small mb-0">&copy; 2024 Asiri App. All rights reserved.</p>
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