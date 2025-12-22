
    @extends("layouts.site")

    @section("content")

    <!-- Registration Section -->
    <section class="auth-container">
        <div class="container">
            <div class="row align-items-center justify-content-center">

                <!-- Left Column: Image -->
                <div class="col-md-6 d-none d-md-block text-center mb-4 mb-md-0">
                    <div class="auth-image-container">
                        <img src="site/images/auth_illustration.png" alt="Secure Messaging"
                            class="img-fluid rounded-4 shadow-lg auth-illustration">
                        <div class="auth-text-overlay mt-4">
                            <h2 class="fw-bold">Join the Secret.</h2>
                            <p class="text-muted">Anonymous. Encrypted. Free.</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Form -->
                <div class="col-md-6">
                    <div class="auth-card mx-auto">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">Create Account</h2>
                            <p class="text-muted">Start receiving anonymous messages.</p>
                        </div>

                        <form method="post" action="{{ route("site.register") }}">
                            @csrf
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" placeholder="John Doe" name="name" value="{{ old('name') }}" required>
                                 @error("name")
                                    <p class="alert alert-warning my-3">
                                        {{ $message }}    
                                    </p> 
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" placeholder="john@example.com" name="email" value="{{ old('email') }}" required>
                                 @error("email")
                                    <p class="alert alert-warning my-3">
                                        {{ $message }}    
                                    </p> 
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" placeholder="John Doe" name="username" value="{{ old('username') }}" required>
                                 @error("username")
                                    <p class="alert alert-warning my-3">
                                        {{ $message }}    
                                    </p> 
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" required name="password">
                                 @error("password")
                                    <p class="alert alert-warning my-3">
                                        {{ $message }}    
                                    </p> 
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" required>
                                <div id="passwordError" class="form-text text-danger d-none">Passwords do not match.
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-premium">Create Account</button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="text-muted small">Already have an account? <a href="/login"
                                        class="text-decoration-none" style="color: var(--accent);">Login</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

   @endsection