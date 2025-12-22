    @extends("layouts.site")

    @section("content")

    <!-- Login Section -->
    <section class="auth-container">
        <div class="container">
            <div class="row align-items-center justify-content-center">

                <!-- Left Column: Image -->
                <div class="col-md-6 d-none d-md-block text-center mb-4 mb-md-0">
                    <div class="auth-image-container">
                        <img src="/site/images/auth_illustration.png" alt="Secure Messaging"
                            class="img-fluid rounded-4 shadow-lg auth-illustration">
                        <div class="auth-text-overlay mt-4">
                            <h2 class="fw-bold">Welcome Back.</h2>
                            <p class="text-muted">Your secrets are waiting.</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Form -->
                <div class="col-md-6">
                    <div class="auth-card mx-auto">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">Login</h2>
                            <p class="text-muted">Access your anonymous messages.</p>
                        </div>

                        <form method="post" action="{{ route('site.login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="john@example.com"
                                    required>
                                @error("email")
                                    <p class="alert alert-warning my-3">
                                        {{ $message }}    
                                    </p> 
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password" required>
                                 @error("password")
                                    <p class="alert alert-warning my-3">
                                        {{ $message }}    
                                    </p> 
                                @enderror
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-premium">Login</button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="text-muted small">Don't have an account? <a href="/register"
                                        class="text-decoration-none" style="color: var(--accent);">Create Account</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    @endsection