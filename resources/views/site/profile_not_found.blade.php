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

    <!-- Error Content -->
    <section class="container error-container text-center">
        <div class="row">
        
            <div class="col-md-8 mx-auto py-5">
                <img src="{{ asset('site/images/bear_search.png') }}" alt="Bear Searching" class="bear-image img-fluid pb-5">

                <h1 class="fw-bold mb-3 d-none">404</h1>
                <h2 class="fw-bold mb-4">Profile Not Found</h2>
                <p class="text-light fs-5 mx-auto mb-5" style="max-width: 500px;">
                    The profile you are looking for has either been deactivated or does not exist.
                </p>

                <a href="/" class="btn btn-premium btn-lg">
                    <i class="ph-bold ph-house me-2"></i> Go Home
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 fixed-bottom">
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