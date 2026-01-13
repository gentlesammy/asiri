@extends('layouts.site')

@section('content')
<div class="container d-flex flex-column align-items-center justify-content-center min-vh-100 text-center py-5 mt-5">
    <div class="mb-4 position-relative">
        <img src="{{ asset('site/images/error_500.png') }}" alt="500 Server Error" class="img-fluid rounded-4 border border-secondary" style="max-height: 400px; box-shadow: 0 0 30px rgba(220, 53, 69, 0.4);">
    </div>
    <h1 class="display-2 fw-bold mb-3 hero-title" style="font-size: 3rem;">Server <span class="text-danger">Error</span></h1>
    <p class="text-light fs-5 mb-5" style="max-width: 600px;">
        Something went wrong on our end. Our systems are currently experiencing a glitch. Please try again later.
    </p>
    <!-- No home link as per requirements for server error -->
</div>
@endsection
