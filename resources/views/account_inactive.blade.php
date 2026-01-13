@extends('layouts.site')

@section('content')
<div class="container d-flex flex-column align-items-center justify-content-center min-vh-100 text-center py-5 mt-5">
    <div class="mb-4 position-relative">
        <div class="rounded-circle bg-danger bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
            <i class="ph-fill ph-user-minus text-danger" style="font-size: 4rem;"></i>
        </div>
    </div>
    <h1 class="display-4 fw-bold mb-3 hero-title">Account <span class="text-danger">Inactive</span></h1>
    <p class="text-light fs-5 mb-5" style="max-width: 600px;">
        Your account is currently <strong>not active</strong>, <strong>deleted</strong>, or <strong>suspended</strong>. 
        <br>
        Please contact support if you believe this is a mistake.
    </p>
    
    <div class="d-flex gap-3">
        <a href="{{ url('/') }}" class="btn btn-outline-light px-4">
            <i class="ph-bold ph-house me-2"></i> Home
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-premium px-4">
                <i class="ph-bold ph-sign-out me-2"></i> Logout
            </button>
        </form>
    </div>
</div>
@endsection
