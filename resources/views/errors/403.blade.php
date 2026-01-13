@extends('layouts.site')

@section('content')
<div class="container d-flex flex-column align-items-center justify-content-center min-vh-100 text-center py-5 mt-5">
    <div class="mb-4 position-relative">
        <img src="{{ asset('site/images/error_403.png') }}" alt="403 Forbidden" class="img-fluid rounded-4 border border-secondary" style="max-height: 400px; box-shadow: 0 0 30px rgba(0, 245, 212, 0.4);">
    </div>
    <h1 class="display-2 fw-bold mb-3 hero-title" style="font-size: 3rem;">Access <span class="text-accent">Forbidden</span></h1>
    <p class="text-light fs-5 mb-5" style="max-width: 600px;">
        Sorry, you don't have permission to access this area. This zone is restricted.
    </p>
    <a href="{{ url('/') }}" class="btn btn-premium px-5">
        <i class="ph-bold ph-house me-2"></i> Return Home
    </a>
</div>
@endsection
